<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/3
 * Time: 17:27
 */
function P($param){
    return $_POST[$param];
}
function G($param){
    return $_GET[$param];
}
class fun{
    function fun(){
        $this->str="";
    }
    function tree($pid,$flag,$table,$db,$currentId=null){
        if($currentId){
            $result=$db->query("select * from $table where cid=".$currentId);
            $row=$result->fetch_assoc();
            $p=$row["pid"];
        }
        $flag=$flag+1;
        $sql="select * from $table where pid=".$pid;
        $row=$result=$db->query($sql);
        while($row=$result->fetch_assoc()){
            $cid=$row["cid"];
            $str=str_repeat("-",$flag);
            if($currentId&&$p==$row["cid"]){
                $this->str.="<option value='$p' selected='selected'>{$str}{$row["cname"]}</option>";
            }else{
                $this->str.="<option value='$cid'>{$str}{$row["cname"]}</option>";
                $this->tree($row["cid"],$flag,$table,$db,$currentId);
            }


        }


    }
    function table($pid,$flag,$table,$db){
        $flag=$flag+1;
        $sql="select * from $table where pid=".$pid;
        $row=$result=$db->query($sql);
        while($row=$result->fetch_assoc()){
            $cid=$row["cid"];
            $str=str_repeat("-",$flag);
            $this->str.="<tr>
					<td>{$cid}</td>
					<td>{$str}{$row["cname"]}</td>
					<td>{$row["pid"]}</td>
					<td>{$row["ckeyWords"]}</td>
					<td>{$row["cdesc"]}</td>
					<td style='height: 67px'><img src='{$row["cimg"]}' alt='' style='width:auto;height:auto;max-width:100%;max-height:100%'/></td>
					<td>
					    <div class='button-group'>
                            <a class='button border-main' href='index.php?m=admin&f=catagory&a=update&id=$cid'><span class='icon-edit'></span> 修改</a>
                            <a class='button border-red' href='index.php?m=admin&f=catagory&a=del&id=$cid'><span class='icon-trash-o'></span> 删除</a>
                        </div>        
                    </td>
					</tr>";
            $this->table($row["cid"],$flag,$table,$db);
        }

    }
    function delete($cid,$table,$db){
        $sql="select * from $table where pid=".$cid;
        $result=$db->query($sql);
        $row=$result->fetch_assoc();
//			while($row=$result->fetch_assoc());
        if(!$row){
            $sql="delete from $table where cid=".$cid;
            $db->query($sql);
            if($db->affected_rows>0){
                return true;
            }
        }
        else{
            return false;
        }
    }
    function treeCon($pid,$flag,$table,$db,$currentId=null){
        $flag=$flag+1;
        $sql="select * from $table where pid=".$pid;
        $row=$result=$db->query($sql);
        while($row=$result->fetch_assoc()){
            $cid=$row["cid"];
            $str=str_repeat("-",$flag);
            if($currentId==$row["cid"]){
                $this->str.="<option value='$cid' selected='selected'>{$str}{$row["cname"]}</option>";
            }else{
                $this->str.="<option value='$cid'>{$str}{$row["cname"]}</option>";
                $this->treeCon($row["cid"],$flag,$table,$db,$currentId);
            }


        }


    }
}