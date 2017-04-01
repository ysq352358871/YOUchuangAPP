<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/1
 * Time: 14:57
 */
class index extends indexMain{
    function __construct(){
        parent::__construct();
        $this->db=new db("shows");
    }
    function init(){
        $uname=$this->session->get("uname");
        $uid=$this->session->get("uid");
        $indexLogin=$this->session->get("indexLogin");
        $this->smarty->assign("uname",$uname);
        $this->smarty->assign("uid",$uid);
        $this->smarty->assign("indexLogin",$indexLogin);
        $this->showCon();
    }
    function indexExit(){
        $this->session->clear();
        $this->jump("index.php?m=index&f=login&a=init","退出成功");
    }
    function showCon(){
//        $result=$this->db->where("statu=3")->order("time desc")->select(); //查询出所有审核通过的文章
        $result=$this->db->select("select * from user,shows,catagory WHERE shows.uid=user.uid and shows.cid=catagory.cid and shows.statu=3 ORDER BY time desc");
        $this->smarty->assign("result",$result);

        //查询出点击排名的文章
        $resultOrder=$this->db->where("statu=3")->limit("0,6")->order("hits desc")->select();
        $this->smarty->assign("resultOrder",$resultOrder);

        //查询最新发布文章
        $resultNew=$this->db->where("statu=3")->limit("0,6")->order("time desc")->select();
        $this->smarty->assign("resultNew",$resultNew);

        //查询站长推荐文章
        $resultRecommend1=$this->db->select("select * from shows,recommend WHERE shows.sid=recommend.sid and recommend.rid=1 ORDER by time desc limit 0,6");
        $this->smarty->assign("resultRecommend1",$resultRecommend1);

        //查询图文推荐
        $resultRecommend2=$this->db->select("select * from shows,recommend,catagory WHERE shows.sid=recommend.sid and shows.cid=catagory.cid and recommend.rid=2 ORDER by time desc limit 0,6");
        $this->smarty->assign("resultRecommend2",$resultRecommend2);

        $this->smarty->display("index/index.html");
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
    function home(){
        if($this->session->get("indexLogin")){
            $uid=$this->session->get("uid");
            if($uid==$this->session->get("uid")){
                $this->smarty->assign("img","true");
            }else{
                $this->smarty->assign("img","false");
            }
            $result=$this->db->select("select * from shows where uid=".$uid);//查询文章的结果
            $result1=$this->db->select("select * from user where uid=".$uid);//查询用户信息的结果
            $this->smarty->assign("result",$result);
            $this->smarty->assign("result1",$result1);
            //查询关注等信息
            $resultFoucu=$this->db->select("select * from foucus WHERE uid1=".$uid);//查询关注别人
            $this->smarty->assign("resultFoucu",$resultFoucu);
            $resultFans=$this->db->select("select * from foucus where uid2=".$uid); //查询粉丝
            $this->smarty->assign("resultFans",$resultFans);

            //查询文章浏览排行榜
            $resultOrder=$this->db->where("statu=3")->limit("0,4")->order("hits desc")->select();
            $this->smarty->assign("resultOrder",$resultOrder);
            //查询收藏文章
            $resultLove=$this->db->select("select * from love,shows WHERE love.sid=shows.sid and love.uid={$uid}");
            $this->smarty->assign("resultLove",$resultLove);

            //查询关注的好友
            $resultFriend=$this->db->select("select * from foucus,user WHERE foucus.uid2=user.uid and foucus.uid1=".$uid);
            foreach ($resultFriend as $k=>$v){ //循环查出好友所关注的人
                $resultFriend[$k]["foucu"][]=$this->db->select("select * from foucus WHERE uid1=".$v["uid"]);
            }
            foreach ($resultFriend as $k=>$v){ //循环查出好友的粉丝
                $resultFriend[$k]["fans"][]=$this->db->select("select * from foucus WHERE uid2=".$v["uid"]);
               //在页面里去粉丝数量 应该为count($v["fans"][0]) fans的长度为一，因为查询出来的结果以一个数组放在fans的第一个。
            }
            foreach ($resultFriend as $k=>$v){ //循环查出好友发布的文章数量
                $resultFriend[$k]["wenzhang"][]=$this->db->select("select * from shows WHERE uid=".$v["uid"]);
            }
            foreach ($resultFriend as $k=>$v){ //循环查出好友收藏的文章数量
                $resultFriend[$k]["love"][]=$this->db->select("select * from love WHERE uid=".$v["uid"]);
            }
            $this->smarty->assign("resultFriend",$resultFriend);


            $this->smarty->display("index/home.html");
        }else{
            $this->jump("index.php?m=index&f=login&a=init","请您先登录");
        }
    }
    function uploadPersonImg(){
        $uid=G("uid");
        $picture=G("picture");
        if($this->db->update("update user set picture='$picture' WHERE uid=".$uid)>0){
            echo "yes";
        }else{
            echo "no";
        }
    }
    function friendHome(){
        $uid=G("id");
        if($uid==$this->session->get("uid")){
            $this->smarty->assign("img","true");
        }else{
            $this->smarty->assign("img","false");
        }
        $result=$this->db->select("select * from shows where uid=".$uid);//查询文章的结果
        $result1=$this->db->select("select * from user where uid=".$uid);//查询用户信息的结果
        $this->smarty->assign("result",$result);
        $this->smarty->assign("result1",$result1);
        //查询关注等信息
        $resultFoucu=$this->db->select("select * from foucus WHERE uid1=".$uid);//查询关注别人
        $this->smarty->assign("resultFoucu",$resultFoucu);
        $resultFans=$this->db->select("select * from foucus where uid2=".$uid); //查询粉丝
        $this->smarty->assign("resultFans",$resultFans);

        //查询文章浏览排行榜
        $resultOrder=$this->db->where("statu=3")->limit("0,4")->order("hits desc")->select();
        $this->smarty->assign("resultOrder",$resultOrder);
        //查询收藏文章
        $resultLove=$this->db->select("select * from love,shows WHERE love.sid=shows.sid and love.uid={$uid}");
        $this->smarty->assign("resultLove",$resultLove);

        //查询关注的好友
        $resultFriend=$this->db->select("select * from foucus,user WHERE foucus.uid2=user.uid and foucus.uid1=".$uid);
        foreach ($resultFriend as $k=>$v){ //循环查出好友所关注的人
            $resultFriend[$k]["foucu"][]=$this->db->select("select * from foucus WHERE uid1=".$v["uid"]);
        }
        foreach ($resultFriend as $k=>$v){ //循环查出好友的粉丝
            $resultFriend[$k]["fans"][]=$this->db->select("select * from foucus WHERE uid2=".$v["uid"]);
            //在页面里去粉丝数量 应该为count($v["fans"][0]) fans的长度为一，因为查询出来的结果以一个数组放在fans的第一个。
        }
        foreach ($resultFriend as $k=>$v){ //循环查出好友发布的文章数量
            $resultFriend[$k]["wenzhang"][]=$this->db->select("select * from shows WHERE uid=".$v["uid"]);
        }
        foreach ($resultFriend as $k=>$v){ //循环查出好友收藏的文章数量
            $resultFriend[$k]["love"][]=$this->db->select("select * from love WHERE uid=".$v["uid"]);
        }
        $this->smarty->assign("resultFriend",$resultFriend);


        $this->smarty->display("index/home.html");
    }
}