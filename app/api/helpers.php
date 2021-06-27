<?php
function input_view($type_id,$table,$field,$id,$tmp_name,$suc=0)
{
    $ci = \App\Options::find($type_id);
    $input ='';

    $res = $ci;

    if($tmp_name!=""){$tmp_name="_".$tmp_name;}else{$tmp_name='';}

    if($table!="" AND $field!="" AND $id!="")
    {

        $value_res=get_table($table, $id);

    }

    @$value_res[''.$res['name_en'].''] = str_replace('"','&quot;',@$value_res[''.$res['name_en'].'']);
    @$value_res[''.$res['name_en'].''] = str_replace("'",'&#039;',@$value_res[''.$res['name_en'].'']);

    switch ($res['type_field'])
    {
        // текстовое поле
        case "i1":
            $input='<input name="'.$res['name_en'].$tmp_name.'" type="text" id="'.$res['name_en'].$tmp_name.'" 
						size="'.$res['width'].'" value="'.(@$value_res[''.$res['name_en'].'']!=''?''.@$value_res[''.$res['name_en'].''].'':''.(@$_POST[$res['name_en'].$tmp_name]!='' && $suc==0?@$_POST[$res['name_en'].$tmp_name]:'').'').'" '.($res['required_fields']==1?'rel="req"':'').' alt="" /><br /><span class="error" id="err_'.$res["name_en"].'">';
            break;

        // поле дата
        case "i2":
            $input='<input name="'.$res['name_en'].$tmp_name.'" type="text" id="'.$res['name_en'].$tmp_name.'" 
						size="'.$res['width'].'" value="'.(@$value_res[''.$res['name_en'].'']!=''?''.@$value_res[''.$res['name_en'].''].'':''.(@$_POST[$res['name_en'].$tmp_name]!='' && $suc==0?@$_POST[$res['name_en'].$tmp_name]:'').'').'';


            $input.='" '.($res['required_fields']==1?'rel="req"':'').' alt="data1"/><br /><span class="error" id="err_'.$res["name_en"].'">';


            break;

        // поле логин
        case "i3":
            $input='<input name="'.$res['name_en'].$tmp_name.'" type="text" id="'.$res['name_en'].$tmp_name.'" 
						size="'.$res['width'].'" '.($res['required_fields']==1?'rel="req"':'').' alt=""/><br /><span class="error" id="err_'.$res["name_en"].'">';
            break;

        // поле пароль
        case "i4":
            $input='<input name="'.$res['name_en'].$tmp_name.'" type="password" id="'.$res['name_en'].$tmp_name.'" 
						size="'.$res['width'].'" '.($res['required_fields']==1?'rel="req"':'').' alt=""/><br /><span class="error" id="err_'.$res["name_en"].'">';
            break;

        // поле числовое
        case "i5":
            $input='<input name="'.$res['name_en'].$tmp_name.'" type="text" id="'.$res['name_en'].$tmp_name.'" 
						size="'.$res['width'].'"  value="'.(@$value_res[''.$res['name_en'].'']!=''?''.@$value_res[''.$res['name_en'].''].'':''.(@$_POST[$res['name_en'].$tmp_name]!='' && $suc==0?@$_POST[$res['name_en'].$tmp_name]:'').'').'" '.($res['required_fields']==1?'rel="req"':'').' alt=""/><br /><span class="error" id="err_'.$res["name_en"].'">';
            break;

        // много текста textarea
        case "i6":
            $value_res[''.$res['name_en'].''] = str_replace('&quot;','"',$value_res[''.$res['name_en'].'']);
            $value_res[''.$res['name_en'].''] = str_replace('&#039;',"'",$value_res[''.$res['name_en'].'']);

            $input='<textarea name="'.$res['name_en'].$tmp_name.'" cols="'.$res['width'].'" 
						rows="'.$res['height'].'" '.($res['required_fields']==1?'rel="req"':'').' alt="">'.(@$value_res[''.$res['name_en'].'']!=''?''.@$value_res[''.$res['name_en'].''].'':''.(@$_POST[$res['name_en'].$tmp_name]!='' && $suc==0?@$_POST[$res['name_en'].$tmp_name]:'').'').'</textarea><br /><span class="error" id="err_'.$res["name_en"].'">';
            break;

        // поле чекбокс
        case "i7":
            $input='<input name="'.$res['name_en'].$tmp_name.'" type="checkbox" alt="" '.($res['required_fields']==1?'rel="req"':'').' value="1"';

            if(@$value_res[''.$res['name_en'].'']==1 || ((@$_POST[$res['name_en'].$tmp_name]==1 && $suc==0))){$input.=' checked';}

            $input.=' /> <br /><span class="error" id="err_'.$res["name_en"].'">';
            break;

        // селект
        case "i8":
            $input='<select alt="" name="'.$res['name_en'].$tmp_name.'" '.($res['required_fields']==1?'rel="req"':'').' style="width:'.$res['width'].'px">';
            $elem=explode("\n",$res['select_elements']);

            foreach($elem as $k=>$v)
            {
                if($v!="")
                {
                    if(trim($v)==@trim($value_res[''.$res['name_en'].'']) || ((@$_POST[$res['name_en'].$tmp_name]==trim($v) && $suc==0)) ){$sel='selected';}else{$sel='';}
                    if ($ci->category=='service' && trim($v)!='архив')
                        $input.='<option value="'.trim($v).'" '.$sel.'>'.trim($v).'</option>';
                    else
                        $input.='<option value="'.trim($v).'" '.$sel.'>'.trim($v).'</option>';

                }
            }

            $input.='</select> <br /><span class="error" id="err_'.$res["name_en"].'">';
            break;

        // радио батоны
        case "i9":
            $elem=explode("\n",$res['select_elements']);

            foreach($elem as $k=>$v)
            {
                if($v!="")
                {
                    if(trim($v)==@trim($value_res[''.$res['name_en'].'']) || ((@$_POST[$res['name_en'].$tmp_name]==trim($v) && $suc==0))){$sel='checked';}else{$sel='';}
                    $input.='<input type="radio" alt="" '.($res['required_fields']==1?'rel="req"':'').' name="'.$res['name_en'].$tmp_name.'" 
								id="'.$res['name_en'].$tmp_name.'" value="'.trim($v).'" '.$sel.'>'.trim($v).'<br>';
                }
            }
            $input.='<br /><span class="error" id="err_'.$res["name_en"].'">';
            break;

        // html редактор
        case "i10":
            $input='<div>
					<input type="hidden" id="'.$res['name_en'].$tmp_name.'" name="'.$res['name_en'].$tmp_name.'" 
					value="'.@$value_res[''.$res['name_en'].''].'">
					<input type="hidden" id="FCKeditor1___Config" value="">
					<iframe id="FCKeditor1___Frame" 
					src="/incom/modules/editor/editor/fckeditor.html?InstanceName='.$res['name_en'].$tmp_name.'&Toolbar=Default"
					width="'.$res['width'].'" height="'.$res['height'].'" frameborder="no" scrolling="no"></iframe></div>';
            break;

        // поле рисунок
        case "i11":
            if(@$value_res[''.$res['name_en'].'']!="")
            {
                $input='<table width="100%"  border="0" cellspacing="0" cellpadding="2" align="left">
							   <tr>
								<td align="left" class="left_menu">
									<img src="/upload/images/'.$value_res[''.$res['name_en'].''].'" width="100">
								</td>
								</tr>
								<tr>
								  <td align="left"><table width="20%"  border="0" cellspacing="0" cellpadding="0">
									<tr>
									  <td width="39%" align="center">
									  <input type="checkbox" name="delete'.$res['id'].'" value="1" 
									  onClick="SectionClick(\'delete_forms'.$res['id'].'\')">
									  </td>
									  <td width="61%" align="left" class="small_text">удалить</td>
									</tr>
								  </table>
								</td>
							</tr>
							<tr>
								<td class="small_text">
									<DIV id="delete_forms'.$res['id'].'" style="DISPLAY:none">
										<input name="'.$res['name_en'].$tmp_name.'" type="file" />
										<br>форматы('.$res['format_file'].')/размер('.$res['size_file'].' bytes)
									</div>
								</td>
							</tr>
					  </table>';

            }else
            {
                $input='<input name="'.$res['name_en'].$tmp_name.'" type="file" />
							<br>форматы('.$res['format_file'].')/размер('.$res['size_file'].' bytes)';
            }

            break;

        // поле для файла
        case "i12":
            if(@$value_res[''.$res['name_en'].'']!="")
            {
                $input='<table width="100%"  border="0" cellspacing="0" cellpadding="2" align="left">
							<tr>
							  <td align="left" class="small_text"><b>'.$value_res[''.$res['name_en'].''].'</b></td>
							</tr>
							<tr>
							  <td align="left"><table width="20%"  border="0" cellspacing="0" cellpadding="0">
								<tr>
								  <td width="39%" align="center">
								  <input type="checkbox" name="delete'.$res['id'].'" value="1" 
								  onClick="SectionClick(\'delete_forms'.$res['id'].'\')"></td>
								  <td width="61%" align="left" class="small_text">удалить</td>
								</tr>
							  </table>
								</td>
							</tr><tr><td class="small_text"><DIV id="delete_forms'.$res['id'].'" style="DISPLAY:none">
							<input name="'.$res['name_en'].$tmp_name.'" type="file" /><br>
							форматы('.$res['format_file'].')/размер('.$res['size_file'].' bytes)</div></td></tr>
						  </table>';
            }else
            {
                $input='<input name="'.$res['name_en'].$tmp_name.'" type="file" /><br>
							форматы('.$res['format_file'].')/размер('.$res['size_file'].' bytes)';
            }

            break;
    }
    return stripslashes($input);
}

function get_table($table, $id=0){
    if($table == 'med_centers'){
        $res = \App\Center::find($id);
    }
    if($table == 'user'){
        $res = \App\Operator::find($id);
    }
    if($table == 'cabinet'){
        $res = \App\Cabinet::find($id);
    }
    if($table == 'service'){
        $res = \App\Service::find($id);
    }
    if($table == 'catalog'){
        $res = \App\Catalog::find($id);
    }
    if($table == 'remont'){
        $res = \App\Remont::where('r.id', '=', $id)->first();
    }
    if($table == 'order'){
        $res = \App\Order::find($id);
    }

    return $res;
}

function get_array(){
    $module = \App\Modules::all();
    return $module;
}

function get_cabinet($id, $str=''){
    if($str!='') {
        $ret = \App\Cabinet::query()->select($str)->find($id);
        return $ret[$str];
    }
    return \App\Cabinet::query()->find($id);
}
function get_razdel($id, $str=''){
    if($str!=''){
        $ret = \App\Razdel::query()->select($str)->find($id);
    return $ret[$str];
    }

        return \App\Razdel::query()->find($id);
}
function get_center($id, $str=''){
    if($str!='') {
        $ret =\App\Center::query()->select($str)->find($id);
    return $ret[$str];
    }
        else
        return \App\Center::query()->find($id);
}
function get_service($id, $str=''){
    if($str!='') {
        $ret =\App\Service::query()->select($str)->where('id_user', '=', $id)->first();
        return $ret[$str];
    }
    else
        return \App\Service::query()->find($id);
}

function page_generate($count, $req_num=0){
    $num1 = 0;
    $pag = '';
    $left =0;
    $right = 0;
    for($i = 0; $i<$count; $i+=50){
        if($num1==0)
            $pag.='<li '.($num1==$req_num?'class="active"':'').'><a onclick="get_ser_page(' .$num1. ')" class="small_text">'.($num1+1).'</a></li>';
        elseif($num1>=$req_num-4 && $num1<=$req_num+4)
            $pag.='<li '.($num1==$req_num?'class="active"':'').'><a onclick="get_ser_page(' .$num1. ')" class="small_text">'.($num1+1).'</a></li>';
        elseif($count<$i+50)
            $pag.='<li '.($num1==$req_num?'class="active"':'').'><a onclick="get_ser_page(' .$num1. ')" class="small_text">'.($num1+1).'</a></li>';

        if($left==0 && $num1>$req_num+4) {
            $pag .= '<li ' . ($num1 == $req_num ? 'class="active"' : '') . '><a class="small_text">...</a></li>';
            $left++;
        }
        if($right==0 && $num1<$req_num-4){
            $pag.='<li '.($num1==$req_num?'class="active"':'').'><a class="small_text">...</a></li>';
            $right++;
        }
        $num1++;
    }
}

function explode_api($delimter, $string){
    $arr = explode($delimter, $string);
    return $arr;
}
