<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Storno racuni</title>
<style type="text/css">
body {
	background: #E3F4FC;
	font: normal 12px/20px Helvetica, Arial, sans-serif;
	color: #2b2b2b;
}
a {
	color:#898989;
	font-size:10px;
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
	width: 1000px;
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

echo "<b>Storno racuni</b>";
echo "\n";
echo "\t";
print '<br />';
echo "\n";
echo 'Izvestaj 2 - Pretraga po SCCode, Operater, Artiklu i Datumu';

?>

<div id="container">
<div id="form">

<!-- p align="left"><a href="/storno/stornosccode.php"><button>Prikazi listu storno stavki order by sccode...</button></a></p -->

<p align="right"><a href="/storno/index.php"><button>Vrati se na prvu stranicu...</button></a></p>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?> ">	
<br />
Broj mpo: &nbsp <input type="text" name="sccode" onkeypress="return tabE(this,event)" />
&nbsp &nbsp &nbsp &nbsp &nbsp
Operater: &nbsp <input type="text" name="operater" onkeypress="return tabE(this,event)" />
<br />
Bar code: &nbsp <input type="text" name="ean" onkeypress="return tabE(this,event)" />
&nbsp &nbsp &nbsp &nbsp &nbsp
Naziv art: &nbsp <input type="text" name="nazivartikla" onkeypress="return tabE(this,event)" />
<br />
Datum od: &nbsp <input type="date" name="datumod" onkeypress="return tabE(this,event)" />
&nbsp &nbsp &nbsp &nbsp 
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

if(!file_exists('/dbf/proveradir/proverafile.txt')){

//konekcija na bazu
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

if (isset($_POST['prikazi'])) {
	//Upit za listu storno stavki order by sccode
	
	if (($_POST['datumdo']) != '' & ($_POST['datumod']) != '' & (($_POST['datumdo']) < ($_POST['datumod']))) {
			echo 'Pogresno su postavljeni datumski okviri!';
		}else{
	
/*	if (($_POST['operater']) != '' OR ($_POST['sccode']) != '') {*/
		$load = "select ts.vreme, concat(tg.racunbr,' / ',tg.naplata) as racunbrnaplata, tg.sccode, tg.kasabr, tg.operater, ts.ean, ar.nazivartikla, ts.kolicina, ts.jm, ts.cenastavke, ts.vrednoststavke, tg.racuniznos from tstavke ts, tglava tg, artikal ar where kolicina <= 0 ";
		
		if (($_POST['operater']) != ''){
			$load .= " AND tg.operater LIKE '%".$_POST['operater']."%' ";
		}
		
		if (($_POST['nazivartikla']) != ''){
			$load .= " AND ar.nazivartikla LIKE '%".$_POST['nazivartikla']."%' ";
		}
		
		if (($_POST['ean']) != ''){
			$load .= " AND ts.ean LIKE '%".$_POST['ean']."%' ";
		}
		
		if (($_POST['sccode']) != ''){
			$load .= " AND ts.sccode = '".$_POST['sccode']."' ";
		}
		
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
		
		
		/*$load .= " AND date(ts.vreme) = date(tg.vreme) ";
		$load .= " AND date(ts.kasabr) = date(tg.kasabr) ";
		$load .= " AND date(ts.racunbr) = date(tg.racunbr) ";*/
		
		//$load .= " AND ts.vremestring = tg.vremestring ";
		$load .= " AND ts.kljuc = tg.kljuc ";
		$load .= " AND ts.racunbr = tg.racunbr ";
		$load .= " AND ar.ean = ts.ean ";
		
		$load .= " order by ts.sccode, ts.kasabr, ts.vreme asc ";
		$res = $mysqli->query($load);
		//echo $load;
		echo "Prikazani podaci za period od : '".$_POST['datumod']."' do '".$_POST['datumdo']."' ";
		
		echo '<table border="1"; table-layout: fixed; width="950">';
		echo '<tr bgcolor = #31B404 ><th><font size="2">Prikazano '.$res->num_rows.' </font></th><th><font size="1">Racun broj / Nacin placanja</font></th><th><font size="2">SC Code</th><th><font size="2">Kasa broj</font></th><th><font size="2">Operater</font></th><th><font size="2"> EAN </font></th><th><font size="2"> Naziv artikla </font></th><th><font size="2">Kol</font></th><th><font size="2">JM</font></th><th><font size="2">Cena stavke</font></th><th><font size="2">Vrednost stavke</font></th><th><font size="1">Vrednost storno racuna</font></th></tr>';
		while($rec = $res->fetch_assoc()){
			echo '<tr>';
				echo '<td width="12%">';
				echo '<font size="2">';
				echo $rec['vreme'];
				echo '</font>';
				echo '</td>';
				echo '<td width="10%">';
				echo '<font size="2">';
				echo $rec['racunbrnaplata'];
				echo '</font>';
				echo '</td>';
				echo '<td width="5%">';
				echo '<font size="2">';
				echo $rec['sccode'];
				echo '</font>';
				echo '</td>';
				echo '<td width="5%">';
				echo '<font size="2">';
				echo $rec['kasabr'];
				echo '</font>';
				echo '</td>';
					echo '<td width="10%">';
					echo '<font size="2">';
					echo $rec['operater'];
					echo '</font>';
					echo '</td>';
				echo '<td width="12%">';
				echo '<font size="2">';
				echo $rec['ean'];
				echo '</font>';
				echo '</td>';
					echo '<td width="14%">';
					echo '<font size="1">';
					echo $rec['nazivartikla'];
					echo '</font>';
					echo '</td>';
				echo '<td align="right" width="3%">';
				echo '<font size="2">';
				echo number_format($rec['kolicina'], 2);
				echo '</font>';
				echo '</td>';
				echo '<td width="4%">';
				echo '<font size="2">';
				echo $rec['jm'];
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="5%">';
				echo '<font size="2">';
				echo number_format($rec['cenastavke'], 2);
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="10%">';
				echo '<font size="2">';
				echo number_format($rec['vrednoststavke'], 2);
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="10%">';
				echo '<font size="2">';
				echo number_format($rec['racuniznos'], 2);
				echo '</font>';
				echo '</td>';
				echo '</tr>';
				
				$kolicinasum = $kolicinasum + $rec['kolicina'];
				$vrednoststavkesum = $vrednoststavkesum + $rec['vrednoststavke'];
				$racuniznossum = $racuniznossum + $rec['racuniznos'];
				
		}
	
			echo '</table>';
			
			echo '<table border="1"; table-layout: fixed; width="950">';
		echo '<tr bgcolor = #31B404 ><th><font size="2">Prikazano '.$res->num_rows.' </font></th><th><font size="1">Racun broj / Nacin placanja</font></th><th><font size="2">SC Code</th><th><font size="2">Kasa broj</font></th><th><font size="2">Operater</font></th><th><font size="2"> EAN </font></th><th><font size="2"> Naziv artikla </font></th><th><font size="2">Kol</font></th><th><font size="2">JM</font></th><th><font size="2">Cena stavke</font></th><th><font size="2">Vrednost stavke</font></th><th><font size="1">Vrednost storno racuna</font></th></tr>';
		
			echo '<tr>';
				echo '<td width="12%">';
				echo '<font size="2">';
				echo "od'".$_POST['datumod']."' ";
				echo '<br />';
				echo "do'".$_POST['datumdo']."' ";
				echo '</font>';
				echo '</td>';
				echo '<td width="10%">';
				echo '<font size="2">';
				echo " Ukupno ";
				echo '</font>';
				echo '</td>';
				echo '<td width="5%">';
				echo '<font size="2">';
				echo " ";
				echo '</font>';
				echo '</td>';
				echo '<td width="5%">';
				echo '<font size="2">';
				echo " ";
				echo '</font>';
				echo '</td>';
					echo '<td width="10%">';
					echo '<font size="2">';
					echo " ";
					echo '</font>';
					echo '</td>';
				echo '<td width="12%">';
				echo '<font size="2">';
				echo " ";
				echo '</font>';
				echo '</td>';
					echo '<td width="14%">';
					echo '<font size="1">';
					echo " ";
					echo '</font>';
					echo '</td>';
				echo '<td align="right" width="3%">';
				echo '<font size="2">';
				echo number_format($kolicinasum, 2);
				echo '</font>';
				echo '</td>';
				echo '<td width="4%">';
				echo '<font size="2">';
				echo $rec['jm'];
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="5%">';
				echo '<font size="2">';
				echo " ";
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="10%">';
				echo '<font size="2">';
				echo number_format($vrednoststavkesum, 2);
				echo '</font>';
				echo '</td>';
				echo '<td align="right" width="10%">';
				echo '<font size="2">';
				echo number_format($racuniznossum, 2);
				echo '</font>';
				echo '</td>';
				echo '</tr>';
				
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
			}elseif (($_POST['datumdo']) != '' & ($_POST['datumod']) != '' & (($_POST['datumdo']) < ($_POST['datumod']))) {
			echo 'Pogresno su postavljeni datumski okviri!';
		}else{
			
	if ((($_POST['operater']) != '') OR (($_POST['sccode']) != '') OR (($_POST['ean']) != '') OR (($_POST['nazivartikla']) != '') OR (($_POST['datumod']) != '') /*OR (($_POST['datumod']) != '')*/) {
					$sql = "select ts.vreme, concat(tg.racunbr,' / ',tg.naplata) as racunbrnaplata, tg.sccode, tg.kasabr, tg.operater, ts.ean, ar.nazivartikla, ts.kolicina, ts.jm, ts.cenastavke, ts.vrednoststavke, tg.racuniznos from tstavke ts, tglava tg, artikal ar where kolicina <= 0 ";
					
					if (($_POST['operater']) != ''){
						$sql .= " AND tg.operater LIKE '%".$_POST['operater']."%' ";
					}
					
					if (($_POST['nazivartikla']) != ''){
						$sql .= " AND ar.nazivartikla LIKE '%".$_POST['nazivartikla']."%' ";
					}
					
					if (($_POST['ean']) != ''){
						$sql .= " AND ts.ean LIKE '%".$_POST['ean']."%' ";
					}
					
					if (($_POST['sccode']) != ''){
						$sql .= " AND ts.sccode = '".$_POST['sccode']."' ";
					}
					
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
					
					
					/*$sql .= " AND date(ts.vreme) = date(tg.vreme) ";
					$sql .= " AND date(ts.kasabr) = date(tg.kasabr) ";
					$sql .= " AND date(ts.racunbr) = date(tg.racunbr) ";*/
					
					//$sql .= " AND ts.vremestring = tg.vremestring ";
					$sql .= " AND ts.kljuc = tg.kljuc ";
					$sql .= " AND ts.racunbr = tg.racunbr ";
					$sql .= " AND ar.ean = ts.ean ";
					
					$sql .= " order by ts.sccode, ts.kasabr, ts.vreme asc ";
		
		

					$sql .= "INTO OUTFILE '/tmp/izvestaj_op.csv' ";
					$sql .= "FIELDS TERMINATED BY ',' ";
					//$sql .= "ENCLOSED BY '\"' ";
					$sql .= "LINES TERMINATED BY '\r\n' ";				
					echo '<br />';
					
					$mysqli->query($sql);
					
		/*			//formatiranje fajla
					
					$buffer = array();
					$fp = fopen("/tmp/izvestaj_op.csv", "r");
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
						$mail->AddAttachment("/tmp/izvestaj_op.csv");             // attachment
							
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
					
					$sftp->get('/tmp/izvestaj_op.csv', 'C:/storno/izvestaj.txt');
					
									
					if ($sftp->delete('/tmp/izvestaj_op.csv')) { // doesn't delete directories
						echo "File /tmp/izvestaj_op.csv je obrisan!";
					}else{
						echo "File /tmp/izvestaj_op.csv nije obrisan!";
					}
					echo '<br />';
					/*echo "Izvrsen je sledeci upit:";
					echo '<br />';
					echo $sql;*/
					echo '<br />';
					echo "Ako ste upisali ispravnu email adresu, fajl ce Vam biti isporucen putem email-a.";
		
		
		
			}else{
				
				//Besmisleno je jer bi ovako bio prevelik fajl
				
				/*
				$sql = "select vreme, racunbr, sccode, kasabr, ean, kolicina, jm, cenastavke, vrednoststavke from tstavke where kolicina <= 0 ";
				
				if (($_POST['datumdo']) != '' & ($_POST['datumod']) == '') {
					$sql .= " AND vreme <= '".$_POST['datumdo']."' ";
				}
				
				if (($_POST['datumdo']) == '' & ($_POST['datumod']) != '') {
					$sql .= " AND vreme >= '".$_POST['datumod']."' ";
				}
				
				if (($_POST['datumdo']) != '' & ($_POST['datumod']) != '' & (($_POST['datumdo']) > ($_POST['datumod']))) {
					$sql .= " AND vreme >= '".$_POST['datumod']."' AND vreme <= '".$_POST['datumdo']."' ";
				}
				
				if (($_POST['datumdo']) != '' & ($_POST['datumod']) != '' & (($_POST['datumdo']) == ($_POST['datumod']))) {
					$sql .= " AND ts.vreme == '".$_POST['datumod']."' ";//AND ts.vreme <= '".$_POST['datumdo']."' ";
				}
				
				*/
				//zato samo ispisiujem upozorenje:
				echo '<b>Suzite pretragu, nije moguce dostaviti sve informacije</b>';
				echo '<br />';
				echo '<b>fajl bi bio prevelik da bi mogao da se posalje email-om!</b>';
				echo '<br />';
				echo '<br />';
				echo '<br />';
				
		
						
			}
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