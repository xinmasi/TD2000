<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

if(!isset($TYPE))
   $TYPE="0";
if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("NEWS", 10);
if(!isset($start) || $start=="")
   $start=0;

$HTML_PAGE_TITLE = _("�����б�");
include_once("inc/header.inc.php");
$time = date("Y-m-d H:i:s",time());
?>


<script Language=JavaScript>
window.setTimeout('this.location.reload();',120000);
</script>

<script>
function open_news(NEWS_ID,FORMAT)
{
 URL="read_news.php?NEWS_ID="+NEWS_ID;
 myleft=(screen.availWidth-780)/2;
 mytop=100
 mywidth=780;
 myheight=500;
 if(FORMAT=="1")
 {
    myleft=0;
    mytop=0
    mywidth=screen.availWidth;
    myheight=screen.availHeight-40;
 }
 window.open(URL,"read_news"+NEWS_ID,"height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}

function re_news(NEWS_ID)
{
 URL="/general/news/show/re_news.php?NEWS_ID="+NEWS_ID;
 myleft=(screen.availWidth-500)/2;
 window.open(URL,"read_news","height=500,width=550,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=100,left="+myleft+",resizable=yes");
}
function order_by(field,asc_desc)
{
 window.location="new.php?start=<?=$start?>&TYPE=<?=$TYPE?>&FIELD="+field+"&ASC_DESC="+asc_desc;
}

function change_type(type)
{
 window.location="new.php?start=<?=$start?>&TYPE="+type+"&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>";
}
function read_all()
{
  msg='<?=_("ȷ��Ҫ�����������Ϊ�Ѷ���")?>';
  if(window.confirm(msg))
  {
    url="read_all.php";
    location=url;
  }
}
</script>


<body class="bodycolor">

<?
 if(!isset($TOTAL_ITEMS))
 {
    $query = "SELECT count(*) from NEWS where NEWS_TIME <= '$time' and  PUBLISH='1' and (TO_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID)".dept_other_sql("TO_ID")." or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID)".priv_other_sql("PRIV_ID")." or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID)) and not find_in_set('".$_SESSION["LOGIN_USER_ID"]."',READERS)";
    if($TYPE!="0")
      $query .= " and TYPE_ID='$TYPE'";
    $cursor= exequery(TD::conn(),$query);
    $TOTAL_ITEMS=0;
    if($ROW=mysql_fetch_array($cursor))
       $TOTAL_ITEMS=$ROW[0];
 }
 
 if($TOTAL_ITEMS==0)
 {
?>
<table border="0" width="95%" cellspacing="0" cellpadding="3" class="small" align="center">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("δ������")?></span><br>
    </td>
  </tr>
</table>

<?
   Message("",_("��δ�������ţ�2���Ӻ��Զ���ת���Ѷ�����"));
   echo "<script>setTimeout(function(){window.location='news.php';},2000)</script>";
   exit;
 }
?>

<table border="0" width="95%" cellspacing="0" cellpadding="3" class="small" align="center">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("δ������")?></span>&nbsp;
       <select name="TYPE" class="BigSelect" onChange="change_type(this.value);">
          <option value="0"<?if($TYPE=="0") echo " selected";?>><?=_("��������")?></option>
          <?=code_list("NEWS",$TYPE)?>
          <option value=""<?if($TYPE=="") echo " selected";?>><?=_("������")?></option>
       </select>
    </td>
<td align="right" valign="bottom" class="small1"><?=page_bar($start,$TOTAL_ITEMS,$PAGE_SIZE)?></td>
    </tr>
</table>
<?
if($ASC_DESC=="")
   $ASC_DESC="1";
/*************************��ѯ�ö�*******************************/
  $query = "SELECT TOP,TOP_DAYS,NEWS_ID from NEWS where PROVIDER='".$_SESSION["LOGIN_USER_ID"]."'";
   $cursor = exequery(TD::conn(),$query);
  
   while($row = mysql_fetch_array($cursor)){
        $NEWS_ID = $row["NEWS_ID"];
        $TOP=$row["TOP"];
        $TOP_DAYS = $row["TOP_DAYS"];
        if($TOP_DAYS != "0" && strtotime($TOP_DAYS) < time()){
            $sql = "update NEWS set TOP='0' WHERE NEWS_ID='$NEWS_ID'";
            exequery(TD::conn(),$sql);
            $TOP = "0";
    }
   }
   //============================ ��ʾ�ѷ������� ======================================= 
$query = "SELECT NEWS_ID,SUBJECT,NEWS_TIME,CLICK_COUNT,FORMAT,TYPE_ID,ANONYMITY_YN,READERS,TOP,TOP_DAYS,SUBJECT_COLOR from NEWS where NEWS_TIME <= '$time' and  PUBLISH='1' and (TO_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID)".dept_other_sql("TO_ID")." or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID)".priv_other_sql("PRIV_ID")." or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID)) and not find_in_set('".$_SESSION["LOGIN_USER_ID"]."',READERS)";

if($TYPE!="0")
   $query .= " and TYPE_ID='$TYPE'";
if($FIELD=="")
   $query .= " order by TOP desc,NEWS_TIME desc";
else
{
   $query .= " order by ".$FIELD;
   if($ASC_DESC=="1")
      $query .= " desc";
   else
      $query .= " asc";
}
$query .= " limit $start,$PAGE_SIZE";
if($ASC_DESC=="0")
   $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_up.gif\" width=\"11\" height=\"10\">";
else
   $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_down.gif\" width=\"11\" height=\"10\">";
?>

<table class="TableList" width="95%" align="center">
  <tr class="TableHeader">
      <td nowrap align="center" onClick="order_by('SUBJECT','<?if($FIELD=="SUBJECT") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("����")?></u><?if($FIELD=="SUBJECT") echo $ORDER_IMG;?></td>
      <td nowrap align="center"><?=_("����")?></td>
      <td nowrap align="center" onClick="order_by('NEWS_TIME','<?if($FIELD=="NEWS_TIME"||$FIELD=="") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("����ʱ��")?></u><?if($FIELD=="NEWS_TIME"||$FIELD=="") echo $ORDER_IMG;?></td>
      <td nowrap align="center" onClick="order_by('CLICK_COUNT','<?if($FIELD=="CLICK_COUNT") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("�������")?></u><?if($FIELD=="CLICK_COUNT") echo $ORDER_IMG;?></td>
      <td nowrap align="center"><?=_("����(��)")?></td>
      <td nowrap align="center"><?=_("��������")?></td>
    </tr>

<?
 //============================ ��ʾ�ѷ������� =======================================
 $cursor= exequery(TD::conn(),$query);
 $NEWS_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $NEWS_COUNT++;
    $NEWS_ID=$ROW["NEWS_ID"];
    $SUBJECT=$ROW["SUBJECT"];
    $NEWS_TIME=$ROW["NEWS_TIME"];
    $CLICK_COUNT=$ROW["CLICK_COUNT"];
    $FORMAT=$ROW["FORMAT"];
    $TYPE_ID=$ROW["TYPE_ID"];
    $TYPE_NAME=get_code_name($TYPE_ID,"NEWS");
    $ANONYMITY_YN=$ROW["ANONYMITY_YN"];
    $READERS=$ROW["READERS"];
    $TOP=$ROW["TOP"];
    $SUBJECT_COLOR=$ROW["SUBJECT_COLOR"];
    $SUBJECT_TITLE="";
    if(strlen($SUBJECT) > 50)
    {
       $SUBJECT_TITLE=$SUBJECT;
       $SUBJECT=csubstr($SUBJECT, 0, 50)."...";
    }
    $SUBJECT=td_htmlspecialchars($SUBJECT);
    if($TOP=="1")
       $SUBJECT="<font color=red><b>".$SUBJECT."</b></font>";
    else
       $SUBJECT="<font color='".$SUBJECT_COLOR."'>".$SUBJECT."</font>";
    $SUBJECT_TITLE=td_htmlspecialchars($SUBJECT_TITLE);

    $query1 = "SELECT count(*) from NEWS_COMMENT where NEWS_ID='$NEWS_ID'";
    $cursor1= exequery(TD::conn(),$query1);
    $COMMENT_COUNT=0;
    if($ROW1=mysql_fetch_array($cursor1))
       $COMMENT_COUNT=$ROW1[0];

    if($NEWS_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
      <td><a href=javascript:open_news('<?=$NEWS_ID?>','<?=$FORMAT?>'); title="<?=$SUBJECT_TITLE?>"><?=$SUBJECT?></a>
      <?
      if(!find_id($READERS,$_SESSION["LOGIN_USER_ID"]))
         echo "<img src='".MYOA_STATIC_SERVER."/static/images/email_new.gif' align='absMiddle'>";
?>
      </td>
      <td nowrap align="center"><?=$TYPE_NAME?></td>
      <td nowrap align="center"><?=$NEWS_TIME?></td>
      <td nowrap align="center"><?=$CLICK_COUNT?></td>
      <td nowrap align="center"><?=$COMMENT_COUNT?></td>
      <td nowrap align="center">
<?
   if($ANONYMITY_YN!="2")
   {
?>
      <a href="javascript:re_news('<?=$NEWS_ID?>');"><?=_("����")?></a>
<?
   }
?>
      </td>
    </tr>
<?
 }
?>

</table>
<br>
<table class="TableBlock" width="95%" align="center">
  <tr>
      <td class="TableContent" nowrap align="center" width="80"><b><?=_("��ݲ�����")?></b></td>
      <td class="TableControl" nowrap>&nbsp;
   <a href="javascript:read_all();" title="<?=_("�����������Ϊ�Ѷ�")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/email_open.gif" align="absMiddle" border="0"> <?=_("�����������Ϊ�Ѷ�")?></a>&nbsp;&nbsp;
      </td>
</tr>
</table>
</body>
</html>
