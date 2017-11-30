<?php

namespace App\Models;

class NewspaperIssue extends LModel
{
    //
    public function issue()
    {
        return $this->hasOne(Issue::class, 'id', 'issue');
    }

    public function newspaperO()
    {
        return $this->hasOne(Newspaper::class, 'id', 'newspaper');
    }
}
