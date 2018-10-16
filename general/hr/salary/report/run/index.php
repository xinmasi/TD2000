<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("工资上报");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
 $CUR_DATE=date("Y-m-d",time());
 
if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("SAL_FLOW", 5);
   $PAGE_START=intval($PAGE_START);
if(!isset($TOTAL_ITEMS))
{
   $query = "SELECT count(*) from SAL_FLOW where ISSEND='0' and END_DATE='0000-00-00'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/salary.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("工资上报待办流程")?></span>
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
 //============================ 显示执行中的工资上报流程 =======================================
 $query = "SELECT * from SAL_FLOW where to_days(BEGIN_DATE)<=to_days('$CUR_DATE') and to_days(END_DATE)>=to_days('$CUR_DATE') and ISSEND=0 or END_DATE='0000-00-00' order by BEGIN_DATE desc limit $PAGE_START, $PAGE_SIZE";
 //$query = "SELECT * from SAL_FLOW where to_days(BEGIN_DATE)<=to_days('$CUR_DATE') and ISSEND=0 order by BEGIN_DATE desc";
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
    $SAL_MONTH=$SAL_YEAR._("年").$SAL_MONTH._("月");
    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $BEGIN_DATE=strtok($BEGIN_DATE," ");
    $END_DATE=$ROW["END_DATE"];
    $END_DATE=strtok($END_DATE," ");
    if($END_DATE=="0000-00-00") $END_DATE=_("需手动终止");
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
      <td nowrap align="center"><?=$END_DATE?></td>
      <td nowrap align="center"><?=$SAL_MONTH?></td>
      <td nowrap align="center"><?=$CONTENT?></td>
      <td nowrap align="center"><a href="sal_index.php?FLOW_ID=<?=$FLOW_ID?>&PAGE_START=<?=$PAGE_START?>"> <?=_("录入")?></a>
        <br><a href="all_index.php?FLOW_ID=<?=$FLOW_ID?>&PAGE_START=<?=$PAGE_START?>"> <?=_("批量录入")?></a>
      <br><a href="import.php?FLOW_ID=<?=$FLOW_ID?>&PAGE_START=<?=$PAGE_START?>"><?=_("导入")?>EXCEL<?=_("工资数据")?></a>
      </td>
    </tr>
<?
 }

 if($FLOW_COUNT>0)
 {
?>
    <thead class="TableHeader">
      <td nowrap align="center"><?=_("流程创建者")?></td>
      <td nowrap align="center"><?=_("起始日期")?> <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_down.gif" width="11" height="10"></td>
      <td nowrap align="center"><?=_("截止日期")?></td>
      <td nowrap align="center"><?=_("工资月份")?></td>
      <td nowrap align="center"><?=_("备注")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
    </thead>
    </table>
<?
 }
 else
    Message("",_("尚未定义"));
?>

</div>

</body>
</html>