<?php

namespace App\Models;

class UMerchantStatistic extends LModel
{
    public function merchant()
    {
        return $this->hasOne(UMerchantAccount::class, 'id', 'merchant_id');
    }

    public function issue()
    {
        return $this->hasOne(Issue::class, 'id', 'issue_id');
    }
}
