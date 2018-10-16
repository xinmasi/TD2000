<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
if($ITEM_ID!="")
{
 $query = "SELECT * from SAL_ITEM where ITEM_ID='$ITEM_ID'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
    {
     $ITEM_ID=$ROW["ITEM_ID"];
     $ITEM_NAME=$ROW["ITEM_NAME"];
     $FORMULA=$ROW["FORMULA"];
     $FORMULANAME=$ROW["FORMULANAME"];
    }
 }

 if($FLAG=="1")
 {
  $textFormula=str_replace('%','<',$_POST["textFormula"]);
    $textFormula=str_replace('`','>',$textFormula);
    $FormulaID=str_replace('%','<',$_POST["FormulaID"]);
    $FormulaID=str_replace('`','>',$FormulaID);
  $query="update SAL_ITEM set FORMULANAME='$textFormula',FORMULA='$FormulaID' where ITEM_ID='$ITEM_ID'";
  exequery(TD::conn(),$query);
?>
  <script language=javascript>
  	window.close();
  </script>
<?
 }

$HTML_PAGE_TITLE = _("公式编辑");
include_once("inc/header.inc.php");
?>
<script language="javascript">
var parent_window =window.opener;
//存储光标的位置
function storeCaret (textEl)
{
    if (textEl.createTextRange)
        textEl.caretPos = document.selection.createRange().duplicate();
}

//将LIST内容插入到光标指定位置
function insertAtCaret (textEl, text)
{

    if (textEl.createTextRange && textEl.caretPos)
    {
        var caretPos = textEl.caretPos;
        caretPos.text = text;//caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
    }
    else
    {
        textEl.value += text;
    }

    textEl.focus();
}

//编辑公式
function funEditFormula ()
{
    if (document.formMain.selectList.selectedIndex < 0)
        return;

    var strValue = document.formMain.selectList.item(document.formMain.selectList.selectedIndex).value;
    var aryData = strValue.split (";");

    if (aryData [2] == "IN")
    {
        alert ("<?=_("您所选择的字段是输入项，不是公式，不能编辑！")?>");
        document.formMain.selectList.focus ();
        return;
    }

    if (!confirm ("<?=_("确定要编辑该公式吗？")?>"))
        return;

    var strFormulaCont = "";
    for (i = 0; i < aryDataFormula.length; i ++)
    {
        if (aryDataFormula [i]["PAYPROID"] == aryData [0])
        {
            strFormulaCont = aryDataFormula [i]["CONTENT"];
            break;
        }
    }

    document.formMain.textFormula.value = strFormulaCont;
    document.formMain.btnFormulaID.value = aryData [0];
    document.formMain.btnFormulaName.value = document.formMain.selectList.item(document.formMain.selectList.selectedIndex).text;

    document.formMain.FormulaName.value = "<?=_("公式编辑：")?>" + document.formMain.btnFormulaName.value + "";
}

//公式校验
function funCheck ()
{
    if (document.formMain.textFormula.value == "")
    {
        alert ("<?=_("请输入公式内容！")?>");
        document.formMain.textFormula.focus ();
        return;
    }
 reVal = /([^<\(\+\-\*\/]|[\+\-\*\/]{2,})(\[|\(|<)/;

    if (reVal.test(document.formMain.textFormula.value))
    {
        //alert (RegExp.$1 +"1\n"+RegExp.$2);
        alert ("<?=_("公式不正确，请检查括号及")?>+-*/<?=_("是否匹配！")?>");
        return;
    }
    reVal = /(\]|\)|>)([^>\)\+\-\*\/]|[\+\-\*\/]{2,})/;

    if (reVal.test(document.formMain.textFormula.value))
    {
        //alert (RegExp.$1 +"2\n"+RegExp.$2);
        alert ("<?=_("公式不正确，请检查括号及")?>+-*/<?=_("是否匹配！")?>");
        return;
    }

    //reVal = /(([^\(\+\-\*\/]|[\+\-\*\/]{2,})(\[|\())|((\]|\))([^\)\+\-\*\/]|[\+\-\*\/]{2,}))/;
    //if (reVal.test(document.formMain.textFormula.value))
    //{
    //    alert ("公式不正确，请检查括号及+-*/是否匹配！");
    //    return;
    //}

}

//公式保存
function SaveInfo ()
{
    if (document.formMain.textFormula.value == "")
    {
        alert ("<?=_("请输入公式内容！")?>");
        document.formMain.textFormula.focus ();
        return;
    }

    reVal = /([^<\(\+\-\*\/]|[\+\-\*\/]{2,})(\[|\(|<)/;
    if (reVal.test(document.formMain.textFormula.value))
    {
        //alert (RegExp.$1 +"1\n"+RegExp.$2);

        alert ("<?=_("公式不正确，请检查括号及")?>+-*/<?=_("是否匹配！")?>");
        return;
    }
    reVal = /(\]|\)|>)([^>\)\+\-\*\/]|[\+\-\*\/]{2,})/;
    if (reVal.test(document.formMain.textFormula.value))
    {
        //alert (RegExp.$1 +"2\n"+RegExp.$2);
        alert ("<?=_("公式不正确，请检查括号及")?>+-*/<?=_("是否匹配！")?>");
        return;
    }

   var s1=document.formMain.textFormula.value;
	 var re;
   //alert(s1);
   re=/\[([^$,\]])*/gi;
   var r=s1.replace(re, "[");
   //alert(r);
     parent_window.form1.FORMULA.value=r;
   parent_window.form1.FORMULANAME.value=document.formMain.textFormula.value;
   parent.close();
}

function UpdateInfo(ITEM_ID)
{
    if (document.formMain.textFormula.value == "")
    {
        alert ("<?=_("请输入公式内容！")?>");
        document.formMain.textFormula.focus ();
        return;
    }

    reVal = /([^<\(\+\-\*\/]|[\+\-\*\/]{2,})(\[|\(|<)/;
    if (reVal.test(document.formMain.textFormula.value))
    {
        //alert (RegExp.$1 +"1\n"+RegExp.$2);

        alert ("<?=_("公式不正确，请检查括号及")?>+-*/<?=_("是否匹配！")?>");
        return;
    }
    reVal = /(\]|\)|>)([^>\)\+\-\*\/]|[\+\-\*\/]{2,})/;
    if (reVal.test(document.formMain.textFormula.value))
    {
        //alert (RegExp.$1 +"2\n"+RegExp.$2);
        alert ("<?=_("公式不正确，请检查括号及")?>+-*/<?=_("是否匹配！")?>");
        return;
    }

   var s1=document.formMain.textFormula.value;
	 var re;

   re=/\[([^$,\]])*/gi;
   var r=s1.replace(re, "[");

   var NEWFORMULANAME=document.formMain.FormulaID.value.replace('<','%');
   NEWFORMULANAME=NEWFORMULANAME.replace('>','`');
   document.formMain.FormulaID.value=NEWFORMULANAME;


   var NEWFORMULA=document.formMain.textFormula.value.replace('<','%');
   NEWFORMULA=NEWFORMULA.replace('>','`');
   document.formMain.textFormula.value=NEWFORMULA;

   document.formMain.FormulaID.value=r;
   formMain.submit();
}

function SetValue(itemid)
{

	 if(itemid=="")
    {
    	document.formMain.FormulaID.value=parent_window.form1.FORMULA.value;
    	document.formMain.textFormula.value=parent_window.form1.FORMULANAME.value;

    }

}

</script>

<BODY class="panel" onload="SetValue('<?=$ITEM_ID?>')">

<form method="POST" action="formula_edit.php?ITEM_ID=<?=$ITEM_ID?>&FLAG=1" name="formMain" >
<input type="hidden" name="FormulaID" value="<?=$FORMULA?>">

  <table  border="0" cellspacing="0" cellpadding="0" align="center" class="small">
    <tr>
      <td colspan="5">
        <table border="0" cellpadding="0" cellspacing="0" width="500">
          <tr class="TableHeader">
            <td align="left">
            	 &nbsp; &nbsp;
<?
                if ($ITEM_ID=="")
                 {
?>
               <script language="javascript">
               	if(parent_window.form1.ITEM_NAME.value!="")
               	document.write(parent_window.form1.ITEM_NAME.value+"=")

               </script>
<?
                }
                else
                {
                	echo $ITEM_NAME."=";
                }
?>
            </td>
          </tr>
          <tr class="TableData">
          <td>
         <textarea name="textFormula" cols="80" rows="5" class="formclass" onSelect="storeCaret(this);" onClick="storeCaret(this);" onKeyup="storeCaret(this);"><?=$FORMULANAME?></textarea>
         </td>
         </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td width="53%" colspan="5">
      	<br><b><?=_("可选字段")?></b></td>
    </tr>
    <tr>
      <td width="12%">
        <select name="selectList" size="10" onchange="insertAtCaret(document.formMain.textFormula, '[' + document.formMain.selectList.item(document.formMain.selectList.selectedIndex).text+'$'+document.formMain.selectList.item(document.formMain.selectList.selectedIndex).value + ']');">
<?
               $query = "SELECT * from SAL_ITEM";
               $cursor= exequery(TD::conn(),$query);
               $VOTE_COUNT=0;
               while($ROW=mysql_fetch_array($cursor))
              {
                $VOTE_COUNT++;
                $ITEMID=$ROW["ITEM_ID"];
                $ITEMNAME=$ROW["ITEM_NAME"];
?>
               <option value="<?=$ITEMID?>"><?=$ITEMNAME?></option>
<?
              }
?>
              <option value="PENSION_U"><?=_("养老保险（单位）")?></option>
              <option value="PENSION_P"><?=_("养老保险（个人）")?></option>
              <option value="MEDICAL_U"><?=_("医疗保险（单位）")?></option>
              <option value="MEDICAL_P"><?=_("医疗保险（个人）")?></option>
              <option value="FERTILITY_U"><?=_("生育保险")?></option>
              <option value="UNEMPLOYMENT_U"><?=_("失业保险（单位）")?></option>
              <option value="UNEMPLOYMENT_P"><?=_("失业保险（个人）")?></option>
              <option value="INJURIES_U"><?=_("工伤保险")?></option>
              <option value="HOUSING_U"><?=_("住房公积金（单位）")?></option>
              <option value="HOUSING_P"><?=_("住房公积金（个人）")?></option>
          </select>
       </td>
      <td width="16%">
        <table width="159">
           <tr valign="middle">
            <td width="35" >
              <input type="button" name="btnAdd" value=" 1 " class="formclass_show4" onClick="insertAtCaret(document.formMain.textFormula, '1');">
            </td>
            <td width="35" >
              <input type="button" name="btnDec" value=" 2 " class="formclass_show4" onclick="insertAtCaret(document.formMain.textFormula, '2');">
            </td>
            <td width="35" >
              <input type="button" name="btnMul" value=" 3 " class="formclass_show4" onclick="insertAtCaret(document.formMain.textFormula, '3');">
            </td>
            <td width="36" >
              <input type="button" name="btnAdd" value=" + " class="formclass_show4" onClick="insertAtCaret(document.formMain.textFormula, '+');">
            </td>
          </tr>
            <tr valign="middle">
            <td width="35" >
              <input type="button" name="btnAdd" value=" 4 " class="formclass_show4" onClick="insertAtCaret(document.formMain.textFormula, '4');">
            </td>
            <td width="35" >
              <input type="button" name="btnDec" value=" 5 " class="formclass_show4" onclick="insertAtCaret(document.formMain.textFormula, '5');">
            </td>
            <td width="35" >
              <input type="button" name="btnMul" value=" 6 " class="formclass_show4" onclick="insertAtCaret(document.formMain.textFormula, '6');">
            </td>
             <td width="36" >
              <input type="button" name="btnDec" value=" - " class="formclass_show4" onclick="insertAtCaret(document.formMain.textFormula, '-');">
            </td>
          </tr>

           <tr valign="middle">
            <td width="35" >
              <input type="button" name="btnAdd" value=" 7 " class="formclass_show4" onClick="insertAtCaret(document.formMain.textFormula, '7');">
            </td>
            <td width="35" >
              <input type="button" name="btnDec" value=" 8 " class="formclass_show4" onclick="insertAtCaret(document.formMain.textFormula, '8');">
            </td>
            <td width="35" >
              <input type="button" name="btnMul" value=" 9 " class="formclass_show4" onclick="insertAtCaret(document.formMain.textFormula, '9');">
            </td>
            <td width="36" >
              <input type="button" name="btnMul" value=" * " class="formclass_show4" onclick="insertAtCaret(document.formMain.textFormula, '*');">
            </td>
          </tr>

          <tr valign="middle">
          	 <td width="35" >
              <input type="button" name="btnDiv" value=" 0 " class="formclass_show4" onclick="insertAtCaret(document.formMain.textFormula, '0');">
            </td>
             <td width="35" >
              <input type="button" name="btnDiv" value=" . " class="formclass_show4" onclick="insertAtCaret(document.formMain.textFormula, '.');">
            </td>
             <td width="35" >
              <input type="button" name="btnLeft" value=" = " class="formclass_show4" onclick="insertAtCaret(document.formMain.textFormula, '=');">
            </td>
            <td width="36" >
              <input type="button" name="btnDiv" value=" / " class="formclass_show4" onclick="insertAtCaret(document.formMain.textFormula, '/');">
            </td>

          </tr>
           <tr valign="middle">
           <td width="35" >
              <input type="button" name="btnLeft" value=" < " class="formclass_show4" onclick="insertAtCaret(document.formMain.textFormula, '<');">
            </td>
            <td width="35">
              <input type="button" name="btnRight" value=" > " class="formclass_show4" onclick="insertAtCaret(document.formMain.textFormula, '>');">
            </td>
             <td width="35" >
              <input type="button" name="btnLeft" value=" ( " class="formclass_show4" onclick="insertAtCaret(document.formMain.textFormula, '(');">
            </td>
            <td width="36">
              <input type="button" name="btnRight" value=" ) " class="formclass_show4" onclick="insertAtCaret(document.formMain.textFormula, ')');">
            </td>
            </tr>
          <tr>
            <td colspan="2">
              <input type="button" name="btnCheck" value=" <?=_("校验")?> " class="formclass_show4" onClick="funCheck ()">

            </td>
			<td colspan="2">
              &nbsp;<input type="button" name="btnClean" value=" <?=_("清空")?> " class="formclass_show4" onClick='javascript:document.formMain.textFormula.value="";'>

            </td>
		   <tr/>
		   <tr>
             <td colspan="2">

<?
                if ($ITEM_ID=="")
                 {
?>
             <input type="button" name="btnCheck" value=" <?=_("确定")?> " class="formclass_show4" onClick="SaveInfo()">
<?
                }
                else
                {
?>
             <input type="submit" value=" <?=_("保存")?> " class="formclass_show4"   name="button" onClick="UpdateInfo('<?=$ITEM_ID?>')">
<?
                }
?>
            </td>
			<td colspan="2">
			</td>
          </tr>
        </table>
      </td>
      <td width="1%" ><?=_("　")?></td>
      <td width="24%" ><?Message(_("说明"),_("个人所得税的输入为<参与所得税计算的薪酬项目>-个税起征额。例如'<[应发薪酬]>-3500'(表示的是[应发薪酬]参与所得税计算)或是<[标准薪酬]+[奖金]>-800(表示的是[标准薪酬]与[奖金]之和参与所得税计算"));?></td>
    </tr>
  </table>
</form>

</BODY>
</HTML>
