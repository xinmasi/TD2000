<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/header.inc.php");
include_once("get_diary_data.func.php");

ob_end_clean();

$login_user_id=$_SESSION["LOGIN_USER_ID"];

if($_GET['op']=='add')
{
	$query  = "SELECT USER_ID FROM diary_top WHERE DIA_CATE = 1 AND DIA_ID = ".$_GET["diary_id"];	
    $cursor = exequery(TD::conn(),$query);
    if($ROW = mysql_fetch_array($cursor))
    {
        $USER_ID = $ROW["USER_ID"];
    }
	if($USER_ID)
	{
		$user   = $USER_ID.$login_user_id.',';
		$query1 = "UPDATE diary_top set USER_ID='$user' WHERE DIA_CATE = 1 AND DIA_ID=".$_GET["diary_id"];
	}
	else
	{
		$user = $login_user_id.",";
		$query1 = "INSERT INTO diary_top (TOP_ID,DIA_CATE,USER_ID,DIA_ID) VALUES ('',1,'".$user."','$_GET[diary_id]');";
	}
	exequery(TD::conn(),$query1);
	//file_put_contents('a.txt',$TOP_ID);
	echo "ok";
	exit;
}
if($_GET['op']=='del')
{
	$user  = $login_user_id.',';
	$query = "UPDATE diary_top set USER_ID = REPLACE(USER_ID,'$user','') WHERE DIA_CATE = 1 AND DIA_ID = ".$_GET["diary_id"];
	exequery(TD::conn(),$query);
	
	$query1  = "SELECT USER_ID FROM diary_top WHERE DIA_CATE = 1 AND DIA_ID = ".$_GET["diary_id"];	
	$cursor1 = exequery(TD::conn(),$query1);
 	if ($row = mysql_fetch_array($cursor1))
	{
		$USER_ID = $row ["USER_ID"];
	}
	if($USER_ID=="")
	{
		$del = "DELETE FROM diary_top WHERE DIA_CATE = 1 AND DIA_ID = ".$_GET["diary_id"];
		exequery(TD::conn(),$del);
	}
	echo "ok";
	exit;
}

?>