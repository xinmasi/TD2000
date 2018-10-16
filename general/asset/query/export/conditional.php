<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("导出");
include_once("inc/header.inc.php");
?>
<body class="bodycolor">
<form name="form1" method="post" action="export.php">
<script>
	function selected(){
		var isSelectedObj = document.getElementById("isSelected");
		var trObj = document.getElementById("conditionTr");
		if(isSelectedObj.checked){
			trObj.style.display="";
		}else{
			trObj.style.display="none";
		}
	}

function func_insert()
{
 for (i=0; i<document.form1.select2.options.length; i++)
 {
   if(document.form1.select2.options[i].selected)
   {
     option_text=document.form1.select2.options[i].text;
     option_value=document.form1.select2.options[i].value;
     option_style_color=document.form1.select2.options[i].style.color;

     var my_option = document.createElement("OPTION");
     my_option.text=option_text;
     my_option.value=option_value;
     my_option.style.color=option_style_color;

     pos=document.form1.select2.options.length;
     document.form1.select1.options.add(my_option,pos);
     document.form1.select2.remove(i);
     i--;
  }
 }//for
}

function func_delete()
{
 for (i=0;i<document.form1.select1.options.length;i++)
 {
   if(document.form1.select1.options[i].selected)
   {
     option_text=document.form1.select1.options[i].text;
     option_value=document.form1.select1.options[i].value;

     var my_option = document.createElement("OPTION");
     my_option.text=option_text;
     my_option.value=option_value;

     if(option_text.indexOf("[<?=_("必选")?>]")>0)
        continue;//  return;
     pos=document.form1.select2.options.length;
     document.form1.select2.options.add(my_option,pos);
     document.form1.select1.remove(i);
     i--;
  }
 }//for
}

function func_select_all1()
{
 for (i=document.form1.select1.options.length-1; i>=0; i--)
   document.form1.select1.options[i].selected=true;
}

function func_select_all2()
{
 for (i=document.form1.select2.options.length-1; i>=0; i--)
   document.form1.select2.options[i].selected=true;
}

function func_up()
{
  sel_count=0;
  for (i=document.form1.select1.options.length-1; i>=0; i--)
  {
    if(document.form1.select1.options[i].selected)
       sel_count++;
  }

  if(sel_count==0)
  {
     alert("<?=_("请选择其中一项！")?>");
     return;
  }
  else if(sel_count>1)
  {
     alert("<?=_("只能选择其中一项！")?>");
     return;
  }

  i=document.form1.select1.selectedIndex;

  if(i!=0)
  {
    var my_option = document.createElement("OPTION");
    my_option.text=document.form1.select1.options[i].text;
    my_option.value=document.form1.select1.options[i].value;

    document.form1.select1.options.add(my_option,i-1);
    document.form1.select1.remove(i+1);
    document.form1.select1.options[i-1].selected=true;
  }
}

function func_down()
{
  sel_count=0;
  for (i=document.form1.select1.options.length-1; i>=0; i--)
  {
    if(document.form1.select1.options[i].selected)
       sel_count++;
  }

  if(sel_count==0)
  {
     alert("<?=_("请选择其中一项！")?>");
     return;
  }
  else if(sel_count>1)
  {
     alert("<?=_("只能选择其中一项！")?>");
     return;
  }

  i=document.form1.select1.selectedIndex;

  if(i!=document.form1.select1.options.length-1)
  {
    var my_option = document.createElement("OPTION");
    my_option.text=document.form1.select1.options[i].text;
    my_option.value=document.form1.select1.options[i].value;

    document.form1.select1.options.add(my_option,i+2);
    document.form1.select1.remove(i);
    document.form1.select1.options[i+1].selected=true;
  }
}

function mysubmit()
{
   fld_str="";
   for (i=0; i< document.form1.select1.options.length; i++)
   {
      options_value=document.form1.select1.options[i].value;
      fld_str+=options_value+",";
    }

   //location="export.php?POS=<?=$POS?>&FLD_STR=" + fld_str;
   document.form1.fieldArrStr.value = fld_str;
   window.document.form1.submit();
}
</script>
<table class="TableList" cellspacing='0' cellpadding='' width="90%" align='center' border='0' style='margin-top:4px;margin-bottom: 4px;'>
	<tr class="TableHeader">
		<td align="left"><?=_("导出类型")?>: </td>
	</tr>
	<tr>
		<td class='TableData'><input type="radio" name="condition" value="thisPage" id="condition1" checked /> <label for="condition1"><?=_("导出本页")?></td>
	</tr>
	<tr>
		<td class='TableData'><input type="radio" name="condition" id="condition2" value="query" /> <label for="condition2"><?=_("导出查询")?></label></td>
	</tr>
	<tr>
		<td class='TableData'><input type="radio" name="condition" id="condition3" value="all" /> <label for="condition3"><?=_("导出所有")?></label></td>
	</tr>
	<tr class="TableHeader">
		<td align="left"><?=_("导出字段")?>: </td>
	</tr>
	<tr>
		<td class='TableData'><input type="checkbox" name="isSelected" id="isSelected" value="selected" onClick="selected()" /> <label for="isSelected"><?=_("选择导出字段")?></label></td>
	</tr>
	<tr id="conditionTr" style="display:none">
		<td class='TableData'>
		
			<table width="500" cellspacing="0" cellpadding="0" align="center" border="0" class="TableList">
			  <tr class="TableHeader" bgcolor="#CCCCCC">
				<td align="center"><?=_("排序")?></td>
				<td align="center"><b><?=_("已选中字段")?></b></td>
				<td align="center"><?=_("选择")?></td>
				<td align="center" valign="top"><b><?=_("备选字段")?></b></td>
			  </tr>
			  <tr>
				<td align="center" bgcolor="#F7F7F7">
				  <input type="button" class="SmallInput" value=" <?=_("↑")?> " onClick="func_up();">
				  <br><br>
				  <input type="button" class="SmallInput" value=" <?=_("↓")?> " onClick="func_down();">
				</td>
				<td valign="top" align="center" bgcolor="#F7F7F7">
				<select  name="select1[]" id="select1" ondblclick="func_delete();" MULTIPLE style="width:200px;height:280px">
					
				</select>
				<input type="button" value=" <?=_("全选")?> " onClick="func_select_all1();" class="SmallInput">
				</td>

				<td align="center" bgcolor="#F7F7F7">
				  <input type="button" class="SmallInput" value=" <?=_("←")?> " onClick="func_insert();">
				  <br><br>
				  <input type="button" class="SmallInput" value=" <?=_("→")?> " onClick="func_delete();">
				</td>

				<td align="center" valign="top" bgcolor="#F7F7F7">
				<select  name="select2[]" id="select2" ondblclick="func_insert();" MULTIPLE style="width:200px;height:280px">
					<?
					    $query = "select * from FIELDSETTING where TABLENAME='CP_CPTL_INFO'";
                        $cursor= exequery(TD::conn(),$query);
						while($ROW=mysql_fetch_array($cursor))
						{
						   $FIELDNAME .= $ROW["FIELDNAME"].",";
						   $FIELDNO   .= $ROW["FIELDNO"].",";
						}
                       	if($FIELDNAME!="")
						{
							$FIELDNAME = substr($FIELDNAME,0,strlen($FIELDNAME)-1);
							$FIELDNO   = substr($FIELDNO,0,strlen($FIELDNO)-1); 
							
						}
                        $thArr=array(_("资产编号"),_("资产名称"),_("资产类别"),_("所属部门"),_("资产性质"),_("加类型"),_("资产原值"),_("残值率"),_("折旧年限"),_("累计折旧"),_("月折旧额"),_("启用日期"),_("保管人"),_("备注"));
						$thArr1=explode(",",_("$FIELDNAME"));
						$thArr=array_merge($thArr,$thArr1);
                       	$fieldArr=array('CPTL_NO','CPTL_NAME','TYPE_NAME','DEPT_NAME','CPTL_KIND','PRCS_LONG_DESC','CPTL_VAL','CPTL_BAL','DPCT_YY','SUM_DPCT','MON_DPCT','FROM_YYMM','KEEPER','REMARK');
					    $fieldArr1=explode(",",$FIELDNO);
					    $fieldArr=array_merge($fieldArr,$fieldArr1);
						for($i = 0; $i < count($fieldArr); $i ++)
						{
							if($fieldArr[$i]=="")
							{
								continue;
							}
							echo "<option value='".$fieldArr[$i]."'>$thArr[$i]</option>";
						}
						
					?>
				</select>
				<input type="button" value=" <?=_("全选")?> " onClick="func_select_all2();" class="SmallInput">
				</td>
			  </tr>

			  <tr bgcolor="#CCCCCC">
				<td align="center" valign="top" colspan="4">
				<?=_("点击条目时，可以组合CTRL或SHIFT键进行多选")?><br>
				</td>
			  </tr>

			</table>
		</td>
	</tr>
	<tr>
		<td class='TableData' align="center">
			<input type="hidden" name="WHERE_CLAUSE" value="<?=$WHERE_CLAUSE?>" />
			<input type="hidden" name="ORDER_CLAUSE" value="<?=$ORDER_CLAUSE?>" />
			<input type="hidden" name="LIMIT_CLAUSE" value="<?=$LIMIT_CLAUSE?>" />
			<input type="hidden" name="fieldArrStr" value="" />
                        <input type="button" class="BigButton" value = "<?=_("导出")?>"  onClick="mysubmit();" />&nbsp;&nbsp;
                        <input type="button" class="BigButton" value = "<?=_("关闭")?>"  onClick="window.close();" />
		</td>
	</tr>
</table>
</form>
</body>
</html>