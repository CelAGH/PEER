<?php

include ('../wp-config.php');

function connection() {
    // serwer
    $mysql_server = "localhost";
    // admin
    $mysql_admin = DB_USER;
    // has³o
    $mysql_pass = DB_PASSWORD;
    // nazwa baza
    $mysql_db = DB_NAME;

    // nawi¹zujemy po³¹czenie z serwerem MySQL
    mysql_connect($mysql_server, $mysql_admin, $mysql_pass)
    or die('Brak po³¹czenia z serwerem MySQL.');
    // ³¹czymy siê z baz¹ danych
    @mysql_select_db($mysql_db)
    or die('B³¹d wyboru bazy danych.');
	

} 


?>