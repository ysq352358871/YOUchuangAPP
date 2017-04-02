<?php
/*关键字
 *  public:公开的
 *  private:私有的
 *
 *
 */
    class db{
        public $a="aa"; //公共的变量
        private $b="bb"; //私有的变量
        protected $c="cc";//受保护的变量
    }
 $db=new db();
//echo $db->b;

?>