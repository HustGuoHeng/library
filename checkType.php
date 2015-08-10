<?php
	//读取文件前几个字节 判断文件类型
	function checkFileType($filename){
		$file=fopen($filename,'rb');
		$bin=fread($file,2); //只读2字节
		fclose($file);
		$strInfo =@unpack("c2chars",$bin);
		$typeCode=intval($strInfo['chars1'].$strInfo['chars2']);
		$fileType='';
		switch($typeCode){
			case 7790:
				$fileType='exe';
			break;
			case 7784:
				$fileType='midi';
			break;
			case 8297:
				$fileType='rar';
			break;
			case 255216:
				$fileType='jpg';
			break;
			case 7173:
				$fileType='gif';
			break;
			case 6677:
				$fileType='bmp';
			break;
			case 13780:
				$fileType='png';
			break;
			default:
				$fileType='unknown'.$typeCode;
			break;
		}
		if($strInfo['chars1']=='-1'&&$strInfo['chars2']=='-40'){
			return 'jpg';
		}
		if($strInfo['chars1']=='-119'&&$strInfo['chars2']=='80'){
			return 'png';
		}
		return $fileType;
	}