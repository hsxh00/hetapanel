<?php include('amftp_header.php');?>

<div id="content" style="width:auto;padding-left:20px">


<div style="">
<p><h2> » 上传大文件帮助：</h2></p>
<p>AMFTP极速上传，上传基本可以达到满速，如需上传大文件几十、几百MB的文件，PHP与Nginx配置需适当更改。下面以amh面板为例：</p>
<p>1 ) 修改面板环境配置 ( /usr/local/amh-版本/etc/amh-php.ini ) 调整 post 与 upload 参数限制：post_max_size、upload_max_filesize。 (注意不能大于 memory_limit 内存限制)</p>
<p>另外更改时间限制参数：max_execution_time。</p>
<p>2 ) 上传大于50M的文件，另需更改Nginx默认配置 ( /usr/local/nginx-版本号/conf/nginx.conf ) 参数： client_max_body_size。</p>
<p>amh面板默认配置最大是50MB，更改后执行命令 amh nginx reload 重载Nginx。</p>
</div>

<br/>

<p><h2> » 关于压缩包支持</h2></p>
<p>1) Linux 环境全面支持 zip tar gzip(tar.gz) 格式解压与压缩。<p>
<p>1) Windows 环境zip支持良好。</p>

<br/>
<p><h2> » 关于响应速度</h2></p>
<p>AMFTP同时支持本地或服务器线上运行，建议程序上传到服务器线上运行、响应快速。<p>

<br/>

<p><h2> » 关于AMFTP</h2></p>
<p>AMFTP - 基于web在线FTP文件管理客户端。<p>
<p>AMFTP应用PHP AMP框架开发，由AMH官方提供，属于amh面板扩展模块之一。</p>
<p>2013-02-06 发布首个版本，获取最新稳定版本与新功能支持请关注官方网站。</p>

</div>


<?php include('amftp_footer.php');?>