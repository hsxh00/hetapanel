<?php
$load=5;
include_once(dirname(__FILE__).'/lib/conf.php');
//http://btpanel.ccc.ci/api.php?m=add&name=s123456789&pass=1234&sql=true&ftp=true&port=80&php_v=56&ps=test&model=all&zml=1&domainnum=10&websize=102400&sqlsize=102400&zsjk=5

if(R('m')=='login'){
	$user=R('user');
	$sign=R('sign');
	$uua=get_query_vals('host', '*', array('name'=>$user));
	$ukey=md5($uua['快速登陆key'].date('YmdH').$user);
	if($sign!=$ukey) die(print_r(['status'=>0,'msg'=>'签名验证失败,可能是配置错误或者服务器时间不对造成的,请等待5分钟尝试重新登陆,如若还不行请联系网站管理员','time'=>time()],1));
	

	if($data['是否被暂停']=='1')	ddie($data,'主机已暂停,详询客服');
	if($uua['数据库id']<1 || $uua['ftpid']<1 || $uua['siteid']<1)	ddie($data,'主机开通时候出现错误,初始化失败,请联系客服完善相关信息');
	session_start();
	
	session_w('userid', $uua['id']);
	redirect('/index.php');
	
}



$jssign=md5(md5($GLOBALS['配置']['操作接口密码']).R('yzm'));
if(R('sign') != $jssign)
	die(json_encode(['status'=>0,'msg'=>'sign is fail','time'=>time()],1));
if(R('m')=='add'){
	$pdd=[];
	$name=R('name');
	$pass=R('pass');
	$sql=R('sql');
	$ftp=R('ftp');
	$port=R('port');
	$version=R('php_v');
	$ps=R('ps');
	$pdd['model']=R('model');
	$pdd['子目录']=R('zml');
	$pdd['域名数量']=R('domainnum');
	$pdd['网站空间大小']=R('websize');
	$pdd['数据库空间大小']=R('sqlsize');
	$pdd['子数据库']=R('zsjk');
	$uua=get_query_vals('host', '*', array('name'=>$name));
	if($uua['id']>0) die(json_encode(['status'=>0,'msg'=>'用户名已存在','time'=>time()],1));
	
	$p_data['webname'] = '{"domain":"'.$name.$GLOBALS['配置']['赠送域名'].'","domainlist":[],"count":0}';//网站域名 json格式
	$p_data['path'] = $GLOBALS['配置']['网站目录'].$name;//网站路径
	$p_data['type_id'] = '2';//网站分类ID
	$p_data['type'] = 'PHP';//网站类型
	$p_data['version'] = $version;//PHP版本
	$p_data['port'] = $port;//网站端口
	$p_data['ps'] = $ps;//网站备注
	$p_data['ftp'] = $ftp;//网站是否开通FTP
	$p_data['ftp_username'] = $name;//FTP用户名
	$p_data['ftp_password'] = $pass;//FTP密码
	$p_data['sql'] = $sql;//网站是否开通数据库
	$p_data['codeing'] = 'utf8mb4';
	$p_data['datauser'] = $name;//
	$p_data['datapassword'] = $pass;//
	$ret=jd(BT_SEND('新增网站', $p_data));
	$msg='';
	if($ret['ftpStatus']!='1'  &&  $ftp=='true')$msg.='[FTP创建失败]';
	if($ret['databaseStatus']!='1' &&  $sql=='true' )$msg.='[数据库创建失败]';
	if($ret['siteStatus']!='1')$msg.='[网站创建失败]';
	if(!empty($msg)){
		$msg.='开通失败 请联系管理员操作';
		die(json_encode(['status'=>0,'msg'=>[$msg,$ret],'time'=>time()],1));
	}
	$infoi['open']	=		$ret;
	$infoi['host']	=		jd(BT_SEND('获取网站列表', array('search'=>$name.$GLOBALS['配置']['赠送域名'],'p'=>'1','limit'=>'15','type'=>'-1','order'=>'id desc','tojs'=>'',)))['data'][0];
	$infoi['ftp']	=		jd(BT_SEND('获取网站FTP列表', array('search'=>$name,'p'=>'1','limit'=>'15','type'=>'-1','order'=>'id desc','tojs'=>'',)))['data'][0];
	$infoi['sql']	=		jd(BT_SEND('获取网站SQL列表', array('search'=>$name,'p'=>'1','limit'=>'15','type'=>'-1','order'=>'id desc','tojs'=>'',)))['data'][0];
	/* print_r($infoi);
	die(); */
	
	$pdd['name']=$name;
	$pdd['domain']=$name.$GLOBALS['配置']['赠送域名'];
	$pdd['password']=$pass;
	$pdd['ftppass']=$pass;
	$pdd['sqlpass']=$pass;
	
	$pdd['数据库id']=$infoi['sql']['id'];
	$pdd['ftpid']=$infoi['ftp']['id'];
	$pdd['siteid']=$infoi['host']['id'];

	$pdd['opentime']=date('Y-m-d H:i:s',time());
	$pdd['status']='1';
	$pdd['是否被暂停']='0';
	$pdd['addtime']=$pdd['opentime'];
	$pdd['path']=$p_data['path'];
	$pdd['快速登陆key']=md5(time().$name);
	$infoi['key']=$pdd['快速登陆key'];
	insert_query('host', $pdd);
	die(json_encode(['status'=>1,'msg'=>'','time'=>time(),'data'=>$infoi],1));
	
}


$user=R('user');
$uua=get_query_vals('host', '*', array('name'=>$user));
if($uua['id']<1) die(json_encode(['status'=>0,'msg'=>'主机不存在'.$user,'time'=>time()],1));
if(R('m')=='start'){
	
	update_query('host', ['是否被暂停'=>'0'], array('name'=>$user));
	$ret=jd(BT_SEND('启用网站', array('id'=>$uua['siteid'],'name'=>$uua['domain'],)));
	die(json_encode($ret));
}
if(R('m')=='stop'){
	update_query('host', ['是否被暂停'=>'1'], array('name'=>$user));
	$ret=jd(BT_SEND('停用站点', array('id'=>$uua['siteid'],'name'=>$uua['domain'],)));
	die(json_encode($ret));
}
if(R('m')=='del'){
	$ret=jd(BT_SEND('删除网站', array('id'=>$uua['siteid'],'webname'=>$uua['domain'],'ftp'=>'1','database'=>'1','path'=>'1',)));
	if($ret['status']){$ret['status']=1; delete_query('host', array('name'=>$user));}
	//print_r($ret);
	die(json_encode($ret));
}


