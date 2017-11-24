<?php
header('Location: http://localhost/index.html');//php에서 자동으로 페이지를 이동할 수 있게 해준다. 이 문장의 위치는 상관없다.
$db = mysqli_connect("localhost","root","1111","webserver");
/*mysqli_query($con,"SELECT * FROM clientlsit");
mysqli_query($con,"INSERT INTO Persons (name,id,pw,age) 
VALUES ('Glenn','root','1111',33)");*/
/*mysqli_query($con,"INSERT INTO clientlist(name,id,pw,age) VALUES(
                  '".mysqli_real_escape_string($con,$_POST['name'])."',
                  '".mysqli_real_escape_string($con,$_POST['id'])."',
                  '".mysqli_real_escape_string($con,$_POST['pw'])."',
                  '".mysqli_real_escape_string($con,$_POST['age'])."')");

post할 때 괜히 int값 넣어서 보낼라고 하다가 헤매지 말고
나중에 알게 되면 하도록 해야겠다
*/
$name = mysqli_real_escape_string($db, $_POST['name']);
$id = mysqli_real_escape_string($db, $_POST['id']);
$pw = mysqli_real_escape_string($db, $_POST['pw']);
$age = mysqli_real_escape_string($db, $_POST['age']); 
$sql="INSERT INTO clientlist (name, id, pw, age) VALUES ('$name', '$id', '$pw', '$age')";
if (!mysqli_query($db,$sql)) {
    die('Error: ' . mysqli_error($db));
}
// sql에서의 길이 칸은 개수가 아니라 말 그래도 길이다.ㅜㅜ <script>location.href='RSDB_starterror.php';</script>
mysqli_close($db);
?>