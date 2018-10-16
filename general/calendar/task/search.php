<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
//2013-4-11 主服务查询
if($IS_MAIN==1)
	$QUERY_MASTER=true;
else
   $QUERY_MASTER=""; 

$HTML_PAGE_TITLE = _("任务查询");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/calendar/css/calendar_person.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/calendar.js"></script>
<script Language=JavaScript>
function my_note(TASK_ID)
{
  window.open("note.php?TASK_ID="+TASK_ID,"note_win"+TASK_ID,"height=400,width=550,status=0,toolbar=no,menubar=no,location=no,scrollbars=auto,resizable=no");
}

function delete_task(TASK_ID)
{ 
 msg='<?=_("确认要删除该任务吗？")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?TASK_ID=" + TASK_ID;
  window.location=URL;
 }
}
function check_all()
{
 for (i=0;i<document.getElementsByName("email_select").length;i++)
 {
   if(document.getElementById("allbox_for").checked)
      document.getElementsByName("email_select").item(i).checked=true;
   else
      document.getElementsByName("email_select").item(i).checked=false;
 }

 if(i==0)
 {
   if(document.getElementById("allbox_for").checked)
      document.getElementsByName("email_select").checked=true;
   else
      document.getElementsByName("email_select").checked=false;
 }
}

function check_one(el)
{
   if(!el.checked)
      document.getElementById("allbox_for").checked=false;
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
    url="delete.php?TASK_ID="+ delete_str;
    location=url;
  }
}
</script>

<body class="bodycolor">

<?
$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());
 //-----------合法性校验---------

if($SEND_TIME_MIN!="")
{
  $TIME_OK=is_date($SEND_TIME_MIN);

  if(!$TIME_OK)
  { 
		$MSG1 = sprintf(_("日期的格式不对，应形如 %s"), $CUR_DATE);
		Message(_("错误"),$MSG1);
    Button_Back();
    exit;
  }
  $SEND_TIME_MIN=$SEND_TIME_MIN." 00:00:00";
}

if($SEND_TIME_MAX!="")
{
  $TIME_OK=is_date($SEND_TIME_MAX);

  if(!$TIME_OK)
  { 
		$MSG2 = sprintf(_("日期的格式不对，应形如 %s"), $CUR_DATE);
		Message(_("错误"),$MSG2);
    Button_Back();
    exit;
  }
  $SEND_TIME_MAX=$SEND_TIME_MAX." 23:59:59";
}

//------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($IMPORTANT =="0")
   $CONDITION_STR.=" and IMPORTANT =''";
else if($IMPORTANT =="1")
   $CONDITION_STR.=" and IMPORTANT ='1'";
else if($IMPORTANT =="2")
   $CONDITION_STR.=" and IMPORTANT ='2'";
else if($IMPORTANT =="3")
   $CONDITION_STR.=" and IMPORTANT ='3'";
else if($IMPORTANT =="4")
   $CONDITION_STR.=" and IMPORTANT ='4'";
   
if($TASK_TYPE!="")
   $CONDITION_STR.=" and TASK_TYPE='$TASK_TYPE'";
   
if($CONTENT!="")
   $CONDITION_STR.=" and (CONTENT like '%".$CONTENT."%' or SUBJECT like '%".$CONTENT."%')";
if($SEND_TIME_MIN!="")
   $CONDITION_STR.=" and BEGIN_DATE >='$SEND_TIME_MIN'";
if($SEND_TIME_MAX!="")
   $CONDITION_STR.=" and END_DATE<='$SEND_TIME_MAX'";

if($TASK_STATUS=="1")
   $CONDITION_STR.=" and TASK_STATUS='1'";
else if($TASK_STATUS=="2")
   $CONDITION_STR.=" and TASK_STATUS='2'";
else if($TASK_STATUS=="3")
   $CONDITION_STR.=" and TASK_STATUS='3'";
else if($TASK_STATUS=="4")
   $CONDITION_STR.=" and TASK_STATUS='4'";
else if($TASK_STATUS=="5")
   $CONDITION_STR.=" and TASK_STATUS='5'";
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small" style=" margin-top:10px; margin-bottom:10px;">
  <tr>
    <td class="Big"><span class="big3"> <?=_("任务查询结果")?></span>
    </td>
  </tr>
</table>
<?
$query = "SELECT * from  TASK where USER_ID='".$_SESSION["LOGIN_USER_ID"]."'".$CONDITION_STR." order by BEGIN_DATE,END_DATE ";
$cursor=exequery(TD::conn(),$query,$QUERY_MASTER);
$TASK_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
  $TASK_COUNT++;

  $TASK_ID =$ROW["TASK_ID"];
  $BEGIN_DATE=$ROW["BEGIN_DATE"];
  $END_DATE=$ROW["END_DATE"];
  $TASK_TYPE=$ROW["TASK_TYPE"];
  $SUBJECT =$ROW["SUBJECT"];
  $CONTENT=$ROW["CONTENT"];
  $IMPORTANT=$ROW["IMPORTANT"];
  $TASK_STATUS=$ROW["TASK_STATUS"];
  $TASK_NO =$ROW["TASK_NO"];
  
  $CONTENT=csubstr(strip_tags($CONTENT), 0, 100);

  if($TASK_COUNT==1)
  {
?>

<table class="table table-bordered" width="95%" align="center">
    <thead class="editThead">
      <th width="40"><?=_("选择")?></th>
      <th nowrap align="center"><?=_("序号")?></th>
      <th nowrap align="center"><?=_("开始时间")?><i class="icon-arrow-up"></i></th>
      <th nowrap align="center"><?=_("结束时间")?></th>
      <th nowrap align="center"><?=_("任务类型")?></th>
      <th nowrap align="center"><?=_("任务标题")?></th>
      <th nowrap align="center"><?=_("状态")?></th>
      <th nowrap align="center"><?=_("任务内容")?></th>
      <th width="70"><?=_("操作")?></th>
    </thead>

<?
   }
?>
    <tr class="TableData">
      <td>&nbsp;<input type="checkbox" name="email_select" value="<?=$TASK_ID?>" onClick="check_one(self);">
      <td nowrap align="center"><?=$TASK_NO?></td>	
      <td nowrap align="center"><?=$BEGIN_DATE?></td>
      <td nowrap align="center"><?=$END_DATE?></td>
      <td nowrap align="center"><?if($TASK_TYPE==1)echo _("工作");else echo _("个人");?></td>
      <td nowrap align="center"><a href="javascript:my_note(<?=$TASK_ID?>);"><?=$SUBJECT?></a></td>
      <td nowrap align="center"><?if($TASK_STATUS==1)echo _("未开始");else if($TASK_STATUS==2)echo _("进行中");else if($TASK_STATUS==3)echo _("已完成");else if($TASK_STATUS==4)echo _("等待其他人");else if($TASK_STATUS==5)echo _("已推迟");?></td>
      <td nowrap align="center" title="<?=$TASK_TITLE?>"><span class="important<?=$IMPORTANT?>" title="<?=cal_level_desc($IMPORTANT)?>">&nbsp</span><?=$CONTENT?></td>
      <td nowrap align="center">
          <!--<a href="edit_search.php?TASK_ID=<?=$TASK_ID?>"> <?=_("修改")?></a>-->
          <a href="task_edit.php?TASK_ID=<?=$TASK_ID?>"> <?=_("修改")?></a>
          <a href="javascript:delete_task(<?=$TASK_ID?>);"> <?=_("删除")?></a>
      </td>
    </tr>
<?
}

if($TASK_COUNT==0)
{
   Message("",_("无符合条件的任务安排"));
?>
<center> <button type="button" class="btn" onClick="location='index_list.php'"><?=_("返回")?></button></center>
<?
   exit;
}
else
{
?>
 <tr class="TableControl">
    <td colspan="10" class="form-inline">
      <label class="checkbox" for="allbox_for">
      <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><?=_("全选")?>
      </label>
      <button type="button" class="btn" onClick="javascript:delete_mail();" title="<?=_("删除所选任务")?>"><?=_("删除")?></button>&nbsp;
    </td>
  </tr>
</table>&nbsp

<center> <button type="button" class="btn" onClick="location='index.php'"><?=_("返回")?></button></center>
<?
}
?>
</body>

</html>
