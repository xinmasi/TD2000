<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("�½�ͼ��");
include_once("inc/header.inc.php");
?>

<script>
function LoadWindow2()
{
  var userAgent = navigator.userAgent.toLowerCase();
  var is_opera = userAgent.indexOf('opera') != -1 && opera.version();
  var is_moz = (navigator.product == 'Gecko') && userAgent.substr(userAgent.indexOf('firefox') + 8, 3);
  var ua_match = /(trident)(?:.*rv:([\w.]+))?/.exec(userAgent) || /(msie) ([\w.]+)/.exec(userAgent);
  var is_ie = ua_match && (ua_match[1] == 'trident' || ua_match[1] == 'msie') ? true : false;
  URL="/general/book/borrow_manage/borrow/bookno_select";
  
  if(is_ie)
  {
    loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
    loc_y=document.body.scrollTop+event.clientY-event.offsetY+170;
    window.showModalDialog(URL,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:320px;dialogHeight:245px;dialogTop:"+loc_y+"px;dialogLeft:"+loc_x+"px",true);
  }
  else
  {
    event =arguments.callee.caller.arguments[0];
    loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
    loc_y=document.body.scrollTop+event.clientY-event.offsetY+170;
    window.open(URL,"parent","status=0,resizable=yes,top="+loc_y+",left="+loc_x+",dialog=yes,modal=yes,dependent=yes,minimizable=no,toolbar=no,menubar=no,location=no,scrollbars=yes",true);
  }
}
function export_csv()
{
	document.form1.action="export.php";
	document.form1.submit();
	
	document.form1.action="list.php";
}

</script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�½�ͼ��")?> </span><br>
    </td>
  </tr>
</table>

<div align="center">
<input type="button"  value="<?=_("�½�ͼ��")?>" class="BigButton" onClick="location='new.php';" title="<?=_("�����µ�ͼ��")?>">&nbsp;&nbsp;
<input type="button"  value="<?=_("����ͼ��")?>" class="BigButton" onClick="location='import.php';" title="<?=_("����ͼ��")?>">
</div>

<br>

<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>
<?
$query = "SELECT POST_PRIV,POST_DEPT from USER where UID='".$_SESSION["LOGIN_UID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $POST_PRIV=$ROW["POST_PRIV"];
    $POST_DEPT=$ROW["POST_DEPT"];
}

?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("����ͼ��")?></span>
    </td>
  </tr>
</table>
<br>

<table class="TableBlock" width="450" align="center" >
  <form action="list.php"  method="post" name="form1" >  
   <tr>
    <td nowrap class="TableData"><?=_("ͼ�����")?></td>
    <td nowrap class="TableData">
        <select name="TYPE_ID" class="BigSelect">
          <option value="all"><?=_("����")?></option>
<?
$query = "SELECT * from BOOK_TYPE order by TYPE_ID";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $TYPE_ID2=$ROW["TYPE_ID"];
   $TYPE_NAME=$ROW["TYPE_NAME"];

?>
          <option value="<?=$TYPE_ID2?>" <? if($TYPE_ID2==$TYPE_ID1) echo "selected";?>><?=$TYPE_NAME?></option>
<?
}
?>
        </select>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("����״̬��")?></td>
    <td nowrap class="TableData">
       <select name="LEND" class="BigSelect">
          <option value="" selected><?=_("��ѡ��")?> </option>
          <option value="0"><?=_("δ���")?> </option>
          <option value="1" ><?=_("�ѽ��")?> </option>
       </select>
    </td>   
   </tr>   
   <tr>
    <td nowrap class="TableData"><?=_("������")?> </td>
    <td nowrap class="TableData">
        <input type="text" name="BOOK_NAME" class="BigInput" size="33" maxlength="100">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("ͼ���ţ�")?></td>
    <td class="TableData">
      <input type="text" name="BOOK_NO" class="BigStatic" size="26" maxlength="100" readonly value="<?=$BOOK_NO?>">&nbsp;
      <input type="button" value="<?=_("ѡ��")?>" class="SmallButton" onClick="LoadWindow2()" title="<?=_("ѡ��ͼ����")?>" name="button">
    </td> 
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("���ߣ�")?> </td>
    <td nowrap class="TableData">
        <input type="text" name="AUTHOR" class="BigInput" size="33" maxlength="100">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("ISBN�ţ�")?> </td>
    <td nowrap class="TableData">
        <input type="text" name="ISBN" class="BigInput" size="33" maxlength="100">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("�����磺")?> </td>
    <td nowrap class="TableData">
        <input type="text" name="PUB_HOUSE" class="BigInput" size="33" maxlength="100">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("��ŵص㣺")?> </td>
    <td nowrap class="TableData">
        <input type="text" name="AREA" class="BigInput" size="33" maxlength="100">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("�����ֶΣ�")?></td>
    <td nowrap class="TableData">
        <select name="ORDER_FIELD" class="BigSelect">
          <option value="DEPT"><?=_("����")?> </option>
          <option value="TYPE_ID"><?=_("���")?> </option>
          <option value="BOOK_NAME"><?=_("����")?> </option>
          <option value="AUTHOR"><?=_("����")?> </option>
          <option value="PUB_HOUSE"><?=_("������")?> </option>
        </select>
    </td>
   </tr>
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
        <input type="submit" value="<?=_("��ѯ")?>" class="BigButton" title="<?=_("ģ����ѯ")?>" name="button">&nbsp;&nbsp;
        <input type="button"  value="<?=_("����")?>" class="BigButton" onClick="export_csv();">
    </td>
   </tr>
  </form>
</table>

</body>
</html>