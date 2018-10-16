<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("黑名单");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<body class="bodycolor">
<?
if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("HR_MANAGE", 10);
$PAGE_START=intval($PAGE_START);

//OA管理员 档案管理员 新建人
//$WHERE_STR = hr_priv("CREATE_USER_ID");
if(!isset($TOTAL_ITEMS))
{
   $query = "SELECT count(*) from HR_STAFF_LEAVE where IS_BLACKLIST=1";
   $cursor= exequery(TD::conn(),$query, $connstatus);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("黑名单")?></span>&nbsp;
    </td>
  </tr>
</table>
<?
if($TOTAL_ITEMS>0)
{
?>
<table class="TableList" width="100%">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("离职人员")?></td>
      <td nowrap align="center"><?=_("离职部门")?></td>
      <td nowrap align="center"><?=_("担任职务")?></td>
      <td nowrap align="center"><?=_("离职类型")?></td>
      <td nowrap align="center"><?=_("实际离职日期")?></td>
      <td nowrap align="center"><?=_("离职当月薪资")?></td>
      <td nowrap align="center"><?=_("黑名单说明")?></td>
      <td nowrap align="center"><?=_("详细信息")?></td>
  </tr>
<?
}

$query = "SELECT * from HR_STAFF_LEAVE where IS_BLACKLIST=1";
$cursor= exequery(TD::conn(),$query, $connstatus);
$LEAVE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $LEAVE_COUNT++;
   $LEAVE_ID=$ROW["LEAVE_ID"];
   $QUIT_TYPE=$ROW["QUIT_TYPE"];
   $LEAVE_PERSON=$ROW["LEAVE_PERSON"];
   $QUIT_TIME_FACT=$ROW["QUIT_TIME_FACT"];
   $POSITION=$ROW["POSITION"];
   $LEAVE_DEPT =$ROW["LEAVE_DEPT"];
   $SALARY =$ROW["SALARY"];
   $BLACKLIST_INSTRUCTIONS=$ROW["BLACKLIST_INSTRUCTIONS"];

   $query9 = "SELECT DEPT_ID from HR_STAFF_INFO where USER_ID='$LEAVE_PERSON'";
    $cursor9= exequery(TD::conn(),$query9);
    if($ROW9=mysql_fetch_array($cursor9))
    {
        $LEAVE_DEPT1=$ROW9["DEPT_ID"];
        $LEAVE_DEPT_NAME=substr(GetDeptNameById($LEAVE_DEPT1),0,-1);
    }
	 $QUIT_TYPE=get_hrms_code_name($QUIT_TYPE,"HR_STAFF_LEAVE");
	 $LEAVE_PERSON_NAME=substr(GetUserNameById($LEAVE_PERSON),0,-1);
	 if($POSITION=="")
	 {
	    $query1 = "SELECT JOB_POSITION from HR_STAFF_INFO where USER_ID='$LEAVE_PERSON'";
            $cursor1= exequery(TD::conn(),$query1);
            if($ROW1=mysql_fetch_array($cursor1))
                $POSITION=$ROW1["JOB_POSITION"];
   }
?>
   <tr class="TableData">
      <td nowrap align="center"><?=$LEAVE_PERSON_NAME?></td>
      <td nowrap align="center"><?=$LEAVE_DEPT_NAME?></td>
      <td nowrap align="center"><?=$POSITION?></td>
      <td nowrap align="center"><?=$QUIT_TYPE?></td>
      <td nowrap align="center"><?=$QUIT_TIME_FACT=="0000-00-00"?"":$QUIT_TIME_FACT;?></td>
      <td nowrap align="center"><?=$SALARY;?></td>
      <td align="center" style="width: 30%"><?=$BLACKLIST_INSTRUCTIONS;?></td>
      <td nowrap align="center"><a href="javascript:;" onClick="window.open('leave_detail.php?LEAVE_ID=<?=$LEAVE_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;</td>
   </tr>
<?
}

if($TOTAL_ITEMS<1)
{
    Message("",_("无黑名单记录"));
}
?>
</table>
</body>

</html>
