<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("值班查询与导出");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/calendar.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
function my_event(the_type)
{
  if(the_type==1)
  {
  	 document.form1.action="search.php";
  	 document.form1.submit();  	
  }
  else
  {
  	 document.form1.action="export.php";
  	 document.form1.submit();  	 	
  	
  }
}

</script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/calendar.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("值班查询与导出")?></span>
    </td>
  </tr>
</table>

<br>
<form action="search.php"  method="post" name="form1">
 <table class="TableBlock" width="60%" align="center">
    <tr height="30">
      <td nowrap class="TableData"> <?=_("人员部门：")?></td>
      <td class="TableData" colspan="3">
         <input type="hidden" name="TO_ID" value="">
         <textarea cols=35 name="TO_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
         <a href="javascript:;" class="orgAdd" onClick="SelectDept('')"><?=_("添加")?></a>
         <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>      	
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("排班类型：")?></td>
      <td class="TableData">
        <select name="PAIBAN_TYPE" style="background: white;">
        	<option value=""></option>
          <?=code_list("PAIBAN_TYPE","$PAIBAN_TYPE")?>
        </select>
      </td>    	
      <td nowrap class="TableData"> <?=_("值班类型：")?></td>
      <td class="TableData">
      	<select name="ZHIBAN_TYPE" style="background: white;">
      		<option value=""></option>
          <?=code_list("ZHIBAN_TYPE","$ZHIBAN_TYPE")?>
        </select>          
      </td>     	  
    </tr>        
    <tr>
      <td nowrap class="TableData"> <?=_("值班日期：")?></td>
      <td class="TableData" colspan="3">
        <?=_("从")?> <input type="text" name="ZBSJ_B" size="10" maxlength="10" class="BigInput" id="start_time" value="<?=$ZBSJ_B?>" onClick="WdatePicker()"/>    	  
        <?=_("到")?> <input type="text" name="ZBSJ_E" size="10" maxlength="10" class="BigInput" value="<?=$ZBSJ_E?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>  	  
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("值班要求：")?></td>
      <td class="TableData">
      	<input type="BigInput" name="ZBYQ" value=<?=$ZBYQ?>>
      </td>
      <td nowrap class="TableData"> <?=_("备注：")?></td>
      <td class="TableData">
      	<input type="BigInput" name="BEIZHU" value=<?=$BEIZHU?>>
      </td>
    </tr> 
    <tr align="center" class="TableControl">
      <td colspan="4" nowrap>
      	<input type="button" value="<?=_("查询")?>" class="BigButton" onClick="my_event(1);">&nbsp;&nbsp;
        <input type="button" value="<?=_("导出")?>" class="BigButton" onClick="my_event(2);">
      </td>
    </tr>        
 </table>
</form>

</body>
</html>