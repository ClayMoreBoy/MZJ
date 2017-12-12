<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UAccountBill extends Model
{
    const k_type_bonus = 'bonus';//返奖
    const k_type_buy = 'buy';//消费
    const k_type_deposit = 'deposit';//充值
    const k_type_withdraw = 'withdraw';//提现

    public function info()
    {

    }
}
