<?php
$load=5;
$GLOBALS['配置']['jm']=5;
include_once(dirname(__FILE__).'/lib/conf.php');
need_login();


$btda=jd(BT_SEND('宝塔一键部署列表', array()));
//die(print_r($btda));
//die(print_r([$btda,$btdsid,$btdssql]));
//print_r($btda);
$data['inapp']=$btda['data'];


if(!empty(R('inapp'))){
	$ret=jd(BT_SEND('宝塔一键部署执行', array('dname'=>R('inapp'),'site_name'=>$user['domain'],'php_version'=>jd(BT_SEND('PHP当前版本', array('siteName'=>$user['domain'])))['phpversion'],)));
	//print_r($ret);die();
	
	if($ret['status']){
		unset($ret['msg']['download']);
		unset($ret['msg']['php']);
		unset($ret['msg']['run']);
		unset($ret['msg']['name']);
		unset($ret['msg']['type']);
		unset($ret['msg']['md5']);
		unset($ret['msg']['ps']);
		
		$ret['msg']['程序']=$ret['msg']['title'].' '.$ret['msg']['version'];
		$ret['msg']['安装或后台地址']=$ret['msg']['install'];
		$ret['msg']['配置文件']=$ret['msg']['config'];
		$ret['msg']['用户名']=$ret['msg']['username'];
		$ret['msg']['密码']=$ret['msg']['password'];
		$ret['msg']['权限chmod']=$ret['msg']['chmod'];
		$ret['msg']['扩展ext']=$ret['msg']['ext'];
		
		unset($ret['msg']['title']);
		unset($ret['msg']['version']);
		unset($ret['msg']['install']);
		unset($ret['msg']['config']);
		unset($ret['msg']['username']);
		unset($ret['msg']['password']);
		unset($ret['msg']['chmod']);
		unset($ret['msg']['ext']);
		
		redirect('?smsg='.urlencode('操作成功<br><pre>'.print_r($ret['msg'],1).'</pre>'));
	}
	redirect('?emsg='.urlencode('操作失败<br><pre>'.print_r($ret['msg'],1).'</pre>'));
	
}





$data['smsg']=$_GET['smsg'];
$data['emsg']=$_GET['emsg'];
//print_r($btda);


load_view('head',$data);
load_view('inapp',$data);
load_view('foot');