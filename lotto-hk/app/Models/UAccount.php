<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UAccount extends Model
{
    //
    public function login()
    {
        return $this->hasMany(UAccountLogin::class, 'account_id', 'id');
    }

    /**
     * 最后登录
     * @return mixed
     */
    public function lastLogin()
    {
        return $this->login()->orderBy('updated_at', 'desc')->first();
    }

    public function lastLoginTime()
    {
        $lastLogin = $this->lastLogin();
        if (isset($lastLogin)) {
            return $lastLogin->updated_at;
        }
        return "";
    }
}
