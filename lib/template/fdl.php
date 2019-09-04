<!--div class="page-header "><h2>后台首页</h2></div-->

<section class="clearfix"><div class="span9">
<form action="?sub=y"  method="POST">
<table width="100%" border="1" cellpadding="1" cellspacing="1">
  <tr>
    <td style="">状态</td>
    <td style=""><?=($inf['status'])?'开启':'关闭';?>&nbsp; &nbsp; &nbsp; &nbsp;<a href="?stop=1">关闭防盗链</a></td>
  </tr>
  <tr>
    <td style="">许可域名</td>
    <td style=""><input type="text" name="a" value="<?=$inf['domains']?>" style="width:100%"</td>
  </tr>
  <tr>
    <td style="">URL后缀</td>
    <td style=""><input type="text" name="u" value="<?=$inf['fix']?>" style="width:100%"></td>
  </tr>
  <tr>
    <td style=""></td>
    <td style=""><input type='submit' name='sub' value='保存'></td>
  </tr>
  <tr>
    <td style=""></td>
    <td style="">
	<ul class="help-info-text c7"><li>默认允许资源被直接访问,即不限制HTTP_REFERER为空的请求</li><li>多个URL后缀与域名请使用逗号(,)隔开,如: png,jpeg,zip,js</li><li>当触发防盗链时,将直接返回404状态</li></ul>
	</td>
  </tr>
</table>
</form>
<br><br>

</div></div>