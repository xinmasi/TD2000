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

$HTML_PAGE_TITLE = _("�޸ĳ���Ǽ�");
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
   { alert("<?=_("�����������Ϊ�գ�")?>");
     return (false);
   }

   if(document.form1.EVECTION_DATE1.value=="")
   { alert("<?=_("���ʼ���ڲ���Ϊ�գ�")?>");
     return (false);
   }

   if(document.form1.EVECTION_DATE2.value=="")
   { alert("<?=_("����������ڲ���Ϊ�գ�")?>");
     return (false);
   }

   return (true);
}

</script>


<body class="bodycolor attendance" onload="document.form1.EVECTION_DEST.focus();">

<h5 class="attendance-title"><span class="big3"> <?=_("�޸ĳ���Ǽ�")?></span>
</h5>

<br>
  <table class="TableBlock" width="90%" align="center">
    <form action="edit_submit.php"  method="post" name="form1" onsubmit="return CheckForm();">
    <tr>
      <td nowrap class="TableData"><?=_("����ص㣺")?></td>
      <td class="TableData">
         <input type="text" name="EVECTION_DEST" size="50" maxlength="100" class="BigInput" value="<?=$EVECTION_DEST?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("����ʱ�䣺")?></td>
      <td class="TableData">
        <input type="text" name="EVECTION_DATE1" size="15" maxlength="10" class="BigInput" id="start_time" value="<?=$EVECTION_DATE1?>" onClick="WdatePicker()"/>
        <?=_("��")?> 
        <input type="text" name="EVECTION_DATE2" size="15" maxlength="10" class="BigInput" value="<?=$EVECTION_DATE2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("���ɣ�")?></td>
      <td class="TableData">
        <textarea name="REASON" cols="50" rows="4" class="BigInput"><?=$REASON?></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("�����ˣ�")?></td>
      <td class="TableData">
        <select name="LEADER_ID">
<?
include_once("../manager.inc.php");
?>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("���ѣ�")?></td>
      <td class="TableData">
        <?=sms_remind(6);?>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
          <input type="submit" value="<?=_("ȷ��")?>" class="BigButton" title="<?=_("�������")?>">&nbsp;&nbsp;
          <input type="button" value="<?=_("����")?>" class="BigButton" onclick="location='./'">
      </td>
    </tr>
     <input type="hidden" name="EVECTION_ID" value="<?=$EVECTION_ID?>">
    </form>
  </table>

</body>
</html>
