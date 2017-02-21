<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function gravatar($size = '100')
    {
        return "http://wx.qlogo.cn/mmopen/sGyfZt1iauRSxuJhpZGcHtqqCE31nWiafzkRvPzTfCcpWKjct0KT8ty3WLcO4Taia8TXibSK1KXR8QmOIssO37cu1POx3YZuFaiaQ/0";
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    // public function setPasswordAttribute($password)
    // {
    //     $this->attributes['password'] = bcrypt($password);
    // }

    /**
     * boot方法会在用户模型类完成初始化之后进行加载
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function($user){
            $user->activation_token = str_random(30);
        });
    }

    /**
     * 指明一对多关系
     */
    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    /**
     * 获取用户微博信息
     */
    public function feed()
    {
        // return $this->statuses()->orderBy('created_at', 'desc');
        $user_ids = Auth::user()->followings->pluck('id')->toArray();
        array_push($user_ids, Auth::user->id);

        return Status::whereIn('user_id', $user_ids)
                        ->with('user')
                        ->orderBy('created_at', 'desc');
    }

    /**
     *  定义一个用户可以有多个粉丝
     *
     *  获取粉丝关系列表
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    /**
     *  关注某个用户
     */
    public function followings()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }

    /**
     * 关注粉丝
     */
    public function follow($user_ids)
    {
        if(!is_array($user_ids)){
            $user_ids = compact('user_ids');
        }

        $this->followings()->sync($user_ids, false);
    }

    /**
     * 取消关注
     */
    public function unfollow($user_ids)
    {
        if(!is_array($user_ids)){
            $user_ids = compact('user_ids');
        }
        $this->followings()->detach($user_ids);
    }

    /**
     * 判断被关注
     */
    public function isFollowing($user_id)
    {
        return $this->followings->contains($user_id);
    }
}
