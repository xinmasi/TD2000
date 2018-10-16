<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");

$PAGE_SIZE = 10;
if(!isset($start) || $start=="")
   $start=0;

$HTML_PAGE_TITLE = _("培训记录查询");
include_once("inc/header.inc.php");
?>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script>
function delete_info(RECORD_ID)
{
  if(confirm("<?=_("确定要删除该培训记录吗？删除后将不可恢复")?>"))
     location = "delete.php?RECORD_ID="+RECORD_ID;
}

function check_one(el)
{
   if(!el.checked)
      document.getElementsByName("allbox").item(0).checked=false;
}

function check_all()
{
 for (i=0;i<document.getElementsByName("hrms_select").length;i++)
 {
   if(document.getElementsByName("allbox").item(0).checked)
      document.getElementsByName("hrms_select").item(i).checked=true;
   else
      document.getElementsByName("hrms_select").item(i).checked=false;
 }

 if(i==0)
 {
   if(document.getElementsByName("allbox").item(0).checked)
      document.getElementsByName("hrms_select").checked=true;
   else
      document.getElementsByName("hrms_select").checked=false;
 }
}
function delete_mail()
{
  delete_str="";
  for(i=0;i<document.getElementsByName("hrms_select").length;i++)
  {

      el=document.getElementsByName("hrms_select").item(i);
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(i==0)
  {
      el=document.getElementsByName("hrms_select");
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(delete_str=="")
  {
     alert("<?=_("要删除培训记录，请至少选择其中一条。")?>");
     return;
  }

  msg='<?=_("确认要删除所选培训记录吗？")?>';
  if(window.confirm(msg))
  {
    url="delete.php?RECORD_ID="+ delete_str +"&start=<?=$start?>";
    location=url;
  }
}
</script>

<?
//------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($STAFF_USER_ID!="")
   $CONDITION_STR.=" and STAFF_USER_ID='$STAFF_USER_ID'";
if($TO_ID!="")
   $CONDITION_STR.=" and T_PLAN_NO='$TO_ID'";
if($T_INSTITUTION_NAME!="")
  $CONDITION_STR.=" and T_INSTITUTION_NAME='$T_INSTITUTION_NAME'";
if($TRAINNING_COST!="")
  $CONDITION_STR.=" and TRAINNING_COST='$TRAINNING_COST'";
if($DUTY_SITUATION!="")
	$CONDITION_STR.=" and DUTY_SITUATION like '%".$DUTY_SITUATION."%'";
$WHERE_STR = hr_priv("STAFF_USER_ID");
$query = "SELECT * from  HR_TRAINING_RECORD WHERE".$WHERE_STR.$CONDITION_STR;
$cursor=exequery(TD::conn(),$query);
$STAFF_COUNT = mysql_num_rows($cursor);

$query = "SELECT * from  HR_TRAINING_RECORD WHERE".$WHERE_STR.$CONDITION_STR."ORDER BY RECORD_ID limit $start,$PAGE_SIZE";
$cursor=exequery(TD::conn(),$query);
$COUNT = mysql_num_rows($cursor);
 
if($COUNT <= 0)
{
   Message("", _("无符合条件的培训记录"));
   Button_Back();
   exit;
}
?>
<body class="bodycolor">
	<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("培训记录信息")?></span><br>
    	</td>
<?
 $THE_FOUR_VAR = "STAFF_USER_ID=$STAFF_USER_ID&TO_ID=$TO_ID&T_INSTITUTION_NAME=$T_INSTITUTION_NAME&TRAINNING_COST=$TRAINNING_COST&"."start";   	
?>    	
    <td align="right" valign="bottom" class="small1"><?=page_bar($start,$STAFF_COUNT,$PAGE_SIZE,$THE_FOUR_VAR)?></td>
	</tr>
</table>
<table class="TableList" width="100%">
   <thead class="TableHeader">
      <td nowrap align="center"><?=_("选择")?></td>
      <td nowrap align="center"><?=_("培训计划名称")?></td>
      <td nowrap align="center"><?=_("受训人")?></td>
      <td nowrap align="center"><?=_("培训费用")?></td>
      <td nowrap align="center"><?=_("培训机构")?></td>
      <td nowrap align="center"><?=_("相关附件")?></td>
      <td width="150"><?=_("操作")?></td>
   </thead>
<?
while($ROW=mysql_fetch_array($cursor))
{ 
   $REQUIREMENTS_COUNT++;
   
	 $RECORD_ID=$ROW["RECORD_ID"];
   $STAFF_USER_ID=$ROW["STAFF_USER_ID"];
   $T_PLAN_NO=$ROW["T_PLAN_NO"];
   $T_PLAN_NAME=$ROW["T_PLAN_NAME"];
   $T_INSTITUTION_NAME=$ROW["T_INSTITUTION_NAME"];
   $TRAINNING_COST=$ROW["TRAINNING_COST"];
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
   
   if($ATTACHMENT_ID=="")
   		$ATTACHMENT=_("无附件");
   else
   		$ATTACHMENT=attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,0,1,1);

   $query2= "SELECT USER_NAME from  USER WHERE USER_ID='$STAFF_USER_ID'";
	 $cursor2=exequery(TD::conn(),$query2);
	 if($ROW2=mysql_fetch_array($cursor2))
	 {
	 		$STAFF_USER_NAME=$ROW2["USER_NAME"];
	 }
?>
<tr class="TableData">
      <td align="center"><input type="checkbox" name="hrms_select" value="<?=$RECORD_ID?>" onClick="check_one(self);"></td>
      <td align="center"><?=$T_PLAN_NAME?></td>
      <td align="center"><?=$STAFF_USER_NAME?></td>
      <td align="center"><?=$TRAINNING_COST?></td>
      <td align="center"><?=$T_INSTITUTION_NAME?></td>
      <td align="center"><?=$ATTACHMENT?></td>
      <td align="center">
      	<a href="javascript:;" onClick="window.open('record_detail.php?RECORD_ID=<?=$RECORD_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
        <a href="modify.php?RECORD_ID=<?=$RECORD_ID?>"><?=_("编辑")?></a>&nbsp;&nbsp;
        <a href="javascript:delete_info('<?=$RECORD_ID?>');"><?=_("删除")?></a>
      </td>
</tr>
<?
}
?>
<tr class="TableControl">
      <td colspan="19">
         <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("全选")?></label> &nbsp;
         <a href="javascript:delete_mail();" title="<?=_("删除所选培训记录")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("删除所选培训记录")?></a>&nbsp;
      </td>
   </tr>
</table>
<br>
<div align="center">
   <input type="button" value="<?=_("返回")?>" class="BigButton" onclick="location='query.php';">
</div>
</html>