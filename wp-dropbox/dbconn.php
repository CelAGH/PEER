<?php

include ('../wp-config.php');

function connection() {
    // serwer
    $mysql_server = "localhost";
    // admin
    $mysql_admin = DB_USER;
    // has�o
    $mysql_pass = DB_PASSWORD;
    // nazwa baza
    $mysql_db = DB_NAME;

    // nawi�zujemy po��czenie z serwerem MySQL
    mysql_connect($mysql_server, $mysql_admin, $mysql_pass)
    or die('Brak po��czenia z serwerem MySQL.');
    // ��czymy si� z baz� danych
    @mysql_select_db($mysql_db)
    or die('B��d wyboru bazy danych.');
	

} 


?>