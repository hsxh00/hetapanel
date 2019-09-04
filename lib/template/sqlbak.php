<!--div class="page-header "><h2>后台首页</h2></div-->

<section class="clearfix"><div class="span9">

<table width="100%" border="1" cellpadding="1" cellspacing="1">
  <tr>
    <td width="">文件名</td>
    <td width="17%">文件大小</td>
    <td style="width: 139px;">备份时间</td>
    <td style="width: 100px;">操作</td>
  </tr>
 <?php foreach($file_list as $dli){ /* print_r($dli); */ ?>
	
  <tr>
    <td><?=$dli['name']?></td>
    <td><?=Jsize($dli['size'])?></td>
    <td><?=$dli['addtime']?></td>
    <td>
	<!--<?=$dli['id']?>-->
	[<a href="?down=<?=$dli['id']?>">下载</a>]&nbsp;&nbsp;
	[<a href="?del=<?=$dli['id']?>">删除</a>]&nbsp;&nbsp;
	
	
	</td>
  </tr>
 <?php } ?>
  <tr>
    <td>
 <?php foreach($idns as $iid=>$iname){ /* print_r($dli); */ ?>
	[<a href="?add=<?=$iid?>">新建备份"<?=$iname?>"数据库</a>]&nbsp;&nbsp;&nbsp;&nbsp;
 
 <?php } ?></td>
    <td></td>
    <td>共<?=$file_num?>条数据</td>
    <td>
	
	
	</td>
  </tr>
   
 
</table>
<br><br>

</div></div>