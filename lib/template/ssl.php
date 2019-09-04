<!--div class="page-header "><h2>后台首页</h2></div-->

<section class="clearfix"><div class="span9">
<form action="?add=y"  method="POST">
<table width="100%" border="1" cellpadding="1" cellspacing="1">
  <tr>
    <td style="">状态</td>
    <td style=""><?=($ssli['status'])?'开启':'关闭';?>&nbsp; &nbsp; &nbsp; &nbsp;<a href="?sslstop=1">关闭SSL</a></td>
  </tr>
  <tr>
    <td style="">强制https</td>
    <td style=""><?=($ssli['httpTohttps'])?'已开启':'已关闭';?>&nbsp; &nbsp; &nbsp; &nbsp; [<a href="?<?=($ssli['httpTohttps'])?'qzsslstop':'qzsslstart';?>=1"><?=($ssli['httpTohttps'])?'关闭':'开启';?>]</td>
  </tr>
  <tr>
    <td style="">密钥(KEY)</td>
    <td style=""><textarea name="key" style="width:100%;height:300px;line-height:22px"><?=$ssli['key']?></textarea></td>
  </tr>
  <tr>
    <td style="">证书(PEM格式)</td>
    <td style=""><textarea  name="csr" style="width:100%;height:300px;line-height:22px"><?=$ssli['csr']?></textarea></td>
  </tr>
  <tr>
    <td style=""></td>
    <td style=""><input type='submit' name='add' value='保存'></td>
  </tr>
  <tr>
    <td style=""></td>
    <td style="">粘贴您的*.key以及*.pem内容，然后保存即可。<a href="http://www.bt.cn/bbs/thread-704-1-1.html" class="btlink" target="_blank">[帮助]</a><br>
如果浏览器提示证书链不完整,请检查是否正确拼接PEM证书。<br>
PEM格式证书 = 域名证书.crt + 根证书(root_bundle).crt。<br>
在未指定SSL默认站点时,未开启SSL的站点使用HTTPS会直接访问到已开启SSL的站点</td>
  </tr>
</table>
</form>
<br><br>

</div></div>