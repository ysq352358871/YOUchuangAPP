<?php
/*
 * 传参：多个参时 以;(分号为间隔);
 **/

class db{
    public $hostname="localhost";
    public $dbname="blog";
    public $tablename;
    private $username="root";
    private $password="";
    public $fileds;
    public $connect;
    function __construct($tablename){
        $this->tablename=$tablename;
        $this->connect=new mysqli($this->hostname,$this->username,$this->password,$this->dbname);
        if(mysqli_connect_errno($this->connect)){
            echo "连接数据库失败";
            $this->connect->close();
            exit;
        }
        $this->connect->query("set names utf8");
        $this->fileds["filed"]=$this->fileds["filed"]?$this->fileds["filed"]:"*";
        $this->fileds["where"]=$this->fileds["order"]=$this->fileds["limit"]="";
        $this->fileds["keys"]=$this->fileds["values"]="";
    }
    public function select($opt=""){    //数据的查询;
        if(strpos($opt,"elect")){
            $sql=$opt;
        }else if(empty($opt)){
            $sql="select ".$this->fileds["filed"]." from ".$this->tablename." ".$this->fileds["where"]." ".$this->fileds["order"]." ".$this->fileds["limit"];
        }else{
            $this->filed($opt);
            $sql="select ".$this->fileds["filed"]." from ".$this->tablename." ".$this->fileds["where"]." ".$this->fileds["order"]." ".$this->fileds["limit"];
        }
        $result=$this->connect->query($sql);
        $arr=array();
        if($result){ //如果是空的 就让他返回空数组
            while($row=$result->fetch_assoc()){
                $arr[]=$row;
            }
        }
        return $arr;
    }
    public function filed($opt=""){
        $sql=$opt?$opt:"*";
        if(strpos( $sql,";")){
            $arr=explode(";",$sql);
            $keys="";
            $values="";
            foreach ($arr as $k=>$v){
                $newarr=explode("=",$v);
                $keys.=$newarr[0].",";
                $values.=$newarr[1].",";
            }
            $sql=str_replace(";",",",$sql);
            $this->keys=substr($keys,0,-1);
            $this->values=substr($values,0,-1);
        }else if(strpos( $sql,"=")){
            $newarr=explode("=",$sql);
            $this->keys=$newarr[0];
            $this->values=$newarr[1];
        }
        $this->fileds["filed"]=$sql;
        return $this;
    }
    public function where($str=""){  //条件
        $sql=empty($str)?"":"where ".$str;
        $this->fileds["where"]=$sql;
        return $this;
    }
    public function order($str=""){ //排序
        $sql=empty($str)?"":"order by ".$str;
        $this->fileds["order"]=$sql;
        return $this;
    }
    public function limit($str=""){  //截取
        $sql=empty($str)?"":"limit ".$str;
        $this->fileds["limit"]=$sql;
        return $this;
    }
    public function delete($str=""){    //数据的删除
        if(strpos($str,"elete")){
            $sql=$str;
        }else if(empty($str)){
            $sql="delete from ".$this->tablename." ".$this->fileds["where"];
        }else{
            $this->where($str);
            $sql="delete from ".$this->tablename." ".$this->fileds["where"];
        }
        $this->connect->query($sql);
        return $this->connect->affected_rows;
    }
    public function update($str=""){    //修改数据
        if(strpos($str,"pdate")){
            $sql=$str;
        }else if(empty($str)){
            $sql="update ".$this->tablename." set ".$this->fileds["filed"]." ".$this->fileds["where"];
        }else{
            $this->filed($str);
            $sql="update ".$this->tablename." set ".$this->fileds["filed"]." ".$this->fileds["where"];
        }
        $this->connect->query($sql);
        return $this->connect->affected_rows;
    }
    public function insert($str=""){    //插入数据
        if(strpos($str,"nsert")){
            $sql=$str;
        }else if(empty($str)){
            $this->fileds["filed"]="(".$this->keys.") values (".$this->values.")";
            $sql="insert into ".$this->tablename." ".$this->fileds["filed"];
        }else{
            $this->filed($str);
            $this->fileds["filed"]="(".$this->keys.") values (".$this->values.")";
            $sql="insert into ".$this->tablename." ".$this->fileds["filed"];
        }
        $this->connect->query($sql);
        return $this->connect->affected_rows;
    }
}
//$db=new db("user");
//var_dump($db->where("uid=8")->filed("uname='wangba'")->update());