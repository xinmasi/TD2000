<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("��Ҫ��ѯ");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small" align="center">
<tr>
  <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("�����Ҫ��ѯ")?></span></td>
</tr>
</table><br>
<table class="TableBlock" width="425" align="center">
<form enctype="multipart/form-data" action="search.php"  method="post" name="form1">
 <tr class="TableLine1">
   <td nowrap><?=_("�������ƣ�")?></td>
   <td nowrap><INPUT type=text name="M_NAME" size=34 class=BigInput></td>
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
$BEGIN_DATE=date("Y-m-01",time());
$CUR_DATE=date("Y-m-d",time());
?>

 <tr class="TableLine1">
   <td nowrap><?=_("��ʼʱ�䣺")?></td>
   <td nowrap>
   <?=_("��")?> <INPUT type=text name="M_START_B" size=10 class=SmallInput value="<?=$BEGIN_DATE?>" onClick="WdatePicker()">
   <?=_("��")?> <INPUT type=text name="M_END_B" size=10 class=SmallInput value="<?=$CUR_DATE?>" onClick="WdatePicker()"></td>
 </tr>
 <tr class="TableLine1">
   <td nowrap><?=_("��Ҫ�ؼ���1��")?></td>
   <td nowrap><INPUT type=text name="KEY_WORD1" size=34 class=BigInput></td>
 </tr>
 <tr class="TableLine2">
   <td nowrap><?=_("��Ҫ�ؼ���2��")?></td>
   <td nowrap><INPUT type=text name="KEY_WORD2" size=34 class=BigInput></td>
 </tr>
 <tr class="TableLine1">
   <td nowrap><?=_("��Ҫ�ؼ���3��")?></td>
   <td nowrap><INPUT type=text name="KEY_WORD3" size=34 class=BigInput></td>
 </tr>
 <tr class="TableLine2">
   <td nowrap><?=_("�����ң�")?></td>
   <td nowrap>
   <select name="M_ROOM" class="SmallSelect">
   	<option value="" selected><?=_("ȫ��")?></option>
<?
$query = "SELECT * from MEETING_ROOM";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $MR_ID=$ROW["MR_ID"];
   $MR_NAME=$ROW["MR_NAME"];
?>
   <option value="<?=$MR_ID?>" <? if($M_ROOM==$MR_ID) echo "selected";?>><?=$MR_NAME?></option>
<?
}
?>
   </select></td>
 </tr>
 <tr class="TableControl">
  <td colspan="9" align="center">
   <input type="submit" value="<?=_("��ѯ")?>" class="BigButton">&nbsp;&nbsp;
  </td>
 </tr>
</form>
</table>
</body>
</html>