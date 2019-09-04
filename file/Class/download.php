<?php
/************************************************
 * Amysql FTP - AMFTP 2.5
 * amh.sh
 * Update:2015-08-15
 * 
 */

class download
{
	public $file_type = array();

	function download()
	{
		$this->file_type['chm']='application/octet-stream';
		$this->file_type['ppt']='application/vnd.ms-powerpoint';
		$this->file_type['xls']='application/vnd.ms-excel';
		$this->file_type['doc']='application/msword';
		$this->file_type['exe']='application/octet-stream';
		$this->file_type['rar']='application/octet-stream';
		$this->file_type['js']="javascrīpt/js";
		$this->file_type['css']="text/css";
		$this->file_type['hqx']="application/mac-binhex40";
		$this->file_type['bin']="application/octet-stream";
		$this->file_type['oda']="application/oda";
		$this->file_type['pdf']="application/pdf";
		$this->file_type['ai']="application/postsrcipt";
		$this->file_type['eps']="application/postsrcipt";
		$this->file_type['es']="application/postsrcipt";
		$this->file_type['rtf']="application/rtf";
		$this->file_type['mif']="application/x-mif";
		$this->file_type['csh']="application/x-csh";
		$this->file_type['dvi']="application/x-dvi";
		$this->file_type['hdf']="application/x-hdf";
		$this->file_type['nc']="application/x-netcdf";
		$this->file_type['cdf']="application/x-netcdf";
		$this->file_type['latex']="application/x-latex";
		$this->file_type['ts']="application/x-troll-ts";
		$this->file_type['src']="application/x-wais-source";
		$this->file_type['zip']="application/zip";
		$this->file_type['bcpio']="application/x-bcpio";
		$this->file_type['cpio']="application/x-cpio";
		$this->file_type['gtar']="application/x-gtar";
		$this->file_type['shar']="application/x-shar";
		$this->file_type['sv4cpio']="application/x-sv4cpio";
		$this->file_type['sv4crc']="application/x-sv4crc";
		$this->file_type['tar']="application/x-tar";
		$this->file_type['ustar']="application/x-ustar";
		$this->file_type['man']="application/x-troff-man";
		$this->file_type['sh']="application/x-sh";
		$this->file_type['tcl']="application/x-tcl";
		$this->file_type['tex']="application/x-tex";
		$this->file_type['texi']="application/x-texinfo";
		$this->file_type['texinfo']="application/x-texinfo";
		$this->file_type['t']="application/x-troff";
		$this->file_type['tr']="application/x-troff";
		$this->file_type['roff']="application/x-troff";
		$this->file_type['shar']="application/x-shar";
		$this->file_type['me']="application/x-troll-me";
		$this->file_type['ts']="application/x-troll-ts";
		$this->file_type['gif']="image/gif";
		$this->file_type['jpeg']="image/pjpeg";
		$this->file_type['jpg']="image/pjpeg";
		$this->file_type['jpe']="image/pjpeg";
		$this->file_type['ras']="image/x-cmu-raster";
		$this->file_type['pbm']="image/x-portable-bitmap";
		$this->file_type['ppm']="image/x-portable-pixmap";
		$this->file_type['xbm']="image/x-xbitmap";
		$this->file_type['xwd']="image/x-xwindowdump";
		$this->file_type['ief']="image/ief";
		$this->file_type['tif']="image/tiff";
		$this->file_type['tiff']="image/tiff";
		$this->file_type['pnm']="image/x-portable-anymap";
		$this->file_type['pgm']="image/x-portable-graymap";
		$this->file_type['rgb']="image/x-rgb";
		$this->file_type['xpm']="image/x-xpixmap";
		$this->file_type['txt']="text/plain";
		$this->file_type['c']="text/plain";
		$this->file_type['cc']="text/plain";
		$this->file_type['h']="text/plain";
		$this->file_type['html']="text/html";
		$this->file_type['htm']="text/html";
		$this->file_type['htl']="text/html";
		$this->file_type['rtx']="text/richtext";
		$this->file_type['etx']="text/x-setext";
		$this->file_type['tsv']="text/tab-separated-values";
		$this->file_type['mpeg']="video/mpeg";
		$this->file_type['mpg']="video/mpeg";
		$this->file_type['mpe']="video/mpeg";
		$this->file_type['avi']="video/x-msvideo";
		$this->file_type['qt']="video/quicktime";
		$this->file_type['mov']="video/quicktime";
		$this->file_type['moov']="video/quicktime";
		$this->file_type['movie']="video/x-sgi-movie";
		$this->file_type['au']="audio/basic";
		$this->file_type['snd']="audio/basic";
		$this->file_type['wav']="audio/x-wav";
		$this->file_type['aif']="audio/x-aiff";
		$this->file_type['aiff']="audio/x-aiff";
		$this->file_type['aifc']="audio/x-aiff";
		$this->file_type['swf']="application/x-shockwave-flash";
	}


	function download_file($fileName, $baseName = null)
	{
		if (!is_file($fileName))
			Return false;

		$filetype = strtolower(array_pop(explode(".", $fileName)));
		$filetype = $this -> file_type[$filetype];
		// if(function_exists("mime_content_type"))
		// 	$filetype = mime_content_type($fileName);
		$baseName = basename($baseName);

		ob_end_clean();
		header('Cache-control: max-age=31536000');
		header('Expires: '.gmdate('D, d M Y H:i:s',time()+31536000).' GMT');
		header('Content-Encoding: none');
		header('Content-Length: '.filesize($fileName));
		header('Content-Disposition: attachment; filename='.$baseName);
		header('Content-Type: '.$filetype);
		readfile($fileName);
	}


}

?>