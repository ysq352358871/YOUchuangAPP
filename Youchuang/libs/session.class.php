<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/3
 * Time: 14:03
 */
class session{
    function __construct(){
        session_start();
    }
    function set($k,$v){
        if(is_array($k)){
            foreach ($k as $k=>$v){
                $_SESSION[$k]=$v;
            }
        }else{
            $_SESSION[$k]=$v;
        }
    }
    function del($k){
        unset($_SESSION[$k]);
    }
    function clear(){
        foreach ($_SESSION as $k=>$v){
            unset($_SESSION[$k]);
        }
    }
    function get($k){
        return empty($_SESSION[$k])?null:$_SESSION[$k];
    }
}