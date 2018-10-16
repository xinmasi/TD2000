<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$PAGE_SIZE = 10;
if(!isset($start) || $start=="")
   $start=0;

$HTML_PAGE_TITLE = _("招聘筛选查询");
include_once("inc/header.inc.php");
?>





<script>
function delete_filter(FILTER_ID,start,EXPERT_ID)
{
 msg='<?=_("确认要删除该记录吗？")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?FILTER_ID=" + FILTER_ID + "&start=" + start +"&EXPERT_ID=" + EXPERT_ID;
  window.location=URL;
 }
}

function order_by(field,asc_desc)
{
 window.location="index1.php?start=<?=$start?>&FIELD="+field+"&ASC_DESC="+asc_desc;
}

function check_all()
{
 for (i=0;i<document.getElementsByName("record_select").length;i++)
 {
   if(document.getElementsByName("allbox").item(0).checked)
      document.getElementsByName("record_select").item(i).checked=true;
   else
      document.getElementsByName("record_select").item(i).checked=false;
 }

 if(i==0)
 {
   if(document.getElementsByName("allbox").item(0).checked)
      document.getElementsByName("record_select").checked=true;
   else
      document.getElementsByName("record_select").checked=false;
 }
}

function check_one(el)
{
   if(!el.checked)
      document.getElementsByName("allbox").item(0).checked=false;
}
function delete_mail()
{
  delete_str="";
  for(i=0;i<document.getElementsByName("record_select").length;i++)
  {

      el=document.getElementsByName("record_select").item(i);
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(i==0)
  {
      el=document.getElementsByName("record_select");
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(delete_str=="")
  {
     alert("<?=_("要删除记录，请至少选择其中一条。")?>");
     return;
  }

  msg='<?=_("确认要删除所选记录吗？")?>';
  if(window.confirm(msg))
  {
    url="delete.php?FILTER_ID="+ delete_str +"&start=<?=$start?>" + "&EXPERT_ID=<?=$EXPERT_ID?>";
    location=url;
  }
}
</script>

<?
//------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($EMPLOYEE_NAME!="")
   $CONDITION_STR.=" and EMPLOYEE_NAME like '%".$EMPLOYEE_NAME."%'";
if($PLAN_NAME!="")
   $CONDITION_STR.=" and PLAN_NO like '%".$PLAN_NAME."%'";
if($POSITION!="")
   $CONDITION_STR.=" and POSITION like '%".$POSITION."%'";
if($EMPLOYEE_MAJOR!="")
   $CONDITION_STR.=" and EMPLOYEE_MAJOR like '%".$EMPLOYEE_MAJOR."%'";
if($EMPLOYEE_PHONE!="")
   $CONDITION_STR.=" and EMPLOYEE_PHONE like '%".$EMPLOYEE_PHONE."%'";   
if($TRANSACTOR_STEP!="")
   $CONDITION_STR.=" and TRANSACTOR_STEP='$TRANSACTOR_STEP'";   
if($NEXT_TRANSA_STEP1!="")
   $CONDITION_STR.=" and NEXT_TRANSA_STEP1='$NEXT_TRANSA_STEP1'";   
if($NEXT_TRANSA_STEP2!="")
   $CONDITION_STR.=" and NEXT_TRANSA_STEP2='$NEXT_TRANSA_STEP2'";   
if($NEXT_TRANSA_STEP3!="")
   $CONDITION_STR.=" and NEXT_TRANSA_STEP3='$NEXT_TRANSA_STEP3'";      
if($NEXT_TRANSA_STEP4!="")
   $CONDITION_STR.=" and TRANSACTOR_STEP4='$NEXT_TRANSA_STEP4'"; 
if($STATUS==1)
   $CONDITION_STR.=" and (STEP_FLAG='1' or STEP_FLAG='2' or STEP_FLAG='3' or STEP_FLAG='4') and END_FLAG='0'";
if($STATUS==2)
   $CONDITION_STR.=" and END_FLAG='1'";
if($STATUS==3)
   $CONDITION_STR.=" and END_FLAG='2'";
   
$WHERE_STR = hr_priv("");   
$CONDITION_STR = hr_priv("").$CONDITION_STR;                    
$query = "SELECT * from HR_RECRUIT_FILTER WHERE".$WHERE_STR;
$cursor=exequery(TD::conn(),$query);
$STAFF_COUNT = mysql_num_rows($cursor);

$query = "SELECT * from HR_RECRUIT_FILTER WHERE ".$CONDITION_STR." ORDER BY FILTER_ID limit $start,$PAGE_SIZE";
$cursor=exequery(TD::conn(),$query);
$COUNT = mysql_num_rows($cursor);
 
if($COUNT <= 0)
{
   Message("", _("无符合条件的招聘计划"));
   Button_Back();
   exit;
}
?>
<body class="bodycolor">
	<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"><?=_("招聘筛选查询结果")?></span><br>
    	</td>
    <td align="right" valign="bottom" class="small1"><?=page_bar($start,$STAFF_COUNT,$PAGE_SIZE)?></td>
	</tr>
</table>
<table class="TableList" width="100%">
  <tr class="TableHeader">
    <td nowrap align="center"><?=_("选择")?></td>
    <td nowrap align="center"><?=_("应聘者姓名")?></td>
    <td nowrap align="center"><?=_("应聘岗位")?></td>
    <td nowrap align="center"><?=_("所学专业")?></td>
    <td nowrap align="center"><?=_("联系电话")?></td>
    <td nowrap align="center"><?=_("发起人")?></td>
    <td nowrap align="center"><?=_("操作")?></td>
  </tr>
<?
$FILTER_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
  $FILTER_COUNT++;
  $FILTER_ID=$ROW["FILTER_ID"];
  $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
  $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];   
  $EMPLOYEE_NAME=$ROW["EMPLOYEE_NAME"];
  $POSITION=$ROW["POSITION"];
  $EMPLOYEE_MAJOR=$ROW["EMPLOYEE_MAJOR"];
  $EMPLOYEE_PHONE=$ROW["EMPLOYEE_PHONE"];
  $TRANSACTOR_STEP=$ROW["TRANSACTOR_STEP"];
    $NEXT_TRANSA_STEP=$ROW["NEXT_TRANSA_STEP"];
  $STEP_FLAG=$ROW["STEP_FLAG"];
  $END_FLAG=$ROW["END_FLAG"];
  $EXPERT_ID=$ROW["EXPERT_ID"];  

  $TRANSACTOR_STEP = substr(GetUserNameById($TRANSACTOR_STEP),0,-1);
?>
<tr class="TableData">
    <td>&nbsp;<input type="checkbox" name="record_select" value="<?=$FILTER_ID?>" onClick="check_one(self);">
    <td nowrap align="center"><?=$EMPLOYEE_NAME?><?if($END_FLAG==2) echo "<div nowrap style='color:red' style='display:inline;'>("._("已通过").")</div>"; else if($END_FLAG==1) echo "<div nowrap style='color:red' style='display:inline;'>("._("未通过").")</div>"; else echo "<div nowrap style='color:red' style='display:inline;'>("._("待筛选").")</div>"; ?></td>
    <td nowrap align="center"><?=$POSITION?></td>
    <td nowrap align="center"><?=$EMPLOYEE_MAJOR?></td>
    <td nowrap align="center"><?=$EMPLOYEE_PHONE?></td>      
    <td nowrap align="center"><?=$TRANSACTOR_STEP?></td>   
    <td align="center" width="200">
    	<a href="javascript:;" onClick="window.open('filter_detail.php?FILTER_ID=<?=$FILTER_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
      <? if($END_FLAG!=2&$END_FLAG!=1) echo _("<a href='deal_with.php?FILTER_ID=$FILTER_ID&start==$start'> 办理</a>&nbsp;"); ?> 
      <a href="modify.php?FILTER_ID=<?=$FILTER_ID?>&start=<?=$start?>"> <?=_("修改")?></a>&nbsp;
      <a href="#" onClick="delete_filter('<?=$FILTER_ID?>','<?=$start?>','<?=$EXPERT_ID?>')"> <?=_("删除")?></a>        
    </td>
  </tr>
<?
}
?>
<tr class="TableControl">
<td colspan="19">
    <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("全选")?></label> &nbsp;
    <a href="javascript:delete_mail();" title="<?=_("删除所选记录")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("删除所选记录")?></a>&nbsp;
</td>
</tr>

</table>
<br>
<div align="center">
   <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='query.php';">
</div>
</html>