<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");


$HTML_PAGE_TITLE = _("案卷管理");
include_once("inc/header.inc.php");
?>



<script>
function open_roll(ROLL_ID,FORMAT)
{
 URL="../show/read_roll.php?ROLL_ID="+ROLL_ID;
 myleft=(screen.availWidth-500)/2;
 mytop=150
 mywidth=550;
 myheight=400;
 if(FORMAT=="1")
 {
    myleft=0;
    mytop=0
    mywidth=screen.availWidth-10;
    myheight=screen.availHeight-40;
 }
 window.open(URL,"read_roll","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}
function order_by(field,asc_desc)
{
 window.location="index1.php?CUR_PAGE=<?=$CUR_PAGE?>&TYPE=<?=$TYPE?>&FIELD="+field+"&ASC_DESC="+asc_desc;
}
</script>


<body class="bodycolor">

<?
 if($_SESSION["LOGIN_USER_PRIV"]=="1")
    $query = "SELECT count(*) from RMS_ROLL where 1=1";
 else
    $query = "SELECT count(*) from RMS_ROLL where ADD_USER='".$_SESSION["LOGIN_USER_ID"]."'";
  
 $cursor= exequery(TD::conn(),$query);
 $RMS_ROLL_COUNT=0;
 if($ROW=mysql_fetch_array($cursor))
    $RMS_ROLL_COUNT=$ROW[0];

 if($RMS_ROLL_COUNT==0)
 {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("文件组卷")?></span>&nbsp;
    </td>
  </tr>
</table>
<br>

<?
   Message("",_("无已发布的案卷"));
   exit;
 }
 
 $PER_PAGE=15;
 $PAGES=10;
 $PAGE_COUNT=ceil($RMS_ROLL_COUNT/$PER_PAGE);

 if($CUR_PAGE<=0 || $CUR_PAGE=="")
    $CUR_PAGE=1;
 if($CUR_PAGE>$PAGE_COUNT)
    $CUR_PAGE=$PAGE_COUNT;

?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("管理案卷")?></span>&nbsp;
    </td>
    <td align="right" valign="bottom" class="small1"><?=sprintf(_("共 %s 条"), '<span class="big4">'.$RMS_ROLL_COUNT.'</span>')?>
    </td>
    <td align="right" valign="bottom" class="small1">
       <a class="A1" href="index1.php?CUR_PAGE=1&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=_("首页")?></a>&nbsp;
       <a class="A1" href="index1.php?CUR_PAGE=<?=$PAGE_COUNT?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=_("末页")?></a>&nbsp;&nbsp;
<?
if($CUR_PAGE%$PAGES==0)
   $J=$PAGES;
else
   $J=$CUR_PAGE%$PAGES;

if($CUR_PAGE> $PAGES)
{
?>
       <a class="A1" href="index1.php?CUR_PAGE=<?=$CUR_PAGE-$J-$PAGES+1?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=sprintf(_("上%s页"), $PAGES)?></a>&nbsp;&nbsp;
<?
}

for($I=$CUR_PAGE-$J+1;$I<=$CUR_PAGE-$J+$PAGES;$I++)
{
   if($I>$PAGE_COUNT)
      break;

   if($I==$CUR_PAGE)
   {
?>
       [<?=$I?>]&nbsp;
<?
   }
   else
   {
?>
       [<a class="A1" href="index1.php?CUR_PAGE=<?=$I?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=$I?></a>]&nbsp;
<?
   }
}
?>
      &nbsp;
<?
if($I-1< $PAGE_COUNT)
{
?>
       <a class="A1" href="index1.php?CUR_PAGE=<?=$I?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=sprintf(_("下%s页"), $PAGES)?></a>&nbsp;&nbsp;
<?
}
if($CUR_PAGE-1>=1)
{
?>
       <a class="A1" href="index1.php?CUR_PAGE=<?=$CUR_PAGE-1?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=_("上一页")?></a>&nbsp;
<?
}
else
{
?>
       <?=_("上一页")?>&nbsp;
<?
}

if($CUR_PAGE+1<= $PAGE_COUNT)
{
?>
       <a class="A1" href="index1.php?CUR_PAGE=<?=$CUR_PAGE+1?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=_("下一页")?></a>&nbsp;
<?
}
else
{
?>
       <?=_("下一页")?>&nbsp;
<?
}
?>
       &nbsp;
    </td>
    </tr>
</table>
<?
if($ASC_DESC=="")
   $ASC_DESC="1";
if($FIELD=="")
   $FIELD="ROLL_CODE";
 //============================ 显示已发布案卷 =======================================
 if($_SESSION["LOGIN_USER_PRIV"]=="1")
    $query = "SELECT RMS_ROLL.*,ROOM_NAME from RMS_ROLL left join RMS_ROLL_ROOM on RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID where 1=1";
 else
    $query = "SELECT RMS_ROLL.*,ROOM_NAME from RMS_ROLL left join RMS_ROLL_ROOM on RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID where RMS_ROLL.ADD_USER='".$_SESSION["LOGIN_USER_ID"]."' or RMS_ROLL_ROOM.MANAGE_USER='".$_SESSION["LOGIN_USER_ID"]."'";

$query .= " order by $FIELD";
if($ASC_DESC=="1")
   $query .= " desc";
else
   $query .= " asc";

if($ASC_DESC=="0")
   $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_up.gif\" width=\"11\" height=\"10\">";
else
   $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_down.gif\" width=\"11\" height=\"10\">";
   
?>
<br>
<table class="TableList" width="100%">
  <tr class="TableHeader">
      <td nowrap align="center" onclick="order_by('SUBJECT','<?if($FIELD=="SUBJECT") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("案卷号")?></u><?if($FIELD=="SUBJECT") echo $ORDER_IMG;?></td>
      <td nowrap align="center"><?=_("案卷名称")?></td>
      <td nowrap align="center"><?=_("所属卷库")?></td>
      <td nowrap align="center" onclick="order_by('RMS_ROLL_TIME','<?if($FIELD=="RMS_ROLL_TIME"||$FIELD=="") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("全宗号")?></u><?if($FIELD=="RMS_ROLL_TIME"||$FIELD=="") echo $ORDER_IMG;?></td>
      <td nowrap align="center" onclick="order_by('CLICK_COUNT','<?if($FIELD=="CLICK_COUNT") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("凭证类别")?></u><?if($FIELD=="CLICK_COUNT") echo $ORDER_IMG;?></td>
      <td nowrap align="center"><?=_("案卷密级")?></td>
      <td nowrap align="center" onclick="order_by('PUBLISH','<?if($FIELD=="PUBLISH") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("案卷状态")?></u><?if($FIELD=="PUBLISH") echo $ORDER_IMG;?></td>
      <td nowrap align="center"><?=_("借阅")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
    </tr>
<?
 $FILE_COUNT=COUNT($CK_FILE);
 for($I=0;$I<$FILE_COUNT;$I++)
 {
?>
	<input type=hidden name=FILE_ID[] value=<?=$CK_FILE[$I]?>>
<?
 }
?>
<?
 $cursor= exequery(TD::conn(),$query);
 $RMS_ROLL_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $RMS_ROLL_COUNT++;
    
    if($RMS_ROLL_COUNT<$CUR_PAGE*$PER_PAGE-$PER_PAGE+1)
       continue;
    if($RMS_ROLL_COUNT>$CUR_PAGE*$PER_PAGE)
       break;

    $ROLL_ID=$ROW["ROLL_ID"];
    $ROLL_CODE=$ROW["ROLL_CODE"];
    $ROLL_NAME=$ROW["ROLL_NAME"];
    $ROOM_NAME=$ROW["ROOM_NAME"];
    $ADD_USER=$ROW["ADD_USER"];
    $CATEGORY_NO=$ROW["CATEGORY_NO"];
    $CERTIFICATE_KIND=$ROW["CERTIFICATE_KIND"];
    $SECRET=$ROW["SECRET"];
    $STATUS=$ROW["STATUS"];
    $BORROW=$ROW["BORROW"];
	if($STATUS==0)
		$STATUS_DESC=_("未封卷");
	else
		$STATUS_DESC=_("已封卷");
	if($BORROW==0)
		$BORROW_DESC=_("已公开");
	else
		$BORROW_DESC=_("需审批");

    $query1="select * from USER where USER_ID='$ADD_USER'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
    {
       $ADD_USER_NAME=$ROW["USER_NAME"];
       $DEPT_ID=$ROW["DEPT_ID"];
    }
    
    $SECRET=get_code_name($SECRET,"RMS_SECRET");
    $CERTIFICATE_KIND=get_code_name($CERTIFICATE_KIND,"RMS_CERTIFICATE_KIND");
    $DEPT_NAME=dept_long_name($DEPT_ID);
    
    if($PUBLISH=="0")
       $PUBLISH_DESC='<font color=red>'._("未发布").'</font>';
    else
       $PUBLISH_DESC="";
    
    if($RMS_ROLL_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
      <td align="center">
      <a href="javascript:open_roll('<?=$ROLL_ID?>','<?=$FORMAT?>');"><?=$ROLL_CODE?></a>
      </td>
      <td nowrap align="center"><?=$ROLL_NAME?></td>
      <td nowrap align="center"><?=$ROOM_NAME?></td>
      <td nowrap align="center"><?=$CATEGORY_NO?></td>
      <td nowrap align="center"><?=$CERTIFICATE_KIND?></td>
      <td nowrap align="center"><?=$SECRET?></td>
      <td nowrap align="center"><?=$STATUS_DESC?></td>
      <td nowrap align="center"><?=$BORROW_DESC?></td>
      <td nowrap align="center">
      <a href="modify.php?ROLL_ID=<?=$ROLL_ID?>&CUR_PAGE=<?=$CUR_PAGE?>"> <?=_("组卷")?></a>
      </td>
    </tr>
<?
 }
?>
</table>
</body>

</html>
