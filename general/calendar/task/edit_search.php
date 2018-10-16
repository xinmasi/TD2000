<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("任务设置");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/calendar/css/calendar_person.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/calendar.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.SUBJECT.value=="")
   {
   	 alert("<?=_("任务标题不能为空！")?>");
      return (false);
   }

   return (true);
}
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
if($TASK_ID=="")
	$NAME=_("新建");
else
 $NAME=_("编辑");
?>

<body class="bodycolor" onLoad="document.form1.SUBJECT.focus();">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small" style="margin-bottom:10px; margin-top:10px;">
  <tr>
    <td class="Big"><span class="big3"> <?=$NAME?></span>
    </td>
  </tr>
</table>

 <table class="table table-bordered" style="width:450px; margin:0 auto">
  <form action="<?if($TASK_ID=="")echo "insert";else echo"update";?>.php" method="post" name="form1" onsubmit="return CheckForm();">
    <tr>
      <td nowrap ><?=_("排序号：")?></td>
      <td class="TableData" colspan=3>
        <INPUT type="text"name="TASK_NO"  size="4" value="<?=$TASK_NO?>">
      </td>
    </tr>
    <tr>
      <td nowrap > <?=_("类型：")?></td>
      <td class="TableData" width=120>
        <select name="TASK_TYPE" >
          <option value="1" <?if($TASK_TYPE=="1") echo "selected";?>><?=_("工作")?></option>
          <option value="2" <?if($TASK_TYPE=="2") echo "selected";?>><?=_("个人")?></option>
        </select>
      </td>
      <td nowrap  width=40> <?=_("状态：")?></td>
      <td class="TableData" width=180>
        <select name="TASK_STATUS" >
          <option value="1" <?if($TASK_STATUS=="1") echo "selected";?>><?=_("未开始")?></option>
          <option value="2" <?if($TASK_STATUS=="2") echo "selected";?>><?=_("进行中")?></option>
          <option value="3" <?if($TASK_STATUS=="3") echo "selected";?>><?=_("已完成")?></option>
          <option value="4" <?if($TASK_STATUS=="4") echo "selected";?>><?=_("等待其他人")?></option>
          <option value="5" <?if($TASK_STATUS=="5") echo "selected";?>><?=_("已推迟")?></option>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap ><?=_("颜色：")?></td>
      <td class="TableData">
        <a id="color" href="javascript:;" class="CalColor<?=$COLOR?>" onClick="showMenu(this.id,'1');" hidefocus="true"><?=menu_arrow("DOWN")?></a>&nbsp;
        <div id="color_menu" class="attach_div" style="position:absolute; top:0px; left:25px;">
           <a id="color_" href="javascript:set_option('','color','CalColor');" class="CalColor"><?=cal_color_desc('')?></a>
           <a id="color_1" href="javascript:set_option('1','color','CalColor');" class="CalColor1"><?=cal_color_desc('1')?></a>
           <a id="color_2" href="javascript:set_option('2','color','CalColor');" class="CalColor2"><?=cal_color_desc('2')?></a>
           <a id="color_3" href="javascript:set_option('3','color','CalColor');" class="CalColor3"><?=cal_color_desc('3')?></a>
           <a id="color_4" href="javascript:set_option('4','color','CalColor');" class="CalColor4"><?=cal_color_desc('4')?></a>
           <a id="color_5" href="javascript:set_option('5','color','CalColor');" class="CalColor5"><?=cal_color_desc('5')?></a>
           <a id="color_6" href="javascript:set_option('6','color','CalColor');" class="CalColor6"><?=cal_color_desc('6')?></a>
        </div>
        <input type="hidden" id="COLOR_FIELD" name="COLOR" value="<?=$COLOR?>">
      </td>
      <td nowrap ><?=_("优先级：")?></td>
      <td class="TableData">
       <a id="important" href="javascript:;" class="CalLevel<?=$IMPORTANT?>" onClick="showMenu(this.id,'1');" hidefocus="true"><?=cal_level_desc($IMPORTANT)?><?=menu_arrow("DOWN")?></a>&nbsp;
        <div id="important_menu" class="attach_div" style="width:110px;">
           <a id="important_" href="javascript:set_option('','important','CalLevel');" class="CalLevel"><?=cal_level_desc("")?></a>
           <a id="important_1" href="javascript:set_option('1','important','CalLevel');" class="CalLevel1"><?=cal_level_desc("1")?></a>
           <a id="important_2" href="javascript:set_option('2','important','CalLevel');" class="CalLevel2"><?=cal_level_desc("2")?></a>
           <a id="important_3" href="javascript:set_option('3','important','CalLevel');" class="CalLevel3"><?=cal_level_desc("3")?></a>
           <a id="important_4" href="javascript:set_option('4','important','CalLevel');" class="CalLevel4"><?=cal_level_desc("4")?></a>
        </div>
        <input type="hidden" id="IMPORTANT_FIELD" name="IMPORTANT" value="<?=$IMPORTANT?>">
      </td>
    </tr>
    <tr>
      <td nowrap ><?=_("任务标题：")?></td>
      <td class="TableData" colspan=3>
        <INPUT type="text"name="SUBJECT"  size="50" value="<?=$SUBJECT?>">
      </td>
    </tr>
    <tr>
      <td nowrap ><?=_("起止日期：")?></td>
      <td class="TableData" colspan=3>
        <INPUT type="text"name="BEGIN_DATE" class="input-small"  size="10" value="<?=$BEGIN_DATE?>" onClick="WdatePicker()">
   <?=_("至")?>
        <INPUT type="text"name="END_DATE" class="input-small"  size="10" value="<?=$END_DATE?>" onClick="WdatePicker()">
      
      </td>
    </tr>
    <tr>
      <td nowrap > <?=_("任务详细：")?></td>
      <td class="TableData" colspan=3>
        <textarea name="CONTENT"><?=$CONTENT?></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap ><?=_("事务提醒：")?></td>
      <td class="TableData" colspan="3">
<?=sms_remind(5);?>
      </td>
    </tr>
    <tr>
      <td nowrap > <?=_("提醒时间：")?></td>
      <td class="TableData" colspan=3>
        <input type="text" name="REMIND_TIME" size="20"  value="<?=$REMIND_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
       
        <?=_("为空则不提醒")?>
      </td>
    </tr>
    <tr>
      <td nowrap ><?=_("完成情况：")?></td>
      <td class="TableData" colspan=3>
        <?=_("完成率")?> <INPUT type="text"name="RATE" class="input-small"   size="3" value="<?=$RATE?>"> %&nbsp;&nbsp;
        <?=_("完成时间")?> <input type="text" name="FINISH_TIME" class="input-small"  size="20"  value="<?=$FINISH_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
  
      </td>
    </tr>
    <tr>
      <td nowrap ><?=_("工作量：")?></td>
      <td class="TableData" colspan=3>
        <?=_("工作总量")?> <INPUT type="text"name="TOTAL_TIME" class="input-small"  size="4" value="<?=$TOTAL_TIME?>"> <?=_("小时")?>
        <?=_("实际工作")?> <input type="text" name="USE_TIME" class="input-small"  size="4"  value="<?=$USE_TIME?>"> <?=_("小时")?>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="4" nowrap style="text-align:center">
      	<INPUT type="hidden" name="PAGE_START" value="<?=$PAGE_START?>">
      	<INPUT type="hidden" name="TASK_ID" value="<?=$TASK_ID?>">
        <button type="submit" class="btn btn-info "><?=_("确定")?></button>&nbsp;&nbsp;
        <button type="button" class="btn" onClick="location='search.php'"><?=_("返回")?></button>
      </td>
    </tr>
  </table>
</form>

</body>
</html>