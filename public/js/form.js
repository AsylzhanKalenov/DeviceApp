// обработка формы с файлами
// author Алисеевич Валерий 2012

// ajax file upload
jQuery.extend({
	

    createUploadIframe: function(id, uri)
	{
			//create frame
            var frameId = 'jUploadFrame' + id;
            var iframeHtml = '<iframe id="' + frameId + '" name="' + frameId + '" style="position:absolute; top:-9999px; left:-9999px"';
			if(window.ActiveXObject)
			{
                if(typeof uri== 'boolean'){
					iframeHtml += ' src="' + 'javascript:false' + '"';

                }
                else if(typeof uri== 'string'){
					iframeHtml += ' src="' + uri + '"';

                }	
			}
			iframeHtml += ' />';
			jQuery(iframeHtml).appendTo(document.body);

            return jQuery('#' + frameId).get(0);			
    },
    createUploadForm: function(id, fileElementId, data)
	{
		//create form	
		var formId = 'jUploadForm' + id;
		var fileId = 'jUploadFile' + id;
		var form = jQuery('<form  action="" method="POST" name="' + formId + '" id="' + formId + '" enctype="multipart/form-data"></form>');	
		if(data)
		{
			for(var i in data)
			{
				jQuery('<input type="hidden" name="' + i + '" value="' + data[i] + '" />').appendTo(form);
			}			
		}		
		var oldElement = jQuery('#' + fileElementId);
		var newElement = jQuery(oldElement).clone();
		jQuery(oldElement).attr('id', fileId);
		jQuery(oldElement).before(newElement);
		jQuery(oldElement).appendTo(form);


		
		//set attributes
		jQuery(form).css('position', 'absolute');
		jQuery(form).css('top', '-1200px');
		jQuery(form).css('left', '-1200px');
		jQuery(form).appendTo('body');		
		return form;
    },

    ajaxFileUpload: function(s) {
        // TODO introduce global settings, allowing the client to modify them for all requests, not only timeout		
        s = jQuery.extend({}, jQuery.ajaxSettings, s);
        var id = new Date().getTime()        
		var form = jQuery.createUploadForm(id, s.fileElementId, (typeof(s.data)=='undefined'?false:s.data));
		var io = jQuery.createUploadIframe(id, s.secureuri);
		var frameId = 'jUploadFrame' + id;
		var formId = 'jUploadForm' + id;		
        // Watch for a new set of requests
        if ( s.global && ! jQuery.active++ )
		{
			jQuery.event.trigger( "ajaxStart" );
		}            
        var requestDone = false;
        // Create the request object
        var xml = {}   
        if ( s.global )
            jQuery.event.trigger("ajaxSend", [xml, s]);
        // Wait for a response to come back
        var uploadCallback = function(isTimeout)
		{			
			var io = document.getElementById(frameId);
            try 
			{				
				if(io.contentWindow)
				{
					 xml.responseText = io.contentWindow.document.body?io.contentWindow.document.body.innerHTML:null;
                	 xml.responseXML = io.contentWindow.document.XMLDocument?io.contentWindow.document.XMLDocument:io.contentWindow.document;
					 
				}else if(io.contentDocument)
				{
					 xml.responseText = io.contentDocument.document.body?io.contentDocument.document.body.innerHTML:null;
                	xml.responseXML = io.contentDocument.document.XMLDocument?io.contentDocument.document.XMLDocument:io.contentDocument.document;
				}						
            }catch(e)
			{
				jQuery.handleError(s, xml, null, e);
			}
            if ( xml || isTimeout == "timeout") 
			{				
                requestDone = true;
                var status;
                try {
                    status = isTimeout != "timeout" ? "success" : "error";
                    // Make sure that the request was successful or notmodified
                    if ( status != "error" )
					{
                        // process the data (runs the xml through httpData regardless of callback)
                        var data = jQuery.uploadHttpData( xml, s.dataType );    
                        // If a local callback was specified, fire it and pass it the data
                        if ( s.success )
                            s.success( data, status );
    
                        // Fire the global callback
                        if( s.global )
                            jQuery.event.trigger( "ajaxSuccess", [xml, s] );
                    } else
                        jQuery.handleError(s, xml, status);
                } catch(e) 
				{
                    status = "error";
                    jQuery.handleError(s, xml, status, e);
                }

                // The request was completed
                if( s.global )
                    jQuery.event.trigger( "ajaxComplete", [xml, s] );

                // Handle the global AJAX counter
                if ( s.global && ! --jQuery.active )
                    jQuery.event.trigger( "ajaxStop" );

                // Process result
                if ( s.complete )
                    s.complete(xml, status);

                jQuery(io).unbind()

                setTimeout(function()
									{	try 
										{
											jQuery(io).remove();
											jQuery(form).remove();	
											
										} catch(e) 
										{
											jQuery.handleError(s, xml, null, e);
										}									

									}, 100)

                xml = null

            }
        }
        // Timeout checker
        if ( s.timeout > 0 ) 
		{
            setTimeout(function(){
                // Check to see if the request is still happening
                if( !requestDone ) uploadCallback( "timeout" );
            }, s.timeout);
        }
        try 
		{

			var form = jQuery('#' + formId);
			jQuery(form).attr('action', s.url);
			jQuery(form).attr('method', 'POST');
			jQuery(form).attr('target', frameId);
            if(form.encoding)
			{
				jQuery(form).attr('encoding', 'multipart/form-data');      			
            }
            else
			{	
				jQuery(form).attr('enctype', 'multipart/form-data');			
            }			
            jQuery(form).submit();

        } catch(e) 
		{			
            jQuery.handleError(s, xml, null, e);
        }
		
		jQuery('#' + frameId).load(uploadCallback	);
        return {abort: function () {}};	

    },

    uploadHttpData: function( r, type ) {
        var data = !type;
        data = type == "xml" || data ? r.responseXML : r.responseText;
        // If the type is "script", eval it in global context
        if ( type == "script" )
            jQuery.globalEval( data );
        // Get the JavaScript object, if JSON is used.
        if ( type == "json" )
            eval( "data = " + data );
        // evaluate scripts within html
        if ( type == "html" )
            jQuery("<div>").html(data).evalScripts();

        return data;
    }
})



// только буквы
function alpha(val)
{
	return val.match(/^[A-Za-zА-Яа-я]+$/i)  ? 'success' : 'error';
}

// только буквы и дроби
function alpha_decimal(val)
{
	return val.match(/^[A-Za-zА-Яа-я0-9\.]+$/i)  ? 'success' : 'error';
}

// только буквы и целые числа
function alpha_numeric(val)
{
	return val.match(/^[A-Za-zА-Яа-я0-9]+$/i)  ? 'success' : 'error';
}

// пустое поле
function blank(val)
{
	return $.trim(val).length == 0  ? 'success' : 'error';
}

// дата dd/mm/yyyy
function data_ch(val){
	 return val.match(/^(0?[1-9]|[12][0-9]|3[01])[\- \/.](0?[1-9]|1[012])[\- \/.](19|20)[0-9]{2}$/)  ? 'success' : 'error';	
}

// дата yyyy/mm/dd
function data1_ch(val){
	 return val.match(/^(19|20)[0-9]{2}[\- \/.](0?[1-9]|1[012])[\- \/.](0?[1-9]|[12][0-9]|3[01])$/)  ? 'success' : 'error';	
}

// дробь
function decimal(val)
{
	return val.match(/^-?((\d+(\.\d+)?)|(\.\d+))$/) ? 'success' : 'error';
}

// целое число
function integer(val)
{
	return val.match(/^-?\d+$/) ? 'success' : 'error';
}

// e-mail
function mail(val)
{
	return val.match(/^[A-Z0-9._%-\+]+@(?:[A-Z0-9\-]+\.)+[A-Z]{2,4}$/i) ? 'success' : 'error';
}

// количество знаков
function length(val, len)
{
	return val.length == len ? 'success' : 'error';
}

// обязательное для заполнения
function req(val)
{
	return val != '' ? 'success' : 'error';
}




$(function(){
	
	// если есть календарь
	$('form[rel=ajax_form] input[alt=data]').datepicker({ 
		
		monthNames: ["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"],
		monthNamesShort: [ "Янв", "Фев", "Мрт", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек" ] ,
		dayNames: [ "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота", "Воскресенье" ],
		dayNamesMin: [ "ПН", "ВТ", "СР", "ЧТ", "ПТ", "СБ", "ВС" ],
		dateFormat: "dd-mm-yy",
		changeMonth: true,
        changeYear: true
		
	});
	
	// если есть календарь
	$('form[rel=ajax_form] input[alt=data1]').datepicker({ 
		
		monthNames: ["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"],
		monthNamesShort: [ "Янв", "Фев", "Мрт", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек" ] ,
		dayNames: [ "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота", "Воскресенье" ],
		dayNamesMin: [ "ПН", "ВТ", "СР", "ЧТ", "ПТ", "СБ", "ВС" ],
		dateFormat: "yy-mm-dd",
		changeMonth: true,
        changeYear: true
		
	});
	
	// отправляем форму
	$('form[rel=ajax_form] input#send').click(function(){
		
		
		
		var error_msg = "";
		var focused = 0;
		var data = new Array();
		
		jQuery(".error").html("");
		
		// проверяем все поля формы
		$('form[rel=ajax_form] input').not($("input[alt=pass1]")).each(function() {
            
			
			// смотрим какой тип
			switch ($(this).attr("type"))
			{
				// если текстовое поле
				case 'text':
					
					if ($(this).attr("rel")=='req')
					{
						
						switch ($(this).attr("alt"))
						{
							case '': 
								if (req($(this).val())=='error')
								{
									jQuery("#err_"+$(this).attr("id")).html("<span style=\"color:red;\">Обязательное поле</span>");
									error_msg = '1'; 
									jQuery("#"+$(this).attr("id")).css("border", "1px solid red");				
									if (focused == 0) { 
										focused = 1; 
										jQuery("#"+$(this).attr("id")).focus(); 
									} 		
								}
								else
								{
									jQuery("#"+$(this).attr("id")).css("border", "1px solid #EDEDED"); 
									jQuery("#err_"+$(this).attr("id")).html("");	
								}
							break;
							case 'alpha':
								if (alpha($(this).val())=='error')
								{
									jQuery("#err_"+$(this).attr("id")).html("<span style=\"color:red;\">Поле должно содержать только буквы.</span>");
									error_msg = '1'; 
									jQuery("#"+$(this).attr("id")).css("border", "1px solid red");				
									if (focused == 0) { 
										focused = 1; 
										jQuery("#"+$(this).attr("id")).focus(); 
									} 		
								}
								else
								{
									jQuery("#"+$(this).attr("id")).css("border", "1px solid #EDEDED"); 
									jQuery("#err_"+$(this).attr("id")).html("");	
								}							
							break;
							case 'alphad':
								if (alpha_decimal($(this).val())=='error')
								{
									jQuery("#err_"+$(this).attr("id")).html("<span style=\"color:red;\">Поле должно содержать только буквы и дробные числа.</span>");
									error_msg = '1'; 
									jQuery("#"+$(this).attr("id")).css("border", "1px solid red");				
									if (focused == 0) { 
										focused = 1; 
										jQuery("#"+$(this).attr("id")).focus(); 
									} 		
								}
								else
								{
									jQuery("#"+$(this).attr("id")).css("border", "1px solid #EDEDED"); 
									jQuery("#err_"+$(this).attr("id")).html("");	
								}							
							break;
							case 'alphan':
								if (alpha_numeric($(this).val())=='error')
								{
									jQuery("#err_"+$(this).attr("id")).html("<span style=\"color:red;\">Поле должно содержать только буквы и целые числа.</span>");
									error_msg = '1'; 
									jQuery("#"+$(this).attr("id")).css("border", "1px solid red");				
									if (focused == 0) { 
										focused = 1; 
										jQuery("#"+$(this).attr("id")).focus(); 
									} 		
								}
								else
								{
									jQuery("#"+$(this).attr("id")).css("border", "1px solid #EDEDED"); 
									jQuery("#err_"+$(this).attr("id")).html("");	
								}							
							break;
							case 'blank':
								if (blank($(this).val())=='error')
								{
									jQuery("#err_"+$(this).attr("id")).html("<span style=\"color:red;\">Поле должно быть пустым.</span>");
									error_msg = '1'; 
									jQuery("#"+$(this).attr("id")).css("border", "1px solid red");				
									if (focused == 0) { 
										focused = 1; 
										jQuery("#"+$(this).attr("id")).focus(); 
									} 		
								}
								else
								{
									jQuery("#"+$(this).attr("id")).css("border", "1px solid #EDEDED"); 
									jQuery("#err_"+$(this).attr("id")).html("");	
								}							
							break;
							case 'data':
								if (data_ch($(this).val())=='error')
								{
									jQuery("#err_"+$(this).attr("id")).html("<span style=\"color:red;\">Дата должна быть в формате dd-mm-yyyy.</span>");
									error_msg = '1'; 
									jQuery("#"+$(this).attr("id")).css("border", "1px solid red");				
									if (focused == 0) { 
										focused = 1; 
										jQuery("#"+$(this).attr("id")).focus(); 
									} 		
								}
								else
								{
									jQuery("#"+$(this).attr("id")).css("border", "1px solid #EDEDED"); 
									jQuery("#err_"+$(this).attr("id")).html("");	
								}							
							break;
							case 'data1':
								if (data1_ch($(this).val())=='error')
								{
									jQuery("#err_"+$(this).attr("id")).html("<span style=\"color:red;\">Дата должна быть в формате yyyy-mm-dd.</span>");
									error_msg = '1'; 
									jQuery("#"+$(this).attr("id")).css("border", "1px solid red");				
									if (focused == 0) { 
										focused = 1; 
										jQuery("#"+$(this).attr("id")).focus(); 
									} 		
								}
								else
								{
									jQuery("#"+$(this).attr("id")).css("border", "1px solid #EDEDED"); 
									jQuery("#err_"+$(this).attr("id")).html("");	
								}							
							break;
							case 'decimal':
								if (decimal($(this).val())=='error')
								{
									jQuery("#err_"+$(this).attr("id")).html("<span style=\"color:red;\">Поле должно содержать только дробное число.</span>");
									error_msg = '1'; 
									jQuery("#"+$(this).attr("id")).css("border", "1px solid red");				
									if (focused == 0) { 
										focused = 1; 
										jQuery("#"+$(this).attr("id")).focus(); 
									} 		
								}
								else
								{
									jQuery("#"+$(this).attr("id")).css("border", "1px solid #EDEDED"); 
									jQuery("#err_"+$(this).attr("id")).html("");	
								}							
							break;
							case 'integer':
								if (integer($(this).val())=='error')
								{
									jQuery("#err_"+$(this).attr("id")).html("<span style=\"color:red;\">Поле должно содержать только целое число.</span>");
									error_msg = '1'; 
									jQuery("#"+$(this).attr("id")).css("border", "1px solid red");				
									if (focused == 0) { 
										focused = 1; 
										jQuery("#"+$(this).attr("id")).focus(); 
									} 		
								}
								else
								{
									jQuery("#"+$(this).attr("id")).css("border", "1px solid #EDEDED"); 
									jQuery("#err_"+$(this).attr("id")).html("");	
								}							
							break;
							case 'mail':
								if (mail($(this).val())=='error')
								{
									jQuery("#err_"+$(this).attr("id")).html("<span style=\"color:red;\">Некорректный e-mail.</span>");
									error_msg = '1'; 
									jQuery("#"+$(this).attr("id")).css("border", "1px solid red");				
									if (focused == 0) { 
										focused = 1; 
										jQuery("#"+$(this).attr("id")).focus(); 
									} 		
								}
								else
								{
									jQuery("#"+$(this).attr("id")).css("border", "1px solid #EDEDED"); 
									jQuery("#err_"+$(this).attr("id")).html("");	
								}							
							break;
							case 'length':
								if (length($(this).val(),$(this).attr("length"))=='error')
								{
									jQuery("#err_"+$(this).attr("id")).html("<span style=\"color:red;\">Поле должно содержать символов - "+$(this).attr("length")+".</span>");
									error_msg = '1'; 
									jQuery("#"+$(this).attr("id")).css("border", "1px solid red");				
									if (focused == 0) { 
										focused = 1; 
										jQuery("#"+$(this).attr("id")).focus(); 
									} 		
								}
								else
								{
									jQuery("#"+$(this).attr("id")).css("border", "1px solid #EDEDED"); 
									jQuery("#err_"+$(this).attr("id")).html("");	
								}							
							break;
								
						}	
					}
				
				break;
				case 'password':
					
					if ($(this).val()=='' && $(this).val().length<5 && $(this).attr("rel")=='req')
					{
						jQuery("#err_"+$(this).attr("id")).html("<span style=\"color:red;\">Поле должно содержать более 4 символов.</span>");
						error_msg = '1'; 
						jQuery("#"+$(this).attr("id")).css("border", "1px solid red");				
						if (focused == 0) { 
							focused = 1; 
							jQuery("#"+$(this).attr("id")).focus(); 
						}
					}
					else
					{
						/*jQuery("#"+$(this).attr("id")).css("border", "1px solid #EDEDED"); 
						jQuery("#err_"+$(this).attr("id")).html("");
									
						if ($(this).val()!=$('input[alt=pass1]').val())
						{
							jQuery("#err_"+$('input[alt=pass1]').attr("id")).html("<span style=\"color:red;\">Пароль не совпадает.</span>");
							error_msg = '1'; 
							jQuery("#"+$('input[alt=pass1]').attr("id")).css("border", "1px solid red");				
							if (focused == 0) { 
								focused = 1; 
								jQuery("#"+$('input[alt=pass1]').attr("id")).focus(); 
							}
						}
						else
						{
							jQuery("#"+$('input[alt=pass1]').attr("id")).css("border", "1px solid #EDEDED"); 
							jQuery("#err_"+$('input[alt=pass1]').attr("id")).html("");
						}
							*/					
						
					}
					
				break;
				case 'checkbox':
					if ($(this).attr("rel")=='req')
					{
						if ($('input[name='+$(this).attr("name")+']:checked').length==0)
						{
							jQuery("#err_"+$(this).attr("name")).html("<span style=\"color:red;\">Обязательное поле.</span>");
							error_msg = '1'; 
							if (focused == 0) { 
								focused = 1; 
								jQuery("#"+$(this).attr("name")).focus(); 
							}
						}
						else
						{
							jQuery("#err_"+$(this).attr("name")).html("");
						}	
					}
				break;
				case 'radio':
					if ($(this).attr("rel")=='req')
					{
						if ($('input[name='+$(this).attr("name")+']:checked').length==0)
						{
							jQuery("#err_"+$(this).attr("name")).html("<span style=\"color:red;\">Обязательное поле.</span>");
							error_msg = '1'; 
							if (focused == 0) { 
								focused = 1; 
								jQuery("#"+$(this).attr("name")).focus(); 
							}
						}
						else
						{
							jQuery("#err_"+$(this).attr("name")).html("");
						}	
					}
				break;
				
			}
			
        });
		
		// проверяем все поля формы
		$('form[rel=ajax_form] select').each(function() {
			if ($(this).attr("rel")=='req')
			{
				if (req($("option:selected",this).val())=='error')
				{
					jQuery("#err_"+$(this).attr("id")).html("<span style=\"color:red;\">Обязательное поле</span>");
					error_msg = '1'; 
					jQuery("#"+$(this).attr("id")).css("border", "1px solid red");				
					if (focused == 0) { 
						focused = 1; 
						jQuery("#"+$(this).attr("id")).focus(); 
					} 		
				}
				else
				{
					jQuery("#"+$(this).attr("id")).css("border", "1px solid #EDEDED"); 
					jQuery("#err_"+$(this).attr("id")).html("");	
				}
			}
		});
		
		
		// проверяем все поля формы
		$('form[rel=ajax_form] textarea').each(function() {
			if ($(this).attr("rel")=='req')
			{
				if (req($(this).val())=='error')
				{
					jQuery("#err_"+$(this).attr("id")).html("<span style=\"color:red;\">Обязательное поле</span>");
					error_msg = '1'; 
					jQuery("#"+$(this).attr("id")).css("border", "1px solid red");				
					if (focused == 0) { 
						focused = 1; 
						jQuery("#"+$(this).attr("id")).focus(); 
					} 		
				}
				else
				{
					jQuery("#"+$(this).attr("id")).css("border", "1px solid #EDEDED"); 
					jQuery("#err_"+$(this).attr("id")).html("");	
				}
			}
		});
		
		
		if (error_msg=='')
		{
			/*
			$('form[rel=ajax_form] input').not($("input[alt=pass1]")).not($("input[type=radio]")).not($("input[type=checkbox]")).each(function() {	
			
				data.push($(this).attr("id")+"="+encodeURIComponent($(this).attr("value")));
			
			})
			
			$('form[rel=ajax_form] textarea').each(function() {
				
				data.push($(this).attr("id")+"="+encodeURIComponent($(this).attr("value")));
				
			})
			
			$('form[rel=ajax_form] select').each(function() {
				
				if ($(this).attr("multiple"))
				{
					var asd = new Array();
					
					$('option',this).each(function() {
                        
						
						if ($(this).attr("selected"))
						{
							asd.push($(this).val());
						}
						
                    });
					
					asd.join('|');
					
					data.push($(this).attr("id")+"="+encodeURIComponent(asd));	
				}
				else
				data.push($(this).attr("id")+"="+encodeURIComponent($(this).attr("value")));
				
			})
			
			
			
			$('form[rel=ajax_form] input:hidden').each(function() {
				
				data.push($(this).attr("id")+"="+encodeURIComponent($(this).attr("value")));
				
			})
			
			$('form[rel=ajax_form] input[type:checkbox]:checked').each(function() {
				
				if ($(this).attr("multiple"))
				{
					var asd = new Array();
					
					$('option',this).each(function() {
                        
						
						if ($(this).attr("selected"))
						{
							asd.push($(this).val());
						}
						
                    });
					
					asd.join('|');
					
					data.push($(this).attr("id")+"="+encodeURIComponent(asd));	
				}
				else
				data.push($(this).attr("id")+"="+encodeURIComponent($(this).attr("value")));
				
			})*/
			
			$(this).closest('form').submit();
		}
		
		
		
		
		
	})
	
	/*
	$('form[rel=ajax_form] input[type=file]').live("change",function(){
		
		var id_input = $(this).attr("id");
		
		$(".info_"+$(this).attr("id"))
		.ajaxStart(function(){
			$(this).html("Идет загрузка файла...");
		})
		.ajaxComplete(function(){
			//$(this).html("");
		});

		$.ajaxFileUpload
		(
			{
				url:$(this).closest('form').attr("action")+'/upload',
				secureuri:false,
				fileElementId:$(this).attr("id"),
				dataType: 'json',
				data:{name:'logan', id:$(this).attr("id")},
				success: function (resp)
				{
					show_info(resp);
				},
				error: function()
				{
					show_error(id_input);
				}
			}
		)
		
	})*/
	
	
	
	
})

// показ инфо о файле
function show_info(resp){
	if (resp.error)
	{
		$('div.info_'+resp.id)
		.html(resp.error);
		clearFileInputField($('#'+resp.id).closest('.conte'));
		$('#hidden_'+resp.id).val("");
	}
	else
	{
		$('div.info_'+resp.id)
		.html("<span>"+resp.name+"</span><br><span>"+
					   resp.size+" Кб</span> <a href='javascript:del_file(\""+resp.name+"\",\""+$('#'+resp.id).closest('form').attr("action")+"/delete\",\""+resp.id+"\")' style=''>Удалить</a>");
		clearFileInputField($('#'+resp.id).closest('.conte'));
		$('#hidden_'+resp.id).val(resp.name);
	}	
}

// ошибка загрузки
function show_error(id){
	
	$('div.info_'+id).html("Ошибка загрузки файла");
	clearFileInputField($('#'+id).closest('.conte'));
	$('#hidden_'+id).val("");
}

// очистка inputa
function clearFileInputField(Id) {
  Id.html(Id.html());
}

// удаление файла
function del_file(name, url, id)
{
	$.post(url, { name: name},
   		function(data) {
     	$('.info_'+id).html("");
		$('#hidden_'+id).val("");
   });
}

function SectionClick(id){	
		var div = document.getElementById(id);
		if(div.style.display=='none'){
			div.style.display='block';
		}else{
			div.style.display='none';
		}
	}