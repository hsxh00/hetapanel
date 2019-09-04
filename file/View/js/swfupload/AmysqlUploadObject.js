/************************************************
 *
 * Amysql AMS
 * Amysql.com 
 * @param Object 
 * AmysqlUploadObject  
 * 
 */

var UpTimeObject;			// 上传时间计算对象
var UpMainObject = null;	// 总进度对象
var UpFileSizes = 0;		// 全部上传文件总大小
var UpSize = 0;				// 已上传文件大小

function UpTime()
{
	this.list = [];
}

// 选择文件后
function fileQueued(file)
{
    try
    {
		//alert(AmysqlUpObject[AmysqlUpObjectId].flash_url);
		var p = new UpProgress(file, AmysqlUpObject[AmysqlUpObjectId].settings.custom_settings.progressTarget);
		UpFileSizes += file.size;
		p.setShow(true);
    }
    catch (e)
    {
            this.debug(e);
    }
}

// 准备上传
function fileDialogComplete()
{
    // UpFileSizes = 0;
    // UpSize = 0;
	if(AmysqlUpObject[AmysqlUpObjectId].settings.custom_settings.progressGroupTarget != '')
	{
		UpMainObject = new FileGroupProgress();		// 总进度对象
		UpMainObject.setFileCountSize(UpFileSizes);
	}
    AmysqlUpObject[AmysqlUpObjectId].startUpload();
	UpTimeObject = new UpTime();					// 上传时间计算对象
}

// 选择文件不符号条件
function fileQueueError(file, errorCode, message)
{
    try
    {
        if (errorCode === SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED)
        {
            alert("You have attempted to queue too many files.\n" + (message === 0 ? "You have reached the upload limit." : "You may select " + (message > 1 ? "up to " + message + " files." : "one file.")));
            return;
        }

        var progress = new UpProgress(file, AmysqlUpObject[AmysqlUpObjectId].settings.custom_settings.progressTarget);
        progress.setShow(false);

        UpFileSizes -= file.size;
        if(UpMainObject) UpMainObject.setFileCountSize(UpFileSizes);

        switch (errorCode)
        {
            case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
                alert("File is too big.");
                this.debug("Error Code: File too big, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
                break;
            case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
                alert("Cannot upload Zero Byte files.");
                this.debug("Error Code: Zero byte file, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
                break;
            case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
                alert("Invalid File Type.");
                this.debug("Error Code: Invalid File Type, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
                break;
            default:
                if (file !== null) alert("Unhandled Error");
                this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
                break;
        }
    } catch (ex)
    {
        this.debug(ex);
    }
}

// 上传开始
function uploadStart(file)
{
    try
    {
        var progress = new UpProgress(file, AmysqlUpObject[AmysqlUpObjectId].settings.custom_settings.progressTarget);
        progress.setUploadState(3,this.settings);
    }
    catch (ex) {}

    return true;
}

// 上传过程
function uploadProgress(file, bytesLoaded, bytesTotal)
{
    try
    {
        var progress = new UpProgress(file, AmysqlUpObject[AmysqlUpObjectId].settings.custom_settings.progressTarget);

		// 更新速度与时间
		var name_arr = file.id.split('_');
		var now_id = parseInt(name_arr[2]);		// 获得当前ID
		var upo = {};							// 每进程信息
		upo.now_time = (new Date()).valueOf();
		upo.bytesLoaded = bytesLoaded;
		
		if (typeof(UpTimeObject.list[now_id]) != 'object')
		{
			UpTimeObject.list[now_id] = {};
			UpTimeObject.list[now_id].Progress = [];
			UpTimeObject.list[now_id].start_time = upo.now_time;
			UpTimeObject.list[now_id].bytesTotal = bytesTotal;
		}
		UpTimeObject.list[now_id].Progress.push(upo);

		var p_l = UpTimeObject.list[now_id].Progress.length;
		if (p_l >= 2)
		{
			var use_time = parseInt(upo.now_time) - parseInt(UpTimeObject.list[now_id].Progress[p_l-2].now_time);		// 使用时间
			var up_size = parseInt(upo.bytesLoaded) - parseInt(UpTimeObject.list[now_id].Progress[p_l-2].bytesLoaded);	// 上传了多少

			progress.setTimeProgress(use_time, up_size, bytesLoaded, bytesTotal);	// 时间进行信息更新
		}

		var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
        progress.setProgress(percent);			// 设置上传百分比

        if(UpMainObject) UpMainObject.setUploadProgress(UpSize+bytesLoaded, UpFileSizes);			// 总进度更新

    } catch (ex)
    {
        this.debug(ex);
    }
}

// 上传成功
function uploadSuccess(file, serverData)
{
    try
    {
		var name_arr = file.id.split('_');
		var now_id = parseInt(name_arr[2]);		// 获得当前ID
		var last_key = UpTimeObject.list[now_id].Progress.length - 1;
		var use_time = (UpTimeObject.list[now_id].Progress[last_key].now_time - UpTimeObject.list[now_id].start_time);

        var progress = new UpProgress(file, AmysqlUpObject[AmysqlUpObjectId].settings.custom_settings.progressTarget);
        progress.setComplete(this.settings, use_time);	// 上传完成
        UpSize += file.size;
		G(AmysqlUpObject[AmysqlUpObjectId].settings.custom_settings.ReturnActionId).innerHTML = serverData;	// 返回结果更新值
        
    } catch (ex)
    {
        this.debug(ex);
    }
}

// 上传完成
function uploadComplete(file)
{
    try{
         // uploadComplete
    }
    catch (ex){
            this.debug(ex);
    }
}

// 上传失败
function uploadError(file, errorCode, message)
{
    try
    {
        var progress = new UpProgress(file, AmysqlUpObject[AmysqlUpObjectId].settings.custom_settings.progressTarget);
        progress.setShow(false);
        UpFileSizes -= file.size;
        if(UpMainObject) UpMainObject.setFileCountSize(UpFileSizes);

        switch (errorCode)
        {
            case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
                alert("Upload Error: " + message);
                this.debug("Error Code: HTTP Error, File name: " + file.name + ", Message: " + message);
                break;
            case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
               alert("Upload Failed.");
                this.debug("Error Code: Upload Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
                break;
            case SWFUpload.UPLOAD_ERROR.IO_ERROR:
                alert("Server (IO) Error");
                this.debug("Error Code: IO Error, File name: " + file.name + ", Message: " + message);
                break;
            case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
                alert("Security Error");
                this.debug("Error Code: Security Error, File name: " + file.name + ", Message: " + message);
                break;
            case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
                alert("Upload limit exceeded.");
                this.debug("Error Code: Upload Limit Exceeded, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
                break;
            case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED:
                alert("Failed Validation.  Upload skipped.");
                this.debug("Error Code: File Validation Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
                break;
            case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
                alert("Cancelled");
                //progress.setCancelled();
                break;
            case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
                alert("Stopped");
                break;
            default:
                alert("Unhandled Error: " + errorCode);
                this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
                break;
        }
    } catch (ex)
    {
        this.debug(ex);
    }
}

// This event comes from the Queue Plugin
function queueComplete(numFilesUploaded)
{
    /*var status = document.getElementById("divStatus");
    status.innerHTML = numFilesUploaded + " file" + (numFilesUploaded === 1 ? "" : "s") + " uploaded.";*/
}



// ******************************************************
// 上传进度对象
function UpProgress(file, targetid)
{
    //定义文件处理标识
    this.ProgressId = file.id;
    
    //获取当前容器对象
    this.fileProgressElement = document.getElementById(file.id);

    if (!this.fileProgressElement)
    {
        //container
        this.fileProgressElement = document.createElement("div");
        this.fileProgressElement.id = file.id;
        this.fileProgressElement.className = AmysqlUpObject[AmysqlUpObjectId].settings.custom_settings.container_css;

        //state button
        this.stateButton = document.createElement("input");
        this.stateButton.type = "button";
        this.stateButton.className = AmysqlUpObject[AmysqlUpObjectId].settings.custom_settings.icoWaiting_css;
        this.fileProgressElement.appendChild(this.stateButton);

        //filename
        this.filenameSpan = document.createElement("span");
        this.filenameSpan.className = AmysqlUpObject[AmysqlUpObjectId].settings.custom_settings.fname_css;
        this.filenameSpan.appendChild(document.createTextNode(file.name));
        this.filenameSpan.appendChild(document.createTextNode(' (' + formatUnits(file.size) + ')'));
        this.fileProgressElement.appendChild(this.filenameSpan);

        //statebar div
        this.stateDiv = document.createElement("span");
        this.stateDiv.className = AmysqlUpObject[AmysqlUpObjectId].settings.custom_settings.state_div_css;
        this.stateBar = document.createElement("span");
        this.stateBar.className = AmysqlUpObject[AmysqlUpObjectId].settings.custom_settings.state_bar_css;
        this.stateBar.innerHTML = "&nbsp;";
        this.stateBar.style.width = "0%";
        this.stateDiv.appendChild(this.stateBar);
        this.fileProgressElement.appendChild(this.stateDiv);

        //span percent
        this.percentSpan = document.createElement("span");
        this.percentSpan.className = AmysqlUpObject[AmysqlUpObjectId].settings.custom_settings.percent_css;
        this.percentSpan.style.marginTop = "10px";
        this.percentSpan.innerHTML = "(等待上传中...)";
        this.fileProgressElement.appendChild(this.percentSpan);

		//span velocity
        this.velocity = document.createElement("span");
        this.velocity.className = AmysqlUpObject[AmysqlUpObjectId].settings.custom_settings.percent_css;
        this.velocity.style.marginTop = "10px";
        this.velocity.innerHTML = "";
        this.fileProgressElement.appendChild(this.velocity);


		//span Seconds remaining
        this.SecondsRemaining = document.createElement("span");
        this.SecondsRemaining.className = AmysqlUpObject[AmysqlUpObjectId].settings.custom_settings.percent_css;
        this.SecondsRemaining.style.marginTop = "10px";
        this.SecondsRemaining.innerHTML = "";
        this.fileProgressElement.appendChild(this.SecondsRemaining);

        //delete href
        this.hrefSpan = document.createElement("a");
        this.hrefSpan.className = AmysqlUpObject[AmysqlUpObjectId].settings.custom_settings.href_delete_css;
        this.hrefControl = document.createElement("a");
        this.hrefControl.innerHTML = "删除";
        this.hrefControl.onclick = function()
        {
            AmysqlUpObject[AmysqlUpObjectId].cancelUpload(file.id);
        }
        this.hrefSpan.appendChild(this.hrefControl);
        this.fileProgressElement.appendChild(this.hrefSpan);

        //insert container
        document.getElementById(targetid).appendChild(this.fileProgressElement);
    }
    else
    {
        this.reset();
    }

}

// 恢复默认设置
UpProgress.prototype.reset = function()
{
    this.stateButton = this.fileProgressElement.childNodes[0];
    this.fileSpan = this.fileProgressElement.childNodes[1];
    this.stateDiv = this.fileProgressElement.childNodes[2];
    this.stateBar = this.stateDiv.childNodes[0];
    this.percentSpan = this.fileProgressElement.childNodes[3];
    this.velocity = this.fileProgressElement.childNodes[4];
    this.SecondsRemaining = this.fileProgressElement.childNodes[5];
    this.hrefSpan = this.fileProgressElement.childNodes[6];
    this.hrefControl = this.hrefSpan.childNodes[0];
}

/**
 * 设置状态按钮状态
 * state:       当前状态,1:初始化完成,2:正在等待,3:正在上传
 * settings:    swfupload.settings对象
 */
UpProgress.prototype.setUploadState = function(state,settings)
{
    switch(state)
    {
        case 1:
            //初始化完成
            this.stateButton.className = settings.custom_settings.icoNormal_css;
            break;
        case 2:
            //正在等待
            this.stateButton.className = settings.custom_settings.icoWaiting_css;
            break;
        case 3:
            //正在上传
            this.stateButton.className = settings.custom_settings.icoUpload_css;
    }
}

/**
 * 设置上传进度
 * percent:     已上传百分比
 */
UpProgress.prototype.setProgress = function(percent)
{
    this.stateBar.style.width = percent + "%";
    this.percentSpan.innerHTML = percent + "%";
    //this.stateButton.className = AmysqlUpObject[AmysqlUpObjectId].settings.custom_settings.icoUpload_css;
    if (percent == 100)
    {
        this.hrefSpan.style.display = "none";
		this.SecondsRemaining.innerHTML = '保存中...';
        //this.stateButton.className = AmysqlUpObject[AmysqlUpObjectId].settings.custom_settings.icoNormal_css;
    }
}


// 时间进行信息更新
UpProgress.prototype.setTimeProgress = function(use_time, up_size, bytesLoaded, bytesTotal)
{
	var s_b = use_time == 0 ? up_size : up_size / use_time * 1000;	// 一秒上传多少b

	var time_str;
	if(s_b > 1024*1024)
	    time_str = (s_b/(1024*1024)).toFixed(2) + 'MB/S';
	else
		time_str = (s_b/1024).toFixed(2) + 'KB/S';
    this.velocity.innerHTML = time_str;

	var SecondsRemaining = (bytesTotal-bytesLoaded)/s_b;	// 上传完毕还需多少秒
	if (SecondsRemaining > 60*60)
		time_str = (SecondsRemaining/60*60).toFixed(2) + '小时';
	else if (SecondsRemaining > 60)
		time_str = (SecondsRemaining/60).toFixed(2) + '分钟';
	else
	    time_str = (SecondsRemaining).toFixed(2) + '秒';

	this.SecondsRemaining.innerHTML = '估计还需:' + time_str;
}


// 上传完成
UpProgress.prototype.setComplete = function(settings, use_time)
{
    this.stateButton.className = settings.custom_settings.icoNormal_css;
    this.hrefSpan.style.display = "none";

	use_time = use_time / 1000;
	if (use_time > 60*60)
		time_str = (use_time/60*60).toFixed(2) + '小时';
	else if (use_time > 60)
		time_str = (use_time/60).toFixed(2) + '分钟';
	else
	    time_str = (use_time).toFixed(2) + '秒';

	this.SecondsRemaining.innerHTML = 'use:' + time_str;
}

// 控制上传进度对象是否显示
UpProgress.prototype.setShow = function(show)
{
    this.fileProgressElement.style.display = show ? "" : "none";
}



// ***************************************************
// 文件上传总进度

function FileGroupProgress()
{
    this.fileGroupElement = document.getElementById(AmysqlUpObject[AmysqlUpObjectId].settings.custom_settings.s_cnt_progress);
    if (!this.fileGroupElement)
    {
        this.fileGroupElement = document.createElement("div");
        this.fileGroupElement.id = AmysqlUpObject[AmysqlUpObjectId].settings.custom_settings.s_cnt_progress;
        this.fileGroupElement.className = AmysqlUpObject[AmysqlUpObjectId].settings.custom_settings.container_css;

        //上传总进度文本0
        this.fileSumSpan = document.createElement("span");
        this.fileSumSpan.className = AmysqlUpObject[AmysqlUpObjectId].settings.custom_settings.s_cnt_span_text;
        this.fileSumSpan.appendChild(document.createTextNode("上传总进度："));
		this.fileSumSpan.style.padding = '5px';
        this.fileGroupElement.appendChild(this.fileSumSpan);

        //进度条容器1
        this.fileProgressDiv = document.createElement("div");
        this.fileProgressDiv.className = "statebarBigDiv";

        //进度条1.1
        this.fileStateBar = document.createElement("div");
        this.fileStateBar.className = "statebar";
        this.fileStateBar.id = AmysqlUpObject[AmysqlUpObjectId].settings.custom_settings.s_cnt_progress_statebar;
        this.fileStateBar.innerHTML = "&nbsp;";
        this.fileStateBar.style.width = "0%";
        this.fileProgressDiv.appendChild(this.fileStateBar);
        this.fileGroupElement.appendChild(this.fileProgressDiv);

        //百分比2
        this.filePercent = document.createElement("span");
        this.filePercent.className = "ftt";
        this.filePercent.id = AmysqlUpObject[AmysqlUpObjectId].settings.custom_settings.s_cnt_progress_percent;
        this.fileGroupElement.appendChild(this.filePercent);

        //已上传文本3
        this.spanTextU = document.createElement("span");
        this.spanTextU.appendChild(document.createTextNode("，已上传"));
        this.fileGroupElement.appendChild(this.spanTextU);

        //已上传大小4
        this.fileUploadSize = document.createElement("span");
        this.fileUploadSize.id = AmysqlUpObject[AmysqlUpObjectId].settings.custom_settings.s_cnt_progress_uploaded;
        this.fileUploadSize.innerHTML = "0bytes";
        this.fileGroupElement.appendChild(this.fileUploadSize);

        //普通文本5
        this.spanText = document.createElement("span");
        this.spanText.appendChild(document.createTextNode("，总文件大小"));
        this.fileGroupElement.appendChild(this.spanText);

        //上传总量6
        this.fileUploadCount = document.createElement("span");
        this.fileUploadCount.id = AmysqlUpObject[AmysqlUpObjectId].settings.custom_settings.s_cnt_progress_size;
        this.fileGroupElement.appendChild(this.fileUploadCount);

        document.getElementById(AmysqlUpObject[AmysqlUpObjectId].settings.custom_settings.progressGroupTarget).appendChild(this.fileGroupElement);
    }
    else
    {
        this.fileStateBar = this.fileGroupElement.childNodes[1].childNodes[0];
        this.filePercent = this.fileGroupElement.childNodes[2];
        this.fileUploadSize = this.fileGroupElement.childNodes[4];
        this.fileUploadCount = this.fileGroupElement.childNodes[6];
    }
}

// 设置全部上传文件大小
FileGroupProgress.prototype.setFileCountSize = function(filesize)
{
    this.fileUploadCount.innerHTML = formatUnits(filesize);
    // 如果没有上传文件,则隐藏控件
    if (filesize == 0)
        this.show(false);
}

// 设置上传进度
FileGroupProgress.prototype.setUploadProgress = function(uploads, filesize)
{
    if (uploads == 0 | filesize == 0) return;
	if(uploads > filesize ) uploads = filesize;
    var percent = Math.ceil((uploads / filesize) * 100);
    this.fileStateBar.style.width = percent + "%";
    this.filePercent.innerHTML = percent + "%";

    this.fileUploadSize.innerHTML = formatUnits(uploads);
}

// 设置控件显示状态
FileGroupProgress.prototype.show = function(show)
{
    this.fileGroupElement.style.display = show ? "" : "none";
}

// 计算文件大小的文字描述,传入参数单位为字节
function formatUnits(size)
{    
    if (isNaN(size) || size == null)
        size = 0;

    if (size <= 0) return size + "bytes";

    var t1 = (size / 1024).toFixed(2);
    if (t1 < 0)
        return "0KB";

    if (t1 > 0 && t1 < 1024)
        return t1 + "KB";

    var t2 = (t1 / 1024).toFixed(2);
    if (t2 < 1024)
        return t2 + "MB";

    return (t2 / 1024).toFixed(2) + "GB";
}