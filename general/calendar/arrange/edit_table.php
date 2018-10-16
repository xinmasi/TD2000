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
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/calendar.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>


<?
$query="select * from CALENDAR where CAL_ID='$CAL_ID' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $CAL_TIME=$ROW["CAL_TIME"];
   $CAL_TIME=date("Y-m-d H:i:s",$CAL_TIME);
   $END_TIME=$ROW["END_TIME"];
   $END_TIME=date("Y-m-d H:i:s",$END_TIME);
   $CAL_TYPE=$ROW["CAL_TYPE"];
   $CAL_LEVEL=$ROW["CAL_LEVEL"];
   $CONTENT=$ROW["CONTENT"];
   $BEFORE_REMAIND=$ROW["BEFORE_REMAIND"];
   
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
?>

<body class="bodycolor" >
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/calendar.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"><?=_("编辑日程")?></span>
    </td>
  </tr>
</table>
 <form action="update.php"  method="post" name="form1" >
 <table class="TableBlock" width="450" align="center">
    <tr>
      <td nowrap class="TableData"> <?=_("优先级：")?></td>
      <td class="TableData">
        <a id="cal_level" href="javascript:;" class="CalLevel<?=$CAL_LEVEL?>" onClick="showMenu(this.id,'1');" hidefocus="true"><?=cal_level_desc($CAL_LEVEL)?><?=menu_arrow("DOWN")?></a>&nbsp;
        <div id="cal_level_menu" class="attach_div" style="width:110px;">
           <a id="cal_level_" href="javascript:set_option('','cal_level','CalLevel');" class="CalLevel"><?=cal_level_desc("")?></a>
           <a id="cal_level_1" href="javascript:set_option('1','cal_level','CalLevel');" class="CalLevel1"><?=cal_level_desc("1")?></a>
           <a id="cal_level_2" href="javascript:set_option('2','cal_level','CalLevel');" class="CalLevel2"><?=cal_level_desc("2")?></a>
           <a id="cal_level_3" href="javascript:set_option('3','cal_level','CalLevel');" class="CalLevel3"><?=cal_level_desc("3")?></a>
           <a id="cal_level_4" href="javascript:set_option('4','cal_level','CalLevel');" class="CalLevel4"><?=cal_level_desc("4")?></a>
        </div>
        <input type="hidden" id="CAL_LEVEL_FIELD" name="CAL_LEVEL" value="<?=$CAL_LEVEL?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("起始时间：")?></td>
      <td class="TableData">
        <input type="text" class="BigInput" name="CAL_TIME" value="<?=$CAL_TIME?>" size="19" maxlength="19" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
        
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("结束时间：")?></td>
      <td class="TableData">
        <input type="text" class="BigInput" name="END_TIME" value="<?=$END_TIME?>" size="19" maxlength="19" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
    
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("事务类型：")?></td>
      <td class="TableData">
        <select name="CAL_TYPE" class="BigSelect">
          <?=code_list("CAL_TYPE",$CAL_TYPE)?>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("事务内容：")?></td>
      <td class="TableData">
        <textarea name="CONTENT" cols="45" rows="5" class="BigInput"><?=$CONTENT?></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("提醒时间：")?></td>
      <td class="TableData"><?=sprintf(_("提前%s天%s小时%s分钟提醒"),'<input type="text" name="BEFORE_DAY" size="3" class="BigInput" value="'.$BEFORE_DAY.'"> ','<input type="text" name="BEFORE_HOUR" size="3" class="BigInput" value="'.$BEFORE_HOUR.'"> ','<input type="text" name="BEFORE_MIN" size="3" class="BigInput" value="'.$BEFORE_MIN.'"> ')?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("提醒：")?></td>
      <td class="TableData">
<?=sms_remind(5);?>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="hidden" name="CAL_ID" value="<?=$CAL_ID?>">
        <input type="hidden" name="FROM" value="<?=$FROM?>">
        <input type="submit" value="<?=_("确定")?>" class="BigButtonA">&nbsp;&nbsp;
        <input type="button" value="<?=_("关闭")?>" class="BigButtonA" onClick="window.close();">
      </td>
    </tr>
  </table>
</form>

</body>
</html>