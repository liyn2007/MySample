<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = ['content'];
    /**
     * 指明一对一关联
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
