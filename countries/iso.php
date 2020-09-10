<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$errmsg = "";
$data = array();
$field = "ISOA2";
$direction = 0;

function print_top() //печатает заголовок и верх страницы
{
	echo "<html>";
	echo "<head>";
	echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
	echo "<style type='text/css'>
			TABLE {
			width: 300px; /* Ширина таблицы */
			border-collapse: collapse; /* Убираем двойные линии между ячейками */
			}
			TD, TH {
			padding: 3px; /* Поля вокруг содержимого таблицы */
			border: 1px solid gold; /* Параметры рамки */
			font: 12pt/10pt monospace;
			}
			#footer {
			position: fixed; /* Фиксированное положение */
			left: 0; bottom: 0; /* Левый нижний угол */
			padding: 10px; /* Поля вокруг текста */	
			width: 100%; /* Ширина слоя */
			}
			</style>";
	echo "<title>Коды ISO и флаги стран</title>";
	echo "</head>";
	echo "<body bgcolor='black' text='silver'>";
	echo "<center><h2>Коды ISO и флаги стран</h2></center>"."\n";
	echo "<table align='center'>";
	echo "<tr><td colspan='12'><center><b>Коды и флаги стран</b></center></td></tr>"."\n";
	echo "<tr><th>Флаг</th><th>Код страны (ISO, Aplha-2)</th>
		  <th>Код страны (ISO, Aplha-3)</th><th>Код страны (ISO, числовой)</th>
		  <th>Название (английский)</th><th>Название (русский)</th></tr>";
}

function print_bottom() //печатает низ страницы
{
	global $errmsg;
	
	echo "</table>";
	echo "<div id='footer'>";
	if ($errmsg!="")
	{
		echo "<font color = 'red'>$errmsg</font><br>";
	}
	echo "(L) 2020";
	echo "</div>";
	echo "</body>";
	echo "</html>";
}

function load_data()
{	
	global $data;
	global $errmsg;
	
	$handle = fopen("isofull.csv","r");	
	if (!$handle)
	{
		$errmsg = "Can't open file isofull.csv";
		return;
	}
	
	$databuf = array();	
	while (!feof($handle))
	{
		$readbuf = fgets($handle);		
		$databuf = explode(";", $readbuf);
		
		$data[] = array ("ISOA2" => $databuf[0],"ISOA3" => $databuf[1],	"ISON" => $databuf[2],
				  "EN" => $databuf[3], "RU" => $databuf[4]);
	}	
	return true;
}

function print_table()
{
	global $data;	
	$tabstr="";
	
	foreach ($data as $item)
	{
		$iso_code = $item["ISOA2"];
		$tabstr = "<tr><td><img src='flags/$iso_code.png' alt='$iso_code' title='$iso_code'></td>";
		
		foreach ($item as $key => $value)
		{
			$tabstr = $tabstr."<td>$value</td>";
		}
		
		$tabstr = $tabstr."</tr>";
		echo $tabstr;
	}
}

function print_sortform ()
{
	global $field;
	global $direction;
	
	$fields=array("ISOA2","ISOA3","ISON","EN","RU");
	$sortmess=array("Aplha-2","Alpha-3","Номер","Английский","Русский");
	
	echo "<tr><td colspan='12'><center><b>"."\n";
	echo "<form name='sort' method='get' action='iso.php'> Сортировать по:";
	$i=0;
	foreach ($fields as $f)
	{
		if ($field == $f)
		{
			echo "<nobr><input name='sort' type='radio' value='$f' checked> $sortmess[$i]</nobr> ";
		}
		else
		{
			echo "<nobr><input name='sort' type='radio' value='$f'> $sortmess[$i] </nobr>";
		}
		$i++;
	}
	echo "<br> Направление:";
	if ($direction == 0)
	{
		echo "<nobr><input name='direction' type='radio' value='0' checked> По возрастанию</nobr> ";
		echo "<nobr><input name='direction' type='radio' value='1'> По убыванию</nobr> ";
	}
	else
	{
		echo "<nobr><input name='direction' type='radio' value='0'> По возрастанию</nobr> ";
		echo "<nobr><input name='direction' type='radio' value='1' checked> По убыванию</nobr> ";
	}
	echo "<br> <input type='submit' value='Отправить'>"; 
	echo "</form></b></center></td></tr>";
}

function compare ($a, $b)
{	
	global $field;
	global $direction;
	global $errmsg;
	
	if (!array_key_exists($field, $a))
	{
		$errmsg = "Field $field not found";
		return 0;
	}
	
	if ($direction == 0)
	{
		return strnatcmp($a[$field],$b[$field]);
	}
	else
	{
		return (strnatcmp($a[$field],$b[$field])*-1);
	}
}

// --- Конец области функций ---

if (isset($_GET['sort']))
{
	$field=$_GET['sort'];
}
if (isset($_GET['direction']))
{
	$direction=$_GET['direction'];
}

print_top();
print_sortform();
load_data();
if ($errmsg=="") //продолжаем делать
{
	usort ($data,"compare");	
	if ($errmsg=="")
	{
		print_table();
	}
}
print_bottom();
?>