<?php
/************************************************
 * Amysql FTP - AMFTP 2.5
 * amh.sh
 * Update:2015-08-15
 * 
 */

class functions
{
	public $orderby_name = '';

	// 外部参数取值
	function Gval($key)
	{
		Return isset($_POST[$key]) ? $_POST[$key] : (isset($_GET[$key]) ? $_GET[$key] : '');
	}

	// 取得文件大小
	function CountSize($size) 
	{
		foreach (array('', 'K', 'M', 'G', 'T') as $val)
		{
			if($size < 1024) 
				Return round($size, 1) . ' ' . $val . 'B';
			$size /= 1024;
		}
	}

	// 排序
	function orderby($a, $b)
	{
		$k = $_GET['orderby'];
		$s = ($_GET['order'] == 'asc') ? 1 : -1;

		$a = strtolower($a[$k]);
		$b = strtolower($b[$k]);
		if ($a == $b) 
			return 0;
		return ($a < $b) ? $s * -1 : $s * 1;
	}

	// 分析文件列表
	function Grawlistline($rawlistline)
	{
		if (preg_match("/([-dl])([rwxsStT-]{9})[ ]+([0-9]+)[ ]+([^ ]+)[ ]+(.+)[ ]+([0-9]+)[ ]+([a-zA-Z]+[ ]+[0-9]+)[ ]+([0-9:]+)[ ]+(.*)/", $rawlistline, $regs) == true) 
		{
			$listline["scanrule"]         = 'rule-1';
			$listline["dirorfile"]        = $regs[1];		
			$listline["dirfilename"]      = $regs[9];		
			$listline["size"]             = $regs[6];		
			$listline["owner"]            = $regs[4];		
			$listline["group"]            = $regs[5];		
			$listline["permissions"]      = $regs[2];		
			$listline["mtime"]            = "$regs[7] $regs[8]";	
		}
		elseif (preg_match("/([-dl])([rwxsStT-]{9})[ ]+(.*)[ ]+([a-zA-Z0-9 ]+)[ ]+([0-9:]+)[ ]+(.*)/", $rawlistline, $regs) == true) 
		{
			$listline["scanrule"]         = 'rule-2';
			$listline["dirorfile"]        = $regs[1];		
			$listline["dirfilename"]      = $regs[6];		
			$listline["size"]             = $regs[3];		
			$listline["permissions"]      = $regs[2];		
			$listline["mtime"]            = "$regs[4] $regs[5]";	
		}
		elseif (preg_match("/([0-9\\/-]+)[ ]+([0-9:AMP]+)[ ]+([0-9]*|<DIR>)[ ]+(.*)/", $rawlistline, $regs) == true) 
		{
			$listline["scanrule"]         = 'rule-3.1';
			$listline["size"] = ($regs[3] == "<DIR>") ? '' : $regs[3]; 
			$listline["dirfilename"] = $regs[4];		
			$listline["owner"]            = '';			
			$listline["group"]            = '';			
			$listline["permissions"]      = '';			
			$listline["mtime"]            = "$regs[1] $regs[2]";	
			$listline["dirorfile"] = ($listline["size"] != '') ? '-' : 'd';
		}
		elseif (preg_match("/([-]|[d])[ ]+(.{10})[ ]+([^ ]+)[ ]+([0-9]*)[ ]+([a-zA-Z]*[ ]+[0-9]*)[ ]+([0-9:]*)[ ]+(.*)/", $rawlistline, $regs) == true) 
		{
			$listline["scanrule"]         = 'rule-3.2';
			$listline["dirorfile"]        = $regs[1];		
			$listline["dirfilename"]      = $regs[7];		
			$listline["size"]             = $regs[4];		
			$listline["owner"]            = $regs[3];		
			$listline["group"]            = '';			
			$listline["permissions"]      = $regs[2];		
			$listline["mtime"]            = "$regs[5] $regs6";	
		}
		elseif (preg_match("/([a-zA-Z0-9_-]+)[ ]+([0-9]+)[ ]+([0-9\\/-]+)[ ]+([0-9:]+)[ ]+([a-zA-Z0-9_ -\*]+)[ \\/]+([^\\/]+)/", $rawlistline, $regs) == true) 
		{
			if ($regs[5] != "*STMF") $directory_or_file = 'd';
			if ($regs[5] == "*STMF") $directory_or_file = '-';
			$listline["scanrule"]         = 'rule-3.3';
			$listline["dirorfile"]        = $directory_or_file;
			$listline["dirfilename"]      = $regs[6];		
			$listline["size"]             = $regs[2];		
			$listline["owner"]            = $regs[1];		
			$listline["group"]            = '';			
			$listline["permissions"]      = '';			
			$listline["mtime"]            = "$regs[3] $regs[4]";	
		}
		elseif (preg_match("/([-dl])([rwxsStT-]{9})[ ]+([0-9]+)[ ]+([a-zA-Z0-9]+)[ ]+([a-zA-Z0-9]+)[ ]+([0-9]+)[ ]+([a-zA-Z]+[ ]+[0-9]+)[ ]+([0-9:]+)[ ](.*)/", $rawlistline, $regs) == true) 
		{
			$listline["scanrule"]         = 'rule-3.4';
			$listline["dirorfile"]        = $regs[1];        
			$listline["dirfilename"]      = $regs[9];        
			$listline["size"]             = $regs[6];        
			$listline["owner"]            = $regs[4];        
			$listline["group"]            = $regs[5];        
			$listline["permissions"]      = $regs[2];        
			$listline["mtime"]            = "$regs[7] $regs[8]";    
		}
		else 
		{
			$listline["scanrule"]         = 'rule-4';
			$listline["dirorfile"]        = 'u';
			$listline["dirfilename"]      = $rawlistline;
		}
		
		Return $listline;
	}

	
	// 取得图标
	function Gicon($filename, $dir)
	{
		if($dir == 'd' ) Return "folder.png";
		$filename = explode('.', $filename);
		$fileExtension = '.' . $filename[count($filename)-1];
		switch($fileExtension) 
		{
			case ".mp3":
			case ".wav":
				$fileImage = "mp3.png";
			break;
			
			case ".jpg":
			case ".jpeg":
			case ".gif":
			case ".png":
			case ".bmp":
				$fileImage = "jpg.png";
			break;
			
			case ".zip":
			case ".rar":
			case ".tar":
			case ".gz":
			$fileImage = "zip.png";
			break;
			
			case ".avi":
			case ".mpg":
			case ".mpeg":
			case ".wmv":
			case ".divx":
			case ".mov":
			case ".swf":
				$fileImage = "avi.png";
			break;
			
			case ".exe":
			case ".bat":
				$fileImage = "exe.png";
			break;
			
			case ".pdf":
				$fileImage = "pdf.png";
			break;
			
			case ".htm":
			case ".html":
				$fileImage = "html.png";
			break;
			
			case ".txt":
			case ".css":
			case ".js":
				$fileImage = "txt.png";
			break;
			
			case ".php":
				$fileImage = "php.png";
			break;
			
			case ".doc":
				$fileImage = "doc.png";
			break;
			
			case ".xls":
			case ".csv":
				$fileImage = "xls.png";
			break;
			
			case ".ppt":
				$fileImage = "ppt.png";
			break;
			
			default:
				$fileImage = "page_white.png";
		}

		Return $fileImage;
	}


	// 删除文件夹
	function rmdirs ($dir) 
	{
		if (is_dir($dir)) 
		{
			$list = array_diff(scandir($dir), array('.', '..'));
			foreach ($list as $val) 
			{
				$path = $dir . '/' . $val;
				is_dir($path) ? functions::rmdirs ($path) : unlink($path);
			}
			reset($list);
			Return  rmdir($dir);
		}
	}

	// 建立文件夹
	function mkdirs ($dir)
	{
        $dir_arr = explode('/',$dir); 
		$tmp_dir = '';
        foreach ($dir_arr as $val)
		{  
            $tmp_dir .= $val . '/';
            if(is_dir($tmp_dir))
                continue;  
            else 
                @mkdir($tmp_dir,0777);  
        }  
        if(is_dir($tmp_dir))
            Return $tmp_dir;  
        else
            Return false;  
	}

	// 移动
	function moves ($source, $target)
	{
		if(is_dir($source))
		{
			$dest_name = basename($source);
			if(!mkdir($target.$dest_name))
				Return false;
			$d = dir($source);
			while(($entry = $d->read()) !== false)
			{
				if(is_dir($source.$entry))
				{
					if($entry == "." || $entry == "..")
						continue;
					else
						functions::moves("$source$entry/", "$target$dest_name/"); 
				}
				else
				{
					if(!rename("$source$entry","$target$dest_name/$entry"))
						Return false;
				}                  
			}
		}
		else
		{
			if(!rename("$source","$target"))
				Return false;
		}    
		Return true;  
	}

	// UTF8转GBK
	function UTF8TOGBK($str, $charset)
	{
		Return ($charset == 'gbk') ? iconv('UTF-8', 'GBK//IGNORE', $str) : $str;
	}


	// 权限值
	function GPNumber($permissions)
	{
		$PNumber = 0;
		$permissions_arr = array(400, 200, 100, 40, 20, 10, 4, 2, 1);
		$permissions_arr2 = array(0, 0, 4000, 0, 0, 2000, 0, 0, 1000);
		$p_len = strlen($permissions);
		for ($i=0; $i<$p_len; ++$i)
		{
			if ($permissions[$i] != '-')
			{
				if (!in_array($permissions[$i], array('S', 'T')))
					$PNumber += $permissions_arr[$i];
				if ($permissions[$i] != 'x')
					$PNumber += $permissions_arr2[$i];
			}
		}
		Return $PNumber;
	}

	// 判断utf8
	function is_utf8($word){ 
		if(!preg_match("/[\x7f-\xff]/", $word))
			Return true;
		// Return json_encode($word) == 'null' ? false : true;
		if (preg_match("/^([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}$/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){2,}/",$word) == true)
			Return true; 
		Return false; 
	}

}

?>