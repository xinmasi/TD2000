<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("н����Ŀ�༭");
include_once("inc/header.inc.php");
?>



<script Language="JavaScript">
function CheckForm()
{ 
   var pattern = new RegExp(/^\s+$/);
   var re=pattern.test(document.form1.ITEM_NAME.value);
   if(re)
   { alert("<?=_("н����Ŀ���Ʋ���Ϊ�գ�")?>");
     return (false);
   }
}

  function LoadWindow2()
{
  URL="formula_edit.php";
  myleft=(screen.availWidth-500)/2;
  window.open(URL,"formul_edit","height=400,width=700,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
  }
function sel_change(input)
  {

  	if(form1.ITEM_TYPE.value=="2")
     {
      	document.all("FORMU").style.display="";
      }
    else
    	{
    		document.all("FORMU").style.display="none";
    		document.form1.FORMULA.value="";
    		document.form1.FORMULANAME.value="";
    	}

  }
</script>


<?
 $ITEM_ID = intval($ITEM_ID);
 $query = "SELECT * from SAL_ITEM where ITEM_ID='$ITEM_ID'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
    {
     $ITEM_ID=$ROW["ITEM_ID"];
     $ITEM_NAME=$ROW["ITEM_NAME"];
     $ISPRINT=$ROW["ISPRINT"];
     $ISCOMPUTER=$ROW["ISCOMPUTER"];
     $FORMULA=$ROW["FORMULA"];
     $FORMULANAME=$ROW["FORMULANAME"];
     $ISREPORT=$ROW["ISREPORT"];
    }
?>

<body class="bodycolor" onLoad="document.form1.ITEM_NAME.focus();">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("н����Ŀ�༭")?></span>
    </td>
  </tr>
</table>

<div align="center" class="big1">
<b>
	<table width="450" class="TableBlock" align="center" >
  <form action="update.php"  method="post" name="form1" onSubmit="return CheckForm();">
   <tr>
    <td nowrap class="TableContent"><?=_("н����Ŀ���ƣ�")?> </td>
    <td nowrap class="TableData">
       <input type="text" name="ITEM_NAME" class="BigInput" size="30" maxlength="100" value="<?=$ITEM_NAME?>" <? if($ITEM_COUNT>=50) echo disabled;?>>
       <input type="hidden" name="ITEM_ID" value="<?=$ITEM_ID?>">
    </td>
   </tr>

   <tr>
    <td nowrap class="TableContent"><?=_("��Ŀ���ͣ�")?> </td>
    <td nowrap class="TableData">
    	 <select name="ITEM_TYPE" class="SmallSelect" onChange="sel_change()">
       <option value="0" <? if($ISREPORT!="1"&&$ISCOMPUTER!="1") echo "selected";?>><?=_("����¼����")?></option>
       <option value="1" <? if($ISREPORT=="1") echo "selected";?>><?=_("�����ϱ���")?></option>
       <option value="2" <? if($ISREPORT!="1"&&$ISCOMPUTER=="1") echo "selected";?>><?=_("������")?></option>
       </select>
    </td>
   </tr>
   <tr id="FORMU" style=<? if($ISCOMPUTER=="1") {echo "";}else {echo "display:none";}?>>
    <td nowrap class="TableContent"><?=_("���㹫ʽ��")?></td>
    <td nowrap class="TableData">
    	<input type="hidden" name="FORMULA" value="<?=$FORMULA?>">
    	<textarea cols=37 name="FORMULANAME" rows="4" class="BigStatic" readonly  wrap="yes"><?=$FORMULANAME?></textarea>&nbsp;
    	<input type="button" value="<?=_("�༭��ʽ")?>" class="SmallButton" onClick="LoadWindow2()" title="<?=_("�༭��ʽ")?>" name="button">
    </td>
   </tr>
     <tfoot align="center" class="TableFooter">
      <td colspan="2" nowrap>
      	<input type="submit" value="<?=_("�޸�")?>" class="BigButton" title="<?=_("�޸�н����Ŀ")?>"  name="button">
      	 &nbsp;&nbsp;<input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='index.php'">
      </td>
    </tfoot>
  </form>
  </table>
</b>
</div>
</body>
</html>