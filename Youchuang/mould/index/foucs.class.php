<?php
class foucs extends indexMain{
    function __construct(){
        parent::__construct();
        $this->db=new db("foucus");
    }
    function checkFoucs(){ //添加关注
        $url=$_GET["url"];
        $this->session->set("url",$url);
        if($this->session->get("indexLogin")){
            $uid2=$_GET["uid2"];
            $uid1=$this->session->get("uid");
            if($this->db->insert("uid1=$uid1;uid2=$uid2")){
                echo "yes";
            }
        }else{
            echo "no";
        }
    }
    function cancelFocus(){  //取消关注
        $uid2=$_GET["uid2"];
        $uid1=$this->session->get("uid");
        if($this->db->delete("delete from foucus where uid1=$uid1 and uid2=$uid2")){
            echo "yes";
        }else{
            echo "no";
        }
    }
}