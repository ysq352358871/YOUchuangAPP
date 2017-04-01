<?php
class message extends indexMain{
    function __construct(){
        parent::__construct();
        $this->db=new db("message");
    }
    function addComment(){
        $this->session->set("url",$_GET["url"]);
        if($this->session->get("indexLogin")){
            $uid1=$this->session->get("uid");
            $uid2=$_GET["uid2"];
            $sid=$_GET["sid"];
            $mcon=$_GET["mcon"];
            if($this->db->insert("uid1='$uid1';uid2='$uid2';sid='$sid';pid=0;mcon='$mcon'")){
                $arr["mid"]=$this->db->connect->insert_id;
                $arr["uid2"]=$this->session->get("uid");
                //获取留言用户的信息
                $result=$this->db->select("select * from user where uid=".$uid1);
                $arr["uname"]=$result[0]["uname"];
                $arr["picture"]=$result[0]["picture"];

                $arr["message"]="yes";
                echo json_encode($arr);
            }
        }else{
            $arr["message"]="no";
            echo json_encode($arr);
        }


    }
    function replyComment(){
        $this->session->set("url",$_GET["url"]);
        if($this->session->get("indexLogin")){
            $uid1=$this->session->get("uid");
            $uid2=G("uid2");
            $pid=G("pid");
            $sid=G("sid");
            $mcon=G("mcon");
            if($this->db->insert("uid1='$uid1';uid2='$uid2';sid='$sid';pid='$pid';mcon='$mcon'")){
                $result=$this->db->select("select * from user where uid=".$uid1);//查询出当前的留言者的信息。
                $arr["uname"]=$result[0]["uname"];
                $arr["uid2"]= $uid1; //当前留言者变成下一个被回复的人。
                $arr["pid"]=$pid;
                $arr["message"]="yes";
                echo json_encode($arr);
            }

        }else{
            $arr["message"]="no";
            echo json_encode($arr);
        }
    }

    //处理点击量的
    function hits(){
        $sid=G("sid");
        if($this->db->update("update shows set hits=hits+1 WHERE sid=".$sid)){
            echo "yes";
        }else{
            echo "no";
        }
    }
}