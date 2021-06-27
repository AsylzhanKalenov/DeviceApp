<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    //
    protected $table = 'operator';

    public $timestamps = false;

    public function __construct(array $attributes = []) {
        $option = Options::where('category', 'operator')->get();
        $arr = array();
        foreach ($option as $op){
            $arr[] = $op->name_en;
        }
        $arr[] = 'id_user';
        $arr[] = 'id_center';
        $arr[] = 'date1';
        $arr[] = 'date2';
        $arr[] = 'foto';
        $arr[] = 'module';


        $this->fillable($arr);
        parent::__construct($attributes);

    }
}
