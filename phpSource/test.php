<?
include "lib.php";

//echo "test1";

//html 긁어서

$num1 = 999;
$num2 = 444;

$html = load_htm("t");
$block_data=get_block("num_list",$html);

$temp_data= array (  get_t("num1") => $num1,
					get_t("num2") => $num2,
                 );

      for($i=1;$i<6;$i++){

      	$temp_data[get_t("num1")] = $i;
      	$temp_data[get_t("num2")] = 6 - $i;
      	$block_temp=a_replace($temp_data,$block_data);
      	$html=ins_block("num_list",$block_temp,$html);
      }
      	echo $html;

//echo "test2";
//$txt = 1234;
//html 데이터 
//$pre_data = array( get_t("txt") => $txt );
//$html=a_replace($pre_data ,$html);

//html 출력 
////echo $block_data;

?>