<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");


$HTML_PAGE_TITLE = _("�������");
include_once("inc/header.inc.php");
?>



<script>
function open_roll(ROLL_ID)
{
 URL="./read_roll.php?ROLL_ID="+ROLL_ID;
 myleft=(screen.availWidth-500)/2;
 mytop=150
 mywidth=550;
 myheight=400;
 window.open(URL,"read_roll","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}

function order_by(field,asc_desc)
{
 window.location="index1.php?CUR_PAGE=<?=$CUR_PAGE?>&TYPE=<?=$TYPE?>&FIELD="+field+"&ASC_DESC="+asc_desc;
}

function change_type(type)
{
 window.location="index1.php?CUR_PAGE=<?=$CUR_PAGE?>&ROOM_ID0="+type+"&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>";
}
</script>


<body class="bodycolor">

<?
 //if($_SESSION["LOGIN_USER_PRIV"]=="1")
    $query = "SELECT count(*) from RMS_ROLL,RMS_ROLL_ROOM where RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID 
    					and (VIEW_DEPT_ID='ALL_DEPT' or VIEW_DEPT_ID='' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',VIEW_DEPT_ID))".dept_other_sql("VIEW_DEPT_ID");
 //else
    //$query = "SELECT count(*) from RMS_ROLL where RMS_ROLL.ADD_USER='".$_SESSION["LOGIN_USER_ID"]."'";
 if($ROOM_ID0!='')
	$query.=" and RMS_ROLL_ROOM.ROOM_ID='$ROOM_ID0'";
 $cursor= exequery(TD::conn(),$query);
 $RMS_ROLL_COUNT=0;
 if($ROW=mysql_fetch_array($cursor))
    $RMS_ROLL_COUNT=$ROW[0];

 if($RMS_ROLL_COUNT==0)
 {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("�������")?></span>&nbsp;
       <select name="ROOM_ID0" class="BigSelect" onchange="change_type(this.value);">
          <option value=""><?=_("���о��")?></option>
	<?
    $query1="select * from RMS_ROLL_ROOM where (TO_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID)".dept_other_sql("TO_ID")." order by ROOM_CODE desc";
    $cursor1= exequery(TD::conn(),$query1);
    while($ROW=mysql_fetch_array($cursor1))
    {
       $ROOM_ID=$ROW["ROOM_ID"];
       $ROOM_NAME=$ROW["ROOM_NAME"];
	?>
          <option value="<?=$ROOM_ID?>" <?if($ROOM_ID0==$ROOM_ID) echo " selected";?>><?=$ROOM_NAME?></option>
	<?
	}
	?>
       </select>
    </td>
  </tr>
</table>
<br>

<?
   Message("",_("���ѷ����İ���"));
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
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("�������")?></span>&nbsp;
       <select name="ROOM_ID0" class="BigSelect" onchange="change_type(this.value);">
          <option value=""><?=_("���о��")?></option>
	<?
    $query1="select * from RMS_ROLL_ROOM  where (VIEW_DEPT_ID='ALL_DEPT' or VIEW_DEPT_ID='' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',VIEW_DEPT_ID))".dept_other_sql("VIEW_DEPT_ID")." order by ROOM_CODE desc";
    $cursor1= exequery(TD::conn(),$query1);
    while($ROW=mysql_fetch_array($cursor1))
    {
       $ROOM_ID=$ROW["ROOM_ID"];
       $ROOM_NAME=$ROW["ROOM_NAME"];
	?>
          <option value="<?=$ROOM_ID?>" <?if($ROOM_ID0==$ROOM_ID) echo " selected";?>><?=$ROOM_NAME?></option>
	<?
	}
	?>
       </select>
    </td>

    <td align="right" valign="bottom" class="small1"><?=sprintf(_("�� %s ��"), '<span class="big4">'.$RMS_ROLL_COUNT.'</span>')?>
    </td>
    <td align="right" valign="bottom" class="small1">
       <a class="A1" href="index1.php?CUR_PAGE=1&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>&ROOM_ID0=<?=$ROOM_ID0?>"><?=_("��ҳ")?></a>&nbsp;
       <a class="A1" href="index1.php?CUR_PAGE=<?=$PAGE_COUNT?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>&ROOM_ID0=<?=$ROOM_ID0?>"><?=_("ĩҳ")?></a>&nbsp;&nbsp;
<?
if($CUR_PAGE%$PAGES==0)
   $J=$PAGES;
else
   $J=$CUR_PAGE%$PAGES;

if($CUR_PAGE> $PAGES)
{
?>
       <a class="A1" href="index1.php?CUR_PAGE=<?=$CUR_PAGE-$J-$PAGES+1?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>&ROOM_ID0=<?=$ROOM_ID0?>"><?=sprintf(_("��%sҳ"), $PAGES)?></a>&nbsp;&nbsp;
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
       [<a class="A1" href="index1.php?CUR_PAGE=<?=$I?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>&ROOM_ID0=<?=$ROOM_ID0?>"><?=$I?></a>]&nbsp;
<?
   }
}
?>
      &nbsp;
<?
if($I-1< $PAGE_COUNT)
{
?>
       <a class="A1" href="index1.php?CUR_PAGE=<?=$I?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>&ROOM_ID0=<?=$ROOM_ID0?>"><?=sprintf(_("��%sҳ"), $PAGES)?></a>&nbsp;&nbsp;
<?
}
if($CUR_PAGE-1>=1)
{
?>
       <a class="A1" href="index1.php?CUR_PAGE=<?=$CUR_PAGE-1?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>&ROOM_ID0=<?=$ROOM_ID0?>"><?=_("��һҳ")?></a>&nbsp;
<?
}
else
{
?>
       <?=_("��һҳ")?>&nbsp;
<?
}

if($CUR_PAGE+1<= $PAGE_COUNT)
{
?>
       <a class="A1" href="index1.php?CUR_PAGE=<?=$CUR_PAGE+1?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>&ROOM_ID0=<?=$ROOM_ID0?>"><?=_("��һҳ")?></a>&nbsp;
<?
}
else
{
?>
       <?=_("��һҳ")?>&nbsp;
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
 //============================ ��ʾ�ѷ������� =======================================
$query = "SELECT RMS_ROLL.*,ROOM_NAME from RMS_ROLL left join RMS_ROLL_ROOM on RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID where 1=1 and (VIEW_DEPT_ID='ALL_DEPT' or VIEW_DEPT_ID='' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',VIEW_DEPT_ID))".dept_other_sql("VIEW_DEPT_ID");

if($ROOM_ID0!="")
   $query .= " and RMS_ROLL_ROOM.ROOM_ID='$ROOM_ID0'";

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
<table class="TableList"  width="100%">
  <tr class="TableHeader">
      <td nowrap align="center" onclick="order_by('ROLL_CODE','<?if($FIELD=="ROLL_CODE") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("�����")?></u><?if($FIELD=="ROLL_CODE") echo $ORDER_IMG;?></td>
      <td nowrap align="center" onclick="order_by('ROLL_NAME','<?if($FIELD=="ROLL_NAME"||$FIELD=="") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("��������")?></u><?if($FIELD=="ROLL_NAME"||$FIELD=="") echo $ORDER_IMG;?></td>
      <td nowrap align="center" onclick="order_by('ROOM_NAME','<?if($FIELD=="ROOM_NAME"||$FIELD=="") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("�������")?></u><?if($FIELD=="ROOM_NAME"||$FIELD=="") echo $ORDER_IMG;?></td>
      <td nowrap align="center" onclick="order_by('CATEGORY_NO','<?if($FIELD=="CATEGORY_NO"||$FIELD=="") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("ȫ�ں�")?></u><?if($FIELD=="CATEGORY_NO"||$FIELD=="") echo $ORDER_IMG;?></td>
      <td nowrap align="center" onclick="order_by('CERTIFICATE_KIND','<?if($FIELD=="CERTIFICATE_KIND") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("ƾ֤���")?></u><?if($FIELD=="CERTIFICATE_KIND") echo $ORDER_IMG;?></td>
      <td nowrap align="center" onclick="order_by('SECRET','<?if($FIELD=="SECRET"||$FIELD=="") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("�����ܼ�")?></u><?if($FIELD=="SECRET"||$FIELD=="") echo $ORDER_IMG;?></td>
      <td nowrap align="center" onclick="order_by('STATUS','<?if($FIELD=="STATUS") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("����״̬")?></u><?if($FIELD=="STATUS") echo $ORDER_IMG;?></td>
      <td nowrap align="center"><?=_("����")?></td>
    </tr>

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

    $SECRET=get_code_name($SECRET,"RMS_SECRET");
    $CERTIFICATE_KIND=get_code_name($CERTIFICATE_KIND,"RMS_CERTIFICATE_KIND");
    $DEPT_NAME=dept_long_name($DEPT_ID);

    if($RMS_ROLL_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
      <td align="center">
      <a href="javascript:open_roll('<?=$ROLL_ID?>');"><?=$ROLL_CODE?></a>
      </td>
      <td nowrap align="center"><?=$ROLL_NAME?></td>
      <td nowrap align="center"><?=$ROOM_NAME?></td>
      <td nowrap align="center"><?=$CATEGORY_NO?></td>
      <td nowrap align="center"><?=$CERTIFICATE_KIND?></td>
      <td nowrap align="center"><?=$SECRET?></td>
      <td nowrap align="center"><?=$STATUS_DESC?></td>
      <td nowrap align="center">
      <a href="roll_file.php?ROLL_ID=<?=$ROLL_ID?>&CUR_PAGE=<?=$CUR_PAGE?>"> <?=_("�鿴�ļ�")?></a>
      </td>
    </tr>
<?
 }
?>

</table>
</body>

</html>
