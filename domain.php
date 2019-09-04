<?php
$load=5;
$GLOBALS['配置']['jm']=5;
include_once(dirname(__FILE__).'/lib/conf.php');
need_login();


$btda=jd(BT_SEND('获取网站域名绑定二级目录信息', array('id'=>$user['siteid'])));
$data['二级目录']=$btda['dirs'];
$data['域名数量']=$user['域名数量'];
if($data['域名数量']==-1) $data['域名数量']='无限';
$data['二级域名列表']=$btda['binding'];
$data['绑定子目录id列表']=array();
foreach($data['二级域名列表'] as $ii) $data['绑定子目录id列表'][]=$ii['id'];
$data['子目录已绑定数量']=count($data['绑定子目录id列表']);
//print_r($data);

if(!empty(R('dela'))){
	$rid=R('dela');
	$ds=explode(':',$rid);
	$webname=$ds[0];
	$port=$ds[1];
	if(empty($port)) $port='80';
	$ret=   jd(BT_SEND('删除网站域名', 
			array(
				'id'=>$user['siteid'],
				'webname'=>$user['domain'],
				'domain'=>$webname,
				'port'=>$port,
			)
		));
		//print_r($ret);die();
	if(!$ret['status'])  redirect('?emsg='.urlencode($ret['msg']));
	redirect('?smsg='.urlencode($ret['msg']));
	
}
if(!empty(R('delb'))){
	$rid=R('delb');
	if(!in_array($rid,$data['绑定子目录id列表'])) redirect('?emsg=子目录删除失败,此项不属于你');
	$ret=jd(BT_SEND('删除网站域名绑定二级目录', ['id'=>$rid]));
	if(!$ret['status'])  redirect('?emsg='.urlencode($ret['msg']));
	redirect('?smsg='.urlencode($ret['msg']));
	
}

$data['domain_list']=  jd(BT_SEND('获取网站域名列表', 
			array(
				'search'=>$user['siteid'],
				'boolean'=>'true'
			)
		))['data'];

$data['域名已绑定数量']=count($data['domain_list'])+$data['子目录已绑定数量'];



if(!empty(R('add'))){
	$webname=R('domain');
	if($user['域名数量']!=-1 && $data['域名已绑定数量']>=$user['域名数量'])
		redirect('?emsg=绑定域名数已到达最高');
	if(R('dir')!='/'){
		if($user['子目录']==0)
			redirect('?emsg=当前空间不允许邦定子目录,详询网站客服');
	}
	if(R('dir')!='/'){
		$ret=jd(BT_SEND('设置网站域名绑定二级目录', 
			array(
				'id'=>$user['siteid'],
				'domain'=>$webname,
				'dirName'=>$data['二级目录'][R('dir')],
			)
		));
	}else{
		$ret=jd(BT_SEND('添加域名', 
			array(
				'id'=>$user['siteid'],
				'webname'=>$user['domain'],
				'domain'=>$webname,
			)
		));
		
		
	}
	if(!$ret['status'])  redirect('?emsg='.urlencode($ret['msg']));
	redirect('?smsg='.urlencode($ret['msg']));
	
}



$data['smsg']=$_GET['smsg'];
$data['emsg']=$_GET['emsg'];
//print_r($btda);


load_view('head',$data);
load_view('domain',$data);
load_view('foot');