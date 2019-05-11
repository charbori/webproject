<?php

if(!isset($_POST['text'])){

$html = <<<HTML

	<h1>계산할 숫자를 입력하세요</h1>
	<br/>
	<div>
		<div>
		<button id="1">1</button>
			<button id="2">2</button>
			<button id="3">3</button>
		</div>
		<div>
			<button id="4">4</button>
			<button id="5">5</button>
			<button id="6">6</button>
		</div>
		<div>
			<button id="7">7</button>
			<button id="8">8</button>
			<button id="9">9</button>
		</div>
		<div>
			<input type="button" value="+" id="10">
			<input type="button" value="-" id="11">
			<input type="button" value="*" id="12">
			<input type="button" value="/" id="13">
		</div>
	</div>
	<br/>
	<div>
		<form action="" id="fofo" method="POST" enctype="multipart/form-data">
			<div>전달될 내용:<br/>
				<input type="text" name="text" id="msg"  value="">	
			</div>
			<br/>
			<div>
				<input type="button" id="submitButton" onclick="document.getElementById('fofo').submit();" value="전송">
			</div>
		</form>
	</div>

	<script>
	</script>
	<script>
		function btnClick(e){
			if(parseInt(event.target.id)>=parseInt(10)){
				var text = document.getElementById('msg');
				var button = document.getElementById('submitButton');
				text.value += event.target.value;
				if(text.value.match(/[]/)){
					button.disable = "disable";
				}else{
					button.disable = "true";
				}
			}else{
				document.getElementById('msg').value += event.target.firstChild.nodeValue;
			}
		}
	</script>

	<script>
		var btnArr = new Array();
		for(var i=0;i<13;i++){
			btnArr[i] = document.getElementById(String(i+1));
			btnArr[i].addEventListener("click",btnClick,false);
		}
	</script>
HTML;

echo $html;

}else{

$input = $_POST['text'];
//문자, 숫자 각각 뽑아내 배열에 저장
$numArr = preg_split("/[^0-9]/",$input);
$arithType = preg_split("/[^\+\-\*\/]+/",$input);

$i = 1;							//배열 키 값
$result = $numArr[0];			//시작값
$count = count($numArr);		//while문 종료값

//  * / 경우에 계산해서 result에 저장하고 아니면
// calcul을 호출해서 +-가 나올 때까지 * / 계산 후 반환 
while($i<$count){

	// * / 경우이면 계산
	if($arithType[$i]=="*"){
		$result *= $numArr[$i];
	}else if($arithType[$i]=="/"){
		$result /= $numArr[$i];
	// +- 이면 calcul 호출 후 return 값을 받아서 연산 
	}else{
		$signal = $arithType[$i];
		$returnVal = calcul($numArr[$i]);
		
		if($signal=="+"){
			$result += $returnVal;
		}else{
			$result -= $returnVal;
		}
	}
	$i++;
}

function calcul($paraVal){
	global $numArr;
	global $i;
	$res = $paraVal;
	if(($i+1)<$count){
		while(true){
			if($arithType[$i+1]=="*"){
				$res *= $numArr[$i+1];
			}else if($arithType[$i+1]=="/"){
				$res /= $numArr[$i+1];
			}else{
				return $res;
			}
			$i++;
		}
	}else{
		return $res;
	}
	
}

echo "결과 값 \n";
echo $result;

}
?>