<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");

$MENU_LEFT=array();

$target="user_main";
$user_list=array(
"PARA_URL1" => "/general/hr/integral/query/point.php",
"PARA_URL2" => "/general/hr/integral/query/point_specific.php",
"PARA_TARGET" => $target,
"PRIV_NO_FLAG" => "3",
"MANAGE_FLAG" => "1",
"xname" => "system_user",
"showButton" => "0",
"include_file" => "inc/user_list/index.php");

$MENU_LEFT[count($MENU_LEFT)] = array("text" => _("全部人员"), "href" => "", "onclick" => "clickMenu", "target" => $target, "title" => _("点击伸缩列表"), "img" => "", "module" => $user_list, "module_style" => "");

$query = "SELECT * from USER where USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $POST_PRIV=$ROW["POST_PRIV"];

if($POST_PRIV=="1")
{
   $MENU_LEFT[count($MENU_LEFT)] = array("text" => _("离职人员/外部人员"), "href" => "search.php?DEPT_ID=0", "onclick" => "clickMenu", "target" => $target, "title" => "", "img" => "", "module" => "", "module_style" => "");
}


include_once("inc/menu_left.php");
?>
<style>
html,body{
    overflow: hidden;
    height: 100%;
}

#center iframe{
    width: 100%;
    height: 100%;
    display: block;
    position: absolute;
    top:0;
    bottom:0;
    left:0;
    right:0;
} 
</style>
<body>
<div id="center">    
    <iframe name="user_main" scrolling="auto" src="search.php?TO_ID=<?=$TO_ID?>&SEX=<?=$SEX?>&DEPT_ID=<?=$DEPT_ID?>&USER_PRIV=<?=$USER_PRIV?>" frameborder="0"></iframe>
</div>
</body>