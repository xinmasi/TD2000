<?
/**
*   add.php文件
*
*   文件内容描述：
*   1、保存快讯；
*
*   @author  马舒宁
*
*   @edit_time  2013/10/15
*
*/
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_org.php");
include_once("../../../proj/proj_priv.php");

$time=time();
$content=$_POST['content'];
$name=$_SESSION['LOGIN_UID'];//登陆者的名字
$i_proj_id = $_GET["PROJ_ID"];

$query="insert into proj_news (uid,proj_id,news_time,content) values('$name','$i_proj_id','$time','$content');";
exequery(TD::conn(),$query);

header("location:index.php?PROJ_ID=$i_proj_id");
?>