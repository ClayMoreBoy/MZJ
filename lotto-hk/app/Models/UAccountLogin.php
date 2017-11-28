<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UAccountLogin extends Model
{
    //
    const K_PLATFORM_WAP = 'WAP';
    const K_PLATFORM_WECHAT = 'WECHAT';
    const K_PLATFORM_PC = 'PC';

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
