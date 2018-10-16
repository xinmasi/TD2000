<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("培训记录查询");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/validation/validationEngine.jquery.min.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/validation/jquery.validationEngine.min.js" type="text/javascript" charset="utf-8"></script>
<script Language="JavaScript">
var userAgent = navigator.userAgent.toLowerCase();
var is_opera = userAgent.indexOf('opera') != -1 && opera.version();
var is_moz = (navigator.product == 'Gecko') && userAgent.substr(userAgent.indexOf('firefox') + 8, 3);
var ua_match = /(trident)(?:.*rv:([\w.]+))?/.exec(userAgent) || /(msie) ([\w.]+)/.exec(userAgent);
var is_ie = ua_match && (ua_match[1] == 'trident' || ua_match[1] == 'msie') ? true : false;
jQuery(document).ready(function(){
    jQuery("#form1").validationEngine({promptPosition:"centerRight"});
});
function LoadWindow()
{
  URL="staff_user_select/?STAFF_USER_ID=<?=$STAFF_USER_ID?>";
  if(is_ie)
  {
    loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
    loc_y=document.body.scrollTop+event.clientY-event.offsetY+170;
    window.showModalDialog(URL,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:320px;dialogHeight:400px;dialogTop:"+loc_y+"px;dialogLeft:"+loc_x+"px");
  }
  else
  {
    event =arguments.callee.caller.arguments[0];
    loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
    loc_y=document.body.scrollTop+event.clientY-event.offsetY+170;
    window.open(URL,"parent","height=320,width=400,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+loc_y+",left="+loc_x+",resizable=yes");
  }
}
function LoadWindow1()
{
  URL="record_select_query/?T_PLAN_NO=<?=$T_PLAN_NO?>";
  if(is_ie)
  {
    loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
    loc_y=document.body.scrollTop+event.clientY-event.offsetY+170;
    window.showModalDialog(URL,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:320px;dialogHeight:400px;dialogTop:"+loc_y+"px;dialogLeft:"+loc_x+"px");
  }
  else
  {
    event =arguments.callee.caller.arguments[0];
    loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
    loc_y=document.body.scrollTop+event.clientY-event.offsetY+170;
    window.open(URL,"parent","height=320,width=400,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+loc_y+",left="+loc_x+",resizable=yes");
  }
}

function do_export()
{

  document.form1.action='export.php';
  document.form1.submit();
}
function do_search()
{

  document.form1.action='search.php';
  document.form1.submit();
}

</script>

<body class="bodycolor">
<table border="0" width="80%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"><?=_("培训记录查询")?></span></td>
  </tr>
</table>
<form enctype="multipart/form-data" action="#"  method="post" id="form1" name="form1">
<table class="TableBlock" width="70%" align="center">
   <tr>
      <td nowrap class="TableData"><?=_("受训人：")?></td>
      <td class="TableData" >
        <input type="hidden" name="STAFF_USER_ID" value="<?=$STAFF_USER_ID?>">
        <INPUT type="text"name="STAFF_USER_NAME" class=BigStatic size="15" value="<?=$STAFF_USER_NAME?>"  readonly>
       	<a href="javascript:;" class="orgAdd" onClick="LoadWindow()"><?=_("选择")?></a>
      </td>
      <td nowrap class="TableData"><?=_("培训计划名称：")?></td>
      <td class="TableData" colspan=3>
      	<input type="hidden" name="TO_ID" value="">
        <INPUT type="text"name="TO_NAME" class=BigStatic size="20" readonly value="">
        <a href="javascript:;" class="orgAdd" onClick="LoadWindow1()"><?=_("选择")?></a>
		<a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清除")?></a>
    </tr>
    </tr>
    <tr>
    	<td nowrap class="TableData"><?=_("培训机构：")?></td>
      <td class="TableData">
       <input type="text" name="T_INSTITUTION_NAME" size="20" class="BigInput" >
      </td>
      <td nowrap class="TableData"><?=_("培训费用：")?></td>
      <td class="TableData">
       <input type="text" name="TRAINNING_COST" size="10" class="BigInput validate[custom[money]]" data-prompt-position="centerRight:0,-7" >
      </td>
    </tr>
		<tr>
    	<td nowrap class="TableData"><?=_("出勤情况：")?></td>
      <td class="TableData" colspan="5">
       <input type="text" name="DUTY_SITUATION" size="20" class="BigInput validate[maxSize[60]]" data-prompt-position="centerRight:0,-7" >
      </td>
 		</tr>          
		<tr align="center" class="TableControl">
	      <td colspan="6" nowrap>
        <input type="button" value="<?=_("查询")?>" class="BigButton" onClick="do_search()">&nbsp;&nbsp;
        <input type="button" value="<?=_("导出")?>" class="BigButton" onClick="do_export()">&nbsp;&nbsp;
	      </td>
 		</tr>          
</table>
</form>

</table>
</body>
</html>