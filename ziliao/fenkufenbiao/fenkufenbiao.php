<?php
/**
 * Created by PhpStorm.
 * User: kona
 * Date: 2019/8/12
 * Time: 17:02
 */
//set_time_limit(0);
header("content-type:text/html;charset=utf-8");
$con = mysqli_connect('localhost', 'root', 'root');
if (!$con) {
    die('错误信息' . mysql_connect_error($con));
}

mysqli_query($con, 'set names utf8');
//一次插入数据的条数
$incNum = 1000;
//获取统一自增ID
$new_tab_id = getAutoIncreaseId($con,$incNum);
$dbTotal = 10; //总库数
$tabTotal = 10; //每一个库中表数
var_dump($new_tab_id);
for ($i = 1; $i <= $incNum; $i++) {
    //对库和表的乘积求尾数
    $temp = $new_tab_id%($dbTotal*$tabTotal);
    echo 'the foot of id:'.$temp."\n<br>";
    //尾数对表数商 是库数
    $dbnum = floor($temp/$tabTotal);
    echo " 插入的库为 user_db_".$dbnum."<br/>";
    mysqli_select_db($con, 'user_db_'.$dbnum);
    //尾数对表数求余数是表数
    $tabNum = $temp%$tabTotal;
    echo "插入的库名为 user_".$tabNum."<br/>";
    $mobile = rand(10000000000,19999999999);
    $provice = rand(1,34);
    $city = rand(50,100);
    $area = rand(101,500);
    $user_name = 'user_'.$new_tab_id;
    $sql = "INSERT INTO user_$tabNum (`id`, `name`, `phone`,`province`,`city`,`area`) VALUES ($new_tab_id, '{$user_name}', '{$mobile}','{$provice}','{$city}','{$area}')";
    $res = mysqli_query($con, $sql);
    if (!$res) {
        echo $sql."\n\n\n<br><br>";
        echo '错误';
    }
    echo "$sql 执行成功 <br/> <hr/>";
    $new_tab_id++;
}

/***
 * @param $con     数据库连接
 * @param $incNum  每次需要添加的个数
 * @return mixed
 */
function getAutoIncreaseId($con,$incNum=1,$tab_name= 'user'){
    //选择数据库
    mysqli_select_db($con, 'fenkufenbiao');
    //获取当前表的ID
    $sql = "SELECT * FROM `autoIncrease` where tab_name='".$tab_name."'";
    $stm = mysqli_query($con,$sql);
    $tabRow = mysqli_fetch_assoc($stm);


    var_dump($stm);
    var_dump($tabRow);//设置数据库表的自增ID
    $sql = "update `autoIncrease` set tab_id= `tab_id`+$incNum  where tab_name='user'";
    mysqli_query($con,$sql);
    return $tabRow['tab_id'];
}