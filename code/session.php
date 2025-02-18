<?php 
session_start();
$_SESSION['login'] = 'admin';

echo $_SESSION['login'] = 'admin';
unset($_SESSION['message']);//Удаление одно записи
session_destroy(); //Удаляет сессию
session_regenerate_id(); //Перелогиниться