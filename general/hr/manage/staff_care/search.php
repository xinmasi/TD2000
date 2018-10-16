<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("员工关怀查询");
include_once("inc/header.inc.php");
?>


<script Language=JavaScript>
function delete_care(CARE_ID)
{
  msg='<?=_("确认要删除该项员工关怀吗？")?>';
  if(window.confirm(msg))
  {
     URL="delete.php?CARE_ID=" + CARE_ID;
     window.location=URL;
   }
}

function check_all()
{
  for (i=0;i<document.getElementsByName("email_select").length;i++)
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
     alert("<?=_("要删除该项员工关怀，请至少选择其中一条。")?>");
     return;
  }

  msg='<?=_("确认要删除该项员工关怀吗？")?>';
  if(window.confirm(msg))
  {
    url="delete.php?CARE_ID="+ delete_str ;
    location=url;
  }
}
</script>

<body class="bodycolor">

<?
$CUR_DATE=date("Y-m-d",time());
//------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($CARE_CONTENT!="")
   $CONDITION_STR.=" and CARE_CONTENT like '%".$CARE_CONTENT."%'";
if($CARE_TYPE!="")
   $CONDITION_STR.=" and CARE_TYPE='$CARE_TYPE'";
if($BY_CARE_STAFFS!="")
   $CONDITION_STR.=" and BY_CARE_STAFFS='$BY_CARE_STAFFS'";
if($CARE_DATE1!="")
  $CONDITION_STR.=" and CARE_DATE>='$CARE_DATE1'";
if($CARE_DATE2!="")
  $CONDITION_STR.=" and CARE_DATE<='$CARE_DATE2'";   
if($CARE_FEES1!="")
   $CONDITION_STR.=" and CARE_FEES>='$CARE_FEES1'";
if($CARE_FEES2!="")
   $CONDITION_STR.=" and CARE_FEES<='$CARE_FEES2'";   
if($PARTICIPANTS!="")
   $CONDITION_STR.=" and PARTICIPANTS like '%".$PARTICIPANTS."%'";
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("员工关怀查询结果")?></span><br>
    	</td>
  </tr>
</table>
<?
$CONDITION_STR = hr_priv("BY_CARE_STAFFS").$CONDITION_STR;
$query = "SELECT * from HR_STAFF_CARE where ".$CONDITION_STR."order by ADD_TIME desc";
$cursor=exequery(TD::conn(),$query);
$CARE_COUNT=0;
$CARE_FEES_ALL=0;
while($ROW=mysql_fetch_array($cursor))
{
  $CARE_COUNT++;
  
  $CARE_ID=$ROW["CARE_ID"];
  $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
  $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
	$BY_CARE_STAFFS=$ROW["BY_CARE_STAFFS"];	
  $CARE_DATE=$ROW["CARE_DATE"];   
  $CARE_CONTENT=$ROW["CARE_CONTENT"];
  $PARTICIPANTS=$ROW["PARTICIPANTS"];
  $CARE_EFFECTS=$ROW["CARE_EFFECTS"];
  $CARE_FEES=$ROW["CARE_FEES"];
  $CARE_TYPE=$ROW["CARE_TYPE"];
  $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME =$ROW["ATTACHMENT_NAME"];
  $ADD_TIME =$ROW["ADD_TIME"];
  
  $CARE_FEES_ALL+=$CARE_FEES;
  
  $TYPE_NAME=get_hrms_code_name($CARE_TYPE,"HR_STAFF_CARE");
  
  $BY_CARE_STAFFS_NAME = substr(GetUserNameById($BY_CARE_STAFFS),0,-1);
   if($BY_CARE_STAFFS_NAME=="")
   {
	    $query1 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$BY_CARE_STAFFS'";
         $cursor1= exequery(TD::conn(),$query1);
         if($ROW1=mysql_fetch_array($cursor1))
         $BY_CARE_STAFFS_NAME=$ROW1["STAFF_NAME"];
	     $BY_CARE_STAFFS_NAME=$BY_CARE_STAFFS_NAME."("."<font color='red'>"._("用户已删除")."</font>".")";
   }
   else
   {
      $query2 = "SELECT DEPT_ID from USER where USER_ID='$BY_CARE_STAFFS'";
      $cursor2= exequery(TD::conn(),$query2);
      if($ROW2=mysql_fetch_array($cursor2))
      	$DEPT_ID=$ROW2['DEPT_ID'];
      if($DEPT_ID=='0')
    		$BY_CARE_STAFFS_NAME=$BY_CARE_STAFFS_NAME."("."<font color='red'>"._("离职/外部人员")."</font>".")";   		
   }
  $PARTICIPANTS_NAME = substr(GetUserNameById($PARTICIPANTS),0,-1);
  
  if($CARE_COUNT==1)
  {
?>
<table class="TableList" width="100%">
  <tr class="TableHeader">
  	  <td nowrap align="center"><?=_("选择")?></td>
      <td nowrap align="center"><?=_("关怀类型")?></td>
      <td nowrap align="center"><?=_("被关怀员工")?></td>
      <td nowrap align="center"><?=_("关怀开支费用/人")?></td>
      <td nowrap align="center"><?=_("参与人")?></td>
      <td nowrap align="center"><?=_("关怀日期")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
  </tr> 
  
<?
   }
?>
     <tr class="TableData">
     	<td>&nbsp;<input type="checkbox" name="email_select" value="<?=$CARE_ID?>" onClick="check_one(self);">
      <td align="center"><?=$TYPE_NAME?></td>
      <td nowrap align="center"><?=$BY_CARE_STAFFS_NAME?></td>
      <td nowrap align="center"><?=$CARE_FEES?>(<?=_("元")?>)</td>
      <td align="center"><?=$PARTICIPANTS_NAME?></td>
      <td nowrap align="center"><?=$CARE_DATE?></td>
     
      <td nowrap align="center">
      <a href="javascript:;" onClick="window.open('care_detail.php?CARE_ID=<?=$CARE_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
      <a href="modify.php?CARE_ID=<?=$CARE_ID?>&start=<?=$start?>"> <?=_("修改")?></a>&nbsp;
      <a href="javascript:delete_care(<?=$CARE_ID?>);"> <?=_("删除")?></a>&nbsp;
      </td>
	</tr>
<?
}

if($CARE_COUNT==0)
{
   Message("",_("无符合条件的员工关怀！"));
   Button_Back();
   exit;
}
else
{
?>
   <tr class="TableControl">
      <td colspan="19" nowrap align="center"><?=_("合计：").$CARE_FEES_ALL.".00"?>(<?=_("元")?>)
      </td>
   </tr>
   <tr class="TableControl">
     <td colspan="19">
       <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("全选")?></label>&nbsp;
       <a href="javascript:delete_mail();" title="<?=_("删除所选员工关怀")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("删除")?></a>&nbsp; 
     </td>
   </tr>
</table>
<?
   Button_Back();
}
?>
</body>

</html>
