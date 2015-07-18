<?php

/**
 * 验证函数
 * */


/**
 * validateIDCard
   验证身份证号码的有效性
 *
 */	
//验证身份证是否有效
function validateIDCard($IDCard) {
	$regIdCard = "/^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/";
	if(preg_match($regIdCard, $IDCard))
	{
		if (strlen($IDCard) == 18) {
        	return check18IDCard($IDCard);
    	} elseif ((strlen($IDCard) == 15)) {
        	$IDCard = convertIDCard15to18($IDCard);
        	return check18IDCard($IDCard);
    	}
	} else {
        return false;
    } 
}

//计算身份证的最后一位验证码,根据国家标准GB 11643-1999
function calcIDCardCode($IDCardBody) {
    if (strlen($IDCardBody) != 17) {
        return false;
    }

    //加权因子 
    $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
    //校验码对应值 
    $code = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
    $checksum = 0;

    for ($i = 0; $i < strlen($IDCardBody); $i++) {
        $checksum += substr($IDCardBody, $i, 1) * $factor[$i];
    }

    return $code[$checksum % 11];
}

// 将15位身份证升级到18位 
function convertIDCard15to18($IDCard) {
    if (strlen($IDCard) != 15) {
        return false;
    } else {
        // 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码 
        if (array_search(substr($IDCard, 12, 3), array('996', '997', '998', '999')) !== false) {
            $IDCard = substr($IDCard, 0, 6) . '18' . substr($IDCard, 6, 9);
        } else {
            $IDCard = substr($IDCard, 0, 6) . '19' . substr($IDCard, 6, 9);
        }
    }
    $IDCard = $IDCard . calcIDCardCode($IDCard);
    return $IDCard;
}

// 18位身份证校验码有效性检查 
function check18IDCard($IDCard) {
    if (strlen($IDCard) != 18) {
        return false;
    }

    $IDCardBody = substr($IDCard, 0, 17); //身份证主体
    $IDCardCode = strtoupper(substr($IDCard, 17, 1)); //身份证最后一位的验证码

    if (calcIDCardCode($IDCardBody) != $IDCardCode) {
        return false;
    } else {
        return true;
    }
}

function validatePospart($number)
{
	$regNumber = "/^[a-zA-Z]\d{8}$/";
	if(preg_match($regNumber, $number))
	{
		return true;
	}else{
		return false;
	}

}
/**
   验证邮箱是否正确
 */
function validateEmail($email)
{
	$REG_Email = '/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/';
	if(preg_match ( $REG_Email, $email ) )
	{
		return ture;
	}else{
		return false;
	}
}
/**
   验证手机号码是否正确
 */
function validatePhone($phone)
{
    $REG_PHONE = '/13[123569]{1}\d{8}|15[1235689]\d{8}|188\d{8}/';
    if(preg_match ( $REG_PHONE, $phone ) )
    {
        return ture;
    }else{
        return false;
    }
}
/**
   验证字符长度是否符合规则
 */
function validateLength($str, $l=NULL, $r=NULL, $type='UTF-8')
{
    $length = iconv_strlen($str,$type);
    
    if(isset($l) && $length < $l )
        return false;
    if(isset($r) && $length > $r)
        return false;

    return true;
}
/**
   检测日期格式是否正确
 */
function validateDatetime($str, $format="Y-m-d H:i:s"){  
    
    $now_time = strtotime("now");
    $unixTime=strtotime($str);  
    if($unixTime > $now_time)
        return 0;
    $checkDate= date($format, $unixTime);
    if($checkDate==$str)  
        return 1;  
    else  
        return 0;  
}  
?>