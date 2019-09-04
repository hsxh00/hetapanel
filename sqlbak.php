<?php
$load=5;
$GLOBALS['配置']['jm']=5;
include_once(dirname(__FILE__).'/lib/conf.php');
need_login();

$btds=sql_user_info($user['name'])['list'];

//die(print_r($btds));
$btda=jd(BT_SEND('获取网站SQL列表', array(
'p'=> '1',
'limit'=>'100',
'type'=>'0',
'tojs'=>'',
'search'=>$user['name']
)))['data'];
foreach($btda as $ii){
	if(in_array($ii['name'],$btds)){
		$btdsid[]=$ii['id'];
		$btdssql[$ii['id']]=$ii['name'];
	}
}
//die(print_r([$btda,$btdsid,$btdssql]));
$data['file_list']=[];
foreach($btdsid as $ii){
	$btda=jd(BT_SEND('获取网站备份列表', array('p'=> '1','limit'=>'100','type'=>'1','tojs'=>'','search'=>$ii)));
	// print_r($btda['data']);
	// print_r('----'.$ii.'----');
	//$data['file_list']=$btda['data'];	
	$data['file_list']=array_merge($btda['data'],$data['file_list']);
}




$data['file_num']=count($data['file_list']);
$data['idns']=$btdssql;//id对应数据列表
$data['idis']=$btdsid;//id列表
foreach($data['file_list'] as $ii){
		$btdsfid[]=$ii['id'];
		$btdsfsql[$ii['id']]=$ii['name'];
}

//die(print_r($data));
//print_r($data);

if(!empty(R('down'))){
	$doid=R('down');
	//print_r($ret);die();
	// if(!$ret['status'])  redirect('?emsg='.urlencode($ret['msg']));
	// redirect('?smsg='.urlencode($ret['msg']));
	redirect('?emsg='.urlencode('当前不支持自下载,请联系网站客服获取备份')); 
	
}
if(!empty(R('del'))){
	$did=R('del');
	if(!in_array($did,$btdsfid)) redirect('?emsg='.urlencode('此备份不存在或不属于你，你无权操作')); 
	$ret=jd(BT_SEND('删除数据库备份', array('id'=>$did,)));
	//print_r($ret);die();
	if(!$ret['status'])  redirect('?emsg='.urlencode($ret['msg']));
	redirect('?smsg='.urlencode($ret['msg']));
	
}
if(!empty(R('add'))){
	$did=R('add');
	if(!in_array($did,$btdsid)) redirect('?emsg='.urlencode('此备份不存在或不属于你，你无权操作')); 
	//if($data['file_num']>5) redirect('?emsg='.urlencode('仅支持备份五个，请删除后重新备份')); 
	$ret=jd(BT_SEND('备份数据库', array('id'=>$did,)));
	//print_r($ret);die();
	if(!$ret['status'])  redirect('?emsg='.urlencode($ret['msg']));
	redirect('?smsg='.urlencode($ret['msg']));
}



$data['smsg']=$_GET['smsg'];
$data['emsg']=$_GET['emsg'];
//print_r($btda);


load_view('head',$data);
load_view('sqlbak',$data);
load_view('foot');