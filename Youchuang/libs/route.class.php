<?php
/*$_REQUEST["m"]:即能获取get又能获取post传过来的数据。
 *
 * public 公开
 * private： 私有  只能在本类的内部访问
 * protected 受保护的  只能在本类或者子类(继承)中访问。
 * static:  这个方法或者属性，只能作用在类的内部。
 *      static修饰的变量。只能用类去调用：如：一个类为aa  aa(self)::$name;
 * class_exists:检测类存在否？
 * method_exists:检测类里的方法是否存在
 *
 * */
//控制器
class route{
    public static $mould;//模板;
    public static $file;//文件;
    public static $action;//方法;
    function init(){
        $this->getInfo();
    }
    function getInfo(){
        self::$mould=isset($_REQUEST["m"]) && !empty($_REQUEST["m"])?$_REQUEST["m"]:"index";
        self::$file=isset($_REQUEST["f"]) && !empty($_REQUEST["f"])?$_REQUEST["f"]:"index";
        self::$action=isset($_REQUEST["a"]) && !empty($_REQUEST["a"])?$_REQUEST["a"]:"init";
        $file="mould/".self::$mould."/".self::$file.".class.php";
        if(is_file($file)){
            include $file;
            if(class_exists(self::$file)){
                if(method_exists(self::$file,self::$action)){
                    $method=self::$action;
                    $obj=new self::$file();
                    $obj->$method();
                }else{
                    echo self::$action."方法不存在啊！大姐";
                }
            }else{
                echo self::$file."类不存在啊!大兄弟！";
            }
        }else{
            echo $file."文件不存在！";
        }
    }
}
