<?php
header("content-type:text/html; charset=UTF-8");

$user_name=$_POST[user_name];
$id=$_POST[id];
$pw=$_POST[pw];
$sex=$_POST[sex];
$email=$_POST[email];

$connect=mysqli_connect("localhost","root","mysql");
mysqli_select_db("webserver",$connect);
if(!$connect){
    echo "연결에 실패 하였습니다.",mysqli_error();
}else{
    echo "페이지 삽입할 것";
}

$query="insert into bbs_1(user_name,id,pw,sex,email)
                    values('$user_name','$id','$pw','$sex','$email')";
mysqli_query($query, $connect);

mysqli_close;
?><!--
$user_name= $_POST[user_name];
$sex= $_POST[sex];
$id= $_POST[id];
$pw= $_POST[pw];
$email= $_POST[email];
$ip=getenv("REMOTE_ADDR");

 if(!$connect){
	echo "연결에 실패 하였습니다.".mysql_error();
 
 }
 
 $query="insert into id_db(user_name, sex,id, pw, email,ip)
						values('$user_name','$sex','$id','$pw','$email','$ip')";
mysql_query($query,$connect);

mysql_close;
-->
