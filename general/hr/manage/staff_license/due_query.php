<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("证件到期查询");
include_once("inc/header.inc.php");
?>


<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
function CheckForm()
{
	if(document.form1.QUERY_DATE1.value=="" && document.form1.QUERY_DATE2.value=="")
	{
		alert("<?=_("请确定时间范围")?>");
		return (false);
	}
	document.form1.submit();
}
</script>


<body class="bodycolor">
<form method="post" name="form1" action="#">
<table border="0" width="100%" cellpadding="3" cellspacing="1" align="center" bgcolor="#000000">
  <tr>
  	<td class="TableHeader">
    &nbsp;<?=_("证件到期查询")?>&nbsp;
     <?=_("从")?>
     <input type="text" id="start_time" name="QUERY_DATE1" size="10" maxlength="10" class="BigInput" value="<?=$QUERY_DATE1?>" onClick="WdatePicker()"/>
     <?=_("至")?>
     <input type="text" name="QUERY_DATE2" size="10" maxlength="10" class="BigInput" value="<?=$QUERY_DATE2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>
     <input type="hidden" name="inform" value="1"/>
     <input type="button" value="<?=_("确定")?>" class="SmallButton" onClick="CheckForm();">&nbsp;&nbsp;
   </td>
  </tr>
</table>
</form>
<br>
<?
$WHERE_STR = hr_priv("STAFF_NAME");
$WHERE_STR.= " and STATUS !='3' ";
if($inform=="1")
{
   if($QUERY_DATE1!='')
      $WHERE_STR.= " and EXPIRE_DATE > '$QUERY_DATE1'";
   if($QUERY_DATE2!='')
      $WHERE_STR.= " and EXPIRE_DATE < '$QUERY_DATE2'";
}
else
{
   $CUR_MONTH = Date("Y-m");
   $TODAY = Date("Y-m-d");
      $WHERE_STR.= " and (EXPIRE_DATE like '".$CUR_MONTH."%' or EXPIRE_DATE < '$TODAY')";
}
if(!isset($TOTAL_ITEMS))
{
   $query = "SELECT count(*) from HR_STAFF_LICENSE where ".$WHERE_STR;
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"><?=_("查询结果")?></span>&nbsp;
    </td>
    </tr>
</table>
<?
if($TOTAL_ITEMS>0)
{
?>  
<table class="TableList" width="100%">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("单位员工")?></td>
      <td nowrap align="center"><?=_("证照类型")?></td>
      <td nowrap align="center"><?=_("证照编号")?></td>
      <td nowrap align="center"><?=_("证照名称")?></td>
      <td nowrap align="center"><?=_("到期时间")?></td>
      <td nowrap align="center"><?=_("状态")?></td>
      <td nowrap align="center"><?=_("详细信息")?></td>
  </tr>
<?
}

$query = "SELECT * from  HR_STAFF_LICENSE where ".$WHERE_STR." order by ADD_TIME desc";
$cursor= exequery(TD::conn(),$query);
$LICENSE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $LICENSE_COUNT++;
   $LICENSE_ID=$ROW["LICENSE_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $STAFF_NAME=$ROW["STAFF_NAME"];
   $LICENSE_TYPE=$ROW["LICENSE_TYPE"];
   $LICENSE_NO=$ROW["LICENSE_NO"];
   $LICENSE_NAME=$ROW["LICENSE_NAME"];
   $STATUS=$ROW["STATUS"];
   $ADD_TIME=$ROW["ADD_TIME"]; 
   $EXPIRE_DATE = $ROW["EXPIRE_DATE"];
	 
	 $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
	  if($STAFF_NAME1=="")
	  {
	     $query1 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$STAFF_NAME'";
         $cursor1= exequery(TD::conn(),$query1);
         if($ROW1=mysql_fetch_array($cursor1))
         $STAFF_NAME1=$ROW1["STAFF_NAME"];
	     $STAFF_NAME1=$STAFF_NAME1."("."<font color='red'>"._("用户已删除")."</font>".")";
	   }
	 $LICENSE_TYPE=get_hrms_code_name($LICENSE_TYPE,"HR_STAFF_LICENSE1");
	 $STATUS=get_hrms_code_name($STATUS,"HR_STAFF_LICENSE2");
	 
?>
   <tr class="TableData">
      <td nowrap align="center"><?=$STAFF_NAME1?></td>
      <td nowrap align="center"><?=$LICENSE_TYPE?></td>
      <td nowrap align="center"><?=$LICENSE_NO?></td>
      <td nowrap align="center"><?=$LICENSE_NAME?></td>
      <td nowrap align="center"><?=$EXPIRE_DATE=="0000-00-00"?"":$EXPIRE_DATE;?></td>
      <td nowrap align="center"><?=$STATUS?></td>
      <td nowrap align="center">
      <a href="javascript:;" onClick="window.open('license_detail.php?LICENSE_ID=<?=$LICENSE_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
      </td>
   </tr>
<?
}

if($TOTAL_ITEMS>0)
{
?>
<?
}else{
   if($inform=="1")
      Message("",_("无证照到期记录"));	
   else
      Message("",_("本月无证照到期记录"));
}
?>
</table>

</body>
</html>
