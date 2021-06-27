<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Peremesh extends Model
{
    //
    protected $table = 'peremesh';

    protected $fillable = ['idcat', 'idc', 'idcab', 'idc1', 'idcab1', 'date', 'id_user'];

    public $timestamps = false;

}
