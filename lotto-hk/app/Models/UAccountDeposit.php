<?php

namespace App\Models;

class UAccountDeposit extends LModel
{
    const k_status_waiting = 0;
    const k_status_rejected = -1;
    const k_status_canceled = -2;
    const k_status_succeed = 1;

    public function account()
    {
        return $this->hasOne(UAccount::class, 'id', 'account_id');
    }

    public function merchant()
    {
        return $this->hasOne(UMerchantAccount::class, 'id', 'merchant_id');
    }

    public function agent()
    {
        return $this->hasOne(UAgentAccount::class, 'id', 'agent_id');
    }
}
