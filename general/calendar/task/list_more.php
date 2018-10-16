<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
$CUR_DATE_T=date("Y-m-d");

$HTML_PAGE_TITLE = _("�ҵ�����");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/calendar/css/calendar_person.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/sort_table.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
function delete_task(TASK_ID)
{
 msg='<?=_("ȷ��Ҫɾ����������")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?TASK_ID=" + TASK_ID+"&PAGE_START=<?=$PAGE_START?>";
  window.location=URL;
 }
}

function my_note(TASK_ID)
{
  my_left=document.body.scrollLeft+400;
  my_top=document.body.scrollTop+300;

  window.open("note.php?TASK_ID="+TASK_ID,"task_win"+TASK_ID,"height=170,width=180,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+ my_top +",left="+ my_left +",resizable=yes");
}
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
    url="delete.php?TASK_ID="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
    location=url;
  }
}
</script>

<body class="bodycolor" onLoad="SortTable('bSortTable');">
<?
 if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("CALENDAR", 10);
 $PAGE_SIZE=intval($PAGE_SIZE);
 $PAGE_START=intval($PAGE_START);
 if(!isset($TOTAL_ITEMS))
 {
    $query = "SELECT count(*) from TASK where USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
       $TOTAL_ITEMS=$ROW[0];
 }
 $TOTAL_ITEMS=intval($TOTAL_ITEMS);
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><span class="big3"> <?=_("�������")?></span>
    </td>
    <td align="center"><button type="button" class="btn" onClick="location='edit.php?PAGE_START=<?=$PAGE_START?>';" title="<?=_("�½�����")?>"><?=_("�½�����")?></button></td>
    <td align="right"><?=page_bar($PAGE_START,$TOTAL_ITEMS,$PAGE_SIZE,"PAGE_START")?></td>
  </tr>
</table>

<?
 $CUR_DATE=date("Y-m-d",strtotime("-1 day"));
 //============================ ��ʾ���� =======================================
 $query = "SELECT * from TASK where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and BEGIN_DATE<='$CUR_DATE' and (END_DATE<='$CUR_DATE' or END_DATE='0000-00-00') order by TASK_ID desc limit $PAGE_START, $PAGE_SIZE";
 $cursor= exequery(TD::conn(),$query);
 $TASK_COUNT=0;

 while($ROW=mysql_fetch_array($cursor))
 {
    $TASK_COUNT++;

    $TASK_ID=$ROW["TASK_ID"];
    $TASK_NO=$ROW["TASK_NO"];
    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $END_DATE=$ROW["END_DATE"];
   if($END_DATE=="0000-00-00")
       $END_DATE="";
     
     if($BEGIN_DATE==$CUR_DATE && $END_DATE==$CUR_DATE)
    {
      $DATE_NAME=$CUR_DATE;
    }
    else
    {
    	$DATE_NAME=_("��������");
    }
    if($BEGIN_DATE=="0000-00-00")
       $BEGIN_DATE="";

    if($END_DATE=="0000-00-00")
       $END_DATE="";

    $TASK_TYPE=$ROW["TASK_TYPE"];
    $TASK_STATUS=$ROW["TASK_STATUS"];
    $COLOR=$ROW["COLOR"];
    $IMPORTANT=$ROW["IMPORTANT"];
    $RATE=intval($ROW["RATE"]);
    $MANAGER_ID=$ROW["MANAGER_ID"];
    $SUBJECT=$ROW["SUBJECT"];
    $SUBJECT=td_htmlspecialchars($SUBJECT);

    $CONTENT=$ROW["CONTENT"];
    $CONTENT=td_htmlspecialchars($CONTENT);
    if($TASK_STATUS!=3 &&$END_DATE< $CUR_DATE_T)
       $TASK_STATUS_DELAY=1;
    switch($TASK_STATUS)
    {
       case "1": $STATUS_DESC=_("δ��ʼ");break;
       case "2": $STATUS_DESC=_("������");break;
       case "3": $STATUS_DESC=_("�����");break;
       case "4": $STATUS_DESC=_("�ȴ�������");break;
       case "5": $STATUS_DESC=_("���Ƴ�");break;
    }

    switch($TASK_TYPE)
    {
       case "1": $TYPE_DESC=_("����");break;
       case "2": $TYPE_DESC=_("����");break;
       case "3": $TYPE_DESC=_("ָ��");break;
    }

    if($TASK_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
         if($TASK_COUNT==1)
    {
?>

<table id="bSortTable" class="table table-bordered" width="100%">
   <thead class="editThead" align="center">
      <th width="40"><?=_("ѡ��")?></th>
      <th width="40"><?=_("ʱ��")?></th>
      <th><?=_("�������")?></th>
      <th width="80"><?=_("״̬")?></th>
      <th width="140"><?=_("���")?></th>
      <th width="50"><?=_("���")?></th>
      <th width="80"><?=_("��ɫ")?></th>
      <th width="70"><?=_("����")?></th>
   </thead>
<?
    }
?>

    <tr class="">
    	 <td>&nbsp;<input type="checkbox" name="email_select" value="<?=$TASK_ID?>" onClick="check_one(self);">
     <td title="<?=$BEGIN_DATE?>-<?=$END_DATE?>"><?=$DATE_NAME?></td>
      <td>
        <? if($TASK_STATUS_DELAY==1){?><img src='<?=MYOA_STATIC_SERVER?>/static/images/sync_error.png' title="<?=_('���ӳ�')?>"><?}?> <a href="javascript:my_note(<?=$TASK_ID?>);" class="CalLevel<?=$IMPORTANT?>" title="<?=cal_level_desc($IMPORTANT)?>"><?=csubstr(strip_tags($SUBJECT),0,100);?> <? if(strlen($SUBJECT)>100)echo "...";?></a>
      </td>
    	<td nowrap align="center"><?=$STATUS_DESC?></td>
      <td nowrap align="right"><div style="background:#00AA00;width:<?=$RATE?>px;border:1px solid;margin-top:2px;float:left;"></div><div style="float:right;">&nbsp;<?=$RATE?>%</div></td>
      <td nowrap align="center"><?=$TYPE_DESC?></td>
      <td nowrap><span class="CalColor<?=$COLOR?>" style="width:20px;height:20px"></span></td>
      <td nowrap align="center">
      	<? if($MANAGER_ID==""|| $MANAGER_ID==$_SESSION["LOGIN_USER_ID"]){?>
        <a href="task_edit.php?TASK_ID=<?=$TASK_ID?>&PAGE_START=<?=$PAGE_START?>"> <?=_("�޸�")?></a>
        <a href="javascript:delete_task(<?=$TASK_ID?>);"> <?=_("ɾ��")?></a>
        <? }
        if($MANAGER_ID!="" && $MANAGER_ID!=$_SESSION["LOGIN_USER_ID"]){?>
          <a href="edit_other.php?TASK_ID=<?=$TASK_ID?>&PAGE_START=<?=$PAGE_START?>"> <?=_("�޸�")?></a>
          <?}?>
      </td>
    </tr>
<?
 }
 if($TASK_COUNT>=1)
 {
?>
  <tr class="">
    <td colspan="10" class="form-inline">
      <label class="checkbox" for="allbox_for">
      <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><?=_("ȫѡ")?>
      </label>
      <button type="button" class="btn" onClick="javascript:delete_mail();" title="<?=_("ɾ����ѡ�ʼ�")?>"><?=_("ɾ��")?></button>&nbsp;
    </td>
  </tr>
<?
  }

if($TOTAL_ITEMS==0)
{
   Message("",_("�޷����������ճ̰���"));
}
else
{
?>
  </table>
<?
}
?>

</body>
</html>
