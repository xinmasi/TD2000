<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("�����ѯ");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"> </script>

<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.M_START_B.value!="" && document.form1.M_END_B.value!="" && document.form1.M_START_B.value > document.form1.M_END_B.value)
   { 
      alert("<?=_("�������ڵĽ�����ѯʱ�䲻��С�ڻ������ڵĿ�ʼ��ѯʱ�䣡")?>");
      return (false);
   }
   return (true);
}

function do_export()
{
	  CheckForm();
    document.form1.action='export.php';
    document.form1.submit();
}

function do_search()
{
	  CheckForm();
    document.form1.action='search.php';
    document.form1.submit();
}
</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small" align="center">
<tr>
  <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("�����ѯ")?></span></td>
</tr>
</table><br>
<table class="TableBlock" width="400" align="center">
<form enctype="multipart/form-data" action=""  method="post" name="form1">
 <tr class="TableLine1">
   <td nowrap><?=_("���ƣ�")?></td>
   <td nowrap><INPUT type=text name="M_NAME" size=34  value="" class=BigInput></td>
 </tr>
 <tr class="TableLine2">
   <td nowrap><?=_("�����ˣ�")?></td>
   <td nowrap>
     <input type="text" name="TO_NAME" size="10" class="BigStatic" readonly>&nbsp;
     <input type="hidden" name="TO_ID" value="">
     <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('85','','TO_ID', 'TO_NAME')"><?=_("ѡ��")?></a>
   </td>
 </tr>
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----�Զ���ʼ----------
$query = "SELECT * from MEETING  where M_STATUS=1";
$cursor= exequery(TD::conn(),$query);
if(mysql_num_rows($cursor)>0)
{
    while($ROW=mysql_fetch_array($cursor))
    {
       $M_ID3=$ROW["M_ID"];
       $M_START3=$ROW["M_START"];
       $M_ID3=intval($M_ID3);
       if($CUR_TIME>=$M_START3)
       {
          exequery(TD::conn(),"UPDATE MEETING SET M_STATUS= '2' WHERE M_ID='$M_ID3'");
       }
    }
}
//-----�Զ�����----------
$query = "SELECT * from MEETING  where M_STATUS=2";
$cursor= exequery(TD::conn(),$query);
if(mysql_num_rows($cursor)>0)
{
    while($ROW=mysql_fetch_array($cursor))
    {
       $M_ID3=$ROW["M_ID"];
       $M_END3=$ROW["M_END"];
       $M_ID3=intval($M_ID3);
       if($CUR_TIME>=$M_END3)
       {
          exequery(TD::conn(),"UPDATE MEETING SET M_STATUS= '4' WHERE M_ID='$M_ID3'");
       }
    }
}

$BEGIN_DATE=date("Y-m-01",time());
$CUR_DATE=date("Y-m-d",time());
?>
 <tr class="TableLine1">
   <td nowrap><?=_("��ʼʱ�䣺")?></td>
   <td nowrap>
   <?=_("��")?> <INPUT type=text name="M_START_B" size=10 class=SmallInput value=""  onClick="WdatePicker()">
   <?=_("��")?> <INPUT type=text name="M_END_B" size=10 class=SmallInput value="" onClick="WdatePicker()">
 </tr>
 <tr class="TableLine2">
   <td nowrap><?=_("�����ң�")?></td>
   <td nowrap>
   <select name="M_ROOM" class="SmallSelect" value="">
   	<option value="" selected><?=_("ȫ��")?></option>
<?
$query = "SELECT * from MEETING_ROOM";
$cursor= exequery(TD::conn(),$query);
if(mysql_num_rows($cursor)>0)
{
    while($ROW=mysql_fetch_array($cursor))
    {
       $MR_ID=$ROW["MR_ID"];
       $MR_NAME=$ROW["MR_NAME"];
    ?>
        <option value="<?=$MR_ID?>"><?=$MR_NAME?></option>
    <?
    }
}
?>
   </select></td>
 </tr>
 <tr class="TableLine1">
 <td nowrap><?=_("����״̬��")?></td>
   <td nowrap>
   <select name="M_STATUS" class="SmallSelect">
 	  <option value="" selected><?=_("ȫ��")?></option>
    <option value="0"><?=_("����")?></option>
    <option value="1"><?=_("��׼")?></option>
    <option value="2"><?=_("������")?></option>
    <option value="3"><?=_("δ׼")?></option>
    <option value="4"><?=_("�ѽ���")?></option>
   </select></td>
 </tr>
 <tr class="TableLine2">
 <td nowrap><?=_("�λ���Ա��")?></td>
   <td nowrap>
	 <input type="hidden" name="COPY_TO_ID" value="">
     <textarea name="COPY_TO_NAME" class="BigStatic" cols="40" rows="2" wrap="yes" value="" readonly></textarea>
     <a href="javascript:;" class="orgAdd" onClick="SelectUser('87','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("���")?></a>
     <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID','COPY_TO_NAME')"><?=_("���")?></a>
	 </td>
 </tr>
 <tr class="TableLine2">
 <td nowrap><?=_("�λᲿ�ţ�")?></td>
   <td nowrap>
	 <input type="hidden" name="DEPT_TO_ID" value="">
     <textarea name="DEPT_TO_NAME" class="BigStatic" cols="40" rows="2" wrap="yes" value="" readonly></textarea>
     <a href="javascript:;" class="orgAdd" onClick="SelectDept('','DEPT_TO_ID', 'DEPT_TO_NAME')"><?=_("���")?></a>
     <a href="javascript:;" class="orgClear" onClick="ClearUser('DEPT_TO_ID', 'DEPT_TO_NAME')"><?=_("���")?></a>
	 </td>
 </tr>
 <tr class="TableControl">
  <td colspan="9" align="center">
     <input type="button" value="<?=_("��ѯ")?>" class="BigButton" onclick="do_search()">&nbsp;&nbsp;
     <input type="button" value="<?=_("����")?>" class="BigButton" onclick="do_export()">&nbsp;&nbsp;
  </td>
 </tr>
</form>
</table>
</body>
</html>