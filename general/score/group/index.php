<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("考核指标集定义");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script>
function CheckForm()
{
   if(document.form1.GROUP_NAME.value=="")
   { alert("<?=_("考核指标集名称不能空！！！")?>");
     return (false);
   }
   return (true);
}

</script>

<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" WIDTH="22" HEIGHT="22" align="absmiddle"><span class="big3"> <?=_("新建考核指标集")?></span><br>
    </td>
  </tr>
</table>
<br>

<div align="center">

 <table width="80%" align="center" class="TableBlock" style="text-align: left;">
  <form action="add.php?CUR_PAGE=<?=$CUR_PAGE?>"  method="post" name="form1" onsubmit="return CheckForm();">
    <tr>
      <td nowrap class="TableData"><span style="color: red;">*</span><?=_("考核指标集名称：")?></td>
      <td class="TableData">
         <INPUT type="text"name="GROUP_NAME" maxlength="25" class=BigInput size="20"><?=_("(最多输入25个字)")?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("考核指标集描述：")?></td>
      <td class="TableData">
        <textarea name="GROUP_DESC" cols="45" rows="3" class="BigInput"></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("设定考核依据模块：")?></td>
      <td class="TableData">
       <input type="checkbox" name="DIARY" id="DIARY_ID"><label for="DIARY_ID"><?=_("个人工作日志")?></label>&nbsp;&nbsp;<input type="checkbox" name="CALENDAR" id="CALENDAR_ID"><label for="CALENDAR_ID"><?=_("个人日程安排")?></label>    
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData">&nbsp;<?=_("按部门设置：")?></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID" value="<?=$TO_ID?>">
        <textarea cols=40 name=TO_NAME rows=2 class="BigStatic" wrap="yes" readonly><?=$TO_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept('6')"><?=_("添加")?></a>
       <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData">&nbsp;<?=_("按人员设置：")?></td>
      <td class="TableData">
        <input type="hidden" name="USER_ID" value="<?=$USER_ID ?>">
        <textarea cols=40 name="USER_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$USER_NAME ?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('123','6','USER_ID', 'USER_NAME')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('USER_ID', 'USER_NAME')"><?=_("清空")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData">&nbsp;<?=_("按角色设置：")?></td>
      <td class="TableData">
        <input type="hidden" name="PRIV_ID" value="<?=$PRIV_ID ?>">
        <textarea cols=40 name="PRIV_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$PRIV_NAME ?></textarea>   	
        <a href="javascript:;" class="orgAdd" onClick="SelectPriv('6','PRIV_ID', 'PRIV_NAME')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('PRIV_ID', 'PRIV_NAME')"><?=_("清空")?></a><br>
        <?=_("考核指标集使用范围取部门、人员和角色的并集")?>
      </td>
    </tr>
    <tfoot align="center" class="TableFooter">
      <td colspan="2" nowrap>
        <input type="submit" value="<?=_("新建")?>" class="BigButton">&nbsp;&nbsp;
      </td>
    </tfoot>
  </table>
</form>
</div>


<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
    <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>

<?
 $query = "SELECT count(*) from SCORE_GROUP";
 $cursor= exequery(TD::conn(),$query, $connstatus);
 $VOTE_COUNT=0;
 if($ROW=mysql_fetch_array($cursor))
    $VOTE_COUNT=$ROW[0];

 if($VOTE_COUNT==0)
 {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/score.gif" WIDTH="20" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("考核指标集管理")?></span><br>
    </td>
  </tr>
</table>

<?
   Message("",_("无考核指标集"));
   exit;
 }

 $PER_PAGE=5;
 $PAGES=10;
 $PAGE_COUNT=ceil($VOTE_COUNT/$PER_PAGE);

 if($CUR_PAGE<=0 || $CUR_PAGE=="")
    $CUR_PAGE=1;
 if($CUR_PAGE>$PAGE_COUNT)
    $CUR_PAGE=$PAGE_COUNT;
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/score.gif"  WIDTH="20" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("考核指标集管理")?></span><br>
    </td>
<?
    $MSG_COUNT = sprintf(_("共%s条"),"<span class='big4'>&nbsp;".$VOTE_COUNT."</span>&nbsp;");
?>
    <td align="right" valign="bottom" class="small1"><?=$MSG_COUNT?>
    </td>
    <td align="right" valign="bottom" class="small1">
       <a class="A1" href="index.php?CUR_PAGE=1"><?=_("首页")?></a>&nbsp;
       <a class="A1" href="index.php?CUR_PAGE=<?=$PAGE_COUNT?>"><?=_("末页")?></a>&nbsp;&nbsp;
<?
if($CUR_PAGE%$PAGES==0)
   $J=$PAGES;
else
   $J=$CUR_PAGE%$PAGES;

if($CUR_PAGE> $PAGES)
{
  $PAGE_UP = sprintf(_("上%d页"),$PAGES);
?>
       <a class="A1" href="index.php?CUR_PAGE=<?=$CUR_PAGE-$J-$PAGES+1?>"><?=$PAGE_UP?></a>&nbsp;&nbsp;
<?
}

for($I=$CUR_PAGE-$J+1;$I<=$CUR_PAGE-$J+$PAGES;$I++)
{
   if($I>$PAGE_COUNT)
      break;

   if($I==$CUR_PAGE)
   {
?>
       [<?=$I?>]&nbsp;
<?
   }
   else
   {
?>
       [<a class="A1" href="index.php?CUR_PAGE=<?=$I?>"><?=$I?></a>]&nbsp;
<?
   }
}
?>
      &nbsp;
<?
if($I-1< $PAGE_COUNT)
{
   $PAGE_DOWN = sprintf(_("下%d页"),$PAGES);   
?>
       <a class="A1" href="index.php?CUR_PAGE=<?=$I?>"><?=$PAGE_DOWN?></a>&nbsp;&nbsp;
<?
}
if($CUR_PAGE-1>=1)
{
?>
       <a class="A1" href="index.php?CUR_PAGE=<?=$CUR_PAGE-1?>"><?=_("上一页")?></a>&nbsp;
<?
}
else
{
?>
       <?=_("上一页")?>&nbsp;
<?
}

if($CUR_PAGE+1<= $PAGE_COUNT)
{
?>
       <a class="A1" href="index.php?CUR_PAGE=<?=$CUR_PAGE+1?>"><?=_("下一页")?></a>&nbsp;
<?
}
else
{
?>
       <?=_("下一页")?>&nbsp;
<?
}
?>
       &nbsp;
    </td>
    </tr>
</table>

<div align="center">
<table width="95%" class="TableList">
  <tr class="TableHeader">
  	<td nowrap align="center"><?=_("考核指标集名称")?></td>
  	<td nowrap align="center"><?=_("考核指标集描述")?></td>
    <td nowrap align="center"><?=_("操作")?></td>
  </tr>

<?
 //============================ 显示考核指标集=======================================

$query ="SELECT * from SCORE_GROUP order by GROUP_ID DESC";
$cursor= exequery(TD::conn(),$query, $connstatus);
$VOTE_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $VOTE_COUNT++;

    if($VOTE_COUNT<$CUR_PAGE*$PER_PAGE-$PER_PAGE+1)
       continue;
    if($VOTE_COUNT>$CUR_PAGE*$PER_PAGE)
       break;

    $GROUP_ID=$ROW["GROUP_ID"];
    $GROUP_NAME=$ROW["GROUP_NAME"];
    $GROUP_DESC=$ROW["GROUP_DESC"];

    if($VOTE_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
     <tr class="<?=$TableLine?>">


      <td align="center"><?=$GROUP_NAME?></td>
      <td align="center"><?=$GROUP_DESC?></td>
      <td nowrap align="center">
       <a href="detail/?GROUP_ID=<?=$GROUP_ID?>&CUR_PAGE=<?=$CUR_PAGE?>"><?=_("指标集明细")?></a>
      <a href="modify.php?GROUP_ID=<?=$GROUP_ID?>&CUR_PAGE=<?=$CUR_PAGE?>"> <?=_("修改")?></a>
      <a href="javascript:delete_vote('<?=$GROUP_ID?>','<?=$CUR_PAGE?>');"> <?=_("删除")?></a>
      </td>
    </tr>
<?
 }
?>


</table>
</div>
</body>
</html>
<script>

function delete_vote(GROUP_ID,CUR_PAGE)
{
	 msg='<?=_("确认要删除该指标集？")?>';
	 if(window.confirm(msg))
	 {
	  URL="delete.php?GROUP_ID=" + GROUP_ID + "&CUR_PAGE=" + CUR_PAGE;
	  window.location=URL;
	 }
}


function delete_all()
{
	 msg='<?=_("确认要删除所有指标集？")?>';
	 if(window.confirm(msg))
	 {
	  URL="delete_all.php";
	  window.location=URL;
	 }
}

</script>