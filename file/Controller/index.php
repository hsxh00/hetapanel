<?php
/************************************************
 * Amysql FTP - AMFTP 2.5
 * amh.sh
 * Update:2015-08-15
 * 
 */

class index extends AmysqlController
{
	public $amftp = null;
	public $functions = null;

	public $cache = false;				// 是否缓存列表
	public $default_order = 'asc';		// 默认排序类型
	public $default_orderby = 'mtime';	// 默认排序字段

	public $txt_code = array('php', 'js', 'html', 'xml', 'xhtml', 'htm', 'txt', 'css', 'json', 'conf', 'c', 'cc', 'sh', 'h', 'm4', 'in', 'ac', 'guess', 'sub', 'sln', 'am', 'dot', 'sql', 'htaccess', 'ini', 'log');
	public $img_code = array('png', 'jpg', 'jpeg', 'gif', 'bmp');


	// 基础引用
	function amftp_base()
	{
		if (!$this -> amftp)
		{
			$this -> functions = $this -> _class('functions');
			$this -> amftp = $this -> _class('amftp');
		}
	}

	// 自动连接
	function amftp_auto_connect()
	{
		if ($this -> amftp && !$this -> amftp -> amftp_ftp_connect && isset($_SESSION['ftp']['user']))
		{
			$this -> amftp -> am_ftp_connect($_SESSION['ftp']['ip'], $_SESSION['ftp']['port']);
			$login_status = $this -> amftp -> am_ftp_login($_SESSION['ftp']['user'], $_SESSION['ftp']['pass']);
			if (!$login_status)
			{
				$this -> logout();
				exit();
			}
		}
	}

	// 登录判断
	function amftp_check_login()
	{
		// 切换用户退出
		if(isset($_GET['ftp_user']) && isset($_SESSION['ftp']) && $_SESSION['ftp']['user'] != $_GET['ftp_user'])
			unset($_SESSION['ftp']);

		if (!isset($_SESSION['ftp']['user']))
		{
			$this -> amftp_login();
			exit();
		}
	}

	

	// 登录页面 *************************************************************************
	function amftp_login()
	{
		$this -> amftp_base();

		$notice = '';
		if (isset($_POST['submit']))
		{
			$ftp_ip = $_POST['ftp_ip'];
			$ftp_port = $_POST['ftp_port'];
			$ftp_user = $_POST['ftp_user'];
			$ftp_pass = $_POST['ftp_pass'];

			if ($this -> amftp -> am_ftp_connect($ftp_ip, $ftp_port))
			{
				// 登录成功
				if ($this -> amftp -> am_ftp_login($ftp_user, $ftp_pass))
				{
					$_SESSION['ftp']['ip'] = $ftp_ip;
					$_SESSION['ftp']['port'] = $ftp_port;
					$_SESSION['ftp']['user'] = $ftp_user;
					$_SESSION['ftp']['pass'] = $ftp_pass;
					header('location:./index.php');
					exit();
				}
				else
				{
					$notice = $ftp_ip . ' #用户 ' . $ftp_user . ' 登录失败。';
				}
			}
			else
			{
				$notice = $ftp_ip . ' 无法响应。';
			}
		}

		$ftp_ip = functions::Gval('ftp_ip');
		$this -> ftp_ip = empty($ftp_ip) ? $_SERVER['SERVER_ADDR'] : $ftp_ip;
		$this -> ftp_user = functions::Gval('ftp_user');
		$this -> notice = $notice;
		$this -> _view('amftp_login');
	}


	// 首页 *************************************************************************
	function IndexAction()
	{
		$this -> amftp_check_login();
		$this -> amftp_base();
		$this -> amftp_auto_connect();


		// 排序类型 & 排序字段
		$_GET['order'] = $_SESSION['ftp']['order'] = isset($_GET['order']) ? $_GET['order'] : (isset($_SESSION['ftp']['order']) ? $_SESSION['ftp']['order'] : $this -> default_order);
		$_GET['orderby'] = $_SESSION['ftp']['orderby'] = isset($_GET['orderby']) ? $_GET['orderby'] : (isset($_SESSION['ftp']['orderby']) ? $_SESSION['ftp']['orderby'] : $this -> default_orderby);
		$pwd = isset($_GET['pwd']) ?  trim($_GET['pwd'], '/') : '';
		$pwd = !empty($pwd) ?  '/'. $pwd . '/' : '/';
		$_SESSION['ftp_pwd'] = $pwd;

		$tmp_id = rand(100000,999999);
		// 解压临时文件夹
		$tmp_tar = _ROOT . '/View/tmp_tar/' . $tmp_id . '/';
		if(!functions::mkdirs($tmp_tar))
		{
			$tmp_tar = '/tmp/tmp_tar/' . $tmp_id . '/';
			functions::mkdirs($tmp_tar);
		}
		
		// 下载临时目录
		$tmp_download = _ROOT . '/View/tmp_download/' . $tmp_id . '/';
		if(!functions::mkdirs($tmp_download))
		{
			$tmp_download = '/tmp/tmp_download/' . $tmp_id . '/';
			functions::mkdirs($tmp_download);
		}

		
		// 关闭session
		if (!$this -> cache)
				session_write_close();

		// 新建 ****************************************************************************
		if (isset($_POST['new']))
		{
			$new_type = (isset($_POST['new_type']) && $_POST['new_type'] == 'file') ? 'file' : 'dir';
			$new_name = basename($_POST['new_name']);
			if ($new_type == 'dir')
			{
				if($this -> amftp -> am_ftp_mkdir($pwd . $new_name))
				{
					$notice = '新建目录成功: ' . $new_name;
					$notice_status = 'success';
				}
				else
				{
				    $notice = '新建目录失败: ' . $new_name;
					$notice_status = 'notice';
				}
			}
			else
			{
				$new_file = $tmp_download . $new_name;
				file_put_contents($new_file, '');
				if (is_file($new_file))
				{
					if($this -> amftp -> am_ftp_put($pwd . $new_name, $new_file))
					{
						$notice = "新建文件成功: {$new_name}";
						$notice_status = 'success';
					}
					else 
					{
						$notice = "新建文件失败： {$new_name}";
						$notice_status = 'notice';
					}
				}
			}
		}


		// 设置权限 ****************************************************************************
		if (isset($_POST['permissions']))
		{
			$p_sum = 0;
			$permissions_val = $_POST['permissions_val'];
			$recursion = (isset($_POST['recursion']) && $_POST['recursion'] == 'on') ? true : false;
			if (is_array($_POST['select_item']['d']))
			{
				foreach ($_POST['select_item']['d'] as $key=>$val)
				{
					if($this -> amftp -> am_ftp_chmods($permissions_val, functions::UTF8TOGBK($val, $_POST['charset_item']['d'][$key]), $recursion))
						++$p_sum;
				}
			}

			if (is_array($_POST['select_item']['-']))
			{
				foreach ($_POST['select_item']['-'] as $key=>$val)
				{
					if($this -> amftp -> am_ftp_chmod($permissions_val, functions::UTF8TOGBK($val, $_POST['charset_item']['-'][$key])))
						++$p_sum;
				}
			}
			
			$notice = ($p_sum) ? '文件权限设置成功(' . $p_sum . '记录)' : '文件权限设置失败';
			$notice_status = ($p_sum) ? 'success' : 'notice';
		}

		// 远程上传 ****************************************************************************
		if (isset($_POST['remote_down']))
		{
			$p_sum = 0;
			$remote_file = $_POST['remote_file'];
			$new_name = basename($remote_file);
			$new_file = $tmp_download . $new_name;
			
			$rf = @fopen($remote_file, 'rb');
			if ($rf)
			{
				$nf = @fopen($new_file, 'wb');
				if ($nf)
				{
					while (!feof($rf))
						fwrite($nf, fread($rf, 1024*8), 1024*8);
				}
			}
			if (is_file($new_file))
			{
				if($this -> amftp -> am_ftp_put($pwd . $new_name, $new_file))
				{
					$notice = "远程上传保存成功: {$new_name}";
					$notice_status = 'success';
				}
				else 
				{
					$notice = "远程上传保存失败： {$new_name}";
					$notice_status = 'notice';
				}
			}
			else
			{
			    $notice = '请确认远程文件是否可以访问。';
				$notice_status = 'notice';
			}
		}


		// 下载 ****************************************************************************
		if (isset($_POST['download']))
		{
			$download =	$this -> _class('download');
			if (is_array($_POST['select_item']['-']))
			{
				foreach ($_POST['select_item']['-'] as $key=>$val)
				{
					$filename = $this -> amftp -> am_ftp_get($tmp_download, functions::UTF8TOGBK($val, $_POST['charset_item']['-'][$key]));
					$download -> download_file($filename, $val);
					unlink($filename);
					exit();
				}	
			}
			$notice = '无可下载项。请选择文件。';
			$notice_status = 'notice';
		}


		// 删除 ****************************************************************************
		if (isset($_POST['delete']))
		{
			if (is_array($_POST['select_item']['d']))
			{
				foreach ($_POST['select_item']['d'] as $key=>$val)
					$status = $this -> amftp -> am_ftp_rmdir(functions::UTF8TOGBK($val, $_POST['charset_item']['d'][$key]));
			}

			if (is_array($_POST['select_item']['-']))
			{
				foreach ($_POST['select_item']['-'] as $key=>$val)
					$status = $this -> amftp -> am_ftp_delete(functions::UTF8TOGBK($val, $_POST['charset_item']['-'][$key]));
			}
			
			$notice = $status ? '文件删除成功' : '文件删除失败';
			$notice_status = $status ? 'success' : 'notice';
		}

		// 解压与压缩 ***********************************************************************
		if (isset($_POST['unzip']) || isset($_POST['zip']))
		{
			include(_Class . '/pclerror.lib.php');
			include(_Class . '/pcltrace.lib.php');
			include(_Class . '/pcltar.lib.php');
			include(_Class . '/pclzip.lib.php');
			include(_Class . '/archive.lib.php');


			// 解压  *****************************************
			if (isset($_POST['unzip']) && isset($_POST['select_item']['-']))
			{
				if (is_dir($tmp_tar))
				{
					foreach ($_POST['select_item']['-'] as $key=>$val)
					{
						$filename = $this -> amftp -> am_ftp_get($tmp_download, functions::UTF8TOGBK($val, $_POST['charset_item']['-'][$key]));
						if ($filename)
						{
							$file = fopen($filename, "rb");
							$bin = fread($file, 2); 
							fclose($file);  
							$strInfo = @unpack("C2chars", $bin);  
							$typeCode = intval($strInfo['chars1'].$strInfo['chars2']);  

							// 解压 (8075-zip, 8297-rar, 31139-gz)
							if ($typeCode == '8075') // zip
							{
								$zip = new PclZip($filename);
								$zip -> extract(PCLZIP_OPT_PATH, $tmp_tar);
							}
							else 
							{
								PclTarExtract($filename, $tmp_tar);
							}

							if (count(array_diff(scandir($tmp_tar), array('.', '..'))) > 0)
							{
								if($this -> amftp -> am_ftp_puts($pwd, $tmp_tar))
									$notice_arr[] = $val;
							}
							
						}
						// unlink($filename);
					}
				}

				$notice = is_array($notice_arr) ? '文件解压成功: ' . implode(',', $notice_arr) : '文件解压失败。';
				$notice_status = is_array($notice_arr) ? 'success' : 'notice';
			}


			// 压缩 *****************************************
			if (isset($_POST['zip']))
			{
				// 下载临时文件夹
				$tag = date('Ymd-His', time());		// 最终目标标识
				$tmp_tar = _ROOT . 'View/tmp_tar/' . $tmp_id . '/';
				if(!functions::mkdirs($tmp_tar) || !functions::mkdirs($tmp_tar  . $tag . '/'))
				{
					$tmp_tar = '/tmp/tmp_tar/' . $tmp_id . '/';
					functions::mkdirs($tmp_tar);
					functions::mkdirs($tmp_tar  . $tag . '/');
				}

				
				if (is_dir($tmp_tar))
				{
					// 下载文件
					if (isset($_POST['select_item']['d']))
					{
						foreach ($_POST['select_item']['d'] as $key=>$val)
							$filename = $this -> amftp -> am_ftp_gets($tmp_tar, functions::UTF8TOGBK($val, $_POST['charset_item']['d'][$key]));
					}
					
					if (isset($_POST['select_item']['-']))
					{
						foreach ($_POST['select_item']['-'] as $key=>$val)
							$filename = $this -> amftp -> am_ftp_get($tmp_tar . $pwd, functions::UTF8TOGBK($val, $_POST['charset_item']['-'][$key]));
					}
					
					// 最终目标移动
					$source = $tmp_tar . $pwd;
					$target = $tmp_tar . $tag . '/';
					$d = dir($source);
					while(($entry=$d->read()) !== false)
					{
						if(is_dir($source.$entry))
						{
							if($entry != "." && $entry != ".." && $entry != $tag)
								functions::moves("$source$entry/", "$target"); 
						}
						else
						{
							if(!rename("$source$entry","$target$entry"))
								return false;
						}                  
					}


					// 开始压缩
					$target_name = $tmp_tar . $tag . '.' . $_POST['zip_type'];
					if ($_POST['zip_type'] == 'zip')
					{
						$archive = new PclZip($target_name);
						$archive->create($target, PCLZIP_OPT_REMOVE_PATH, $tmp_tar . $tag);
					}
					else
					{
						$test = new gzip_file($target_name);
						$options = array('basedir' => $tmp_tar . $tag, 'overwrite' => 1, 'level' => 1);
						$options['type'] = ($_POST['zip_type'] == 'tar') ? 'tar' : 'gzip';
						$test->set_options($options);
						$test->add_files(array('./*'));
						$test->create_archive();
						// PclTarCreate($target_name, $target, $_POST['zip_type'], '', $tmp_tar . $tag);
					}

					if (is_file($target_name))
					{
						if($this -> amftp -> am_ftp_put($pwd . $tag . '.' . $_POST['zip_type'], $target_name))
						{
							$notice = "文件压缩成功: {$tag}.{$_POST['zip_type']}";
							$notice_status = 'success';
						}
						else 
						{
							$notice = '文件压缩失败。';
							$notice_status = 'notice';
						}
					}
				}
			}

		}
		
		// 文件移动 *****************************************
		if (isset($_POST['move']))
		{
			$_pwd = $pwd;
			// 移到到
			$pwd = isset($_POST['pwd']) ?  trim($_POST['pwd'], '/') : '';
			$pwd = !empty($pwd) ?  '/'. $pwd . '/' : '/';

			if (is_dir($tmp_download))
			{
				$move_sum = 0;
				if (isset($_POST['select_item']['d']))
				{
					foreach ($_POST['select_item']['d'] as $key=>$val)
					{
						$_val = functions::UTF8TOGBK($val, $_POST['charset_item']['d'][$key]);
						$target = $pwd . basename($_val);
						if (strpos($target, $_val) !== 0)	// 禁止原地移动&移动至子级目录
						{
							if ($this -> amftp -> am_ftp_move($_val, $target))
								++$move_sum;
						}
					}
				}
				if (isset($_POST['select_item']['-']))
				{
					foreach ($_POST['select_item']['-'] as $key=>$val)
					{
						$_val = functions::UTF8TOGBK($val, $_POST['charset_item']['-'][$key]);
						if ($_val != $pwd . basename($_val))
						{
							if ($this -> amftp -> am_ftp_move($_val, $pwd . basename($_val)))
								++$move_sum;
						}
					}
				}

				$notice = ($move_sum) ? '文件移动成功。(' . $move_sum . '记录)' : '文件移动失败。';
				$notice_status = ($move_sum) ? 'success' : 'notice';
			}

			$pwd = $_pwd;
		}


		
		

		// 取得当前文件列表 *******************************************************************
		if (isset($_SESSION['rawlist'][$pwd]) && !isset($_GET['reload']) && $this -> cache)
		{
			$am_ftp_rawlist_value = $_SESSION['rawlist'][$pwd];
		}
		else
		{
		    $am_ftp_rawlist = $this -> amftp -> am_ftp_rawlist(str_replace(' ', '\ ', $pwd));

			$am_ftp_rawlist_array = array();
			foreach ($am_ftp_rawlist as $key=>$val)
			{
				$row = $this -> functions -> Grawlistline($val);
				if(!in_array($row['dirfilename'], array('.', '..')))
				{
					$row['charset'] = 'utf8';
					if (!functions::is_utf8($row['dirfilename'])) // gbk
					{
						$row['dirfilename'] = iconv('GBK', 'UTF-8//IGNORE', $row['dirfilename']);
						$row['charset'] = 'gbk';
					}
					$row['file_type'] = (strpos($row['dirfilename'], '.') !== false ) ? end(explode('.', $row['dirfilename'])) : '?';
					$row['can_open'] = ($row['dirorfile'] == 'd' || $row['file_type'] == '?' || in_array($row['file_type'], $this -> txt_code) || in_array($row['file_type'], $this -> img_code)) ?  'y' : 'n';
					$row['ico'] = functions::Gicon($row['dirfilename'], $row['dirorfile']);
					$row['size_text'] = functions::CountSize($row['size']);
					$row['permissions_number'] = functions::GPNumber($row['permissions']);
					$row['mtime'] = date('Y-m-d H:i:s', strtotime($row['mtime']));
					$am_ftp_rawlist_array[] = $row;
				}
			}
			
			// 列表排序
			usort($am_ftp_rawlist_array, array('functions', 'orderby'));
			
			// 目录位前面
			foreach ($am_ftp_rawlist_array as $key=>$val)
				$am_ftp_rawlist_array[$val['dirorfile']][] = $val;
			foreach ($am_ftp_rawlist_array['-'] as $key=>$val)
				$am_ftp_rawlist_array['d'][] = $val;

			$am_ftp_rawlist_value = $am_ftp_rawlist_array['d'];
			unset($am_ftp_rawlist_array);

			if($this -> cache) $_SESSION['rawlist'][$pwd] = $am_ftp_rawlist_value;
		}

		
		// 删除临时目录(避免占用空间)
		functions::rmdirs('./View/tmp_tar/' . $tmp_id . '/');
		functions::rmdirs('/tmp/tmp_tar/' . $tmp_id . '/');
		functions::rmdirs('./View/tmp_download/' . $tmp_id . '/');
		functions::rmdirs('/tmp/tmp_download/' . $tmp_id . '/');


		$this -> title = 'AMFTP：' . $pwd;
		$this -> am_ftp_rawlist = $am_ftp_rawlist_value;
		$this -> amftp_am_ftp_pwd = $pwd;
		$this -> order = $order;
		$this -> notice = $notice;
		$this -> notice_status = $notice_status;
		$this -> memory_limit = ini_get('memory_limit');
		$this -> _view('amftp_index');
	}

	
	// 重命名
	function file_rename()
	{
		$this -> amftp_check_login();
		$this -> amftp_base();
		$this -> amftp_auto_connect();
		
		$new_name = str_replace(basename($_POST['old_name']), $_POST['new_name'], $_POST['old_name']);
		if($this -> amftp -> am_ftp_rename(functions::UTF8TOGBK($_POST['old_name'], $_POST['charset']), $new_name))
			echo $new_name;
		exit();
	}


	// 编辑文件
	function file_edit()
	{
		$this -> amftp_check_login();
		$this -> amftp_base();
		$this -> amftp_auto_connect();

		$pwd = $_GET['pwd'];
		$file = $_GET['file'] = basename($_GET['file']);
		$charset = $_GET['charset'];
		if(strpos($file, '..') !== false || strpos($file, '/') !== false )
		{
			unset($_POST);
			unset($_GET);
			$this -> IndexAction();
			exit();
		}

		$tmp_id = rand(100000,999999);
		// 下载临时目录
		$tmp_download = _ROOT . '/View/tmp_file/' . $tmp_id . '/';
		if(!functions::mkdirs($tmp_download))
		{
			$tmp_download = '/tmp/tmp_file/' . $tmp_id . '/';
			functions::mkdirs($tmp_download);
		}

		$file_type = (strpos($file, '.') !== false ) ? end(explode('.', $file)) : 'txt';
		if (in_array($file_type, $this -> txt_code))
			$file_type = 'txt';
		elseif (in_array($file_type, $this -> img_code))
			$file_type = 'img';

		
		// 保存 ************************************
		$is_gbk = 'n';
		if (isset($_POST['file_content']))
		{
			$filename = $tmp_download . $file;
			$file_content_save = $file_content = stripslashes($_POST['file_content']);
			if ($_POST['is_gbk'] == 'y')
			{
				$is_gbk = 'y';
				$file_content_save = iconv('UTF-8', 'GBK//IGNORE', $file_content);
			}
			file_put_contents($filename, $file_content_save);
			if (is_file($filename))
			{
				if($this -> amftp -> am_ftp_put($pwd . $file, $filename))
				{
					$notice = "文件保存成功: {$pwd}{$file}";
					$notice_status = 'success';
				}
				else 
				{
					$notice = "文件保存失败： {$pwd}{$file}";
					$notice_status = 'notice';
				}
			}
		}
		else
		{
			$filename = $this -> amftp -> am_ftp_get($tmp_download, $pwd . functions::UTF8TOGBK($file, $charset));
			if ($filename)
			{
				$file_content = file_get_contents($filename);
				if (isset($_GET['img']))
				{
					header('Content-type: image/jpeg');
					echo $file_content;
					exit();
				}

				if (!functions::is_utf8($file_content)) // gbk
				{
					$file_content = iconv('GBK', 'UTF-8//IGNORE', $file_content);
					$is_gbk = 'y';
				}
			}
			else
			{
				$file_type = '';
				$notice = '文件读取失败';
				$notice_status = 'notice';
			}
		}

		functions::rmdirs('./View/tmp_file/' . $tmp_id . '/');
		functions::rmdirs('/tmp/tmp_file/' . $tmp_id . '/');

		$this -> title = '文件：' . $file;
		$this -> amftp_am_ftp_pwd = $pwd;
		$this -> file_content = $file_content;
		$this -> file_type = $file_type;
		$this -> filename = $filename;
		$this -> notice = $notice;
		$this -> notice_status = $notice_status;
		$this -> is_gbk = $is_gbk;
		$this -> memory_limit = ini_get('memory_limit');
		$this -> _view('amftp_file_edit');
	}


	// 上传文件 *************************************************************************
	function upload()
	{
		// firefox session
		if(!isset($_SESSION['ftp']['user']) && isset($_GET['session_id']))
		{
			session_destroy();
			session_id($_GET['session_id']);
			session_start();
		}

		$this -> amftp_check_login();
		$this -> amftp_base();
		$this -> amftp_auto_connect();

		if(!isset($_FILES['Filedata']['tmp_name'])) exit();


		$this -> amftp -> am_ftp_chdir($_SESSION['ftp_pwd']);
		if($this -> amftp -> am_ftp_put($_FILES['Filedata']['name'], $_FILES['Filedata']['tmp_name']))
			echo '<p>已上传完成。  » <a href="javascript:;" onclick="window.location.reload();">刷新列表</a></p>';
		else
			echo '<p>已上传失败。</p>';

		exit();
	}

	// 删除临时目录 *************************************************************************
	function rmtmp()
	{
		$this -> amftp_check_login();
		$this -> amftp_base();
		functions::rmdirs('./View/tmp_tar/');
		functions::rmdirs('/tmp/tmp_tar/');
		functions::rmdirs('./View/tmp_download/');
		functions::rmdirs('/tmp/tmp_download/');
		functions::rmdirs('./View/tmp_file/');
		functions::rmdirs('/tmp/tmp_file/');
		if ( (!is_dir('./View/tmp_tar/') && !is_dir('/tmp/tmp_tar/') ) && 
		( !is_dir('./View/tmp_download/') && !is_dir('/tmp/tmp_download/') ) && 
		( !is_dir('./View/tmp_file/') && !is_dir('/tmp/tmp_file/') ) )
		{
			$notice = '清除缓存成功';
			$notice_status = 'success';
		}
		else
		{
		    $notice = '清除缓存失败';
			$notice_status = 'notice';
		}

		if (isset($_SERVER['HTTP_REFERER']))
		{
			$_SESSION['ftp']['back'] = $_SERVER['HTTP_REFERER'];
		}
		$this -> notice = $notice;
		$this -> notice_status = $notice_status;
		$this -> back = $_SESSION['ftp']['back'];
		$this -> _view('amftp_rmtmp');
	}

	// 帮助 *************************************************************************
	function help()
	{
		// $this -> amftp_check_login();
		$this -> _view('amftp_help');
	}


	// 退出
	function logout()
	{
		unset($_SESSION['ftp']);
		$this -> IndexAction();
	}

}