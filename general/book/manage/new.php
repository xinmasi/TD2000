<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("�½�ͼ��");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.BOOK_NAME.value=="")
   { alert("<?=_("��������Ϊ�գ�")?>");
     return (false);
   }

   if(document.form1.BOOK_NO.value=="")
   { alert("<?=_("��Ų���Ϊ�գ�")?>");
     return (false);
   }

   if(document.form1.AMT.value=="")
   { alert("<?=_("��������Ϊ�գ�")?>");
     return (false);
   }
   if(document.form1.TO_ID.value=="" && document.form1.COPY_TO_ID.value=="" && document.form1.PRIV_ID.value=="")
   { alert("<?=_("���ķ�Χ����Ϊ�գ�")?>");
     return (false);
   }
   return (true);   
}

</script>


<body class="bodycolor">

<?
  $query = "select * from BOOK_TYPE";
  $cursor= exequery(TD::conn(),$query);
  if(!$ROW=mysql_fetch_array($cursor))
  {
?>
    <body class="bodycolor">
<?
    Message(_("��ʾ"),_("�����ȶ���ͼ�����"));
    Button_Back();
    exit;
  }
?>

<body class="bodycolor" onload="document.form1.BOOK_NAME.focus();">

<?
  $query = "SELECT POST_PRIV,POST_DEPT from USER where UID='".$_SESSION["LOGIN_UID"]."'";
	$cursor= exequery(TD::conn(),$query);
	if($ROW=mysql_fetch_array($cursor))
	{
       $POST_PRIV=$ROW["POST_PRIV"];
       $POST_DEPT=$ROW["POST_DEPT"];
    }
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�½�ͼ��")?></span>
    </td>
  </tr>
</table>

<table class="TableBlock" width="70%" align="center">
  <form enctype="multipart/form-data" action="add.php"  method="post" name="form1" onSubmit="return CheckForm();">
   <tr>
    <td nowrap class="TableData"><?=_("���ţ�")?></td>
    <td nowrap class="TableData">
        <select name="DEPT_ID" class="BigSelect">
<?
      echo my_dept_tree(0,$_SESSION["LOGIN_DEPT_ID"],0);
?>
        </select>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("ͼ�����ƣ�")?></td>
    <td nowrap class="TableData">
        <input type="text" name="BOOK_NAME" class="BigInput" size="33" maxlength="100" value="<?=$BOOK_NAME?>">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("ͼ���ţ�")?></td>
    <td nowrap class="TableData">
        <input type="text" name="BOOK_NO" class="BigInput" size="33" maxlength="100" value="<?=$BOOK_NO?>">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("ͼ�����")?></td>
    <td nowrap class="TableData">
        <select name="TYPE_ID" class="BigSelect">
<?
      $query = "SELECT * from BOOK_TYPE order by TYPE_ID";
      $cursor= exequery(TD::conn(),$query);
      while($ROW=mysql_fetch_array($cursor))
      {
         $TYPE_ID1=$ROW["TYPE_ID"];
         $TYPE_NAME=$ROW["TYPE_NAME"];

?>
          <option value="<?=$TYPE_ID1?>" <? if($TYPE_ID1==$TYPE_ID) echo "selected";?>><?=$TYPE_NAME?></option>
<?
      }
?>
        </select>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("ͼ�����ߣ�")?></td>
    <td nowrap class="TableData">
        <input type="text" name="AUTHOR" class="BigInput" size="33" maxlength="100" value="<?=$AUTHOR?>">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("ISBN�ţ�")?></td>
    <td nowrap class="TableData">
        <input type="text" name="ISBN" class="BigInput" size="33" maxlength="100" value="<?=$ISBN?>">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("�����磺")?></td>
    <td nowrap class="TableData">
        <input type="text" name="PUB_HOUSE" class="BigInput" size="33" maxlength="100" value="<?=$PUB_HOUSE?>">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("�������ڣ�")?></td>
    <td nowrap class="TableData">
        <input type="text" name="PUB_DATE" class="BigInput" size="30" maxlength="10" value="<?=$PUB_DATE?>" onClick="WdatePicker()">&nbsp;
       
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("��ŵص㣺")?></td>
    <td nowrap class="TableData">
        <input type="text" name="AREA" class="BigInput" size="33" maxlength="100" value="<?=$AREA?>">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("������")?></td>
    <td nowrap class="TableData">
        <input type="text" name="AMT" class="BigInput" size="25" maxlength="11" value="<?=$AMT?>">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("�۸�")?></td>
    <td nowrap class="TableData">
        <input type="text" name="PRICE" class="BigInput" size="25" maxlength="10" value="<?=$PRICE?>">&nbsp;<?=_("Ԫ")?>
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("���ݼ�飺")?></td>
    <td nowrap class="TableData">
      <textarea cols=37 rows=3 name="BRIEF" class="BigInput" wrap="yes"><?=$BRIEF?></textarea>
    </td>
   </tr>
   <tr>
     <td nowrap class="TableData"><?=_("���ķ�Χ(����)��")?></td>
     <td class="TableData">
       <input type="hidden" name="TO_ID" value="">
       <textarea cols=37 name=TO_NAME rows=2 class="BigStatic" wrap="yes" readonly></textarea>
       <a href="javascript:;" class="orgAdd" onClick="SelectDept()"><?=_("���")?></a>
       <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("���")?></a>
     </td>
   </tr>
   <tr>
     <td nowrap class="TableData"><?=_("������Ա��")?></td>
     <td class="TableData">
       <input type="hidden" name="COPY_TO_ID" value="">
       <textarea cols=37 name="COPY_TO_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>&nbsp;
       <a href="javascript:;" class="orgAdd" onClick="SelectUser('53','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("ѡ��")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("���")?></a>
     </td>
   </tr>
   <tr>
      <td nowrap class="TableData"><?=_("���Ľ�ɫ")?></td>
      <td nowrap class="TableData">
      	<input type="hidden" name="PRIV_ID" value="<?=$PRIV_ID?>">
      	<textarea cols=37 name="PRIV_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$PRIV_NAME?></textarea>
      	<a href="javascript:;" class="orgAdd" onClick="SelectPriv('7','PRIV_ID', 'PRIV_NAME')"><?=_("���")?></a>
      	<a href="javascript:;" class="orgClear" onClick="ClearUser('PRIV_ID', 'PRIV_NAME')"><?=_("���")?></a>
      </td>
   </tr>
   <!--
   <tr>
    <td nowrap class="TableData" width="120"><?=_("����״̬��")?></td>
    <td nowrap class="TableData">
       <select name="LEND" class="BigSelect">
          <option value="0" <?if($LEND=="0"||$LEND=="") echo "selected"?>><?=_("δ���")?> </option>
          <option value="1" <?if($LEND=="1") echo "selected"?>><?=_("�ѽ��")?> </option>
       </select>
    </td>
   </tr>-->
   <tr>
    <td nowrap class="TableData" width="120"><?=_("¼���ˣ�")?></td>
    <td nowrap class="TableData">
        <input type="text" name="BORR_PERSON" class="BigStatic" size="33" maxlength="100" value="<?=$_SESSION["LOGIN_USER_NAME"]?>" readonly>&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("��ע��")?></td>
    <td nowrap class="TableData">
        <input type="text" name="MEMO" class="BigInput" size="33" maxlength="100" value="<?=$MEMO?>">&nbsp;
    </td>
   </tr>
   <tr>
    <td nowrap class="TableData" width="120"><?=_("�ϴ����棺")?></td>
    <td class="TableData">
        <input type="file" name="ATTACHMENT" size="40" class="BigInput" title="<?=_("ѡ�񸽼��ļ�")?>">
    </td>
   </tr>
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
        <input type="submit" value="<?=_("���")?>" class="BigButton" title="<?=_("���ͼ��")?>" name="button">&nbsp;
        <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='index.php'">
    </td>
   </tr>
  </form>
</table>

</body>
</html>
