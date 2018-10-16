<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("图书信息查询");
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
    URL="/general/book/query/bookno_select";
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


<body class="bodycolor"  onload="document.form1.BOOK_NAME.focus();">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("图书信息查询")?> </span>
    </td>
  </tr>
</table>
<br>
<table class="TableBlock"  width="450" align="center" >
  <form action="list.php"  method="post" name="form1" >  
   <tr>
    <td nowrap class="TableData"><?=_("图书类别：")?></td>
    <td nowrap class="TableData">
        <select name="TYPE_ID" class="BigSelect">
          <option value="all"><?=_("所有")?></option>
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
    <td nowrap class="TableData" width="120"><?=_("借阅状态：")?></td>
    <td nowrap class="TableData">
       <select name="LEND" class="BigSelect">
          <option value="" selected><?=_("请选择")?> </option>
          <option value="0"><?=_("未借出")?> </option>
          <option value="1" ><?=_("已借出")?> </option>
       </select>
    </td>
   </tr>       
   <tr>
    <td nowrap class="TableData"><?=_("图书名称：")?> </td>
    <td nowrap class="TableData">
        <input type="text" name="BOOK_NAME" class="BigInput" size="25" maxlength="100">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("图书编号：")?></td>
    <td class="TableData">
      <input type="text" name="BOOK_NO" class="BigStatic" size="18" maxlength="100" readonly value="<?=$BOOK_NO?>">&nbsp;
      <input type="button" value="<?=_("选择")?>" class="SmallButton" onClick="LoadWindow2()" title="<?=_("选择图书编号")?>" name="button">
    </td> 
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("图书作者：")?> </td>
    <td nowrap class="TableData">
        <input type="text" name="AUTHOR" class="BigInput" size="25" maxlength="25">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("ISBN号：")?> </td>
    <td nowrap class="TableData">
        <input type="text" name="ISBN" class="BigInput" size="25" maxlength="50">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("出版社：")?> </td>
    <td nowrap class="TableData">
        <input type="text" name="PUB_HOUSE" class="BigInput" size="25" maxlength="100">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("存放地点：")?> </td>
    <td nowrap class="TableData">
        <input type="text" name="AREA" class="BigInput" size="25" maxlength="200">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("排序字段：")?></td>
    <td nowrap class="TableData">
        <select name="ORDER_FIELD" class="BigSelect">
          <option value="DEPT"><?=_("部门")?> </option>
          <option value="TYPE_ID"><?=_("类别")?> </option>
          <option value="BOOK_NAME"><?=_("书名")?> </option>
          <option value="AUTHOR"><?=_("作者")?> </option>
          <option value="PUB_HOUSE"><?=_("出版社")?> </option>
          <option value="BOOK_NO"><?=_("图书编号")?> </option>
        </select>
    </td>
   </tr>
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
        <input type="submit" value="<?=_("查询")?>" class="BigButton" title="<?=_("模糊查询")?>" name="button">
    </td>
   </tr>
  </form>
</table>

</body>
</html>