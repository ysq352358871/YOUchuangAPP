<?php
class login extends indexMain{
    function __construct(){
        parent::__construct();
        $this->db=new db("user");
    }
    function init(){
        $this->smarty->display("index/login.html");
    }
    function reg(){
        $this->smarty->display("index/reg.html");
    }
    function check(){
        $uname=$_POST["username"];
        $upass=md5($_POST["pass"]);
        $code=$_POST["code"];
        $result=$this->db->select();
        if($code==$this->session->get("code")){
            foreach ($result as $k=>$v){
                if($uname==$v["uname"]){
                    if($upass==$v["upass"]){
                        $this->session->set("indexLogin","yes");
                        $this->session->set("uid",$v["uid"]);
                        $this->session->set("uname",$v["uname"]);
                        $url=$this->session->get("url")?$this->session->get("url"):"index.php";
                        $this->jump("$url","登录成功");
                        exit;
                    }else{
                        $this->jump("index.php?m=index&f=login&a=init","账号密码错误");
                        exit;
                    }
                }
            }
            $this->jump("index.php?m=index&f=login&a=init","账号密码错误");
        }else{
            $this->jump("index.php?m=index&f=login&a=init","验证码错误");
        }

    }
    function checkReg(){
        $uname=$_POST["username"];
        $upass=md5($_POST["password"]);
        $picture=IMG_PATH."y.jpg"; //设置注册默认头像
        if($this->db->filed("uname='$uname';upass='$upass';picture='$picture'")->insert()>0){
            $this->jump("index.php?m=index&f=login&a=init","注册成功");
        }else{
            $this->jump("index.php?m=index&f=login&a=reg","注册失败");
        }
    }
}