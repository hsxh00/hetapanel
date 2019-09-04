<?php
$load=5;
$GLOBALS['配置']['jm']=5;
include_once(dirname(__FILE__).'/lib/conf.php');
need_login();
$ret=jd(BT_SEND('获取可选的预定义伪静态列表', 
		array(
			'siteName'=>$user['domain'],
		)
	));
	//Bjg($ret);
	//print_r($ret);die();
$data['rew_list']=$ret['rewrite'];
$set_def=R('set_def');
if(!empty($set_def)){
	$rew_n=$data['rew_list'][$set_def];
	if(!empty($rew_n)){
		$ret=jd(BT_SEND('获取预置伪静态规则', 
			array(
				'path'=>'/www/server/panel/rewrite/'.$GLOBALS['配置']['webserver'].'/'.$rew_n.'.conf',
			)
		));
		
		
		$ret=jd(BT_SEND('保存伪静态规则', 
			array(
				'path'=>'/www/server/panel/vhost/rewrite/'.$user['domain'].'.conf',
				'data'=>$ret['data'],
				'encoding'=>'utf-8',
			)
		));
		Bjg($ret);
		
	}
}
$rew_text_post=R('rew_text');
if(!empty($rew_text_post)){
		$ret=jd(BT_SEND('保存伪静态规则', 
			array(
				'path'=>'/www/server/panel/vhost/rewrite/'.$user['domain'].'.conf',
				'data'=>$rew_text_post,
				'encoding'=>'utf-8',
			)
		));
		Bjg($ret);
}
$ret=jd(BT_SEND('获取预置伪静态规则', 
		array(
			'path'=>'/www/server/panel/vhost/rewrite/'.$user['domain'].'.conf',
		)
));

$data['rewtext']=$ret['data'];

$data['list']=$ret;
$data['smsg']=$_GET['smsg'];
$data['emsg']=$_GET['emsg'];
//print_r($btda);


load_view('head',$data);
load_view('rewrite',$data);
load_view('foot');