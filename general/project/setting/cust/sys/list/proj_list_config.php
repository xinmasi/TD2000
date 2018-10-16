<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

$LIST_ARRAY = array();

$CONFIG_FILE = MYOA_ATTACH_PATH."config/project_list.ini";
if(file_exists($CONFIG_FILE))
{
   $PARA_ARRAY = parse_ini_file($CONFIG_FILE);
   if(is_array($PARA_ARRAY))
   {
      $LIST_ARRAY = $PARA_ARRAY;
   }
}
?>