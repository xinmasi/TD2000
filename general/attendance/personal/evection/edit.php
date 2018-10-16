<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$CUR_DATE=date("Y-m-d",time());
$query = "SELECT * from ATTEND_EVECTION where EVECTION_ID='$EVECTION_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $EVECTION_DEST=$ROW["EVECTION_DEST"];
   $EVECTION_DATE1=substr($ROW["EVECTION_DATE1"],0,10);
   $EVECTION_DATE2=substr($ROW["EVECTION_DATE2"],0,10);
   $REASON=$ROW["REASON"];
}

$HTML_PAGE_TITLE = _("修改出差登记");
include_once("inc/header.inc.php");
?>


<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script>
function CheckForm()
{
   var temp_time = "<?=$CUR_DAY?>";
   if(document.form1.EVECTION_DEST.value=="")
   { alert("<?=_("出差地区不能为空！")?>");
     return (false);
   }

   if(document.form1.EVECTION_DATE1.value=="")
   { alert("<?=_("出差开始日期不能为空！")?>");
     return (false);
   }

   if(document.form1.EVECTION_DATE2.value=="")
   { alert("<?=_("出差结束日期不能为空！")?>");
     return (false);
   }

   return (true);
}

</script>


<body class="bodycolor attendance" onload="document.form1.EVECTION_DEST.focus();">

<h5 class="attendance-title"><span class="big3"> <?=_("修改出差登记")?></span>
</h5>

<br>
  <table class="TableBlock" width="90%" align="center">
    <form action="edit_submit.php"  method="post" name="form1" onsubmit="return CheckForm();">
    <tr>
      <td nowrap class="TableData"><?=_("出差地点：")?></td>
      <td class="TableData">
         <input type="text" name="EVECTION_DEST" size="50" maxlength="100" class="BigInput" value="<?=$EVECTION_DEST?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("出差时间：")?></td>
      <td class="TableData">
        <input type="text" name="EVECTION_DATE1" size="15" maxlength="10" class="BigInput" id="start_time" value="<?=$EVECTION_DATE1?>" onClick="WdatePicker()"/>
        <?=_("至")?> 
        <input type="text" name="EVECTION_DATE2" size="15" maxlength="10" class="BigInput" value="<?=$EVECTION_DATE2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("事由：")?></td>
      <td class="TableData">
        <textarea name="REASON" cols="50" rows="4" class="BigInput"><?=$REASON?></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("审批人：")?></td>
      <td class="TableData">
        <select name="LEADER_ID">
<?
include_once("../manager.inc.php");
?>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("提醒：")?></td>
      <td class="TableData">
        <?=sms_remind(6);?>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
          <input type="submit" value="<?=_("确定")?>" class="BigButton" title="<?=_("申请出差")?>">&nbsp;&nbsp;
          <input type="button" value="<?=_("返回")?>" class="BigButton" onclick="location='./'">
      </td>
    </tr>
     <input type="hidden" name="EVECTION_ID" value="<?=$EVECTION_ID?>">
    </form>
  </table>

</body>
</html>
