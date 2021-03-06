<?php

namespace App\Models;

class UAgentAccount extends LModel
{
    public function merchant()
    {
        return $this->hasOne(UMerchantAccount::class, 'id', 'merchant_id');
    }

    public function accounts()
    {
        return $this->hasMany(UAccount::class, 'agent_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(UOrder::class, 'agent_id', 'id');
    }

    public function bills()
    {
        return $this->hasMany(UAccountBill::class, 'agent_id', 'id');
    }

    public function withdraws()
    {
        return $this->hasMany(UAccountWithdraw::class, 'agent_id', 'id');
    }

    public function deposits()
    {
        return $this->hasMany(UAccountDeposit::class, 'agent_id', 'id');
    }

    public function statistics()
    {
        return $this->hasMany(UAgentStatistic::class, 'agent_id', 'id');
    }
}
