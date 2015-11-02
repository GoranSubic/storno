<?php

//if (file_exists('/dbf/proveradir')){
if(!file_exists('/dbf/proveradir/proverafile.txt')){

	//mkdir("/dbf/proveradir", 0777, true);
	touch ("/dbf/proveradir/proverafile.txt");

//include "settings.php"; //Connect to Database
include_once ('/opt/bitnami/apps/storno/settings.php');

//konekcija na bazu
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

$deleterecords = "TRUNCATE TABLE tglava"; 
//empty the table of its current records mysql_query($deleterecords);
$res = $mysqli->query($deleterecords);

$deleterecords = "TRUNCATE TABLE tstavke"; 
//empty the table of its current records mysql_query($deleterecords);
$res = $mysqli->query($deleterecords);

$deleterecords = "TRUNCATE TABLE artikal"; 
//empty the table of its current records mysql_query($deleterecords);
$res = $mysqli->query($deleterecords);

//Upload File kasaapp.racun.csv
	for ($x=1; $x<=9; $x++) {
		for ($k=1; $k<=9; $k++) {
			$load = "LOAD DATA INFILE \"/dbf/prodaja/0$x/$k/kasaapp.racun.csv\" INTO TABLE tglava FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' (@vreme,racunbr,naplata,racuniznos,naplata1,naplata2,naplata3,naplata4,tf,vreme2,operater,subjekat,banka,prazno1,prazno2,datum) SET vreme = str_to_date(@vreme,'%d-%M-%Y %H:%i:%s'), sccode=0$x, kasabr=$k";
			$mysqli->query($load);
			
			echo 'Zavrsen import za SCCode';
			echo $x;
			echo 'i kasu broj';
			echo $k;
			echo '<br />';
			
			/*$sccode = "UPDATE tglava SET sccode = 0$x WHERE sccode = 0";
			$mysqli->query($sccode);
			
			$kasabr = "UPDATE tglava SET kasabr = $k WHERE kasabr = 0";
			$mysqli->query($kasabr);*/
		}
	}
	for ($y=10; $y<=120; $y++) {
		for ($l=1; $l<=9; $l++) {
			$load = "LOAD DATA INFILE \"/dbf/prodaja/$y/$l/kasaapp.racun.csv\" INTO TABLE tglava FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' (@vreme,racunbr,naplata,racuniznos,naplata1,naplata2,naplata3,naplata4,tf,vreme2,operater,subjekat,banka,prazno1,prazno2,datum) SET vreme = str_to_date(@vreme,'%d-%M-%Y %H:%i:%s'), sccode=$y, kasabr=$l";
			$mysqli->query($load);
			
			echo 'Zavrsen import za SCCode';
			echo $y;
			echo 'i kasu broj';
			echo $l;
			echo '<br />';
		}
	}
	
//Upload File kasaapp.racun_stavka.csv
	for ($x=1; $x<=9; $x++) {
		for ($k=1; $k<=9; $k++) {
			$load = "LOAD DATA INFILE \"/dbf/prodaja/0$x/$k/kasaapp.racun_stavka.csv\" INTO TABLE tstavke FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' (@vreme,ean,kolicina,jm,cenastavke,vrednoststavke,racunbr) SET vreme = str_to_date(@vreme,'%d-%M-%Y %H:%i:%s'), sccode=0$x, kasabr=$k";
			$mysqli->query($load);
			
			echo 'Zavrsen import stavki za SCCode';
			echo $x;
			echo 'i kasu broj';
			echo $k;
			echo '<br />';
		}
	}
	
//Brisanje redova koji nisu u minusu	
			echo '<br />';
			echo 'Brisanje redova koji nisu u minusu za prvih 9 mpo';
			echo '<br />';
			$load = " DELETE FROM tstavke WHERE kolicina > 0 ";
			$mysqli->query($load);

	for ($y=10; $y<=34; $y++) {
		for ($l=1; $l<=9; $l++) {
			$load = "LOAD DATA INFILE \"/dbf/prodaja/$y/$l/kasaapp.racun_stavka.csv\" INTO TABLE tstavke FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' (@vreme,ean,kolicina,jm,cenastavke,vrednoststavke,racunbr) SET vreme = str_to_date(@vreme,'%d-%M-%Y %H:%i:%s'), sccode=$y, kasabr=$l";
			$mysqli->query($load);
			
			echo 'Zavrsen import stavki za SCCode';
			echo $y;
			echo 'i kasu broj';
			echo $l;
			echo '<br />';
		}
	}

//Brisanje redova koji nisu u minusu	
			echo '<br />';
			echo 'Brisanje redova koji nisu u minusu za prvih 35 mpo';
			echo '<br />';
			$load = " DELETE FROM tstavke WHERE kolicina > 0 ";
			$mysqli->query($load);			
			
	for ($y=35; $y<=69; $y++) {
		for ($l=1; $l<=9; $l++) {
			$load = "LOAD DATA INFILE \"/dbf/prodaja/$y/$l/kasaapp.racun_stavka.csv\" INTO TABLE tstavke FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' (@vreme,ean,kolicina,jm,cenastavke,vrednoststavke,racunbr) SET vreme = str_to_date(@vreme,'%d-%M-%Y %H:%i:%s'), sccode=$y, kasabr=$l";
			$mysqli->query($load);
			
			echo 'Zavrsen import stavki za SCCode';
			echo $y;
			echo 'i kasu broj';
			echo $l;
			echo '<br />';
		}
	}

//Brisanje redova koji nisu u minusu		
			echo '<br />';
			echo 'Brisanje redova koji nisu u minusu za prvih 70 mpo';
			echo '<br />';
			$load = " DELETE FROM tstavke WHERE kolicina > 0 ";
			$mysqli->query($load);
			
	for ($y=70; $y<=99; $y++) {
		for ($l=1; $l<=9; $l++) {
			$load = "LOAD DATA INFILE \"/dbf/prodaja/$y/$l/kasaapp.racun_stavka.csv\" INTO TABLE tstavke FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' (@vreme,ean,kolicina,jm,cenastavke,vrednoststavke,racunbr) SET vreme = str_to_date(@vreme,'%d-%M-%Y %H:%i:%s'), sccode=$y, kasabr=$l";
			$mysqli->query($load);
			
			echo 'Zavrsen import stavki za SCCode';
			echo $y;
			echo 'i kasu broj';
			echo $l;
			echo '<br />';
		}
	}

//Brisanje redova koji nisu u minusu		
			echo '<br />';
			echo 'Brisanje redova koji nisu u minusu za prvih 99 mpo';
			echo '<br />';
			$load = " DELETE FROM tstavke WHERE kolicina > 0 ";
			$mysqli->query($load);
			
	for ($y=100; $y<=120; $y++) {
		for ($l=1; $l<=9; $l++) {
			$load = "LOAD DATA INFILE \"/dbf/prodaja/$y/$l/kasaapp.racun_stavka.csv\" INTO TABLE tstavke FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' (@vreme,ean,kolicina,jm,cenastavke,vrednoststavke,racunbr) SET vreme = str_to_date(@vreme,'%d-%M-%Y %H:%i:%s'), sccode=$y, kasabr=$l";
			$mysqli->query($load);
			
			echo 'Zavrsen import stavki za SCCode';
			echo $y;
			echo 'i kasu broj';
			echo $l;
			echo '<br />';
		}
	}

//Brisanje redova koji nisu u minusu	
			echo '<br />';
			echo 'Brisanje redova koji nisu u minusu do mpo 120';
			echo '<br />';
			$load = " DELETE FROM tstavke WHERE kolicina > 0 ";
			$mysqli->query($load);

	
//Upload File artikal.csv
	$load = "LOAD DATA INFILE \"/dbf/prodaja/artikal.csv\" INTO TABLE artikal FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' (ean,idartikla,nebitna,nazivartikla)";
	$mysqli->query($load);
	echo '<br />';
	echo 'Zavrsen import artikal.csv';
	echo '<br />';

//Popunjavanje kolone kljuc tstavke
	/*$load = " UPDATE tstavke SET vremestring = (SELECT CAST(vreme AS CHAR)) ";
	$mysqli->query($load);
	$load = " UPDATE tstavke SET vremestring = SUBSTRING(vremestring,1,LENGTH(vremestring)-9) ";
	$mysqli->query($load);
	$load = " UPDATE tstavke SET vremestring = (REPLACE(vremestring,'-','')) ";
	$mysqli->query($load);*/
	//$load = " UPDATE tstavke SET kljuc = concat(sccode,kasabr,racunbr,vremestring) ";
	echo '<br />';
	echo 'Pocinje update polja kljuc u tabeli tstavke';
	echo '<br />';
	$load = "UPDATE tstavke SET vremestring = CAST(DATE_FORMAT (tstavke.vreme,'%Y%m%d') AS UNSIGNED)";
	$mysqli->query($load);
	
	/*$load = " UPDATE tstavke SET kljuc = concat(vremestring,'0',sccode,kasabr,racunbr) ";
	$mysqli->query($load);*/
	
	$load = " UPDATE tstavke SET kljuc = concat(vremestring,'00',sccode,kasabr,'000',racunbr) WHERE sccode<=9 and racunbr<=9 ";
	$mysqli->query($load);
	$load = " UPDATE tstavke SET kljuc = concat(vremestring,'00',sccode,kasabr,'00',racunbr) WHERE sccode<=9 and racunbr<=99 and racunbr>9 ";
	$mysqli->query($load);
	$load = " UPDATE tstavke SET kljuc = concat(vremestring,'00',sccode,kasabr,'0',racunbr) WHERE sccode<=9 and racunbr<=999 and racunbr>99 ";
	$mysqli->query($load);
	$load = " UPDATE tstavke SET kljuc = concat(vremestring,'00',sccode,kasabr,racunbr) WHERE sccode<=9 and racunbr>999 ";
	$mysqli->query($load);
		
	$load = " UPDATE tstavke SET kljuc = concat(vremestring,'0',sccode,kasabr,'000',racunbr) WHERE sccode<=99 and sccode>9 and racunbr<=9 ";
	$mysqli->query($load);
	$load = " UPDATE tstavke SET kljuc = concat(vremestring,'0',sccode,kasabr,'00',racunbr) WHERE sccode<=99 and sccode>9 and racunbr<=99 and racunbr>9 ";
	$mysqli->query($load);
	$load = " UPDATE tstavke SET kljuc = concat(vremestring,'0',sccode,kasabr,'0',racunbr) WHERE sccode<=99 and sccode>9 and racunbr<=999 and racunbr>99 ";
	$mysqli->query($load);
	$load = " UPDATE tstavke SET kljuc = concat(vremestring,'0',sccode,kasabr,racunbr) WHERE sccode<=99 and sccode>9 and racunbr>999 ";
	$mysqli->query($load);
		
	$load = " UPDATE tstavke SET kljuc = concat(vremestring,sccode,kasabr,'000',racunbr) WHERE sccode>99 and racunbr<=9 ";
	$mysqli->query($load);
	$load = " UPDATE tstavke SET kljuc = concat(vremestring,sccode,kasabr,'00',racunbr) WHERE sccode>99 and racunbr<=99 and racunbr>9 ";
	$mysqli->query($load);
	$load = " UPDATE tstavke SET kljuc = concat(vremestring,sccode,kasabr,'0',racunbr) WHERE sccode>99 and racunbr<=999 and racunbr>99 ";
	$mysqli->query($load);
	$load = " UPDATE tstavke SET kljuc = concat(vremestring,sccode,kasabr,racunbr) WHERE sccode>99 and racunbr>999 ";
	$mysqli->query($load);
	
	
//Popunjavanje kolone kljuc tglava	
	/*$load = " UPDATE tglava SET vremestring = (SELECT CAST(vreme AS CHAR)) ";
	$mysqli->query($load);
	$load = " UPDATE tglava SET vremestring = SUBSTRING(vremestring,1,LENGTH(vremestring)-9) ";
	$mysqli->query($load);
	$load = " UPDATE tglava SET vremestring = (REPLACE(vremestring,'-','')) ";*/
	echo '<br />';
	echo 'Pocinje update polja kljuc u tabeli tglava';
	echo '<br />';
	$load = "UPDATE tglava SET vremestring = CAST(DATE_FORMAT (tglava.vreme,'%Y%m%d') AS UNSIGNED)";
	$mysqli->query($load);
	
	$load = " UPDATE tglava SET kljuc = concat(vremestring,'00',sccode,kasabr,'000',racunbr) WHERE sccode<=9 and racunbr<=9 ";
	$mysqli->query($load);
	$load = " UPDATE tglava SET kljuc = concat(vremestring,'00',sccode,kasabr,'00',racunbr) WHERE sccode<=9 and racunbr<=99 and racunbr>9 ";
	$mysqli->query($load);
	$load = " UPDATE tglava SET kljuc = concat(vremestring,'00',sccode,kasabr,'0',racunbr) WHERE sccode<=9 and racunbr<=999 and racunbr>99 ";
	$mysqli->query($load);
	$load = " UPDATE tglava SET kljuc = concat(vremestring,'00',sccode,kasabr,racunbr) WHERE sccode<=9 and racunbr>999 ";
	$mysqli->query($load);
		
	$load = " UPDATE tglava SET kljuc = concat(vremestring,'0',sccode,kasabr,'000',racunbr) WHERE sccode<=99 and sccode>9 and racunbr<=9 ";
	$mysqli->query($load);
	$load = " UPDATE tglava SET kljuc = concat(vremestring,'0',sccode,kasabr,'00',racunbr) WHERE sccode<=99 and sccode>9 and racunbr<=99 and racunbr>9 ";
	$mysqli->query($load);
	$load = " UPDATE tglava SET kljuc = concat(vremestring,'0',sccode,kasabr,'0',racunbr) WHERE sccode<=99 and sccode>9 and racunbr<=999 and racunbr>99 ";
	$mysqli->query($load);
	$load = " UPDATE tglava SET kljuc = concat(vremestring,'0',sccode,kasabr,racunbr) WHERE sccode<=99 and sccode>9 and racunbr>999 ";
	$mysqli->query($load);
		
	$load = " UPDATE tglava SET kljuc = concat(vremestring,sccode,kasabr,'000',racunbr) WHERE sccode>99 and racunbr<=9 ";
	$mysqli->query($load);
	$load = " UPDATE tglava SET kljuc = concat(vremestring,sccode,kasabr,'00',racunbr) WHERE sccode>99 and racunbr<=99 and racunbr>9 ";
	$mysqli->query($load);
	$load = " UPDATE tglava SET kljuc = concat(vremestring,sccode,kasabr,'0',racunbr) WHERE sccode>99 and racunbr<=999 and racunbr>99 ";
	$mysqli->query($load);
	$load = " UPDATE tglava SET kljuc = concat(vremestring,sccode,kasabr,racunbr) WHERE sccode>99 and racunbr>999 ";
	$mysqli->query($load);
	
	/*
	/*$load = " UPDATE tglava SET kljuc = concat(vremestring,'00',sccode,kasabr,racunbr) WHERE sccode<=9 ";
	* $mysqli->query($load);
	* $load = " UPDATE tglava SET kljuc = concat(vremestring,'0',sccode,kasabr,racunbr) WHERE sccode<=99 and sccode>9 ";
	* $mysqli->query($load);
	* $load = " UPDATE tglava SET kljuc = concat(vremestring,sccode,kasabr,racunbr) WHERE sccode>99 ";
	
	$load = " UPDATE tglava SET kljuc = concat(sccode,kasabr,racunbr,vremestring) ";
	$mysqli->query($load);*/

// Optimizacija tabela
	$optim = "OPTIMIZE table tstavke";
	$mysqli->query($optim);
	echo '<br />';
	echo 'Izvrsena je optimizacija tabela: tstavke';
	
	$optim = "OPTIMIZE table tglava";
	$mysqli->query($optim);
	echo ', tglava';
	
	$optim = "OPTIMIZE table artikal";
	$mysqli->query($optim);
	echo ', artikal.';
	echo '<br />';

	//rmdir("/dbf/proveradir");
	unlink ("/dbf/proveradir/proverafile.txt");
	echo '<br />';
	echo 'Zavrseno je punjenje baze.';
}else{
//if (file_exists('/dbf/proveradir/proverafile.txt')){
exit ("Vec je u toku punjenje baze!!!");
}
?>
