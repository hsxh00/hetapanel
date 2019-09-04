<!--div class="page-header "><h2>后台首页</h2></div-->

<section class="clearfix"><div class="span9">

<table width="360" height="219" border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2">查看/修改密码</td>
  </tr>
  <tr>
    <td width="103">面板登陆密码</td>
    <td width="251"><form id="form1" name="form1" method="post" action="">
      <input type="text" name="pass" id="pass" value="<?=$user['password']?>"/>
      <input type="submit" name="loginpass" id="loginpass" value="修改" />
    </form></td>
  </tr>
  <tr>
    <td>FTP密码</td>
    <td><form id="form1" name="form1" method="post" action="">
      <input type="text" name="pass" id="pass" value="<?=$user['ftppass']?>" />
      <input type="submit" name="ftppass" id="ftppass" value="修改" />
    </form></td>
  </tr>
  <tr>
    <td>数据库密码</td>
    <td><form id="form1" name="form1" method="post" action="">
      <input type="text" name="pass" id="pass" value="<?=$user['sqlpass']?>" />
      <input type="submit" name="sqlpass" id="sqlpass" value="修改" />
    </form></td>
  </tr>
</table>


</div></div>