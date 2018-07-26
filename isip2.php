<?php
	
	header('Content-type: text/plain; charset=utf8');
	
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
		if (filter_var($_GET['ip'],FILTER_VALIDATE_IP))
		{
			$ans=" correct ip address. [".fulladdr($_GET['ip'])."]";
		}
		else
		{
			$ans=" incorrect ip address.";
		}
		
		echo $_GET['ip'].$ans."\n";		
	}
	else
	{
		echo "IsIP (filter_var) test script\n";
		echo "Use:\n";
		echo "isip2.php?ip=<ip-address>\n";
	}
?>