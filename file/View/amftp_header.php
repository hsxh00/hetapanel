<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $title;?></title>
<base href="<?php echo _Http;?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="View/css/style.css?2.5" rel="stylesheet" type="text/css" />
<link href="View/css/ico.css?2.5" rel="stylesheet" type="text/css" />
<script src="View/js/index.js?2.5" type="text/javascript"></script>
<!-- AmysqlUpObject -->
<link href="View/css/UploadObject.css?2.5" rel="stylesheet" type="text/css" />
<script src="View/js/swfupload/swfupload.js?2.5"></script>
<script src="View/js/swfupload/swfupload.queue.js?2.5"></script>
<script src="View/js/swfupload/AmysqlUploadObject.js?2.5"></script>

</head>

<body>
<div id="header"> 
<font>
<b><a href="./index.php"><img src="View/images/logo.jpg?2.5" /></a></b> 
</font> 
<span> 在线FTP管理客户端 </span>
<?php if(isset($_SESSION['ftp']['user'])) {?>
	<span>&nbsp - <?php echo $_SESSION['ftp']['ip'];?></span>
	<a id="logout" href="./index.php?c=index&a=logout"><img src="View/images/ico/logout.png" /> 退出<?php echo $_SESSION['ftp']['user'];?></a>
<?php } else {?>
<?php } ?>
</div>
