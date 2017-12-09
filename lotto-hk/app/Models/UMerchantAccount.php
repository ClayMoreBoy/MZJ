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

    public function bills()
    {
        return $this->hasMany(UAccountBill::class, 'merchant_id', 'id');
    }

    public function withdraws()
    {
        return $this->hasMany(UAccountWithdraw::class, 'merchant_id', 'id');
    }

    public function deposits()
    {
        return $this->hasMany(UAccountDeposit::class, 'merchant_id', 'id');
    }

    public function orders(){
        return $this->hasMany(UOrder::class, 'merchant_id', 'id');
    }

    public function statistics(){
        return $this->hasMany(UMerchantStatistic::class, 'merchant_id', 'id');
    }
}
