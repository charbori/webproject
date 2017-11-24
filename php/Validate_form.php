<?

class Validate{
    $id;
    $pw;
    $users=array();
    public function validate_form($db,$id,$pw){
        //$users = mysqli_query($db,"select id from clientlist");
        this->$pw=$pw;
        this->$id=$id;
        this->$users=$db;
        foreach($users as $users){
            print "$users";
        }
        print "전달 잘 됐는지 확인: $id $pw"
    }
}
?>