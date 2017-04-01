<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/4
 * Time: 15:55
 */
class user extends main{
    function __construct(){
        parent::__construct();
        $this->db=new db("admin");
        $this->dbuser=new db("user");
    }

    //管理员修改密码
    function updatepass(){
        $aid=$this->session->get("aid");
        $aname=$this->session->get("aname");
        $this->smarty->assign("aid",$aid);
        $this->smarty->assign("aname",$aname);
        $this->smarty->display("admin/pass.html");
    }
    //修改页面检查
    function updatePassCheck(){
        $apass=md5($_POST["mpass"]);
        $newpass=md5($_POST["newpass"]);
        $result=$this->db->where("aid={$this->session->get('aid')}")->select();
            if($apass==$result[0]["apass"]){
                if($this->db->where("aid={$this->session->get('aid')}")->filed("apass='$newpass'")->update()>0){
                    $this->jump("index.php?m=admin&f=user&a=updatepass","修改失败");
                }else{
                    $this->jump("index.php?m=admin&f=user&a=updatepass","修改失败");
                }
            }else{
                $this->jump("index.php?m=admin&f=user&a=updatepass","原始密码不对！");
            }
    }
    //添加用户
    function addUser(){
        $this->smarty->display("admin/addUser.html");
    }
    function addUserCheck(){
        $uname=$_POST["uname"];
        $upass=md5($_POST["upass"]);
        $result=$this->dbuser->select();
        foreach ($result as $k=>$v){
            if($v["uname"]==$uname){
                $this->jump("index.php?m=admin&f=user&a=addUser","用户名已经存在");
                exit;
            }
        }
        $resulti=$this->dbuser->filed("uname='$uname';upass='$upass'")->insert();
        if( $resulti>0){
            $this->jump("index.php?m=admin&f=user&a=edit","添加成功");
            exit;
        }else{
            $this->jump("index.php?m=admin&f=user&a=addUser","添加失败");
            exit;
        }
    }
    //管理用户
    function editUser(){
        $result=$this->dbuser->select();
        $this->smarty->assign("result",$result);
        $this->smarty->display("admin/editUser.html");
    }
    //修改用户
    function update(){
        echo 1;
    }
    //删除用户
    function delete(){
        $uid=$_GET["id"];
        $result=$this->dbuser->where("uid=$uid")->delete();
        if($result>0){
            $this->jump("index.php?m=admin&f=user&a=editUser","删除成功");
        }else{
            $this->jump("index.php?m=admin&f=user&a=editUser","删除失败");
        }
    }
}