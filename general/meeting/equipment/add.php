<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
if($EQUIPMENT_ID=="")
   $query="insert into MEETING_EQUIPMENT(EQUIPMENT_NO,EQUIPMENT_NAME,EQUIPMENT_STATUS,REMARK,GROUP_NO,GROUP_YN,MR_ID) values('$EQUIPMENT_NO','$EQUIPMENT_NAME','$EQUIPMENT_STATUS','$REMARK','$GROUP_NO','$GROUP_YN','$MR_ID')";
else
   $query = "update MEETING_EQUIPMENT set EQUIPMENT_NO='$EQUIPMENT_NO', EQUIPMENT_NAME='$EQUIPMENT_NAME', EQUIPMENT_STATUS='$EQUIPMENT_STATUS',GROUP_YN='$GROUP_YN',GROUP_NO='$GROUP_NO',REMARK='$REMARK',MR_ID='$MR_ID' where EQUIPMENT_ID='$EQUIPMENT_ID'";
exequery(TD::conn(),$query);	
Message(_("提示"),_("保存成功！"));

if($EQUIPMENT_ID!="")
   $BACK_VALUE="-2";
else
   $BACK_VALUE="-1";
   
if($ZHEFROM==1)
{
?>
<div align="center">
  <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='query.php'">
</div>
<?
}else{
?>
<div align="center">
  <input type="button" value="<?=_("返回")?>" class="BigButton" onclick="history.go('<?=$BACK_VALUE?>');">
</div>
<?
}
?>
</body>
</html>