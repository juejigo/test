var fileCount;
function fileQueueError(file, errorCode, message) {
    showMessage(message);
}

function fileQueued(file) {

    try {

        var progress = new FileProgress(file, this.customSettings.upload_target);
        progress.setProgress(0);
        progress.toggleCancel(true, this);
            
    } catch (ex) {
        this.debug(ex);
    }
}

function uploadStart(file) {
    this.addPostParam("bank", $(".in").val());
}

function fileDialogComplete(numFilesSelected, numFilesQueued) {
    try {
        if (numFilesQueued > 0)
        {
        	$('#total').html(numFilesQueued);
			this.startUpload(); //选择完成直接上传
        }
    } catch (ex) {
        this.debug(ex);
    }
}

function uploadProgress(file, bytesLoaded) {
    try {
        var percent = Math.ceil((bytesLoaded / file.size) * 100);

        var progress = new FileProgress(file, this.customSettings.upload_target);
        progress.setProgress(percent);
        if (percent === 100) {
            progress.toggleCancel(false, this);
        } else {
            progress.toggleCancel(true, this);
        }
    } catch (ex) {
        this.debug(ex);
    }
}

var iCount = 0;
function itemUpdateuploadSuccess(file, serverData) 
{
        iCount++;
        var json = strToJson(serverData);
        if (json.flag == 'success') 
        {
        	$('#upload').html(iCount);
            var dom = $('<li><img width="100" src="' + json.thumb + '" alt="" /></li>');
            $("#image-list ul").append(dom);
        }

        var progress = new FileProgress(file, this.customSettings.upload_target);
        progress.toggleCancel(false);
}


function strToJson(str) {
    var json = (new Function("return " + str))();
    return json;
}

function uploadComplete(file) {
	try {

        var progress = new FileProgress(file, this.customSettings.upload_target);
        progress.setComplete();
        if (this.getStats().files_queued > 0) {
            this.startUpload();
        } else {
            progress.toggleCancel(false);
        }
    } catch (ex) {
        this.debug(ex);
    }
}

var failCount = 0;
function uploadError(file, errorCode, message) 
{
	failCount ++;
	$('#fail').html(failCount);
	
	var imageName = "error.gif";
    var progress;
    try {
        switch (errorCode) {
            case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
                try {
                    progress = new FileProgress(file, this.customSettings.upload_target);
                    progress.setCancelled();
                    progress.toggleCancel(false);
                }
                catch (ex1) {
                    this.debug(ex1);
                }
                break;
            case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
                try {
                    progress = new FileProgress(file, this.customSettings.upload_target);
                    progress.setCancelled();
                    progress.toggleCancel(true);
                }
                catch (ex2) {
                    this.debug(ex2);
                }
            case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
                imageName = "uploadlimit.gif";
                break;
            default:
                break;
        }
        
        progress = new FileProgress(file, this.customSettings.upload_target);
        progress.setError();

    } catch (ex3) {
        this.debug(ex3);
    }
}

function fadeIn(element, opacity) {
    var reduceOpacityBy = 5;
    var rate = 30; // 15 fps


    if (opacity < 100) {
        opacity += reduceOpacityBy;
        if (opacity > 100) {
            opacity = 100;
        }

        if (element.filters) {
            try {
                element.filters.item("DXImageTransform.Microsoft.Alpha").opacity = opacity;
            } catch (e) {
                // If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
                element.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + opacity + ')';
            }
        } else {
            element.style.opacity = opacity / 100;
        }
    }

    if (opacity < 100) {
        setTimeout(function() {
            fadeIn(element, opacity);
        }, rate);
    }
}



/* ******************************************
*    FileProgress Object
*    Control object for displaying file info
* ****************************************** */

function FileProgress(file, targetID) {
    if (file.filestatus != -5) {
        this.fileProgressID = file.id;

        this.fileProgressWrapper = document.getElementById(this.fileProgressID);

        if (this.fileProgressWrapper == null) {
            this.fileProgressWrapper = document.createElement("div");
            this.fileProgressWrapper.className = "progressWrapper";
            this.fileProgressWrapper.id = this.fileProgressID;

            this.fileProgressElement = document.createElement("div");
            this.fileProgressElement.className = "progressContainer";

            var progressCancel = document.createElement("a");
            progressCancel.className = "progressCancel";
            progressCancel.href = "#";
            progressCancel.innerHTML = "删除";

            var progressText = document.createElement("div");
            progressText.className = "progressName";
            progressText.appendChild(document.createTextNode(file.name + " (" + (file.size / 1024000).toFixed(2) + " MB)"));

            var progressBar = document.createElement("div");
            progressBar.className = "progressBarInProgress";

            var progressStatus = document.createElement("div");
            progressStatus.className = "progressBarStatus";

            this.fileProgressElement.appendChild(progressText);
            this.fileProgressElement.appendChild(progressStatus);
            progressStatus.appendChild(progressBar);
            this.fileProgressElement.appendChild(progressCancel);

            this.fileProgressWrapper.appendChild(this.fileProgressElement);

            document.getElementById(targetID).appendChild(this.fileProgressWrapper);
            fadeIn(this.fileProgressWrapper, 0);

        } else {
            this.fileProgressElement = this.fileProgressWrapper.firstChild;
            //this.fileProgressElement.childNodes[1].firstChild.nodeValue = file.name;
        }

        this.height = this.fileProgressWrapper.offsetHeight;
    }

}
FileProgress.prototype.setProgress = function(percentage) {
    this.fileProgressElement.className = "progressContainer green";
    this.fileProgressElement.childNodes[1].childNodes[0].className = "progressBarInProgress";
    this.fileProgressElement.childNodes[1].childNodes[0].style.width = percentage + "%";
    
    if (percentage >= 99)
    {
    	if ($(this.fileProgressElement).find('.progressing').length == 0)
    	{
    		var progressing = document.createElement("span");
		    progressing.className = "progressing";
		    progressing.innerHTML = "正在处理...";
		    this.fileProgressWrapper.childNodes[0].appendChild(progressing);
    	}
    }
};
FileProgress.prototype.setComplete = function() {
    this.fileProgressElement.className = "progressContainer blue";
    this.fileProgressElement.childNodes[1].childNodes[0].className = "progressBarInProgress";
    this.fileProgressElement.childNodes[1].childNodes[0].style.width = "100%";
    
    $(this.fileProgressElement).find('.progressing').remove();
    
    var progressOk = document.createElement("span");
    progressOk.className = "progressOk";
    progressOk.innerHTML = "上传成功";
    this.fileProgressWrapper.childNodes[0].appendChild(progressOk);

};
FileProgress.prototype.setError = function() {
    this.fileProgressElement.className = "progressContainer red";
    this.fileProgressElement.childNodes[1].childNodes[0].className = "progressBarError";
    this.fileProgressElement.childNodes[1].childNodes[0].style.width = "";
	
};
FileProgress.prototype.setCancelled = function() {
    this.fileProgressElement.className = "progressContainer";
    this.fileProgressElement.childNodes[1].childNodes[0].className = "progressBarError";
    this.fileProgressElement.childNodes[1].childNodes[0].style.width = "";

};
//FileProgress.prototype.setStatus = function(status) {
//    this.fileProgressElement.childNodes[2].innerHTML = status;
//};

FileProgress.prototype.toggleCancel = function(show, swfuploadInstance) {
    this.fileProgressElement.childNodes[2].style.display = show ? "block" : "none";
    if (swfuploadInstance) {
        var fileID = this.fileProgressID;
        var filePro = this.fileProgressElement.childNodes[2];
        filePro.onclick = function() {
            var f = document.getElementById(fileID);
            f.parentNode.removeChild(f);
            swfuploadInstance.cancelUpload(fileID);
            return false;
        };
    }
};