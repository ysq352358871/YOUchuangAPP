<?php
class show extends main{
    function __construct(){
        parent::__construct();
        $this->db=new db("shows");
        $this->tree=new fun();
    }
    function show(){
        $result=$this->db->select();
        $pageObj=new pages(count($result),5,"pages");
        $pages=$pageObj->out();
        $this->smarty->assign("pages",$pages);
        $limit=$pageObj->limit;
        $current=$this->db->select("select * from shows,catagory where shows.cid=catagory.cid order by time desc ".$limit);
        $this->smarty->assign("result",$current);
        $this->smarty->display("admin/conShow.html");
    }
    function editCon(){
        $sid=$_GET["id"];
        $result=$this->db->where("sid=$sid")->select();
//        var_dump($result)
        $this->smarty->assign("result",$result);
        $this->smarty->display("admin/editCon.html");
    }
    function editConCheck(){
        $sid=p("hidden"); //文章的id

         //P("recommend")[0] 推荐
        if(isset($_POST["recommend"])){
            $recommend=P("recommend");
            foreach ($recommend as $k=>$v){
                $this->db->insert("insert into recommend (sid,rid) VALUES ('$sid','$v')");
            }
        }

        $statu=p("statu");
        $result=$this->db->where("sid=$sid")->filed("statu='$statu'")->update();
        if( $result){
            $this->jump("index.php?m=admin&f=show&a=show","审核完成");
        }else{
            $this->jump("index.php?m=admin&f=show&a=show","审核失败");
        }
    }
    function del(){
        $sid=$_GET["id"];//获得删除当前内容的id。
        if($this->db->where("sid={$sid}")->delete()>0){
            $this->jump("index.php?m=admin&f=show&a=show","删除成功");
        }else{
            $this->jump("index.php?m=admin&f=show&a=show","删除失败");
        }

    }
    function addCon(){
        $this->tree->tree(0,0,"catagory",$this->db->connect);
        $this->smarty->assign("str",$this->tree->str);
        $this->smarty->display("admin/addCon.html");
    }
    function addConCheck(){
        $uid=$this->session->get("aid");
        $uname=$this->session->get("aname");
        $cid=P("cid");
        $stitle=P("stitle");
        $sdesc=P("sdesc");
        $simg=P("hidden");
        $scon=P("scon");
        $result=$this->db->insert("insert into shows (stitle,scon,sdesc,cid,uid,author,simg) VALUES ('$stitle','$scon','$sdesc','$cid','$uid','$uname','$simg')");
        if($result>0){
            $this->jump("index.php?m=admin&f=show&a=show","添加文章成功");
        }else{
            $this->jump("index.php?m=admin&f=show&a=addCon","添加文章失败");
        }
    }
}