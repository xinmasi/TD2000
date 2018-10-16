<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$CUR_YEAR = date('Y');
$CUR_MON = date('m');
$CUR_DAY = date('d');

if($BTN_OP!="")
{
   $DATE=strtotime($BTN_OP,strtotime($YEAR."-".$MONTH."-".$DAY));
   $YEAR=date("Y",$DATE);
   $MONTH=date("m",$DATE);
   $DAY=date("d",$DATE);
}

if(!$YEAR)
   $YEAR = $CUR_YEAR;
if(!$MONTH)
   $MONTH = $CUR_MON;
if(!$DAY)
   $DAY = $CUR_DAY;

if(!checkdate($MONTH,$DAY,$YEAR))
{
   Message(_("����"),_("���ڲ���ȷ"));
   exit;
}

$DATE=strtotime($YEAR."-".$MONTH."-".$DAY);
$WEEK=date("W",$DATE);

$CUR_TIME=date("Y-m-d H:i:s",time());
$CONDITION_STR="";
if($_GET['OVER_STATUS']=="1")
{
   $CONDITION_STR.=" and OVER_STATUS='0' and CAL_TIME>'$CUR_TIME'";
   $STATUS_DESC="<font color='#0000FF'>"._("δ��ʼ")."</font>";
}
else if($_GET['OVER_STATUS']=="2")
{
   $CONDITION_STR.=" and OVER_STATUS='0' and CAL_TIME<='$CUR_TIME' and END_TIME>='$CUR_TIME'";
   $STATUS_DESC="<font color='#0000FF'>"._("������")."</font>";
}
else if($_GET['OVER_STATUS']=="3")
{
   $CONDITION_STR.=" and OVER_STATUS='0' and END_TIME<'$CUR_TIME'";
   $STATUS_DESC="<font color='#FF0000'>"._("�ѳ�ʱ")."</font>";
}
else if($_GET['OVER_STATUS']=="4")
{
   $CONDITION_STR.=" and OVER_STATUS='1'";
   $STATUS_DESC="<font color='#00AA00'>"._("�����")."</font>";
}
else
{
   $STATUS_DESC=_("ȫ��");
}

$HTML_PAGE_TITLE = _("�ճ̰���");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/dialog.css">
<?
if(MYOA_IS_UN && find_id("zh-TW,en,", MYOA_LANG_COOKIE) && find_id(MYOA_FASHION_THEME, $_SESSION["LOGIN_THEME"]))
{
?>
   <link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/un_<?=MYOA_LANG_COOKIE?>.css" />
<?
}
?>

<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/calendar.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/dialog.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
function check_all()
{
 for (i=0;i<document.getElementsByName("email_select").length;i++)
 {
   if(document.getElementsByName("allbox")[0].checked)
      document.getElementsByName("email_select").item(i).checked=true;
   else
      document.getElementsByName("email_select").item(i).checked=false;
 }

 if(i==0)
 {
   if(document.getElementsByName("allbox")[0].checked)
      document.getElementsByName("email_select").checked=true;
   else
      document.getElementsByName("email_select").checked=false;
 }
}

function check_one(el)
{
   if(!el.checked)
      document.getElementsByName("allbox")[0].checked=false;
}
function get_checked()
{
  checked_str="";
  for(i=0;i<document.getElementsByName("email_select").length;i++)
  {

      el=document.getElementsByName("email_select").item(i);
      if(el.checked)
      {  val=el.value;
         checked_str+=val + ",";
      }
  }

  if(i==0)
  {
      el=document.getElementsByName("email_select");
      if(el.checked)
      {  val=el.value;
         checked_str+=val + ",";
      }
  }
  return checked_str;
}
function delete_mail()
{
  delete_str=get_checked();
  if(delete_str=="")
  {
     alert("<?=_("Ҫɾ������������ѡ������һ����")?>");
     return;
  }

  msg='<?=_("ȷ��Ҫɾ����ѡ������")?>';
  if(window.confirm(msg))
  {
    url='delete.php?CAL_ID='+delete_str+'&PAGE_START=<?=$PAGE_START?>&OVER_STATUS='+document.form1.OVER_STATUS.value+'&YEAR='+document.form1.YEAR.value+'&MONTH='+document.form1.MONTH.value+'&DAY='+document.form1.DAY.value;
    location=url;
  }
}
</script>
<body class="bodycolor">
<div class="PageHeader calendar_icon">
 <div class="header-left">
   <form name="form1" action="<?=$_SERVER["SCRIPT_NAME"]?>" style="margin-bottom:5px;">
   <input type="hidden" value="" name="BTN_OP">
   <input type="hidden" value="<?=$_GET['OVER_STATUS']?>" name="OVER_STATUS">
   <input type="hidden" value="<?=$YEAR?>" name="YEAR">
   <input type="hidden" value="<?=$MONTH?>" name="MONTH">
   <input type="hidden" value="<?=$DAY?>" name="DAY">
   <a id="status" href="javascript:;" class="dropdown" onclick="showMenu(this.id,'1');" hidefocus="true"><span><?=$STATUS_DESC?></span></a>&nbsp;
   <div id="status_menu" class="attach_div">
      <a href="javascript:set_status_index('');"><?=_("ȫ��")?></a>
      <a href="javascript:set_status_index(1);" style="color:#0000FF;"><?=_("δ��ʼ")?></a>
      <a href="javascript:set_status_index(2);" style="color:#0000FF;"><?=_("������")?></a>
      <a href="javascript:set_status_index(3);" style="color:#FF0000;"><?=_("�ѳ�ʱ")?></a>
      <a href="javascript:set_status_index(4);" style="color:#00AA00;"><?=_("�����")?></a>
   </div>
   <a href="list.php" class="ToolBtn"><span><?=_("�°���ͼ")?></span></a>
 </div>
 <div class="header-right">
 	<a href="query.php" class="ToolBtn"><span><?=_("��ѯ")?></span></a>
   <a id="new" href="javascript:;" class="dropdown" onclick="showMenu(this.id,'1');" hidefocus="true"><span><?=_("�½�")?></span></a>
    <a href="count.php" class="ToolBtn"><span><?=_("ͳ��")?></span></a>&nbsp;
   <div id="new_menu" class="attach_div">
      <a href="javascript:new_cal('<?=$DATE?>','+1 days');" title="<?=_("�����µ������Ա������Լ�")?>"><?=_("��������")?></a>
      <a href="javascript:new_diary('<?=date("Ymd",$DATE)?>');"><?=_("������־")?></a>
   </div>
   <a href="javascript:set_view('list');" class="calendar-view list-view" title="<?=_("�б���ͼ")?>"></a>
   <a href="javascript:set_view('day');" class="calendar-view day-view" title="<?=_("����ͼ")?>"></a>
   <a href="javascript:set_view('index');" class="calendar-view week-view" title="<?=_("����ͼ")?>"></a>
   <a href="javascript:set_view('month');" class="calendar-view month-view" title="<?=_("����ͼ")?>"></a>
   </form>
 </div>
</div>
<?
 $CODE_NAME=array();
 $MANAGER=array();
 
 if(!$PAGE_SIZE)
   if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("CALENDAR", 20);
 $PAGE_START=intval($PAGE_START);
 if(!isset($TOTAL_ITEMS))
 {
    $query = "SELECT count(*) from CALENDAR where USER_ID='".$_SESSION["LOGIN_USER_ID"]."'".$CONDITION_STR;
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
       $TOTAL_ITEMS=$ROW[0];
 }
 $TOTAL_ITEMS=intval($TOTAL_ITEMS);
 
 if($TOTAL_ITEMS==0)
 {
    Message("",_("�޷����������ճ̰���"));
    exit;
 }
?>
<table class="TableList" width="100%" align="center">
  <tr align="center" class="TableHeader">
    <td width="40"><?=_("ѡ��")?></td>
    <td width="120"><?=_("��ʼʱ��")?></td>
    <td width="120"><?=_("����ʱ��")?></td>
    <td><?=_("����")?></td>
    <td><?=_("����")?></td>
    <td width="120"><?=_("����")?></td>
  </tr>
<?
 //============================ ��ʾ�ճ̰��� =======================================
 $CAL_COUNT=0;
 $query = "SELECT * from CALENDAR where USER_ID='".$_SESSION["LOGIN_USER_ID"]."'".$CONDITION_STR." order by CAL_TIME desc limit $PAGE_START,$PAGE_SIZE";
 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
    $CAL_COUNT++;
    $CAL_ID=$ROW["CAL_ID"];
    $CAL_TIME=$ROW["CAL_TIME"];
    $CAL_TIME=date("Y-m-d H:i:s",$CAL_TIME);
    $END_TIME=$ROW["END_TIME"];
    $END_TIME=date("Y-m-d H:i:s",$END_TIME);
    $CAL_TYPE=$ROW["CAL_TYPE"];
    $CAL_LEVEL=$ROW["CAL_LEVEL"];
    $CONTENT=$ROW["CONTENT"];
    $MANAGER_ID=$ROW["MANAGER_ID"];
    $OVER_STATUS=$ROW["OVER_STATUS"];
    
    $CONTENT=csubstr(strip_tags($CONTENT),0,80);

    if(!array_key_exists($CAL_TYPE, $MANAGER))
       $CODE_NAME[$CAL_TYPE]=get_code_name($CAL_TYPE,"CAL_TYPE");
    $CAL_TYPE_DESC=$CODE_NAME[$CAL_TYPE];
    
    if($OVER_STATUS=="0")
    {
       if(compare_time($CUR_TIME,$END_TIME)>0)
       {
          $STATUS_COLOR="#FF0000";
          $CAL_TITLE=_("״̬���ѳ�ʱ");
       }
       else if(compare_time($CUR_TIME,$CAL_TIME)<0)
       {
          $STATUS_COLOR="#0000FF";
          $CAL_TITLE=_("״̬��δ��ʼ");
       }
       else
       {
          $STATUS_COLOR="#0000FF";
          $CAL_TITLE=_("״̬��������");
       }
    }
    else
    {
       $STATUS_COLOR="#00AA00";
       $CAL_TITLE=_("״̬�������");
    }

    $MANAGER_NAME="";
    if($MANAGER_ID!="")
    {
       if(!array_key_exists($CAL_TYPE, $MANAGER))
       {
          $query = "SELECT USER_NAME from USER where USER_ID='$MANAGER_ID'";
          $cursor1= exequery(TD::conn(),$query);
          if($ROW1=mysql_fetch_array($cursor1))
             $MANAGER[$MANAGER_ID]=$ROW1["USER_NAME"];
       }
       $MANAGER_NAME="("._("������:").$MANAGER[$MANAGER_ID].")";
    }
?>
  <tr id="list_tr_<?=$CAL_ID?>" align="center" class="TableLine<?=2-$CAL_COUNT%2?>" title="<?=$CAL_TITLE?>">
    <td><input type="checkbox" name="email_select" value="<?=$CAL_ID?>" onClick="check_one(self);">
    <td><?=substr($CAL_TIME,0,16)?></td>
    <td><?=substr($END_TIME,0,16)?></td>
    <td align="left"><span class="CalLevel<?=$CAL_LEVEL?>" title="<?=cal_level_desc($CAL_LEVEL)?>">&nbsp</span><a id="cal_<?=$CAL_ID?>" href="javascript:my_note(<?=$CAL_ID?>);" status="<?=$OVER_STATUS?>" style="color:<?=$STATUS_COLOR?>;"><?=$CONTENT?></a></td>
    <td><?=$CAL_TYPE_DESC?></td>
    <td>
<?
    echo "<a href=\"javascript:;\" onclick=\"set_status(this, '$CAL_ID');\"> ".($OVER_STATUS=="0" ? _("���") : _("δ���"))."</a>\n";

    if($MANAGER_ID=="" || $MANAGER_ID==$_SESSION["LOGIN_USER_ID"])
    {
       echo "<a href=\"javascript:edit_cal($CAL_ID);\">"._("�޸�")."</a>\n";
       echo "<a href=\"javascript:del_cal($CAL_ID);\"> "._("ɾ��")."</a>\n";
    }
?>
    </td>
  </tr>
<?
}
?>
  <tr class="TableControl">
    <td colspan="6">&nbsp;<input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();">
      <label for="allbox_for"><?=_("ȫѡ")?></label>&nbsp;
      <a href="javascript:delete_mail();" title="<?=_("ɾ����ѡ�ʼ�")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("ɾ��")?></a>&nbsp;
    </td>
  </tr>
</table>
<div style="float:right;"><?=page_bar($PAGE_START,$TOTAL_ITEMS,$PAGE_SIZE,"PAGE_START")?></div>

<div id="overlay"></div>
<div id="form_div" class="ModalDialog" style="width:500px;">
  <div class="header"><span id="title" class="title"><?=_("�½��ճ�")?></span><a class="operation" href="javascript:HideDialog('form_div');"><img src="<?=MYOA_STATIC_SERVER?>/static/images/close.png"/></a></div>
  <div id="form_body" class="body" style="text-align:center;height:310px;overflow-y:auto;">
     
  </div>
</div>
<iframe name="form_iframe" id="form_iframe" style="display:none;"></iframe>
</body>
</html>
