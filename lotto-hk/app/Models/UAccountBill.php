<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UAccountBill extends Model
{
    const k_type_bonus = 'bonus';//返奖
    const k_type_buy = 'buy';//消费
    const k_type_deposit = 'deposit';//充值
    const k_type_withdraw = 'withdraw';//提现

    public function order()
    {
        return $this->hasOne(UOrder::class, 'id', 'tid');
    }

    public function deposit()
    {
        return $this->hasOne(UAccountDeposit::class, 'id', 'tid');
    }

    public function withdraw()
    {
        return $this->hasOne(UAccountWithdraw::class, 'id', 'tid');
    }

    public function info()
    {
        switch ($this->type) {
            case self::k_type_bonus: {
                $this->order->or;
                return '';
            }
            case self::k_type_buy: {
                return '';
            }
            case self::k_type_deposit: {
                return '';
            }
            case self::k_type_withdraw: {
                return '';
            }
        }
    }
}
