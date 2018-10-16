<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("初始化OA积分");
include_once("inc/header.inc.php");
?>


<script src="/module/DatePicker/WdatePicker.js"></script>

<?
$query1="truncate table HR_INTEGRAL_OA";
exequery(TD::conn(),$query1);
$query2="update HR_INTEGRAL_ITEM set ITEM_VALUE='0',USED='0'";
exequery(TD::conn(),$query2);

Message(_("提示"),_("初始化成功，请设置开始积分时间！"));

?>
<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("设置开始积分时间")?></span>
    </td>
  </tr>
</table>
<br/>
<div align="center" class="big1">
<form name="form1" action="csh_time.php" method="post">
<b>
	<?=_("开始日期：")?><input type="text" name="CALCULATE_TIME" size="15" maxlength="10" class="BigInput" value="<?=$CALCULATE_TIME?>" onClick="WdatePicker()"/>
	&nbsp;<input type="submit" value="<?=_("确定")?>" class="BigButton" title="<?=_("开始计算积分时间")?>">
</form>
</div>
