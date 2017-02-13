<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Status;
use Auth;

class StatusesController extends Controller
{
    public function __contruct()
    {
        $this->middleware('auth', [
            'only' => ['destroy','store'],
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|max:140',
        ]);

        Auth::user()->statuses()->create([
            'content' => $request->content,
        ]);

        session()->flash('success','微博发布成功！');
        return back();
    }

    /**
     * 删除微博数据
     */
    public function destroy($id)
    {
        $status = Status::findorFail($id);
        $this->authorize('destroy', $status);

        $status->delete();
        session()->flash('success', '微博已被成功删除！');
        return back();
    }
}
