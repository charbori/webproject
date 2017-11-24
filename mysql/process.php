<?php
mysqli_connect('localhost','root','mysql');
mysqli_select_db('webserver');
switch($_GET['mode']){
    case 'insert':
        $sql = "INSERT INTO bbs_2 (title,sub_title,description, created) VALUES ('".mysqli_real_escape_string($_POST['title']) ."','".mysqli_real_escape_string($_POST['sub_title'])."','".mysqli_real_escape_string($_POST['description'])."')";
        //header("Location: list.php");
        echo $sql;
        echo "제출에 성공했닭";
        $result = mysqli_query($sql);
        break;
    case 'delete':
        mysqli_query('DELETE FROM bbs_2 WHERE id = '.mysqli_real_escape_string($_POST['id']));
        header("Location: list.php");
        break;
    case 'modify':
        mysqli_query('UPDATE bbs_2 SET title = "'.mysqli_real_escape_string($_POST['title'])'"');
        header("Location: list.php?id={$_POST['id']}");
        break;
}
?>