<!DOCTYPE html>
<html lang="en">
  <head>
    
   <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- meta name="viewport" content="width=device-width, initial-scale=1"-->
    
    <title>主机控制面板  <?php echo $user['name'];?><?php echo date("Y-m-d H:i:s");?></title>

    <!-- CSS -->
    <link rel="stylesheet" href="http://static.8889838.com/spanel/spanel.css">
   <script src="//cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
		<script src="//cdn.bootcss.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

    
  </head>
  <body>
  <div id="fb-root"></div>
    <div class="container">
      <div class="navbar navbar-default" role="navigation">
        <div class="navbar-header">  
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">主机控制面板</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/<?php echo RKWJM;?>/Index/index/">主机控制面板</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav" style="width: auto;">
            <li><a href="/index.php">首页</a></li>
            <?php if($_SESSION['userid']!=''){?>
			<li><a href="/domain.php">域名绑定</a></li>
			<li><a href="/sql.php">数据库管理</a></li>
			<li><a href="/backup.php">数据库备份</a></li>
			<?php } ?>
          </ul>
		<?php if($_SESSION['userid']!=''){?>
		<ul class="nav navbar-nav navbar-right">
			<li><a href="/index.php"><?php echo $user['name'];?>(id:<?php echo uid();?>)</a></li>
			<li><a href="/exit.php">退出登陆</a></li>
		</ul>
		<?php } ?>
        </div>
      </div>
 <?php if($smsg != ""){ ?><div class="alert alert-success" role="alert"><?php echo $smsg;?></div><?php } ?>
 <?php if($emsg != ""){ ?><div class="alert alert-danger" role="alert"><?php echo $emsg;?></div><?php } ?>





