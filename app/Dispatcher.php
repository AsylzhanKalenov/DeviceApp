<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dispatcher extends Model
{
    //
    protected $table = 'dispatcher';

    protected $fillable = ['id_user', 'fio', 'module'];
    //
//    public function __construct(array $attributes = [])
//    {
//        $arr = ['id_user', 'fio', 'module'];
//        $this->fillable($arr);
//        parent::__construct($attributes);
//    }
}
