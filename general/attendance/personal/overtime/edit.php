<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$query = "SELECT * from ATTENDANCE_OVERTIME where OVERTIME_ID='$OVERTIME_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $OVERTIME_CONTENT=$ROW["OVERTIME_CONTENT"];
   $START_TIME=$ROW["START_TIME"];
   $END_TIME=$ROW["END_TIME"];
   $RECORD_TIME=$ROW["RECORD_TIME"];
   $OVERTIME_HOURS=$ROW["OVERTIME_HOURS"];
   $OVERTIME_HOURS_ARRAY=explode(_("Сʱ"),$OVERTIME_HOURS);
   $OVERTIME_MINUTES_ARRAY=explode(_("��"),$OVERTIME_HOURS_ARRAY[1]);
   $OVERTIME_CONTENT=str_replace("<","&lt",$OVERTIME_CONTENT);
   $OVERTIME_CONTENT=str_replace(">","&gt",$OVERTIME_CONTENT);
   $OVERTIME_CONTENT=gbk_stripslashes($OVERTIME_CONTENT);
}

$HTML_PAGE_TITLE = _("�޸ļӰ�Ǽ�");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/validation/validationEngine.jquery.min.css">
<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/validation/jquery.validationEngine.min.js" type="text/javascript" charset="utf-8"></script>
<script>
function count_time(){
    var s_time = jQuery("#start_time").val();
    var e_time = jQuery("#end_time").val();
    
    if(s_time=='' || e_time==''){
        jQuery("#overtime_hours").val(0);
        jQuery("#overtime_minutes").val(0);
    }else{
        var s_ms = Date.parse(new Date(s_time.replace(/-/g, "/")));
        var e_ms = Date.parse(new Date(e_time.replace(/-/g, "/")));
        
        if(e_ms > s_ms){
            var diff_ms = parseInt((e_ms - s_ms)/1000);
            var diff_h = parseInt(diff_ms/3600);
            var diff_m = parseInt((diff_ms % 3600)/60);
            jQuery("#overtime_hours").val(diff_h);
            jQuery("#overtime_minutes").val(diff_m);
        }else{
            jQuery("#overtime_hours").val(0);
            jQuery("#overtime_minutes").val(0);
        }
    }
}

jQuery(document).ready(function(){
    jQuery("#form1").validationEngine({promptPosition:"centerRight"});
    
    jQuery("#start_time").blur(function(){
        count_time();
    });
    
    jQuery("#end_time").blur(function(){
        count_time();
    });
});
function CheckForm()
{
 if(document.form1.START_TIME.value=="" || document.form1.END_TIME.value=="")
 { alert("<?=_("�Ӱ���ֹʱ�䲻��Ϊ�գ�")?>");
   return (false);
 }
 if(document.form1.OVERTIME_CONTENT.value=="")
 { alert("<?=_("�Ӱ����ݲ���Ϊ�գ�")?>");
   return (false);
 }
 if(document.form1.OVERTIME_HOURS.value < 0 && document.form1.OVERTIME_HOURS.value!="")
 { alert("<?=_("�Ӱ�ʱ��(Сʱ)������ڻ����0")?>");
   return (false);
 }
 if(document.form1.OVERTIME_MINUTES.value < 0 && document.form1.OVERTIME_MINUTES.value!="")
 { alert("<?=_("�Ӱ�ʱ��(����)������ڻ����0")?>");
   return (false);
 } 
 return (true);
}
function resetTime()
{
   document.form1.START_TIME.value="<?=date("Y-m-d H:i:s",time())?>";
   count_time();
}
function resetTime1()
{
   document.form1.END_TIME.value="<?=date("Y-m-d H:i:s",time())?>";
   count_time();
}
</script>


<body class="bodycolor attendance" onLoad="document.form1.OVERTIME_CONTENT.focus();">

<h5 class="attendance-title"><span class="big3"> <?=_("�޸ļӰ�Ǽ�")?></span>
</h5>
<br>

<form action="edit_submit.php"  method="post" name="form1" class="big1" onSubmit="return CheckForm();">
<table class="TableBlock" width="90%" align="center">
  <tr>
   <td nowrap class="TableData"> <?=_("�Ӱ࿪ʼʱ�䣺")?></td>
   <td class="TableData">
    <input type="text" id="start_time" name="START_TIME" size="20" maxlength="20"  value="<?=$START_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
        &nbsp;&nbsp;<a href="javascript:resetTime();"><?=_("��Ϊ��ǰʱ��")?></a>
   </td>
 </tr>
 <tr> 
   <td nowrap class="TableData"> <?=_("�Ӱ����ʱ�䣺")?></td>
   <td class="TableData">
    <input type="text" id="end_time" name="END_TIME" size="20" maxlength="20"  value="<?=$END_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
        &nbsp;&nbsp;<a href="javascript:resetTime1();"><?=_("��Ϊ��ǰʱ��")?></a>
   </td>
  </tr>
  <tr>
   <td nowrap class="TableData"> <?=_("�Ӱ�ʱ����")?></td>
   <td class="TableData">
   	<input type="text" name="OVERTIME_HOURS" id="overtime_hours"  size="2" maxlength="2"  value="<?=$OVERTIME_HOURS_ARRAY[0]?>"><?=_("Сʱ")?>
   	<input type="text" name="OVERTIME_MINUTES" id="overtime_minutes" size="2" maxlength="2"  value="<?=$OVERTIME_MINUTES_ARRAY[0]?>"><?=_("��")?>
   	 (<?=_("ע������д�򰴼Ӱ����ʱ����Ӱ࿪ʼʱ���ֵ����õ�Сʱ��������")?>)
   </td>
  </tr>
  <tr>
   <td nowrap class="TableData"> <?=_("�Ӱ����ݣ�")?></td>
   <td class="TableData">
   	<textarea name="OVERTIME_CONTENT"  cols="60" rows="3"><?=$OVERTIME_CONTENT?></textarea>
   </td>
  </tr>
  <tr>
   <td nowrap class="TableData"> <?=_("�����ˣ�")?></td>
   <td class="TableData">
    <select name="APPROVE_ID" >
<?
include_once("../manager.inc.php");
?>
    </select>
   </td>
  </tr>
  <tr>
   <td nowrap class="TableData"> <?=_("�������ѣ�")?></td>
   <td class="TableData"> <?=sms_remind(6);?></td>
 </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="submit" value="<?=_("����Ӱ�")?>" class="btn" title="<?=_("�������")?>">&nbsp;&nbsp;
        <input type="button" value="<?=_("������ҳ")?>" class="btn" onClick="location='./'">&nbsp;&nbsp;
      </td>
    </tr>
  </table>
  <input type="hidden" name="OVERTIME_ID" value="<?=$OVERTIME_ID?>">
</form>

</body>
</html>