<?php
$load=5;
$GLOBALS['配置']['jm']=5;
include_once(dirname(__FILE__).'/lib/conf.php');
need_login();


$btda=jd(BT_SEND('获取网站盗链状态及规则信息', array('id'=>$user['siteid'],'name'=>$user['domain'],)));
//die(print_r($btda));
//die(print_r([$btda,$btdsid,$btdssql]));

$data['inf']=$btda;


if(!empty(R('stop'))){
	$ret=jd(BT_SEND('设置网站盗链状态及规则信息', array('id'=>$user['siteid'],'name'=>$user['domain'],'status'=>'0','domains'=>$btda['domains'],'fix'=>$btda['fix'],)));
	//print_r($ret);die();
	if(!$ret['status'])  redirect('?emsg='.urlencode($ret['msg']));
	redirect('?smsg='.urlencode($ret['msg']));
	
}


if(!empty(R('sub'))){
	
	if(empty(R('a'))) redirect('?emsg='.urlencode('设置失败'));  
	if(empty(R('u'))) redirect('?emsg='.urlencode('设置失败'));  
	$ret=jd(BT_SEND('设置网站盗链状态及规则信息', array('id'=>$user['siteid'],'name'=>$user['domain'],'status'=>'1','domains'=>R('a'),'fix'=>R('u'),)));
	//print_r($ret);die();
	if(!$ret['status'])  redirect('?emsg='.urlencode($ret['msg']));
	redirect('?smsg='.urlencode($ret['msg']));
}



$data['smsg']=$_GET['smsg'];
$data['emsg']=$_GET['emsg'];
//print_r($btda);


load_view('head',$data);
load_view('fdl',$data);
load_view('foot');