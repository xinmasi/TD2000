<?
include_once("inc/auth.inc.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("统计分析");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script>
function clk_submit(GROUP_ID,FLOW_ID)
{
	var all_clk=document.getElementsByName("MAP_TYPE");
	for(var i=0;i<all_clk.length;i++)
	{
		if(all_clk[i].checked)
    {
    	 var e_action = "";
    	 if(i==0)
    	    e_action = "analysis.php?CHOSE=1&GROUP_ID="+GROUP_ID+"&FLOW_ID="+FLOW_ID;
    	 else if(i==1)
    	 	  e_action = "analysis.php?CHOSE=2&GROUP_ID="+GROUP_ID+"&FLOW_ID="+FLOW_ID;
    	 else if(i==2)
    	 	  e_action = "analysis.php?CHOSE=3&GROUP_ID="+GROUP_ID+"&FLOW_ID="+FLOW_ID;
    	 //else
    	 	 // e_action = "line.php";
       document.form1.action=e_action;
       document.form1.submit();
       break;
    }
		
	}
}
</script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("详细情况")?> </span><br></td>   	
    <td align="right" valign="bottom" class="small1"></td>
  </tr>
</table>
<form action="analysis.php" method="post" name="form1" target="tu_main">
<table align="center" width="100%" class="TableBlock">
  <tr>
    <td nowrap class="TableContent" width="80" ><?=_("基本信息：")?></td>
    <td class="TableData" nowrap colspan="3" >
       <input type="radio" name='SUMFIELD' id='SUMFIELD1' value="1" checked ><label for="SUMFIELD1"><?=_("分数")?></label>&nbsp;
       	<input type="text" name="SCORE_RANGE" size="20" maxlength="100" class="BigInput" value="0,25,26,31,35,36,41,45,46,50,51,60">
    </td>
  </tr>
    <tr>
      <td nowrap class="TableContent" width="60"> <?=_("统计图：")?></td>
      <td class="TableData" nowrap>
         <input type="radio" name='MAP_TYPE' id='MAP_TYPE1' value="1" checked><label for="MAP_TYPE1"><?=_("饼图")?></label>&nbsp;
         <input type="radio" name='MAP_TYPE' id='MAP_TYPE2' value="2"><label for="MAP_TYPE2"><?=_("柱状图")?></label>&nbsp;
         <input type="radio" name='MAP_TYPE' id='MAP_TYPE3' value="3"><label for="MAP_TYPE3"><?=_("统计表")?></label>&nbsp;
         
      </td>
      <td class="TableContent" width="80"> <?=_("所属部门：")?></td>
      <td class="TableData" >
      	<input type="hidden" name="TO_ID">
		    <textarea cols=20 name=TO_NAME rows=2 class="BigStatic" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept('')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>
      	<input type="hidden" name="MODULE" value="<?=$MODULE?>">
        <input type="button" value="<?=_("确定")?>"  onClick="clk_submit('<?=$GROUP_ID ?>','<?=$FLOW_ID ?>')">&nbsp;&nbsp;        
    </tr> 
</table>
</form>
</body>
</html>
