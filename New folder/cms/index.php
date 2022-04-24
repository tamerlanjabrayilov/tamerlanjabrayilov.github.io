<?
	include ("_configs.php");
	if ($_SERVER['HTTP_HOST']!=substr($ROOT,7,-1))
	{
		header("Location: ${ROOT}cms/");
	}

	//check login info
	CheckLogin();

	$title = "Система управления сайтом. Авторизация";
	include ("cms_template/_top.php");

	if (!empty($_SESSION['LoggedUser']['id']) && in_array("cms", $_SESSION['LoggedUser']['modules']))
	{
?>

		<h1>Добро пожаловать, <?=$_SESSION['LoggedUser']['name']?>!</h1>
		<br>
		<div>С помощью данной панели управления вы можете в удобной и привычной форме вносить изменения на сайт: редктировать тексты, менять описание страниц и многое другое. Для того, чтобы начать - выберите соответствующий пункт меню.</div><br>
<?
	}
	else
	{
?>
		<form name="CMSLoginForm" method="post" action="index.php">
		
			<div class="eitem">
				<div class="eitem1">
					Логин (E-Mail):
				</div>
				<div class="eitem2">
					<input name="email" type="text" width="30">
				</div>
			</div>
			
			<div class="eitem">
				<div class="eitem1">
					Пароль:
				</div>
				<div class="eitem2">
					<input name="password" type="password" width="30">
				</div>
			</div>
			

			<div class="eitem">
				<div class="eitem1">
					
				</div>
				<div class="eitem2">
					<input class="submit" name="submit" type="submit"  value="Войти" style="width:80px;">
				</div>
			</div>

			<a href='logout.php' class="delete">Удалить сессию</a>		
			<a href='../index.php' class="button">Вернуться на сайт</a>
		</form>
        
        <br /><br />
		<h1>Внимание!</h1>
        <h3>Для того, чтобы у вас корректно работала система управления, рекомендуем использовать для работы с ней <a href="https://www.google.ru/intl/ru/chrome/browser/">браузер Google Chrome</a></h3><br>
        У вас появится поддержа орфографии, корректное отображение элементов сайта в поле редактора и многое другое...
        <br /><br />




<?		
	}		
	include ("cms_template/_bottom.php");
?>
