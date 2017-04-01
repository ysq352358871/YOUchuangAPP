<?php
class jibenSet extends main{
    function __construct(){
        parent::__construct();
        $this->db=new db("message");
    }
    function show(){      //去除留言,展示
        $result=$this->db->select();
        $pageObj=new pages(count($result),5,"pages");
        $pages=$pageObj->out();
        $this->smarty->assign("pages",$pages);
        $limit=$pageObj->limit;
        $current=$this->db->select("select * from message ".$limit);
        $this->smarty->assign("result",$current);
        $this->smarty->display("admin/manageLiuyan.html");
    }
    function del(){ // 删除留言
        $mid=G("id");
        if($this->db->where("mid={$mid}")->delete()>0){
            $this->jump("index.php?m=admin&f=jibenSet&a=show","删除成功");
        }else{
            $this->jump("index.php?m=admin&f=jibenSet&a=show","删除失败");
        }
    }

    function bannerShow(){
        echo 1;
    }

}