<?php

namespace App\Models;

class UAccountWithdraw extends LModel
{
    const k_status_waiting = 0;
    const k_status_rejected = -1;
    const k_status_canceled = -2;
    const k_status_succeed = 1;

    public function account()
    {
        return $this->hasOne(UAccount::class, 'account_id', 'id');
    }

    public function merchant()
    {
        return $this->hasOne(UMerchantAccount::class, 'merchant_id', 'id');
    }

    public function agent()
    {
        return $this->hasOne(UAgentAccount::class, 'agent_id', 'id');
    }

    public function statusCN()
    {
        switch ($this->status) {
            case self::k_status_waiting: {
                return '待处理';
            }
            case self::k_status_canceled: {
                return '取消提款';
            }
            case self::k_status_rejected: {
                return '驳回申请';
            }
            case self::k_status_succeed: {
                return '提款成功';
            }
        }
    }

    public function statusCSS()
    {
        switch ($this->status) {
            case self::k_status_waiting: {
                return 'waiting';
            }
            case self::k_status_canceled: {
                return 'canceled';
            }
            case self::k_status_rejected: {
                return 'rejected';
            }
            case self::k_status_succeed: {
                return 'succeed';
            }
        }
    }
}
