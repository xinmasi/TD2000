<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("上下班记录查询");
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

</script>


<body class="bodycolor" onload="document.form1.DATE1.focus();">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("上下班记录查询")?></span><br>
    </td>
  </tr>
</table>

<?
$CUR_DATE_FIRST=date("Y-m-01",time());
$CUR_DATE=date("Y-m-d",time());
?>

<div align="center" class="big1">
<form action="search.php" name="form1" onsubmit="return CheckForm1();">
<b>
<?=_("起始日期：")?><input type="text" id="start_time" name="DATE1" size="10" maxlength="10" class="BigInput" value="<?=$DATE1?>" onClick="WdatePicker()"/>
&nbsp;
<?=_("截止日期：")?><input type="text" name="DATE2" size="10" maxlength="10" class="BigInput" value="<?=$DATE2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>
&nbsp;
<input type="submit" value="<?=_("查询")?>" class="BigButton" title="<?=_("上下班记录查询")?>">
</form>
</div>

</body>
</html>