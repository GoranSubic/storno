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
	width: 945px;
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

echo "<b>Program za pretragu storno racuna</b>";
echo "\n";
echo "\t";
print '<br />';
echo "\n";
echo 'Izvestaj 1 - Pretraga po SCCode i Datumu';

?>

<div id="container">
<div id="form">

<!-- p align="left"><a href="/storno/stornosccode.php"><button>Prikazi listu storno stavki order by sccode...</button></a></p -->

<p align="right"><a href="/storno/index.php"><button>Vrati se na prvu stranicu...</button></a></p>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?> ">	
Datum od: &nbsp <input type="date" name="datumod" onkeypress="return tabE(this,event)" />
Datum do: &nbsp <input type="date" name="datumdo" onkeypress="return tabE(this,event)" />
<br />
<input type="submit" name="prikazi" value="Prikazi listu storno stavki u svim mpo slozeno po sccode, datum..." />
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

$brstavkisvi = '0';
$ukracunbrsvi = '0';
$sumstavkisvi = '0';
$sumracunasvi = '0';
$ukbrracsccodesvi = '0';

if(!file_exists('/dbf/proveradir/proverafile.txt')){

//konekcija na bazu
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

if (isset($_POST['prikazi'])) {

if (($_POST['datumdo']) != '' & ($_POST['datumod']) != '' & (($_POST['datumdo']) < ($_POST['datumod']))) {
	echo 'Pogresno su postavljeni datumski okviri!';
	}else{
		
		
	//$load = "select ts.vreme as tvreme, ts.sccode as tsccode, COUNT(ts.ean) as brstavki, COUNT(DISTINCT ts.kljuc) as ukracunbr, SUM(ts.vrednoststavke) as sumstavki, MAX(tg.racunbr) as ukbrracsccode, SUM(tg.racuniznos) as sumracuna from tstavke ts, tglava tg where ts.kljuc = tg.kljuc AND ts.sccode = tg.sccode AND ts.kolicina <= 0 ";
	//$load = "select * from (select sccode as t1tsccode, MAX(racunbr) as ukbrracsccode, SUM(racuniznos) as sumracuna from tglava ";
	
	$load = "select * from (select sccode as t1tsccode, COUNT(DISTINCT kljuc) as ukbrracsccode, SUM(racuniznos) as sumracuna from tglava ";
	$load .= "where sccode >= '1' ";
		if (($_POST['datumdo']) != '' & ($_POST['datumod']) != '' & (($_POST['datumdo']) == ($_POST['datumod']))) {
			$load .= " AND vreme >= '".$_POST['datumod']."' AND vreme < date_add('".$_POST['datumdo']."', INTERVAL 1 DAY) ";//AND ts.vreme <= '".$_POST['datumdo']."' ";
			//date_add(yourDate, INTERVAL 7 DAY)
		}
		
		if (($_POST['datumdo']) != '' & ($_POST['datumod']) == '') {
			$load .= " AND vreme < date_add('".$_POST['datumdo']."', INTERVAL 1 DAY) ";
		}
		
		if (($_POST['datumdo']) == '' & ($_POST['datumod']) != '') {
			$load .= " AND vreme >= '".$_POST['datumod']."' ";
		}
		
		if (($_POST['datumdo']) != '' & ($_POST['datumod']) != '' & (($_POST['datumdo']) > ($_POST['datumod']))) {
			$load .= " AND vreme >= '".$_POST['datumod']."' AND vreme < date_add('".$_POST['datumdo']."', INTERVAL 1 DAY) ";
		}	
		
	$load .= "GROUP BY t1tsccode order by t1tsccode asc ) t1  ";
	
	$load .= "inner join (select ts.vreme as tvreme, ts.sccode as t2tsccode, COUNT(ts.ean) as brstavki, COUNT(DISTINCT ts.kljuc) as ukracunbr, SUM(ts.vrednoststavke) as sumstavki ";
	$load .= "from tstavke ts, tglava tg  ";
	$load .= "where ts.kljuc = tg.kljuc AND ts.sccode = tg.sccode AND ts.kolicina <= 0 ";
	
	
		if (($_POST['datumdo']) != '' & ($_POST['datumod']) != '' & (($_POST['datumdo']) == ($_POST['datumod']))) {
			$load .= " AND ts.vreme >= '".$_POST['datumod']."' AND ts.vreme < date_add('".$_POST['datumdo']."', INTERVAL 1 DAY) ";//AND ts.vreme <= '".$_POST['datumdo']."' ";
			//date_add(yourDate, INTERVAL 7 DAY)
		}
		
		if (($_POST['datumdo']) != '' & ($_POST['datumod']) == '') {
			$load .= " AND ts.vreme < date_add('".$_POST['datumdo']."', INTERVAL 1 DAY) ";
		}
		
		if (($_POST['datumdo']) == '' & ($_POST['datumod']) != '') {
			$load .= " AND ts.vreme >= '".$_POST['datumod']."' ";
		}
		
		if (($_POST['datumdo']) != '' & ($_POST['datumod']) != '' & (($_POST['datumdo']) > ($_POST['datumod']))) {
			$load .= " AND ts.vreme >= '".$_POST['datumod']."' AND ts.vreme < date_add('".$_POST['datumdo']."', INTERVAL 1 DAY) ";
		}	
	$load .= "GROUP BY t2tsccode order by t2tsccode, tvreme asc	";
	$load .= "	) t2 ";
	$load .= "ON t1.t1tsccode=t2.t2tsccode ";
	$load .= "GROUP BY t1.t1tsccode ";	
	$load .= "order by t1.t1tsccode, t2.tvreme asc ";
	$res = $mysqli->query($load);
	/*	
			echo "Izvrsava se sledeci upit";
			echo '<br />';
			echo $load;
			echo '<br />';
			*/
	echo "Prikazani podaci za period od : '".$_POST['datumod']."' do '".$_POST['datumdo']."' ";
			
	echo '<table border="1"; table-layout: fixed; width="800">';
	echo '<tr bgcolor = #31B404 ><th><font size="2">Prikazano '.$res->num_rows.' </font></th><th><font size="2">SC Code</font></th><th><font size="2">Broj storno stavki</font></th><th><font size="2">Broj storno racuna</font></th><th><font size="2">Vrednost storniranih stavki</font></th><th><font size="2">Broj racuna</font></th><th><font size="2">Vrednost racuna ukupno</font></th><th><font size="2">% Storno</font></th></tr>';
	echo '<br />';			
	while($rec = $res->fetch_assoc()){
			echo '<tr>';
				echo '<td width="18%">';
				echo '<font size="2">';
				echo $rec['tvreme'];
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="7%">';
				echo '<font size="2">';
				echo $rec['t1tsccode'];
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="10%">';
				echo '<font size="2">';
				echo $rec['brstavki'];
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="10%">';
				echo '<font size="2">';
				echo $rec['ukracunbr'];
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="15%">';
				echo '<font size="2">';
				echo number_format($rec['sumstavki'], 2);
				echo '</font>';
				echo '</td>';
				
				echo '<td align="right" width="10%">';
				echo '<font size="2">';
				echo $rec['ukbrracsccode'];
				echo '</font>';
				echo '</td>';
				
				echo '<td align="right" width="15%">';
				echo '<font size="2">';
				echo number_format($rec['sumracuna'], 2);
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="15%">';
				echo '<font size="2">';
				echo number_format(($procstorno = $rec['sumstavki'] / $rec['sumracuna'] * 100), 2);
				echo '</font>';
				echo '</td>';
				echo '</tr>';
				
			$brstavkisvi = $brstavkisvi + $rec['brstavki'];
			$ukracunbrsvi = $ukracunbrsvi + $rec['ukracunbr'];
			$sumstavkisvi = $sumstavkisvi + $rec['sumstavki'];
			$sumracunasvi = $sumracunasvi + $rec['sumracuna'];
			$ukbrracsccodesvi = $ukbrracsccodesvi + $rec['ukbrracsccode'];
			
			
	}		
			echo '</table>';
			echo '<table border="1"; table-layout: fixed; width="800">';
			//echo '<tr bgcolor = #31B404 ><th>Prikazano '.$res->num_rows.' </th><th>SCCode</th><th>Broj storno stavki</th><th>Broj storno racuna</th><th>Vrednost storniranih stavki</th><th>Vrednost racuna</th><th>% Storno</th></tr>';
			echo '<tr bgcolor = #31B404 ><th><font size="2">Prikazano '.$res->num_rows.' </font></th><th><font size="2">SC Code</font></th><th><font size="2">Broj storno stavki</font></th><th><font size="2">Broj storno racuna</font></th><th><font size="2">Vrednost storniranih stavki</font></th><th><font size="2">Broj racuna</font></th><th><font size="2">Vrednost racuna ukupno</font></th><th><font size="2">% Storno</font></th></tr>';
			echo '<tr>';
				echo '<td width="18%">';
				echo '<font size="2">';
				echo "od'".$_POST['datumod']."' ";
				echo '<br />';
				echo "do'".$_POST['datumdo']."' ";
				echo '</font>';
				echo '</td>';
				echo '<td width="7%">';
				echo '<font size="2">';
				echo " Ukupno";
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="10%">';
				echo '<font size="2">';
				echo $brstavkisvi;
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="10%">';
				echo '<font size="2">';
				echo $ukracunbrsvi;
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="15%">';
				echo '<font size="2">';
				echo number_format($sumstavkisvi, 2);
				echo '</font>';
				echo '</td>';
				echo '</td>';
				echo '<td align="right" width="10%">';
				echo '<font size="2">';
				echo $ukbrracsccodesvi;
				echo '</font>';
				echo '</td>';
				
				echo '<td align="right" width="15%">';
				echo '<font size="2">';
				echo number_format($sumracunasvi, 2);
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="15%">';
				echo '<font size="2">';
				echo number_format($procstorno = $sumstavkisvi / $sumracunasvi*100, 2);
				echo '</font>';
				echo '</td>';
				echo '</tr>';
			echo '</table>';
			
			
			/*echo "Ukupna suma racuna koji imaju stornirane stavke: ";
			echo $sumracunasvi;
			
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
	}elseif(($_POST['datumdo']) != '' & ($_POST['datumod']) != '' & (($_POST['datumdo']) < ($_POST['datumod']))) {
		echo 'Pogresno su postavljeni datumski okviri!';
		}else{
					
					
					$sql = "select * from (select ts.vreme as tvreme, ts.sccode as t2tsccode, COUNT(ts.ean) as brstavki, COUNT(DISTINCT ts.kljuc) as ukracunbr, SUM(ts.vrednoststavke) as sumstavki ";
					$sql .= "from tstavke ts, tglava tg  ";
					$sql .= "where ts.kljuc = tg.kljuc AND ts.sccode = tg.sccode AND ts.kolicina <= 0 ";
					
					
						if (($_POST['datumdo']) != '' & ($_POST['datumod']) != '' & (($_POST['datumdo']) == ($_POST['datumod']))) {
							$sql .= " AND ts.vreme >= '".$_POST['datumod']."' AND ts.vreme < date_add('".$_POST['datumdo']."', INTERVAL 1 DAY) ";//AND ts.vreme <= '".$_POST['datumdo']."' ";
							//date_add(yourDate, INTERVAL 7 DAY)
						}
						
						if (($_POST['datumdo']) != '' & ($_POST['datumod']) == '') {
							$sql .= " AND ts.vreme < date_add('".$_POST['datumdo']."', INTERVAL 1 DAY) ";
						}
						
						if (($_POST['datumdo']) == '' & ($_POST['datumod']) != '') {
							$sql .= " AND ts.vreme >= '".$_POST['datumod']."' ";
						}
						
						if (($_POST['datumdo']) != '' & ($_POST['datumod']) != '' & (($_POST['datumdo']) > ($_POST['datumod']))) {
							$sql .= " AND ts.vreme >= '".$_POST['datumod']."' AND ts.vreme < date_add('".$_POST['datumdo']."', INTERVAL 1 DAY) ";
						}	
					$sql .= "GROUP BY t2tsccode order by t2tsccode, tvreme asc	";
					$sql .= "	) t2 ";
					
					
					$sql .= "inner join (select COUNT(DISTINCT kljuc) as ukbrracsccode, SUM(racuniznos) as sumracuna, sccode as t1tsccode from tglava ";
					$sql .= "where sccode >= '1' ";
						if (($_POST['datumdo']) != '' & ($_POST['datumod']) != '' & (($_POST['datumdo']) == ($_POST['datumod']))) {
							$sql .= " AND vreme >= '".$_POST['datumod']."' AND vreme < date_add('".$_POST['datumdo']."', INTERVAL 1 DAY) ";//AND ts.vreme <= '".$_POST['datumdo']."' ";
							//date_add(yourDate, INTERVAL 7 DAY)
						}
						
						if (($_POST['datumdo']) != '' & ($_POST['datumod']) == '') {
							$sql .= " AND vreme < date_add('".$_POST['datumdo']."', INTERVAL 1 DAY) ";
						}
						
						if (($_POST['datumdo']) == '' & ($_POST['datumod']) != '') {
							$sql .= " AND vreme >= '".$_POST['datumod']."' ";
						}
						
						if (($_POST['datumdo']) != '' & ($_POST['datumod']) != '' & (($_POST['datumdo']) > ($_POST['datumod']))) {
							$sql .= " AND vreme >= '".$_POST['datumod']."' AND vreme < date_add('".$_POST['datumdo']."', INTERVAL 1 DAY) ";
						}	
						
					$sql .= "GROUP BY t1tsccode order by t1tsccode asc ) t1  ";
					
					$sql .= "ON t1.t1tsccode=t2.t2tsccode ";
					$sql .= "GROUP BY t1.t1tsccode ";	
					$sql .= "order by t1.t1tsccode, t2.tvreme asc ";
					
					$sql .= "INTO OUTFILE '/tmp/izvestaj.csv' ";
					$sql .= "FIELDS TERMINATED BY ',' ";
					//$sql .= "ENCLOSED BY '\"' ";
					$sql .= "LINES TERMINATED BY '\r\n' ";				
					echo '<br />';
					
					$mysqli->query($sql);
					
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
						$mail->Subject = "Izvestaj o storno stavkama u txt formatu";

						// From
						//$header="from: your name <your email>";
						$mail->Header = "from: Podrska BB Trade ad <podrska.bbtrade@gmail.com>";

						// Your message
						//$message="Your Comfirmation link \r\n";
						//$message.="Click on this link to activate your account \r\n";
						//$message.="http://www.yourweb.com/confirmation.php?passkey=$confirm_code";
						$mail->Body = "U prilogu je izvestaj o storno stavkama u txt formatu";                      //HTML Body
						$mail->AltBody    = "AltBody - U prilogu je izvestaj o storno stavkama u txt formatu"; // optional, comment out and test
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

exit ("U toku punjenje baze!!!");

}

			
?>



</div>
</div>
</body>
</html>