<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

$query = "delete from DIARY_COMMENT where COMMENT_ID='$COMMENT_ID' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
exequery(TD::conn(),$query);

$query = "delete from DIARY_COMMENT_REPLY where COMMENT_ID='$COMMENT_ID'";
exequery(TD::conn(),$query);

if($FROMUD==1)
{
   header("location: user_diary.php?USER_ID=$USER_ID&DEPT_ID=$DEPT_ID&IS_MAIN=1");	 
}
else
{
   if($FROMUD==10)
      header("location: diary_body.php?IS_MAIN=1");
   else
   {
      $SUBJECT = urlencode($SUBJECT);
      header("location: read.php?DIA_ID=$DIA_ID&USER_NAME=$USER_NAME&FROM=$FROM&BEGIN_DATE=$BEGIN_DATE&END_DATE=$END_DATE&SUBJECT=$SUBJECT&IS_MAIN=1");
   }
}
?>
