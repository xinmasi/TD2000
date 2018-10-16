<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("单位信息");
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
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("单位信息")?></span>
    </td>
  </tr>
</table>

<br>

<table class="TableBlock" width="80%" align="center">
    <tr>
      <td nowrap class="TableHeader" colspan="2"><b>&nbsp;<?=_("单位信息")?></b></td>
    </tr>
   <tr>
    <td nowrap class="TableData" width="80"><?=_("单位名称：")?></td>
    <td nowrap class="TableData"><?=$UNIT_NAME?></td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("电话：")?></td>
    <td nowrap class="TableData"><?=$TEL_NO?></td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("传真：")?></td>
    <td nowrap class="TableData"><?=$FAX_NO?></td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("邮编：")?></td>
    <td nowrap class="TableData"><?=$POST_NO?></td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("地址：")?></td>
    <td nowrap class="TableData"><?=$ADDRESS?></td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("网站：")?></td>
    <td nowrap class="TableData"><a href="<?=$URL?>" target="_blank"><?=$URL?></a></td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("电子信箱：")?></td>
    <td nowrap class="TableData"><a href="mailto:<?=$EMAIL?>"><?=$EMAIL?></a></td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("开户行：")?></td>
    <td nowrap class="TableData"><?=$BANK_NAME?></td>
   </tr>
   <tr>
    <td nowrap class="TableData"><?=_("账号：")?></td>
    <td nowrap class="TableData"><?=$BANK_NO?></td>
   </tr>
  <tr class="TableData" >
      <td nowrap class="TableData"  colspan="2" ><b><?=_("单位简介")?></b></td>
    </tr>
  <?
   if ($ATTACHMENT_ID != ""){
?>
   <tr class="TableData" id="attachment2">
      <td nowrap><?=_("附件文档：")?></td>
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