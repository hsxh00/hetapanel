<?php include('amftp_header.php');?>
<script>
var amftp_am_ftp_pwd = <?php echo json_encode($amftp_am_ftp_pwd);?>;
</script>

<div id="content">
<?php 
$back_amftp_am_ftp_pwd = explode('/', $amftp_am_ftp_pwd);
$back_amftp_am_ftp_pwd = array_slice($back_amftp_am_ftp_pwd, 0, -2);
$back_amftp_am_ftp_pwd = implode('/', $back_amftp_am_ftp_pwd);
if (!empty($notice)) { ?>
	<div id="<?php echo $notice_status;?>"><?php echo $notice;?></div>
<?php } ?>

	<div style="border:1px solid #DBDEE1;overflow: hidden;">
	<div class="title" style="">
		<input type="button" value="根目录" onclick="window.location='./index.php'" class="input_button"/> 
		<input type="button" value="返回目录" onclick="window.location='./index.php?pwd=<?php echo $amftp_am_ftp_pwd;?>'" class="input_button"/> 

		<form action="./index.php" method="GET" style="display:inline"  id="go_pwd_form">
			<input type="text" value="<?php echo $amftp_am_ftp_pwd;?>" name="pwd" id="pwd" class="input_text" style="width:590px"/> 
			<input type="submit" value="跳转"  class="input_button"/>
		</form>
		<input type="button" value="刷新" onclick="window.location='<?php echo $_SERVER['REQUEST_URI'];?>'"  class="input_button"/> 
		<br />
		
		<p style="font-size:16px;margin:12px 3px 3px 3px;">
		<?php echo $_GET['pwd'] . '<font style="font-weight:bold;">' . $_GET['file'] . '</font> <img src="View/images/ico/'. functions::Gicon($_GET['file'], '-') . '" style="margin-bottom:-3px;"/> <font style="font-size:12px;">(名称编码 ' . $_GET['charset'] . ', 内容编码 ' . ($is_gbk == 'y' ? 'gbk' : 'utf8')  . ')</font>'; ?>
		</p>
	</div>

	<?php if ($file_type == 'txt') { ?>
	
	<link rel="stylesheet" href="View/js/codemirror/codemirror.css">
	<script src="View/js/codemirror/codemirror.js"></script>
	<script src="View/js/codemirror/mode/xml.js"></script>
	<script src="View/js/codemirror/mode/javascript.js"></script>
	<script src="View/js/codemirror/mode/css.js"></script>
	<script src="View/js/codemirror/mode/clike.js"></script>
	<script src="View/js/codemirror/mode/php.js"></script>
	<script src="View/js/codemirror/mode/active-line.js"></script>
	<style type="text/css">
	.CodeMirror { 
		height:400px; 
	}
	#content .title {
	}
	</style>

	<form action="" id="file_content_form" method="POST" style="margin:0px">
			<textarea id="file_content" name="file_content"><?php echo ($file_content);?></textarea>

			<div class="title" style="">
				<input type="button" value="返回" onclick="window.location='./index.php?pwd=<?php echo $amftp_am_ftp_pwd;?>'" class="input_button" />
				<input type="submit" value="保存" name="save"  class="input_button"/> (Ctrl+S) &nbsp; 
			</div>
			<input type="hidden" name="is_gbk" value="<?php echo $is_gbk;?>" />
	</form>
	
	<script>
	CodeMirror.commands.saveaction = function (cm) {
		G('file_content_form').submit();
	};
	var runKey = ((CodeMirror.keyMap.default == CodeMirror.keyMap.macDefault) ? "Cmd" : "Ctrl") + "-S";
	var extraKeys = {};
	extraKeys[runKey] = "saveaction";

	window.editor = CodeMirror.fromTextArea(document.getElementById('file_content'), {
        mode: "application/x-httpd-php",
	    indentWithTabs: true,
	    smartIndent: true,
	    lineNumbers: true,
		styleActiveLine: true,
	    matchBrackets : true,
	    autofocus: true,
		extraKeys: extraKeys
	  });
	
	
	</script>
	<?php } elseif ($file_type == 'img') { ?>
			<img src="<?php echo $_SERVER['REQUEST_URI'];?>&img=y" style="margin:5px;"/>
			<div class="title" style="">
				<input type="button" value="返回" onclick="window.location='./index.php?pwd=<?php echo $amftp_am_ftp_pwd;?>'" class="input_button" />
				<input type="button" value="新窗口打开" onclick="window.open('<?php echo $_SERVER['REQUEST_URI'];?>&img=y')" class="input_button" />&nbsp;
			</div>
	<?php } else {?>
			<p>文件不支持直接阅读，请返回列表下载文件查看。</p>
			<div class="title" style="">
				<input type="button" value="返回" onclick="window.location='./index.php?pwd=<?php echo $amftp_am_ftp_pwd;?>'" class="input_button" />
			</div>
	<?php } ?>
	</div>
</div>



<?php include('amftp_footer.php');?>