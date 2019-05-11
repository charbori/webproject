<?

include "var.php";

function load_htm($name)
{
	$data="";

	$filename=$name.".htm";
	$fp = @fopen("$filename",r); 
	$data = @fread($fp,filesize("$filename")); 
	@fclose($fp);

	return $data;
} 

function get_t($name)
{
	return ":z|".$name.":";
}

function a_replace($array,$data)
{ 
	foreach ($array as $key => $val)
	{ 
		$data = str_replace("$key","$val","$data"); 
	} 

	return $data; 
} 

function get_block($block_name,&$data)
{
	$fn_len=strlen($block_name)+8;

	$start_block_name="<!--#".$block_name."-->";

	$end_block_name="<!--".$block_name."#-->";
	$start_pos=strpos($data,$start_block_name)+$fn_len;

	$end_pos=strpos($data,$end_block_name)-$start_pos;

	$result=substr($data,$start_pos,$end_pos);
	$data=substr_replace($data,"",$start_pos,$end_pos);
	return $result;
}

function ins_block($block_name,$insert_data,$data)
{

	$fn_len=strlen($block_name)+8;

	$start_block_name="<!--#".$block_name."-->";

	$end_block_name="<!--".$block_name."#-->";

	$start_pos=strpos($data,$start_block_name)+$fn_len;

	$end_pos=strpos($data,$end_block_name);

	$result=substr_replace($data,$insert_data,$end_pos,0);

	return $result;
}

function strToHex2($string,$str_len)
{
	$hex = '';
	for ($i=0; $i<$str_len; $i++)
	{				        
		$ord = ord($string[$i]);
		$hexCode = dechex($ord);
		$hex .= substr('0'.$hexCode, -2);
	}
	 return strToUpper($hex);
}

function getKey($date,$key) 
{
	$gen_iv ="";

//	$sha256 = hash('sha256',$key);

	$sha256 = $key;

//	echo "SHA256<br>".$sha256."<br>";

// Step 2

//	$iv_pos = (substr($date,0,4)+2)*$cal_date % 64;

	$cal_date = (substr($date,4,2)+3) * (substr($date,6,2)+4);
	$iv_pos = $cal_date % 32;

//	echo "<br><br>iv_pos #1 (mon * day % 32): ".$iv_pos."<br>";

	for($i=0; $i < 32; $i++)
	{
		$gen_iv .= substr($sha256,$iv_pos,1);
			
		if($iv_pos < 31)	$iv_pos++;
		else				$iv_pos = 0;

	}


//	echo "<br>gen_iv #2: ".$gen_iv."<br>";

	return strToUpper($gen_iv);
}


function getAuthKey($pDate) 
{
	if(strlen($pDate) < 6)		$pDate = date("Ymd");

	//$key_t = "peaksystem!iotwizbox!!wizberry!!";
	$key_t = "32b6b07a4e3c3f6445bb1d5a7d0a626d7a05f3198800c61cc7d91f8d8e549368";

	return getKey($pDate,$key_t);

}


function _hex2bin($inp) 
{
	$res = pack("H*" ,$inp);
	return $res;
}

function pkcs7pad($plaintext, $blocksize)
{
    $padsize = $blocksize - (strlen($plaintext) % $blocksize);
    return $plaintext . str_repeat(chr($padsize), $padsize);
}


function pkcs5pad($unpaddedString, $blockSize) {
    $additionalChars = $blockSize - strlen($unpaddedString) % $blockSize;

    $char = chr($additionalChars);

    return $unpaddedString.str_repeat($char, $additionalChars);
  }

function DecTest() 
{
	/*
	$relay ="0001";

	$rly_value = 0;
	$rly_pos = strpos($relay, "1");

	if ($rly_pos === false) 
	{
		$rly_pos = strpos($relay, "2"); //??
		$rly_value = 1;

		echo "rly_pos ".$rly_pos."<br>";
	}

	$rly=substr_replace("0000", $rly_value , $rly_pos, 1);

	$res = $rly;
	*/


	$res ="";

	$key = getAuthKey("19480815");
//	$key = getAuthKey("20170328");
//	$data_t = "6494F36296D1D92F226258C4BB28175A";
	$data_t = "28F2C26F0E413E41BC868CB7DE05805297525E879E4CCDA0E92388A6F695B9BE";
	$data_t = "6494F36296D1D92F226258C4BB28175A6C756C75706F742177697A626F782121";

	$res = openssl_decrypt(_hex2bin($data_t), "aes-256-ecb", $key, true);

	$data_t = "lulupot!wizbox!!lulupot!wizbox!!";

	echo "<br>ENC<br>".strToHex2(@openssl_encrypt($data_t, "aes-256-ecb", $key, true),strlen($data_t))."<br>";



//	echo "<br>[pad]<br>".strToHex2(pkcs7pad(_hex2bin($data_t),32),32)."<br>";

	return $res;
}

function EncEmail($email) 
{
	$res ="";

	
	$data_t = $email;

	$pKey = getAuthKey("19480815");

	$res = base64_encode(@openssl_encrypt($data_t, "aes-256-ecb", $pKey, true));

	return $res;
}

function getAuthEnc($pKey, $isBase64, $isLimit16) 
{
	$res ="";

	$data_t = "lulupot!wizbox!!";

	//if($isBase64 == true)		$res = base64_encode(substr(@openssl_encrypt($data_t, "aes-256-ecb", $pKey, true),0,strlen($data_t)));
	if($isBase64 != true)	
	{
		if($isLimit16)
		{
			$enc_res = @openssl_encrypt($data_t, "aes-256-ecb", $pKey, true);

			$res = substr($enc_res,0,16);

			//printf("<br>cut_res[%d]:%s<br>",strlen($cut_res),$cut_res);

			//$res = base64_encode($cut_res);
			
		}
		else
		{
			$res = strToHex2(@openssl_encrypt($data_t, "aes-256-ecb", $pKey, true),strlen($data_t));
		}

	}
	else	$res = base64_encode(@openssl_encrypt($data_t, "aes-256-ecb", $pKey, true));



	return $res;
}

function isAuth($pDate, $pAuth) 
{
	$res = false;

	$enc = getAuthEnc(getAuthKey($pDate),false, false);

	if($enc == $pAuth)		$res = true;
	else					echo $enc."<br><br>";

	return $res;
}

function isAuthBase64($pDate, $pAuth) 
{
	$res = false;

	$pAuth = urldecode($pAuth);

	$enc = getAuthEnc(getAuthKey($pDate),true, false);

	if($enc == $pAuth)		$res = true;



/*
	if(!$res)		$enc = getAuthEnc(getAuthKey($pDate),false);

*/

	return $res;
}

function isAuth_16($pAuth) 
{
	$res = false;

//	$pAuth = urldecode($pAuth);

//	$pAuth = substr($pAuth,0,16);

	$pDate = date("Ymd");

	$enc = getAuthEnc(getAuthKey($pDate),false, true);

	if($enc == $pAuth)		$res = true;
	else
	{
/*

		printf("<br>enc[%d]:%s<br>",strlen($enc),$enc);
		printf("<br>pAuth[%d]:%s<br>",strlen($pAuth),$pAuth);

		$bin1 = unpack('C*', $enc);
		$bin2 = unpack('C*', $pAuth);

		for($i = 0; $i < 18; $i++)
		{
			printf("%02d: Enc [%02X] [%02X] Auth<br>",$i, $bin1[$i],$bin2[$i]);
		}
*/

	}
/*
	if(!$res)		$enc = getAuthEnc(getAuthKey($pDate),false);

*/

	return $res;
}

function bin2byte($inpData)
{
	$res = array();
	$bin = unpack('C*', $inpData);

	for($i = 0; $i < sizeof($bin); $i++)
	{
		$res[$i] = $bin[$i+1];
	}

	return $res;
}

function revrse_chg_Idx2($pData, $nStartPos, $nRefStartPos, $nEndPos, $nChgIdx)
{
	$debug = true;

	$nSize = sizeof($pData);

//	$nChgIdx = $nSize - $nChgIdx + ($nStartPos+1);


	if($debug)	echo	"<br>nSize:".$nSize."<br>";
	if($debug)	echo	"<br>nChgIdx:".$nChgIdx."<br>";

	if($nChgIdx != 1)	$nChgIdx = $nSize - ($nChgIdx) + ($nStartPos)+1;

	if($debug)	echo	"<br>nChgIdx:".$nChgIdx."<br>";


	$strPreChgHeader = array();

	for($i = 0; $i <= $nSize; $i++)
	{
		if($i >= $nStartPos && $i <= $nEndPos)
        {
			$strPreChgHeader[$i] = $pData[$nChgIdx];

			if($debug)	printf("[%02X]	%s	[i: %d]	[chgIdx: %d] [nStartPos: %d]<br>",$pData[$nChgIdx],chr($pData[$nChgIdx]),$i,$nChgIdx,$nStartPos);


			if($nChgIdx < $nEndPos)	    $nChgIdx++;
			else				        $nChgIdx = $nRefStartPos;
		}
		else
		{
			$strPreChgHeader[$i] = $pData[$i];
		}
		
	}

	return $strPreChgHeader;
}

function getVChgIdx($idx)
{
	return ($idx+1);
}

function revrse_chg_Idx($pData, $nStartPos, $nRefStartPos, $nEndPos, $nChgIdx)
{
	$debug = false;

	if($debug)	var_dump($pData);

	$nSize = sizeof($pData);

//	$nChgIdx = $nSize - $nChgIdx + ($nStartPos+1);


	if($debug)	printf("<br><br>nSize[%d] nStartPos[%d] nRefStartPos[%d] nEndPos[%d] nChgIdx[%d]<br>",$nSize, $nStartPos, $nRefStartPos, $nEndPos, $nChgIdx);
	if($debug)	echo	"<br>pData[nSize]:".$pData[$nSize]."<br>";

	if($nChgIdx != 0)	$nChgIdx = $nSize - ($nChgIdx) + ($nStartPos-1);

	$nChgIdx+=$nStartPos;

	if($nChgIdx >= $nSize)	$nChgIdx = $nRefStartPos + ($nChgIdx - $nSize);

	if($debug)	echo	"<br>nChgIdx:".$nChgIdx."<br>";


	$strPreChgHeader = array();

	for($i = 0; $i < $nSize; $i++)
	{
		if($i >= $nStartPos && $i < $nEndPos)
        {
			$strPreChgHeader[$i] = $pData[$nChgIdx];

			if($debug)	printf("#1[%02X]	%s	[i: %d]	[chgIdx: %d] [nStartPos: %d]<br>",$pData[$nChgIdx],chr($pData[$nChgIdx]),$i,$nChgIdx,$nStartPos);


			if($nChgIdx < ($nEndPos-1))		$nChgIdx++;
			else							$nChgIdx = $nRefStartPos;
		}
		else
		{
			$strPreChgHeader[$i] = $pData[$i];

			if($debug)	printf("#2[%02X]	%s	[i: %d]	[chgIdx: %d] [nStartPos: %d]<br>",$pData[$nChgIdx],chr($pData[$i]),$i,$nChgIdx,$nStartPos);
		}
		
	}

	return $strPreChgHeader;
}

function revrse_chg_Idx_test($pData, $nStartPos, $nRefStartPos, $nEndPos, $nChgIdx)
{
	$debug = true;

	if($debug)	var_dump($pData);

	$nSize = sizeof($pData);

//	$nChgIdx = $nSize - $nChgIdx + ($nStartPos+1);


	if($debug)	printf("<br><br>nSize[%d] nStartPos[%d] nRefStartPos[%d] nEndPos[%d] nChgIdx[%d]<br>",$nSize, $nStartPos, $nRefStartPos, $nEndPos, $nChgIdx);
	if($debug)	echo	"<br>pData[nSize]:".$pData[$nSize]."<br>";

	if($nChgIdx != 0)	$nChgIdx = $nSize - ($nChgIdx) + ($nStartPos-1);

	$nChgIdx+=$nStartPos;

	if($nChgIdx >= $nSize)	$nChgIdx = $nRefStartPos + ($nChgIdx - $nSize);

	if($debug)	echo	"<br>nChgIdx:".$nChgIdx."<br>";


	$strPreChgHeader = array();

	for($i = 0; $i < $nSize; $i++)
	{
		if($i >= $nStartPos && $i < $nEndPos)
        {
			$strPreChgHeader[$i] = $pData[$nChgIdx];

			if($debug)	printf("#1[%02X]	%s	[i: %d]	[chgIdx: %d] [nStartPos: %d]<br>",$pData[$nChgIdx],chr($pData[$nChgIdx]),$i,$nChgIdx,$nStartPos);


			if($nChgIdx < ($nEndPos-1))		$nChgIdx++;
			else							$nChgIdx = $nRefStartPos;
		}
		else
		{
			$strPreChgHeader[$i] = $pData[$i];

			if($debug)	printf("#2[%02X]	%s	[i: %d]	[chgIdx: %d] [nStartPos: %d]<br>",$pData[$nChgIdx],chr($pData[$i]),$i,$nChgIdx,$nStartPos);
		}
		
	}

	return $strPreChgHeader;
}

function cutChgIdx2($pData, $isHeader)
{
	$debug = false;

	$res = array();

	$nCnt = 0;

//	$bin = unpack('C*', $pData);
	$bin = bin2byte($pData);

	$nPos = 1;
	$nStartPos = array(2,2,2);
	//$nEndPos = sizeof($bin);
	$nEndPos = array(sizeof($bin),sizeof($bin));

	if($isHeader)
	{
		$nPos = 19;
		$nStartPos = array(0,2,1);
		$nEndPos = array(sizeof($bin)-1,sizeof($bin)-2);

		if($debug)	printf("<br>nEndPos: %d<br>",$nEndPos);
	}


	$nChgIdx = (int)$bin[$nPos];

	if($debug)	echo "<br>[nChgIdx] ".$nChgIdx."<br>";

	$strCut = "";

	$chg_bin = array();

	$chg_bin = revrse_chg_Idx($bin,$nStartPos[0],$nStartPos[2],$nEndPos[0],($nChgIdx+1));

	for($i = $nStartPos[1]; $i <= $nEndPos[1]; $i++)
	{
		$strCut.=chr($chg_bin[$i]);

		//echo "bin_$i: ".$bin[$i]."<br>";
		if($debug)		$nCnt++;
	}

	$res[0] = $nChgIdx;
	$res[1] = $strCut;

	if($isHeader)	$res[2] = (int)$chg_bin[1];

	//	echo "res: ".$res[1]."<br>";

	if($debug)	echo "nCnt: ".$nCnt."<br>";

	return $res;

}

function cutChgIdx($pData, $isHeader)
{
	$debug = false;

	$res = array();

	$nCnt = 0;

//	$bin = unpack('C*', $pData);
	$bin = bin2byte($pData);

	$nPos = 0;
	$nStartPos = array(1,1,1);
	//$nEndPos = sizeof($bin);
	$nEndPos = array(sizeof($bin),sizeof($bin)+1);

	if($isHeader)
	{
		$nPos = 18;
		$nStartPos = array(0,2,0);
		$nEndPos = array(sizeof($bin)-1,sizeof($bin)-1);

		if($debug)	printf("<br>nEndPos: %d<br>",$nEndPos);
	}


	$nChgIdx = (int)$bin[$nPos];

	if($debug)	echo "<br>[nChgIdx] ".$nChgIdx."<br>";

	$strCut = "";

	$chg_bin = array();

	$chg_bin = revrse_chg_Idx($bin,$nStartPos[0],$nStartPos[2],$nEndPos[0],($nChgIdx));

	for($i = $nStartPos[1]; $i < $nEndPos[1]; $i++)
	{
		$strCut.=chr($chg_bin[$i]);

		//echo "bin_$i: ".$bin[$i]."<br>";
		if($debug)		$nCnt++;
	}

	$res[0] = $nChgIdx;
	$res[1] = $strCut;

	if($isHeader)	$res[2] = (int)$chg_bin[1];

	//	echo "res: ".$res[1]."<br>";

	if($debug)	echo "nCnt: ".$nCnt."<br>";

	return $res;

}

function cutChgIdx_test($pData, $isHeader)
{
	$debug = true;

	$res = array();

	$nCnt = 0;

//	$bin = unpack('C*', $pData);
	$bin = bin2byte($pData);

	$nPos = 0;
	$nStartPos = array(1,1,1);
	//$nEndPos = sizeof($bin);
	$nEndPos = array(sizeof($bin),sizeof($bin)+1);

	if($isHeader)
	{
		$nPos = 18;
		$nStartPos = array(0,2,0);
		$nEndPos = array(sizeof($bin)-1,sizeof($bin)-1);

		if($debug)	printf("<br>nEndPos: %d<br>",$nEndPos);
	}


	$nChgIdx = (int)$bin[$nPos];

	if($debug)	echo "<br>[nChgIdx] ".$nChgIdx."<br>";

	$strCut = "";

	$chg_bin = array();

	$chg_bin = revrse_chg_Idx_test($bin,$nStartPos[0],$nStartPos[2],$nEndPos[0],($nChgIdx));

	for($i = $nStartPos[1]; $i < $nEndPos[1]; $i++)
	{
		$strCut.=chr($chg_bin[$i]);

		//echo "bin_$i: ".$bin[$i]."<br>";
		if($debug)		$nCnt++;
	}

	$res[0] = $nChgIdx;
	$res[1] = $strCut;

	if($isHeader)	$res[2] = (int)$chg_bin[1];

	//	echo "res: ".$res[1]."<br>";

	if($debug)	echo "nCnt: ".$nCnt."<br>";

	return $res;

}

function parseBody_test($pData)
{
	$debug = true;
	//echo "pData: ".$pData."<br>";

	//$pData = urldecode($pData);

	if($debug)	echo "pData: ".$pData."<br>";

	$body = array();

	$pData=base64_decode($pData);

	if($debug)	echo "pData: ".$pData."<br>";

	$body =	cutChgIdx_test($pData,false);

//	$body[1] = trim(urldecode($body[1]));
//	$body[1] = (urldecode($body[1]));
	$body[1] = $body[1];
	
	if($debug)	echo "Body: ".$body[1]."<br>";
/*	
	$json_parse = json_decode($body[1], true);

	 switch (json_last_error()) {
        case JSON_ERROR_NONE:
            echo ' - No errors';
        break;
        case JSON_ERROR_DEPTH:
            echo ' - Maximum stack depth exceeded';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Underflow or the modes mismatch';
        break;
        case JSON_ERROR_CTRL_CHAR:
            echo ' - Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            echo ' - Syntax error, malformed JSON';
        break;
        case JSON_ERROR_UTF8:
            echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
        default:
            echo ' - Unknown error';
        break;
    }

	var_dump($json_parse);
*/
	return json_decode(trim($body[1]), true);
}

function parseBody($pData)
{
	$debug = false;
	//echo "pData: ".$pData."<br>";

	//$pData = urldecode($pData);

	if($debug)	echo "pData: ".$pData."<br>";

	$body = array();

	$pData=base64_decode($pData);

	if($debug)	echo "pData: ".$pData."<br>";

	$body =	cutChgIdx($pData,false);

//	$body[1] = trim(urldecode($body[1]));
//	$body[1] = (urldecode($body[1]));
	$body[1] = $body[1];
	
	if($debug)	echo "Body: ".$body[1]."<br>";
/*	
	$json_parse = json_decode($body[1], true);

	 switch (json_last_error()) {
        case JSON_ERROR_NONE:
            echo ' - No errors';
        break;
        case JSON_ERROR_DEPTH:
            echo ' - Maximum stack depth exceeded';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Underflow or the modes mismatch';
        break;
        case JSON_ERROR_CTRL_CHAR:
            echo ' - Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            echo ' - Syntax error, malformed JSON';
        break;
        case JSON_ERROR_UTF8:
            echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
        default:
            echo ' - Unknown error';
        break;
    }

	var_dump($json_parse);
*/
	return json_decode(trim($body[1]), true);
}

function parseHeader($pData)
{
	$debug = false;

	if($debug)	echo "pData: ".$pData."<br>";

	//$pData = urldecode($pData);

	if($debug)	echo "pData: ".$pData."<br>";

	$header = array();

	$pData=base64_decode($pData);

	if($debug)	echo "pData: ".$pData."<br>";

	$header = cutChgIdx($pData,true);

	//echo "Body: ".$body[1]."<br>";

	return $header;
}

function echoResultJson($pRes)
{
	$res_array = array(array("RESULT" => $pRes));
	$res = json_encode($res_array);
	echo $res;
}

function echoResultJsonArray($pRes)
{
	$res_array = array();
	$res_array = array(array("RESULT" => $pRes,"ERR_CODE" => "0"));
	$res = json_encode($res_array);
	echo $res;
}

function echoFailJson()
{
	echoResultJson("F");
}

function echoFailJsonArray()
{
	echoResultJsonArray("F");
}

function fcm_notification ($tokens, $msg_type, $message, $mac_addr)
{
//	if($tokens != "eRbbQpQP290:APA91bEmMomoGEGq4r3R-0wfD-IbVJac76IBI77TfbfNBHqYb1HZslrXRo0kdp_kT2-ISpE3w-dWJEsabwaaeBgk7Vi-doUzYkEKPBMEgBeCGEyM8i5uQi6l58IHL_uVHS7YH1J2RpHU") return ""; 

	$msg = "!".$msg_type;

	if($msg_type == NOTI_WARN_WATERLV)			$msg .= $mac_addr.iconv("EUC-KR","UTF-8",$message." \n물이 부족합니다.\n물보충을 해주세요!");
	else if($msg_type == NOTI_HAVEST)			$msg .= iconv("EUC-KR","UTF-8","통신두절이 발생했습니다.\n점검을 해주세요!");
	else if($msg_type == NOTI_WARN_CERR)		$msg .= iconv("EUC-KR","UTF-8","수확할 때입니다.\n수확을 해주세요!");
	else if($msg_type == NOTI_EVENT)			$msg .= iconv("EUC-KR","UTF-8","".$message);
	else if($msg_type == NOTI_CHG_WORK_CONF)	$msg .= iconv("EUC-KR","UTF-8","".$message);
	else if($msg_type == NOTI_EMERGENCY)		$msg .= iconv("EUC-KR","UTF-8","".$message);
	else if($msg_type == NOTI_FULL_WATERLV)		$msg .= $mac_addr.iconv("EUC-KR","UTF-8","물이 꽉 찾습니다.\n물보충을 중단하세요!");
	else if($msg_type == NOTI_NOTFULL_WATERLV)	$msg .= iconv("EUC-KR","UTF-8","".$message);


//	echo $msg;

	$url = 'https://fcm.googleapis.com/fcm/send';

/*
	$fields = array( 'registration_ids' => "", 'data' => $msg );
	$fields[0] =$tokens;
*/

	$arr = array();
	$arr['data'] = array();
	$arr['data']['title'] = 'Lulupot';
	$arr['data']['message'] = $msg;
	$arr['registration_ids'] = array();
	$arr['registration_ids'][0] = $tokens;

	$headers = array(
		'Authorization:key = AAAA27rFkow:APA91bFVvaFjoYLmugmXde6RKJy-70WI41HtxdOjFSAZBm3NM_sNY_ykENGESK_OHijVivEVbfdusG43GEc0O0Mdgvq-BjEn23JaNLadKtzm5madEHouMU_Z9_YZOXgzzfwH_EO_oo2M',
		'Content-Type: application/json'
		);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr));
	$result = curl_exec($ch);           

	if ($result === FALSE) 
	{
	   die('Curl failed: ' . curl_error($ch));
	}
	curl_close($ch);

	return $result;
}

/*
echo $d1."<br>";



function chg_Idx($pData, $nStartPos, $nEndPos, $nChgIdx)
{
	$nSize = sizeof($pData);

	$strPreChgHeader = array();

	for($i = 0; $i <= $nSize; $i++)
	{
		if($i >= $nStartPos && $i < $nEndPos)
        {
			$strPreChgHeader[$i] = $pData[$nChgIdx];

			if($nChgIdx < ($nEndPos-1))	    $nChgIdx++;
			else				            $nChgIdx = $nStartPos;
		}
		else
		{
			$strPreChgHeader[$i] = $pData[$i];
		}
		
	}

	return $strPreChgHeader;
}
*/

function getFileTimeStamp($dir,$fname)
{
	$res = "";

	if($fname != null && strlen($fname) > 0)
	{
		$filename = $dir.$fname;

		$res_array = array();

		if (file_exists($filename)) 
		{
			$res = filemtime($filename);
		}
	}

	return $res;
}

function getFileTimeDate($dir,$fname)
{
//	date_default_timezone_set('Asia/Seoul');

	$get_flist = preg_grep('~^'.$fname.'.*\.(jpg)$~', scandir($dir,SCANDIR_SORT_DESCENDING));

	$fname = reset($get_flist);

	$res = "";

	if($fname != null && strlen($fname) > 0)
	{
		$filename = $dir.$fname;

		$res_array = array();

		if (file_exists($filename)) 
		{
			$res = filemtime($filename);
			$res = date("Y-m-d H:i", $res);

			$c_date = date("Y-m-d", time());

			if($c_date == substr($res,0,10))	$res = "<b>".$res."</b>";
		}
	}

	return $res;
}

function GetPMLevel($nValue, $nType)
{
	$nRes = 0;

	if($nType == 0) // pm10
	{
		if($nValue <  KOR_PM10_LV1)           $nRes = 0;
		else if($nValue <  KOR_PM10_LV2)      $nRes = 1;
		else if($nValue <  KOR_PM10_LV3)      $nRes = 2;
		else                                  $nRes = 3;
	}
	else if($nType == 1) // pm2_5
	{
		if($nValue <  KOR_PM2_5_LV1)          $nRes = 0;
		else if($nValue <  KOR_PM2_5_LV2)     $nRes = 1;
		else if($nValue <  KOR_PM2_5_LV3)     $nRes = 2;
		else                                  $nRes = 3;
	}

	return $nRes;
}

?>