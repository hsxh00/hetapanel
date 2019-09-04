<!--div class="page-header "><h2>后台首页</h2></div-->

<section class="clearfix"><div class="span9">

<table width="100%" border="1" cellpadding="1" cellspacing="1">
  <tr>
    <td width="17%">操作</td>
    <td width="38%">域名</td>
    <td width="">目录</td>
  </tr>
 <?php foreach($domain_list as $dli){ /* print_r($dli); */ ?>
	
  <tr>
    <td><!--<?=$dli['id']?>-->[<a href="?dela=<?=$dli['name']?><?php if($dli['port']!=80) echo ':'.$dli['port']; ?>">删除</a>]</td>
    <td>&nbsp;&nbsp;<?=$dli['name']?><?php if($dli['port']!=80) echo ':'.$dli['port']; ?></td>
    <td>&nbsp;&nbsp;/</td>
  </tr>
   
 <?php } ?>
 <?php foreach($二级域名列表 as $dli){ ?>
	
  <tr>
    <td><!--<?=$dli['id']?>-->[<a href="?delb=<?=$dli['id']?>">删除</a>]</td>
    <td>&nbsp;&nbsp;<?=$dli['domain']?><?php if($dli['port']!=80) echo $dli['port']; ?></td>
    <td>&nbsp;&nbsp;/<?=$dli['path']?></td>
  </tr>
   
 <?php } ?>
</table>
<br><br>
<table width="545" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td>域名绑定(域名绑定数量:<?=$域名已绑定数量?>/<?=$域名数量?> )</td>
  </tr>
  <tr>
    <td>
      <br />
		域名解析:<input type="text" readonly="readonly" value="<?=$GLOBALS['配置']['解析记录']?>" style="width: 80%;"/>
      <br />
      <br />
	  <form id="form1" name="form1" method="post" action="">
      绑定域名:
      <input type="text" name="domain" id="domain" style="width: 80%;"/><br><br />
      绑定目录:
      <select name="dir"style="width: 80%;">
        <option value="/">主目录<?php if($user['子目录']==0) echo '(当前不允许绑定子目录)';?></option>
        <?php if($user['子目录']!=0){ foreach($二级目录 as $li=>$value){
			echo '<option value="'.$li.'">'.$value.'</option>';
		}}?>
		
      </select><br />
      <br />
      <input type="submit" name="add" id="add" value="提交" />
    </form></td>
  </tr>
</table>
</div></div>