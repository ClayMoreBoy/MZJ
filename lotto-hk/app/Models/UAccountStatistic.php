<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UAccountStatistic extends Model
{
    public function account()
    {
        return $this->hasOne(UAccount::class, 'id', 'account_id');
    }

    public function issue()
    {
        return $this->hasOne(Issue::class, 'id', 'issue_id');
    }
}
