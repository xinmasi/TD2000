<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$connstatus = ($connstatus) ? true : false;
if(!isset($TYPE))
   $TYPE="0";

$HTML_PAGE_TITLE = _("������");
include_once("inc/header.inc.php");
?>



<script>
function open_RMS_ROLL_ROOM(ROOM_ID,CATEGORY_NO)
{
 URL="../show/read_RMS_ROLL_ROOM.php?ROOM_ID="+ROOM_ID;
 myleft=(screen.availWidth-500)/2;
 mytop=150
 mywidth=550;
 myheight=400;
 if(CATEGORY_NO=="1")
 {
    myleft=0;
    mytop=0
    mywidth=screen.availWidth-10;
    myheight=screen.availHeight-40;
 }
 window.open(URL,"read_RMS_ROLL_ROOM","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}

function delete_RMS_ROLL_ROOM(ROOM_ID,CUR_PAGE)
{
 msg='<?=_("ȷ��Ҫɾ����������")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?ROOM_ID=" + ROOM_ID + "&CUR_PAGE=" + CUR_PAGE;
  window.location=URL;
 }
}


function delete_all()
{
 msg='<?=_("ȷ��Ҫɾ�����о����")?>';
 if(window.confirm(msg))
 {
  URL="delete_all.php";
  window.location=URL;
 }
}

function re_RMS_ROLL_ROOM(ROOM_ID)
{
 URL="/general/RMS_ROLL_ROOM/show/re_RMS_ROLL_ROOM.php?ROOM_ID="+ROOM_ID+"&MANAGE=1";
 myleft=(screen.availWidth-500)/2;
 window.open(URL,"read_RMS_ROLL_ROOM","height=500,width=550,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=100,left="+myleft+",resizable=yes");
}
function order_by(field,asc_desc)
{
 window.location="index1.php?CUR_PAGE=<?=$CUR_PAGE?>&TYPE=<?=$TYPE?>&FIELD="+field+"&ASC_DESC="+asc_desc;
}

function change_type(type)
{
 window.location="index1.php?CUR_PAGE=<?=$CUR_PAGE?>&TYPE="+type+"&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>";
}
</script>


<body class="bodycolor">

<?
 if($_SESSION["LOGIN_USER_PRIV"]=="1")
    $query = "SELECT count(*) from RMS_ROLL_ROOM where 1=1";
 else
    $query = "SELECT count(*) from RMS_ROLL_ROOM where ADD_USER='".$_SESSION["LOGIN_USER_ID"]."' or MANAGE_USER = '".$_SESSION["LOGIN_USER_ID"]."'";

if($TYPE!="0")
   $query .= " and CATALOG_NO='$TYPE'";

 $cursor= exequery(TD::conn(),$query, $connstatus);
 $RMS_ROLL_ROOM_COUNT=0;
 if($ROW=mysql_fetch_array($cursor))
    $RMS_ROLL_ROOM_COUNT=$ROW[0];

 if($RMS_ROLL_ROOM_COUNT==0)
 {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("������")?></span>&nbsp;
    </td>
  </tr>
</table>
<br>

<?
   Message("",_("�޾��"));
   exit;
 }

 $PER_PAGE=15;
 $PAGES=10;
 $PAGE_COUNT=ceil($RMS_ROLL_ROOM_COUNT/$PER_PAGE);

 if($CUR_PAGE<=0 || $CUR_PAGE=="")
    $CUR_PAGE=1;
 if($CUR_PAGE>$PAGE_COUNT)
    $CUR_PAGE=$PAGE_COUNT;

?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("������")?></span>&nbsp;
    </td>

    <td align="right" valign="bottom" class="small1"><?=sprintf(_("�� %s ��"), '<span class="big4">'.$RMS_ROLL_ROOM_COUNT.'</span>')?>
    </td>
    <td align="right" valign="bottom" class="small1">
       <a class="A1" href="index1.php?CUR_PAGE=1&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=_("��ҳ")?></a>&nbsp;
       <a class="A1" href="index1.php?CUR_PAGE=<?=$PAGE_COUNT?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=_("ĩҳ")?></a>&nbsp;&nbsp;
<?
if($CUR_PAGE%$PAGES==0)
   $J=$PAGES;
else
   $J=$CUR_PAGE%$PAGES;

if($CUR_PAGE> $PAGES)
{
?>
       <a class="A1" href="index1.php?CUR_PAGE=<?=$CUR_PAGE-$J-$PAGES+1?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=sprintf(_("��%sҳ"), $PAGES)?></a>&nbsp;&nbsp;
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
       <a class="A1" href="index1.php?CUR_PAGE=<?=$I?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=sprintf(_("��%sҳ"), $PAGES)?></a>&nbsp;&nbsp;
<?
}
if($CUR_PAGE-1>=1)
{
?>
       <a class="A1" href="index1.php?CUR_PAGE=<?=$CUR_PAGE-1?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=_("��һҳ")?></a>&nbsp;
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
       <a class="A1" href="index1.php?CUR_PAGE=<?=$CUR_PAGE+1?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=_("��һҳ")?></a>&nbsp;
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
   $FIELD="ROOM_CODE";
 //============================ ��ʾ�ѷ������ =======================================
 if($_SESSION["LOGIN_USER_PRIV"]=="1") {
     $query = "SELECT * from RMS_ROLL_ROOM where 1=1";
 }
 else if($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION['LOGIN_USER_PRIV_TYPE']!='1')
 {
    $dept_id = get_manage_dept_ids($_SESSION['LOGIN_UID']);
    if($dept_id)
    {
        $dept_str = $dept_id;
    }
    else
    {
        $dept_str = $_SESSION["LOGIN_DEPT_ID"];
    }
    $UID = rtrim(GetUidByOther('','',$dept_str),",");
    $user_id = rtrim(GetUserIDByUid($UID),",");
    if($user_id != "") {
        $query = "SELECT * from RMS_ROLL_ROOM where find_in_set(ADD_USER,'".$user_id."') or find_in_set(MANAGE_USER,'".$user_id."')";
    }
    else
    {
        $query = "SELECT * from RMS_ROLL_ROOM where ADD_USER='".$_SESSION["LOGIN_USER_ID"]."' or MANAGE_USER = '".$_SESSION["LOGIN_USER_ID"]."'";
    }
 }else{
     $query = "SELECT * from RMS_ROLL_ROOM where ADD_USER='".$_SESSION["LOGIN_USER_ID"]."' or MANAGE_USER = '".$_SESSION["LOGIN_USER_ID"]."'";
 }
if($TYPE!="0")
   $query .= " and CATALOG_NO='$TYPE'";
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
      <td nowrap align="center" onclick="order_by('ROOM_CODE','<?if($FIELD=="ROOM_CODE") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("����")?></u><?if($FIELD=="ROOM_CODE") echo $ORDER_IMG;?></td>
      <td nowrap align="center" onclick="order_by('ROOM_NAME','<?if($FIELD=="ROOM_NAME"||$FIELD=="") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("�������")?></u><?if($FIELD=="ROOM_NAME"||$FIELD=="") echo $ORDER_IMG;?></td>
      <td nowrap align="center" onclick="order_by('DEPT_ID','<?if($FIELD=="DEPT_ID") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("��������")?></u><?if($FIELD=="DEPT_ID") echo $ORDER_IMG;?></td>
      <td nowrap align="center"><?=_("����")?></td>
    </tr>

<?
 $cursor= exequery(TD::conn(),$query, $connstatus);
 $RMS_ROLL_ROOM_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $RMS_ROLL_ROOM_COUNT++;

    if($RMS_ROLL_ROOM_COUNT<$CUR_PAGE*$PER_PAGE-$PER_PAGE+1)
       continue;
    if($RMS_ROLL_ROOM_COUNT>$CUR_PAGE*$PER_PAGE)
       break;

    $ROOM_ID=$ROW["ROOM_ID"];
    $DEPT_ID=$ROW["DEPT_ID"];
    $ADD_USER=$ROW["ADD_USER"];
    $ROOM_CODE=$ROW["ROOM_CODE"];
    $ROOM_NAME=$ROW["ROOM_NAME"];

    $ROOM_NAME=td_htmlspecialchars($ROOM_NAME);

    $query1="select * from USER where USER_ID='$ADD_USER'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
    {
       $ADD_USER_NAME=$ROW["USER_NAME"];
    }

    $TYPE_NAME=get_code_name($CATALOG_NO,"RMS_ROLL_ROOM");
    $DEPT_NAME=dept_long_name($DEPT_ID);

    if($RMS_ROLL_ROOM_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
      <td align="center"><?=$ROOM_CODE?></td>
      <td nowrap align="center"><?=$ROOM_NAME?></td>
      <td nowrap align="center"><?=$DEPT_NAME?></td>
      <td nowrap align="center">
      <a href="modify.php?ROOM_ID=<?=$ROOM_ID?>&CUR_PAGE=<?=$CUR_PAGE?>"> <?=_("�޸�")?></a>
      <a href="javascript:delete_RMS_ROLL_ROOM('<?=$ROOM_ID?>','<?=$CUR_PAGE?>');"> <?=_("ɾ��")?></a>
      </td>
    </tr>
<?
 }
?>

<tr class="TableControl">
<td colspan="9" align="center">
    <input type="button"  value="<?=_("ȫ��ɾ��")?>" class="SmallButton" onClick="delete_all()" title="<?=_("ɾ�����о��")?>">
</td>
</tr>
</table>
</body>
</html>
