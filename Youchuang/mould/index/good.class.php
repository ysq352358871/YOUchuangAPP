<?php
class good extends indexMain {
    function __construct(){
        parent::__construct();
        $this->db=new db("good");
    }
    function addGood(){  //添加点赞
        $this->session->set("url",G("url"));
        if($this->session->get("uid")){
            $uid=$this->session->get("uid");
            $sid=G("sid");
            if($this->db->insert("sid=$sid;uid=$uid")){
                $this->db->update("update shows set good=good+1 WHERE sid=$sid");
                echo "yes";
            }else{
                echo "no";
            }

        }else{
            echo "not"; //跳转到登录页
        }
    }
    function delGood(){ //取消点赞
        $sid=G("sid");  //封装的GET
        $uid=$this->session->get("uid");
        if($this->db->delete("delete from good WHERE sid=$sid and uid=$uid")){
            $this->db->update("update shows set good=good-1 WHERE sid=$sid");
            echo "yes";
        }else{
            echo "no";
        }
    }
}