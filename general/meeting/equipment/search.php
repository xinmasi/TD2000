<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$CONDITION_STR="";
if($EQUIPMENT_NO!="")
   $CONDITION_STR.=" and EQUIPMENT_NO like '%".$EQUIPMENT_NO."%'";
if($EQUIPMENT_NAME!="")
   $CONDITION_STR.=" and EQUIPMENT_NAME like '%".$EQUIPMENT_NAME."%'";
if($EQUIPMENT_STATUS!="")
   $CONDITION_STR.=" and EQUIPMENT_STATUS='$EQUIPMENT_STATUS'";
if($REMARK!="")
   $CONDITION_STR.=" and REMARK like '%$REMARK%'";
if($MR_ID!="")
   $CONDITION_STR.=" and MR_ID='$MR_ID'";   
   

$HTML_PAGE_TITLE = _("会议设备查询");
include_once("inc/header.inc.php");
?>


<script>
function delete_equipment(EQUIPMENT_ID)
{
  msg='<?=_("确认要删除该设备吗？")?>';
  if(window.confirm(msg))
  {
     URL="delete.php?start=<?=$start?>&ZHEFROM=1&EQUIPMENT_ID=" + EQUIPMENT_ID;
     window.location=URL;
  }
}
</script>

<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("设备查询结果")?></span><br>
    </td>
  </tr>
</table>

<?
$query = "SELECT * from MEETING_EQUIPMENT where 1=1 ".$CONDITION_STR."order by EQUIPMENT_NO";
$cursor=exequery(TD::conn(),$query);
$EQUIPMENT_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
	 $EQUIPMENT_COUNT++;
   $EQUIPMENT_ID=$ROW["EQUIPMENT_ID"];
   $EQUIPMENT_NO=$ROW["EQUIPMENT_NO"];
   $EQUIPMENT_NAME=$ROW["EQUIPMENT_NAME"];  
   $EQUIPMENT_STATUS=$ROW["EQUIPMENT_STATUS"];
   $GROUP_YN=$ROW["GROUP_YN"];  
   $REMARK=$ROW["REMARK"];	
   $GROUP_NO=$ROW["GROUP_NO"];		
   $MR_ID=$ROW["MR_ID"];	 
   $M_ROOM_NAME="";
   $query = "SELECT MR_NAME from MEETING_ROOM where MR_ID='$MR_ID'";
   $cursor1= exequery(TD::conn(),$query);
   if($ROW1=mysql_fetch_array($cursor1))
      $M_ROOM_NAME=$ROW1["MR_NAME"];    
  
  if($EQUIPMENT_STATUS==1)
     $STATUS_DESC=_("可用");
  else
     $STATUS_DESC=_("不可用");
	if($EQUIPMENT_COUNT==1)
	{
?>
<table class="TableList" width="90%" align="center">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("设备编号")?></td>
      <td nowrap align="center"><?=_("设备名称")?></td>
      <td nowrap align="center"><?=_("所属会议室")?></td>      
      <td nowrap align="center"><?=_("设备状态")?></td>
      <td nowrap align="center"><?=_("同类设备名称")?></td>      
      <td nowrap align="center"><?=_("设备描述")?></td>
      <td nowrap align="center"><?=_("操作")?></td>  
  </tr>
<?
}

  if($EQUIPMENT_COUNT%2==1)
     $TableLine="TableLine1";
  else
     $TableLine="TableLine2";
?>
  <tr class="<?=$TableLine?>">
      <td nowrap align="center"><?=$EQUIPMENT_NO?></td>
      <td nowrap align="center"><?=$EQUIPMENT_NAME?></td>  
      <td nowrap align="center"><?=$M_ROOM_NAME?></td>           
      <td nowrap align="center"><?=$STATUS_DESC?></td>
      <td nowrap align="center"><?=get_code_name($GROUP_NO,"MEETING_EQUIPMENT");?></td>      
      <td nowrap align="center"><?=$REMARK?></td>
      <td nowrap align="center">
      	<a href="new.php?EQUIPMENT_ID=<?=$EQUIPMENT_ID?>&start=<?=$start?>&ZHEFROM=1"><?=_("编辑")?></a> 
      	<a href="javascript:delete_equipment('<?=$EQUIPMENT_ID?>');"><?=_("删除")?></a>
      </td>      
<?
}
if($EQUIPMENT_COUNT>0)
{
?>
</table> 
<?
}else{
	Message(_("提示"),_("无相关记录"));
}
?>
<br><center><input type="button" class="BigButton" value="<?=_("返回")?>" onclick="history.back();"></center>
</body>
</html>