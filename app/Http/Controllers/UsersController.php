<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\User;

use Auth;
use Mail;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', [
                'only' => ['edit', 'update', 'destroy','followings', 'followers'],
        ]);

        $this->middleware('guest', [
                'only' => ['create'],
        ]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function show($id)
    {
        $user = User::findorFail($id);
        $statuses = $user->statuses()
                        ->orderBy('created_at','desc')
                        ->paginate(10);
        return view('users.show', compact('user','statuses'));
    }

    public function store(Request $request)
    {
        // var_dump($request->all());
        // exit;
        $this->validate($request, [
            'name'=>'required|max:50',
            'email'=>'required|email|unique:users|max:255',
            'password'=>'required',
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $this->sendEmailConfirmationTo($user);
        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
        return redirect('/');
    }

    public function edit($id)
    {
        $user = User::findorFail($id);
        $this->authorize('update', $user);

        return view('users.edit',compact('user'));
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'confirmed|min:6',
        ]);

        $user = User::findorFail($id);
        $this->authorize('update', $user);

        $data = [];
        $data['name'] = $request->name;
        if($request->password){
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        session()->flash('success','个人资料更新成功！');
        return redirect()->route('users.show',$id);
    }

    /**
     * 列出所有的用户信息
     */
    public function index()
    {
        $users = User::paginate(30);

        return view('users.index',compact('users'));
    }

    /**
     * 删除用户
     * @param $id 用户ID
     */
    public function destroy($id)
    {
        $user = User::findorFail($id);
        $this->authorize('destroy', $user); //加载UserPolicy的destroy
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }

    /**
     * 发邮件
     */
    protected function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $from = 'liyn2007@qq.com';
        $name = 'Gavin';
        $to = $user->email;
        $subject = "感谢注册 Sample 应用！请确认你的邮箱。";

        Mail::send($view, $data, function($message) use ($from, $name, $to, $subject){
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }

    /**
     * 激活邮件
     */
    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();
        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', $user);
    }

    /**
     * 我关注的人
     */
    public function followings($id)
    {
        $user = User::findOrFail($id);
        $users = $user->followings()->paginate(30);
        $title = '关注的人';
        return view('users.show_follow', compact('users', 'title'));
    }

    /**
     * 粉丝列表
     */
    public function followers($id)
    {
        $user = User::findOrFail($id);
        $users = $user->followers()->paginate(30);
        $title = '粉丝';
        return view('users.show_follow', compact('users', 'title'));
    }
}
