<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

if(!isset($TYPE))
   $TYPE="0";

$HTML_PAGE_TITLE = _("借阅统计");
include_once("inc/header.inc.php");
?>



<script>
function open_file(FILE_ID)
{
 URL="../read_file.php?FILE_ID="+FILE_ID;
 myleft=(screen.availWidth-500)/2;
 mytop=150
 mywidth=550;
 myheight=300;
 window.open(URL,"read_file","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}

function order_by(field,asc_desc)
{
 window.location="lend.php?CUR_PAGE=<?=$CUR_PAGE?>&TYPE=<?=$TYPE?>&FIELD="+field+"&ASC_DESC="+asc_desc;
}

function check_all()
{
 for (i=0;i<document.getElementsByName("file_select").length;i++)
 {
   if(document.getElementsByName("allbox")[0].checked)
      document.getElementsByName("file_select").item(i).checked=true;
   else
      document.getElementsByName("file_select").item(i).checked=false;
 }

 if(i==0)
 {
   if(document.getElementsByName("allbox")[0].checked)
      document.getElementsByName("file_select").checked=true;
   else
      document.getElementsByName("file_select").checked=false;
 }
}

function change_roll()
{
  delete_str="";
  for(i=0;i<document.getElementsByName("file_select").length;i++)
  {

      el=document.getElementsByName("file_select").item(i);
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(i==0)
  {
      el=document.getElementsByName("file_select");
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(delete_str=="")
  {
     alert("<?=_("要组卷文件，请至少选择其中一个。")?>");
     document.form1.reset();
     return;
  }

  roll_id=document.getElementsByName("ROLL_ID").value;
  url="./change_roll.php?DELETE_STR="+ delete_str +"&ROLL_ID="+roll_id+"&PAGE_START=<?=$PAGE_START?>";
  location=url;
}
function check_one(el)
{
   if(!el.checked)
      document.getElementsByName("allbox")[0].checked=false;
}
</script>


<body class="bodycolor">

<?
$query = "SELECT count(*) from RMS_FILE where ROLL_ID<>0 and DEL_USER=''";

if($TYPE!="0")
   $query .= " and CATALOG_NO='$TYPE'";
   
 $cursor= exequery(TD::conn(),$query);
 $RMS_FILE_COUNT=0;
 if($ROW=mysql_fetch_array($cursor))
    $RMS_FILE_COUNT=$ROW[0];

 if($RMS_FILE_COUNT==0)
 {
?>
<br>

<?
   Message("",_("无文件"));
   exit;
 }
 
 $PER_PAGE=15;
 $PAGES=10;
 $PAGE_COUNT=ceil($RMS_FILE_COUNT/$PER_PAGE);

 if($CUR_PAGE<=0 || $CUR_PAGE=="")
    $CUR_PAGE=1;
 if($CUR_PAGE>$PAGE_COUNT)
    $CUR_PAGE=$PAGE_COUNT;

?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("借阅统计")?></span>&nbsp;
    </td>
    <td align="right" valign="bottom" class="small1"><?=sprintf(_("共 %s 条"), '<span class="big4">'.$RMS_FILE_COUNT.'</span>')?>
    </td>
    <td align="right" valign="bottom" class="small1">
       <a class="A1" href="lend.php?CUR_PAGE=1&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=_("首页")?></a>&nbsp;
       <a class="A1" href="lend.php?CUR_PAGE=<?=$PAGE_COUNT?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=_("末页")?></a>&nbsp;&nbsp;
<?
if($CUR_PAGE%$PAGES==0)
   $J=$PAGES;
else
   $J=$CUR_PAGE%$PAGES;

if($CUR_PAGE> $PAGES)
{
?>
       <a class="A1" href="lend.php?CUR_PAGE=<?=$CUR_PAGE-$J-$PAGES+1?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=sprintf(_("上%s页"), $PAGES)?></a>&nbsp;&nbsp;
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
       [<a class="A1" href="lend.php?CUR_PAGE=<?=$I?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=$I?></a>]&nbsp;
<?
   }
}
?>
      &nbsp;
<?
if($I-1< $PAGE_COUNT)
{
?>
       <a class="A1" href="lend.php?CUR_PAGE=<?=$I?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=sprintf(_("下%s页"), $PAGES)?></a>&nbsp;&nbsp;
<?
}
if($CUR_PAGE-1>=1)
{
?>
       <a class="A1" href="lend.php?CUR_PAGE=<?=$CUR_PAGE-1?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=_("上一页")?></a>&nbsp;
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
       <a class="A1" href="lend.php?CUR_PAGE=<?=$CUR_PAGE+1?>&TYPE=<?=$TYPE?>&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>"><?=_("下一页")?></a>&nbsp;
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
   $FIELD="FILE_CODE";
 //============================ 显示已发布文件 =======================================
if($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION['LOGIN_USER_PRIV_TYPE']!='1')
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
        $query = "SELECT * from RMS_FILE where ROLL_ID<>0 and DEL_USER='' and find_in_set(ADD_USER,'".$user_id."')";
    }else{
        $query = "SELECT * from RMS_FILE where ROLL_ID<>0 and DEL_USER='' and ADD_USER ='".$_SESSION["LOGIN_USER_ID"]."'";
    }
}
else
{
    $query = "SELECT * from RMS_FILE where ROLL_ID<>0 and DEL_USER=''";
}
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
  <form action="?"  method="post" name="form1">
  <tr class="TableHeader">
      <td nowrap align="center" onclick="order_by('FILE_CODE','<?if($FIELD=="FILE_CODE") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("文件号")?></u><?if($FIELD=="FILE_CODE") echo $ORDER_IMG;?></td>
      <td nowrap align="center" onclick="order_by('FILE_TITLE','<?if($FIELD=="FILE_TITLE") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("文件标题")?></u><?if($FIELD=="FILE_TITLE") echo $ORDER_IMG;?></td>
      <td nowrap align="center"><?=_("密级")?></td>
      <td nowrap align="center" onclick="order_by('SEND_UNIT','<?if($FIELD=="SEND_UNIT"||$FIELD=="") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("发文单位")?></u><?if($FIELD=="SEND_UNIT"||$FIELD=="") echo $ORDER_IMG;?></td>
      <td nowrap align="center" onclick="order_by('SEND_DATE','<?if($FIELD=="SEND_DATE") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("发文时间")?></u><?if($FIELD=="SEND_DATE") echo $ORDER_IMG;?></td>
      <td nowrap align="center"><?=_("紧急等级")?></td>
      <td nowrap align="center"><?=_("借阅次数")?></td>
    </tr>

<?
 $cursor= exequery(TD::conn(),$query);
 $RMS_FILE_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $RMS_FILE_COUNT++;
    
    if($RMS_FILE_COUNT<$CUR_PAGE*$PER_PAGE-$PER_PAGE+1)
       continue;
    if($RMS_FILE_COUNT>$CUR_PAGE*$PER_PAGE)
       break;

    $FILE_ID=$ROW["FILE_ID"];
    $FILE_CODE=$ROW["FILE_CODE"];
    $FILE_TITLE=$ROW["FILE_TITLE"];
    $SECRET=$ROW["SECRET"];
    $SEND_UNIT=$ROW["SEND_UNIT"];
    $SEND_DATE=$ROW["SEND_DATE"];
    $URGENCY=$ROW["URGENCY"];

    $FILE_TITLE=td_htmlspecialchars($FILE_TITLE);
	if($SEND_DATE=='0000-00-00') $SEND_DATE='';

    $SECRET=get_code_name($SECRET,"RMS_SECRET");
    $URGENCY=get_code_name($URGENCY,"RMS_URGENCY");
    $FILE_ID=intval($FILE_ID);
	$query1="SELECT COUNT(*) FROM RMS_LEND WHERE FILE_ID='$FILE_ID'";
	$cursor1= exequery(TD::conn(),$query1);
	$ROW=mysql_fetch_array($cursor1);
	$LEND_COUNT=$ROW[0];

	if($RMS_FILE_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
      <td align="center">
      <a href="javascript:open_file('<?=$FILE_ID?>');"><?=$FILE_CODE?></a>
      </td>
      <td nowrap align="center"><?=$FILE_TITLE?></td>
      <td nowrap align="center"><?=$SECRET?></td>
      <td nowrap align="center"><?=$SEND_UNIT?></td>
      <td nowrap align="center"><?=$SEND_DATE?></td>
      <td nowrap align="center"><?=$URGENCY?></td>
<?
if($LEND_COUNT>0)
{
?>      
      <td nowrap align="center"><a href = "lend_detail.php?FILE_ID=<?=$FILE_ID?>"><?=$LEND_COUNT?></a></td>
<?
}
else
{
?>
      <td nowrap align="center"><?=$LEND_COUNT?></td> 
<?
}
?>
   </tr>
<?
 }
?>
</form>

</table>
</body>

</html>
