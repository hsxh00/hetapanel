<?php
if($load!=5) die('..');

function sql_write_log($data){
	$t=date("YmdHis").rand(00001,99999);
	$data=print_r($data,1)."\r\n------------------------------\r\n\r\n";
	file_put_contents(dirname(__FILE__).'/sql_error/'.$t.'.log',$data, FILE_APPEND );
	return $t;
}
function db_connect(){
	$db = new mysqli($GLOBALS['配置']['数据库']['主机'], $GLOBALS['配置']['数据库']['用户名'], $GLOBALS['配置']['数据库']['密码'], $GLOBALS['配置']['数据库']['数据库'], $GLOBALS['配置']['数据库']['端口']);
	if ($db->connect_errno) errorc__("系统出现错误,请将错误编码告知管理员,并重试操作,错误编码:[".sql_write_log(array('数据库链接出错',$db->connect_errno,$db->connect_error))."]");
	$GLOBALS['D']=$db;
	$GLOBALS['dbcontent']=true;
	db_query("set time_zone = '+8:00';");
	db_query("SET sql_mode='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';");
	db_query("SET NAMES UTF8");
}
function db_query($sqlstr,$is=false){
	if($GLOBALS['dbcontent']!==true) db_connect();
	$db=$GLOBALS['D'];
	$queryr=$db->query($sqlstr);
	$GLOBALS['db_query_']=$queryr;
	$GLOBALS['D']=$db;
	if (!$queryr || $db->errno > 0){
		$sqlerrorid=sql_write_log(array('语句执行出错',$db->errno,$db->error,$sqlstr));
	}
	if (!$is && !empty($sqlerrorid)){
		errorc__("系统出现错误,请将错误编码告知管理员,错误编码:[{$sqlerrorid}]");
	}
	return $queryr;
}
function errorc__($text,$tt=''){ 
	die("<!-- msg:{$text}--><title>支付出现错误！</title><b>出现错误:{$text}</b>");
}
function db_fetch_array(){
	return @$GLOBALS['db_query_']->fetch_array();
}
function db_fetch_assoc(){
	return @$GLOBALS['db_query_']->fetch_assoc();
}
function db_findall($table){
	return db_query("SELECT * FROM {$table}");
}
function db_getid(){
	return $GLOBALS['D']->insert_id;
}
function db_real_escape_string($str){
	return  $GLOBALS['D']->real_escape_string($str);
}
function db_affected_rows(){
	return  $GLOBALS['D']->affected_rows;
}
function db_close(){
	return $GLOBALS['D']->close();
}
 
   

 

function select_query($table = "", $ziduan = "", $where = "", $order = "",  $limit = ""){
    if (!$ziduan) {
        $ziduan = "*";
    }
    $sqlstr = "SELECT " . $ziduan . " FROM " . db_make_safe_field($table);
    if ($where) {
        if (is_array($where)) {
            $a514 = array();
            foreach ($where as $whename => $wheval) {
                $a2 = db_make_safe_field($whename);
                if (is_array($wheval)) {
					$wheval_typ=$wheval["sqltype"];
					$wheval_val=db_escape_string($wheval["value"]);
                    if ($a2 == "default") $a2 = "`default`";
                    if ($wheval_typ == "LIKE")  $a514[] = "" . $a2 . " LIKE '%" . $wheval_val. "%'";
                    if ($wheval_typ == "NEQ") $a514[] = "" . $a2 . "!='" . $wheval_val . "'";
                    if ($wheval_typ == ">") $a514[] = "" . $a2 . ">" . $wheval_val . "";
                    if ($wheval_typ == "<") $a514[] = "" . $a2 . "<" .$wheval_val . "";
                    if ($wheval_typ == "<=") $a514[] = "" . $whename . "<=" . $wheval_val . "";
                    if ($wheval_typ == ">=") $a514[] = "" . $whename . ">=" . $wheval_val . "";
                    if ($wheval_typ == "TABLEJOIN") $a514[] = "" . $a2 . "=" . $wheval_val . "";
                    //if ($wheval_typ == "IN") $a514[] = "".$a2." IN ('".implode("','", db_escape_array($wheval_val) . "')";
                    continue;
                }
                if (substr($a2, 0, 3) == "MD5") {
                    $a2 = explode("(", $whename, 2);
                    $a2 = explode(")", $a2[1], 2);
                    $a2 = db_make_safe_field($a2[0]);
                    $a2 = "MD5(" . $a2 . ")";
                } else {
                    if (strpos($a2, ".")) {
                        $a2 = explode(".", $a2);
                        $a2 = "`" . $a2[0] . "`.`" . $a2[1] . "`";
                    } else {
                        $a2 = "`" . $a2 . "`";
                    }
                }
                $a514[] = "" . $a2 . "='" . db_escape_string($wheval) . "'";
            }
            $sqlstr .= " WHERE " . implode(" AND ", $a514);
        } else {
            $sqlstr .= " WHERE " . $where;
        }
    }
    if (is_array($order)) {
        $sqlstr .= " ORDER BY " . implode(",", $order);
    }
    if ($limit) {
        if (strpos($limit, ",")) {
            $limit = explode(",", $limit);
            $limit = (int) $limit[0] . "," . (int) $limit[1];
        } else {
            $limit = (int) $limit;
        }
        $sqlstr .= " LIMIT " . $limit;
    }
    $queryr = db_query($sqlstr);
    return $queryr;
}
function update_query($table, $str, $where){
    $sqlstr = "UPDATE " . db_make_safe_field($table) . " SET ";
    foreach ($str as $a2 => $wheval) {
        $sqlstr .= "`" . db_make_safe_field($a2) . "`=";
        if ($wheval === "now()") {
            $sqlstr .= "'" . date("YmdHis") . "',";
            continue;
        }
        if ($wheval === "+1") {
            $sqlstr .= "`" . $a2 . "`+1,";
            continue;
        }
        if (is_array($wheval) && isset($wheval['type']) && $wheval['type'] == "AES_ENCRYPT") {
            $sqlstr .= sprintf("AES_ENCRYPT('%s','%s'),", db_escape_string($wheval['text']), db_escape_string($wheval['hashkey']));
            continue;
        }
        if ($wheval === "NULL") {
            $sqlstr .= "NULL,";
            continue;
        }
        if (substr($wheval, 0, 2) === "+=" && db_is_valid_amount(substr($wheval, 2))) {
            $sqlstr .= "`" . $a2 . "`+" . substr($wheval, 2) . ",";
            continue;
        }
        if (substr($wheval, 0, 2) === "-=" && db_is_valid_amount(substr($wheval, 2))) {
            $sqlstr .= "`" . $a2 . "`-" . substr($wheval, 2) . ",";
            continue;
        }
        $sqlstr .= "'" . db_escape_string($wheval) . "',";
    }
    $sqlstr = substr($sqlstr, 0, 0 - 1);
    if (is_array($where)) {
        $sqlstr .= " WHERE";
        foreach ($where as $a2 => $wheval) {
            if (substr($a2, 0, 4) == "MD5(") {
                $a2 = "MD5(" . db_make_safe_field(substr($a2, 4, 0 - 1)) . ")";
            } else {
                $a2 = db_make_safe_field($a2);
                if ($a2 == "order") {
                    $a2 = "`order`";
                }
            }
            $sqlstr .= " " . $a2 . "='" . db_escape_string($wheval) . "' AND";
        }
        $sqlstr = substr($sqlstr, 0, 0 - 4);
    } else {
        if ($where) {
            $sqlstr .= " WHERE " . $where;
        }
    }
    $queryr = db_query($sqlstr);
	return $queryr;
}
function insert_query($table, $str,&$id){
    $a529 = $a530 = "";
    $sqlstr = "INSERT INTO " . db_make_safe_field($table) . " ";
    foreach ($str as $a2 => $wheval) {
        $a529 .= "`" . db_make_safe_field($a2) . "`,";
        if ($wheval === "now()") {
            $a530 .= "'" . date("YmdHis") . "',";
            continue;
        }
        if ($wheval === "NULL") {
            $a530 .= "NULL,";
            continue;
        }
        $a530 .= "'" . db_escape_string($wheval) . "',";
    }
    $a529 = substr($a529, 0, 0 - 1);
    $a530 = substr($a530, 0, 0 - 1);
    $sqlstr .= "(" . $a529 . ") VALUES (" . $a530 . ")";
    $queryr = db_query($sqlstr);
    $id = db_getid();
    return $queryr;
}
function delete_query($table, $where){
    $sqlstr = "DELETE FROM " . db_make_safe_field($table) . " WHERE";
    if (is_array($where)) {
        foreach ($where as $a2 => $wheval) {
            $sqlstr .= db_build_quoted_field($a2) . "='" . db_escape_string($wheval) . "' AND ";
        }
        $sqlstr = substr($sqlstr, 0, 0 - 4);
    } else {
        $sqlstr .= " " . $where;
    }
    $queryr = db_query($sqlstr);
}
function db_build_quoted_field($a2){
    $a531 = "`";
    $a532 = explode(".", $a2, 3);
    foreach ($a532 as $k => $a19) {
        $a533 = db_make_safe_field($a19);
        if ($a533 !== $a19) {
            exit("Unexpected input field parameter in database query.");
        }
        $a532[$k] = $a531 . $a533 . $a531;
    }
    return implode(".", $a532);
}
function full_query($sqlstr, $a7Handle = null){
    $queryr = db_query($sqlstr);
    return $queryr;
}
function get_query_val($table, $ziduan, $where, $a29erby = "", $a29erbyorder = "", $limit = "", $a512 = ""){
    select_query($table, $ziduan, $where, $a29erby, $a29erbyorder, $limit, $a512);
    $a209 = db_fetch_array();
    return $a209[0];
}
function get_query_vals($table, $ziduan, $where, $a29erby = "", $a29erbyorder = "", $limit = "", $a512 = ""){
    select_query($table, $ziduan, $where, $a29erby, $a29erbyorder, $limit, $a512);
    $a209 = db_fetch_array();
    return $a209;
}
function db_escape_string($str){
    $str = db_real_escape_string($str);
    return $str;
}
function db_escape_array($str){
    $str = array_map("db_escape_string", $str);
    return $str;
}
function db_escape_numarray($str){
    $str = array_map("intval", $str);
    return $str;
}
function db_make_safe_field($ziduan){
    return $ziduan;
}
function db_is_valid_amount($a535){
    return preg_match('/^-?[0-9\\.]+$/', $a535) === 1 ? true : false;
}
