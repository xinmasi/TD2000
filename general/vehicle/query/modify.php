<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("车辆使用信息修改");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>

<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
function IsNumber(str)
{
   return str.match(/^[0-9]*$/)!=null;
}

function CheckForm()
{
   if(document.form1.VU_END.value=="")
   {
   	  alert("<?=_("结束时间不能为空！")?>");
      return (false);
   }
   
   if(document.form1.VU_MILEAGE.value!=""&&!IsNumber(document.form1.VU_MILEAGE.value))
   {
   	  alert("<?=_("申请里程应为数字！")?>");
      return (false);
   }

   form1.submit();
}
</script>


<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
if($VU_ID!="")
{
   $query = "SELECT * from VEHICLE_USAGE  where VU_ID='$VU_ID'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
      $V_ID=$ROW["V_ID"];
      $VU_PROPOSER=$ROW["VU_PROPOSER"];
      $VU_REQUEST_DATE=$ROW["VU_REQUEST_DATE"];
      $VU_USER=$ROW["VU_USER"];
      $VU_REASON=$ROW["VU_REASON"];
      $VU_REASON=str_replace("\n","<br>",$VU_REASON);
      $VU_START =$ROW["VU_START"];
      $VU_END=$ROW["VU_END"];
      $VU_MILEAGE=$ROW["VU_MILEAGE"];
      $VU_DEPT=$ROW["VU_DEPT"];
      $VU_STATUS=$ROW["VU_STATUS"];
      $VU_REMARK=$ROW["VU_REMARK"];
      $VU_DESTINATION=$ROW["VU_DESTINATION"];
      $VU_OPERATOR=$ROW["VU_OPERATOR"];
      $VU_DRIVER=$ROW["VU_DRIVER"];
      $DEPT_MANAGER=$ROW["DEPT_MANAGER"];

      $query = "SELECT * from USER where USER_ID='$DEPT_MANAGER'";
      $cursor= exequery(TD::conn(),$query);
      if($ROW=mysql_fetch_array($cursor))
         $DEPT_MANAGER_NAME=$ROW["USER_NAME"];

      if($VU_START=="0000-00-00 00:00:00")
         $VU_START="";
      if($VU_END=="0000-00-00 00:00:00")
         $VU_END="";
   }
}
else
{
   $VU_START=$CUR_TIME;
   $VU_END=$CUR_TIME;
}

if($VU_REQUEST_DATE=="0000-00-00 00:00:00" || $VU_REQUEST_DATE=="")
   $VU_REQUEST_DATE=$CUR_TIME;
if($VU_PROPOSER=="")
   $VU_PROPOSER=$_SESSION["LOGIN_USER_ID"];

$query = "SELECT * from USER  where USER_ID='$VU_PROPOSER'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $VU_PROPOSER_NAME=$ROW["USER_NAME"];

if($VU_DEPT!="")
{
   $query = "SELECT * from DEPARTMENT  where DEPT_ID='$VU_DEPT'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $VU_DEPT_FIELD_DESC=$ROW["DEPT_NAME"];
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle" width="22" height="18"><span class="big3"> <?=_("车辆使用信息修改")?></span>
    </td>
  </tr>
</table>

<table class="TableBlock" align="center" width="620">
<form enctype="multipart/form-data" action="submit.php" method="post" name="form1">
    <tr>
      <td nowrap class="TableContent" width="80"> <?=_("车牌号：")?></td>
      <td class="TableData" width="230">
<?
$query = "SELECT * from VEHICLE where V_ID='$V_ID'";
$cursor1= exequery(TD::conn(),$query);
if($ROW1=mysql_fetch_array($cursor1))
   echo $V_NUM=$ROW1["V_NUM"];
?>
      </td>
      <td nowrap class="TableContent" width="80"> <?=_("司机：")?></td>
      <td class="TableData" width="230"><?=$VU_DRIVER?></td>
    </tr>
    <tr>
      <td nowrap class="TableContent" width="80"> <?=_("用车人：")?></td>
      <td class="TableData"><?=$VU_USER?></td>
      <td nowrap class="TableContent" width="80"> <?=_("用车部门：")?></td>
      <td class="TableData"><?=$VU_DEPT_FIELD_DESC?></td>
    </tr>
    <tr>
      <td nowrap class="TableContent" width="80"> <?=_("起始时间：")?></td>
      <td class="TableData"><?=$VU_START?></td>
      <td nowrap class="TableContent" width="80"> <?=_("结束时间：")?></td>
      <td class="TableData">
        <input type="text" name="VU_END" size="20" maxlength="19" class="BigInput" value="<?=$VU_END?>" 
onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" >
      
    </tr>
    <tr>
      <td nowrap class="TableContent" width="80"> <?=_("目的地：")?></td>
      <td class="TableData">
        <input type="text" name="VU_DESTINATION" size="20" maxlength="100" class="BigInput" value="<?=$VU_DESTINATION?>">
      </td>
      <td nowrap class="TableContent" width="80"> <?=_("申请里程：")?></td>
      <td class="TableData">
        <input type="text" name="VU_MILEAGE" size="10" maxlength="14" class="BigInput" value="<?=$VU_MILEAGE?>"> (<?=_("公里")?>)
      </td>
    </tr>
    <tr>
      <td nowrap class="TableContent" width="80"> <?=_("事由：")?></td>
      <td class="TableData" colspan="3"><?=$VU_REASON?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableContent" width="80"> <?=_("备注：")?></td>
      <td class="TableData" colspan="3">
        <textarea name="VU_REMARK" class="BigInput" cols="74" rows="5"><?=$VU_REMARK?></textarea>
      </td>
    </tr>
    <tr class="TableControl">
      <td nowrap colspan="4" align="center">
        <input type="hidden" value="<?=$VU_ID?>" name="VU_ID"> 
        <input type="hidden" value="<?=$VU_START?>" name="VU_START">              	
        <input type="button" value="<?=_("保存")?>" class="BigButton" onclick="CheckForm();">&nbsp;&nbsp;
        <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close();" title="<?=_("关闭窗口")?>">
      </td>
    </tr>
    </table>
</form>
</body>
</html>