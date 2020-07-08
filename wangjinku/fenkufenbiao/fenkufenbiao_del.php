<?php
/**
 * Created by PhpStorm.
 * User: kona
 * Date: 2019/8/12
 * Time: 17:02
 */
//防止超过PHP的最大执行时间
//set_time_limit(0);
header("content-type:text/html;charset=utf-8");
$con = mysqli_connect('127.0.0.1', 'root', 'root');
if (!$con) {
    die('错误信息' . mysql_connect_error($con));
}
mysqli_query($con, 'set names utf8');

//dropDataBase($con,10);//清空所有的数据库和表drop级别

createDbAndTab($con, 10,10);//创建数据库和数据表

//cleanAll($con,10,10);//清空数据库表中的数据

die();

/**
 *  清理所有数据库表的数据
 * @param $con    数据库连接数
 * @param $dbNum  数据库数
 * @param $tabNum 数据库表数
 */
function cleanAll($con,$dbNum,$tabNum)  {
    for ($i = 0; $i <$dbNum; $i++) {
        mysqli_select_db($con, 'user_db_'.$i);
        echo '《《----select db'.$i."------》》\n<br>";
        for($j=0;$j<$tabNum;$j++) {
            $sql = "delete from user_{$j}";
            $res = mysqli_query($con, $sql);
            if (!$res) {
                echo $sql."\n\n\n";
                echo '错误';
            }
            echo $sql."\n\n";
        }
    }
}

/** 创建数据库表
 * //创建表
 * @param $con 数据库连接
 * @param $dbNum  库数
 * @param $tabNum 表数
 */
function createDbAndTab($con,$dbNum,$tabNum){
    //创建统一的ID分配器所在的库
    $auto_sql = "CREATE DATABASE `fenkufenbiao` CHARACTER SET 'utf8' COLLATE 'utf8_general_ci'";
    $res = mysqli_query($con, $auto_sql);
    //创建统一的ID分配器的表
    mysqli_select_db($con, 'fenkufenbiao');
    $auto_tab = "CREATE TABLE `autoincrease` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tab_id` int(11) DEFAULT '0',
  `tab_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
    $res = mysqli_query($con, $auto_tab);
    //初始化统一的ID初始值
    $insert = "INSERT INTO `fenkufenbiao`.`autoincrease`(`id`, `tab_id`, `tab_name`) VALUES (1, 1, 'user');";
    $res = mysqli_query($con, $insert);
    //创建分库的库和分表的表
    for ($i = 0; $i <$dbNum; $i++) {
        $dbsql = "CREATE DATABASE `user_db_{$i}` CHARACTER SET 'utf8' COLLATE 'utf8_general_ci'";
        $res = mysqli_query($con, $dbsql);
        mysqli_select_db($con, 'user_db_'.$i);
        for($j=0;$j<$tabNum;$j++) {
            $tabsql = "CREATE TABLE `user_{$j}` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `phone` char(11) NOT NULL DEFAULT '',
  `province` int(11) NOT NULL DEFAULT '0',
  `city` int(11) NOT NULL DEFAULT '0',
  `area` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `province_city_area` (`province`,`city`,`area`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;";
            $res = mysqli_query($con, $tabsql);
            if($res){
                echo "user_db_ $i 库，user_ $j 表创建成功 <br/>";
            }else{
                echo "user_db_ $i 库，user_ $j 表创建失败<br/>";
            }
        }
        echo "<hr/>";
    }
}

/** 删除数据库
 * @param $con 数据库连接
 * @param $dbNum 数据库的数量
 * //对库drop
 */
function dropDataBase($con,$dbNum){
    $sql = "drop database fenkufenbiao";
    $res = mysqli_query($con, $sql);
    echo "<br/> fenkufenbiao 库drop完成  <br/>";
    for ($i = 0; $i <$dbNum; $i++) {
        $sql = "drop database user_db_{$i}";
        $res = mysqli_query($con, $sql);//对fenkufenbiao删除
        echo "<br/> user_db_ $i 库drop完成  <br/>";
    }
}