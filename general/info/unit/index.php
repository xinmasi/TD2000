<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("��λ��Ϣ");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>


<?
 $query = "SELECT * from UNIT";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
    $UNIT_NAME=$ROW["UNIT_NAME"];
    $TEL_NO=$ROW["TEL_NO"];
    $FAX_NO=$ROW["FAX_NO"];
    $POST_NO=$ROW["POST_NO"];
    $ADDRESS=$ROW["ADDRESS"];
    $URL=$ROW["URL"];
    $EMAIL=$ROW["EMAIL"];
    $BANK_NAME=$ROW["BANK_NAME"];
    $BANK_NO=$ROW["BANK_NO"];
    $ATTACHMENT_ID   = $ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME = $ROW["ATTACHMENT_NAME"];
    $CONTENT         = $ROW["CONTENT"];
 }
?>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("��λ��Ϣ")?></span>
    </td>
  </tr>
</table>

<br>

<table class="TableBlock" width="80%" align="center">
    <tr>
      <td nowrap class="TableHeader" colspan="2"><b>&nbsp;<?=_("��λ��Ϣ")?></b></td>
    </tr>
   <tr>
    <td nowrap class="TableData" width="80"><?=_("��λ���ƣ�")?></td>
    <td nowrap class="TableData"><?=$UNIT_NAME?></td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("�绰��")?></td>
    <td nowrap class="TableData"><?=$TEL_NO?></td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("���棺")?></td>
    <td nowrap class="TableData"><?=$FAX_NO?></td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("�ʱࣺ")?></td>
    <td nowrap class="TableData"><?=$POST_NO?></td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("��ַ��")?></td>
    <td nowrap class="TableData"><?=$ADDRESS?></td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("��վ��")?></td>
    <td nowrap class="TableData"><a href="<?=$URL?>" target="_blank"><?=$URL?></a></td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("�������䣺")?></td>
    <td nowrap class="TableData"><a href="mailto:<?=$EMAIL?>"><?=$EMAIL?></a></td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("�����У�")?></td>
    <td nowrap class="TableData"><?=$BANK_NAME?></td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("�˺ţ�")?></td>
    <td nowrap class="TableData"><?=$BANK_NO?></td>
   </tr>
  <tr class="TableData" >
      <td nowrap class="TableData"  colspan="2" ><b><?=_("��λ���")?></b></td>
    </tr>
  <?
   if ($ATTACHMENT_ID != ""){
?>
   <tr class="TableData" id="attachment2">
      <td nowrap><?=_("�����ĵ���")?></td>
      <td nowrap><?=attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,0,0,0,0,0,0,"unit")?></td>
    </tr>
<?
   }
?>
    <tr class="TableData" >
      <td colspan="2"><?=$CONTENT?></td> 
      
    </tr>
</table>

</body>
</html>