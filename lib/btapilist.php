<?php

$fwmoli=[
    # 系统状态相关接口
    'GetSystemTotal' => '/system?action=GetSystemTotal',	   //获取系统基础统计
    'GetDiskInfo' => '/system?action=GetDiskInfo',			     //获取磁盘分区信息
    'GetNetWork' => '/system?action=GetNetWork',			       //获取实时状态信息(CPU、内存、网络、负载)
    'GetTaskCount' => '/ajax?action=GetTaskCount',			     //检查是否有安装任务
    'UpdatePanel' => '/ajax?action=UpdatePanel',			       //检查面板更新
   	# 网站管理相关接口
   	'Websites' => '/data?action=getData&table=sites',		     //获取网站列表
   	'Webtypes' => '/site?action=get_site_types',			       //获取网站分类
   	'GetPHPVersion' => '/site?action=GetPHPVersion',		     //获取已安装的 PHP 版本列表
    'GetSitePHPVersion' => '/site?action=GetSitePHPVersion', //获取指定网站运行的PHP版本
    'SetPHPVersion' => '/site?action=SetPHPVersion',         //修改指定网站的PHP版本
    'SetHasPwd' => '/site?action=SetHasPwd',                 //开启并设置网站密码访问
    'CloseHasPwd' => '/site?action=CloseHasPwd',             //关闭网站密码访问
    'GetDirUserINI' => '/site?action=GetDirUserINI',         //获取网站几项开关（防跨站、日志、密码访问）
   	'WebAddSite' => '/site?action=AddSite',					         //创建网站
   	'WebDeleteSite' => '/site?action=DeleteSite',			       //删除网站
   	'WebSiteStop' => '/site?action=SiteStop',				         //停用网站
   	'WebSiteStart' => '/site?action=SiteStart',				       //启用网站
   	'WebSetEdate' => '/site?action=SetEdate',				         //设置网站有效期
   	'WebSetPs' => '/data?action=setPs&table=sites',			     //修改网站备注
   	'WebBackupList' => '/data?action=getData&table=backup',	 //获取网站备份列表
   	'WebToBackup' => '/site?action=ToBackup',				         //创建网站备份
   	'WebDelBackup' => '/site?action=DelBackup',				       //删除网站备份
   	'WebDoaminList' => '/data?action=getData&table=domain',	 //获取网站域名列表
    'GetDirBinding' => '/site?action=GetDirBinding',         //获取网站域名绑定二级目录信息
    'AddDirBinding' => '/site?action=AddDirBinding',         //添加网站子目录域名
    'DelDirBinding' => '/site?action=DelDirBinding',         //删除网站绑定子目录
    'GetDirRewrite' => '/site?action=GetDirRewrite',         //获取网站子目录伪静态规则
   	'WebAddDomain' => '/site?action=AddDomain',				       //添加网站域名
   	'WebDelDomain' => '/site?action=DelDomain',				       //删除网站域名
    'GetSiteLogs' => '/site?action=GetSiteLogs',             //获取网站日志
    'GetSecurity' => '/site?action=GetSecurity',             //获取网站盗链状态及规则信息
    'SetSecurity' => '/site?action=SetSecurity',             //设置网站盗链状态及规则信息
    'GetSSL' => '/site?action=GetSSL',                       //获取SSL状态及证书详情
    'HttpToHttps' => '/site?action=HttpToHttps',             //强制HTTPS
    'CloseToHttps' => '/site?action=CloseToHttps',           //关闭强制HTTPS
    'SetSSL' => '/site?action=SetSSL',                       //设置SSL证书
    'CloseSSLConf' => '/site?action=CloseSSLConf',           //关闭SSL
    'WebGetIndex' => '/site?action=GetIndex',                //获取网站默认文件
    'WebSetIndex' => '/site?action=SetIndex',                //设置网站默认文件
    'GetLimitNet' => '/site?action=GetLimitNet',             //获取网站流量限制信息
    'SetLimitNet' => '/site?action=SetLimitNet',             //设置网站流量限制信息
    'CloseLimitNet' => '/site?action=CloseLimitNet',         //关闭网站流量限制
    'Get301Status' => '/site?action=Get301Status',           //获取网站301重定向信息
    'Set301Status' => '/site?action=Set301Status',           //设置网站301重定向信息
   	'GetRewriteList' => '/site?action=GetRewriteList',		   //获取可选的预定义伪静态列表
   	'GetFileBody' => '/files?action=GetFileBody',			       //获取指定预定义伪静态规则内容(获取文件内容)
   	'SaveFileBody' => '/files?action=SaveFileBody',			     //保存伪静态规则内容(保存文件内容)
    'GetProxyList' => '/site?action=GetProxyList',           //获取网站反代信息及状态
    'CreateProxy' => '/site?action=CreateProxy',             //添加网站反代信息
    'ModifyProxy' => '/site?action=ModifyProxy',             //修改网站反代信息

    # Ftp管理
    'WebFtpList' => '/data?action=getData&table=ftps',       //获取FTP信息列表
    'SetUserPassword' => '/ftp?action=SetUserPassword',      //修改FTP账号密码
    'SetStatus' => '/ftp?action=SetStatus',                  //启用/禁用FTP

    # Sql管理
    'WebSqlList' => '/data?action=getData&table=databases',  //获取SQL信息列表
    'ResDatabasePass' => '/database?action=ResDatabasePassword',  //修改SQL账号密码
    'SQLToBackup' => '/database?action=ToBackup',            //创建sql备份
    'SQLDelBackup' => '/database?action=DelBackup',          //删除sql备份

    'download' => '/download?filename=',                     //下载备份文件(目前暂停使用)

    # 插件管理
    'deployment' => '/plugin?action=a&name=deployment&s=GetList&type=0',       //宝塔一键部署列表
    'SetupPackage' => '/plugin?action=a&name=deployment&s=SetupPackage',       //部署任务

    
  ];
$fwmo['获取系统基础统计']=$fwmoli['GetSystemTotal'];
$fwmo['获取磁盘分区信息']=$fwmoli['GetDiskInfo'];
$fwmo['获取实时状态信息']=$fwmoli['GetNetWork'];
$fwmo['检查是否有安装任务']=$fwmoli['GetTaskCount'];
$fwmo['检查面板更新']=$fwmoli['UpdatePanel'];
$fwmo['获取网站列表']=$fwmoli['Websites'];
$fwmo['获取网站FTP列表']=$fwmoli['WebFtpList'];
$fwmo['获取网站SQL列表']=$fwmoli['WebSqlList'];
$fwmo['获取所有网站分类']=$fwmoli['Webtypes'];
$fwmo['PHP版本列表']=$fwmoli['GetPHPVersion'];
$fwmo['修改PHP版本']=$fwmoli['SetPHPVersion'];
$fwmo['PHP当前版本']=$fwmoli['GetSitePHPVersion'];
$fwmo['新增网站']=$fwmoli['WebAddSite'];
$fwmo['删除网站']=$fwmoli['WebDeleteSite'];
$fwmo['停用站点']=$fwmoli['WebSiteStop'];
$fwmo['启用网站']=$fwmoli['WebSiteStart'];
$fwmo['设置网站到期时间']=$fwmoli['WebSetEdate'];
$fwmo['修改网站备注']=$fwmoli['WebSetPs'];
$fwmo['获取网站备份列表']=$fwmoli['WebBackupList'];
$fwmo['创建网站备份']=$fwmoli['WebToBackup'];
$fwmo['删除网站备份']=$fwmoli['WebDelBackup'];
$fwmo['删除数据库备份']=$fwmoli['SQLDelBackup'];
$fwmo['备份数据库']=$fwmoli['SQLToBackup'];
$fwmo['获取网站域名列表']=$fwmoli['WebDoaminList'];
$fwmo['添加域名']=$fwmoli['WebAddDomain'];
$fwmo['删除网站域名']=$fwmoli['WebDelDomain'];
$fwmo['获取可选的预定义伪静态列表']=$fwmoli['GetRewriteList'];
$fwmo['获取预置伪静态规则']=$fwmoli['GetFileBody'];
$fwmo['保存伪静态规则']=$fwmoli['SaveFileBody'];
$fwmo['设置密码访问网站']=$fwmoli['SetHasPwd'];
$fwmo['关闭密码访问网站']=$fwmoli['CloseHasPwd'];
$fwmo['获取网站日志']=$fwmoli['GetSiteLogs'];
$fwmo['获取网站盗链状态及规则信息']=$fwmoli['GetSecurity'];
$fwmo['设置网站盗链状态及规则信息']=$fwmoli['SetSecurity'];
$fwmo['获取网站三项配置开关']=$fwmoli['GetDirUserINI'];
$fwmo['开启强制HTTPS']=$fwmoli['HttpToHttps'];
$fwmo['关闭强制HTTPS']=$fwmoli['CloseToHttps'];
$fwmo['设置SSL域名证书']=$fwmoli['SetSSL'];
$fwmo['关闭SSL']=$fwmoli['CloseSSLConf'];
$fwmo['获取SSL状态及证书信息']=$fwmoli['GetSSL'];
$fwmo['获取网站默认文件']=$fwmoli['WebGetIndex'];
$fwmo['设置网站默认文件']=$fwmoli['WebSetIndex'];
$fwmo['获取网站流量限制信息']=$fwmoli['GetLimitNet'];
$fwmo['设置网站流量限制信息']=$fwmoli['SetLimitNet'];
$fwmo['关闭网站流量限制']=$fwmoli['CloseLimitNet'];
$fwmo['获取网站301重定向信息']=$fwmoli['Get301Status'];
$fwmo['设置网站301重定向信息']=$fwmoli['Set301Status'];
$fwmo['获取网站反代信息及状态']=$fwmoli['GetProxyList'];
$fwmo['添加网站反代信息']=$fwmoli['CreateProxy'];
$fwmo['编辑网站反代信息']=$fwmoli['ModifyProxy'];
$fwmo['获取网站域名绑定二级目录信息']=$fwmoli['GetDirBinding'];
$fwmo['设置网站域名绑定二级目录']=$fwmoli['AddDirBinding'];
$fwmo['删除网站域名绑定二级目录']=$fwmoli['DelDirBinding'];
$fwmo['获取网站子目录绑定伪静态信息']=$fwmoli['GetDirRewrite'];
$fwmo['修改FTP账号密码']=$fwmoli['SetUserPassword'];
$fwmo['修改SQL账号密码']=$fwmoli['ResDatabasePass'];
$fwmo['启用禁用FTP']=$fwmoli['SetStatus'];
$fwmo['宝塔一键部署列表']=$fwmoli['deployment'];
$fwmo['宝塔一键部署执行']=$fwmoli['SetupPackage'];
$fwmo['重定向2.获取']='/site?action=GetRedirectList';
$fwmo['获取文件夹大小']='/files?action=get_path_size';
/* 
sitename: hkfbew_142.alqlhk.ccc.ci


domainorpath: "domain"
holdpath: 1
redirectdomain: ["www.ll.cc"]
redirectname: "1559891003394"
redirectpath: ""
redirecttype: "301"
sitename: "hkfbew_142.alqlhk.ccc.ci"
tourl: "http://www.baidu.com"
type: 1 */
$fwmo['重定向2.删除']='/site?action=DeleteRedirect';//sitename，redirectname
$fwmo['重定向2.修改']='/site?action=ModifyRedirect';//
$fwmo['重定向2.添加']='/site?action=CreateRedirect';//
/* type: 1
sitename: hkfbew_142.alqlhk.ccc.ci
holdpath: 1
redirectname: 1559891003394
redirecttype: 301
domainorpath: path
redirectpath: \4040
redirectdomain: []
tourl: http://www.baidu.com
redirectname
type: 1
sitename: hkfbew_142.alqlhk.ccc.ci
holdpath: 1
redirectname: 1559891003394
redirecttype: 301
domainorpath: domain
redirectpath: 
redirectdomain: ["www.ll.cc"]
tourl: http5/www.baidu.com


 */

$GLOBALS['访问模块']=$fwmo;