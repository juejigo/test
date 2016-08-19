function imageUploader(callback,multi) {
	this.callback = arguments[0];
	this.multi = arguments[1];
	
	this.ret = new Array();
}

imageUploader.prototype.show = function() {
	$('image_uploader').show();
}

imageUploader.prototype.cancle = function() {
	
}



imageUploader.prototype.submit = function() {
	$('#image_uploader').hide();
	this.callback();
}