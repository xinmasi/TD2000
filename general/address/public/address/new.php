<?
include_once("inc/auth.inc.php");
include_once("inc/utility_field.php");
include_once("inc/utility_file.php");
include_once("inc/utility_all.php");


$HTML_PAGE_TITLE = _("�½���ϵ��");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
function IsNumber(str)
{
   return str.match(/^[0-9]*$/)!=null;
}

function IsValidEmail(str)
{
   var re = /@/;
   return str.match(re)!=null;
}

function CheckForm()
{
   if(document.form1.PSN_NAME.value=="")
   { alert("<?=_("��ϵ����������Ϊ�գ�")?>");
     return (false);
   }

   if (document.form1.POST_NO_DEPT.value!=""&&!IsNumber(document.form1.POST_NO_DEPT.value))
   { alert("<?=_("��λ�ʱ�ֻ�������֣�")?>");
     return (false);
   }

   if (document.form1.POST_NO_HOME.value!=""&&!IsNumber(document.form1.POST_NO_HOME.value))
   { alert("<?=_("��ͥ�ʱ�ֻ�������֣�")?>");
     return (false);
   }

   if (document.form1.MOBIL_NO.value!=""&&!IsNumber(document.form1.MOBIL_NO.value))
   { alert("<?=_("�ֻ�����ֻ�������֣�")?>");
     return (false);
   }

   if (document.form1.EMAIL.value!=""&&!IsValidEmail(document.form1.EMAIL.value))
   { alert("<?=_("��������Ч�ĵ������䣡")?>");
     return (false);
   }

   return (true);
}

</script>


<body class="bodycolor">
<div class="PageHeader">
   <div class="title"><?=_("�½���ϵ��")?></div>
</div>
<table class="TableTop" width="650">
   <tr>
      <td class="left"></td>
      <td class="center subject">
         <?=_("����")?>
      </td>
      <td class="right"></td>
   </tr>
</table>
<form enctype="multipart/form-data" action="insert.php"  method="post" name="form1" onsubmit="return CheckForm();">
 <table class="TableBlock no-top-border"   width="650">
    <tr>
      <td nowrap class="TableData"> <?=_("���飺")?></td>
      <td class="TableData" colspan="2">
        <select name="GROUP_ID" class="BigSelect">
<?
 if($_SESSION["LOGIN_USER_PRIV"]==1) 
 {
?>          	
        <option value="0" <?if($GROUP_ID==0) echo "selected";?>><?=_("Ĭ��")?></option>
<?
 }
 if($_SESSION["LOGIN_USER_PRIV"]!=1)
   $query = "select GROUP_ID,GROUP_NAME from ADDRESS_GROUP where USER_ID='' and (find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',SUPPORT_DEPT) or SUPPORT_DEPT='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',SUPPORT_USER)) order by ORDER_NO asc,GROUP_NAME asc";
 else
   $query = "select GROUP_ID,GROUP_NAME from ADDRESS_GROUP where USER_ID='' order by ORDER_NO asc,GROUP_NAME asc";

$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $GROUP_ID1=$ROW["GROUP_ID"];
   $GROUP_NAME=$ROW["GROUP_NAME"];
?>
        <option value="<?=$GROUP_ID1?>" <?if($GROUP_ID==$GROUP_ID1) echo "selected";?>><?=$GROUP_NAME?></option>
<?
}
?>
        </select>
      </td>
    </tr>

    <tr>
      <td nowrap class="TableHeader" colspan="3"><b>&nbsp;<?=_("������Ϣ")?></b></td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("����ţ�")?></td>
      <td class="TableData">
        <input type="text" name="PSN_NO" size="12" maxlength="50" class="BigInput">
      </td>
      <td class="TableData" width="250" rowspan="6">
<?
   if($ATTACHMENT_NAME=="")
      echo "<center>"._("������Ƭ")."</center>";
   else
   {
	   $URL_ARRAY = attach_url($ATTACHMENT_ID,$ATTACHMENT_NAME);
?>
       <a href="<?=$URL_ARRAY["view"]?>" title="<?=_("����鿴�Ŵ�ͼƬ")?>" target="_blank"><img src="<?=$URL_ARRAY["view"]?>" width='250' border=1 alt="<?=_("�ļ�����")?><?=$ATTACHMENT_NAME?>"></a>
<?
	}
?>	
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("������")?></td>
      <td class="TableData">
        <input type="text" name="PSN_NAME" size="12" maxlength="50" class="BigInput">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("�Ա�")?></td>
      <td class="TableData">
        <select name="SEX" class="BigSelect">
          <option value=""></option>
          <option value="0"><?=_("��")?></option>
          <option value="1"><?=_("Ů")?></option>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("���գ�")?></td>
      <td class="TableData">
        <input type="text" name="BIRTHDAY" size="10" maxlength="10" class="BigInput" value="" onClick="WdatePicker()">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("�ǳƣ�")?></td>
      <td class="TableData">
        <input type="text" name="NICK_NAME" size="25" width="60" class="BigInput">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("ְ��")?></td>
      <td class="TableData">
        <input type="text" name="MINISTRATION" size="25" width="60" class="BigInput">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("��ż��")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="MATE" size="25" width="60" class="BigInput">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("��Ů��")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="CHILD" size="25" width="60" class="BigInput">
      </td>
    </tr>
    </tr>
<?
if($ATTACHMENT_NAME=="")
   $PHOTO_STR=_("��ϵ����Ƭ�ϴ���");
else
   $PHOTO_STR=_("��ϵ����Ƭ���ģ�");
?>
    <tr>
      <td nowrap class="TableData"> <?=$PHOTO_STR?></td>
      <td class="TableData" colspan="2">
        <input type="file" name="ATTACHMENT" size="40" class="BigInput" title="<?=_("ѡ�񸽼��ļ�")?>">
      </td>
    </tr>

    <tr>
      <td nowrap class="TableHeader" colspan="3"><b>&nbsp;<?=_("��ϵ��ʽ����λ��")?></b></td>
    </tr>

    <tr>
      <td nowrap class="TableData"> <?=_("��λ���ƣ�")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="DEPT_NAME" size="25" width="60" class="BigInput">
      </td>
    </tr>

    <tr>
      <td nowrap class="TableData"><?=_("��λ��ַ��")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="ADD_DEPT" size="40" maxlength="100" class="BigInput">
      </td>
    </tr>

    <tr>
      <td nowrap class="TableData"> <?=_("��λ�ʱࣺ")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="POST_NO_DEPT" size="25" width="60" class="BigInput">
      </td>
    </tr>

    <tr>
      <td nowrap class="TableData"> <?=_("�����绰��")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="TEL_NO_DEPT" size="25" width="60" class="BigInput">
      </td>
    </tr>

    <tr>
      <td nowrap class="TableData"> <?=_("�������棺")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="FAX_NO_DEPT" size="25" width="60" class="BigInput">
      </td>
    </tr>

    <tr>
      <td nowrap class="TableHeader" colspan="3"><b>&nbsp;<?=_("��ϵ��ʽ����ͥ��")?></b></td>
    </tr>

    <tr>
      <td nowrap class="TableData"> <?=_("��ͥסַ��")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="ADD_HOME" size="40" maxlength="100" class="BigInput">
      </td>
    </tr>

    <tr>
      <td nowrap class="TableData"> <?=_("��ͥ�ʱࣺ")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="POST_NO_HOME" size="25" width="60" class="BigInput">
      </td>
    </tr>

    <tr>
      <td nowrap class="TableData"> <?=_("��ͥ�绰��")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="TEL_NO_HOME" size="25" width="60" class="BigInput">
      </td>
    </tr>

    <tr>
      <td nowrap class="TableData"> <?=_("�ֻ���")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="MOBIL_NO" size="25" width="60" class="BigInput">
      </td>
    </tr>

    <tr>
      <td nowrap class="TableData"> <?=_("С��ͨ��")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="BP_NO" size="25" width="60" class="BigInput">
      </td>
    </tr>

    <tr>
      <td nowrap class="TableData"> <?=_("�����ʼ���")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="EMAIL" size="25" maxlength="80" class="BigInput">
      </td>
    </tr>

    <tr>
      <td nowrap class="TableData"> <?=_("QQ��")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="OICQ_NO" size="25" width="60" class="BigInput">
      </td>
    </tr>

    <tr>
      <td nowrap class="TableData"> <?=_("MSN��")?></td>
      <td class="TableData" colspan="2">
        <input type="text" name="ICQ_NO" size="25" width="60" class="BigInput">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableHeader" colspan="3"><b>&nbsp;<?=_("��ע")?></b></td>
    </tr>
    <tr>
      <td class="TableData" colspan="3">
        <textarea cols="60" name="NOTES" rows="5" class="BigInput" wrap="on"></textarea>
      </td>
    </tr>
    <tr>
      <td class="TableData" colspan="3">
<?
echo get_field_table(get_field_html("ADDRESS"));
?>
       </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="3" nowrap>
        <input type="submit" value="<?=_("����")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='index.php?GROUP_ID=<?=$GROUP_ID?>'">
      </td>
    </tr>
  </table>
</form>

</body>
</html>