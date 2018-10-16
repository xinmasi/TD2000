<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("选择会议室");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
$query = "SELECT MR_ID from MEETING_ROOM where find_in_set('".$_SESSION["LOGIN_USER_ID"]."',SECRET_TO_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',OPERATOR) or TO_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID) or (TO_ID='' and SECRET_TO_ID='')";
$cursor= exequery(TD::conn(),$query);
$ROOM_COUNT=0;
$MR_ID_STR = "";
while($ROW=mysql_fetch_array($cursor))
{
    $MR_ID_STR .= $ROW[0].",";
    $ROOM_COUNT++;
}

if($ROOM_COUNT==0)
{
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif" align="absmiddle"><span class="big3"> <?=_("会议室列表")?></span>
    </td>
  </tr>
</table>
<?
Message("",_("无可用的会议室"));
exit;
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif" align="absmiddle"><span class="big3"> <?=_("会议室列表")?></span>
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
    <td nowrap align="center"><?=_("操作")?></td>
  </tr>

<?
$query = "SELECT * from MEETING_ROOM where find_in_set('".$_SESSION["LOGIN_USER_ID"]."',SECRET_TO_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',OPERATOR) or TO_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID) or (TO_ID='' and SECRET_TO_ID='') order by MR_ID";
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
   $OPERATOR_NAME=substr($OPERATOR_NAME,0,-1);
   $query1 = "SELECT USER_NAME from USER where find_in_set(USER_ID,'".$SECRET_TO_ID."')";
   $cursor1= exequery(TD::conn(),$query1);
   while($ROW1=mysql_fetch_array($cursor1))
   {
      $SECRET_TO_NAME.=$ROW1["USER_NAME"].",";
   }
   $SECRET_TO_NAME=substr($SECRET_TO_NAME,0,-1);
   $TOK=strtok($TO_ID,",");
   while($TOK!="")
   {
    $query2 = "SELECT DEPT_NAME from DEPARTMENT where DEPT_ID='$TOK'";
    $cursor2= exequery(TD::conn(),$query2);
    if($ROW=mysql_fetch_array($cursor2))
       $DEPT_NAME.=$ROW["DEPT_NAME"].",";
    $TOK=strtok(",");
   }
   $DEPT_NAME=substr($DEPT_NAME,0,-1);
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
     <td nowrap align="center">
     <a href="javascript:;" onclick="window.open('select.php?MR_ID=<?=$MR_ID?>&MR_ID_STR=<?=$MR_ID_STR?>')"><?=_("申请")?></a> 
	  <!--<a href="select.php?MR_ID=<?=$MR_ID?>" ><?=_("申请")?></a>-->
     </td>
   </tr>
<?
   $OPERATOR_NAME="";
   $SECRET_TO_NAME="";
   $DEPT_NAME="";
}//while
?>
</table>
</body>

</html>
