<?php
$load=5;
$GLOBALS['配置']['jm']=5;
include_once(dirname(__FILE__).'/lib/conf.php');

select_query("host", "", $where = "", $order = "",  $limit = "")

$host_size=  jd(BT_SEND('获取文件夹大小',array('path'=>$user['path'])))['size'];
$sqi=sql_user_info($user['name']);
if(host_size>=$user['网站空间大小'] || $sqi['allsizen']>=$user['数据库空间大小']){
	$ret=jd(BT_SEND('停用站点', array('id'=>$user['siteid'],'name'=>$user['domain'],))); 
}


	

