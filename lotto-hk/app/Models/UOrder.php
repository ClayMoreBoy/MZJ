<?php

namespace App\Models;

class UOrder extends LModel
{
    const k_status_unknown = 0;
    const k_status_done = 1;
    const k_status_clean = -1;

    const k_hit_win = 1;
    const k_hit_lose = -1;
    const k_hit_unknown = 0;

    public function issueO()
    {
        return $this->hasOne(Issue::class, 'id', 'issue');
    }

    public function game()
    {
        return $this->hasOne(UGame::class, 'id', 'game_id');
    }

    public function merchant()
    {
        return $this->hasOne(UMerchantAccount::class, 'id', 'merchant_id');
    }

    public function agent()
    {
        return $this->hasOne(UAgentAccount::class, 'id', 'agent_id');
    }

    public function account()
    {
        return $this->hasOne(UAccount::class, 'id', 'account_id');
    }

    public function statusCN()
    {
        switch ($this->status) {
            case self::k_status_unknown: {//未结算
                return '未结算';
            }
            case self::k_status_done: {//已结算
                if ($this->hit == self::k_hit_win) {
                    return '中奖';
                } elseif ($this->hit == self::k_hit_lose) {
                    return '未中奖';
                } else {
                    return '未结算';
                }
            }
            case self::k_status_clean: {//取消
                return '取消';
            }
        }
        return '未知';
    }

    public function statusCSS()
    {
        switch ($this->status) {
            case self::k_status_unknown: {//未结算
                return 'unknown';//
            }
            case self::k_status_done: {//已结算
                if ($this->hit == self::k_hit_win) {
                    return 'hit';
                } elseif ($this->hit == self::k_hit_lose) {
                    return 'un-hit';
                } else {
                    return 'unknown';
                }
            }
            case self::k_status_clean: {//取消
                return 'clean';
            }
        }
        return 'unknown';
    }
}
