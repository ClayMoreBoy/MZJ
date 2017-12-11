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

    public function withdraws()
    {
        return $this->hasMany(UAccountWithdraw::class, 'account_id', 'id');
    }

    public function deposits()
    {
        return $this->hasMany(UAccountDeposit::class, 'account_id', 'id');
    }

    public function statistics()
    {
        return $this->hasMany(UAccountStatistic::class, 'account_id', 'id');
    }

    public function lastExpenseTime()
    {
        $order = $this->orders()->where('status', '>=', UOrder::k_status_unknown)->orderBy('created_at', 'desc')->first();
        if (isset($order)) {
            return substr($order->created_at, 0, 16);
        }
        return '暂无消费';
    }
}
