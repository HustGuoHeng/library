<?php 

$resizeimage=new resizeimage("http://localhost/backgo.png",'demo/demo.jpg');

class ResizeImage{
    public $type;//图片类型
    public $width;//实际宽度
    public $height;//实际高度
    public $resize_width;//改变后的宽度
    public $resize_height;//改变后的高度
    public $cut;//是否裁图
    public $srcimg;//源图象  
    public $dstimg;//目标图象地址
    public $im;//临时创建的图象
	public $quality;//图片质量
	public $img_array=array('jpg','png','gif');

    function __construct($img, $dstpath, $max_width = NULL, $max_height = NULL,  $quality=100){

        $this->srcimg=$img;
		$this->quality=$quality;
		$this->type=$this->checkFileType($this->srcimg);//更为严格的检测图片类型
		if(!in_array($this->type,$this->img_array)){
			return '';
		}
        $this->initi_img();//初始化图象
        $this -> dst_img($dstpath);//目标图象地址
        $this->width=imagesx($this->im);
        $this->height=imagesy($this->im);

        //生成缩略图的高度和宽度
        $this->resize_width = $this->width;
        $this->resize_height = $this->height;
        if( isset($max_width) && isset($max_height) )
        {
            $this->set_resize_size($max_width, $max_height);//按比例生成缩略图的尺寸
        }

        $this->newimg();//生成图象
        ImageDestroy($this->im);
    }
    function newimg(){
        $newimg=imagecreatetruecolor( $this->resize_width,$this->resize_height);
        imagecopyresampled($newimg, $this->im, 0, 0, 0, 0, $this->resize_width, $this->resize_height, $this->width,$this->height);
        
        imagejpeg($newimg, $this->dstimg, $this->quality);
    }
    function initi_img(){//初始化图象
        switch ($this->type) {
            case 'jpg':
                $this->im=imagecreatefromjpeg($this->srcimg);
                break;
            case 'jpeg':
                $this->im=imagecreatefromjpeg($this->srcimg);
                break;
            case 'gif':
                $this->im=imagecreatefromgif($this->srcimg);
                break;
            case 'png':
                $this->im=imagecreatefrompng($this->srcimg);
                break;
            // default:
            //     break;
        }
    }
    function dst_img($dstpath){//图象目标地址
        $this->dstimg = $dstpath;
    }
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
    //按比例生成缩略图的尺寸
    function set_resize_size($max_width, $max_height)
    {
        if( $this->resize_width > $max_width)//如果现在宽度大于最大宽度
        {
            $this->resize_height *= number_format( $max_width/$this->resize_width,  4);
            $this->resize_width  = $max_width;
        }
        if( $this->resize_height > $max_height )
        {
            $this->resize_width  *= number_format( $max_height/$this->resize_height , 4);
            $this->resize_height =  $max_height;
        }

    }
}

