<!--div class="page-header "><h2>后台首页</h2></div-->

<section class="clearfix"><div class="span9">
<form action="?sub=y"  method="POST">
<table width="100%" border="1" cellpadding="1" cellspacing="1">
	<tr>
		<td colspan="5">
			一键部署程序
		</td>
	</tr>
	<tr>
		<td colspan="5">
			注意：请按照推荐PHP版本进行安装，如果程序在运行中出现异常，请调整PHP版本的设置。<hr>
			部署时长不定，取决于网络状况，所以如果出现很久没有响应的也不要紧张。<hr>
			一键部署程序不会清空网站内容，所以在部署前请尽量保证网站内容为空，避免造成文件/文件夹丢失。<hr>
			该功能由宝塔提供技术支持，如果多次出现程序部署失败，请联系本站管理员。
		</td>
	</tr>
	<tr><th>名称</th><th>版本</th><th>简介</th><th>支持PHP版本</th><th>操作</th></tr>
<?php foreach( $inapp as $ii){  ?>
	<tr>
		<td><?=$ii['title']?></td>
		<td><?=$ii['version']?></td>
		<td><?=$ii['ps']?></td>
		<td><?=$ii['php']?></td>
		<td><a href="?inapp=<?=$ii['name']?>">一键部署</a></td>
	</tr>
  <?php }  ?>
</table>
</form>
<br><br>

</div></div>