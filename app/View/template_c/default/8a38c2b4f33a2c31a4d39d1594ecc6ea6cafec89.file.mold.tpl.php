<?php /* Smarty version Smarty-3.1.11, created on 2016-08-12 09:07:46
         compiled from "app\View\template\default\public\admincp\mold.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2050357ad21623386f3-18440896%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8a38c2b4f33a2c31a4d39d1594ecc6ea6cafec89' => 
    array (
      0 => 'app\\View\\template\\default\\public\\admincp\\mold.tpl',
      1 => 1468561150,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2050357ad21623386f3-18440896',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_57ad2162385455_07102860',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57ad2162385455_07102860')) {function content_57ad2162385455_07102860($_smarty_tpl) {?><!-- 上传图片 -->

<div id="image_uploader" class="modal fade">
		<div class="modal-dialog">
				<div class="modal-content">
						<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">上传图片</h4>
						</div>
						
						<div class="modal-body">
										<script type="text/javascript" src="<?php echo @URL_JS;?>
lib/plupload/plupload.full.min.js"></script>
										<script type="text/javascript" src="<?php echo @URL_JS;?>
lib/jquery.cookie.js"></script>
										
										<script type="text/javascript">
										function ImageUploader() {
											this.callback = '';
											this.uploader = new plupload.Uploader({
												runtimes : 'html5,flash,silverlight,html4',
												browse_button : 'pickfiles', // you can pass in id...
												container: document.getElementById('image_uploader_container'), // ... or DOM Element itself
												url : '/imageuc/image/upload',
												flash_swf_url : '<?php echo @URL_JS;?>
lib/plupload/Moxie.swf',
												silverlight_xap_url : '<?php echo @URL_JS;?>
lib/plupload/Moxie.xap',
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
													'from' : ''
												},
											
												init: {
													PostInit: function() {
														document.getElementById('filelist').innerHTML = '';
													},
													
													FileUploaded: function(uploader,file,responseObject) {
														imageUploader.ret(responseObject);
													},
											
													FilesAdded: function(up, files) {
														plupload.each(files, function(file) {
															document.getElementById('filelist').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
														});
														
														imageUploader.uploader.start();
													},
											
													UploadProgress: function(up, file) {
														document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
													},
											
													Error: function(up, err) {
														document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
													}
												}
											});
											
											this.uploader.init();
										}
										
										ImageUploader.prototype.show = function(from,callback) {
											$('#image_uploader').show();
											this.uploader.settings.multipart_params.from = from;
											this.setCallback(callback);
										}
										
										ImageUploader.prototype.close = function() {
											$('#image_uploader').find('#filelist').html('').end().hide();
										}
										
										ImageUploader.prototype.setCallback = function(callback) {
											this.callback = callback;
										}
										
										ImageUploader.prototype.ret = function(responseObject) {
											this.close();
											this.callback(responseObject);
										}
										</script>
										
										<div id="filelist">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
										<br />
										
										<div id="image_uploader_container">
										    <p style="text-align:center;"><a class="btn blue" id="pickfiles" href="javascript:;">本地上传</a></p>
										</div>
										
										<br />
										<pre id="console"></pre>
										
										<script type="text/javascript">
										var imageUploader = new ImageUploader();
										$(function()
										{
											$('#image_uploader').draggable();
										})
										</script>
						</div>
				</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- 上传图片 /--><?php }} ?>