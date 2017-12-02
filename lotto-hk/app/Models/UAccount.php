<?php

namespace App\Models;

class UAccount extends LModel
{
    public function merchant()
    {
        return $this->hasOne(UMerchantAccount::class, 'id', 'merchant_id');
    }

    public function agent()
    {
        return $this->hasOne(UAgentAccount::class, 'id', 'agent_id');
    }

    public function login()
    {
        return $this->hasMany(UAccountLogin::class, 'account_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(UOrder::class, 'account_id', 'id');
    }

    public function bills()
    {
        return $this->hasMany(UAccountBill::class, 'account_id', 'id');
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
