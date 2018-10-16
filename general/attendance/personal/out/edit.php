<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$CUR_TIME=date("H:i",time());
$CUR_DAY=date("Y-m-d",time());

$query = "SELECT * from ATTEND_OUT where OUT_ID='$OUT_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $OUT_TYPE=$ROW["OUT_TYPE"];
   $OUT_TIME1=$ROW["OUT_TIME1"];
   $OUT_TIME2=$ROW["OUT_TIME2"];

   $SUBMIT_TIME=substr($ROW["SUBMIT_TIME"],0,10);
}

$HTML_PAGE_TITLE = _("修改外出登记");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
function CheckForm()
{
   var temp_time = "<?=$CUR_DAY?>";

   if(document.form1.OUT_TIME1.value=="" || document.form1.OUT_TIME2.value=="")
   { alert("<?=_("外出起止时间不能为空！")?>");
     return (false);
   }
   if(document.form1.OUT_TYPE.value=="")
   { alert("<?=_("外出原因不能为空！")?>");
     return (false);
   }

   return (true);
}

function td_calendar(fieldname)
{
  myleft=document.body.scrollLeft+event.clientX-event.offsetX+120;
  mytop=document.body.scrollTop+event.clientY-event.offsetY+230;

  window.showModalDialog("/inc/calendar.php?FIELDNAME="+fieldname,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:280px;dialogHeight:215px;dialogTop:"+mytop+"px;dialogLeft:"+myleft+"px");
}

function td_clock(fieldname,pare)
{
  myleft=document.body.scrollLeft+event.clientX-event.offsetX+120;
  mytop=document.body.scrollTop+event.clientY-event.offsetY+230;
  window.showModalDialog("../clock.php?FIELDNAME="+fieldname,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:280px;dialogHeight:120px;dialogTop:"+mytop+"px;dialogLeft:"+myleft+"px");
}
</script>


<body class="bodycolor attendance" onload="document.form1.OUT_TYPE.focus();">

<h5 class="attendance-title"><span class="big3"> <?=_("修改外出登记")?></span></h5><br>
<br>

  <form action="edit_submit.php"  method="post" name="form1" class="big1" onsubmit="return CheckForm();">
  <table class="TableBlock" width="90%" align="center">
    <tr>
      <td nowrap class="TableData"> <?=_("外出原因：")?></td>
      <td class="TableData">
      	 <textarea name="OUT_TYPE" cols="60" rows="3"><?=$OUT_TYPE?></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("外出时间：")?></td>
      <td class="TableData">
         <?=_("日期")?> <input type="text" name="OUT_DATE" size="15" maxlength="10" value="<?=$SUBMIT_TIME?>" onClick="WdatePicker()"/>
         <?=_("从")?> <input type="text" name="OUT_TIME1" size="5" maxlength="5" readonly value="<?=$OUT_TIME1?>"  onClick="WdatePicker({dateFmt:'HH:mm'})">
         <?=_("至")?> <input type="text" name="OUT_TIME2" size="5" maxlength="5" readonly value="<?=$OUT_TIME2?>"  onClick="WdatePicker({dateFmt:'HH:mm'})"><br>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("审批人：")?></td>
      <td class="TableData">
        <select name="LEADER_ID" >
<?
include_once("../manager.inc.php");
?>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("事务提醒：")?></td>
      <td class="TableData"> <?=sms_remind(6);?></td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="submit" value="<?=_("申请外出")?>" class="btn btn-primary" title="<?=_("申请外出")?>">&nbsp;&nbsp;
        <input type="button" value="<?=_("返回上页")?>" class="btn" onclick="location='./'">&nbsp;&nbsp;
      </td>
    </tr>
  </table>
  <input type="hidden" name="OUT_ID" value="<?=$OUT_ID?>">
</form>

</body>
</html>