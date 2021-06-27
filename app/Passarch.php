<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Passarch extends Model
{
    //
    protected $table = 'pass_arch';

    protected $fillable = ['id_user', 'pass'];

    public $timestamps = false;
}
