<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("会议室设置");
include_once("inc/header.inc.php");
?>

<script>
function delete_mr(MR_ID)
{
 msg='<?=_("确认要删除该会议室吗？")?>';
 if(window.confirm(msg))
 {
    URL="delete.php?MR_ID=" + MR_ID;
    window.location=URL;
 }
}

function delete_all()
{
 msg='<?=_("确认要删除所有会议室吗？")?>';
 if(window.confirm(msg))
 {
    URL="delete_all.php";
    window.location=URL;
 }
}
</script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("新建会议室")?></span>
    </td>
  </tr>
</table>
<div align="center">
  <input type="button"  value="<?=_("新建会议室")?>" class="BigButton" onClick="location='new_room.php';" title="<?=_("新建会议室，并添加基本信息")?>">
</div>
<br>

<?
$query = "SELECT count(*) from MEETING_ROOM";
$cursor= exequery(TD::conn(),$query);
$ROOM_COUNT=0;
if($ROW=mysql_fetch_array($cursor))
   $ROOM_COUNT=$ROW[0];

if($ROOM_COUNT==0)
{
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("管理会议室")?></span>
    </td>
  </tr>
</table>
<?
Message("",_("无定义的会议室"));
exit;
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("管理会议室")?></span>
    </td>
		<?
  		$MSG_ROOM_COUNT=sprintf(_("共%s个会议室"),"<span class='big4'>&nbsp;".$ROOM_COUNT."</span>&nbsp;");
  	?>
    <td valign="bottom" class="small1"><?=$MSG_ROOM_COUNT ?>
    </td>
  </tr>
</table>
<table class="TableList" width="95%" align="center">
  <tr class="TableHeader">
    <td nowrap align="center"><?=_("名称")?></td>
    <td nowrap align="center"><?=_("可容纳人数")?></td>
    <td nowrap align="center"><?=_("设备情况")?></td>
    <td nowrap align="center"><?=_("所在地点")?></td>
    <td nowrap align="center"><?=_("会议室描述")?></td>
    <td nowrap align="center"><?=_("会议室管理员")?></td>
    <td nowrap align="center"><?=_("申请权限(部门)")?></td>
    <td nowrap align="center"><?=_("申请权限(人员)")?></td>
    <td nowrap align="center"><?=_("操作")?></td>
  </tr>

<?
$query = "SELECT * from MEETING_ROOM order by MR_ID";
$cursor= exequery(TD::conn(),$query);
$ROOM_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $ROOM_COUNT++;

   $MR_ID=$ROW["MR_ID"];
   $MR_NAME=$ROW["MR_NAME"];
   $MR_CAPACITY=$ROW["MR_CAPACITY"];
   $MR_DEVICE=$ROW["MR_DEVICE"];
   $MR_DESC=$ROW["MR_DESC"];
   $MR_PLACE=$ROW["MR_PLACE"];
   $OPERATOR=$ROW["OPERATOR"];
   $TO_ID=$ROW["TO_ID"];
   $SECRET_TO_ID=$ROW["SECRET_TO_ID"];
   $query1 = "SELECT USER_NAME from USER where find_in_set(USER_ID,'".$OPERATOR."')";
   $cursor1= exequery(TD::conn(),$query1);
   while($ROW1=mysql_fetch_array($cursor1))
   {
      $OPERATOR_NAME.=$ROW1["USER_NAME"].",";
   }
   $query1 = "SELECT USER_NAME from USER where find_in_set(USER_ID,'".$SECRET_TO_ID."')";
   $cursor1= exequery(TD::conn(),$query1);
   while($ROW1=mysql_fetch_array($cursor1))
   {
      $SECRET_TO_NAME.=$ROW1["USER_NAME"].",";
   }
   $TOK=strtok($TO_ID,",");
   while($TOK!="")
   {
    $query2 = "SELECT DEPT_NAME from DEPARTMENT where DEPT_ID='$TOK'";
    $cursor2= exequery(TD::conn(),$query2);
    if($ROW=mysql_fetch_array($cursor2))
       $DEPT_NAME.=$ROW["DEPT_NAME"].",";
    $TOK=strtok(",");
   }
   
   if($TO_ID=="ALL_DEPT")
      $DEPT_NAME=_("全体部门");

   if($ROOM_COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";
      
   if(strlen($MR_DESC)>15)
   {
      $MR_DESC_SHORT= csubstr($MR_DESC,0,15);
      $MR_DESC_SHORT=$MR_DESC_SHORT."......";
   }
   else
      $MR_DESC_SHORT=$MR_DESC;
?>

   <tr class="<?=$TableLine?>">
     <td nowrap align="center"><?=$MR_NAME?></td>
     <td nowrap align="center"><?=$MR_CAPACITY?></a></td>
     <td align="center"><?=$MR_DEVICE?></a></td>
     <td align="center"><?=$MR_PLACE?></td>
     <td align="center" title="<?=$MR_DESC?>"><? if($MR_DESC!="") echo $MR_DESC_SHORT;?> </td>   
     <td align="center"><?=$OPERATOR_NAME?></td>  
     <td align="center"><?=$DEPT_NAME?></td>   
     <td align="center"><?=$SECRET_TO_NAME?></td> 
     <td nowrap align="center">
     <a href="new_room.php?MR_ID=<?=$MR_ID?>"> <?=_("修改")?></a>&nbsp;&nbsp;&nbsp;
     <a href="javascript:delete_mr('<?=$MR_ID?>');"> <?=_("删除")?></a>
     </td>
   </tr>
<?
   $OPERATOR_NAME="";
   $SECRET_TO_NAME="";
   $DEPT_NAME="";
}//while
?>
<tr class="TableControl">
 <td colspan="9" align="center">
    <input type="button"  value="<?=_("全部删除")?>" class="SmallButton" onClick="delete_all()" title="<?=_("删除所有会议室")?>">
 </td>
</tr>
</table>
</body>

</html>
