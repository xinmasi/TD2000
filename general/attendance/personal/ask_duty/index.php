<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("查岗记录查询");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
function CheckForm1()
{
   if(document.form1.CHECK_DUTY_TIME1.value=="")
   { alert("<?=_("起始日期不能为空！")?>");
     return (false);
   }

   if(document.form1.CHECK_DUTY_TIME2.value=="")
   { alert("<?=_("截止日期不能为空！")?>");
     return (false);
   }

   return (true);
}

</script>


<body class="bodycolor attendance" >

<h5 class="attendance-title"><span class="big3"> <?=_("查岗记录查询")?></span></h5><br>
<?
$CUR_DATE_FIRST=date("Y-m-01",time());
$CUR_DATE=date("Y-m-d",time());
?>

<div align="center" class="big1">
<form action="search.php" name="form1" onsubmit="return CheckForm1();">
<b>
<?=_("起始日期：")?><input type="text" name="CHECK_DUTY_TIME1" size="10" maxlength="10" id="start_time" value="<?=$CHECK_DUTY_TIME1?>" onClick="WdatePicker()"/>
&nbsp;
<?=_("截止日期：")?><input type="text" name="CHECK_DUTY_TIME2" size="10" maxlength="10"  value="<?=$CHECK_DUTY_TIME2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>
&nbsp;
<input type="submit" value="<?=_("查询")?>" class="btn" title="<?=_("查岗记录查询")?>">
</form>
</div>

</body>
</html>