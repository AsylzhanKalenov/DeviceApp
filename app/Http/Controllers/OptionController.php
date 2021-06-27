<?php

namespace App\Http\Controllers;

use App\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OptionController extends Controller
{
    //

    public function med_center($table){
        $id=0;

        $ar=array("i0"=>"выберите из списка","i1"=>"текстовое (text)","i2"=>"текстовое (дата)","i3"=>"текстовое (логин)","i4"=>"текстовое (пароль)","i5"=>"текстовое цена (числовое)","i6"=>"много текста (textarea)","i7"=>"выбор (checkbox)","i8"=>"список (select)","i9"=>"список (radio button)","i10"=>"html редактор","i11"=>"рисунок (file)","i12"=>"файл (file)");


        if($id!=0){
            $option = Options::find($id);
        }
        else {
            $option = Options::where('category', $table)->get();
        }
        $table = $table=='operator'?'user':$table;
        return view('modules.'.$table.'.option', compact('option','ar'));
    }

    public function store(Request $request){


        $this->validate($request, [
            'category' => 'required',
            'name_ru' => 'required',
            'name_en' => 'required',
            'type_field' => 'required',
            $request->get('type_field')=='i11'||$request->get('type_field')=='i12'?$request->get('type_field').'_size_file':$request->get('type_field').'_width' => 'required',
            'id_sort' => 'required',

        ]);
        $category = $request->get('category');

        $option = new Options();
        $option->category = $category;
        $option->category_id = $request->get('category_id');
        $option->required_fields = intval($request->get('required_fields'));
        $option->select_fields = intval($request->get('select_fields'));
        $option->id_sort = $request->get('id_sort');
        $option->name_ru = $request->get('name_ru');
        $option->name_en = $request->get('name_en');
        $option->width = $request->get($request->get('type_field').'_width');
        $option->type_field = $request->get('type_field');
        $option->format_date = $request->get('format_date');
        $option->height = $request->get('height');
        $option->select_elements = $request->get('select_elements');
        $option->size_file = $request->get('size_file');
        $option->format_file = $request->get('format_file');
        $option->type_resize = $request->get('type_resize');
        $option->w_resize_small = intval($request->get('w_resize_small'));
        $option->h_resize_small = intval($request->get('h_resize_small'));
        $option->watermark = $request->get('watermark');
        $option->w_resize_big = intval($request->get('w_resize_big'));
        $option->h_resize_big = intval($request->get('h_resize_big'));
        $option->filter = $request->get('filter');
        $option->search = $request->get('search');
        $option->translit = $request->get('translit');

        $option->save();
        $tb_name = $request->get('category')=='center'?$request->get('category').'s':$request->get('category');
        if($tb_name=='order') $tb_name = '`'.$tb_name.'`';
        $col_name = $request->get('name_en');
        $type = $request->get('type_field');
        if($type == 'i6' || $type == 'i10')
            $type1 = 'longtext';
        else if($type == 'i2')
            $type1 = 'timestamp';
        else if($type == 'i5')
            $type1 = 'integer';
        else
            $type1 = 'varchar(255)';
        DB::statement('alter table '.$tb_name.' add '.$col_name.' '.$type1.' null');

        return redirect()->route('module.'.$request->get('category').'.index');
    }

    public function edit($id){
        $ar=array("i0"=>"выберите из списка","i1"=>"текстовое (text)","i2"=>"текстовое (дата)","i3"=>"текстовое (логин)","i4"=>"текстовое (пароль)","i5"=>"текстовое цена (числовое)","i6"=>"много текста (textarea)","i7"=>"выбор (checkbox)","i8"=>"список (select)","i9"=>"список (radio button)","i10"=>"html редактор","i11"=>"рисунок (file)","i12"=>"файл (file)");

        $val = Options::find($id);

        $option = Options::where('category', $val["category"])->get();

        return view($val["category"].'.option', compact('option','val', 'ar'));

    }
    public function update(Request $request, $id){

        $category = $request->get('category')=='med_centers'?'center':$request->get('category');

        $option = Options::find($id);



        $option->category = $category;
        $option->category_id = $request->get('category_id');
        $option->required_fields = intval($request->get('required_fields'));
        $option->select_fields = intval($request->get('select_fields'));
        $option->id_sort = $request->get('id_sort');
        $option->name_ru = $request->get('name_ru');
        $option->name_en = $request->get('name_en');
        $option->width = $request->get($request->get('type_field').'_width');
        $option->type_field = $request->get('type_field');
        $option->format_date = $request->get('format_date');
        $option->height = $request->get('height');
        $option->select_elements = $request->get('select_elements');
        $option->size_file = $request->get('size_file');
        $option->format_file = $request->get('format_file');
        $option->type_resize = $request->get('type_resize');
        $option->w_resize_small = intval($request->get('w_resize_small'));
        $option->h_resize_small = intval($request->get('h_resize_small'));
        $option->watermark = $request->get('watermark');
        $option->w_resize_big = intval($request->get('w_resize_big'));
        $option->h_resize_big = intval($request->get('h_resize_big'));
        $option->filter = $request->get('filter');
        $option->search = $request->get('search');
        $option->translit = $request->get('translit');

        $option->save();

        return redirect()->route('module.'.($request->get('category')=='med_centers'?'center':$request->get('category')).'.index');


    }

    public function delete($id){
        $delete = Options::find($id);
        $tb_name = $delete["category"];
        if($delete["category"] == 'center')
            $tb_name = $delete["category"].'s';
        elseif ($delete["category"] == 'order')
            $tb_name = '`'.$delete["category"].'`';
        DB::statement('alter table '.$tb_name.' drop column '.$delete["name_en"].' ');
        Options::query()->find($id)->delete();
        return redirect()->route('option.list', ['table' =>$delete->category]);
    }
}
