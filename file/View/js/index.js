/************************************************
 * Amysql FTP - AMFTP 2.5
 * amh.sh
 * Update:2015-08-15
 * 
 */
// AJAX请求
var Ajax={};
Ajax._xmlHttp = function(){ return new (window.ActiveXObject||window.XMLHttpRequest)("Microsoft.XMLHTTP");}
Ajax._AddEventToXHP = function(xhp,fun,isxml){
	xhp.onreadystatechange=function(){
		if(xhp.readyState==4&&xhp.status==200)
			fun(isxml?xhp.responseXML:xhp.responseText);
	}	
}
Ajax.get=function(url,fun,isxml,bool){
	var _xhp = this._xmlHttp();	
	this._AddEventToXHP(_xhp, fun || function(){} ,isxml);
	_xhp.open("GET",url,bool);
	_xhp.send(null);	
}
Ajax.post=function(url,data,fun,isxml,bool){	
	var _xhp = this._xmlHttp();	
	this._AddEventToXHP(_xhp, fun || function(){},isxml);
	_xhp.open("POST",url,bool);
	_xhp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	_xhp.send(data);
}
// 创建元素 
// attr 元素的属性
// CssOrHtml CSS或节点内容
var C = function (tag, attr, CssOrHtml)
{
	var o = (typeof(tag) != 'object') ? document.createElement(tag) : tag;
	if (attr == 'In')
	{
		if(CssOrHtml  && typeof(CssOrHtml) == 'object') 
		{
			if(CssOrHtml.length > 1 && CssOrHtml.constructor == Array )
			{
				for (x in CssOrHtml)
					if(CssOrHtml[x]) o.appendChild(CssOrHtml[x]);
			}
			else
			    o.appendChild(CssOrHtml);
		}
		else
			o.innerHTML = CssOrHtml;
		return o;
	}

	if (typeof(attr) == 'object')
	{
		for (k in attr )
			if(attr[k] != '') o[k] = attr[k];
	}

	if (typeof(CssOrHtml) == 'object')
	{
	    for (k in CssOrHtml )
			if(CssOrHtml[k] != '') o.style[k] = CssOrHtml[k];
	}
	return o;
}
var G = function (id) {return document.getElementById(id); }




// ***********************************************************
var submit_change_notice;	// 提示
var need_select_notice;		// 需要选择
var submit_change_form;		// 表单提交
var temp_tr;			// 临时TR
var t;					// TimeOut
var tr_arr = [];		// TR数组


var permissions_val;	// 权限值
var pv_item_arr = {};	// 权限数组
var pv_item_set;
var pv_select;
var pv_all_select;
var pv_all_unselect;

window.onload = function ()
{

	// AMFTP列表
	all_tr_arr = document.getElementsByTagName('tr');
	for (var k in all_tr_arr )
	{
		if (all_tr_arr[k].className && all_tr_arr[k].className.indexOf('tr') !== -1)
			tr_arr.push(all_tr_arr[k]);
	}

	for (var k in tr_arr )
	{
		(function (obj)
		{
			var input_arr = obj.getElementsByTagName('input');
			obj.select = input_arr[0];		// 选择框
			obj.charset = input_arr[1];		// 编码
			obj.can_open = input_arr[2];	// 是否可打开
			obj.permissions_number = input_arr[3];	// 权限值

			// 文件操作
			obj.action = {};
			var a_arr = obj.getElementsByTagName('a');
			for (var ak in a_arr )
			{
				if (a_arr[ak] && a_arr[ak].className && a_arr[ak].className.indexOf('action') != -1)
					obj.action[a_arr[ak].name] = a_arr[ak];
			}


			// 文件名
			obj.file_name = obj.getElementsByTagName('font')[0];
			obj.file_name.a = obj.file_name.getElementsByTagName('a')[0];	// 文件或目录链接
			obj.file_name.input = C('input', {'value':obj.file_name.a.name, 'className':'file_name_input'}, {'display':'none'});	// 重命名输入
			C(obj.file_name, 'In', obj.file_name.input);
			
			// 文件或目录链接打开
			obj.file_name.a.onclick = function ()
			{
				if (obj.can_open.value == 'n')	// 不能打开直接下载
				{
					obj.action['download'].onclick();
					return false;
				}
				else if (obj.select.name.indexOf('[d]') != -1)	// 以form方式打开目录以能保存记录
				{
					G('pwd').value = obj.select.value;
				    G('go_pwd_form').submit();
					return false;
				}
			}

			// 重命名保存
			obj.file_name.input.onblur = function ()
			{
				obj.file_name.a.style.display = '';
				obj.file_name.input.style.display = 'none';
				if(obj.file_name.input.value == obj.file_name.a.name) return false;
				var data = 'old_name=' + encodeURIComponent(obj.select.value) + '&new_name=' + encodeURIComponent(obj.file_name.input.value) + '&charset=' + obj.charset.value;
				Ajax.post('./index.php?c=index&a=file_rename&tag=' + Math.random(), data, function (msg){
					if (msg != '')
					{
						// 新值
						obj.file_name.a.name = obj.file_name.input.value;
						obj.file_name.a.innerHTML = htmlspecialchars(obj.file_name.a.name);
						if (obj.file_name.a.className == 'file')
							obj.file_name.a.href = './index.php?a=file_edit&charset=utf8&pwd=' + amftp_am_ftp_pwd + '&file=' + obj.file_name.a.name;
						else 
							obj.file_name.a.href = './index.php?pwd=' + amftp_am_ftp_pwd + obj.file_name.a.name + '/';
						
						// 新路径
						obj.charset.value = 'utf8';
						obj.select.value = msg;
					}
				}, false, true)
			}

			// Action List ******************************
			// 重命名 
			if(obj.action['rename'])
			{
				obj.action['rename'].onclick = function ()
				{
					if(obj.file_name.input.style.display != 'none') return false;
					obj.file_name.a.style.display = 'none';
					obj.file_name.input.style.display = '';
					obj.file_name.input.focus();
					return false;
				}
			}
			
			// 删除 
			if (obj.action['delete'])
			{
				obj.action['delete'].onclick = function ()
				{
					if (!confirm('确认删除：' + obj.select.value + ' ?'))
						return false;

					submit_change_notice = '';
					all_select({'checked':false});
					G('hidden_submit').name = 'delete';
					setTimeout(function ()
					{
						obj.select.checked = true;
						submit_change_form.submit();
						G('hidden_submit').name = '';
					}, 100)
					return false;
				}
			}
			
			// 下载
			if (obj.action['download'])
			{
				obj.action['download'].onclick = function ()
				{
					submit_change_notice = '';
					all_select({'checked':false});
					G('hidden_submit').name = 'download';
					setTimeout(function ()
					{
						obj.select.checked = true;
						submit_change_form.submit();
						G('hidden_submit').name = '';
					}, 100)
					return false;
				}
			}
			
			// 编辑
			if (obj.action['edit'])
			{
				obj.action['edit'].onclick = function ()
				{
					window.location = './index.php?a=file_edit&charset=' + obj.charset.value + '&pwd=' + amftp_am_ftp_pwd + '&file=' + obj.file_name.a.name;
					return false;
				}
			}
			// ******************************
			
			// 焦点
			obj.onmouseover = function ()
			{
				if(temp_tr == obj) clearTimeout(t);
				obj.className = obj.className + ' trmove';

				for (var ak in obj.action)
					obj.action[ak].className = 'action_ico ' + obj.action[ak].name + ' ' + obj.action[ak].name + '_hover';
			}
			
			// 离开
			obj.onmouseout = function ()
			{
				for (var ak in obj.action)
					obj.action[ak].className = 'action_ico ' + obj.action[ak].name;

				if(obj.select.checked) return;

				temp_tr = obj;
				t = setTimeout(function ()
				{
					obj.className = obj.className.replace(/ trmove/g, '');
				}, 2);
			}

			// 选择
			obj.select.onclick = function ()
			{
				this.checked = this.checked ? false : true;
			}

			// 点击
			obj.onclick = function ()
			{
				obj.select.checked = obj.select.checked ? false : true;
			}
			
		})(tr_arr[k])		
	}
	
	// *****************************************
	permissions_val = G('permissions_val');							// 权限值
	if (permissions_val)
	{
		pv_item_arr = getElementByClassName('pv_item', 'input');	// 权限选择
		pv_all_select = G('pv_all_select');							// 全选
		pv_all_unselect = G('pv_all_unselect');						// 全不选

		// 取得权限值
		pv_item_set = function ()
		{
			var pv_val_sum = 0;
			for (var k in pv_item_arr )
			{
				if (pv_item_arr[k].checked)
				{
					pv_val_sum += parseInt(pv_item_arr[k].value);
				}
			}
			// 值补0
			var pad = (4-parseInt((pv_val_sum + '').length));
			var pad_str = '';
			for (var i=0; i<pad; ++i )
				pad_str += '0';

			permissions_val.value = pad_str + pv_val_sum;
		}
		for (var k in pv_item_arr )
			pv_item_arr[k].onclick = pv_item_set;

		// 全选&全不选
		pv_select = function (status)
		{
			for (var k in pv_item_arr )
			{
				if (pv_item_arr[k].id.indexOf('s') == -1)
					pv_item_arr[k].checked = status;
			}
			pv_item_set();
			return false;
		}
		pv_all_select.onclick = function ()
		{
			return pv_select(true);
		}
		pv_all_unselect.onclick = function ()
		{
			return pv_select(false);
		}

		// 权限值改变
		permissions_val.onkeyup = function ()
		{
			var pv_object = [[0,0,0], [1,0,0], [0,1,0], [1,1,0], [0,0,1], [1,0,1], [0,1,1], [1,1,1]];
			var pv_group = ['s', 'u', 'g', 'p'];

			var pv_val = (permissions_val.value + '');
			var pv_len = pv_val.length;	

			// 值补0
			var pad = (4-parseInt(pv_len));
			if(pad < 0)
			{
				permissions_val.value = permissions_val.value.substr(0, 4);
				return false;
			}
			var pad_str = '';
			for (var i=0; i<pad; ++i )
				pad_str += '0';
			pv_val = pad_str + pv_val;

			var i = 3;
			for (; i>=0; --i )
			{
				// var pok = pv_val[i]; // ie6&7
				var pok = pv_val.substr(i, 1);
				var pv_object_tmp = pv_object[pok] ? pv_object[pok] : pv_object[0];		// 分别每组权限值, (7,5,5)
				G(pv_group[i]+'x').checked = pv_object_tmp[0];
				G(pv_group[i]+'w').checked = pv_object_tmp[1];
				G(pv_group[i]+'r').checked = pv_object_tmp[2];
			}
		}

	}


	// *****************************************
	// 主表单提交
	submit_change_form = G('submit_change_form');
	if (submit_change_form)
	{
		submit_change_form.onsubmit = function()
		{
			if (need_select_notice)
			{
				var select_sum = 0;
				for (var k in tr_arr )
				{
					if(tr_arr[k].select.checked)	
						++select_sum;
				}
				if (select_sum == 0)
				{
					alert('请先选择文件。');
					return false;		
				}

				if (submit_change_notice && submit_change_notice != '')
				{
					if (!confirm(submit_change_notice + ' (总' + select_sum + '项)' )) return false;	
				}
			}
			return true;
		}
		// 禁止回车提交(避免误操作)
		submit_change_form.onkeydown = function(e)
		{
			e = e ? e : window.event;
			if (e.keyCode == 13)
				return false;
		}
	}

	
}

// 获取class元素
var getElementByClassName = function (cls,elm) 
{  
	var arrCls = new Array();  
	var seeElm = elm;  
	var rexCls = new RegExp('(^|\\\\s)' + cls + '(\\\\s|$)','i');  
	var lisElm = document.getElementsByTagName(seeElm);  
	for (var i=0; i<lisElm.length; i++ ) 
	{  
		var evaCls = lisElm[i].className;  
		if(evaCls.length > 0 && (evaCls == cls || rexCls.test(evaCls))) 
			arrCls.push(lisElm[i]);  
	}  
	return arrCls;  
}

// 全反选 
function all_select(o)
{
	for (var k in tr_arr )
	{
		tr_arr[k].select.checked = o.checked;
		if (tr_arr[k].select.checked)
			tr_arr[k].className = tr_arr[k].className + ' trmove';
		else
		    tr_arr[k].className = tr_arr[k].className.replace(/ trmove/g, '');
	}
}


// 上传
var upload_file = function (obj, session_id)
{
	if(obj.disabled) return;
	obj.style.display = 'none';
	obj.disabled = true;

	var upload_block = G('upload_block');
	upload_block.style.display = 'inline-block';

	UploadInit(1, './index.php?c=index&a=upload&session_id=' + session_id, '10 GB', '*.*', '0', '0','_UpButtonDom', '_progressTarget', '_progressGroupTarget', '_ReturnActionId');
	// 唯一id, 上传URL, 最大大小, 允许类型, 数量, 排队数量, 按钮id, 进度id, 进度组id, 返回数据处理id
}


// 需要选择
function need_select()
{
	var exit = true;
	for (var k in tr_arr )
	{
		if(tr_arr[k].select.checked)	
		{
			exit = false;
			break;
		}
	}
	if (exit)
	{
		alert('请先选择文件。');
		return false;		
	}
	return true;
}


// 移动
function move_file(obj)
{
	if(!need_select()) return false;
	obj.disabled = true;
	G('move_block').style.display = 'inline-block';
}
// 取消移动
function move_cancel()
{
	G('move_button').disabled = false;
	G('move_block').style.display = '';
}
// 权限
function permissions_file(obj)
{
	if(!need_select()) return false;
	obj.disabled = true;
	G('permissions_block').style.display = 'inline-block';

	var Pnumber = 0;
	for (var k in tr_arr)
	{
		if(tr_arr[k].select.checked)
			Pnumber = tr_arr[k].permissions_number.value;
	}
	permissions_val.value = Pnumber;
	permissions_val.onkeyup();

}
// 取消权限
function permissions_cancel()
{
	G('permissions_button').disabled = false;
	G('permissions_block').style.display = '';
}

// 新建
function new_file(obj)
{
	obj.disabled = true;
	G('new_block').style.display = 'inline-block';
}
// 取消新建
function new_cancel()
{
	G('new_button').disabled = false;
	G('new_block').style.display = '';
}
// 远程下载
function remote_download(obj)
{
	obj.disabled = true;
	G('remote_download_block').style.display = 'inline-block';
}
// 取消远程下载
function remote_download_cancel()
{
	G('remote_download_button').disabled = false;
	G('remote_download_block').style.display = '';
}


//************************************************

// 文件上传初始化函数
var AmysqlUpObject = {};		// 上传对象
var AmysqlUpObjectId = 0;
var UploadInit = function (id, upload_url, file_size_limit, file_types, file_upload_limit, file_queue_limit, button_placeholder_id, progressTarget, progressGroupTarget, ReturnActionId)
{
	var settings = {
		flash_url: "View/js/swfupload/swfupload.swf",
		'upload_url': upload_url,
		'file_size_limit': file_size_limit,
		'file_types': file_types,
		'file_types_description': "All Files",
		'file_upload_limit': file_upload_limit,
		'file_queue_limit': file_queue_limit,
		custom_settings: {
			
			'progressTarget': progressTarget,					// 进度ID
			'progressGroupTarget' : progressGroupTarget,		// 总进度ID,为空不显示总进度
			'ReturnActionId' : ReturnActionId,					// 处理返回数据ID
			
			// progress object
			container_css: "progressobj",
			icoNormal_css: "IcoNormal",
			icoWaiting_css: "IcoWaiting",
			icoUpload_css: "IcoUpload",
			fname_css : "fle ftt",
			state_div_css : "statebarSmallDiv",
			state_bar_css : "statebar",
			percent_css : "ftt",
			href_delete_css : "ftt a",

			// 页面中不应出现以"cnt_"开头声明的元素
			s_cnt_progress: "cnt_progress",
			s_cnt_span_text : "fle",
			s_cnt_progress_statebar : "cnt_progress_statebar",
			s_cnt_progress_percent: "cnt_progress_percent",
			s_cnt_progress_uploaded : "cnt_progress_uploaded",
			s_cnt_progress_size : "cnt_progress_size"
		},
		debug: false,

		// 按钮设置
		'button_image_url': "View/images/UploadObject/AMFTP_ButtonUploadText_61x22.png",
		'button_placeholder_id': button_placeholder_id,
		button_width: 61,
		button_height: 22,

		// 进程响应
		file_dialog_complete_handler: fileDialogComplete,	// 准备上传
		file_queued_handler: fileQueued,					// 选择文件后
		file_queue_error_handler: fileQueueError,			// 选择文件不符号条件
		upload_start_handler: uploadStart,			// 上传开始
		upload_progress_handler: uploadProgress,	// 上传过程
		upload_error_handler: uploadError,			// 上传失败
		upload_success_handler: uploadSuccess,		// 上传成功
		upload_complete_handler:uploadComplete		// 上传完成

	};
	AmysqlUpObjectId = id;
	AmysqlUpObject[AmysqlUpObjectId] = new SWFUpload(settings);	//　存至AmysqlUpObject对象
}

// 转实体
function htmlspecialchars(str)  
{  
	str = str.replace(/&/g, '&amp;');
	str = str.replace(/</g, '&lt;');
	str = str.replace(/>/g, '&gt;');
	str = str.replace(/"/g, '&quot;');
	str = str.replace(/'/g, '&#039;');
	return str;
}