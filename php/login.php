<?php
$db=mysqli_connect("localhost", "root", "1111", "webserver");
$userid = $_POST['id'];
$password = $_POST['pw'];
$sql="select * from clientlist where id='$userid' and pw='$password'";
$result = mysqli_query($db, $sql);
$countNum = mysqli_num_rows($result);
if ($countNum == 1) {
    session_start();
    $_SESSION["userid"]=$userid;
    header('location: http://localhost/index2/index2.html');
} else {
    $str = "<script>";
    $str .= "alert('아이디나 비밀번호가 다릅다.');";
    $str .= "location.href = 'http://localhost/index.html';";
    $str .= "</script>";
    echo("$str");
    /*echo "<script language="javascript"> alert(\"아이디나 비밀번호가 틀립니다.\"); </script>";
    sleep(1);
    header('location: http://localhost/index.html');
*/}

mysqli_close($db);
/*<script language=javascript>
        var popUrl = "loginpopup.html";
        var popOption = "width=370, height=360, resizable=no, scrollbars=no, status=no";
            window.open(popUrl,"",popOption);
    </script>*/
/*
if(mysql_query($db,"select * from clientlist where exists in ($userid)")){
    $var=mysqli_query($db,"select client_num from clientlist where id = $userid")
    if($var==(mysqli_query($db,"select client_num from clientlist where pw = $password"))){
        
    }
    header('Location: http://localhost/index2.html');//php에서 자동으로 페이지를 이동할 수 있게 해준다. 이 문장의 위치는 상관없다.
    
}else{
    <script>
        //자바 이벤트를 출력하면서 로그인 화면으로 돌아가게 만들자.
    </script>
    header('Location: http://localhost/index.html');    
    
}*/
/* 로그인 할 때 아이디 맞게 입력했는지 확인해주는 함수
require 'Validate_form.php';
$val = new Validate();
$val->validate_form($db, $userid, $password);
*/
?>