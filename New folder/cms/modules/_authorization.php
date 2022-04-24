<?
if (@ empty($_SESSION['LoggedUser']['id']) || ! in_array("cms", $_SESSION['LoggedUser']['modules']))
{
	DropError("Доступ закрыт! Используйте логин и пароль для авторизации!<br><a href='$CMS_ROOT'>вернуться в начало</a>");
	exit;
}
?>