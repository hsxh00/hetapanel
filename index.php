<?php
$load=5;
$GLOBALS['配置']['jm']=5;
include_once(dirname(__FILE__).'/lib/conf.php');
need_login();
$data['host_info']=  jd(BT_SEND('获取网站列表', 
							array(
								'search'=>$user['domain'],
								'p'=>'1',
								'limit'=>'15',
								'type'=>'-1',
								'order'=>'id desc',
								'tojs'=>'',
							)
						))['data'][0];
$data['ftp_info']=  jd(BT_SEND('获取网站FTP列表', 
							array(
								'search'=>$user['name'],
								'p'=>'1',
								'limit'=>'15',
								'type'=>'-1',
								'order'=>'id desc',
								'tojs'=>'',
							)
						))['data'][0];
						//print_r($data['ftp_info']);die(time());

$data['host_size']=  jd(BT_SEND('获取文件夹大小', 
							array(
								'path'=>$user['path']
							)
						))['size'];	
$plt=jd(BT_SEND('PHP版本列表', array('siteName'=>$user['domain'])));
//print_r($plt);
//$data['phplist']=[];
foreach($plt as $ii){
	//print_r($ii);
	$data['phplist'][$ii['version']]=$ii['name'];
}
$data['sql_info']=sql_user_info($user['name']);
$sqlallsize=$user['数据库空间大小'];
$data['sql_info']['wuse']=$sqlallsize-$data['sql_info']['allsizen'];
$data['sql_info']['wusen']=Jsize($data['sql_info']['wuse']);
$data['sql_info']['all']=$sqlallsize;
$data['sql_info']['alln']=Jsize($data['sql_info']['all']);
$data['sql_info']['num']=count($data['sql_info']['list'])-1;
$data['sql_info']['allnum']=3;

						
$sgst=R('sitestatus');
$setphpv=R('setphpv');
if(!empty($setphpv)){
	$ret=jd(BT_SEND('修改PHP版本', 
		array(
			'siteName'=>$user['domain'],
			'version'=>$setphpv,
		)
	));
	Bjg($ret);
}

 

if($sgst=='start' ||$sgst=='stop'){
	if($data['host_size']>=$user['网站空间大小'] || $data['sql_info']['allsizen']>=$user['数据库空间大小']) redirect('?emsg='.urlencode('操作失败,资源超限'));
	$zzist=($sgst=='start')?'启用网站':'停用站点';
	$ret=jd(BT_SEND($zzist, 
		array(
			'id'=>$user['siteid'],
			'name'=>$user['domain'],
		)
	));
	Bjg($ret);
}
if($sgst=='start_ftp' ||$sgst=='stop_ftp'){
	$zzist=($sgst=='start_ftp')?'1':'0';
	$ret=jd(BT_SEND('启用禁用FTP', 
		array(
			'id'=>$user['ftpid'],
			'username'=>$user['name'],
			'status'=>$zzist,
		)
	));
	Bjg($ret);
}
$data['smsg']=$_GET['smsg'];
$data['emsg']=$_GET['emsg'];
$data['ipage']='index';
load_view('head',$data);
load_view('index',$data);
load_view('foot');