<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");
$PAGE_SIZE = 10;
if(!isset($start) || $start=="")
   $start=0;

$HTML_PAGE_TITLE = _("培训计划查询");
include_once("inc/header.inc.php");
?>


<script Language=JavaScript>
function delete_plan(T_PLAN_ID)
{
  msg='<?=_("确认要删除该项培训计划吗？")?>';
  if(window.confirm(msg))
  {
     URL="delete.php?T_PLAN_ID=" + T_PLAN_ID+"&PAGE_START=<?=$PAGE_START?>";
     window.location=URL;
  }
}

function check_all()
{
   for(i=0;i<document.getElementsByName("email_select").length;i++)
   {
      if(document.getElementsByName("allbox").item(0).checked)
         document.getElementsByName("email_select").item(i).checked=true;
      else
         document.getElementsByName("email_select").item(i).checked=false;
   }

   if(i==0)
   {
      if(document.getElementsByName("allbox").item(0).checked)
         document.getElementsByName("email_select").checked=true;
      else
         document.getElementsByName("email_select").checked=false;
   }
}

function check_one(el)
{
   if(!el.checked)
      document.getElementsByName("allbox").item(0).checked=false;
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
     alert("<?=_("要删除证培训计划，请至少选择其中一条。")?>");
     return;
  }

  msg='<?=_("确认要删除该项培训计划信息吗？")?>';
  if(window.confirm(msg))
  {
    url="delete.php?T_PLAN_ID="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
    location=url;
  }
}
</script>

<body class="bodycolor">

<?
$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());
if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("HR_MANAGE", 10);
	 $PAGE_START=intval($PAGE_START);
 //-----------合法性校验---------

if($COURSE_START_TIME1!="")
{
  $TIME_OK=is_date_time($COURSE_START_TIME1);

  if(!$TIME_OK)
  { Message(_("错误"),_("开始时间的格式不对，应形如 ").$CUR_TIME);
    Button_Back();
    exit;
  }
}

if($COURSE_START_TIME2!="")
{
  $TIME_OK=is_date_time($COURSE_START_TIME2);

  if(!$TIME_OK)
  { Message(_("错误"),_("开始时间的格式不对，应形如 ").$CUR_TIME);
    Button_Back();
    exit;
  }
}
//------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($T_PLAN_NAME!="")
   $CONDITION_STR.=" and T_PLAN_NAME='$T_PLAN_NAME'";
if($T_CHANNEL!="")
   $CONDITION_STR.=" and T_CHANNEL='$T_CHANNEL'";
if($T_ADDRESS!="")
   $CONDITION_STR.=" and T_ADDRESS like '%".$T_ADDRESS."%'";   
if($T_INSTITUTION_NAME!="")
   $CONDITION_STR.=" and T_INSTITUTION_NAME like '%".$T_INSTITUTION_NAME."%'";
if($T_COURSE_TYPES!="")
   $CONDITION_STR.=" and T_COURSE_TYPES='$T_COURSE_TYPES'";   
if($COURSE_START_TIME1!="")
   $CONDITION_STR.=" and COURSE_START_TIME>='$COURSE_START_TIME1'";
if($COURSE_START_TIME2!="")
   $CONDITION_STR.=" and COURSE_START_TIME<='$COURSE_START_TIME2'";   
   
$CONDITION_STR = hr_priv("").$CONDITION_STR;
$sql="SELECT COUNT(*) from HR_TRAINING_PLAN where".$CONDITION_STR;
$cur=exequery(TD::conn(),$sql);
if($ro=mysql_fetch_array($cur))
{
	$TOTAL_ITEMS=$ro[0];
}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("培训计划查询结果")?></span><br>
    	</td>
    <td align="right" valign="bottom" class="small1"><?=page_bar($PAGE_START,$TOTAL_ITEMS,$PAGE_SIZE,"PAGE_START")?></td>
  </tr>
</table>
<?

$query = "SELECT * from HR_TRAINING_PLAN where".$CONDITION_STR."order by ADD_TIME desc limit $PAGE_START, $PAGE_SIZE";
$cursor=exequery(TD::conn(),$query);
$PLAN_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $PLAN_COUNT++;

   $T_PLAN_ID=$ROW["T_PLAN_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $T_PLAN_NO=$ROW["T_PLAN_NO"];
   $T_PLAN_NAME=$ROW["T_PLAN_NAME"];
   $T_CHANNEL=$ROW["T_CHANNEL"];
   $T_COURSE_TYPES=$ROW["T_COURSE_TYPES"];
   $ASSESSING_STATUS=$ROW["ASSESSING_STATUS"];
   $ADD_TIME=$ROW["ADD_TIME"]; 
	 
	 $T_COURSE_TYPES=get_hrms_code_name($T_COURSE_TYPES,"T_COURSE_TYPE");
	 
	 if($T_CHANNEL=="0")
	 		$T_CHANNEL=_("内部培训");
	 if($T_CHANNEL=="1")
	 		$T_CHANNEL=_("渠道培训");
  
  if($PLAN_COUNT==1)
  {
?>
<table class="TableList" width="100%">
  <thead class="TableHeader">
  	  <td nowrap align="center"><?=_("选择")?></td>
      <td nowrap align="center"><?=_("培训计划编号")?></td>
      <td nowrap align="center"><?=_("培训计划名称")?></td>
      <td nowrap align="center"><?=_("培训渠道")?></td>
      <td nowrap align="center"><?=_("培训形式")?></td>
      <td nowrap align="center"><?=_("计划状态")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
  </thead> 
  
<?
   }
?>
    <tr class="TableData">
      <td>&nbsp;<input type="checkbox" name="email_select" value="<?=$T_PLAN_ID?>" onClick="check_one(self);">
      <td nowrap align="center"><?=$T_PLAN_NO?></td>
      <td nowrap align="center"><?=$T_PLAN_NAME?></td>
      <td nowrap align="center"><?=$T_CHANNEL?></td>
      <td nowrap align="center"><?=$T_COURSE_TYPES?></td>
      <td nowrap align="center"><?if($ASSESSING_STATUS==0) echo _("待审批");?><?if($ASSESSING_STATUS==1) echo "<font color=green>"._("已批准")."</font>";?><?if($ASSESSING_STATUS==2) echo "<font color=red>"._("未批准")."</font>";?></td>
      <td nowrap align="center">
      <a href="javascript:;" onClick="window.open('plan_detail.php?T_PLAN_ID=<?=$T_PLAN_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
      <?if($ASSESSING_STATUS!=1) {?><a href="modify.php?T_PLAN_ID=<?=$T_PLAN_ID?>"> <?=_("修改")?></a>&nbsp;<?}?>
			<a href="javascript:delete_plan(<?=$T_PLAN_ID?>);"> <?=_("删除")?></a>&nbsp;
      </td>
   </tr>
<?
}

if($PLAN_COUNT==0)
{
   Message("",_("无符合条件的培训计划信息！"));
   Button_Back();
   exit;
}
else
{
?>
   <tr class="TableControl">
     <td colspan="19">
       <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("全选")?></label>&nbsp;
       <a href="javascript:delete_mail();" title="<?=_("删除所选培训计划")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("删除")?></a>&nbsp; 
     </td>
   </tr>
</table>
<?
   Button_Back();
}
?>
</body>

</html>
