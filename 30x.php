<?php
$load=5;
$GLOBALS['配置']['jm']=5;
include_once(dirname(__FILE__).'/lib/conf.php');
need_login();

if(!empty(R('del'))){
	$ret=jd(BT_SEND('重定向2.删除', 
		array(
			'sitename'=>$user['domain'],
			'redirectname'=>R('del'),
		)
	));
	Bjg($ret);
}
if(!empty(R('start')) or !empty(R('stop'))){
	$id=(empty(R('start')))?R('stop'):R('start');
	$ret=jd(BT_SEND('重定向2.获取', array('sitename'=>$user['domain'])));
	foreach($ret as $ii) if($ii['redirectname']==$id) $info=$ii;
	$info['redirectdomain']=$info['redirectdomain'][0];
}

if(!empty(R('start'))){
	$ret=jd(BT_SEND('重定向2.修改', 
		array(
			'redirectdomain'=>'["'.$info['redirectdomain'].'"]',
			'redirectname'=>$id,
			'sitename'=>$user['domain'],
			'redirecttype'=>$info['redirecttype'],
			'tourl'=>$info['tourl'],
			'holdpath'=>$info['holdpath'],
			'domainorpath'=>$info['domainorpath'],
			'redirectpath'=>$info['redirectpath'],
			'type'=>'1',
		)
	));
	Bjg($ret);	
	
}
if(!empty(R('stop'))){
	$ret=jd(BT_SEND('重定向2.修改', 
		array(
			'redirectdomain'=>'["'.$info['redirectdomain'].'"]',
			'redirectname'=>$id,
			'sitename'=>$user['domain'],
			'redirecttype'=>$info['redirecttype'],
			'tourl'=>$info['tourl'],
			'holdpath'=>$info['holdpath'],
			'domainorpath'=>$info['domainorpath'],
			'redirectpath'=>$info['redirectpath'],
			'type'=>'0',
		)
	));
	Bjg($ret);	
	
}

$data['domain_lista']=  jd(BT_SEND('获取网站域名列表', array('search'=>$user['siteid'],'boolean'=>'true')))['data'];
$data['domain_list']=[];
foreach($data['domain_lista'] as $ii) $data['domain_list'][]=$ii['name'];
if(!empty(R('add'))){
	/* die(print_r(array(
			'redirectdomain'=>'["'.$data['domain_list'][R('redirectdomain')].'"]',
			'redirectname'=>mstime(),
			'sitename'=>$user['domain'],
			'redirecttype'=>R('redirecttype'),
			'tourl'=>R('tourl'),
			'holdpath'=>R('holdpath'),
			'domainorpath'=>R('domainorpath'),
			'redirectpath'=>R('redirectpath'),
			'type'=>'1',
		),1)); */
	$ret=jd(BT_SEND('重定向2.添加', 
		array(
			'redirectdomain'=>'["'.$data['domain_list'][R('redirectdomain')].'"]',
			'redirectname'=>mstime(),
			'sitename'=>$user['domain'],
			'redirecttype'=>R('redirecttype'),
			'tourl'=>R('tourl'),
			'holdpath'=>R('holdpath'),
			'domainorpath'=>R('domainorpath'),
			'redirectpath'=>R('redirectpath'),
			'type'=>'1',
		)
	));
	Bjg($ret);	
	
}

	

$ret=jd(BT_SEND('重定向2.获取', 
	array(
		'sitename'=>$user['domain'],
	)
));

$data['list']=$ret;
$data['smsg']=$_GET['smsg'];
$data['emsg']=$_GET['emsg'];
//print_r($btda);


load_view('head',$data);
load_view('30x',$data);
load_view('foot');