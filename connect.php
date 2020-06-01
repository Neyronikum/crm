<?
$hostname="localhost";
$username="neyronoway_viber";
$password="wTKSx0i%";
$dbname="neyronoway_viber";
$link = mysqli_connect($hostname,$username, $password, $dbname);
mysqli_options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
if (!$link) {
    file_put_contents("sql_errors.txt", "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL);
	file_put_contents("sql_errors.txt", "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL);
    file_put_contents("sql_errors.txt", "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL);
    exit;
}
