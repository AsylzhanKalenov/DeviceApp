<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Remont extends Model
{
    //
    protected $table = 'remont as r';

    public $timestamps = false;

    protected $fillable = ['idser', 'idcat', 'data1', 'data2', 'data3', 'stat', 'comm', 'idtype', 'idisp', 'comm1', 'user', 'id_user'];

    public function category(){
        return $this->hasMany(Catalog::class);
    }
}
