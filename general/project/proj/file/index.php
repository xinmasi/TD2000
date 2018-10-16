<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("ПоДїОДµµ");
include_once("inc/header.inc.php");
$PROJ_ID = intval($_GET['PROJ_ID']);
$MENU_LEFT_CONFIGS = Array(
    'href' => '/general/project/file/blank.php',
    'offset' => '300px',
    'framename' => 'file_main'
);
include_once("left.php");
?>