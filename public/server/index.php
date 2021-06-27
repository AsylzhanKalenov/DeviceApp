<?
$id = $_GET["files"];
?>
<html lang="en">
<head>
<!-- Force latest IE rendering engine or ChromeFrame if installed -->
<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
<meta charset="utf-8">
<title>Загрузка файлов</title>
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap CSS Toolkit styles -->
<link rel="stylesheet" href="css/bootstrap.min.css">
<!-- Generic page styles -->
<link rel="stylesheet" href="css/style.css">
<!-- Bootstrap styles for responsive website layout, supporting different screen sizes -->
<link rel="stylesheet" href="css/bootstrap-responsive.min.css">
<!-- Bootstrap CSS fixes for IE6 -->
<!--[if lt IE 7]><link rel="stylesheet" href="css/bootstrap-ie6.min.css"><![endif]-->
<!-- Bootstrap Image Gallery styles -->
<link rel="stylesheet" href="css/bootstrap-image-gallery.min.css">
<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
<link rel="stylesheet" href="css/jquery.fileupload-ui.css">
<!-- CSS adjustments for browsers with JavaScript disabled -->
<noscript><link rel="stylesheet" href="css/jquery.fileupload-ui-noscript.css"></noscript>
</head>
<body>

<div class="container">

    <!-- The file upload form used as target for the file upload widget -->
    <form id="fileupload" action="/server/php/index.php?files=<?=$id?>" method="POST" enctype="multipart/form-data">
        <!-- Redirect browsers with JavaScript disabled to the origin page -->
        <noscript><input type="hidden" name="redirect" value=""></noscript>
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="row fileupload-buttonbar">
            <div class="span7">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button">
                    <i class="icon-plus icon-white"></i>
                    <span>Добавить файлы...</span>
                    <input type="file" name="files[]" multiple maxlength="5">
                </span>
                <button type="submit" class="btn btn-primary start" style="display: none">
                    <i class="icon-upload icon-white"></i>
                    <span>Закачать</span>
                </button>
                <button type="reset" class="btn btn-warning cancel" style="display: none">
                    <i class="icon-ban-circle icon-white"></i>
                    <span>Отменить</span>
                </button>
                <button type="button" class="btn btn-danger delete" style="display: none">
                    <i class="icon-trash icon-white"></i>
                    <span>Удалить</span>
                </button>
                <input type="checkbox" class="toggle">
                <!-- The loading indicator is shown during file processing -->
                <span class="fileupload-loading"></span>
            </div>
            <!-- The global progress information -->
            <div class="span5 fileupload-progress fade">
                <!-- The global progress bar -->
                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="bar" style="width:0%;"></div>
                </div>
                <!-- The extended global progress information -->
                <div class="progress-extended">&nbsp;</div>
            </div>
        </div>
        <!-- The table listing the files available for upload/download -->
        <table role="presentation" class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody></table>
    </form>
    <br>

</div>
<!-- modal-gallery is the modal dialog used for the image gallery -->

<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
{% if (i<5) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview" style=" display:block; width:40px; height:40px; overflow:hidden"></span>
        </td>
        <td>
            <p class="name" style="margin:0px; width:auto">{%=file.name%}</p>
            {% if (file.error) { %}
                <div><span class="label label-important">Ошибка</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <p class="size">{%=o.formatFileSize(file.size)%}</p>
            {% if (!o.files.error) { %}
                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar" style="width:0%;"></div></div>
            {% } %}
        </td>
        <td align="right" style=" text-align:right">
            {% if (!o.files.error && !i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start">
                    <i class="icon-upload icon-white"></i>
                    <span>Начать</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="icon-ban-circle icon-white"></i>
                    <span>Отменить</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
{% if (i<5) { %}
    <tr class="template-download fade">
        <td width="100">
            <span class="preview">
                {% if (file.name) { %}
                    <a href="/upload/images/{%=file.name%}" style=" display:block; width:100px; height:100px; overflow:hidden;" title="{%=file.name%}" data-gallery="gallery" download="{%=file.name%}"><img width="100" src="/upload/images/{%=file.name%}"></a>
                {% } %}
            </span>
        </td>
        <td valign="middle" width="200">
            <p class="name" style=" margin:0px; width:auto">
                <a href="/upload/images/{%=file.name%}" title="{%=file.name%}" data-gallery="{%=file.thumbnail_url&&'gallery'%}" download="{%=file.name%}">{%=file.name%}</a>
            </p>
            {% if (file.error) { %}
                <div><span class="label label-important">Ошибка</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td align="right" style=" text-align:right">
            <button class="btn btn-danger delete" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}"{% if (file.delete_with_credentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                <i class="icon-trash icon-white"></i>
                <span>Удалить</span>
            </button>
            <input type="checkbox" name="delete" value="1" class="toggle">
        </td>
    </tr>
	{% } %}
{% } %}
</script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="js/vendor/jquery.ui.widget.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="js/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="js/load-image.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="js/canvas-to-blob.min.js"></script>
<!-- Bootstrap JS and Bootstrap Image Gallery are not required, but included for the demo -->
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-image-gallery.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="js/jquery.fileupload-process.js"></script>
<!-- The File Upload image resize plugin -->
<script src="js/jquery.fileupload-resize.js"></script>
<!-- The File Upload validation plugin -->
<script src="js/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="js/jquery.fileupload-ui.js"></script>
<!-- The main application script -->
<script>

	$(function () {
    'use strict';

    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: '/server/php/index.php?files=<?=$id?>',
        autoUpload: true
    });
	
	$('#fileupload').bind("fileuploadcompleted",function (e, data) {
    	var arr = new Array();
		var i = 1;
		$('#fileupload p.name a').each(function() {
			if (i<6)
            arr.push($(this).prop('download'));
			i++;
        });
		window.parent.document.getElementById('files').value=arr.join(";");
		console.log(arr.join(";"));
	});

	$('#fileupload').bind("fileuploaddestroyed",function (e, data) {
   		var arr = new Array();
		var i = 1;
		$('#fileupload p.name a').each(function() {
			if (i<6)
            arr.push($(this).prop('download'));
			i++;
        });
		window.parent.document.getElementById('files').value=arr.join(";");
		console.log(arr.join(";"));
	});
    // Enable iframe cross-domain access via redirect option:
    $('#fileupload').fileupload(
        'option',
        'redirect',
        window.location.href.replace(
            /\/[^\/]*$/,
            '/server/cors/result.html?%s'
        )
    );

    if (window.location.hostname === 'blueimp.github.com' ||
            window.location.hostname === 'blueimp.github.io') {
        // Demo settings:
        $('#fileupload').fileupload('option', {
            url: '/server/php/',
            disableImageResize: false,
            maxFileSize: 5000000,
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i
        });
        // Upload server status check for browsers with CORS support:
        if ($.support.cors) {
            $.ajax({
                url: '/server/php/index.php?files=<?=$id?>',
                type: 'HEAD'
            }).fail(function () {
                $('<span class="alert alert-error"/>')
                    .text('Upload server currently unavailable - ' +
                            new Date())
                    .appendTo('#fileupload');
            });
        }
    } else {
        // Load existing files:
        $('#fileupload').addClass('fileupload-processing');
        $.ajax({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: $('#fileupload').fileupload('option', 'url'),
            dataType: 'json',
            context: $('#fileupload')[0]
        }).always(function (result) {
            $(this).removeClass('fileupload-processing');
        }).done(function (result) {
			$(this).fileupload('option', 'done')
                .call(this, null, {result: result});
			var arr = new Array();
			
			$.each(result, function(index, value){
				$.each(value, function(index1, value1){
					$.each(value1, function(index2, value2){
						if (index2 == 'name')
						{
							arr.push(value2);
						}
					})
				})	
			})
			window.parent.document.getElementById('files').value=arr.join(";");
			console.log(arr.join(";"));
		
        });
    }

});


</script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE8+ -->
<!--[if gte IE 8]><script src="js/cors/jquery.xdr-transport.js"></script><![endif]-->
<style>
*{ font-size:12px; line-height:16px;}
.table th, .table td {
    border-top: 1px solid #DDDDDD;
    line-height: 20px;
    padding: 8px;
    text-align: left;
    vertical-align: middle;
}
.progress{ display:none}
</style>
</body>
</html>
