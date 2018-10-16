<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("check_priv.inc.php");

$HTML_PAGE_TITLE = _("轮班考勤统计");
include_once("inc/header.inc.php");
?>


<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
function CheckForm1()
{
   if(document.form1.DATE1.value=="")
   { alert("<?=_("起始日期不能为空！")?>");
     return (false);
   }

   if(document.form1.DATE2.value=="")
   { alert("<?=_("截止日期不能为空！")?>");
     return (false);
   }

   return (true);
}

function set_date(str)
{
  document.form1.DATE1.value=str;
}
function set_date2(str)
{
  document.form1.DATE2.value=str;
}
</script>

<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("轮班考勤统计")?></span>
    </td>
  </tr>
</table>

<?
$CUR_DATE=date("Y-m-d",time());
$CUR_DATE_FIRST=date("Y-m-01",time());
$CUR_DATE_END = date('Y-m-d', strtotime("$CUR_DATE_FIRST +1 month -1 day")); 

$query = "SELECT POST_PRIV,POST_DEPT from USER where USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $USER_POST_PRIV=$ROW["POST_PRIV"];
   $USER_POST_DEPT=$ROW["POST_DEPT"];   
}
?>

<table align="center" class="TableList" width=450>
<form action="shift/" name="form1" onsubmit="return CheckForm1();">
<tr class=TableHeader >
<td colspan=2>
<?=_("轮班考勤统计")?>
</td>
</tr>
<tr>
<td class="TableContent">
<?=_("起始日期")?>
</td>
<td class="TableData">
<input type="text" name="DATE1" size="10" maxlength="10" class="BigInput" value="<?=$CUR_DATE_FIRST?>" onClick="WdatePicker()" id="start_time"/>
<a href="javascript:set_date('<?=$CUR_DATE?>')"><?=_("设为今日")?></a>
</td>
</tr>
<tr>
<td class="TableContent">
<?=_("截止日期")?>
</td>
<td class="TableData">
<input type="text" name="DATE2" size="10" maxlength="10" class="BigInput" value="<?=$CUR_DATE_END?>"  onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>
<a href="javascript:set_date2('<?=$CUR_DATE?>')"><?=_("设为今日")?></a>
</td>
</tr>
<tr class="TableControl">
<td colspan=2 align=center>
<input type="submit" value="<?=_("轮班考勤统计")?>" class="BigButton" title="<?=_("轮班考勤统计")?>">
</td>
</tr>
</table>
</form>

</body>
</html>