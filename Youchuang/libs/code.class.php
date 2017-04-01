<?php
class code{
    private $letter="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    public $resultLetter;
    private $num=4;
    public $width=200;
    public $height=80;
    private $lineNum=10;
    public $pointNum=100;
    public $imgType="png";
    public $img;
    public $h;
    public $w;
    public $x1;
    public $y1;
    public $x2;
    public $y2;
    public $fontFile="Roboto-Bold-webfont.ttf";
    public $fontSize=["min"=>15,"max"=>25];     //设定字体大小的范围

    private function getColor(){        //随机获取颜色
        $arr[0]=mt_rand(0,128);
        $arr[1]=mt_rand(0,128);
        $arr[2]=mt_rand(0,128);
        return $arr;
    }
    private function getFontColor(){
        $arr[0]=mt_rand(129,255);
        $arr[1]=mt_rand(129,255);
        $arr[2]=mt_rand(129,255);
        return $arr;
    }
    private function getLineColor(){
        $arr[0]=mt_rand(0,255);
        $arr[1]=mt_rand(0,255);
        $arr[2]=mt_rand(0,255);
        return $arr;
    }
    private function getFont(){ //随机获取文字
        $len=strlen($this->letter);  //获取字符串长度；
        $str="";
        for($i=0;$i<$this->num;$i++){
            $str.=$this->letter[mt_rand(0,$len-1)]; //随机获取字符
        }
       return $str;
    }
    private function getXY(){   //随机获取每条线条的开始、结束的坐标。
        $this->x1=mt_rand(0,$this->width);
        $this->y1=mt_rand(0,$this->height);
        $this->x2=mt_rand(0,$this->width);
        $this->y2=mt_rand(0,$this->height);
    }
    private function create(){
        $this->img=imagecreatetruecolor($this->width,$this->height);
        $color=$this->getColor();
        $bgColor=imagecolorallocate($this->img,$color[0],$color[1],$color[2]);
        imagefill($this->img,0,0,$bgColor);
    }
    private function createText(){
        $wenzi=$this->getFont();
        $this->resultLetter=strtolower($wenzi);
        $x=$this->width/$this->num;     //字体初始的左右范围
        for($i=0;$i<$this->num;$i++){
            $color=$this->getFontColor();
            $fontColor=imagecolorallocate($this->img,$color[0],$color[1],$color[2]);
            $arr=imagettfbbox(mt_rand($this->fontSize["min"],$this->fontSize["max"]),0,$this->fontFile,$wenzi[$i]);
            $this->w=$arr[2]-$arr[0];
            $this->h=$arr[1]-$arr[5];
            $y=mt_rand($this->h,$this->height); //字体上下出现的范围。
            imagettftext($this->img,mt_rand($this->fontSize["min"],$this->fontSize["max"]),mt_rand(-15,15),(10+$x*$i)+mt_rand(-10,10),$y,$fontColor,$this->fontFile,$wenzi[$i]);
        }

    }
    private function createLine(){
        for($i=0;$i<=$this->lineNum;$i++){
            $this->getXY();
            $color=$this->getLineColor();
            $line=imagecolorallocate($this->img,$color[0],$color[1],$color[2]);
            imageline($this->img,$this->x1,$this->y1,$this->x2,$this->y2,$line);
        }

    }
    private function createPoint(){
        for($i=0;$i<=$this->pointNum;$i++){
            $color=$this->getLineColor();
            $x=mt_rand(0,$this->width);
            $y=mt_rand(0,$this->height);
            $line=imagecolorallocate($this->img,$color[0],$color[1],$color[2]);
            imagesetpixel($this->img,$x,$y,$line);
        }
    }
    public function outPut(){
        header("content-type:img/".$this->imgType);
        $this->create();   //创建画布
        $this->createText();   //创建文字；
        $this->createLine(); //创建线条；
        $this->createPoint();
        $out="image".$this->imgType;    //以某种格式输出到浏览器 如imagepng();
        $out($this->img);
        imagedestroy($this->img);
    }
}