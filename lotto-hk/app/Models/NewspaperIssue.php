<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewspaperIssue extends Model
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
