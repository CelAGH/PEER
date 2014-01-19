<?php
$ciastko = $_COOKIE["peeroverlaydisplay"];

if ($ciastko=="No"){
	setcookie ("peeroverlaydisplay", "Yes");
	echo "ustawilem ciacho";
       header ("Location: ./app.php");
} else {
header ("Location: ./app.php");
}
?>