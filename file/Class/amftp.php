<?php
/************************************************
 * Amysql FTP - AMFTP 2.5
 * amh.sh
 * Update:2015-08-15
 * 
 */

class amftp 
{
	public $amftp_ftp_connect = null;
	public $amftp_am_ftp_pwd = null;
	
	// 建立ftp连接
	function am_ftp_connect($ftp_ip, $ftp_port)
	{
		$this -> amftp_ftp_connect =  @ftp_connect($ftp_ip, $ftp_port);
		Return $this -> amftp_ftp_connect;
	}


	// 登录
	function am_ftp_login($ftp_user, $ftp_pass)
	{
		if(@ftp_login($this -> amftp_ftp_connect, $ftp_user, $ftp_pass))
		{
			$this -> amftp_am_ftp_pwd = $this -> am_ftp_pwd();
			Return true;
		}
		Return false;
	}


	// 文件相关 *************************************************************


	// 更改权限
	function am_ftp_chmod($mode, $file)
	{
		Return $this -> am_ftp_site("CHMOD {$mode} {$file}");
	}
	// 更改权限(目录递归)
	function am_ftp_chmods($mode, $dir, $recursion = false)
	{
		if ($recursion)
		{
			$am_ftp_rawlist = $this -> am_ftp_rawlist($dir);
			foreach ($am_ftp_rawlist as $key=>$val)
			{
				$row = functions::Grawlistline($val);
				if(!in_array($row['dirfilename'], array('.', '..')))
				{
					$this -> am_ftp_chmod($mode, $dir . '/' . $row['dirfilename']);

					if ($row['dirorfile'] == 'd')
						$this -> am_ftp_chmods($mode, $dir . '/' . $row['dirfilename'], $recursion);
				}
			}
		}
		Return $this -> am_ftp_chmod($mode, $dir);
	}

	// 移动
	function am_ftp_move($from, $to)
	{
		$this -> am_ftp_raw("RNFR {$from}");
		$this -> am_ftp_raw("RNTO {$to}");
		$file_list = $this -> am_ftp_nlist(dirname($to));
		Return in_array(basename($to), $file_list);
	}

	// 删除文件
	function am_ftp_delete($file)
	{
		Return @ftp_delete($this -> amftp_ftp_connect, $file);
	}

	// 取得文件大小
	function am_ftp_size($file)
	{
		Return @ftp_size($this -> amftp_ftp_connect, $file);
	}

	// 重命名
	function am_ftp_rename($from, $to)
	{
		Return @ftp_rename($this -> amftp_ftp_connect, $from, $to);
	}

	// 目录相关 *************************************************************

	// 返回父目录
	function am_ftp_cdup()
	{
		Return @ftp_cdup($this -> amftp_ftp_connect);
	}
	
	// 改变目录
	function am_ftp_chdir($directory)
	{
		Return @ftp_chdir($this -> amftp_ftp_connect, $directory);
	}

	// 当前路径
	function am_ftp_pwd()
	{
		Return @ftp_pwd($this -> amftp_ftp_connect);
	}

	// 建立文件夹
	function am_ftp_mkdir($dirname)
	{
		Return @ftp_mkdir($this -> amftp_ftp_connect, $dirname);
	}

	// 删除目录
	function am_ftp_rmdir($directory)
	{
		$list = $this -> am_ftp_nlist($directory);	
		foreach ($list as $k=>$v)
		{
			if ($v != '.' && $v != '..')
			{
				if(!$this -> am_ftp_delete($directory . '/' . $v))
					$this -> am_ftp_rmdir($directory . '/' . $v);
			}
		}
		Return @ftp_rmdir($this -> amftp_ftp_connect, $directory);
	}

	// 文件列表
	function am_ftp_nlist($directory)
	{
		Return @ftp_nlist($this -> amftp_ftp_connect, $directory);
	}

	// 文件详细列表
	function am_ftp_rawlist($directory)
	{
		Return @ftp_rawlist($this -> amftp_ftp_connect, $directory);
	}


	// 命令相关 *************************************************************

	// 执行命令
	function am_ftp_exec($command)
	{
		Return ftp_exec($this -> amftp_ftp_connect, $command);
	}

	// 执行raw命令
	function am_ftp_raw($command)
	{
		Return ftp_raw($this -> amftp_ftp_connect, $command);
	}

	// 执行site命令
	function am_ftp_site($command)
	{
		Return ftp_site($this -> amftp_ftp_connect, $command);
	}

	// 上传&下载 *************************************************************
	// 上传文件(包含目录)
	function am_ftp_puts($remote, $local)
	{
		$list = array_diff(scandir($local), array('.', '..'));
		foreach ($list as $val) 
		{
			$remote_file_dir = $remote . $val;
			$file_or_dir = $local . $val;
			
			if (is_dir($file_or_dir))
			{
				$this -> am_ftp_mkdir($remote_file_dir);
				if(!$this -> am_ftp_puts($remote_file_dir . '/', $file_or_dir . '/'))
					Return false;
			}
			else
			{
				if(!$this -> am_ftp_put($remote_file_dir, $file_or_dir))
					Return false;
			}
		}
		unset($list);
		Return true;
	}


	// 上传文件
	function am_ftp_put($remote, $local)
	{
		Return @ftp_put($this -> amftp_ftp_connect, $remote, $local, FTP_BINARY);
	}

	
	// 下载文件(包含目录) **********************
	function am_ftp_gets($local, $remote)
	{
		// /tmp/tmp_tar/   /modules
		$path = $local . $remote . '/';
		functions::mkdirs($path);

		$am_ftp_rawlist = $this -> am_ftp_rawlist($remote);
		foreach ($am_ftp_rawlist as $key=>$val)
		{
			$row = functions::Grawlistline($val);
			if(!in_array($row['dirfilename'], array('.', '..')))
			{
				if ($row['dirorfile'] == 'd')
					$this -> am_ftp_gets($local, $remote . '/' . $row['dirfilename']);
				else
					$this -> am_ftp_get($path, $remote . '/' . $row['dirfilename']);
			}
		}
	}

	// 下载文件
	function am_ftp_get($local, $remote)
	{
		$filename = explode('/', $remote);
		$filename = $filename[count($filename) -1];
		functions::mkdirs($local);

		if(ftp_get($this -> amftp_ftp_connect, $local . $filename , $remote, FTP_BINARY))
			Return $local . $filename;
		else		
			Return false;
	}
	
	// *************************


	// 释放
	function __destruct()
	{
		($this -> amftp_ftp_connect && ftp_close($this -> amftp_ftp_connect));
	}
}

?>