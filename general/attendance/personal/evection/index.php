<?
include_once("inc/auth.inc.php");
include_once("inc/flow_hook.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("出差");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script>
//window.setTimeout('this.location.reload();',120000);

function evection_back_edit(EVECTION_ID)
{
 URL="evection_back_edit.php?EVECTION_ID="+EVECTION_ID;
 myleft=(screen.availWidth-780)/2;
 mytop=100;
 mywidth=650;
 myheight=400;
 window.open(URL,"evection_back_edit","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}
function form_view(RUN_ID)
{
window.open("/general/workflow/list/print/?RUN_ID="+RUN_ID+"&HOOK=1","","status=0,toolbar=no,menubar=no,width="+(screen.availWidth-12)+",height="+(screen.availHeight-38)+",location=no,scrollbars=yes,resizable=yes,left=0,top=0");
}
function delete_alert(EVECTION_ID)
{
    msg='<?=_("确认要删除该出差信息吗？")?>';
    
    if(window.confirm(msg))
    {
        URL="delete.php?EVECTION_ID=" + EVECTION_ID;
        window.location=URL;
    }
}
</script>

<body class="bodycolor attendance">
<h5 class="attendance-title"><span class="big3"> <?=_("出差登记")?></span></h5><br>
<br>
<div align="center">
<input type="button"  value="<?=_("出差登记")?>" class="btn btn-primary" onClick="location='new/';" title="<?=_("新建出差登记")?>">&nbsp;&nbsp;
<input type="button"  value="<?=_("出差历史记录")?>" class="btn" onClick="location='history.php';" title="<?=_("查看过往的出差记录")?>">
<br>
<br>
<table class=" table table-bordered"  width="95%">
    <thead class="">
        <th nowrap align="center"><?=_("出差地点")?></th>
        <th nowrap align="center"><?=_("出差原因")?></th>
        <th nowrap align="center"><?=_("开始日期")?></th>
        <th nowrap align="center"><?=_("结束日期")?></th>
        <th nowrap align="center"><?=_("审批人员")?></th>
        <th nowrap align="center"><?=_("状态")?></th>
        <th nowrap align="center"><?=_("操作")?></th>
    </thead>
<?
 //----- 现行的出差情况 -----
 $EVECTION_COUNT=0;

 $query = "SELECT * from ATTEND_EVECTION where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and STATUS='1'";
 $cursor= exequery(TD::conn(),$query, $connstatus);
 while($ROW=mysql_fetch_array($cursor))
 {
    $EVECTION_COUNT++;

    $EVECTION_ID=$ROW["EVECTION_ID"];
    $EVECTION_DATE1=$ROW["EVECTION_DATE1"];
    $EVECTION_DATE1=strtok($EVECTION_DATE1," ");

    $EVECTION_DATE2=$ROW["EVECTION_DATE2"];
    $EVECTION_DATE2=strtok($EVECTION_DATE2," ");

    $EVECTION_DEST=$ROW["EVECTION_DEST"];
    $ALLOW=$ROW["ALLOW"];
    $LEADER_ID=$ROW["LEADER_ID"];
    $NOT_REASON=$ROW["NOT_REASON"];
    $REASON=$ROW["REASON"];

    $USER_NAME="";
    $query = "SELECT * from USER where USER_ID='$LEADER_ID'";
    $cursor1= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor1))
       $USER_NAME=$ROW["USER_NAME"];

    if($ALLOW=="0")
       $ALLOW_DESC=_("待审批");
    else if($ALLOW=="1")
       $ALLOW_DESC="<font color=green>"._("已批准")."</font>";
    else if($ALLOW=="2")
       $ALLOW_DESC="<font color=red>"._("未批准")."</font>";
?>
    <tr class="TableData">
      <td nowrap align="center"><?=$EVECTION_DEST?></td>
      <td style="word-break:break-all" align="left"><?=$REASON?></td>
      <td nowrap align="center"><?=$EVECTION_DATE1?></td>
      <td nowrap align="center"><?=$EVECTION_DATE2?></td>
<?
      $is_run_hook=is_run_hook("EVECTION_ID",$EVECTION_ID);
      if($is_run_hook!=0)
      {
?>
      <td nowrap align="center"><a href="javascript:;" onClick="form_view('<?=$is_run_hook?>')"><?=_("查看流程")?></a></td>
<?
      }
      else
      {
?>
      <td nowrap align="center"><?=$USER_NAME?></td>
<?
      }
?>
      <td nowrap align="center" title="<?if($ALLOW==2) echo _("原因：")."\n".$NOT_REASON?>"><?=$ALLOW_DESC?></td>
      <td nowrap align="center">
<?
    if($ALLOW=="0" || $ALLOW=="2")
    {
    	if($is_run_hook!=0)
      {    	
    	   $query2 = "SELECT * from FLOW_RUN where RUN_ID='$is_run_hook' and DEL_FLAG='0'";
         $cursor2= exequery(TD::conn(),$query2);
         if(!$ROW2=mysql_fetch_array($cursor2))
         {  
?>
      <a href="delete.php?EVECTION_ID=<?=$EVECTION_ID?>"><?=_("删除")?></a>
<?
         }
     }
     else
     {
?>         
      <a href="edit.php?EVECTION_ID=<?=$EVECTION_ID?>"><?=_("修改")?></a>
      <a href="javascript:delete_alert('<?=$EVECTION_ID?>');"><?=_("删除")?></a>         
<?
     }        
    }
    else if($ALLOW=="1")
    {
?>
      <a href=javascript:evection_back_edit('<?=$EVECTION_ID?>');><?=_("出差归来")?></a>
<?
    }
?>
      </td>
    </tr>
<?
 }

 if($EVECTION_COUNT==0)
 {
?>
    <tr><td colspan="7"><div class="emptyTip"><?=_("无出差记录")?></div></td></tr>
<?
 }
?>
</table>
</div>
</body>
</html>