<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
$CUR_DATE_T=date("Y-m-d");

$HTML_PAGE_TITLE = _("我的任务");
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
 msg='<?=_("确认要删除该任务吗？")?>';
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
     alert("<?=_("要删除任务，请至少选择其中一条。")?>");
     return;
  }

  msg='<?=_("确认要删除所选任务吗？")?>';
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
    <td class="Big"><span class="big3"> <?=_("任务管理")?></span>
    </td>
    <td align="center"><button type="button" class="btn" onClick="location='edit.php?PAGE_START=<?=$PAGE_START?>';" title="<?=_("新建任务")?>"><?=_("新建任务")?></button></td>
    <td align="right"><?=page_bar($PAGE_START,$TOTAL_ITEMS,$PAGE_SIZE,"PAGE_START")?></td>
  </tr>
</table>

<?
 $CUR_DATE=date("Y-m-d",strtotime("-1 day"));
 //============================ 显示任务 =======================================
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
    	$DATE_NAME=_("跨天任务");
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
       case "1": $STATUS_DESC=_("未开始");break;
       case "2": $STATUS_DESC=_("进行中");break;
       case "3": $STATUS_DESC=_("已完成");break;
       case "4": $STATUS_DESC=_("等待其他人");break;
       case "5": $STATUS_DESC=_("已推迟");break;
    }

    switch($TASK_TYPE)
    {
       case "1": $TYPE_DESC=_("工作");break;
       case "2": $TYPE_DESC=_("个人");break;
       case "3": $TYPE_DESC=_("指派");break;
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
      <th width="40"><?=_("选择")?></th>
      <th width="40"><?=_("时间")?></th>
      <th><?=_("任务标题")?></th>
      <th width="80"><?=_("状态")?></th>
      <th width="140"><?=_("完成")?></th>
      <th width="50"><?=_("类别")?></th>
      <th width="80"><?=_("颜色")?></th>
      <th width="70"><?=_("操作")?></th>
   </thead>
<?
    }
?>

    <tr class="">
    	 <td>&nbsp;<input type="checkbox" name="email_select" value="<?=$TASK_ID?>" onClick="check_one(self);">
     <td title="<?=$BEGIN_DATE?>-<?=$END_DATE?>"><?=$DATE_NAME?></td>
      <td>
        <? if($TASK_STATUS_DELAY==1){?><img src='<?=MYOA_STATIC_SERVER?>/static/images/sync_error.png' title="<?=_('已延迟')?>"><?}?> <a href="javascript:my_note(<?=$TASK_ID?>);" class="CalLevel<?=$IMPORTANT?>" title="<?=cal_level_desc($IMPORTANT)?>"><?=csubstr(strip_tags($SUBJECT),0,100);?> <? if(strlen($SUBJECT)>100)echo "...";?></a>
      </td>
    	<td nowrap align="center"><?=$STATUS_DESC?></td>
      <td nowrap align="right"><div style="background:#00AA00;width:<?=$RATE?>px;border:1px solid;margin-top:2px;float:left;"></div><div style="float:right;">&nbsp;<?=$RATE?>%</div></td>
      <td nowrap align="center"><?=$TYPE_DESC?></td>
      <td nowrap><span class="CalColor<?=$COLOR?>" style="width:20px;height:20px"></span></td>
      <td nowrap align="center">
      	<? if($MANAGER_ID==""|| $MANAGER_ID==$_SESSION["LOGIN_USER_ID"]){?>
        <a href="task_edit.php?TASK_ID=<?=$TASK_ID?>&PAGE_START=<?=$PAGE_START?>"> <?=_("修改")?></a>
        <a href="javascript:delete_task(<?=$TASK_ID?>);"> <?=_("删除")?></a>
        <? }
        if($MANAGER_ID!="" && $MANAGER_ID!=$_SESSION["LOGIN_USER_ID"]){?>
          <a href="edit_other.php?TASK_ID=<?=$TASK_ID?>&PAGE_START=<?=$PAGE_START?>"> <?=_("修改")?></a>
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
      <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><?=_("全选")?>
      </label>
      <button type="button" class="btn" onClick="javascript:delete_mail();" title="<?=_("删除所选邮件")?>"><?=_("删除")?></button>&nbsp;
    </td>
  </tr>
<?
  }

if($TOTAL_ITEMS==0)
{
   Message("",_("无符合条件的日程安排"));
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
