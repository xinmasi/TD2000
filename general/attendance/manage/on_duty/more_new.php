<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");


$HTML_PAGE_TITLE = _("�����Ű�");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/calendar.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.ZHIBANREN.value=="")
   { alert("<?=_("��ѡ����Ա��")?>");
     return (false);
   }
   if(document.form1.ZBSJ_B.value=="")
   { alert("<?=_("�Ű࿪ʼʱ�䲻��Ϊ�գ�")?>");
     return (false);
   }
   return (true);
}
function resetTime()
{
   document.form1.ZBSJ_B.value="<?=date("Y-m-d H:i:s",time())?>";
}

function check_int(str)
{
   if(parseInt(str)!=str)
   {
      alert("<?=_("����������")?>");
      document.form1.TIME_LONG.value="8";
      return (false);
   }

}
</script>

<body class="bodycolor">
<?
	 $CUR_TIME=date("Y-m-d H:i:s",time());
   if($ZBSJ_B=="" || $ZBSJ_B=="undefined")
      $ZBSJ_B0 = time();
   else
      $ZBSJ_B0 = strtotime(date("Y-m-d",$ZBSJ_B)." ".substr($CUR_TIME,11));
   $ZBSJ_B=date("Y-m-d H:i:s",$ZBSJ_B0);  
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small" style="margin-top:5px;">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/calendar.gif" align="absMiddle" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("�����Ű�")?></span>
    </td>
  </tr>
</table>
 <table class="TableBlock" width="500" align="center">
  <form action="more_add.php"  method="post" name="form1" onsubmit="return CheckForm();">
    <tr height="30">
      <td nowrap class="TableData"> <?=_("ֵ����Ա��")?></td>
      <td class="TableData" colspan="3">     
        <input type="hidden" name="ZHIBANREN" value="">
        <textarea cols=40 name="ZHIBANREN_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('7','','ZHIBANREN', 'ZHIBANREN_NAME')"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('ZHIBANREN', 'ZHIBANREN_NAME')"><?=_("���")?></a>         
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("�Ű����ͣ�")?></td>
      <td class="TableData">
        <select name="PAIBAN_TYPE" style="background: white;" title="<?=_("�Ű����Ϳ��ڡ�ϵͳ����->��ϵͳ�������á�ģ�����á�")?>">
          <?=code_list("PAIBAN_TYPE","$PAIBAN_TYPE")?>
        </select>
      </td>    	
      <td nowrap class="TableData"> <?=_("ֵ�����ͣ�")?></td>
      <td class="TableData">
      	<select name="ZHIBAN_TYPE" style="background: white;" title="<?=_("ֵ�����Ϳ��ڡ�ϵͳ����->��ϵͳ�������á�ģ�����á�")?>">
          <?=code_list("ZHIBAN_TYPE","$ZHIBAN_TYPE")?>
        </select>          
      </td>     	  
    </tr>        
    <tr>
      <td nowrap class="TableData"> <?=_("ֵ�࿪ʼʱ�䣺")?></td>
      <td class="TableData" colspan="3">
        <input type="text" name="ZBSJ_B" size="19" maxlength="19" class="BigInput" value="<?=$ZBSJ_B?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
        &nbsp;&nbsp;<a href="javascript:resetTime();"><?=_("��Ϊ��ǰʱ��")?></a>      
      </td>   	  
    </tr>        
    <tr>
      <td nowrap class="TableData"> <?=_("ÿ��ʱ����")?></td>
      <td class="TableData" colspan="3">
        <input type="text" name="TIME_LONG" size="10" class="BigInput" value="8" onblur="check_int(this.value)"/><?=_("Сʱ")?>  
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("ֵ��Ҫ��")?></td>
      <td class="TableData" colspan="3">
        <textarea name="ZBYQ" cols="50" rows="4" class="BigInput"><?=$ZBYQ?></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("��ע��")?></td>
      <td class="TableData" colspan="3">
        <textarea name="BEIZHU" cols="50" rows="4" class="BigInput"><?=$BEIZHU?></textarea>
      </td>
    </tr>       
    <tr>
      <td nowrap class="TableData"> <?=_("���ѣ�")?></td>
      <td class="TableData" colspan="3"> 
<?=sms_remind(55);?>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td nowrap colspan="4">
        <input type="hidden" name="PAIBAN_ID" value="<?=$PAIBAN_ID?>">
        <input type="submit" value="<?=_("ȷ��")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("�ر�")?>" class="BigButton" onclick="parent.close();">
      </td>
    </tr>
  </table>
</form>

</body>
</html>