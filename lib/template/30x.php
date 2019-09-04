<!--div class="page-header "><h2>后台首页</h2></div-->

<section class="clearfix"><div class="span9">
添加/删除重定向
<table width="90%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <th width="39%" scope="col">域名/路径</th>
    <th width="12%" scope="col">目标</th>
    <th width="34%" scope="col">保留URI参数</th>
    <th width="15%" scope="col">操作</th>
  </tr>
 <?php foreach($list as $ii){/*  print_r($ii); */?>
  <tr>
    <td>
	<?=($ii['type']=='0')?'[停用中]':''?>
	<?=($ii['domainorpath']=='path')?'[路径]':'[域名]'?>
	<?=$ii['redirectdomain'][0]?>
	<?=$ii['redirectpath']?>
	[<?=$ii['redirecttype']?>]
	</td>
    <td><?=$ii['tourl']?></td>
    <td><?=($ii['holdpath']=='1')?'保留':'不保留'?></td>
    <td>
	[<a href="?del=<?=$ii['redirectname']?>">删除</a>]
	<?php if($ii['type']=='1'){ ?>[<a href="?stop=<?=$ii['redirectname']?>">停用</a>]
	<?php }else{ ?>[<a href="?start=<?=$ii['redirectname']?>">启用</a>]
	<?php } ?>
	</td>
  </tr>
  <?php }?>
</table>
<hr>
<form id="form1" name="form1" method="post" action="">
  <table width="100%" border="1" cellspacing="0" cellpadding="0">
    <tr>
      <th colspan="2" align="left" scope="col">创建域名重定向</th>
    </tr>
    <tr>
      <th width="17%" align="right" scope="row">保留URI参数
      <label for="holdpath"></label></th>
      <td width="83%"><select name="holdpath">
        <option value="1">保留</option>
        <option value="0">不保留</option>
      </select></td>
    </tr>
    <tr>
      <th align="right" scope="row">重定向方式</th>
      <td><select name="redirecttype">
        <option value="301">301</option>
        <option value="302">302</option>
      </select></td>
    </tr>
    <tr>
      <th align="right" scope="row">重定向类型</th>
      <td><select name="domainorpath">
        <option value="domain" selected="selected">域名</option>
        <option value="path">路径</option>
      </select></td>
    </tr>
    <tr>
      <th align="right" scope="row">重定向路径</th>
      <td><input name="redirectpath" type="text" value="/" />
      <br />
      重定向类型选择路径时在这里输入</td>
    </tr>
    <tr>
      <th align="right" scope="row">重定向域名</th>
      <td><select id="usertype" name="redirectdomain" data-actions-box="true" data-live-search="false" tabindex="-98">
      <?php foreach($domain_list as $i=>$ii){?>  <option value="<?=$i?>"><?=$ii?></option><?php } ?>
      </select>
        <br />
        重定向类型选择域名时在这里选择</td>
    </tr>
    <tr>
      <th align="right" scope="row">目标URL</th>
      <td><input name="tourl" type="text" value="http://" /></td>
    </tr>
    <tr>
      <th align="right" scope="row">&nbsp;</th>
      <td><input type="submit" name="add" id="add" value="添加" /></td>
    </tr>
    <tr>
      <th align="right" scope="row">&nbsp;</th>
      <td><ul>
        <li>重定向类型：表示访问选择的&ldquo;域名&rdquo;或输入的&ldquo;路径&rdquo;时将会重定向到指定URL</li>
        <li>目标URL：可以填写你需要重定向到的站点，目标URL必须为可正常访问的URL，否则将返回错误</li>
        <li>重定向方式：使用301表示永久重定向，使用302表示临时重定向</li>
        <li>保留URI参数：表示重定向后访问的URL是否带有子路径或参数如设置访问http://b.com 重定向到http://a.com</li>
        <li>保留URI参数： http://b.com/1.html ---&gt; http://a.com/1.html</li>
        <li>不保留URI参数：http://b.com/1.html ---&gt; http://a.com</li>
      </ul></td>
    </tr>
  </table>
</form>

</div></div>