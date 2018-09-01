<?php
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	
	header('Content-type: text/plain; charset=utf8');
		
	$spec_list = array(										
					array ("0.0.0.0","0.255.255.255", "Current network"),
					array ("255.255.255.255","255.255.255.255", "Broadcast"),
					array ("255.0.0.0","255.255.255.255", "Reserved by the IETF, broadcast"),
					array ("10.0.0.0","10.255.255.255", "Private network"),
					array ("100.64.0.0","100.127.255.255", "Shared Address Space"),
					array ("127.0.0.0","127.255.255.255", "Loopback"),
					array ("169.254.0.0","169.254.255.255", "Link-local"),
					array ("172.16.0.0","172.31.255.255", "Private network"),
					array ("192.0.0.0","192.0.0.7", "DS-Lite"),
					array ("192.0.0.170","192.0.0.170", "NAT64"),
					array ("192.0.0.171","192.0.0.171", "DNS64"),
					array ("192.0.2.0","192.0.2.255", "Documentation example"),
					array ("192.0.0.0","192.0.0.255", "Reserved by the IETF"),
					array ("192.88.99.1","192.88.99.1", "IPv6 to IPv4 Incapsulation"),
					array ("192.88.99.0","192.88.99.255", "Anycast"),
					array ("192.168.0.0","192.168.255.255", "Private network"),
					array ("198.51.100.0","198.51.100.255", "Documentation example"),
					array ("198.18.0.0","198.19.255.255", "Test IP"),
					array ("203.0.113.0","203.0.113.255", "Documentation example"),
					array ("224.0.0.0","224.255.255.255", "Multicast"),
					array ("240.0.0.0","240.255.255.255", "Future reserved")					
					);

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
	
	function chkdiapip ($user_ip, $ip_from, $ip_to) //попадает ли ip в нужный диапазон
	{
		return ( ip2long($user_ip)>=ip2long($ip_from) && ip2long($user_ip)<=ip2long($ip_to) );
	}
	
	function get_spec_diap ($user_ip, $listspec)
	{
		for ($i=0;$i<sizeof($listspec);$i++)
		{
			$item = $listspec[$i];
			if (chkdiapip($user_ip, $item[0], $item[1]))
			{
				return $item[0]."\t".$item[1]."\t".$item[2]."\t\n";
			}
		}
		
		return -1;
	}
	
	function printspecdiap ($listspec)
	{
		for ($i=0;$i<sizeof($listspec);$i++)
		{
			$item = $listspec[$i];
			echo $item[0]."\t\t".$item[1]."\t\t\t".$item[2], "\t\t\t\n";
		}
	}
	
	//---------------------------------------------------------------------------
	if (isset($_GET['ip']))
	{
		$ip = $_GET['ip'];
		if (!isip($ip))
		{
			echo "No correct IP address";
			die();
		}
		$retspec = get_spec_diap ($ip, $spec_list);
		$fa = fulladdr($ip);
		if ($ip!=$fa)
		{
			$ip = $fa." [".$ip."]";
		}
		if ($retspec == -1)
		{
			echo "IP ".$ip." not in special diapason.";
		}
		else
		{
			echo "IP ".$ip." ".$retspec;
		}
	}
	else
	{
		echo "Test script for detecting IPv4 special diapasons\n";
		echo "Use specdiap.php?ip=<ip address>\n\n";
		
		echo "Diapasons list:\n";
		printspecdiap($spec_list);
	}

?>