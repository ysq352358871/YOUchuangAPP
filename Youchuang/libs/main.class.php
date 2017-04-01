<?php
class main{
    function __construct(){
        $this->smarty=new smarty();
        $this->smarty->setCompileDir("compile");
        $this->smarty->setTemplateDir("template");
        $this->session=new session();
    }
    function jump($url,$message){
        $this->smarty->assign("url",$url);
        $this->smarty->assign("message",$message);
        $this->smarty->display("admin/message.html");
    }
    function check(){
        $session=$this->session;
        if(!$session->get("login")&&MVC=="yes"){
            $this->jump("index.php?m=admin&f=index&a=login","请先登录");
            return false;
        }else{
            return true;
        }
    }
}
