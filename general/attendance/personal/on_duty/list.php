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
if($_GET['PAIBAN_TYPE']=="1")
{
   $CONDITION_STR.=" and PAIBAN_TYPE='1'";
   $PAIBAN_TYPE_DESC="<font color='#0000FF'>"._("�쵼����")."</font>";
}
else if($_GET['PAIBAN_TYPE']=="2")
{
   $CONDITION_STR.=" and PAIBAN_TYPE='2'";
   $PAIBAN_TYPE_DESC="<font color='#0000FF'>"._("����ֵ��")."</font>";
}
else if($_GET['PAIBAN_TYPE']=="3")
{
   $CONDITION_STR.=" and PAIBAN_TYPE='3'";
   $PAIBAN_TYPE_DESC="<font color='#FF0000'>"._("Ӧ��ֵ��")."</font>";
}
else if($_GET['PAIBAN_TYPE']=="4")
{
   $CONDITION_STR.=" and PAIBAN_TYPE='4'";
   $PAIBAN_TYPE_DESC="<font color='#0000FF'>"._("���ֵ��")."</font>";
}
else
{
   $PAIBAN_TYPE_DESC=_("ȫ��");
}

$HTML_PAGE_TITLE = _("ֵ���¼");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">
<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/calendar.js"></script>
<style>
  a:hover{
  text-decoration: none;
}
a span{
  color: #383838;
}
#pageArea #page_no{
    width:25px;
}
/*.table{
  margin-left:20px;
  margin-right:20px;
}*/
</style>
<script>
function set_view2(view, cname)
{
    if(cname=="" || typeof(cname)=='undefined') cname="cal_view";
    var exp = new Date();
    exp.setTime(exp.getTime() + 24*60*60*1000);
    document.cookie = cname+"="+ escape (view) + ";expires=" + exp.toGMTString()+";path=/";
    
    var url=view+'.php?PAIBAN_TYPE='+document.form1.PAIBAN_TYPE.value+'&YEAR='+document.form1.YEAR.value+'&MONTH='+document.form1.MONTH.value+'&DAY='+document.form1.DAY.value;
    if(document.form1.DEPT_ID) url+='&DEPT_ID='+document.form1.DEPT_ID.value;
    location=url;
}
function set_status2(status)
{
  document.form1.PAIBAN_TYPE.value=status;
  My_Submit();
}

function my_dairy(PAIBAN_ID)
{
  myleft=(screen.availWidth-400)/2;
  mytop=(screen.availHeight-200)/2;
  window.open("my_dairy.php?PAIBAN_ID="+PAIBAN_ID,"note_win"+PAIBAN_ID,"height=300,width=400,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,top="+mytop+",left="+myleft);
}

</script>
<body class="bodycolor">

<div class="small" style="clear:both;">
 <div style="float:left;">
   <form name="form1" action="<?=$_SERVER["SCRIPT_NAME"]?>" style="margin-bottom:5px;">
   <input type="hidden" value="" name="BTN_OP">
   <input type="hidden" value="<?=$_GET['PAIBAN_TYPE']?>" name="PAIBAN_TYPE">
   <input type="hidden" value="<?=$YEAR?>" name="YEAR">
   <input type="hidden" value="<?=$MONTH?>" name="MONTH">
   <input type="hidden" value="<?=$DAY?>" name="DAY">
   <a id="status" href="javascript:;" class="dropdown" onclick="showMenu(this.id,'1');" hidefocus="true"><span><?=$PAIBAN_TYPE_DESC?><?=menu_arrow("DOWN")?></span></a>&nbsp;
   <div id="status_menu" class="attach_div">
      <a href="javascript:set_status2('');"><?=_("ȫ��ֵ��")?></a>
      <a href="javascript:set_status2(1);" style="color:#0000FF;"><?=_("�쵼����")?></a>
      <a href="javascript:set_status2(2);" style="color:#0000FF;"><?=_("����ֵ��")?></a>
      <a href="javascript:set_status2(3);" style="color:#FF0000;"><?=_("Ӧ��ֵ��")?></a>
      <a href="javascript:set_status2(4);" style="color:#0000FF;"><?=_("���ֵ��")?></a>
   </div>
 </div>
 <div style="float:right;margin:15px;">
   <span class="">
      <a class="btn btn-large" href="javascript:set_view2('list');" title="<?=_("ֵ���б���ͼ")?>"><span><?=_("ֵ���б���ͼ")?></span></a>
      <a class="btn btn-large" href="javascript:set_view2('index');" title="<?=_("ֵ������ͼ")?>"><span><?=_("ֵ������ͼ")?></span></a>
   </span>
   </form>
 </div>
</div>
<?
$CODE_NAME=array();
$MANAGER=array();
 
if(!$PAGE_SIZE)
   $PAGE_SIZE = 20;
$PAGE_START=intval($PAGE_START);
if(!isset($TOTAL_ITEMS))
{
   $query = "SELECT count(*) from ZBAP_PAIBAN where ZHIBANREN='".$_SESSION["LOGIN_USER_ID"]."'".$CONDITION_STR;
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);
 
if($TOTAL_ITEMS==0)
{
   Message("",_("�޷���������ֵ���¼"));
   exit;
}
?>
<table class="table table-bordered" width="90%" align="center">
  <tr align="center" class="TableHeader">
    <td nowrap width="120"><?=_("ֵ�࿪ʼʱ��")?></td>
    <td nowrap width="120"><?=_("ֵ�����ʱ��")?></td>
    <td><?=_("ֵ��Ҫ��")?></td>
    <td><?=_("��ע")?></td>
    <td nowrap><?=_("ֵ����־")?></td>    
    <td nowrap><?=_("����")?></td>
  </tr>
<?
//============================ ��ʾֵ���¼ =======================================
$CAL_COUNT=0;
$query = "SELECT * from ZBAP_PAIBAN where ZHIBANREN='".$_SESSION["LOGIN_USER_ID"]."'".$CONDITION_STR." order by ZBSJ_B desc limit $PAGE_START,$PAGE_SIZE";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $CAL_COUNT++;
   $PAIBAN_ID=$ROW["PAIBAN_ID"];
   $ZHIBANREN=$ROW["ZHIBANREN"];
   $PAIBAN_TYPE=$ROW["PAIBAN_TYPE"];
   $ZHIBAN_TYPE=$ROW["ZHIBAN_TYPE"];
   $ZBSJ_B=$ROW["ZBSJ_B"];
   $ZBSJ_E=$ROW["ZBSJ_E"]; 
   $ZBYQ=$ROW["ZBYQ"];
   $BEIZHU=$ROW["BEIZHU"]; 
   $ZB_RZ=$ROW["ZB_RZ"];    
   $PAIBAN_APR=$ROW["PAIBAN_APR"]; 
   $ANPAI_TIME=$ROW["ANPAI_TIME"];  
?>
  <tr align="center" class="TableLine<?=2-$CAL_COUNT%2?>" title="<?=$CAL_TITLE?>">
    <td><?=substr($ZBSJ_B,0,16)?></td>
    <td><?=substr($ZBSJ_E,0,16)?></td>
    <td align="left"><?=str_replace("\n","<br>",$ZBYQ)?></td>
    <td align="left"><?=str_replace("\n","<br>",$BEIZHU)?></td>
    <td style="word-wrap:break-word;word-break:break-all;" align="left"><?=str_replace("\n","<br>",$ZB_RZ)?></td>    
    <td nowrap>
<?
$thistime = time();
if($ZB_RZ=="")
{
    if(strtotime($ZBSJ_B) <= $thistime){
        ?>
        <a href='javascript:my_dairy(<?= $PAIBAN_ID ?>);'><?= _("д��־") ?></a>
        <?
    }
}else echo _("��д");
?>
    </td>
  </tr>
<?
}
?>
</table>
<div style="float:right;"><?=page_bar($PAGE_START,$TOTAL_ITEMS,$PAGE_SIZE,"PAGE_START")?></div>
</body>
</html>
