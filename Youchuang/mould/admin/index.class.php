<?php
/*
 *
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/1
 * Time: 14:57
 */
class index extends main{
    function init(){
            if($this->check()){
                $this->smarty->display("admin/index.html");
            }
    }
    function login(){
        if($this->session->get("login")&&$this->check()){
            header("location:index.php?m=admin");
        }
        $this->smarty->display("admin/login.html");
    }
    function reg(){
        $this->smarty->display("admin/reg.html");
    }
    function code(){
        $code=new code();
        $code->fontFile=FONT_PATH."Roboto-Bold-webfont.ttf";
        $code->fontSize=["min"=>"20","max"=>"40"];
        $code->outPut();
//        $session=$this->session;
        $this->session->set("code",$code->resultLetter);
    }
    function checkLogin(){
        $session=$this->session;
        $code=strtolower($_POST["code"]);
        if($code==$session->get("code")){
            $aname=$_POST["aname"];
            $apass=md5($_POST["apass"]);
            $db=new db("admin");
            $result=$db->select();
           foreach ($result as $k=>$v){
                if($aname==$v["aname"]){
                   if($apass==$v["apass"]){
                       $this->session->set("login","yes");
                       $this->session->set("aname",$v["aname"]);
                       $this->session->set("aid",$v["aid"]);
                        $this->jump("index.php?m=admin","登录成功");
                        exit;
                    }else{
                        $this->jump("index.php?m=admin&a=login","账号密码错误");
                        exit;
                    }
                }
            }
            $this->jump("index.php?m=admin&a=login","账号密码错误");
        }else{
            $this->jump("index.php?m=admin&a=login","验证码错误");
        }
    }
    function logOut(){
        $this->session->clear();
        $this->jump("index.php?m=admin&a=login","退出成功");
    }
}