<?php include('amftp_header.php');?>

<div id="content" style="margin-left:20px;">
<?php if (!empty($notice)) { ?>
	<div id="notice"><?php echo $notice;?></div>
<?php } ?>
<form action="./index.php?c=index&a=amftp_login" method="POST">
<dl> <dd>IP域名 / 端口: </dd><dt><input type="text" id="ftp_ip" name="ftp_ip" value="<?php echo $ftp_ip;?>" class="input_text" style="width:190px"/>
<input type="text" id="ftp_port" name="ftp_port" value="21" class="input_text" style="width:25px"/></dt> </dl>
<dl> <dd>用户名: </dd><dt><input type="text" name="ftp_user" value="<?php echo $ftp_user;?>"  class="input_text"/> </dt> </dl>
<dl> <dd>密码: </dd><dt><input type="password" name="ftp_pass"  class="input_text"/> </dt> </dl>
<input type="submit" name="submit" value=" 登录 " class="input_button"/>
</form>

</div>


<?php include('amftp_footer.php');?>