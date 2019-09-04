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

$data['rewtext']=$ret['data'];

$data['list']=$ret;
$data['user']=$user;

$data['smsg']=$_GET['smsg'];
$data['emsg']=$_GET['emsg'];
$data['sql_info']=sql_user_info($user['name']);
$sqlallsize=$user['数据库空间大小'];
$data['sql_info']['wuse']=$sqlallsize-$data['sql_info']['allsizen'];
$data['sql_info']['wusen']=Jsize($data['sql_info']['wuse']);
$data['sql_info']['all']=$sqlallsize;
$data['sql_info']['alln']=Jsize($data['sql_info']['all']);
$data['sql_info']['num']=count($data['sql_info']['list'])-1;
$data['sql_info']['allnum']=$user['子数据库'];

$dela=R('dela');
if(!empty($dela)){
	if($dela==$user['name'])  Bno('禁止删除主数据库');
	$zsqlname=explode($user['name'].'_',$dela)[1];
	if(empty($zsqlname))   Bno('数据库不存在');
	if(!in_array($user['name'].'_'.$zsqlname,$data['sql_info']['list']))   Bno('数据库不存在');
	sqlku_del($user['name'].'_'.$zsqlname);
	Bok('操作成功');
}
$adda=R('adda');
if(!empty($adda)){
	if(!ctype_alnum($adda)) Bno('数据库名非法!');
	if(strstr($adda, 'information_schema')!=false) Bno('数据库名非法.');
	if(strstr($adda, 'mysql')!=false) Bno('数据库名非法....');
	if(strstr($adda, 'performance_schema')!=false) Bno('数据库名非法..');
	if(strstr($adda, 'test')!=false) Bno('数据库名非法.....');
	if(in_array($user['name'].'_'.$adda,$data['sql_info']['list']))   Bno('数据库存在');
	if($data['sql_info']['num']>=$data['sql_info']['allnum'])   Bno('已达到或超量,无法新增子数据库');
	sqlku_add($user['name'].'_'.$adda,$user['name']);
	Bok('操作成功');
}



/* print_r($data['sql_info']); */

/* Array
(
    [list] => Array
        (
            [0] => hkfbew_142
            [1] => hkfbew_142_1
        )

    [size] => Array
        (
            [hkfbew_142] => 64KB
            [hkfbew_142_1] => 128KB
        )

    [allsize] => 192KB
    [allsizen] => 196608
) */

load_view('head',$data);
load_view('sqlgl',$data);
load_view('foot');