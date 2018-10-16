<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("借阅查询");
include_once("inc/header.inc.php");

if($ROOM_NAME!="")
{
    $ROOM_ID_STR="";
    $query = "SELECT ROOM_ID from RMS_ROLL_ROOM where (VIEW_DEPT_ID='ALL_DEPT' or VIEW_DEPT_ID='' or VIEW_DEPT_ID is null or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',VIEW_DEPT_ID))".dept_other_sql("VIEW_DEPT_ID")." and ROOM_NAME like '%$ROOM_NAME%'";
    $cursor= exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $ROOM_ID_STR.=$ROW["ROOM_ID"].",";
    }
}
else
{
    $ROOM_ID_STR="";
    $query = "SELECT ROOM_ID from RMS_ROLL_ROOM where (VIEW_DEPT_ID='ALL_DEPT' or VIEW_DEPT_ID='' or VIEW_DEPT_ID is null or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',VIEW_DEPT_ID))".dept_other_sql("VIEW_DEPT_ID");
    $cursor= exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $ROOM_ID_STR.=$ROW["ROOM_ID"].",";
    }
}

if($ROOM_ID_STR!="")
{
    $ROLL_ID_STR="";
    if($ROLL_NAME!="")
        $query = "SELECT ROLL_ID from RMS_ROLL  where find_in_set(ROOM_ID,'$ROOM_ID_STR') and ROLL_NAME like '%$ROLL_NAME%'";
    else
        $query = "SELECT ROLL_ID from RMS_ROLL  where find_in_set(ROOM_ID,'$ROOM_ID_STR')";
    $cursor= exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $ROLL_ID_STR.=$ROW["ROLL_ID"].",";
    }
}
else 
{
    Message("提示",_("无可以申请借阅的文件！"));
    exit;
}
if($ROLL_ID_STR=="")
{
    Message("提示",_("无可以申请借阅的文件！"));
    exit;
}

$WHERE_STR = "";
if($FILE_CODE!="")
    $WHERE_STR = " and FILE_CODE like '%$FILE_CODE%'";
if($FILE_SUBJECT!="")
    $WHERE_STR .= " and FILE_SUBJECT like '%$FILE_SUBJECT%'";
if($FILE_TITLE!="")
    $WHERE_STR .= " and FILE_TITLE like '%$FILE_TITLE%'";
if($FILE_TITLE0!="")
    $WHERE_STR .= " and FILE_TITLE0 like '%$FILE_TITLE0%'";
if($SEND_UNIT!="")
    $WHERE_STR .= " and SEND_UNIT like '%$SEND_UNIT%'";
if($REMARK!="")
    $WHERE_STR .= " and REMARK like '%$REMARK%'";
$ROLL_ID_STR=td_trim($ROLL_ID_STR);
if($ROLL_ID_STR!="")
    $WHERE_STR .= " and find_in_set(ROLL_ID,'$ROLL_ID_STR')";
    
//    $WHERE_STR = stripslashes($WHERE_STR);
//decode_base64
//encode_base64
//encodeURIComponent

$HTML_PAGE_TITLE = _("查询结果");
include_once("inc/header.inc.php");
?>

<script>
function open_file(FILE_ID,ISAUDIT)
{
 if(ISAUDIT==0)
	  URL="./read_file.php?FILE_ID="+FILE_ID;
 else
    URL="./read_file0.php?FILE_ID="+FILE_ID;
 myleft=(screen.availWidth-500)/2;
 mytop=150
 mywidth=550;
 myheight=300;
 window.open(URL,"read_file","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}

function lend_file(FILE_ID,CUR_PAGE,ROLL_ID)
{
 msg='<?=_("确认要借阅该项文件吗？")?>';
 if(window.confirm(msg))
 {
  URL="./lend.php?ROLL_ID=" + ROLL_ID + "&FILE_ID=" + FILE_ID + "&CUR_PAGE=" + CUR_PAGE + "&FROM_SEARCH=1&ROOM_NAME=<?=$ROOM_NAME?>&FILE_CODE=<?=$FILE_CODE?>&FILE_SUBJECT=<?=$FILE_SUBJECT?>&FILE_TITLE=<?=$FILE_TITLE?>&FILE_TITLE0=<?=$FILE_TITLE0?>&SEND_UNIT=<?=$SEND_UNIT?>&REMARK=<?=$REMARK?>&ROLL_NAME=<?=$ROLL_NAME?>";
  window.location=URL;
 }
}

</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/roll_manage.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("查询结果")?></span><br>
    </td>
  </tr>
</table>

<br>

<?
$query = "SELECT * from RMS_FILE where DEL_USER=''".$WHERE_STR." order by FILE_CODE desc";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
  $RMS_FILE_COUNT++;
  $FILE_ID    = $ROW["FILE_ID"];
  $FILE_ID    = $ROW["FILE_ID"];  
  $FILE_CODE  = $ROW["FILE_CODE"];
  $FILE_TITLE = $ROW["FILE_TITLE"];
  $SECRET     = $ROW["SECRET"];
  $ROLL_ID    = $ROW["ROLL_ID"];
  $SEND_DATE  = $ROW["SEND_DATE"];
  $URGENCY    = $ROW["URGENCY"];
  $ISAUDIT    = $ROW["ISAUDIT"];
  $SEND_UNIT  = $ROW["SEND_UNIT"];

  $FILE_TITLE=td_htmlspecialchars($FILE_TITLE);
  if($SEND_DATE=='0000-00-00') $SEND_DATE='';

  $SECRET=get_code_name($SECRET,"RMS_SECRET");
  $URGENCY=get_code_name($URGENCY,"RMS_URGENCY");

  if($RMS_FILE_COUNT==1)
  {
?>
<form action="?"  method="post" name="form1">
<table class="TableList" width="100%">
  <tr class="TableHeader">
      <td nowrap align="center"><u><?=_("文件号")?></u></td>
      <td nowrap align="center"><u><?=_("文件标题")?></u></td>
      <td nowrap align="center"><?=_("密级")?></td>
      <td nowrap align="center"><u><?=_("发文单位")?></u></td>
      <td nowrap align="center"><u><?=_("发文时间")?></u></td>
      <td nowrap align="center"><?=_("紧急等级")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
    </tr>
<?
  }  
  if($RMS_FILE_COUNT%2==1)
     $TableLine="TableLine1";
  else
     $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
      <td align="center">
      <a href="javascript:open_file('<?=$FILE_ID?>','<?=$ISAUDIT?>');"><?=$FILE_CODE?></a>
      </td>
      <td nowrap align="center"><?=$FILE_TITLE?></td>
      <td nowrap align="center"><?=$SECRET?></td>
      <td nowrap align="center"><?=$SEND_UNIT?></td>
      <td nowrap align="center"><?=$SEND_DATE?></td>
      <td nowrap align="center"><?=$URGENCY?></td>
      <td nowrap align="center">
<?
if($ISAUDIT==1)
{
?>
      <a href="javascript:lend_file('<?=$FILE_ID?>','<?=$CUR_PAGE?>','<?=$ROLL_ID?>');"> <?=_("借阅")?></a>
<?
}else{
?>
      <?=_("不需借阅")?>
<?
}
?>
      </td>
    </tr>
<?
}

if($RMS_FILE_COUNT == 0)
   Message("",_("无文件"));
?>
</table>
</form>	
<br>
<center><input type="button" class="BigButton" value="<?=_("返回")?>" onClick="location='query.php'"></center>
<br>
</body>

</html>
