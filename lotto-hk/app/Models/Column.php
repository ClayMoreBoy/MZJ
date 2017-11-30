<?php

namespace App\Models;

class Column extends LModel
{
    //
    public function articles()
    {
        return $this->hasMany(Article::class, 'column_id', 'id');
    }
}
