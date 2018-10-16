<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("��ѵ�ƻ���ѯ");
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
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("��ѵ�ƻ���ѯ")?></span></td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="search.php"  method="get" name="form1">
 <table class="TableBlock" width="450" align="center">
    <tr>
      <td nowrap class="TableData"><?=_("��ѵ�ƻ����ƣ�")?></td>
      <td class="TableData">
        <INPUT type="text"name="T_PLAN_NAME" class=BigStatic size="15"  readonly>
        <INPUT type="hidden" name="T_PLAN_NO" value="">
        <a href="javascript:;" class="orgAdd" onClick="LoadWindow()"><?=_("ѡ��")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("��ѵ������")?></td>
      <td class="TableData" >
        <select name="T_CHANNEL" style="background: white;" title="">
          <option value=""><?=_("��ѡ��")?></option>
          <option value="0"><?=_("�ڲ���ѵ")?></option>
          <option value="1"><?=_("������ѵ")?></option>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("��ѵ��ʽ��")?></td>
      <td class="TableData">
        <select name="T_COURSE_TYPES" style="background: white;" title="<?=_("��ѵ��ʽ���ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
          <option value=""><?=_("��ѡ��")?></option>
          <?=hrms_code_list("T_COURSE_TYPE","")?>
        </select>
      </td>
   </tr>
   <tr>
    	<td nowrap class="TableData"><?=_("��ѵ�ص㣺")?></td>
      <td class="TableData" >
        <INPUT type="text"name="T_ADDRESS" class=BigInput size="15">
      </td>
   </tr>
       <tr>
      <td nowrap class="TableData"><?=_("��ѵ�������ƣ�")?></td>
      <td class="TableData">
        <INPUT type="text" name="T_INSTITUTION_NAME" size="38" class="BigInput" value=""><?=$T_INSTITUTION_NAME?></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("����ʱ�䣺")?></td>
      <td class="TableData">
        <input type="text"id="start_time" name="COURSE_START_TIME1" size="20" class="BigInput" value="<?=$COURSE_START_TIME1?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
        <?=_("��")?>
        <input type="text" name="COURSE_START_TIME2" size="20" class="BigInput" value="<?=$COURSE_START_TIME2?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'#F{$dp.$D(\'start_time\')}'})"/>      
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="submit" value="<?=_("��ѯ")?>" class="BigButton">&nbsp;&nbsp;
        <input type="reset" value="<?=_("����")?>" class="BigButton">&nbsp;&nbsp;
      </td>
    </tr>
  </form>
 </table>


</body>
</html>