<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("�̶��ʲ��۾ɼ�¼");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
$query="select * from CP_CPTL_INFO where CPTL_ID='$CPTL_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $CPTL_NO=$ROW["CPTL_NO"];
   $CPTL_NAME=$ROW["CPTL_NAME"];
}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/asset.gif"  width="24" height="24"><span class="big3"> <?=_("�̶��ʲ��۾ɼ�¼")?> - <?=$CPTL_NAME?></span><br>
    </td>
    </tr>
</table>

<hr width="100%" height="1" align="left" color="#FFFFFF">
<?
$CUR_DATE=date("Y-m-d",time());

$COUNT=0;
$query="select * from CP_DPCT_SUB where CPTL_ID='$CPTL_ID' order by PEPRE_DATE asc";
$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $COUNT++;
   $PEPRE_DATE=$ROW["PEPRE_DATE"];
   $FROM_YYMM=$ROW["FROM_YYMM"];
   $TO_YYMM=$ROW["TO_YYMM"];
   $DEPRE_AMT=$ROW["DEPRE_AMT"];
   
   if($COUNT==1)
   {
   	  if($COUNT%2==1)
         $TableLine="TableLine1";
      else
         $TableLine="TableLine2";
?>
<table class="TableList"  width="95%">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("����ʱ��")?></td>
      <td nowrap align="center"><?=_("��ʼʱ��")?></td>
      <td nowrap align="center"><?=_("����ʱ��")?></td>
      <td nowrap align="center"><?=_("�۾ɽ��")?></td>
  </tr>
<?
   }
?>
 <tr class="<?=$TableLine?>">
      <td nowrap align="center"><?=$PEPRE_DATE?></td>
      <td nowrap align="center"><?=$FROM_YYMM?></td>
      <td nowrap align="center"><?=$TO_YYMM?></a></td>
      <td nowrap align="right"><?=$DEPRE_AMT?></td>
</tr>
<?
}
if($COUNT>0)
{
?>
<tr class="TableControl">
      <td colspan="9" align="center">
           <input type="button" value="<?=_("��ӡ")?>" class="BigButton" onclick="document.execCommand('Print');" title="<?=_("ֱ�Ӵ�ӡ���ҳ��")?>">&nbsp;&nbsp;&nbsp;&nbsp;
           <input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="window.close();" title="<?=_("�رմ���")?>">
      </td>
  </tr>
</table>
<?
}
else{
   Message("",_("δ�ҵ���Ӧ��¼��"));
   echo '<center><input type="button" value="'._("�ر�").'" class="BigButton" onClick="window.close();" title="'._("�رմ���").'"><center>';
}
?>

</body>
</html>