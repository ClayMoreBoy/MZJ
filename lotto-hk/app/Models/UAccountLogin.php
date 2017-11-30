<?php

namespace App\Models;

class UAccountLogin extends LModel
{
    //
    const K_PLATFORM_WAP = 'WAP';
    const K_PLATFORM_WECHAT = 'WECHAT';
    const K_PLATFORM_PC = 'PC';

    protected $primaryKey = "token";
    protected $keyType = "string";

    public static function generateToken()
    {
        $token = uniqid('lotto-', true);
        $al = UAccountLogin::query()->find($token);
        if (empty($al)) {
            return $token;
        } else {
            return self::generateToken();
        }
    }

    public function account()
    {
        return $this->belongsTo(UAccount::class, 'account_id');
    }
}
