<?
include_once("inc/auth.inc.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("人才统计分析");
include_once("inc/header.inc.php");
?>
<script language="JavaScript">
function sel_change()
{
  var el=document.getElementsByName("SUMFIELD").item(1);
  if(el.checked)
  	 document.getElementsByName("AGE").style.display="";
  else
  	 document.getElementsByName("AGE").style.display="none";
}
function clk_submit()
{
	var all_clk=document.getElementsByName("MAP_TYPE");
	for(var i=0;i<all_clk.length;i++)
	{
		if(all_clk[i].checked)
    {
    	 var e_action = "";
    	 if(i==0)
    	    e_action = "pie.php";
    	 else if(i==1)
    	 	  e_action = "column.php";
    	 else
    	 	  e_action = "line.php";
       document.form1.action=e_action;
       document.form1.submit();
       break;
    }
		
	}
}
function my_sbumit()
{
  $position_select=document.form1.position.value;
	document.form1.submit();
	}
</script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/finance1.gif" width="18" HEIGHT="18"><span class="big3">&nbsp;<?=_("人才统计分析")?></span>
    </td>
  </tr>
</table>
<br>
<form action="search.php" method="post" name="form1">
 <table align="center" width="50%" class="TableBlock">
	<tr>
	  <td nowrap class="TableContent"><?=_("基本信息：")?></td>
		<td nowrap class="TableData" colspan=3>
			<input type="radio" name="SUMFIELD" id="SUMFIELD" value="1" checked onclick="sel_change()"><lable for="SUMFIELD1"><?=_("学历")?></lable>&nbsp;
			<input type="radio" name="SUMFIELD" id="SUMFIELD" value="2" checked onclick="sel_change()"><lable for="SUMFIELD2"><?=_("年龄")?></lable>&nbsp;
			<input type="radio" name="SUMFIELD" id="SUMFIELD" value="3" checked onclick="sel_change()"><lable for="SUMFIELD3"><?=_("性别")?></lable>&nbsp;
			<input type="radio" name="SUMFIELD" id="SUMFIELD" value="4" checked onclick="sel_change()"><lable for="SUMFIELD4"><?=_("专业")?></lable>&nbsp;
			<input type="radio" name="SUMFIELD" id="SUMFIELD" value="5" checked onclick="sel_change()"><lable for="SUMFIELD5"><?=_("籍贯")?></lable>&nbsp;
			<input type="radio" name="SUMFIELD" id="SUMFIELD" value="6" checked onclick="sel_change()"><lable for="SUMFIELD6"><?=_("期望工作性质")?></lable>&nbsp;
		</td>
	</tr>
	<tr id="AGE" style="display:none">
   <td nowrap class="TableContent" width="80" ><?=_("年龄范围：")?></td>
    <td class="TableData" width="280" colspan="3">
      <input type="text" name="AGE_RANGE" size="47" maxlength="100" class="BigInput" value="0-20,21-25,26-30,31-35,36-40,41-45,46-50,51-60">
    </td>
  </tr>
	<tr>
    <td nowrap class="TableContent"> <?=_("统计图：")?></td>
    <td class="TableData" colspan=3>
      <input type="radio" name='MAP_TYPE' id='MAP_TYPE1' value="1" checked><label for="MAP_TYPE1"><?=_("饼图")?></label>&nbsp;
      <input type="radio" name='MAP_TYPE' id='MAP_TYPE2' value="2"><label for="MAP_TYPE2"><?=_("柱状图")?></label>&nbsp;
      <input type="radio" name='MAP_TYPE' id='MAP_TYPE3' value="3"><label for="MAP_TYPE3"><?=_("列表")?></label>&nbsp;  
    </td>
  </tr>  
	<tr>
	  <td nowrap class="TableContent"><?=_("应聘岗位：")?></td>
		<td class="TableData" colspan=3>
			<select name="POSITION" class="BigSelect">
				<option value=""></option>
		  <?=hrms_code_list("POOL_POSITION","");?>		
    	</select>
		</td>	
	</tr>
	<tfoot align="center" class="TableFooter">
    <td nowrap colspan="4" align="center">
    	<input type="hidden" name="position_select" value="<?=$POSITION_SELECT?>">
      <input type="button" value="<?=_("确定")?>"  onClick="clk_submit()" class="BigButton">&nbsp;&nbsp;
      <input type="button" value="<?=_("清空")?>" class="BigButton" onClick="location='index.php'">
    </td>
  </tfoot>
 </table>
</form>
</body>
</html>