<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("�����ϱ�");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
 $CUR_DATE=date("Y-m-d",time());

if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("SAL_FLOW", 10);
   $PAGE_START=intval($PAGE_START);
if(!isset($TOTAL_ITEMS))
{
   $query = "SELECT count(*) from SAL_FLOW where to_days(END_DATE)<=to_days('$CUR_DATE')";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/salary.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("�����ϱ���ʷ����")?></span>
    </td>
<? 
if($TOTAL_ITEMS>0)
{
?>    
    <td align="right" valign="bottom" class="small1"><?=page_bar($PAGE_START,$TOTAL_ITEMS,$PAGE_SIZE,"PAGE_START")?></td>
<?
}
?> 
  </tr>
</table>
<div align="center">

<?
 //============================ �����ϱ���ʷ���� =======================================
 $query = "SELECT * from SAL_FLOW where to_days(END_DATE)<=to_days('$CUR_DATE') order by BEGIN_DATE desc limit $PAGE_START, $PAGE_SIZE";
 $cursor= exequery(TD::conn(),$query);
 $FLOW_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $FLOW_COUNT++;
    $FLOW_ID=$ROW["FLOW_ID"];
    $SAL_CREATER=$ROW["SAL_CREATER"];
    $SAL_CREATER=substr(GetUserNameById($SAL_CREATER),0,-1);
    $SAL_YEAR=$ROW["SAL_YEAR"];
    $SAL_MONTH=$ROW["SAL_MONTH"];
    $SAL_MONTH=$SAL_YEAR._("��").$SAL_MONTH._("��");
    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $BEGIN_DATE=strtok($BEGIN_DATE," ");

    $CONTENT=$ROW["CONTENT"];

    if($FLOW_COUNT==1)
    {
?>

    <table width="95%" class="TableList">

<?
    }
?>
    <tr class="TableData">
      <td nowrap align="center"><?=$SAL_CREATER?></td>
      <td nowrap align="center"><?=$BEGIN_DATE?></td>
      <td nowrap align="center"><?=_("����ֹ")?></td>
      <td nowrap align="center"><?=$SAL_MONTH?></td>
      <td nowrap align="center"><?=$CONTENT?></td>
      <td nowrap align="center" width="100"><a href="sal_index.php?FLOW_ID=<?=$FLOW_ID?>&PAGE_START=<?=$PAGE_START?>"> <?=_("����")?></a></td>
    </tr>
<?
 }

 if($FLOW_COUNT>0)
 {
?>
    <thead class="TableHeader">
      <td nowrap align="center"><?=_("���̴�����")?></td>
      <td nowrap align="center"><?=_("��ʼ����")?> <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_down.gif" width="11" height="10"></td>
      <td nowrap align="center"><?=_("��ֹ����")?></td>
      <td nowrap align="center"><?=_("�����·�")?></td>
      <td nowrap align="center"><?=_("��ע")?></td>
      <td nowrap align="center"><?=_("����")?></td>
    </thead>
    </table>
<?
 }
 else
    Message("",_("��δ����"));
?>

</div>

</body>
</html>