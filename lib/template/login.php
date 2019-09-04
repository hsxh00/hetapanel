<!DOCTYPE html>
<html>
 <head>
  <title>主机控制面板登录</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="UTF-8" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link href="https://dn-idcswap-down.qbox.me/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="https://dn-idcswap-down.qbox.me/assets/css/modern.min.css" rel="stylesheet" type="text/css" />
  <style rel="stylesheet" type="text/css" />
  body,.page-login,.page-content{
	  font-family: 'Arial','Microsoft YaHei','黑体','宋体',sans-serif;background-color: #f9f9f9; color: #797979;background: #f9f9f9;padding: 0px !important;margin: 0px !important;width: 100%;height: 100%;
  }
  </style>
 </head>

 <body class="page-login" gtools_scp_screen_capture_injected="true">
  <main class="page-content">
   <div class="page-inner">
    <div id="main-wrapper">
     <div class="row">
      <div class="col-md-3 center">
       <div class="login-box">
        <a href="/<?php echo RKWJM;?>/index/index/" class="logo-name text-lg text-center">主机控制面板</a>
        <h3 class="text-center"><?php if($smsg != ""){ ?><?php echo $smsg;?><?php } ?><?php if($emsg != ""){ ?><?php echo $emsg;?><?php } ?></h3>
        <form class="m-t-md login"  method="post" >
         <div class="form-group">
          <input type="text" name="u" class="form-control" placeholder="请填写用户名" required="" />
         </div>
         <div class="form-group">
          <input type="password" name="p" class="form-control" placeholder="请输入密码" required="" />
         </div>
        <button type="submit" class="btn btn-success btn-block btn-login">登录</button>
        </form>
        <p class="text-center m-t-xs text-sm">2015 &copy; Powered by SWAPTEAM.</p>
       </div>
      </div>
     </div>
    </div>
   </div>
  </main>
 </body>
</html>



