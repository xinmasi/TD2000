<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

//-- 删除目录 --
$query="delete from PROJ_FILE_SORT where SORT_ID='$SORT_ID'";
exequery(TD::conn(),$query);

//-- 删除附件文件 --
$query="select * from PROJ_FILE where SORT_ID='$SORT_ID'";
$cursor=exequery(TD::conn(),$query);

while($ROW=mysql_fetch_array($cursor))
{
  $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
  if($ATTACHMENT_NAME!="")
     delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
}

$query="delete from PROJ_FILE where SORT_ID='$SORT_ID'";
exequery(TD::conn(),$query);

header("location: index.php?PROJ_ID=".$PROJ_ID);
?>
