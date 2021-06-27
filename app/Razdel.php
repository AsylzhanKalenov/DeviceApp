<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Razdel extends Model
{
    //
    protected $table = 'razdel';

    public $timestamps = false;

    protected $fillable = ['ids', 'name', 'trebpom', 'treb', 'ptb', 'metod1', 'metod', 'reguds1', 'reguds', 'licens1', 'licens', 'instr', 'rash', 'compl', 'teh_har'];
}
