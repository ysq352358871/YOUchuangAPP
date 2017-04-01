<?php

class catagory extends main{
    function __construct(){
        parent::__construct();
        $this->db=new db("catagory");
        $this->tree=new fun();
    }
    function addcatagory(){
//        $tree=new fun();
        $this->tree->tree(0,0,"catagory",$this->db->connect);
        $this->smarty->assign("str",$this->tree->str);
        $this->smarty->display("admin/addcatagory.html");
    }
    function addCatagoryCheck(){
        $cimg=$_POST["hidden"];
        $pid=$_POST["pid"];
        $cname=$_POST["cname"];
        $ckeyWords=$_POST["ckeyWords"];
        $cdesc=$_POST["cdesc"];
        if($this->db->filed("cname='$cname';pid='$pid';ckeyWords='$ckeyWords';cdesc='$cdesc';cimg='$cimg'")->insert()>0){
            $this->jump("index.php?m=admin&f=catagory&a=addcatagory","添加成功");
        }else{
            $this->jump("index.php?m=admin&f=catagory&a=addcatagory","添加失败");
        }
    }
    function upload(){
        $fileInfo=$_FILES["file"];
        date_default_timezone_set('PRC');
//	echo date("Y-m-d his");
        $dirName=date("Ymd");//创建文件夹名字
        $imgName=date("Ymdhis");//创建图片名字
        if(!file_exists("upload")){
            mkdir("upload",0777,true);
        }
        if(!file_exists("upload/".$dirName)){
            mkdir("upload/".$dirName,0777,true);
        }
        if(is_uploaded_file($fileInfo["tmp_name"])){
            $path="upload/".$dirName."/".$imgName.$fileInfo["name"];
            move_uploaded_file($fileInfo["tmp_name"],$path);
        }
        echo HTTP_URL."/upload/".$dirName."/".$imgName.$fileInfo["name"];
    }
    function edit(){
        $this->tree->table(0,0,"catagory",$this->db->connect);
        $this->smarty->assign("str",$this->tree->str);
        $this->smarty->display("admin/edit.html");
    }
    function del(){
        $cid=$_GET["id"];
        $result=$this->tree->delete($cid,"catagory",$this->db->connect); //调用delete方法返回的结果。
        if($result){
            $this->jump("index.php?m=admin&f=catagory&a=edit","删除成功");
        }else{
            $this->jump("index.php?m=admin&f=catagory&a=edit","有子级栏目,删除失败");
        }
    }
    function update(){
        $cid=$_GET["id"]; //获取地址栏传过来的要修改栏目的id
        $result=$this->db->where("cid=$cid")->select();
        $this->tree->tree(0,0,"catagory",$this->db->connect,$cid);
        $this->smarty->assign("cid",$cid);
        $this->smarty->assign("str",$this->tree->str);
        $this->smarty->assign("cname",$result[0]["cname"]);
        $this->smarty->assign("ckeyWords",$result[0]["ckeyWords"]);
        $this->smarty->assign("cdesc",$result[0]["cdesc"]);
        $this->smarty->assign("cimg",$result[0]["cimg"]);
        $this->smarty->display("admin/update.html");
    }
    function updateCheck(){
        $cid=$_GET["id"];
        $cname=$_POST["cname"];
        $ckeyWords=$_POST["ckeyWords"];
        $cdesc=$_POST["cdesc"];
        $cimg=$_POST["hidden"];
        if($this->db->where("cid=$cid")->filed("cname='$cname';ckeyWords='$ckeyWords';cdesc='$cdesc';cimg='$cimg'")->update()>0){
            $this->jump("index.php?m=admin&f=catagory&a=edit","修改成功");
        }else{
            $this->jump("index.php?m=admin&f=catagory&a=edit","修改失败");
        }
    }
}