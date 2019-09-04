<?php
$load=5;
$GLOBALS['配置']['jm']=5;
include_once(dirname(__FILE__).'/lib/conf.php');
need_login();


$pass=R('pass');
$ps=0;
if(!empty($pass)){
	if(!empty(R('loginpass'))){
		update_query('host', ['password'=>$pass], ['id'=>$user['id']]);
		$ret['status']='1';
		$ret['msg']='密码修改成功';
		$ps=1;
	}
	if(!empty(R('ftppass'))){
		$ret=jd(BT_SEND('修改FTP账号密码', 
			array(
				'id'=>$user['ftpid'],
				'ftp_username'=>$user['name'],
				'new_password'=>$pass,
			)
		));
		if($ret['status'])  update_query('host', ['ftppass'=>$pass], ['id'=>$user['id']]);
		$ps=1;
	}
	if(!empty(R('sqlpass'))){	
		$ret=jd(BT_SEND('修改SQL账号密码', 
			array(
				'id'=>$user['数据库id'],
				'name'=>$user['name'],
				'password'=>$pass,
			)
		));
		if($ret['status'])  update_query('host', ['sqlpass'=>$pass], ['id'=>$user['id']]);
		$ps=1;
	}
	if($ps==1){
		if(!$ret['status'])  redirect('?emsg='.urlencode($ret['msg']));
		redirect('?smsg='.urlencode($ret['msg']));
	}

}
	



$data['smsg']=$_GET['smsg'];
$data['emsg']=$_GET['emsg'];
//print_r($btda);


load_view('head',$data);
load_view('editpass',$data);
load_view('foot');