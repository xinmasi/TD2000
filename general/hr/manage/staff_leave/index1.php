<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("员工离职管理");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script>
function delete_leave(LEAVE_ID)
{
  msg='<?=_("确认要删除该项离职信息吗？")?>';
  if(window.confirm(msg))
  {
     URL="delete.php?LEAVE_ID=" + LEAVE_ID+"&PAGE_START=<?=$PAGE_START?>";
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
     alert("<?=_("要删除员工离职信息，请至少选择其中一条。")?>");
     return;
  }

  msg='<?=_("确认要删除该项离职信息吗？")?>';
  if(window.confirm(msg))
  {
    url="delete.php?LEAVE_ID="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
    location=url;
  }
}

function order_by(field,asc_desc)
{
 window.location="index1.php?start=<?=$start?>&FIELD="+field+"&ASC_DESC="+asc_desc;
}
</script>

<body class="bodycolor">
<?
if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("HR_MANAGE", 10);
$PAGE_START=intval($PAGE_START);

//OA管理员 档案管理员 新建人 
$WHERE_STR = hr_priv("CREATE_USER_ID");
if(!isset($TOTAL_ITEMS))
{
   $query = "SELECT count(*) from HR_STAFF_LEAVE where ".$WHERE_STR;
   $cursor= exequery(TD::conn(),$query, $connstatus);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("管理员工离职")?></span>&nbsp;
    </td>
<?
if($TOTAL_ITEMS>0)
{
?>    
    <td align="right" valign="bottom" class="small1"><?=page_bar($PAGE_START,$TOTAL_ITEMS,$PAGE_SIZE,"PAGE_START")?></td>
<?
}
?>
    </tr>
</table>
<?
if($FIELD=="")
	 $ORDER_FIELD="ADD_TIME";
else
	 $ORDER_FIELD=$FIELD;
if($ASC_DESC=="")
   $ASC_DESC="1";
if($ASC_DESC=="1")
   $ORDER_FIELD .= " desc";
else
   $ORDER_FIELD .= " asc";

if($ASC_DESC=="0")
   $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_up.gif\" width=\"11\" height=\"10\">";
else
   $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_down.gif\" width=\"11\" height=\"10\">";
if($TOTAL_ITEMS>0)
{
?>  
<table class="TableList" width="100%">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("选择")?></td>
      <td nowrap align="center"><?=_("离职人员")?></td>
      <td nowrap align="center"><?=_("离职部门")?></td>
      <td nowrap align="center"><?=_("担任职务")?></td>
      <td nowrap align="center"><?=_("离职类型")?></td>
      <td nowrap align="center"><?=_("拟离职日期")?></td>      
      <td nowrap align="center" 
      	onClick="order_by('QUIT_TIME_FACT','<?if($FIELD=="QUIT_TIME_FACT") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("实际离职日期")?></u><?if($FIELD=="QUIT_TIME_FACT") echo $ORDER_IMG;?>
      </td>
      <td nowrap align="center"><?=_("工资截止日期")?></td>
      <td nowrap align="center"><?=_("离职当月薪资")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
  </tr>
<?
}

$query = "SELECT * from HR_STAFF_LEAVE where ".$WHERE_STR." order by ".$ORDER_FIELD." limit $PAGE_START, $PAGE_SIZE";
$cursor= exequery(TD::conn(),$query, $connstatus);
$LEAVE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $LEAVE_COUNT++;
   $LEAVE_ID=$ROW["LEAVE_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $QUIT_TYPE=$ROW["QUIT_TYPE"];
   $LAST_SALARY_TIME=$ROW["LAST_SALARY_TIME"];
   $QUIT_TIME_PLAN=$ROW["QUIT_TIME_PLAN"];
   $LEAVE_PERSON=$ROW["LEAVE_PERSON"];
   $ADD_TIME=$ROW["ADD_TIME"];
   $QUIT_TIME_FACT=$ROW["QUIT_TIME_FACT"]; 
   $POSITION=$ROW["POSITION"]; 
   $IS_REINSTATEMENT=$ROW["IS_REINSTATEMENT"];    
   $LEAVE_DEPT =$ROW["LEAVE_DEPT"];
   $SALARY =$ROW["SALARY"];
   
   $query9 = "SELECT DEPT_ID from HR_STAFF_INFO where USER_ID='$LEAVE_PERSON'";
    $cursor9= exequery(TD::conn(),$query9);
    if($ROW9=mysql_fetch_array($cursor9))
    {
        $LEAVE_DEPT1=$ROW9["DEPT_ID"];
        $LEAVE_DEPT_NAME=substr(GetDeptNameById($LEAVE_DEPT1),0,-1);  
    }
   
    
	 $QUIT_TYPE=get_hrms_code_name($QUIT_TYPE,"HR_STAFF_LEAVE");
	 $LEAVE_PERSON_NAME=substr(GetUserNameById($LEAVE_PERSON),0,-1);
	if($LEAVE_PERSON_NAME=="")
	{
		$query1 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$LEAVE_PERSON'";
		$cursor1= exequery(TD::conn(),$query1);
		if($ROW1=mysql_fetch_array($cursor1))
		$LEAVE_PERSON=$ROW1["STAFF_NAME"];
		$LEAVE_PERSON_NAME=$LEAVE_PERSON."("._("<font color=red>用户已删除</font>").")";	 
	}
	else
	{
	    $query2 = "SELECT DEPT_ID from user where USER_ID='$LEAVE_PERSON'";
	    $cursor2 = exequery(TD::conn(), $query2);
	    if($ROW1=mysql_fetch_array($cursor2))
	    {
	        $LEAVE_PERSON_DEPT_ID=$ROW1["DEPT_ID"];
	        
	        if($LEAVE_PERSON_DEPT_ID != 0)
	        {
	            $query3 = "UPDATE HR_STAFF_LEAVE SET IS_REINSTATEMENT=1 WHERE LEAVE_ID='$LEAVE_ID'";
	            exequery(TD::conn(), $query3);
	            
	            $IS_REINSTATEMENT = 1;
	        }
	    }
	}
	    
	if($IS_REINSTATEMENT == 1)
	{
		$LEAVE_PERSON_NAME=$LEAVE_PERSON_NAME."("._("<font color=red>用户已复职</font>").")";	 
	}
	 if($POSITION=="")
	 {
	    $query1 = "SELECT JOB_POSITION from HR_STAFF_INFO where USER_ID='$LEAVE_PERSON'";
      $cursor1= exequery(TD::conn(),$query1);
      if($ROW1=mysql_fetch_array($cursor1))
         $POSITION=$ROW1["JOB_POSITION"];
   }
?>
   <tr class="TableData">
      <td>&nbsp;<input type="checkbox" name="email_select" value="<?=$LEAVE_ID?>" onClick="check_one(self);">
      <td nowrap align="center"><?=$LEAVE_PERSON_NAME?></td>
      <td nowrap align="center"><?=$LEAVE_DEPT_NAME?></td>      
      <td nowrap align="center"><?=$POSITION?></td>
      <td nowrap align="center"><?=$QUIT_TYPE?></td>
      <td nowrap align="center"><?=$QUIT_TIME_PLAN=="0000-00-00"?"":$QUIT_TIME_PLAN;?></td>
      <td nowrap align="center"><?=$QUIT_TIME_FACT=="0000-00-00"?"":$QUIT_TIME_FACT;?></td>
      <td nowrap align="center"><?=$LAST_SALARY_TIME=="0000-00-00"?"":$LAST_SALARY_TIME;?></td>
      <td nowrap align="center"><?=$SALARY;?></td>
      <td nowrap align="center">
      	<a href="javascript:;" onClick="window.open('leave_detail.php?LEAVE_ID=<?=$LEAVE_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
<?
   if($IS_REINSTATEMENT == 0)
   {
?>
        <a href="../staff_reinstatement/new.php?USER_ID=<?=urlencode($LEAVE_PERSON)?>&TYPE=leave"> <?=_("复职")?></a>&nbsp;
<?
   }
?>
  	    <a href="modify.php?LEAVE_ID=<?=$LEAVE_ID?>"> <?=_("修改")?></a>&nbsp;
	      <a href="javascript:delete_leave(<?=$LEAVE_ID?>);"> <?=_("删除")?></a>
      </td>
   </tr>
<?
}

if($TOTAL_ITEMS>0)
{
?>
   <tr class="TableControl">
     <td colspan="19">
       <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("全选")?></label>&nbsp;
       <a href="javascript:delete_mail();" title="<?=_("删除所选员工离职信息")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("删除")?></a>&nbsp; 
     </td>
   </tr>
<?
}else{
   Message("",_("无员工离职信息记录"));	
}
?>
</table>
</body>

</html>
