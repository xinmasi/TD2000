<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

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
function resetTime()
{
   document.form1.ASSESSING_TIME1.value="<?=date("Y-m-d H:i:s",time())?>";
}
function resetTime1()
{
   document.form1.ASSESSING_TIME2.value="<?=date("Y-m-d H:i:s",time())?>";
}
</script>

<body class="bodycolor">

<table border="0" width="80%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("��ѵ�ƻ���ѯ")?></span></td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="search.php"  method="post" name="form1">
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
          <option value="" ><?=_("��ѵ����")?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
          <option value="0"><?=_("�ڲ���ѵ")?></option>
          <option value="1"><?=_("������ѵ")?></option>
        </select>
      </td>
    </tr>
   <tr>
    	<td nowrap class="TableData"><?=_("�����ˣ�")?></td>
      <td class="TableData">
        <input type="text" name="ASSESSING_OFFICER_NAME" size="15" class="BigStatic" readonly value="">
        <input type="hidden" name="ASSESSING_OFFICER" value="">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','', 'ASSESSING_OFFICER', 'ASSESSING_OFFICER_NAME')"><?=_("ѡ��")?></a>
      </td>
   </tr>
   <tr>
      <td nowrap class="TableData"><?=_("�ƻ���������״̬��")?></td>
      <td class="TableData" >
        <select name="ASSESSING_STATUS" style="background: white;" title="">
          <option value="" ><?=_("�ƻ�(����)״̬")?></option>
          <option value="0"><?=_("������")?></option>
          <option value="1"><?=_("����׼")?></option>
          <option value="2"><?=_("δ��׼")?></option>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("����ʱ�䣺")?></td>
      <td class="TableData">
        <input type="text"id="start_time" name="ASSESSING_TIME1" size="20" class="BigInput" value="<?=$ASSESSING_TIME1?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
        &nbsp;&nbsp;<a href="javascript:resetTime();"><?=_("��Ϊ��ǰʱ��")?></a>
        <br><?=_("��")?><br>
        <input type="text" name="ASSESSING_TIME2" size="20" class="BigInput" value="<?=$ASSESSING_TIME2?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'#F{$dp.$D(\'start_time\')}'})"/>
        &nbsp;&nbsp;<a href="javascript:resetTime1();"><?=_("��Ϊ��ǰʱ��")?></a>      
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="submit" value="<?=_("��ѯ")?>" class="BigButton">
      </td>
    </tr>
  </form>
 </table>


</body>
</html>