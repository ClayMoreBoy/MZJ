<?php

namespace App\Models;

class UAgentAccount extends LModel
{
    public function merchant()
    {
        return $this->hasOne(UMerchantAccount::class, 'merchant_id', 'id');
    }

    public function accounts()
    {
        return $this->hasMany(UAccount::class, 'merchant_id', 'id');
    }
}
