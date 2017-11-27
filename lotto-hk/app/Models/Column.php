<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Column extends Model
{
    //
    public function articles()
    {
        return $this->hasMany(Article::class, 'column_id', 'id');
    }
}
