<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");
$connstatus = ($connstatus) ? true : false;
$ITEMS_IN_PAGE=10;
if(!isset($start) || $start=="")
$start=0;

$HTML_PAGE_TITLE = _("���պؿ���ʾ����");
include_once("inc/header.inc.php");
?>


<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script>
function edit_card_module()
{
   URL="edit_module.php";
   window.location=URL;
}
function delete_card_module(MODULE_ID)
{
   var msg="<?=_("ȷ��Ҫɾ����ģ����")?>";
   if(window.confirm(msg))
   {
      URL="delete_module.php?MODULE_ID="+MODULE_ID;
      window.location=URL;
   }
}
</script>

<body class="bodycolor">
<?
$query="select count(*) from HR_CARD_MODULE";
$cursor=exequery(TD::conn(),$query, $connstatus);
if($ROW=mysql_fetch_array($cursor))
   $TOTAL_ITEMS=$ROW["0"];

?>
<td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absMiddle"><span class="big3"> <?=_("�½�ģ��")?><span><td>
<br>
<br>
<div align="center">
<td>
 <input type="button" class="BigButton" value="<?=_("�½�ģ��")?>" onclick="edit_card_module();">
</td>
</div>
<?
if($TOTAL_ITEMS == 0)
{
    Message("",_("û�пɹ����ģ��"));
    exit();
}
else
{
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
<tr>
<td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/card_module.gif" align="absMiddle"><span class="big3"> <?=_("���պؿ�ģ���б�")?></span></td>
<td align="right" valign="bottom" style="borderstyle:none"><?=page_bar($start,$TOTAL_ITEMS,$ITEMS_IN_PAGE)?></td>
</tr>
</table>

<table class="TableList" width="95%" align="center">
<tr class="TableHeader">
  <td nowrap align="center"><?=_("ģ������")?></td>
  <td nowrap align="center"><?=_("����Ա��")?></td>
  <td nowrap align="center"><?=_("ģ���ļ�")?></td>
  <td nowrap align="center"><?=_("�ʺ���")?></td>
  <td nowrap align="center"><?=_("����ʱ��")?></td>
  <td nowrap align="center"><?=_("����")?></td>
</tr>
<?
$query="select * from HR_CARD_MODULE order by CREATE_TIME limit $start,$ITEMS_IN_PAGE";
$cursor=exequery(TD::conn(),$query, $connstatus);
while($ROW=mysql_fetch_array($cursor))
{
	$MODULE_ID=$ROW["MODULE_ID"];
	$MODULE_NAME=$ROW["MODULE_NAME"];
	$CREATE_TIME=$ROW["CREATE_TIME"];
	$GREETING=$ROW["GREETING"];;
        $SUIT_USERS=$ROW["SUIT_USERS"];
	$ATTACH=$ROW["ATTACH"];
	$ATTACH_ARRAY = explode(",",$ATTACH);
	$ATTACH_ID=$ATTACH_ARRAY[0];
	$ATTACH_NAME=$ATTACH_ARRAY[1];
	$USER_NAME_STR=substr(GetUserNameById($SUIT_USERS),0,-1);
?>
<tr>
  <td class="TableData"><?=$MODULE_NAME?></td>
  <td align="left" class="TableData" style="width: 40%;"><?=$USER_NAME_STR?></td>
  <td class="TableData" align="left"><?=attach_link($ATTACH_ID,$ATTACH_NAME,1,1,0,1,0,0,0,0,"")?></td>
  <td align="left" class="TableData" style="width: 20%;"><?=$GREETING?></td>
  <td align="center" class="TableData" nowrap><?=$CREATE_TIME?></td>
  <td align="left" class="TableData" nowrap>
    <a href="edit_module.php?MODULE_ID=<?=$MODULE_ID?>" ><?=_("�༭")?></a>&nbsp;
     <a href="#" onclick="delete_card_module('<?=$MODULE_ID?>');"><?=_("ɾ��")?></a>&nbsp;
  </td>
</tr>
<?
}
}
?>
</table>
</html>
</body>