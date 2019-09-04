<?php
$load=5;
$GLOBALS['配置']['jm']=5;
include_once(dirname(__FILE__).'/lib/conf.php');
need_login();


$text=R('text');
$edit=R('edit');
$ps=0;
if(!empty($text) && !empty($edit) ){
		$ret=jd(BT_SEND('设置网站默认文件', 
			array(
				'id'=>$user['siteid'],
				'Index'=>$text,
			)
		));
		
		if(!$ret['status'])  redirect('?emsg='.urlencode($ret['msg']));
		redirect('?smsg='.urlencode($ret['msg']));
}
	

$ret=jd(BT_SEND('获取网站默认文件', 
	array(
		'id'=>$user['siteid'],
	)
));

$data['text']=$ret;
$data['smsg']=$_GET['smsg'];
$data['emsg']=$_GET['emsg'];
//print_r($btda);


load_view('head',$data);
load_view('defaultindex',$data);
load_view('foot');