<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Isp extends Model
{
    //
    protected $table = 'isp';

    public $timestamps = false;

    public function __construct(array $attributes = []) {
        $option = Options::where('category', 'isp')->get();
        $arr = array();
        foreach ($option as $op){
            $arr[] = $op->name_en;
        }
        $arr[] = 'id_user';
        $arr[] = 'module';

        $this->fillable($arr);
        parent::__construct($attributes);

    }
}
