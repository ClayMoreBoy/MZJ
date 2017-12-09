<?php

namespace App\Models;

class UAgentStatistic extends LModel
{
    public function agent()
    {
        return $this->hasOne(UAgentAccount::class, 'id', 'agent_id');
    }

    public function issue()
    {
        return $this->hasOne(Issue::class, 'id', 'issue_id');
    }
}
