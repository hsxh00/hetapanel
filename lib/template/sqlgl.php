<!--div class="page-header "><h2>后台首页</h2></div-->

<section class="clearfix"><div class="span9">
数据库管理
<table width="90%" border="1" cellspacing="0" cellpadding="0">
<td>
 <form id="toPHPMyAdmin" action="<?=$GLOBALS['配置']['phpmyadminurl']?>" method="post"  target="_blank">
            <input type="hidden" name="pma_username" id="pma_username" value="<?=$user['name']?>" />
            <input type="hidden" name="pma_password" id="pma_password" value="<?=$user['sqlpass']?>" />
            <input type="hidden" name="server" value="1" />
            <input type="hidden" name="target" value="index.php" />
            <input type="hidden" name="db" id="db" value="" />
			<input type="submit" value="一键登陆phpmyadmin">
        </form>
	</td>
<td>
<h3>
已使用:<?=$sql_info['allsize']?>(<?=$sql_info['allsizen']?>)<br>
未使用:<?=$sql_info['wusen']?>(<?=$sql_info['wuse']?>)<br>
总	共:<?=$sql_info['alln']?>(<?=$sql_info['all']?>)
</h3>
	</td>
  </tr>
</table>
<hr>


子数据库管理(数量:<?=$sql_info['num']?>/<?=$sql_info['allnum']?>)
<table width="100%" border="1" cellpadding="1" cellspacing="1">
  <tr>
    <td width="17%">操作</td>
    <td>数据库名</td>
  </tr>
 <?php foreach($sql_info['size'] as $dli=>$dio){ /* print_r($dli); */ ?>
	
  <tr>
    <td>[<a href="?dela=<?=$dli?>">删除</a>]</td>
    <td>&nbsp;&nbsp;<?=$dli?>(使用量:<?=$dio?>)</td>
  </tr>
   
 <?php } ?>
  <tr>
    <td>新建</td>
    <td>&nbsp;&nbsp;
	<form id="form1" name="form1" method="post" action="">
      数据库名:<?=$user['name']?>_
      <input type="text" name="adda" id="adda" style="width: 50%;"/>
      <input type="submit" name="add" id="add" value="提交" />
    </form></td>
  </tr>
 
</table>


</div></div>