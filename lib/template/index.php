<!--div class="page-header "><h2>后台首页</h2></div-->

<section class="clearfix"><div class="span9">

<table width="1170" border="0">
  <tr>
    <td width="557"><table  border="1">
      <tr>
        <td colspan="6" align="left" valign="middle">基本功能</td>
        </tr>
      <tr><!--?php print_r($host_info);?-->
        <td width="113" height="102" align="center" valign="middle"><a href="/domain.php">域名绑定</a></td>
        <td width="113" align="center" valign="middle"><a href="/editpass.php">修改密码</a></td>
        <td width="113" align="center" valign="middle"><a href="/defaultindex.php">默认文件</a></td>
        <td width="113" align="center" valign="middle"><a href="/30x.php">重定向</a></td>
        <td width="113" align="center" valign="middle"><a href="/rewrite.php">伪静态规则</a></td>
        <td width="113" align="center" valign="middle"><a href="/sqlgl.php">数据库管理</a></td>
      </tr>
      <tr>
        <td colspan="6" align="left" valign="middle">空间管理</td>
        </tr>
      <tr>
        <td height="102" align="center" valign="middle">
		
		<form action="<?=$GLOBALS['配置']['在线ftp']?>index.php?c=index&amp;a=amftp_login" method="POST" target="_blank">
		<input type="hidden" name="ftp_ip" value="127.0.0.1">
		<input type="hidden" name="ftp_port" value="21">
		<input type="hidden" name="ftp_user" value="<?=$user['name']?>">
		<input type="hidden" name="ftp_pass" value="<?=$user['ftppass']?>">
		<button type="submit" name="submit">在线文件</button></form>
		
		
		
		</td>
        <td align="center" valign="middle"><a href="/sitebak.php">网站备份</a></td>
        <td align="center" valign="middle">
		<a href="/?sitestatus=<?=($ftp_info['status'])?'stop_ftp':'start_ftp';?>"><?=($ftp_info['status'])?'停用ftp':'启用ftp';?>
		</a></td>
        <td align="center" valign="middle"><a href="/sqlbak.php">数据库备份</a></td>
        <td align="center" valign="middle"><a href="/ssl.php">SSL证书</a></td>
		<td align="center" valign="middle">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="6" align="left" valign="middle">安全管理</td>
        </tr>
      <tr>
        <td height="102" align="center" valign="middle"><a href="/fdl.php">防盗链</a></td>
        <td align="center" valign="middle">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="6" align="left" valign="middle">高级功能</td>
        </tr>
      <tr>
        <td height="102" align="center" valign="middle"><a href="/log.php">网站日志</a></td>
        <td align="center" valign="middle"><a href="/pass.php">网站密码访问</a></td>
        <td align="center" valign="middle"><a href="/inapp.php">一键部署程序</a></td>
        <!--td align="center" valign="middle"><a href="#">流量图</a></td-->
        <td align="center" valign="middle">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
      </tr>
    </table></td>
    <td width="312"><table width="100%" height="605" border="1" cellpadding="1" cellspacing="1">
      <tr>
        <td>虚拟主机信息</td>
      </tr>
      <tr>
        <td>用户名：<?=$user['name']?></td>
      </tr>
      <tr>
        <td>密码:&nbsp;&nbsp; 登陆、ftp、数据库密码,请至修改密码里面查看与修改</td>
      </tr>
      <tr>
        <td>创建时间：<?=$user['addtime']?></td>
      </tr>
      <tr>
        <td>主机状态：<?=($host_info['status'])?'正常':'停用';?>  [<a href="/?sitestatus=<?=($host_info['status'])?'stop':'start';?>"><?=($host_info['status'])?'停用站点':'启用站点';?></a>]</td>
      </tr>
      <tr>
        <td>网站空间：<?=Jsize($host_size)?>/<?=Jsize($user['网站空间大小'])?></td>
      </tr>
      <tr>
        <td>数据库:<?=$sql_info['allsize']?>/<?=$sql_info['alln']?></td>
      </tr>
      <tr>
        <td>ftp地址:<?=$GLOBALS['配置']['显示']['ftp地址']?></td>
      </tr>
      <tr>
        <td>ftp账号:<?=$user['name']?></td>
      </tr>
      <tr>
        <td>sql地址:<?=$GLOBALS['配置']['显示']['sql地址']?></td>
      </tr>
      <tr>
        <td>sql端口:<?=$GLOBALS['配置']['显示']['sql端口']?></td>
      </tr>
      <tr>
        <td>sql用户名/数据库:<?=$user['name']?></td>
      </tr>
      <tr>
        <td>域名绑定数:<?=$user['域名数量']?></td>
      </tr>
      <!--tr>
        <td>最大连接数:</td>
      </tr>
      <tr>
        <td>流量限制:</td>
      </tr>
      <tr>
        <td>ftp限速:</td>
      </tr>
      <tr>
        <td>网站限速：</td>
      </tr-->
      <tr>
        <td>语言:<?=$phplist[jd(BT_SEND('PHP当前版本', array('siteName'=>$user['domain'])))['phpversion']]?></td>
      </tr>
      <tr>
        <td>切换语言:<?php  
		foreach($phplist as $ia=>$ib){ ?>
			[<a href='?setphpv=<?=$ia?>'><?=$ib?></a>]&nbsp;&nbsp;&nbsp;&nbsp;
		<? } ?></td>
      </tr>
    </table></td>
  </tr>
</table>
            
</div></div>