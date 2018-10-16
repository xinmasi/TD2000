<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("历史记录查询");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script Language="JavaScript">

function LoadWindow2()
{
    var userAgent = navigator.userAgent.toLowerCase();
    var is_opera = userAgent.indexOf('opera') != -1 && opera.version();
    var is_moz = (navigator.product == 'Gecko') && userAgent.substr(userAgent.indexOf('firefox') + 8, 3);
    var ua_match = /(trident)(?:.*rv:([\w.]+))?/.exec(userAgent) || /(msie) ([\w.]+)/.exec(userAgent);
    var is_ie = ua_match && (ua_match[1] == 'trident' || ua_match[1] == 'msie') ? true : false;
    URL="/general/book/borrow_manage/return/bookno_select";
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
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("历史记录查询")?> </span>
    </td>
  </tr>
</table>
<br>

<table class="TableBlock"  width="40%" align="center" >
  <form action="search.php"  method="post" name="form1" >
   <tr>
    <td nowrap class="TableData"><?=_("借书人：")?> </td>
    <td nowrap class="TableData">
        <input type="hidden" name="TO_ID">
        <input type="text" name="TO_NAME" size="18" class="BigStatic" readonly>&nbsp;
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('51','','TO_ID', 'TO_NAME')" title="<?=_("选择借书人")?>"><?=_("指定")?></a><br>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("图书编号：")?></td>
    <td class="TableData">
      <input type="text" name="BOOK_NO" class="BigStatic" size="18" maxlength="100" readonly value="<?=$BOOK_NO?>">&nbsp;
      <input type="button" value="<?=_("选择")?>" class="SmallButton" onClick="LoadWindow2()" title="<?=_("选择图书编号")?>" name="button">
    </td>
   </tr>
   </tr>
    <td nowrap class="TableData"><?=_("借书日期：")?></td>
    <td nowrap class="TableData">
    <?=_("从")?> <INPUT type=text name="START_B" size=10 class=SmallInput  id="start_time" value="<?=$BEGIN_DATE?>" onClick="WdatePicker()">
    <?=_("至")?> <INPUT type=text name="END_B" size=10 class=SmallInput value="<?=$CUR_DATE?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})">
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("状态：")?></td>
    <td nowrap class="TableData">
       <select name="BOOK_STATUS1" class="BigSelect">
       	<option value=""><?=_("选择")?></option>
       	<option value="1"><?=_("已还")?></option>
       	<option value="0"><?=_("未还")?></option>
       </select>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("备注：")?></td>
    <td class="TableData">
      <input type="text" name="BORROW_REMARK" class="BigInput" size="35" maxlength="100" value="">
    </td>
   </tr>
   <tr>
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
        <input type="submit" value="<?=_("查询")?>" class="BigButton" title="<?=_("模糊查询")?>" name="button">
    </td>
   </tr>
  </form>
</table>

</body>
</html>