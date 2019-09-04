<?php
$load=5;
$GLOBALS['配置']['jm']=5;
include_once(dirname(__FILE__).'/lib/conf.php');
need_login();


$btda=jd(BT_SEND('获取网站备份列表', array(
'p'=> '1',
'limit'=>'100',
'type'=>'0',
'tojs'=>'',
'search'=>$user['siteid']
)));
//die(print_r($btda));

$data['file_list']=$btda['data'];
$data['file_num']=count($btda['data']);
//print_r($data);

if(!empty(R('down'))){
	$doid=R('down');
	//print_r($ret);die();
	// if(!$ret['status'])  redirect('?emsg='.urlencode($ret['msg']));
	// redirect('?smsg='.urlencode($ret['msg']));
	redirect('?emsg='.urlencode('当前不支持自下载,请联系网站客服获取备份')); 
	
}
if(!empty(R('del'))){
	$ids=[];
	foreach($data['file_list'] as $ii){
		$ids[]=$ii['id'];
	}
	$did=R('del');
	if(!in_array($did,$ids)) redirect('?emsg='.urlencode('此备份不存在或不属于你，你无权操作')); 
	$ret=jd(BT_SEND('删除网站备份', array('id'=>$did,)));
	//print_r($ret);die();
	if(!$ret['status'])  redirect('?emsg='.urlencode($ret['msg']));
	redirect('?smsg='.urlencode($ret['msg']));
	
}
if(!empty(R('add'))){
	if($data['file_num']>5) redirect('?emsg='.urlencode('仅支持备份五个，请删除后重新备份')); 
	$ret=jd(BT_SEND('创建网站备份', array('id'=>$user['siteid'],)));
	//print_r($ret);die();
	if(!$ret['status'])  redirect('?emsg='.urlencode($ret['msg']));
	redirect('?smsg='.urlencode($ret['msg']));
}



$data['smsg']=$_GET['smsg'];
$data['emsg']=$_GET['emsg'];
//print_r($btda);


load_view('head',$data);
load_view('sitebak',$data);
load_view('foot');