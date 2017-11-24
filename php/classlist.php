<?
    $con=mysql_connect("localhost","root","1111","classlist");
    or die("MySQL 접속 실패");
    
    $sql = "SELECT * FROM classlist";
    
    $ret = mysqli_query($con,$sql);

    if($ret){
        echo mysqli_num_rows($ret), "건이 조회됨.<br><br>";
    }else{
        echo "강좌 조회 실패!!!"."<br>";
        echo "실패 원인 :".mysqli_error($con);
        exit();
    }
    while($row = mysqli_fetch_array($ret)){
        echo $row['classname'], " ", $row['']
    }
/*php에서 javascript에 변수 값을 넣을 때
$str = 'hello';
$arr = array('my', 'friend');
<script>
var str = '<?= $str ? >';
var arr = <?= json_encode($arr) ? >;

console.log(str); // hello///
console.log(arr); // ["my","friend"]
</script>
*/
/*php에서 javascript에 변수 값을 넣을 때 
$sName = "삽잡이";
echo ("<script language=javascript> getName($sName);</script>");


출처: http://shovelman.tistory.com/811 [한글로는 삽잡이, 영어로는 shovelMan]

출처: http://shovelman.tistory.com/811 [한글로는 삽잡이, 영어로는 shovelMan]*/
?>
