<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Storno racuni</title>
<style type="text/css">
body {
	background: #E3F4FC;
	font: normal 14px/30px Helvetica, Arial, sans-serif;
	color: #2b2b2b;
}
a {
	color:#898989;
	font-size:14px;
	font-weight:bold;
	text-decoration:none;
}
a:hover {
	color:#CC0033;
}

h1 {
	font: bold 14px Helvetica, Arial, sans-serif;
	color: #CC0033;
}
h2 {
	font: bold 14px Helvetica, Arial, sans-serif;
	color: #898989;
}
#container {
	background: #CCC;
	margin: 100px auto;
	width: 945px;
}
#form 			{padding: 20px 150px;}
#form input     {margin-bottom: 20px;}
</style>
</head>
<body>

<?php

echo "<b>Storno racuni</b>";
echo "\n";
echo "\t";
print '<br />';
echo "\n";
echo 'Program za pretragu storno racuna';

?>

<div id="container">
<div id="form">

<p align="right"><a href="/storno/azuriraj.php"><button>Azuriranje podataka...</button></a></p>
<br />
<p align="left"><a href="/storno/svimpodatum-sum.php"><button>Izvestaj 1 - pregled storno stavki za sve mpo po datumu	...</button></a></p>
<br />
<p align="left"><a href="/storno/stornovrednrac.php"><button>Izvestaj 2 - pregled storno stavki po sccode, operateru	...</button></a></p>
<br />
<p align="left"><a href="/storno/prometposatima-join2.php"><button>Izvestaj 3 - pregled prometa po satima za sve mpo	...</button></a></p>

</div>
</div>
</body>
</html>