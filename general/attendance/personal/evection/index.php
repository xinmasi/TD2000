<?
include_once("inc/auth.inc.php");
include_once("inc/flow_hook.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("����");
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
    msg='<?=_("ȷ��Ҫɾ���ó�����Ϣ��")?>';
    
    if(window.confirm(msg))
    {
        URL="delete.php?EVECTION_ID=" + EVECTION_ID;
        window.location=URL;
    }
}
</script>

<body class="bodycolor attendance">
<h5 class="attendance-title"><span class="big3"> <?=_("����Ǽ�")?></span></h5><br>
<br>
<div align="center">
<input type="button"  value="<?=_("����Ǽ�")?>" class="btn btn-primary" onClick="location='new/';" title="<?=_("�½�����Ǽ�")?>">&nbsp;&nbsp;
<input type="button"  value="<?=_("������ʷ��¼")?>" class="btn" onClick="location='history.php';" title="<?=_("�鿴�����ĳ����¼")?>">
<br>
<br>
<table class=" table table-bordered"  width="95%">
    <thead class="">
        <th nowrap align="center"><?=_("����ص�")?></th>
        <th nowrap align="center"><?=_("����ԭ��")?></th>
        <th nowrap align="center"><?=_("��ʼ����")?></th>
        <th nowrap align="center"><?=_("��������")?></th>
        <th nowrap align="center"><?=_("������Ա")?></th>
        <th nowrap align="center"><?=_("״̬")?></th>
        <th nowrap align="center"><?=_("����")?></th>
    </thead>
<?
 //----- ���еĳ������ -----
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
       $ALLOW_DESC=_("������");
    else if($ALLOW=="1")
       $ALLOW_DESC="<font color=green>"._("����׼")."</font>";
    else if($ALLOW=="2")
       $ALLOW_DESC="<font color=red>"._("δ��׼")."</font>";
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
      <td nowrap align="center"><a href="javascript:;" onClick="form_view('<?=$is_run_hook?>')"><?=_("�鿴����")?></a></td>
<?
      }
      else
      {
?>
      <td nowrap align="center"><?=$USER_NAME?></td>
<?
      }
?>
      <td nowrap align="center" title="<?if($ALLOW==2) echo _("ԭ��")."\n".$NOT_REASON?>"><?=$ALLOW_DESC?></td>
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
      <a href="delete.php?EVECTION_ID=<?=$EVECTION_ID?>"><?=_("ɾ��")?></a>
<?
         }
     }
     else
     {
?>         
      <a href="edit.php?EVECTION_ID=<?=$EVECTION_ID?>"><?=_("�޸�")?></a>
      <a href="javascript:delete_alert('<?=$EVECTION_ID?>');"><?=_("ɾ��")?></a>         
<?
     }        
    }
    else if($ALLOW=="1")
    {
?>
      <a href=javascript:evection_back_edit('<?=$EVECTION_ID?>');><?=_("�������")?></a>
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
    <tr><td colspan="7"><div class="emptyTip"><?=_("�޳����¼")?></div></td></tr>
<?
 }
?>
</table>
</div>
</body>
</html>