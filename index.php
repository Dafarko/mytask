<?php
    ini_set('display_errors', 1);
    //error_reporting('E_ALL');
    require_once 'application/core/DB.php'; // подключаем скрипт
	// подключаемся к серверу
	$link = mysqli_connect($host, $user, $password, $database) 
    or die("Ошибка " . mysqli_error($link));
 
	// выполняем операции с базой данных

	// закрываем подключение
	mysqli_close($link);
	
    require_once 'application/bootstrap.php';
?>