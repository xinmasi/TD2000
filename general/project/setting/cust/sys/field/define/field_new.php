<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("�Զ����ֶ�");
include_once("inc/header.inc.php");
?>


<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.FIELDNAME.value=="")
   { alert("<?=_('�ֶ����Ʋ���Ϊ�գ�')?>");
     return (false);
   }
   
   if(document.form1.ORDERNO.value=="")
   { alert("<?=_('����Ų���Ϊ�գ�')?>");
     return (false);
   }
   
   if(document.form1.STYPE.value=="D" || document.form1.STYPE.value=="R" || document.form1.STYPE.value=="C")
   { 
   	 if(document.form1.TYPECODE.value=="" && (document.form1.TYPENAME.value=="" || document.form1.TYPEVALUE.value==""))
   	 {
   	   alert("<?=_('ѡ���Ϊ�գ�')?>");
       return (false);
     }
   }
}

function SelChange()
{
   if(document.form1.STYPE.value=="T" || document.form1.STYPE.value=="MT" || document.form1.STYPE.value=="")
   {
      document.getElementById("select_option").style.display="none";
      document.getElementById("code_list").style.display="none";
      document.getElementById("code_def").style.display="none";
   }
   else
   {
   	  document.getElementById("select_option").style.display="";
   	  
   	  if(document.getElementById("CODE_TYPE1").checked)
   	     SelCode(1);
   	  if(document.getElementById("CODE_TYPE2").checked)
   	     SelCode(2);
   }
}
function SelCode(val)
{
   if(val==1)
   {
      document.getElementById("code_list").style.display="";
      document.getElementById("code_def").style.display="none";
   }
   else if(val==2)
   {
      document.getElementById("code_list").style.display="none";
      document.getElementById("code_def").style.display="";
   }
   form1.CODE_TYPE.value=val;
}
</script>


<body class="bodycolor" onload="document.form1.FIELDNAME.focus();">
<?
 
$WB_CODE_NAME = $CODE_NAME;

if($FIELDNO!="")
{
   $query = "SELECT * from PROJ_FIELDSETTING where TYPE_CODE_NO='$CODE_ID' and FIELDNO='$FIELDNO'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
      $FIELDNO =$ROW["FIELDNO"];
      $FIELDNAME =$ROW["FIELDNAME"];
      $ORDERNO =$ROW["ORDERNO"];
      $STYPE =$ROW["STYPE"];
      $TYPECODE =$ROW["TYPECODE"];
      $TYPENAME =$ROW["TYPENAME"];
      $TYPEVALUE =$ROW["TYPEVALUE"];
      $ISQUERY =$ROW["ISQUERY"];
	  $IS_SHOWLIST =$ROW["IS_SHOWLIST"];
      
      if($TYPECODE!="")
         $CODE_TYPE=1;
      else if($TYPENAME!=""&&$TYPECODE=="")
         $CODE_TYPE=2;
   }
}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�����Զ����ֶ�")?> - <?=$CODE_NAME?> <?=$FIELDNAME?></span>
    </td>
  </tr>
</table>

<br>
<table class="TableBlock" width="90%" align="center">
  <form action="insert.php"  method="post" name="form1" onsubmit="return CheckForm();">
   <tr class="TableData">
    <td width="80"><?=_("�ֶ����ƣ�")?></td>
    <td>
        <input type="text" name="FIELDNAME" class="BigInput" size="20" maxlength="100" value="<?=$FIELDNAME?>">&nbsp;
    </td>
   </tr>
   <tr class="TableData">
    <td><?=_("����ţ�")?></td>
    <td>
        <input type="text" name="ORDERNO" class="BigInput" size="12" maxlength="20" value="<?=$ORDERNO?>">
    </td>
   </tr>
   <tr class="TableData">
    <td><?=_("�ֶ����ͣ�")?></td>
    <td>
        <select name="STYPE" class="BigSelect" onchange="SelChange();">
           <option value="T"<?if($STYPE=="T") echo " selected";?>><?=_("���������")?></option>
           <option value="MT"<?if($STYPE=="MT") echo " selected";?>><?=_("���������")?></option>
           <option value="D"<?if($STYPE=="D") echo " selected";?>><?=_("�����˵�")?></option>
           <option value="R"<?if($STYPE=="R") echo " selected";?>><?=_("��ѡ��")?></option>
           <option value="C"<?if($STYPE=="C") echo " selected";?>><?=_("��ѡ��")?></option>
        </select>
    </td>
   </tr>

   <tr class="TableData" id="select_option" style="display:<?if($STYPE=="T" || $STYPE=="MT" || $STYPE=="") echo "none";else echo "";?>;">
    <td><?=_("�������ͣ�")?></td>
    <td>
        <input type="radio" name="radio" id="CODE_TYPE1"<?if($TYPECODE!="") echo " checked";?> onclick="SelCode(1);"><label for="CODE_TYPE1"><?=_("ϵͳ����")?></label>
        <input type="radio" name="radio" id="CODE_TYPE2"<?if($TYPENAME!=""&&$TYPECODE=="") echo " checked";?> onclick="SelCode(2);"><label for="CODE_TYPE2"><?=_("�Զ���ѡ��")?></label>
    </td>
   </tr>

   <tr class="TableData" id="code_list" style="display:<?if(($STYPE=="D" || $STYPE=="R" || $STYPE=="C")&&$TYPECODE!="") echo "";else echo "none";?>;">
      <td><?=_("ϵͳ���룺")?></td>
      <td>
        <select class="BigSelect" name="TYPECODE">
          <option value=""></option>
          <?=code_list('',$TYPECODE)?>
        </select>
      </td>
   </tr>
   <tbody id="code_def" style="display:<?if(($STYPE=="D" || $STYPE=="R" || $STYPE=="C")&&$TYPENAME!=""&&$TYPECODE=="") echo "";else echo "none";?>;">
   <tr class="TableData">
      <td><?=_("ѡ�����ƣ�")?></td>
      <td>
         <textarea name="TYPENAME" cols="45" rows="3"><?=$TYPENAME?></textarea>
         <br><?=_("��ʾ��ѡ������Ļ����Ķ��Ÿ�����:��ѡ��1,ѡ��2,ѡ��3��")?></font>
      </td>
   </tr>
   <tr class="TableData">
      <td><?=_("ѡ���ֵ��")?></td>
      <td>
         <textarea name="TYPEVALUE" cols="45" rows="3"><?=$TYPEVALUE?></textarea>
         <br><?=_("ѡ���Ӧ�洢��ֵ�����ظ������֣��ö��Ÿ�����:��1,2,3��")?>
      </td>
   </tr>
   </tbody>
   <tr class="TableData">
    <td><?=_("�ֶ�ѡ�")?></td>
    <td>
        <input type="checkbox" name="ISQUERY" id="ISQUERY"<?if($ISQUERY=="1") echo " checked";?>><label for="ISQUERY"><?=_("��Ϊ��ѯ�ֶ�")?></label>
    </td>
   </tr>

   <tr class="TableData">
    <td><?=_("�Ƿ���ʾ���б�")?></td>
    <td>
        <input name="IS_SHOWLIST" id="IS_SHOWLIST1" <?if($IS_SHOWLIST!="1") echo " checked";?> type="radio" value="0"/><label for="IS_SHOWLIST1"><?=_("��")?></label>
		<input name="IS_SHOWLIST" id="IS_SHOWLIST2" <?if($IS_SHOWLIST=="1") echo " checked";?> type="radio" value="1"/><label for="IS_SHOWLIST2"><?=_("��")?></label>
    </td>
   </tr>

   <tr class="TableControl">
    <td nowrap colspan="2" align="center">
        <input type="hidden" name="CODE_ID" value="<?=$CODE_ID?>">
		<input type="hidden" name="PAR_ID" value="<?=$CODE_ID?>">
        <input type="hidden" name="FIELDNO" value="<?=$FIELDNO?>">
        <input type="hidden" name="CODE_TYPE" value="<?=$CODE_TYPE?>">
		<input type="hidden" name="TT_CODE_NAME" value="<?=$WB_CODE_NAME?>">
        <input type="submit" value="<?=_("ȷ��")?>" class="BigButton">&nbsp;&nbsp;
		<input type="button" value="<?=_("����")?>" class="BigButton" onclick="location='index.php?CODE_ID=<?=$CODE_ID?>&CODE_NAME=<?=$WB_CODE_NAME?>'">
    </td>
	</tr>
  </form>
</table>
</body>
</html>