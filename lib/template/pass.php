<!--div class="page-header "><h2>后台首页</h2></div-->

<section class="clearfix"><div class="span9">
<form action="?sub=y"  method="POST">
<table width="100%" border="1" cellpadding="1" cellspacing="1">
  <tr>
    <td style="">状态</td>
    <td style=""><?=($inf['pass'])?'开启':'关闭';?>&nbsp; &nbsp; &nbsp; &nbsp;<a href="?stop=1">关闭网站访问密码</a></td>
  </tr>
  <tr>
    <td style="">用户名</td>
    <td style=""><input type="text" name="u" value="<?=$inf['domains']?>" style="width:100%"</td>
  </tr>
  <tr>
    <td style="">密码</td>
    <td style=""><input type="text" name="p" value="<?=$inf['fix']?>" style="width:100%"></td>
  </tr>
  <tr>
    <td style=""></td>
    <td style=""><input type='submit' name='sub' value='保存并开启'></td>
  </tr>
  <tr>
    <td style=""></td>
    <td style="">
	注意：本系统不记录此信息，请自行牢记，如果忘记请关闭后重新提交账号密码.</ul>
	</td>
  </tr>
</table>
</form>
<br><br>

</div></div>