<?php

if(!defined('INCLUDE_CHECK')) die('You are not allowed to execute this file directly');

$globalsalt = '$%hl25d-k1tgbG$bh^';

function checkEmail($str)
{
	return preg_match("/^[\.A-z0-9_\-\+]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z]{1,4}$/", $str);
}


function send_mail($from,$to,$subject,$body)
{
	$headers = '';
	$headers .= "From: $from\n";
	$headers .= "Reply-to: $from\n";
	$headers .= "Return-Path: $from\n";
	$headers .= "Message-ID: <" . md5(uniqid(time())) . "@" . $_SERVER['SERVER_NAME'] . ">\n";
	$headers .= "MIME-Version: 1.0\n";
	$headers .= "Date: " . date('r', time()) . "\n";

	mail($to,$subject,$body,$headers);
}

function getRandom ($type = 'sha1', $len = 40)
{
        if (phpversion() >= 4.2) mt_srand();
        else
            mt_srand(hexdec(substr(md5(microtime()), - $len)) & 0x7fffffff);

    switch ($type) {
        case 'basic':
            return mt_rand();
            break;
        case 'alpha':
        case 'numeric':
        case 'nozero':
            switch ($type) {
                case 'alpha':
                    $param = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    break;
                case 'numeric':
                    $param = '0123456789';
                    break;
                case 'nozero':
                    $param = '123456789';
                    break;
            }
            $str = '';
            for ($i = 0; $i < $len; $i ++) {
                $str .= substr($param, mt_rand(0, strlen($param) - 1), 1);
            }
            return $str;
            break;
        case 'md5':
            return md5(uniqid(mt_rand(), TRUE));
            break;
        case 'sha1':
            return sha1(uniqid(mt_rand(), TRUE));
            break;
    }
}

function getSHA()
{
	return getRandom('sha1',40);
}

function getPassword($username, $password, $salt)
{
	return sha1($globalsalt.$password.$username.$salt);
}

function getUserSalt($userid)
{
	$salt = "";
	if ($userid)
	{
		$saltOut = mysql_query("SELECT salt FROM users WHERE 
				userid=".$userid."
				");
		
		$saltrow = mysql_fetch_assoc($saltOut);
		
		if ($saltrow['salt'])
		{
			$salt = $saltrow['salt'];
		}
		
		mysql_free_result($saltOut);
	}
	return $salt;

}
?>