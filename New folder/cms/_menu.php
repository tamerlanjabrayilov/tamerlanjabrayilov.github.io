<? if (in_array("pages",$_SESSION['LoggedUser']['modules'])) { ?> <div class="menu_item" ><a href="pages_list.php" class="menu">Страницы</a></div> <? }?>
<? if (in_array("pages",$_SESSION['LoggedUser']['modules'])) { ?> <div class="menu_item"><a href="menu_list.php" class="menu">Меню</a></div> <? }?>
<? if (in_array("pages",$_SESSION['LoggedUser']['modules'])) { ?> <div class="menu_item"><a href="textvars_list.php" class="menu">Константы</a></div> <? }?>

<br>

<? if (@$_SESSION['LoggedUser']['level']>=50) { ?> <div class="menu_item"><a href="users_list.php" class="menu">Пользователи</a></div> <? }?>
<? if (@$_SESSION['LoggedUser']['level']==100) { ?> <div class="menu_item"><a href="groups_list.php" class="menu">Группы</a></div> <? }?>

<br>

<? if (in_array("banners",$_SESSION['LoggedUser']['modules'])) { ?><div class="menu_item"><a href="banners_list.php" class="menu">Баннеры</a></div> <? }?>


<br /><hr>


<a href="<?=$ROOT?>" class="button" target="_blank">Просмотреть сайт</a>

<a href="logout.php"  class="delete">Выйти из системы</a>
