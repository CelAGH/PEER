<?php

$cos = '[peer_doodle poll_id=48]<p></p>[peer_doodle poll_id=47]<p></p>[peer_doodle poll_id=46]<p></p>[hint text="Select the event you want to participate and share your prefereces with Community Members"][peer_doodle poll_id=48][peer_doodle poll_id=48][peer_doodle poll_id=48][peer_doodle poll_id=48]
';

echo $cos;
echo "<br><br>";
$hint = strstr($cos, "[hint ");

$cosl = strlen($cos);
$hintl = strlen($hint);
$cosret = $cosl - $hintl;

$poczatek = substr($cos, 0, $cosret);

$ahint = strstr($hint, "[peer");
$ahintl = strlen($ahint);
$ahintl = $ahintl *-1;
$onlyhint = substr($hint, 0, $ahintl);

echo $poczatek.$onlyhint.$ahint;
?>