<?php
	
	header('Content-type: text/plain; charset=utf8');
	function IsIP($ip)
	{
		//преобразуем в нижний регистр, на случай шестнадцатиричных чисел
		$ip=strtoupper($ip);
		//ip2long в некоторых версиях php 
		//некорректно реагирует на адрес 255.255.255.255
		//делаем небольшую заглушку
		if ($ip == '255.255.255.255'||$ip == '0xff.0xff.0xff.0xff'||
		   $ip == '0377.0377.0377.0377')
		   {
			   return true;
		   }
		
		$tolong=ip2long($ip);
		
		if ($tolong == -1||$tolong===FALSE) return FALSE;
		else return TRUE;
		
	}
	function fulladdr($ip)
	{
		//преобразует неполные адреса в полные
		//для информации
		$tolong=ip2long($ip);
		return long2ip ($tolong);
	}
	
	if (isset($_GET['ip']))
	{
		$ans="";
		if (IsIP($_GET['ip']))
		{
			$ans=" correct ip address. [".fulladdr($_GET['ip'])."]";
		}
		else
		{
			$ans=" incorrect ip address.";
		}
		
		echo $_GET['ip'].$ans."\n";
		echo "Long value: ".ip2long($_GET['ip']);
	}
	else
	{
		echo "IsIP test script\n";
		echo "Use:\n";
		echo "isip.php?ip=<ip-address>\n";
	}
?>