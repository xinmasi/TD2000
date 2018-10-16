<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("新建周期性事务");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/validation/validationEngine.jquery.min.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/validation/jquery.validationEngine.min.js" charset="utf-8" type="text/javascript"></script>
<script Language="JavaScript">
jQuery(document).ready(function(){
    jQuery("#form1").validationEngine({promptPosition:"centerRight"});
});

var aff_type="day";
function sel_change()
{
   if(aff_type!="")
      document.getElementById(aff_type).style.display="none";
   if(form1.TYPE.value=="2")
      aff_type="day";
   if(form1.TYPE.value=="3")
      aff_type="week";
   if(form1.TYPE.value=="4")
      aff_type="mon";
   if(form1.TYPE.value=="5")
      aff_type="year";
   if(form1.TYPE.value=="6")
      aff_type="workday";
   document.getElementById(aff_type).style.display="";
}

function td_clock1(fieldname)
{
  document.form1.REMIND_TIME2.value="";
  document.form1.REMIND_TIME3.value="";
  document.form1.REMIND_TIME4.value="";
  document.form1.REMIND_TIME5.value="";
  var userAgent = navigator.userAgent.toLowerCase();
  var is_opera = userAgent.indexOf('opera') != -1 && opera.version();
  var is_moz = (navigator.product == 'Gecko') && userAgent.substr(userAgent.indexOf('firefox') + 8, 3);
  var ua_match = /(trident)(?:.*rv:([\w.]+))?/.exec(userAgent) || /(msie) ([\w.]+)/.exec(userAgent);
  var is_ie = ua_match && (ua_match[1] == 'trident' || ua_match[1] == 'msie') ? true : false;
  if(is_ie)
  {
	myleft=document.body.scrollLeft+event.clientX-event.offsetX-80;
	mytop=document.body.scrollTop+event.clientY-event.offsetY+140;

	window.showModalDialog("/inc/clock.php?FIELDNAME="+fieldname,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:280px;dialogHeight:120px;dialogTop:"+mytop+"px;dialogLeft:"+myleft+"px");  
  }
  else
  {
	myleft=document.body.scrollLeft+event.clientX-event.offsetX-80;
	mytop=document.body.scrollTop+event.clientY-event.offsetY+140;
	window.open("/inc/clock.php?FIELDNAME="+fieldname,"parent","status=0,resizable=yes,top="+mytop+",left="+myleft+",dialog=yes,modal=yes,dependent=yes,minimizable=no,toolbar=no,menubar=no,location=no,scrollbars=yes",true);
  }
  
}
</script>



<?
 $CUR_DATE_TIME=date("Y-m-d H:i:s",time());
 $CUR_DATE=date("Y-m-d",time());
 $CUR_TIME=date("H:i:00",time());
 $CUR_END_TIME=date("H:i:00",time()+3600);
?>

<body class="bodycolor" onLoad="document.form1.CONTENT.focus();">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small" style="margin-top:10px;">
  <tr>
    <td class="Big"><span class="big3" style="padding-left:20px;"> <?=_("新建周期性事务")?></span>
    </td>
  </tr>
</table>

<br> 
<form id="form1" action="submit.php"  method="post" name="form1" >
    <table class="" style="width:500px; margin:0 auto" align="center">
        <tr>
            <td nowrap class="" width=100><?=_("起始日期：")?></td>
            <td class="">
                <INPUT type="text"name="BEGIN_TIME"  size="15" value="<?=$CUR_DATE?>" id="start_time" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})">           
                &nbsp;&nbsp;<?=_("为空为当前日期")?>
            </td>
        </tr>
        <tr>
            <td nowrap class=""><?=_("结束日期：")?></td>
            <td class="">
                <INPUT type="text"name="END_TIME" class="datepicker" size="15" value="" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})">       
                &nbsp;&nbsp;<?=_("为空则不结束")?>
            </td>
        </tr>
        <tr>
            <td nowrap  width=100><?=_("开始时间：")?></td>
            <td >
                <INPUT type="text" class="input-small" name="BEGIN_TIME_TIME"  size="15" value="<?=$CUR_TIME?>" onClick="WdatePicker({dateFmt:'HH:mm:ss'})">     
                &nbsp;&nbsp;
            </td>
        </tr>
        <tr>
            <td nowrap ><?=_("结束时间：")?></td>
            <td >
                <INPUT type="text" class="input-small" name="END_TIME_TIME"  size="15" value="<?=$CUR_END_TIME?>" onClick="WdatePicker({dateFmt:'HH:mm:ss'})">
                &nbsp;&nbsp;
            </td>
        </tr>
        <tr>
            <td nowrap > <?=_("事务类型：")?></td>
            <td >
                <select name="CAL_TYPE" >
                    <?=code_list("CAL_TYPE","")?>
                </select>
            </td>
        </tr>
        <tr>
            <td nowrap > <?=_("重复类型：")?></td>
            <td >
                <select name="TYPE"  onChange="sel_change()">
                    <option value="2"><?=_("按日重复")?></option>
                    <option value="3"><?=_("按周重复")?></option>
                    <option value="4"><?=_("按月重复")?></option>
                    <option value="5"><?=_("按年重复")?></option>
                    <option value="6"><?=_("按工作日重复")?></option>
                </select>
            </td>
        </tr>
        <tr id="day">
            <td nowrap > <?=_("重复时间：")?></td>
            <td >
                <input type="text" class="input-small" name="REMIND_TIME2" size="10"  value="<?=$CUR_TIME?>">
                <img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/clock.gif" align="absMiddle" border="0" style="cursor:pointer" onClick="td_clock1('form1.REMIND_TIME2');">
                &nbsp;&nbsp;<?=_("为空为当前时间")?>
            </td>
        </tr>
        <tr id="workday" style="display:none">
            <td nowrap > <?=_("重复时间：")?></td>
            <td >
                <input type="text" class="input-small"  name="REMIND_TIME6" size="10"  value="<?=$CUR_TIME?>">
                <img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/clock.gif" align="absMiddle" border="0" style="cursor:pointer" onClick="td_clock1('form1.REMIND_TIME6');">
                &nbsp;&nbsp;<?=_("为空为当前时间")?>
            </td>
        </tr>
        <tr id="week" style="display:none">
            <td nowrap > <?=_("重复时间：")?></td>
            <td >
            <select name="REMIND_DATE3" style="width:95px;">
                <option value="1" <?if(date("w",time())==1) echo "selected";?>><?=_("星期一")?></option>
                <option value="2" <?if(date("w",time())==2) echo "selected";?>><?=_("星期二")?></option>
                <option value="3" <?if(date("w",time())==3) echo "selected";?>><?=_("星期三")?></option>
                <option value="4" <?if(date("w",time())==4) echo "selected";?>><?=_("星期四")?></option>
                <option value="5" <?if(date("w",time())==5) echo "selected";?>><?=_("星期五")?></option>
                <option value="6" <?if(date("w",time())==6) echo "selected";?>><?=_("星期六")?></option>
                <option value="0" <?if(date("w",time())==0) echo "selected";?>><?=_("星期日")?></option>
            </select>&nbsp;&nbsp;
                <input type="text" class="input-small"  name="REMIND_TIME3" size="10"  value="<?=$CUR_TIME?>">
                <img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/clock.gif" align="absMiddle" border="0" style="cursor:pointer" onClick="td_clock1('form1.REMIND_TIME3');">
                &nbsp;&nbsp;<?=_("为空为当前时间")?>
            </td>
        </tr>
        <tr id="mon" style="display:none">
            <td nowrap > <?=_("重复时间：")?></td>
            <td >
                <select name="REMIND_DATE4" style="width:95px">
<?
for($I=1;$I<=31;$I++)
{
?>
          <option value="<?=$I?>" <?if(date("j",time())==$I) echo "selected";?>><?=$I?><?=_("日")?></option>
<?
}
?>
        </select>&nbsp;&nbsp;
        <input type="text" class="input-small"  name="REMIND_TIME4" size="10"  value="<?=$CUR_TIME?>">
        <img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/clock.gif" align="absMiddle" border="0" style="cursor:pointer" onClick="td_clock1('form1.REMIND_TIME4');">
        &nbsp;&nbsp;<?=_("为空为当前时间")?>
      </td>
    </tr>
    
    <tr id="year" style="display:none">
      <td nowrap > <?=_("重复时间：")?></td>
      <td >
        <select name="REMIND_DATE5_MON" style="width:95px">
<?
for($I=1;$I<=12;$I++)
{
?>
          <option value="<?=$I?>" <?if(date("n",time())==$I) echo "selected";?>><?=$I?><?=_("月")?></option>
<?
}
?>
        </select>&nbsp;&nbsp;
        <select name="REMIND_DATE5_DAY" style="width:95px">
<?
for($I=1;$I<=31;$I++)
{
?>
          <option value="<?=$I?>" <?if(date("j",time())==$I) echo "selected";?>><?=$I?><?=_("日")?></option>
<?
}
?>
        </select>&nbsp;&nbsp;
        <input type="text" class="input-small"  name="REMIND_TIME5" size="10"  value="<?=$CUR_TIME?>">
        <img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/clock.gif" align="absMiddle" border="0" style="cursor:pointer" onClick="td_clock1('form1.REMIND_TIME5');">
        &nbsp;&nbsp;<?=_("为空为当前时间")?>
      </td>
    </tr>
    
    <tr>
      <td nowrap > <?=_("事务内容：")?><font color=red><?=_("(*)")?></font></td>
      <td >
        <textarea name="CONTENT" class="validate[required]" data-prompt-position="centerRight:0,7"></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap  > <?=_("参与者：")?></td>
      <td colspan=3>
        <input type="hidden" name="TAKER" id="TAKER" value="<?=$TAKER?>">
        <textarea cols=35 name="TAKER_NAME" rows=2  wrap="yes" readonly><?=$TAKER_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('8','','TAKER', 'TAKER_NAME','','form1')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('TAKER', 'TAKER_NAME')"><?=_("清空")?></a>
      </td>
    </tr>
     <tr>
      <td nowrap><?=_("事务提醒：")?></td>
      <td colspan="3">
    <?=sms_remind(45);?>
      </td>
    </tr>
    <tr align="center">
      <td colspan="2" nowrap>
        <button type="submit" class="btn btn-info"><?=_("确定")?></button>
        <? if($FROM_TASK_CENTER=='1') {?>
        <button type="button" class="btn" onClick="window.close()"><?=_("关闭")?></button>
        <? }else {?>
        <button type="button" class="btn" onClick="location='../'"><?=_("返回")?></button>
        <? } ?> 
      </td>
    </tr>
  </table>
</form>

</body>
</html>