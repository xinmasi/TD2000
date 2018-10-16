<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("工资流程管理");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript">
function delete_flow(FLOW_ID)
{
 msg='<?=_("确认要删除该流程吗？通过该流程上报的工资数据将被删除且不可恢复！")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?FLOW_ID=" + FLOW_ID+"&PAGE_START=<?=$PAGE_START?>";
  window.location=URL;
 }
}
function send_flow(FLOW_ID)
{
  URL="send_email.php?FLOW_ID=" + FLOW_ID+"&PAGE_START=<?=$PAGE_START?>";
  window.location=URL;
}
function edit_flow(FLOW_ID)
{
  URL="new.php?FLOW_ID=" + FLOW_ID+"&PAGE_START=<?=$PAGE_START?>";
  window.location=URL;
}
function mobilesend_flow(FLOW_ID)
{
  URL="send_mobile.php?FLOW_ID=" + FLOW_ID+"&PAGE_START=<?=$PAGE_START?>";
  window.location=URL;
}
function weixinsend_flow(FLOW_ID)
{
  URL="send_weixin.php?FLOW_ID=" + FLOW_ID+"&PAGE_START=<?=$PAGE_START?>";
  window.location=URL;
}

function rerun_flow(FLOW_ID)
{
  URL="rerun.php?FLOW_ID=" + FLOW_ID+"&PAGE_START=<?=$PAGE_START?>";
  window.location=URL;
}

function stop_flow(FLOW_ID)
{
  URL="stop.php?FLOW_ID=" + FLOW_ID+"&PAGE_START=<?=$PAGE_START?>";
  window.location=URL;
}
function CheckForm()
{
   if(document.form1.BEGIN_DATE.value=="")
   { alert("<?=_("上报起始日期不能为空！")?>");
     return (false);
   }

   if(document.form1.END_DATE.value=="")
   { alert("<?=_("上报截止日期不能为空！")?>");
     return (false);
   }

   return (true);
}
</script>


<body class="bodycolor">
<?
$CUR_DATE=date("Y-m-d");
if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("SAL_FLOW", 5);
   $PAGE_START=intval($PAGE_START);
if(!isset($TOTAL_ITEMS))
{
   $query = "SELECT count(*) from SAL_FLOW";
   $cursor= exequery(TD::conn(),$query, $connstatus);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("已定义的工资流程")?></span>
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
 //============================ 显示已定义工资上报流程 =======================================
 $query = "SELECT * from SAL_FLOW order by SEND_TIME desc limit $PAGE_START, $PAGE_SIZE";
 $cursor= exequery(TD::conn(),$query, $connstatus);
 $FLOW_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $FLOW_COUNT++;
    $FLOW_ID=$ROW["FLOW_ID"];
    $SAL_CREATER=$ROW["SAL_CREATER"];
    $SAL_CREATER=substr(GetUserNameById($SAL_CREATER),0,-1);
    $SAL_YEAR=$ROW["SAL_YEAR"];
    $SAL_MONTH=$ROW["SAL_MONTH"];
    $SAL_MONTH=sprintf(_("%s年%s月"), $SAL_YEAR, $SAL_MONTH);
    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $BEGIN_DATE=strtok($BEGIN_DATE," ");
    $END_DATE=$ROW["END_DATE"];
    $END_DATE=strtok($END_DATE," ");
    if($END_DATE=="0000-00-00")
       $END_DATE="";
    $CONTENT=$ROW["CONTENT"];
    $SEND_TIME=$ROW["SEND_TIME"];
    //现用作工资流程状态标记，0为使用中，1为终止
    $ISSEND=$ROW["ISSEND"];
    if($END_DATE=="1980-01-01")
       $END_DATE_DESC=_("已终止");
    elseif($END_DATE=="")
	   $END_DATE_DESC=_("需手动终止");
	else
       $END_DATE_DESC=$END_DATE;

    if($FLOW_COUNT==1)
    {
?>

    <table width="95%" class="TableList">
    <thead class="TableHeader">
      <td nowrap align="center"><?=_("流程创建时间")?> <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_down.gif" width="11" height="10"></td>
      <td nowrap align="center"><?=_("流程创建者")?></td>
      <td nowrap align="center"><?=_("起始日期")?></td>
      <td nowrap align="center"><?=_("截止日期")?></td>
      <td nowrap align="center"><?=_("工资月份")?></td>
      <td nowrap align="center"><?=_("备注")?></td>
      <td nowrap align="center"><?=_("状态")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
    </thead>

<?
    }
?>
    <tr class="TableData">
      <td nowrap align="center"><?=$SEND_TIME?></td>
      <td nowrap align="center"><?=$SAL_CREATER?></td>
      <td nowrap align="center"><?=$BEGIN_DATE?></td>
      <td nowrap align="center"><?=$END_DATE_DESC?></td>
      <td nowrap align="center"><?=$SAL_MONTH?></td>
      <td nowrap align="center"><?=$CONTENT?></td>
      <td nowrap align="center">
<?
    $RUNNING=0;
    if($END_DATE=="")
    {
       echo "<font color='#00AA00'><b>"._("执行中")."</b></font>";
       $RUNNING=1;
    }
    else
    {
       if(compare_date($CUR_DATE,$END_DATE)>0||$ISSEND==1)
       {
          echo "<font color='#FF0000'><b>"._("已终止")."</b></font>";
          $RUNNING=2;
       }
       elseif(compare_date($CUR_DATE,$BEGIN_DATE)>=0)
       {
          echo "<font color='#00AA00'><b>"._("执行中")."</b></font>";
          $RUNNING=1;
       }
       else
          echo _("待执行");
    }
?>
      </td>
      <td nowrap>
        <a href="import.php?FLOW_ID=<?=$FLOW_ID?>"><?=_("导入工资数据")?></a><br>
        <a href="report_manager/?FLOW_ID=<?=$FLOW_ID?>"><?=_("导出工资报表")?></a><br>     
<?
        if($RUNNING==1)
        {
?>
        <a href="javascript:stop_flow(<?=$FLOW_ID?>);"><?=_("终止")?></a>
<?
        }
        if ($RUNNING==2)
        {
?>
        <a href="javascript:send_flow(<?=$FLOW_ID?>);"><?=_("发送")?>EMAIL<?=_("工资条")?></a>
        <br><a href="javascript:mobilesend_flow(<?=$FLOW_ID?>);"><?=_("发送手机工资条")?></a>
        <br><a href="javascript:weixinsend_flow(<?=$FLOW_ID?>);"><?=_("发送微信工资条")?></a>
		<br><a href="javascript:rerun_flow(<?=$FLOW_ID?>);"><?=_("启用")?></a>
<?        
        }
?>       <a href="javascript:edit_flow(<?=$FLOW_ID?>);"><?=_("编辑")?></a>
         <a href="javascript:delete_flow(<?=$FLOW_ID?>);"><?=_("删除")?></a>
      </td>
    </tr>
<?     
 }

 if($FLOW_COUNT>0)
 {
?>
    
    </table>
<?
 }
 else
    Message("",_("尚未定义"));
?>

</body>
</html>