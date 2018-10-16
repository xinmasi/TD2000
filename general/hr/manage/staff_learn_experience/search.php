<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("学习经历信息查询");
include_once("inc/header.inc.php");
?>


<script Language=JavaScript>
function delete_experience(L_EXPERIENCE_ID)
{
  msg='<?=_("确认要删除该项学习经历信息吗？")?>';
  if(window.confirm(msg))
  {
     URL="delete.php?L_EXPERIENCE_ID=" + L_EXPERIENCE_ID+"&PAGE_START=<?=$PAGE_START?>";
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
     alert("<?=_("要删除学习经历信息，请至少选择其中一条。")?>");
     return;
  }

  msg='<?=_("确认要删除该项学习经历吗？")?>';
  if(window.confirm(msg))
  {
    url="delete.php?L_EXPERIENCE_ID="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
    location=url;
  }
}

function change_type(type)
{
   window.location="index1.php?start=<?=$start?>";
}
</script>

<body class="bodycolor">

<?
//------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($STAFF_NAME!="")
   $CONDITION_STR.=" and STAFF_NAME='$STAFF_NAME'";
if($MAJOR!="")
   $CONDITION_STR.=" and MAJOR like '%".$MAJOR."%'";
if($ACADEMY_DEGREE!="")
   $CONDITION_STR.=" and ACADEMY_DEGREE ='".$ACADEMY_DEGREE."'";
if(trim($SCHOOL)!="")
   $CONDITION_STR.=" and SCHOOL like '%".$SCHOOL."%'";
if($CERTIFICATES!="")
   $CONDITION_STR.=" and CERTIFICATES like '%".$CERTIFICATES."%'";
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("学习经历信息查询结果")?></span><br>
    	</td>
  </tr>
</table>
<?
$CONDITION_STR = hr_priv("STAFF_NAME").$CONDITION_STR;
$query = "SELECT * from HR_STAFF_LEARN_EXPERIENCE where".$CONDITION_STR."order by ADD_TIME desc";
$cursor=exequery(TD::conn(),$query);
$EXPERIENCE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $EXPERIENCE_COUNT++;

   $L_EXPERIENCE_ID=$ROW["L_EXPERIENCE_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $STAFF_NAME=$ROW["STAFF_NAME"];
   $MAJOR=$ROW["MAJOR"];
   $ACADEMY_DEGREE=$ROW["ACADEMY_DEGREE"];
   $ACADEMY_DEGREE=get_hrms_code_name($ACADEMY_DEGREE,'STAFF_HIGHEST_SCHOOL');
   $SCHOOL=$ROW["SCHOOL"];
   $WITNESS=$ROW["WITNESS"];
   $ADD_TIME=$ROW["ADD_TIME"]; 
  	
   $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
    if($STAFF_NAME1=="")
	  {
	     $query1 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$STAFF_NAME'";
         $cursor1= exequery(TD::conn(),$query1);
         if($ROW1=mysql_fetch_array($cursor1))
         $STAFF_NAME1=$ROW1["STAFF_NAME"];
	     $STAFF_NAME1=$STAFF_NAME1."("."<font color='red'>"._("用户已删除")."</font>".")";
	   }
   
   if(strlen($SCHOOL) > 20)
	 $SCHOOL=substr($SCHOOL, 0, 20)."...";
  
  if($EXPERIENCE_COUNT==1)
  {
?>
<table class="TableList" width="100%">
  <thead class="TableHeader">
  	  <td nowrap align="center"><?=_("选择")?></td>
      <td nowrap align="center"><?=_("单位员工")?></td>
      <td nowrap align="center"><?=_("所学专业")?></td>
      <td nowrap align="center"><?=_("所获学历")?></td>
      <td nowrap align="center"><?=_("所在院校")?></td>
      <td nowrap align="center"><?=_("证明人")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
  </thead> 
  
<?
   }
?>
     <tr class="TableData">
     	<td>&nbsp;<input type="checkbox" name="email_select" value="<?=$L_EXPERIENCE_ID?>" onClick="check_one(self);">
      <td nowrap align="center"><?=$STAFF_NAME1?></td>
      <td nowrap align="center"><?=$MAJOR?></td>
      <td nowrap align="center"><?=$ACADEMY_DEGREE?></td>
      <td nowrap align="center"><?=$SCHOOL?></td>
      <td nowrap align="center"><?=$WITNESS?></td>
      <td align="center">
			<a href="javascript:;" onClick="window.open('experience_detail.php?L_EXPERIENCE_ID=<?=$L_EXPERIENCE_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
      <a href="modify.php?L_EXPERIENCE_ID=<?=$L_EXPERIENCE_ID?>"> <?=_("修改")?></a>
			<a href="javascript:delete_experience(<?=$L_EXPERIENCE_ID?>);"> <?=_("删除")?></a>
      </td>
      </td>
	</tr>
<?
}

if($EXPERIENCE_COUNT==0)
{
   Message("",_("无符合条件的学习经历信息！"));
   Button_Back();
   exit;
}
else
{
?>
   <tr class="TableControl">
     <td colspan="19">
       <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("全选")?></label>&nbsp;
       <a href="javascript:delete_mail();" title="<?=_("删除所选学习经历信息")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("删除")?></a>&nbsp; 
     </td>
   </tr>
</table>
<?
   Button_Back();
}
?>
</body>

</html>
