<script src="{$smarty.const.URL_JS}lib/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="{$smarty.const.URL_JS}lib/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="{$smarty.const.URL_JS}lib/jquery.cookie.js"></script>

<div id="filelist">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
<br />

<div id="container">
    <a id="pickfiles" href="javascript:;">[Select files]</a> 
    <a id="uploadfiles" href="javascript:;">[Upload files]</a>
</div>

<br />
<pre id="console"></pre>

<script type="text/javascript">

var uploader = new plupload.Uploader({
	runtimes : 'html5,flash,silverlight,html4',
	browse_button : 'pickfiles', // you can pass in id...
	container: document.getElementById('container'), // ... or DOM Element itself
	url : '/imageuc/image/upload',
	flash_swf_url : '{$smarty.const.URL_JS}lib/plupload/Moxie.swf',
	silverlight_xap_url : '{$smarty.const.URL_JS}lib/plupload/Moxie.xap',
	file_data_name : 'image',
	multi_selection : false, // 暂时取消批量上传
	
	filters : {
		max_file_size : '10mb',
		file_size : '1mb',
		mime_types : [
			{ title : "Image files", extensions : "jpg,gif,png"}
		]
	},
	
	multipart_params: {
		'cookie' : $.cookie('session_id'),
		'from' : '',
		'target_id' : ''
	},

	init: {
		PostInit: function() {
			document.getElementById('filelist').innerHTML = '';

			document.getElementById('uploadfiles').onclick = function() {
				uploader.start();
				return false;
			};
		},
		
		FileUploaded: function(uploader,file,responseObject) {
			json = $.parseJSON(responseObject.response);
			
			if (json.flag == 'error') {
				alert(json.msg);
			}
			else if (json.flag == 'success') {
				$('#image-id').val(json.id);
			}
		},

		FilesAdded: function(up, files) {
			plupload.each(files, function(file) {
				document.getElementById('filelist').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
			});
		},

		UploadProgress: function(up, file) {
			document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
		},

		Error: function(up, err) {
			document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
		}
	}
});

uploader.init();

</script>