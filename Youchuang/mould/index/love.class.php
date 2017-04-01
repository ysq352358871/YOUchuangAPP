<?php
class love extends indexMain{
    function __construct(){
        parent::__construct();
        $this->db=new db("love");
    }
    function addLove(){ //添加收藏
        $url=$_GET["url"];
        $this->session->set("url",$url);
        if($this->session->get("indexLogin")){
            $sid=$_GET["sid"]; //通过地址栏获取当前文章的id.
            $uid=$this->session->get("uid");
            if($this->db->insert("sid= $sid;uid=$uid")){
                echo "yes";
            }else{
                echo "no";
            }
        }else{
            echo "not";
        }
    }
    function delLove(){
        $sid=$_GET["sid"];
        $uid=$this->session->get("uid");
        if($this->db->delete("delete from love where sid=$sid and uid=$uid")){
            echo "yes";
        }else{
            echo "no";
        }
    }
}