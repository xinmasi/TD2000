<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
ob_end_clean();
//2013-04-11 ���ӷ�������ѯ�ж�
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";
$query="select * from CALENDAR where CAL_ID='$CAL_ID' and (find_in_set('".$_SESSION["LOGIN_USER_ID"]."',OWNER) or USER_ID='".$_SESSION["LOGIN_USER_ID"]."')";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
if($ROW=mysql_fetch_array($cursor))
{
   $CAL_TIME=$ROW["CAL_TIME"];
   //$CAL_TIME=date("Y-m-d H:i:s",$CAL_TIME);
   $END_TIME=$ROW["END_TIME"];
   //$END_TIME=date("Y-m-d H:i:s",$END_TIME);
   $CAL_TYPE=$ROW["CAL_TYPE"];
   $CAL_LEVEL=$ROW["CAL_LEVEL"];
   $CONTENT=$ROW["CONTENT"];
   $TAKER=$ROW["TAKER"];
   $OWNER=$ROW["OWNER"];
   $TAKER_NAME=GetUserNameById($TAKER);
   $OWNER_NAME=GetUserNameById($OWNER);
   $BEFORE_REMAIND=$ROW["BEFORE_REMAIND"];
   $START_DATE = date("Y-n-j",$CAL_TIME);
   $START_TIME = date("h:i A",$CAL_TIME);
   $FINISHI_DATE = date("Y-n-j",$END_TIME);
   $FINISHI_TIME = date("h:i A",$END_TIME);
   if($BEFORE_REMAIND=="")
   {
      $BEFORE_DAY="0";
      $BEFORE_HOUR="0";
      $BEFORE_MIN="10";
   }
   else
   {
      $REMAIND_ARRAY=explode("|",$BEFORE_REMAIND);
      
      $BEFORE_DAY=intval($REMAIND_ARRAY[0]);
      $BEFORE_HOUR=intval($REMAIND_ARRAY[1]);
      $BEFORE_MIN=intval($REMAIND_ARRAY[2])==0 ? 10 : intval($REMAIND_ARRAY[2]);
   }   
}
if($DRAG==2 && $NEW_TIME!="")
{

   $CAL_TIME=date("Y-m-d H:i:00",$NEW_TIME);

   $TIME_DIFF="+1 hours";
   $END_TIME=date("Y-m-d H:i:00",strtotime($TIME_DIFF,strtotime($CAL_TIME)));

   $CAL_TIME_D=substr($CAL_TIME,0,11);

   if(substr($CAL_TIME,11)=="00:00:00")
   	 $CAL_TIME=$CAL_TIME_D.substr(date("Y-m-d H:i:s"),11);
}


?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/datepicker/bootstrap.datepicker.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/timepicker/bootstrap.timepicker.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/datepicker/bootstrap.datepicker.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/timepicker/bootstrap.timepicker.js"></script>
<script>
(function($){
	jQuery(document).ready(function(){
		
		var dateLangConfigs = {
			monthNames: ['һ��','����','����','����','����','����','����','����','����','ʮ��','ʮһ��','ʮ����'],
            monthNamesShort: ['1','2','3','4','5','6','7','8','9','10','11','12'],
            dayNames: ['������','����һ','���ڶ�','������','������','������','������'],
            dayNamesShort: ['��','һ','��','��','��','��','��']
		};

		$.fn.datepicker.dates = {
			days: dateLangConfigs['dayNames'],
			daysShort: dateLangConfigs['dayNamesShort'],
			daysMin: dateLangConfigs['dayNamesShort'],
			months: dateLangConfigs['monthNames'],
			monthsShort:  dateLangConfigs['monthNamesShort']
		};
		
		$('.calendar-startdate, .calendar-enddate').datepicker({
		   format: "yyyy-m-d"
		}); 
		$('.calendar-starttime, .calendar-endtime').timepicker({ 
		   minuteStep: 5
		});
				
	});
})(jQuery);
</script>

<body>
<form action="update.php" target="form_iframe"  method="post" name="new_cal_form" onSubmit="return CheckCalForm();">

                <div class="control-group" style="width:333px;float:left;">
                    <label><?=_("��������:")?>
                        <textarea name="CONTENT" cols="47" rows="2"><?=$CONTENT?></textarea><font color=red><?=_("(*)")?></font>
                    </label>
                </div>
                    <!--<div class="color_wrapper">
                        <a id="color" class="color" hidefocus="true" index="0"></a>
                        <div id="color_menu" class="color_menu" style="width:20px;left:0px;top:28px;">
                            <a id="calcolor" href="javascript:;" class="color" index="0"></a>
                            <a id="calcolor1" href="javascript:;" class="color1" index="1"></a>
                            <a id="calcolor2" href="javascript:;" class="color2" index="2"></a>
                            <a id="calcolor3" href="javascript:;" class="color3" index="3"></a>
                            <a id="calcolor4" href="javascript:;" class="color4" index="4"></a>
                            <a id="calcolor5" href="javascript:;" class="color5" index="5"></a>
                            <a id="calcolor6" href="javascript:;" class="color6" index="6"></a>
                        </div>-->
                        <input type="hidden" id="COLOR_FIELD" name="COLOR" value="0">
                    </div> 
                <div class="control-group" style="clear:both">
                    <label class=""><?=_("�������ͣ�")?>
                        <select class="smallSelect" name="CAL_TYPE" id="CAL_TYPE">
                            <?=code_list("CAL_TYPE",$CAL_TYPE)?>
                        </select>
                    </label>
                </div>
                <div class="control-group">
                    <span><?=_("��ʼʱ��:")?></span>
                    <input name="START_DATE" id="START_DATE" class="timepadding input input-small calendar-startdate valtype"  type="text" data-valtype="placeholder" value="<?=$START_DATE?>">
                    <span class="bootstrap-timepicker" id="startInput1">
                        <input name="START_TIME" id="START_TIME" class="input input-mini calendar-starttime valtype" placeholder="<?=_("��ʼʱ��")?>" type="text" data-valtype="placeholder" value="<?=$START_TIME?>">
                    </span>         
                </div>
                <div class="control-group">
                    <span><?=_("����ʱ��:")?></span>
                        <input name="FINISHI_DATE" id="FINISHI_DATE" class="timepadding input input-small calendar-enddate valtype" placeholder="<?=_("��������")?>" type="text" data-valtype="placeholder" value="<?=$FINISHI_DATE?>">
                    <span class="bootstrap-timepicker" id="endInput1">
                        <input name="FINISHI_TIME" id="FINISHI_TIME" class="input input-mini calendar-endtime valtype" placeholder="<?=_("����ʱ��")?>" type="text" data-valtype="placeholder" value="<?=$FINISHI_TIME?>">
                    </span>         
                </div>
                <div class="control-group" >
                    <label class="textareapadding inline"><?=_("������:")?>
                        <input type="hidden" name="TAKER" id="TAKER" value="<?=$TAKER?>">
                        <textarea cols=35 name="TAKER_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$TAKER_NAME?></textarea>
                        <a href="javascript:;" class="orgAdd" onClick="SelectUser('8','','TAKER', 'TAKER_NAME','','new_cal_form')"><?=_("���")?></a>
                        <a href="javascript:;" class="orgClear" onClick="ClearUser('TAKER', 'TAKER_NAME')"><?=_("���")?></a>
                    </label>
                </div>
                <div class="control-group" id="OWNER1">
                    <label class="textareapadding inline"><?=_("������:")?>
                        <input type="hidden" name="OWNER" id="OWNER" value="<?=$OWNER?>">
                        <textarea cols=35 name="OWNER_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$OWNER_NAME?></textarea>
                        <a href="javascript:;" class="orgAdd" onClick="SelectUser('8','','OWNER', 'OWNER_NAME','','new_cal_form')"><?=_("���")?></a>
                        <a href="javascript:;" class="orgClear" onClick="ClearUser('OWNER', 'OWNER_NAME')"><?=_("���")?></a>
                    </label>
                </div>
                <div id="remindTime">
                    <div class="control-group">
                        <span><?=_("����ʱ��:")?></span>
                        <?=sprintf(_("��ǰ%s��%sСʱ%s��������"),'<input type="text" name="BEFORE_DAY" size="3" class="input input-small" style="width:30px" value="'.$BEFORE_DAY.'"> ','<input type="text" name="BEFORE_HOUR" size="3" class="input input-small" style="width:30px" value="'.$BEFORE_HOUR.'"> ','<input type="text" name="BEFORE_MIN" size="3" class="input input-small" style="width:30px" value="'.$BEFORE_MIN.'"> ')?>                        
                   </div>
               </div>
                <div class="control-group" id="smsDiv">
                    <label class="checkbox inline remindText"><?=_("����:")?>
                    </label>
                    <?=sms_remind(5);?>
                </div>
              <div  class="control-group" style="clear:both; text-align:center">
            <input type="hidden" name="CAL_ID" value="<?=$CAL_ID?>">
           <input type="hidden" name="DRAG" id="DRAG" value="<?=$DRAG?>">
            <!--<button class="btn btn-primary" id="save"><?=_("ȷ��")?></button><br/>
            <button class="btn" data-dismiss="modal" aria-hidden="true"><?=_("�ر�")?></button>
        -->
        <button type="submit" class="btn btn-primary"><?=_("ȷ��")?></button>&nbsp;&nbsp;
        <button type="button" class="btn" onClick="close_modify('<?=$DRAG?>');"><?=_("�ر�")?></button>
              </div>
 
 
 <!--<table class="TableBlock" width="450" align="center">
    <tr>
      <td nowrap class="TableData"> <?=_("�������ͣ�")?></td>
      <td class="TableData">
        <select name="CAL_TYPE" class="BigSelect">
          <?=code_list("CAL_TYPE",$CAL_TYPE)?>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("��ʼʱ�䣺")?></td>
      <td class="TableData">
        <input type="text" class="BigInput" name="CAL_TIME" value="<?=$CAL_TIME?>" size="19" maxlength="19" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
      </td>
        <td nowrap class="TableData"> <?=_("����ʱ�䣺")?></td>
      <td class="TableData">
        <input type="text" class="BigInput" name="END_TIME" value="<?=$END_TIME?>" size="19" maxlength="19" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
    
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("�������ݣ�")?></td>
      <td class="TableData" colspan=3>
        <textarea name="CONTENT" cols="47" rows="3" class="BigInput"><?=$CONTENT?></textarea><font color=red><?=_("(*)")?></font>
      </td>
    </tr>
     <tr>
      <td nowrap class="TableData" > <?=_("�����ߣ�")?></td>
      <td class="TableData" colspan=3>
        <input type="hidden" name="TAKER" id="TAKER" value="<?=$TAKER?>">
        <textarea cols=35 name="TAKER_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$TAKER_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('8','','TAKER', 'TAKER_NAME','','new_cal_form')"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('TAKER', 'TAKER_NAME')"><?=_("���")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" > <?=_("�����ߣ�")?></td>
      <td class="TableData" colspan=3>
        <input type="hidden" name="OWNER" id="OWNER" value="<?=$OWNER?>">
        <textarea cols=35 name="OWNER_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$OWNER_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('8','','OWNER', 'OWNER_NAME','','new_cal_form')"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('OWNER', 'OWNER_NAME')"><?=_("���")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("����ʱ�䣺")?></td>
      <td class="TableData" colspan=3><?=sprintf(_("��ǰ%s��%sСʱ%s��������"),'<input type="text" name="BEFORE_DAY" size="3" class="BigInput" value="'.$BEFORE_DAY.'"> ','<input type="text" name="BEFORE_HOUR" size="3" class="BigInput" value="'.$BEFORE_HOUR.'"> ','<input type="text" name="BEFORE_MIN" size="3" class="BigInput" value="'.$BEFORE_MIN.'"> ')?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("���ѣ�")?></td>
      <td class="TableData" colspan=3>
<?=sms_remind(5);?>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="4" nowrap>
        <input type="hidden" name="CAL_ID" value="<?=$CAL_ID?>">
        <input type="hidden" name="DRAG" id="DRAG" value="<?=$DRAG?>">
        <input type="submit" value="<?=_("ȷ��")?>" class="BigButtonA">&nbsp;&nbsp;
        <input type="button" value="<?=_("�ر�")?>" class="BigButtonA" onclick="close_modify('<?=$DRAG?>');">
      </td>
    </tr>
  </table>-->
</form>
</body>