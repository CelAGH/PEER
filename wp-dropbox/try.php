<?php

setcookie("peeruserislogged", "Yes", time()+3600);
setcookie("peeroverlaydisplay", "Yes");
setcookie("peerrepomainpage", $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
?>