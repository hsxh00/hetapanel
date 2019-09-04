<!--div class="page-header "><h2>后台首页</h2></div-->

<section class="clearfix"><div class="span9">
伪静态规则配置
<table width="90%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <th width="39%" scope="col">设置预制伪静态(电击名称以设置相应的伪静态)</th>
  </tr>

  <tr>
    <td>
 <?php foreach($rew_list as $i=>$ii){
 echo '<a href="?set_def='.$i.'">'.$ii.'</a>&nbsp;&nbsp;&nbsp;&nbsp;';
 
 //print_r($ii);
 }?>
	</td>
  </tr>
</table>
<hr>
<form id="form1" name="form1" method="post" action="">
  
<table width="90%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <th width="39%" scope="col">设置伪静态</th>
  </tr>

  <tr>
    <td>
<textarea style="margin: 0px; width: 994px; height: 554px;" name="rew_text"><?=$rewtext?></textarea><br><input name="submit" type="submit" value="保存修改">
	</td>
  </tr>

    <tr>
      <td>
        <ul class="help-info-text c7"><li>请选择您的应用，若设置伪静态后，网站无法正常访问，请尝试设置回default</li><li>您可以对伪静态规则进行修改，修改完后保存即可。</li></ul>
      </td>
    </tr>
  </table>
</form>

</div></div>