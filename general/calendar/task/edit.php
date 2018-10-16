<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("任务设置");
include_once("inc/header.inc.php");
?>


<meta name="save" content="history">
<style>
.saveHistory  {behavior:url(#default#savehistory);}
</style>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/calendar/css/calendar_person.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>	
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/calendar.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>

<script Language="JavaScript">
function CheckForm()
{
   var TASK_NO=document.form1.TASK_NO.value;
	 var SMS_REMIND=document.form1.SMS_REMIND
	 var SMS2_REMIND=document.form1.SMS2_REMIND;
	 var RATE=document.form1.RATE.value;
	 var TOTAL_TIME=document.form1.TOTAL_TIME.value;
	 var USE_TIME=document.form1.USE_TIME.value;
	 
	 if(TASK_NO!="" && isNaN(TASK_NO))
   {
		  alert("<?=_("排序号应为数字型")?>");
		  document.getElementById('TASK_NO').focus(); 
		  return false;
	 }
   if(document.form1.SUBJECT.value=="")
   {
   	 alert("<?=_("任务标题不能为空！")?>");
   	 document.getElementById('SUBJECT').focus();
     return false;
   }
  
   if(typeof(SMS_REMIND)!="undefined")
   {
	    if(document.form1.REMIND_TIME.value=="" && document.getElementById("SMS_REMIND").checked)
		{
			alert("<?=_("提醒时间不能为空！")?>");
			document.getElementById('REMIND_TIME').focus();
			return  false;	
		}
   }
   if(typeof(SMS2_REMIND)!="undefined")
   {
	    if(document.form1.REMIND_TIME.value=="" && document.getElementById("SMS2_REMIND").checked)
		{
			alert("<?=_("提醒时间不能为空！")?>");
			document.getElementById('REMIND_TIME').focus();
			return  false;	
		}
   }
   if(RATE!="" && isNaN(RATE))
   {
     alert("<?=_("完成率应为数字型")?>");
     document.getElementById('RATE').focus();
     return false;
   }
     if(TOTAL_TIME!="" && isNaN(TOTAL_TIME))
   {
     alert("<?=_("工作总量应为数字型")?>");
     document.getElementById('TOTAL_TIME').focus();
     return false;
   }
     if(USE_TIME!="" && isNaN(USE_TIME))
   {
     alert("<?=_("实际工作应为数字型")?>");	
     document.getElementById('USE_TIME').focus();
     return false;
   }

   return (true);
}

jQuery(document).ready(function(){
    $("#color").click(function(){
        $("#color_menu").slideToggle();
    });
    $("a[id^=CalColor]").each(function(i){
        $(this).click(function(){
            $("#color").css({"background-color":$(this).css('background-color')});
            $("#COLOR_FIELD").val($(this).attr("index"));
            $("#color_menu").hide();
        })
    })
});
</script>

<?
$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("H:i:s",time());

if($TASK_ID!="")
{
  $query="select * from TASK where TASK_ID='$TASK_ID'";
  $cursor= exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor))
  {
    $TASK_ID=$ROW["TASK_ID"];
    $TASK_NO=$ROW["TASK_NO"];
    $USER_ID=$ROW["USER_ID"];

    if($USER_ID!=$_SESSION["LOGIN_USER_ID"])
       exit;

    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $END_DATE=$ROW["END_DATE"];

    if($BEGIN_DATE=="0000-00-00")
       $BEGIN_DATE="";

    if($END_DATE=="0000-00-00")
       $END_DATE="";

    $TASK_TYPE=$ROW["TASK_TYPE"];
    $TASK_STATUS=$ROW["TASK_STATUS"];
    $COLOR=$ROW["COLOR"];
    $IMPORTANT=$ROW["IMPORTANT"];
    $RATE=$ROW["RATE"];
    $FINISH_TIME=$ROW["FINISH_TIME"];
    $TOTAL_TIME=$ROW["TOTAL_TIME"];
    $USE_TIME=$ROW["USE_TIME"];

    if($FINISH_TIME=="0000-00-00 00:00:00")
       $FINISH_TIME="";

    $SUBJECT=$ROW["SUBJECT"];
    $SUBJECT=td_htmlspecialchars($SUBJECT);

    $CONTENT=$ROW["CONTENT"];
  }
}
else
{
  $BEGIN_DATE=$CUR_DATE;
}
?>

<body class="bodycolor" onLoad="document.form1.SUBJECT.focus();">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><span class="big3"> <?if($TASK_ID=="")echo _("新建");else echo _("编辑");?><?=_("任务")?></span>
    </td>
  </tr>
</table>

 <table id="editTable"  width="450" align="center">
  <form action="<?if($TASK_ID=="")echo "insert";else echo"update";?>.php"  method="post" name="form1" onsubmit="return CheckForm();">
    <tr>
      <td nowrap ><?=_("排序号：")?></td>
      <td  colspan=3>
        <input type="text"name="TASK_NO"  id="TASK_NO" value="<?=$TASK_NO?>">  <?=_("(排序号应为数字型)")?>
      </td>
    </tr>
    <tr>
      <td nowrap ><?=_("任务标题：")?></td>
      <td  colspan=2>
        <input type="text"name="SUBJECT" id="SUBJECT" value="<?=$SUBJECT?>"> <font color=red><?=_("(*)")?></font>
      </td>
      <td style="position:relative">
        <a id="color" class="CalColor<?=$COLOR?>" hidefocus="true"><?=menu_arrow("DOWN")?></a>&nbsp;
        <div id="color_menu" class="attach_div" style="width:140px;position:absolute;left:25px;top:10px;">
           <a id="CalColor" href="javascript:;" class="CalColor" index="0"></a>
           <a id="CalColor1" href="javascript:;" class="CalColor1" index="1"></a>
           <a id="CalColor2" href="javascript:;" class="CalColor2" index="2"></a>
           <a id="CalColor3" href="javascript:;" class="CalColor3" index="3"></a>
           <a id="CalColor4" href="javascript:;" class="CalColor4" index="4"></a>
           <a id="CalColor5" href="javascript:;" class="CalColor5" index="5"></a>
           <a id="CalColor6" href="javascript:;" class="CalColor6" index="6"></a>
        </div>
        <input type="hidden" id="COLOR_FIELD" name="COLOR" value="<?=$COLOR?>">
      </td>
    </tr>
    <tr>
      <td nowrap > <?=_("类型：")?></td>
      <td  width=120>
        <select name="TASK_TYPE">
          <option value="1" <?if($TASK_TYPE=="1") echo "selected";?>><?=_("工作")?></option>
          <option value="2" <?if($TASK_TYPE=="2") echo "selected";?>><?=_("个人")?></option>
        </select>
      </td>
      <td nowrap  width=40><?=_("状态：")?></td>
      <td  width=180>
        <select name="TASK_STATUS">
          <option value="1" <?if($TASK_STATUS=="1") echo "selected";?>><?=_("未开始")?></option>
          <option value="2" <?if($TASK_STATUS=="2") echo "selected";?>><?=_("进行中")?></option>
          <option value="3" <?if($TASK_STATUS=="3") echo "selected";?>><?=_("已完成")?></option>
          <option value="4" <?if($TASK_STATUS=="4") echo "selected";?>><?=_("等待其他人")?></option>
          <option value="5" <?if($TASK_STATUS=="5") echo "selected";?>><?=_("已推迟")?></option>
        </select>
      </td>
    </tr>
    <tr style="line-height:45px">
      <td nowrap ><?=_("优先级：")?></td>
      <td>
       <select name="IMPORTANT">
          <option value=""><?=_("未指定")?></option>
          <option value="1" <? if($IMPORTANT==1) echo "selected";?>><?=_("重要/紧急")?></option>
          <option value="2" <? if($IMPORTANT==2) echo "selected";?>><?=_("重要/不紧急")?></option>
          <option value="3" <? if($IMPORTANT==3) echo "selected";?>><?=_("不重要/紧急")?></option>
          <option value="4" <? if($IMPORTANT==4) echo "selected";?>><?=_("不重要/不紧急")?></option>
        </select>
      </td>
    </tr>
 
    <tr>
      <td nowrap ><?=_("起止日期：")?></td>
      <td  colspan=3>
        <INPUT type="text"name="BEGIN_DATE" class="input-small" value="<?=$BEGIN_DATE?>" onClick="WdatePicker()">
        <?=_("至")?>
        <INPUT type="text"name="END_DATE" class="input-small" value="<?=$END_DATE?>" onClick="WdatePicker()">
       
      </td>
    </tr>
    <tr>
      <td nowrap > <?=_("任务详细：")?></td>
      <td  colspan=3>
        <textarea name="CONTENT"><?=$CONTENT?></textarea>
      </td>
    </tr>
    <tr style="line-height:45px;">
      <td nowrap ><?=_("事务提醒：")?></td>
      <td  colspan="3" id="sendRemind">
<?=sms_remind(5);?>
      </td>
    </tr>
    <tr>
      <td nowrap > <?=_("提醒时间：")?></td>
      <td  colspan=3>
        <input type="text" name="REMIND_TIME" size="20" value="<?=$REMIND_TIME?>" id="REMIND_TIME" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
        <?=_("(选中事务或手机提醒时提醒时间不能为空)")?>
      </td>
    </tr>
    <tr>
      <!--<td nowrap ><?=_("完成情况：")?></td>-->
      <td  colspan=4>&nbsp;
        <?=_("完成率:")?> <input type="text" class="input-mini" name="RATE" id="RATE"  size="3" value="<?=$RATE?>">&nbsp;%&nbsp;&nbsp;&nbsp;
        <?=_("完成时间：")?> <input type="text" class="input-mini" name="FINISH_TIME" size="20"  value="<?=$FINISH_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
       
      </td>
    </tr>
    <tr>
      <!--<td nowrap ><?=_("工作量：")?></td>-->
      <td  colspan=4>
        <?=_("工作总量:")?> <INPUT type="text"name="TOTAL_TIME" class="input-mini" size="4" value="<?=$TOTAL_TIME?>"> <?=_("小时")?>
        <?=_("实际工作:&nbsp;")?> <input type="text" name="USE_TIME" size="4" class="input-mini" value="<?=$USE_TIME?>"> <?=_("小时")?>
      </td>
    </tr>
    <tr align="center" >
      <td colspan="4" nowrap>
      	<INPUT type="hidden" name="PAGE_START" value="<?=$PAGE_START?>">
      	<INPUT type="hidden" name="TASK_ID" value="<?=$TASK_ID?>">
        <button type="submit" class="btn btn-info"><?=_("确定")?></button>
        <? if($FROM_TASK_CENTER=='1') {?>
        <button type="button" class="btn" onClick="window.close()"><?=_("关闭")?></button>
        <? }else {?>
        <button type="button" class="btn" onClick="location='index.php'"><?=_("返回")?></button>
        <? } ?> 
      </td>
    </tr>
  </table>
</form>

</body>
</html>