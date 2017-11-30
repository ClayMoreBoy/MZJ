<?php

namespace App\Models;

class UMerchantAccount extends LModel
{
    public function agents()
    {
        return $this->hasMany(UAgentAccount::class, 'merchant_id', 'id');
    }

    public function accounts()
    {
        return $this->hasMany(UAccount::class, 'merchant_id', 'id');
    }

    public function games()
    {
        return $this->hasMany(UMerchantGame::class, 'merchant_id', 'id');
    }
}
