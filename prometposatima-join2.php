<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Storno racuni</title>
<style type="text/css">
body {
	background: #E3F4FC;
	font: normal 14px/20px Helvetica, Arial, sans-serif;
	color: #2b2b2b;
}
a {
	color:#898989;
	font-size:12px;
	font-weight:bold;
	text-decoration:none;
}
a:hover {
	color:#CC0033;
}

h1 {
	font: bold 12px Helvetica, Arial, sans-serif;
	color: #CC0033;
}
h2 {
	font: bold 10px Helvetica, Arial, sans-serif;
	color: #898989;
}
#container {
	background: #CCC;
	margin: 10px auto;
	width: 1366px;
}
#form 			{padding: 20px 10px;}
#form input     {margin-bottom: 20px;}
</style>

<script type="text/javascript"> 
  function tabE(obj,e){ 
   var e=(typeof event!='undefined')?window.event:e;// IE : Moz 
   if(e.keyCode==13){ 
     var ele = document.forms[0].elements; 
     for(var i=0;i<ele.length;i++){ 
       var q=(i==ele.length-1)?0:i+1;// if last element : if any other 
       if(obj==ele[i]){ele[q].focus();break} 
     } 
  return false; 
   } 
  } 
</script> 

</head>
<body>

<?php

echo "<b>Program za prikaz prometa po satima</b>";
echo "\n";
echo "\t";
print '<br />';
echo "\n";
echo 'Izvestaj 3 - Pregled prometa po satima za sve mpo';

?>

<div id="container">
<div id="form">

<!-- p align="left"><a href="/storno/stornosccode.php"><button>Prikazi listu storno stavki order by sccode...</button></a></p -->

<p align="right"><a href="/storno/index.php"><button>Vrati se na prvu stranicu...</button></a></p>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?> ">	
Datum od: &nbsp <input type="date" name="datumod" onkeypress="return tabE(this,event)" />
<!-- Datum do: &nbsp <input type="date" name="datumdo" onkeypress="return tabE(this,event)" /-->
<br />
<input type="submit" name="prikazi" value="Prikazi prodaju po satima u svim mpo slozeno po sccode..." />
<!--/form-->	

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?> ">
<br />
		E-mail: <input type="email" name="nemail" />
		<input type="submit" name="preuzmi" value="Preuzmi fajl" />
</form>
	
<?php
include 'settings.php';
include 'class.phpmailer.php';
include 'class.smtp.php'; // optional, gets called from within class.phpmailer.php if not already loaded
include('Net/SFTP.php');

if(!file_exists('/dbf/proveradir/proverafile.txt')){

//konekcija na bazu
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

if (isset($_POST['prikazi'])) {

if (($_POST['datumod']) == '' ) {
	echo 'Potrebno je da odaberete datum!';
	}else{
	
	$load = "select * from ";
	
	$load .= "(select sccode as t1sccode, SUM(racuniznos) as sumracuna1, vreme from tglava where vreme >= '".$_POST['datumod']. " 01:00:00' and vreme < '".$_POST['datumod']. " 23:50:00' ";
	$load .= "GROUP BY t1sccode order by t1sccode asc) t1 ";
	
	/*$load .= " LEFT JOIN ";
	$load .= "(select sccode as t5sccode, SUM(racuniznos) as sumracuna5, vreme from tglava where vreme >= '".$_POST['datumod']. " 05:00:00' and vreme < '".$_POST['datumod']. " 06:00:00' ";
	$load .= "GROUP BY t5sccode order by t5sccode asc) t5 ON t1.t1sccode = t5.t5sccode ";
	
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t6sccode, SUM(racuniznos) as sumracuna6 from tglava where vreme >= '".$_POST['datumod']. " 06:00:00' and vreme < '".$_POST['datumod']. " 07:00:00' ";
	$load .= "GROUP BY t6sccode order by t6sccode asc) t6 ON t5.t5sccode = t6.t6sccode ";
	*/$load .= " LEFT JOIN ";
	$load .= "(select sccode as t7sccode, SUM(racuniznos) as sumracuna7 from tglava where vreme >= '".$_POST['datumod']. " 07:00:00' and vreme < '".$_POST['datumod']. " 08:00:00' ";
	$load .= "GROUP BY t7sccode order by t7sccode asc) t7 ON t1.t1sccode = t7.t7sccode ";
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t8sccode, SUM(racuniznos) as sumracuna8 from tglava where vreme >= '".$_POST['datumod']. " 08:00:00' and vreme < '".$_POST['datumod']. " 09:00:00' ";
	$load .= "GROUP BY t8sccode order by t8sccode asc) t8 ON t7.t7sccode = t8.t8sccode ";
	
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t9sccode, SUM(racuniznos) as sumracuna9 from tglava where vreme >= '".$_POST['datumod']. " 09:00:00' and vreme < '".$_POST['datumod']. " 10:00:00' ";
	$load .= "GROUP BY t9sccode order by t9sccode asc) t9 ON t8.t8sccode = t9.t9sccode ";
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t10sccode, SUM(racuniznos) as sumracuna10 from tglava where vreme >= '".$_POST['datumod']. " 10:00:00' and vreme < '".$_POST['datumod']. " 11:00:00' ";
	$load .= "GROUP BY t10sccode order by t10sccode asc) t10 ON t9.t9sccode = t10.t10sccode ";
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t11sccode, SUM(racuniznos) as sumracuna11 from tglava where vreme >= '".$_POST['datumod']. " 11:00:00' and vreme < '".$_POST['datumod']. " 12:00:00' ";
	$load .= "GROUP BY t11sccode order by t11sccode asc) t11 ON t10.t10sccode = t11.t11sccode ";
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t12sccode, SUM(racuniznos) as sumracuna12 from tglava where vreme >= '".$_POST['datumod']. " 12:00:00' and vreme < '".$_POST['datumod']. " 13:00:00' ";
	$load .= "GROUP BY t12sccode order by t12sccode asc) t12 ON t11.t11sccode = t12.t12sccode ";
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t13sccode, SUM(racuniznos) as sumracuna13 from tglava where vreme >= '".$_POST['datumod']. " 13:00:00' and vreme < '".$_POST['datumod']. " 14:00:00' ";
	$load .= "GROUP BY t13sccode order by t13sccode asc) t13 ON t12.t12sccode = t13.t13sccode ";
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t14sccode, SUM(racuniznos) as sumracuna14 from tglava where vreme >= '".$_POST['datumod']. " 14:00:00' and vreme < '".$_POST['datumod']. " 15:00:00' ";
	$load .= "GROUP BY t14sccode order by t14sccode asc) t14 ON t13.t13sccode = t14.t14sccode ";
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t15sccode, SUM(racuniznos) as sumracuna15 from tglava where vreme >= '".$_POST['datumod']. " 15:00:00' and vreme < '".$_POST['datumod']. " 16:00:00' ";
	$load .= "GROUP BY t15sccode order by t15sccode asc) t15 ON t14.t14sccode = t15.t15sccode ";
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t16sccode, SUM(racuniznos) as sumracuna16 from tglava where vreme >= '".$_POST['datumod']. " 16:00:00' and vreme < '".$_POST['datumod']. " 17:00:00' ";
	$load .= "GROUP BY t16sccode order by t16sccode asc) t16 ON t15.t15sccode = t16.t16sccode ";
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t17sccode, SUM(racuniznos) as sumracuna17 from tglava where vreme >= '".$_POST['datumod']. " 17:00:00' and vreme < '".$_POST['datumod']. " 18:00:00' ";
	$load .= "GROUP BY t17sccode order by t17sccode asc) t17 ON t16.t16sccode = t17.t17sccode ";
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t18sccode, SUM(racuniznos) as sumracuna18 from tglava where vreme >= '".$_POST['datumod']. " 18:00:00' and vreme < '".$_POST['datumod']. " 19:00:00' ";
	$load .= "GROUP BY t18sccode order by t18sccode asc) t18 ON t17.t17sccode = t18.t18sccode ";
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t19sccode, SUM(racuniznos) as sumracuna19 from tglava where vreme >= '".$_POST['datumod']. " 19:00:00' and vreme < '".$_POST['datumod']. " 20:00:00' ";
	$load .= "GROUP BY t19sccode order by t19sccode asc) t19 ON t18.t18sccode = t19.t19sccode ";
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t20sccode, SUM(racuniznos) as sumracuna20 from tglava where vreme >= '".$_POST['datumod']. " 20:00:00' and vreme < '".$_POST['datumod']. " 21:00:00' ";
	$load .= "GROUP BY t20sccode order by t20sccode asc) t20 ON t19.t19sccode = t20.t20sccode ";
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t21sccode, SUM(racuniznos) as sumracuna21 from tglava where vreme >= '".$_POST['datumod']. " 21:00:00' and vreme < '".$_POST['datumod']. " 22:00:00' ";
	$load .= "GROUP BY t21sccode order by t21sccode asc) t21 ON t20.t20sccode = t21.t21sccode ";
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t22sccode, SUM(racuniznos) as sumracuna22 from tglava where vreme >= '".$_POST['datumod']. " 22:00:00' and vreme < '".$_POST['datumod']. " 23:00:00' ";
	$load .= "GROUP BY t22sccode order by t22sccode asc) t22 ON t21.t21sccode = t22.t22sccode ";
	
	$load .= " GROUP BY t1.t1sccode ORDER BY t1.t1sccode asc ";
	
	
	
	$res = $mysqli->query($load);	
			/*echo "Izvrsava se sledeci upit 1:";
			echo '<br />';
			echo $load;
			echo '<br />';
			*/
		
	echo '<br />';		
	echo "Prikazani podaci za period od : '".$_POST['datumod']."' ";
	
	
	echo '<table border="1"; table-layout: fixed; width="1024">';
	echo "Upit 1";
	echo '<tr bgcolor = #31B404 ><th><font size="2">Datum </font></th><th><font size="2">SC Code</font></th><th><font size="2">Dn prod</font></th><th><font size="2">5-6</font></th><th><font size="2">6-7</font></th><th><font size="2">7-8</font></th><th><font size="2">8-9</font></th><th><font size="2">9-10</font></th><th><font size="2">10-11</font></th><th><font size="2">11-12</font></th><th><font size="2">12-13</font></th><th><font size="2">13-14</font></th><th><font size="2">14-15</font></th><th><font size="2">15-16</font></th><th><font size="2">16-17</font></th><th><font size="2">17-18</font></th><th><font size="2">18-19</font></th><th><font size="2">19-20</font></th><th><font size="2">20-21</font></th><th><font size="2">21-22</font></th><th><font size="2">22-23</font></th></tr>';
	echo '<br />';		
	
	while($rec = $res->fetch_assoc()){
			echo '<tr>';
				echo '<td width="12%">';
				echo '<font size="2">';
				echo "'".$_POST['datumod']."' ";
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="3%">';
				echo '<font size="2">';
				echo $rec['t1sccode'];
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="5%">';
				echo '<font size="2">';
				echo number_format($rec['sumracuna1'], 2);
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="5%">';
				echo '<font size="2">';
				echo number_format($rec['sumracuna5'], 2);
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="5%">';
				echo '<font size="2">';
				echo number_format($rec['sumracuna6'], 2);
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="5%">';
				echo '<font size="2">';
				echo number_format($rec['sumracuna7'], 2);
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="5%">';
				echo '<font size="2">';
				echo number_format($rec['sumracuna8'], 2);
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="5%">';
				echo '<font size="2">';
				echo number_format($rec['sumracuna9'], 2);
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="5%">';
				echo '<font size="2">';
				echo number_format($rec['sumracuna10'], 2);
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="5%">';
				echo '<font size="2">';
				echo number_format($rec['sumracuna11'], 2);
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="5%">';
				echo '<font size="2">';
				echo number_format($rec['sumracuna12'], 2);
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="5%">';
				echo '<font size="2">';
				echo number_format($rec['sumracuna13'], 2);
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="5%">';
				echo '<font size="2">';
				echo number_format($rec['sumracuna14'], 2);
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="5%">';
				echo '<font size="2">';
				echo number_format($rec['sumracuna15'], 2);
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="5%">';
				echo '<font size="2">';
				echo number_format($rec['sumracuna16'], 2);
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="5%">';
				echo '<font size="2">';
				echo number_format($rec['sumracuna17'], 2);
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="5%">';
				echo '<font size="2">';
				echo number_format($rec['sumracuna18'], 2);
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="5%">';
				echo '<font size="2">';
				echo number_format($rec['sumracuna19'], 2);
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="5%">';
				echo '<font size="2">';
				echo number_format($rec['sumracuna20'], 2);
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="5%">';
				echo '<font size="2">';
				echo number_format($rec['sumracuna21'], 2);
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="5%">';
				echo '<font size="2">';
				echo number_format($rec['sumracuna22'], 2);
				echo '</font>';
				echo '</td>';
			echo '</tr>';
	}		
	
		
	echo '</table>';
			/*
			echo '<br />';
			echo "Izvrsen je sledeci upit:";
			echo '<br />';
			echo $load;
			echo '<br />';
			*/
	}
}
//slanje fajla email-om

if (isset($_POST['preuzmi'])) {
	if (!isset($_POST['nemail'])) {
		echo "Morate uneti E-mail adresu za prijem fajla!";
	}else{
					
					
					$load = "select * from ";
	
	$load .= "(select sccode as t1sccode, SUM(racuniznos) as sumracuna1, vreme from tglava where vreme >= '".$_POST['datumod']. " 01:00:00' and vreme < '".$_POST['datumod']. " 23:50:00' ";
	$load .= "GROUP BY t1sccode order by t1sccode asc) t1 ";
	
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t5sccode, SUM(racuniznos) as sumracuna5 from tglava where vreme >= '".$_POST['datumod']. " 05:00:00' and vreme < '".$_POST['datumod']. " 06:00:00' ";
	$load .= "GROUP BY t5sccode order by t5sccode asc) t5 ON t1.t1sccode = t5.t5sccode ";
	
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t6sccode, SUM(racuniznos) as sumracuna6 from tglava where vreme >= '".$_POST['datumod']. " 06:00:00' and vreme < '".$_POST['datumod']. " 07:00:00' ";
	$load .= "GROUP BY t6sccode order by t6sccode asc) t6 ON t5.t5sccode = t6.t6sccode ";
	
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t7sccode, SUM(racuniznos) as sumracuna7 from tglava where vreme >= '".$_POST['datumod']. " 07:00:00' and vreme < '".$_POST['datumod']. " 08:00:00' ";
	$load .= "GROUP BY t7sccode order by t7sccode asc) t7 ON t1.t1sccode = t7.t7sccode ";
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t8sccode, SUM(racuniznos) as sumracuna8 from tglava where vreme >= '".$_POST['datumod']. " 08:00:00' and vreme < '".$_POST['datumod']. " 09:00:00' ";
	$load .= "GROUP BY t8sccode order by t8sccode asc) t8 ON t7.t7sccode = t8.t8sccode ";
	
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t9sccode, SUM(racuniznos) as sumracuna9 from tglava where vreme >= '".$_POST['datumod']. " 09:00:00' and vreme < '".$_POST['datumod']. " 10:00:00' ";
	$load .= "GROUP BY t9sccode order by t9sccode asc) t9 ON t8.t8sccode = t9.t9sccode ";
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t10sccode, SUM(racuniznos) as sumracuna10 from tglava where vreme >= '".$_POST['datumod']. " 10:00:00' and vreme < '".$_POST['datumod']. " 11:00:00' ";
	$load .= "GROUP BY t10sccode order by t10sccode asc) t10 ON t9.t9sccode = t10.t10sccode ";
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t11sccode, SUM(racuniznos) as sumracuna11 from tglava where vreme >= '".$_POST['datumod']. " 11:00:00' and vreme < '".$_POST['datumod']. " 12:00:00' ";
	$load .= "GROUP BY t11sccode order by t11sccode asc) t11 ON t10.t10sccode = t11.t11sccode ";
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t12sccode, SUM(racuniznos) as sumracuna12 from tglava where vreme >= '".$_POST['datumod']. " 12:00:00' and vreme < '".$_POST['datumod']. " 13:00:00' ";
	$load .= "GROUP BY t12sccode order by t12sccode asc) t12 ON t11.t11sccode = t12.t12sccode ";
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t13sccode, SUM(racuniznos) as sumracuna13 from tglava where vreme >= '".$_POST['datumod']. " 13:00:00' and vreme < '".$_POST['datumod']. " 14:00:00' ";
	$load .= "GROUP BY t13sccode order by t13sccode asc) t13 ON t12.t12sccode = t13.t13sccode ";
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t14sccode, SUM(racuniznos) as sumracuna14 from tglava where vreme >= '".$_POST['datumod']. " 14:00:00' and vreme < '".$_POST['datumod']. " 15:00:00' ";
	$load .= "GROUP BY t14sccode order by t14sccode asc) t14 ON t13.t13sccode = t14.t14sccode ";
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t15sccode, SUM(racuniznos) as sumracuna15 from tglava where vreme >= '".$_POST['datumod']. " 15:00:00' and vreme < '".$_POST['datumod']. " 16:00:00' ";
	$load .= "GROUP BY t15sccode order by t15sccode asc) t15 ON t14.t14sccode = t15.t15sccode ";
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t16sccode, SUM(racuniznos) as sumracuna16 from tglava where vreme >= '".$_POST['datumod']. " 16:00:00' and vreme < '".$_POST['datumod']. " 17:00:00' ";
	$load .= "GROUP BY t16sccode order by t16sccode asc) t16 ON t15.t15sccode = t16.t16sccode ";
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t17sccode, SUM(racuniznos) as sumracuna17 from tglava where vreme >= '".$_POST['datumod']. " 17:00:00' and vreme < '".$_POST['datumod']. " 18:00:00' ";
	$load .= "GROUP BY t17sccode order by t17sccode asc) t17 ON t16.t16sccode = t17.t17sccode ";
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t18sccode, SUM(racuniznos) as sumracuna18 from tglava where vreme >= '".$_POST['datumod']. " 18:00:00' and vreme < '".$_POST['datumod']. " 19:00:00' ";
	$load .= "GROUP BY t18sccode order by t18sccode asc) t18 ON t17.t17sccode = t18.t18sccode ";
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t19sccode, SUM(racuniznos) as sumracuna19 from tglava where vreme >= '".$_POST['datumod']. " 19:00:00' and vreme < '".$_POST['datumod']. " 20:00:00' ";
	$load .= "GROUP BY t19sccode order by t19sccode asc) t19 ON t18.t18sccode = t19.t19sccode ";
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t20sccode, SUM(racuniznos) as sumracuna20 from tglava where vreme >= '".$_POST['datumod']. " 20:00:00' and vreme < '".$_POST['datumod']. " 21:00:00' ";
	$load .= "GROUP BY t20sccode order by t20sccode asc) t20 ON t19.t19sccode = t20.t20sccode ";
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t21sccode, SUM(racuniznos) as sumracuna21 from tglava where vreme >= '".$_POST['datumod']. " 21:00:00' and vreme < '".$_POST['datumod']. " 22:00:00' ";
	$load .= "GROUP BY t21sccode order by t21sccode asc) t21 ON t20.t20sccode = t21.t21sccode ";
	$load .= " LEFT JOIN ";
	$load .= "(select sccode as t22sccode, SUM(racuniznos) as sumracuna22 from tglava where vreme >= '".$_POST['datumod']. " 22:00:00' and vreme < '".$_POST['datumod']. " 23:00:00' ";
	$load .= "GROUP BY t22sccode order by t22sccode asc) t22 ON t21.t21sccode = t22.t22sccode ";
	
	$load .= " GROUP BY t1.t1sccode ORDER BY t1.t1sccode asc ";
					
					$load .= "INTO OUTFILE '/tmp/izvestaj.csv' ";
					$load .= "FIELDS TERMINATED BY ';' ";
					//$load .= "ENCLOSED BY '\"' ";
					$load .= "LINES TERMINATED BY '\r\n' ";				
					echo '<br />';
					echo '<br />';
					$mysqli->query($load);
					
					//append hedera na kraj fajla - jer sa drugim opcijama za postavljanje na vrh fajla se brise prvi red...
					$delimiter = ';';
					$list = array ("MPO","DnevnaProdaja","Vreme","SCCode","5h - 6h","SCCode","6h - 7h","SCCode","7h - 8h","SCCode","8h - 9h","SCCode","9h - 10h","SCCode","10h - 11h","SCCode","11h - 12h","SCCode","12h - 13h","SCCode","13h - 14h","SCCode","14h - 15h","SCCode","15h - 16h","SCCode","16h - 17h","SCCode","17h - 18h","SCCode","18h - 19h","SCCode","19h - 20h","SCCode","20h - 21h","SCCode","21h - 22h","SCCode","22h - 23h");
					//$handle = fopen("C:\\Users\\gricko\\Downloads\\izvestaj.csv", "a");
					$handle = fopen("/tmp/izvestaj.csv", "a");
					fputcsv($handle, $list, $delimiter);
					fclose($handle);	
		/*			//formatiranje fajla
					
					$buffer = array();
					$fp = fopen("/tmp/izvestaj.csv", "r");
					while(!feof($fp)) {
					  $buffer[] = fgets($fp, 4096);
					}
					fclose($fp);
					$tekst = implode($buffer);
					$tekst = preg_replace("/,m$/", "", $tekst);
					$fp = fopen("/tmp/kontakti.txt", "w");
					fputs($fp, $tekst);
					fclose($fp);										*/
					
						
						// ---------------- SEND MAIL FORM ----------------
						$mail = new PHPMailer();
						//$mail->SetLanguage('en',dirname(__FILE__)); // . '/phpmailer/language/');
						//$body             = $mail->getFile('contents.html');
						//$body             = eregi_replace("[\]",'',$body);
						
						$mail->IsSMTP();
						$mail->Mailer = "smtp";
						$mail->SMTPAuth   = true;                  // enable SMTP authentication
						$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
						$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
						$mail->Port       = 465;                   // set the SMTP port for the GMAIL server
						$mail->CharSet="utf-8";
						//ne znam kako da vucem un iz settings.php	$mail->Username   = "'".$emailun."'@gmail.com";  // GMAIL username
						$mail->Username   = "podrska.bbtrade@gmail.com";
						//ne znam kako da vucem pass iz settings.php	$mail->Password = $emailpass;            // GMAIL password
						$mail->Password   = "bbit1234";            // GMAIL password
						//bezveze pokusaj   $mail->login($emailun, $emailpass);
						
						$mail->AddReplyTo("podrska.bbtrade@gmail.com","Podrska BB Trade ad");
						//$mail->AddReplyTo($emailun,"Podrska BB Trade ad");
						
						$mail->From       = "podrska.bbtrade@gmail.com";
						$mail->FromName   = "Podrska BB Trade ad";
						$mail->AddAddress($_POST['nemail']);
						$mail->AddAttachment("/tmp/izvestaj.csv");             // attachment
							
						// send e-mail to ...
						//$to=$email;
						//$mail->AddAddress("$email");
						
						// Your subject
						//$subject="Your confirmation link here";
						$mail->Subject = "Izvestaj o prometu po satima za sve mpo";

						// From
						//$header="from: your name <your email>";
						$mail->Header = "from: Podrska BB Trade ad <podrska.bbtrade@gmail.com>";

						// Your message
						//$message="Your Comfirmation link \r\n";
						//$message.="Click on this link to activate your account \r\n";
						//$message.="http://www.yourweb.com/confirmation.php?passkey=$confirm_code";
						$mail->Body = "U prilogu je izvestaj o prometu po satima za sve mpo";                      //HTML Body
						$mail->AltBody    = "AltBody - U prilogu je izvestaj o prometu po satima za sve mpo"; // optional, comment out and test
						$mail->WordWrap   = 50; // set word wrap
						
						//$mail->MsgHTML($body);


						// send email
						//$mail = mail($to,$subject,$message,$header);
						
						if(!$mail->Send()) {
							  echo "Mailer Error: " . $mail->ErrorInfo;
							} else {
							  echo "Message sent!";
							}
				
					//unlink('/tmp/kontakti.txt');

					$sftp = new Net_SFTP('podrska.bbtrade.rs');

					if (!$sftp->login('root', 'bbsb100$')) {
						echo "Login Failed";
						exit('Login Failed');
					}
					// outputs the contents of filename.remote to the screen
					//echo $sftp->get('/tmp/kontakti.txt');
					// copies filename.remote to filename.local from the SFTP server
					
					//$sftp->get('/tmp/kontakti.txt', '\\C$\Users\%username%\Desktop\kontakti.txt');
					
					$sftp->get('/tmp/izvestaj.csv', '/cygdrive/c/storno/izvestaj.txt');
					//ssh2.sftp://{$resSFTP}/cygdrive/c/to/path
									
					if ($sftp->delete('/tmp/izvestaj.csv')) { // doesn't delete directories
						echo "File /tmp/izvestaj.csv je obrisan!";
					}else{
						echo "File /tmp/izvestaj.csv nije obrisan!";
					}
					echo '<br />';
					/*echo "Izvrsen je sledeci upit:";
					echo '<br />';
					echo $sql;*/
					echo '<br />';
					echo "Ako ste upisali ispravnu email adresu, fajl ce Vam biti isporucen putem email-a.";
				}
			}
}else{
//if (file_exists('/dbf/proveradir/proverafile.txt')){

exit ("U toku je punjenje baze!!!");

}

			
?>



</div>
</div>
</body>
</html>