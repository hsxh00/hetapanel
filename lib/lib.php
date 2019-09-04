<?php
if($load!=5) die('..');
function text_qucenter($input, $start, $end){
    $substr = substr($input, strlen($start) + strpos($input, $start), (strlen($input) - strpos($input, $end)) * -1);
    return $substr;
}
function _text_qucenter($input, $start, $end, $head = ''){
    $a = mb_strpos($input, $start);
    if ($a === false) {
        return '';
    }
    $a = $a + strlen($start);
    $a = mb_substr($input, $a, strlen($input) - $a);
    $substr = mb_substr($a, 0, mb_strpos($a, $end));
    return $head . $substr;
}

function eecho($bt, $text){
	insert_query('调试日志', array('time' => date("Y-m-d H:i:s"), 'bt' => $bt, 'text' => print_r($text, 1)));
}
function jishi(){
	$old=$GLOBALS['jishi'];
	$new=microtime(true);
	$GLOBALS['jishi']=$new;
	return round($new-$old,4);
}
function redirect($uri = '', $code = NULL){
    if (isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') !== FALSE) {
        $method = 'refresh';
    } elseif ($method !== 'refresh' && (empty($code) or !is_numeric($code))) {
        if (isset($_SERVER['SERVER_PROTOCOL'], $_SERVER['REQUEST_METHOD']) && $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.1') {
            $code = $_SERVER['REQUEST_METHOD'] !== 'GET' ? 303 : 307;
        } else {
            $code = 302;
        }
    }
    switch ($method) {
        case 'refresh':
            header('Refresh:0;url=' . $uri);
            break;
        default:
            header('Location: ' . $uri, TRUE, $code);
            break;
    }
    exit;
}
function domain_hq($urls){
	$url=$urls;
	if(substr($url,0,8)=="https://") $l=8;
	if(substr($url,0,7)=="http://") $l=7;
	if($l<7) return '';
	$url=substr($url,$l);
	return substr($url,0,strpos($url,"/"));
}

function user_lo(){
	if (session_r('userid') != ''){return true;}else{return false;}
}
function need_login(){
    if (!user_lo()) die(redirect('/login.php').'请先登录');
}
function uid(){
	return session_r('userid');
}
function load_view($name,$bl=array()){
	foreach($bl as $_bl=>$_bbl) $$_bl=$_bbl;
	if(empty($smsg)) $smsg=$_GET['smsg'];
	if(empty($emsg)) $emsg=$_GET['emsg'];	
	require_once(dirname(__FILE__).'/template/'.$name.'.php');
}

function session_r($name){
	return $_SESSION[$name];
}
function session_w($name,$nr){
	$_SESSION[$name]=$nr;
}
function session_d($name,$nr){
	$_SESSION[$name]=null;
	unset($_SESSION[$name]);
}
function R($name){return $_REQUEST[$name];}


function PPOST($url, $postdata, &$aaa,$post=1,$proxy_text){
    //$proxy_text = $GLOBALS['proxy_ip'];
    if (is_array($postdata)) {
        $postdata_ = http_build_query($postdata);
    } else {
        $postdata_ = $postdata;
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, $post);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//不验证证书
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);//不验证证书
    if (!empty($proxy_text)) curl_setopt($ch, CURLOPT_PROXY, $proxy_text);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata_);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $data = curl_exec($ch);
    $aaa = true;
    if ($data === false) {
        $aaa = false;
        $data .= "网络通讯异常请稍后再试";
    }
	//print_r( curl_getinfo($ch));
    curl_close($ch);
    if (empty($proxy_text)) {
        $data = $data . ' -.';
    }
    return $data;
}
function GGET($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//不验证证书
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);//不验证证书
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $data = curl_exec($ch);
    if ($data === false) $data .= "网络通讯异常请稍后再试";
    return $data;
}
function jd($text){
	return json_decode($text,1);
}
function mstime() {
	list($t1, $t2) = explode(' ', microtime());
    return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
}
function Bjg($ret){
	if(!$ret['status'])  redirect('?emsg='.urlencode($ret['msg']));
	redirect('?smsg='.urlencode($ret['msg']));
}
function Bok($ret){
	redirect('?smsg='.urlencode($ret));
}
function Bno($ret){
	redirect('?emsg='.urlencode($ret));
}
function BT_SEND($m, $data,$timeout = 60){
	$time=time();
    $data['request_time']=$time;
	$data['request_token']=md5($time.''.md5($GLOBALS['配置']['btkey']));
	
	//print_r($data);print_r($GLOBALS['配置']['bt地址'].''.$GLOBALS['访问模块'][$m]);
	$cookie_file='./'.md5($GLOBALS['配置']['bt地址']).'.cookie';
	if(!file_exists($cookie_file)){$fp = fopen($cookie_file,'w+');fclose($fp);}
		
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $GLOBALS['配置']['bt地址'].''.$GLOBALS['访问模块'][$m]);
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$output = curl_exec($ch);
	curl_close($ch);
	//print_r($output);
	// print_r($GLOBALS['配置']['bt地址'].''.$GLOBALS['访问模块'][$m]);
	// print_r($data);
	return $output;
}
function sql_user_info($sqluser){
	//$sqluser=str_replace('_','\_',$sqluser);
	$db = new mysqli($GLOBALS['配置']['sql']['ip'], $GLOBALS['配置']['sql']['name'], $GLOBALS['配置']['sql']['pass'], '', $GLOBALS['配置']['sql']['port']);
	if ($db->connect_errno) errorc__(print_r(array('数据库链接出错',$db->connect_errno,$db->connect_error),1));
	$db->query("set time_zone = '+8:00';");
	$db->query("SET sql_mode='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';");
	$db->query("SET NAMES UTF8");
	$dbb=$db->query("SHOW DATABASES where `database` not in ('information_schema','mysql','performance_schema') and (`database`='{$sqluser}' or `database` like '{$sqluser}\_%')");
	$databases=[];
	while($di=$dbb->fetch_array()) $databases[]=$di[0];
	$ret=[];
	$ret['list']=$databases;
	$ret['size']=[];
	$ret['allsize']=0;
	foreach($databases as $dii){
		$dbb=$db->query("select sum(data_length+index_length) from information_schema.tables where table_schema = '{$dii}' ");
		
		if($idd=$dbb->fetch_array()){
			$ret['size'][$dii]=Jsize($idd[0]);
			$ret['allsize']=$ret['allsize']+$idd[0];
		}
	}
	$ret['allsizen']=$ret['allsize'];
	$ret['allsize']=Jsize($ret['allsize']);
	$db->close();
	return $ret;
}
function sqlku_del($name){
	//$name=str_replace('_','\_',$name);
	$db = new mysqli($GLOBALS['配置']['sql']['ip'], $GLOBALS['配置']['sql']['name'], $GLOBALS['配置']['sql']['pass'], '', $GLOBALS['配置']['sql']['port']);
	if ($db->connect_errno) errorc__(print_r(array('数据库链接出错',$db->connect_errno,$db->connect_error),1));
	$db->query("set time_zone = '+8:00';");
	$db->query("SET sql_mode='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';");
	//
	//
	$db->query("SET NAMES UTF8");
	$dbb=$db->query("DROP DATABASE `{$name}`;");
	if ($db->errno > 0)  die(print_r(array($db->errno,$db->error),1));
	$db->close();
	return $ret;
}
function sqlku_add($name,$user){
	//$name=str_replace('_','\_',$name);
	//$user=str_replace('_','\_',$user);
	$db = new mysqli($GLOBALS['配置']['sql']['ip'], $GLOBALS['配置']['sql']['name'], $GLOBALS['配置']['sql']['pass'], '', $GLOBALS['配置']['sql']['port']);
	if ($db->connect_errno) errorc__(print_r(array('数据库链接出错',$db->connect_errno,$db->connect_error),1));
	$db->query("set time_zone = '+8:00';");
	$db->query("SET sql_mode='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';");
	$db->query("SET NAMES UTF8");
	$dbb=$db->query("CREATE DATABASE `{$name}` /*!40100 COLLATE 'utf8mb4_general_ci' */;");
	//print_r(["CREATE DATABASE `{$name}` /*!40100 COLLATE 'utf8mb4_general_ci' */;","GRANT SELECT, EXECUTE, SHOW VIEW, ALTER, ALTER ROUTINE, CREATE, CREATE ROUTINE, CREATE TEMPORARY TABLES, CREATE VIEW, DELETE, DROP, EVENT, INDEX, INSERT, REFERENCES, TRIGGER, UPDATE, LOCK TABLES  ON `{$name}`.* TO '{$user}' WITH GRANT OPTION;"]);
	//die(); 
	if ($db->errno > 0)  die(print_r(array($db->errno,$db->error),1));
	$dbb=$db->query("GRANT SELECT, EXECUTE, SHOW VIEW, ALTER, ALTER ROUTINE, CREATE, CREATE ROUTINE, CREATE TEMPORARY TABLES, CREATE VIEW, DELETE, DROP, EVENT, INDEX, INSERT, REFERENCES, TRIGGER, UPDATE, LOCK TABLES  ON `{$name}`.* TO '{$user}' WITH GRANT OPTION;");
	if ($db->errno > 0) die(print_r(array($db->errno,$db->error),1));
	$db->close();
	return $ret;
}

function Jsize($byte)
{
    if($byte < 1024) {
      $unit="B";
    }
    else if($byte < 10240) {
      $byte=round_dp($byte/1024, 5);
      $unit="KB";
    }
    else if($byte < 102400) {
      $byte=round_dp($byte/1024, 5);
      $unit="KB";
    }
    else if($byte < 1048576) {
      $byte=round_dp($byte/1024, 5);
      $unit="KB";
    }
    else if ($byte < 10485760) {
      $byte=round_dp($byte/1048576, 5);
      $unit="MB";
    }
    else if ($byte < 104857600) {
      $byte=round_dp($byte/1048576,5);
      $unit="MB";
    }
    else if ($byte < 1073741824) {
      $byte=round_dp($byte/1048576, 5);
      $unit="MB";
    }
    else {
      $byte=round_dp($byte/1073741824, 5);
      $unit="GB";
    }
 
$byte .= $unit;
return $byte;
}
 
function round_dp($num , $dp)
{
  $sh = pow(10 , $dp);
  return (round($num*$sh)/$sh);
}
