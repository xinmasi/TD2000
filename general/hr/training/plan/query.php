<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("培训计划查询");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script language="JavaScript">

function LoadWindow()
{
  var userAgent = navigator.userAgent.toLowerCase();
  var is_opera = userAgent.indexOf('opera') != -1 && opera.version();
  var is_moz = (navigator.product == 'Gecko') && userAgent.substr(userAgent.indexOf('firefox') + 8, 3);
  var ua_match = /(trident)(?:.*rv:([\w.]+))?/.exec(userAgent) || /(msie) ([\w.]+)/.exec(userAgent);
  var is_ie = ua_match && (ua_match[1] == 'trident' || ua_match[1] == 'msie') ? true : false;
  URL="plan_no_info/?T_PLAN_NO=<?=$T_PLAN_NO?>";
  if(is_ie)
  {
    loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
    loc_y=document.body.scrollTop+event.clientY-event.offsetY+170;
    window.showModalDialog(URL,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:320px;dialogHeight:245px;dialogTop:"+loc_y+"px;dialogLeft:"+loc_x+"px");
  }
  else
  {
    event =arguments.callee.caller.arguments[0];
    loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
    loc_y=document.body.scrollTop+event.clientY-event.offsetY+170;
    window.open(URL,"parent","status=0,resizable=yes,top="+loc_y+",left="+loc_x+",dialog=yes,modal=yes,dependent=yes,minimizable=no,toolbar=no,menubar=no,location=no,scrollbars=yes",true);
  }
}
</script>

<body class="bodycolor">

<table border="0" width="80%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("培训计划查询")?></span></td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="search.php"  method="get" name="form1">
 <table class="TableBlock" width="450" align="center">
    <tr>
      <td nowrap class="TableData"><?=_("培训计划名称：")?></td>
      <td class="TableData">
        <INPUT type="text"name="T_PLAN_NAME" class=BigStatic size="15"  readonly>
        <INPUT type="hidden" name="T_PLAN_NO" value="">
        <a href="javascript:;" class="orgAdd" onClick="LoadWindow()"><?=_("选择")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("培训渠道：")?></td>
      <td class="TableData" >
        <select name="T_CHANNEL" style="background: white;" title="">
          <option value=""><?=_("请选择")?></option>
          <option value="0"><?=_("内部培训")?></option>
          <option value="1"><?=_("渠道培训")?></option>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("培训形式：")?></td>
      <td class="TableData">
        <select name="T_COURSE_TYPES" style="background: white;" title="<?=_("培训形式可在“人力资源设置”->“HRMS代码设置”模块设置。")?>">
          <option value=""><?=_("请选择")?></option>
          <?=hrms_code_list("T_COURSE_TYPE","")?>
        </select>
      </td>
   </tr>
   <tr>
    	<td nowrap class="TableData"><?=_("培训地点：")?></td>
      <td class="TableData" >
        <INPUT type="text"name="T_ADDRESS" class=BigInput size="15">
      </td>
   </tr>
       <tr>
      <td nowrap class="TableData"><?=_("培训机构名称：")?></td>
      <td class="TableData">
        <INPUT type="text" name="T_INSTITUTION_NAME" size="38" class="BigInput" value=""><?=$T_INSTITUTION_NAME?></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("开课时间：")?></td>
      <td class="TableData">
        <input type="text"id="start_time" name="COURSE_START_TIME1" size="20" class="BigInput" value="<?=$COURSE_START_TIME1?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
        <?=_("至")?>
        <input type="text" name="COURSE_START_TIME2" size="20" class="BigInput" value="<?=$COURSE_START_TIME2?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'#F{$dp.$D(\'start_time\')}'})"/>      
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="submit" value="<?=_("查询")?>" class="BigButton">&nbsp;&nbsp;
        <input type="reset" value="<?=_("重填")?>" class="BigButton">&nbsp;&nbsp;
      </td>
    </tr>
  </form>
 </table>


</body>
</html>