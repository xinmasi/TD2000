<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("���ŷ��͹���");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
function CheckForm()
{
	if(document.form1.BEGIN_DATE.value!=""&&document.form1.END_DATE.value!=""&&document.form1.BEGIN_DATE.value>=document.form1.END_DATE.value)
   { alert("<?=_("����ʱ�䲻��С�ڻ������ʼʱ�䣡")?>");
     return (false);
   }
   return true;
}

function delete_sms()
{
 msg='<?=_("ȷ��Ҫɾ��ָ����Χ�ڵĶ��ż�¼��")?>\n<?=_("�ѷ��ͳɹ��Ķ��Ž����ᱻɾ��")?>';
 if(window.confirm(msg))
 {
  document.form1.DELETE_FLAG.value="1";
  document.form1.submit();
 }
}
function LoadWindow3()
{
  URL="/module/addr_select/?FIELD=MOBIL_NO&TO_ID=TO_ID1";
  loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
  loc_y=document.body.scrollTop+event.clientY-event.offsetY+170;
  //window.open(URL,"read_notify","height=400,width=550,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left=150,resizable=yes");
  if(window.showModalDialog){
    window.showModalDialog(URL,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:450px;dialogHeight:350px;dialogTop:"+loc_y+"px;dialogLeft:"+loc_x+"px");
  }else{
    window.open(URL,"load_dialog_win","height=350,width=450,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+loc_y+",left="+loc_x+",resizable=yes,modal=yes,dependent=yes,dialog=yes,minimizable=no",true);
  }
}
</script>

<body class="bodycolor">
<?
 //$BEGIN_DATE=date("Y-m-d 00:00:00",time());
 $END_DATE=date("Y-m-d H:i:s",time());
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/mobile_sms.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("���ŷ��͹���")?></span>
    </td>
  </tr>
</table>
<br>

  <table class="TableBlock" width="500" align="center">
  <form action="search.php" name="form1" onsubmit="return CheckForm();">
    <tr>
      <td nowrap class="TableData"><?=_("���ŷ���״̬��")?></td>
      <td class="TableData">
        <select name="SEND_FLAG" class="BigSelect">
          <option value="ALL"><?=_("����")?></option>
          <option value="0"><?=_("������")?></option>
          <option value="3"><?=_("������")?>...</option>
          <option value="1"><?=_("���ͳɹ�")?></option>
          <option value="2"><?=_("���ͳ�ʱ")?></option>
        </select>
      </td>
    </tr>
     <tr>
      <td nowrap class="TableData"><?=_("������[�ڲ��û�]��")?></td>
      <td nowrap class="TableData">
        <input type="hidden" name="TO_ID" value="<?=$TO_ID?>">
        <textarea cols=55 name=TO_NAME rows=3 class="BigStatic" wrap="yes" readonly><?=$TO_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('42','','TO_ID', 'TO_NAME','1')"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('TO_ID', 'TO_NAME')"><?=_("���")?></a>
      </td>
    </tr>
    <tr>
    	  <tr>
      <td nowrap class="TableData"><?=_("������[�ⲿ����]��")?></td>
      <td class="TableData">
        <?=_("����֮�����ö��ŷָ���ÿ��һ��")?><br>
        <textarea cols=55 name=TO_ID1 rows=3 class="BigInput" wrap="yes"><?=$TO_ID1?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="LoadWindow3()" title="<?=_("��ͨѶ�����������")?>"><?=_("���")?></a>
      </td>
    </tr>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("���ݣ�")?></td>
      <td class="TableData"><textarea cols=40 name="CONTENT" rows="3" class="BigInput" wrap="yes"></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("��ʼʱ�䣺")?></td>
      <td class="TableData"><input type="text" name="BEGIN_DATE" size="20" maxlength="20" class="BigInput" value="<?=$BEGIN_DATE?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
        
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("��ֹʱ�䣺")?></td>
      <td class="TableData"><input type="text" name="END_DATE" size="20" maxlength="20" class="BigInput" value="<?=$END_DATE?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
          
      </td>
    </tr>
    <tr >
      <td nowrap class="TableControl" colspan="2" align="center">
      	  <input type="hidden" name="DELETE_FLAG" value="0">
          <input type="submit" value="<?=_("��ѯ")?>" class="BigButton" title="<?=_("���в�ѯ")?>">&nbsp;&nbsp;&nbsp;
          <input type="button" value="<?=_("ɾ��")?>" class="BigButton" title="<?=_("ɾ��ָ����Χ�ڵĶ��ż�¼")?>" onclick="delete_sms();">
      </td>
    </tr>
    </form>
  </table>
</body>
</html>
