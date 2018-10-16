<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
ob_start();

include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?

//变量替换，名字简化，带有后缀“_FORM2”的变量是从隐藏的form2里提交过来的
$PROJ_NUM = $PROJ_NUM_FORM2;
$PROJ_ID = $PROJ_ID_FORM2;
$PROJ_NAME = $PROJ_NAME_FORM2;
$PROJ_START_TIME = $PROJ_START_TIME_FORM2;
$PROJ_END_TIME = $PROJ_END_TIME_FORM2;
$PROJ_TYPE = $PROJ_TYPE_FORM2;
$PROJ_MANAGER = $PROJ_MANAGER_FORM2;
$PROJ_DEPT = $PROJ_DEPT_FORM2;
$PROJ_VIEWER = $PROJ_VIEWER_FORM2;
$PROJ_DESCRIPTION = $PROJ_DESCRIPTION_FORM2;

$ATTACHMENT_ID = $DEL_ATTACHMENT_ID_FORM2;
$ATTACHMENT_NAME = $DEL_ATTACHMENT_NAME_FORM2;

//删除附件之前,先对项目基本信息进行保存 by dq 090629
$query = "UPDATE `PROJ_PROJECT` SET `PROJ_NAME` = '$PROJ_NAME', `PROJ_NUM` = '$PROJ_NUM', `PROJ_DESCRIPTION` = '$PROJ_DESCRIPTION', `PROJ_TYPE` = '$PROJ_TYPE', `PROJ_DEPT` = '$PROJ_DEPT', `PROJ_UPDATE_TIME` = '$CUR_TIME', `PROJ_START_TIME` = '$PROJ_START_TIME', `PROJ_END_TIME` = '$PROJ_END_TIME', `PROJ_LEADER` = '$PROJ_LEADER', `PROJ_VIEWER` = '$PROJ_VIEWER', `PROJ_MANAGER` = '$PROJ_MANAGER' WHERE `PROJ_ID` = '$PROJ_ID'";
exequery(TD::conn(),$query);

$query="select * from PROJ_PROJECT where PROJ_ID='$PROJ_ID'";
$cursor=exequery(TD::conn(),$query);
$ATTACHMENT_NAME_OLD="";
if($ROW=mysql_fetch_array($cursor))
{
  $ATTACHMENT_ID_OLD=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME_OLD=$ROW["ATTACHMENT_NAME"];
}

if($ATTACHMENT_NAME_OLD!="")
{
   delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);

   $ATTACHMENT_ID_ARRAY=explode(",",$ATTACHMENT_ID_OLD);
   $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACHMENT_NAME_OLD);

   $ARRAY_COUNT=sizeof($ATTACHMENT_ID_ARRAY);
   for($I=0;$I<$ARRAY_COUNT;$I++)
   {
       if($ATTACHMENT_ID_ARRAY[$I]==$ATTACHMENT_ID||$ATTACHMENT_ID_ARRAY[$I]=="")
          continue;
       $ATTACHMENT_ID1.=$ATTACHMENT_ID_ARRAY[$I].",";
       $ATTACHMENT_NAME1.=$ATTACHMENT_NAME_ARRAY[$I]."*";
   }
   $ATTACHMENT_ID=$ATTACHMENT_ID1;
   $ATTACHMENT_NAME=$ATTACHMENT_NAME1;
   
   $query="update PROJ_PROJECT set ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME' where PROJ_ID='$PROJ_ID'";
   exequery(TD::conn(),$query);
}
?>
<script>
   parent.reload_page();   
</script>
</body>
</html>
