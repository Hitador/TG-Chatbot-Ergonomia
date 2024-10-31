<?php
// Conecção com o banco de dados
    $host = "localhost";
    $port = "5432";
    $dbname = "bancotg";
    $user = "postgres";
    $password = "1234";

    $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
    if (!$conn) {
        die("Erro na conexão: " . pg_last_error());
    }
?>