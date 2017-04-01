<?php
class art extends indexMain{
    function __construct(){
        parent::__construct();
        $this->db=new db("shows");
        $this->tree=new fun();
    }
    function addArt(){
        if(!$this->session->get("indexLogin")){
            $this->jump("index.php?m=index&f=login&a=init","请登录！");
            exit;
        }
        $this->tree->tree(0,0,"catagory",$this->db->connect);
        $this->smarty->assign("str",$this->tree->str);
        $this->smarty->display("index/addArt.html");
    }
    function addArtCheck(){
        $uid=$this->session->get("uid");
        $uname=$this->session->get("uname");
        $simg=$_POST["hidden"];
        $cid=$_POST["cid"];
        $stitle=$_POST["stitle"];
        $sdesc=$_POST["sdesc"];
        $scon=$_POST["scon"];
        $result=$this->db->insert("insert into shows (stitle,scon,sdesc,cid,uid,author,simg) VALUES ('$stitle','$scon','$sdesc',$cid,$uid,'$uname','$simg')");
        if($result>0){
            $this->jump("index.php?m=index&f=art&a=addArt","添加成功");
        }else{
            $this->jump("index.php?m=index&f=art&a=addArt","添加失败");
        }
    }
    function info(){
        $this->sid=$_GET["id"];//文章的id
        $result=$this->db->select("select * from shows,user where shows.uid=user.uid and shows.sid=$this->sid");

        $this->guanzhu($result);    //调用关注方法

        $this->shoucang($result);   //调用收藏方法

        $this->dianzan($result);    //调用点赞方法

        $this->message();       //调用查询留言的方法
        if($this->session->get("uid")){  //登录即获取用户信息
            $resultUser=$this->db->select("select * from user WHERE uid=".$this->session->get("uid"));
            $this->smarty->assign("resultUser",$resultUser[0]);
        }
        $this->smarty->assign("result",$result);
        $this->smarty->display("index/innerInfo.html");
    }
    function guanzhu($result){
        $uid2=$result[0]["uid"];//查询出文章发布者的id
        if($uid2==$this->session->get("uid")){ //判断文章的id是否和在线者的id相等
            $this->smarty->assign("foucs","self");
        }else{
            $result2=$this->db->select("select * from foucus where uid1=".$this->session->get("uid"));
            $flag=false;
            foreach ($result2 as $v){
                if($v["uid2"]==$uid2){
                    $flag=true;
                    break;
                }
            }
            if($flag){
                $this->smarty->assign("foucs","true");
            }else{
                $this->smarty->assign("foucs","false");
            }
        }
    }
    function shoucang($result){
        $uid=$result[0]["uid"];//查询出文章作者的id
        if($uid==$this->session->get("uid")){
            $this->smarty->assign("love","self");
        }else{
           $result2=$this->db->select("select * from love where uid=".$this->session->get("uid"));//查询出当前登录用户收藏的文章
            $flag=false;
           foreach ($result2 as $v){
               if($v["sid"]==$this->sid){
                   $flag=true;
                   break;
               }
           }
           if($flag){
               $this->smarty->assign("love","true");
           }else{
               $this->smarty->assign("love","false");
           }
        }
    }

    function dianzan($result){
        $uid=$result[0]["uid"];     //查询出文章作者的id;
        if($uid==$this->session->get("uid")){       //判断作者的id和当前登录用户的id是否一致
            $this->smarty->assign("dianzan","self");
        }else{
            $result2=$this->db->select("select * from good WHERE uid=".$this->session->get("uid")); //查询出当前用户点赞过的文章
            $flag=false; //定义标识
            foreach ($result2 as $v){   //遍历当前的结果
                if($v["sid"]==$this->sid){
                    $flag=true;
                    break;
                }
            }
            if($flag=="true"){
                $this->smarty->assign("dianzan","true");
            }else{
                $this->smarty->assign("dianzan","false");
            }
        }
    }


    function message(){     //查询message信息的方法
        $sid=$_GET["id"];
        $result=$this->db->select("select * from message,user where message.sid=$sid and message.uid1=user.uid ORDER BY mtime desc");
        $arr=array();
        foreach ($result as $v){
            if($v["pid"]==0){
                $arr[]=$v;
            }
        }
        $this->smarty->assign("commentArr",$arr); //首次评论的数组(一级评论)。
        foreach ($result as $v){ //再次遍历$result;
            if($v["pid"]!=0){   //遍历出不是一级评论的评论。
                foreach ($arr as $k=>$v1){ //遍历一级评论
                    if(!isset($arr[$k]["son"])){
                        $arr[$k]["son"]=array();
                    }
                    if($v["pid"]==$v1["mid"]){
                        $arr[$k]["son"][]=$v;

                    }
                }
            }
        }
        $this->smarty->assign("arr",$arr); //所有评论的数组。
    }

}