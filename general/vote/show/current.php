<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("show_functions.php");//显示vote信息的一些公用函数

if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("VOTE", 15);
if(!isset($start))
   $start=0;

if(!isset($ASC_DESC))
{
	$ASC_DESC = 0;
}

$HTML_PAGE_TITLE = _("已发布的投票");
include_once("inc/header.inc.php");
?>

<script>
function open_vote(VOTE_ID)
{
 URL="read_vote.php?VOTE_ID="+VOTE_ID;
 myleft=(screen.availWidth-780)/2;
 window.open(URL,"read_vote"+VOTE_ID,"height=500,width=780,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}

function view_result(VOTE_ID)
{
 URL="show_reader.php?VOTE_ID="+VOTE_ID;
 myleft=(screen.availWidth-780)/2;
 window.open(URL,"read_vote"+VOTE_ID,"height=500,width=780,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}

function order_by(ASC_DESC)
{
 window.location="current.php?start=<?=$start?>&ASC_DESC="+ASC_DESC;
}
</script>

<body class="bodycolor">

<?
if($ASC_DESC=="0")
{
	$ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_up.gif\" width=\"11\" height=\"10\">";
}
else
{
	$ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_down.gif\" width=\"11\" height=\"10\">";
}
if($ASC_DESC=="1")
{
	$order = " desc";
}
else
{
	$order = " asc";
}
$CUR_DATE=date("Y-m-d H:i:s",time());
 
$query = "SELECT VOTE_ID from VOTE_TITLE where PUBLISH='1' and PARENT_ID=0 and (TO_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID)".dept_other_sql("TO_ID")." or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID) or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID)".priv_other_sql("PRIV_ID")."  or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',VIEW_RESULT_USER_ID)  or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',VIEW_RESULT_PRIV_ID)".priv_other_sql("VIEW_RESULT_PRIV_ID").") and BEGIN_DATE<='$CUR_DATE' and (END_DATE>'$CUR_DATE' or END_DATE is null) order by BEGIN_DATE ".$order;
$cursor= exequery(TD::conn(),$query);
 
$VOTE_COUNT=mysql_num_rows($cursor);

 if($VOTE_COUNT==0)
 {
?>
<table border="0" width="95%" cellspacing="0" cellpadding="3" class="small" align="center">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vote.gif" align="absmiddle"><span class="big3"> <?=_("已发布的投票")?></span><br>
    </td>
  </tr>
</table>
<br>

<?
   Message("",_("无已发布的投票"));
   exit;
 }
?>

<table border="0" width="95%" cellspacing="0" cellpadding="3" class="small" align="center">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vote.gif" align="absmiddle"><span class="big3"> <?=_("已发布的投票")?></span><br>
    </td>
    <td align="right" valign="bottom" class="small1"><?=sprintf(_("共%s条"),"<span class='big4'>&nbsp;".$VOTE_COUNT."</span>&nbsp;");?>
    </td>
    <td align="right" valign="bottom" class="small1"><?=page_bar($start,$VOTE_COUNT,$PAGE_SIZE)?></td>
    </tr>
</table>

<table class="TableList" width="95%" align="center">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("发布人")?></td>
      <td nowrap align="center"><?=_("标题")?></td>
      <td nowrap align="center"><?=_("匿名")?></td>
      <td nowrap align="center" onClick="order_by(<?=$ASC_DESC==0?"1":"0";?>);"><?=_("生效日期")?><?=$ORDER_IMG?></td>
      <td nowrap align="center"><?=_("终止日期")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
    </tr>

<?
 //============================ 显示已发布投票 =======================================
 $VOTE_COUNT=0;
 $query = "SELECT * from VOTE_TITLE where PUBLISH='1' and PARENT_ID=0 and (TO_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID)".dept_other_sql("TO_ID")." or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID) or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID)".priv_other_sql("PRIV_ID")."  or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',VIEW_RESULT_USER_ID)  or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',VIEW_RESULT_PRIV_ID)".priv_other_sql("VIEW_RESULT_PRIV_ID").") and BEGIN_DATE<='$CUR_DATE' and (END_DATE>'$CUR_DATE' or END_DATE is null) order by TOP desc,BEGIN_DATE ".$order." limit $start,$PAGE_SIZE";
 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
    $VOTE_COUNT++;

    $VOTE_ID=$ROW["VOTE_ID"];
    $FROM_ID=$ROW["FROM_ID"];
    $TO_ID=$ROW["TO_ID"];
    $SUBJECT=$ROW["SUBJECT"];
    $TYPE=$ROW["TYPE"];
    $VIEW_PRIV=$ROW["VIEW_PRIV"];
    $ANONYMITY=$ROW["ANONYMITY"];
    $SEND_TIME=$ROW["SEND_TIME"];
    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $END_DATE=$ROW["END_DATE"];
    $READERS=$ROW["READERS"];
    $TOP=$ROW["TOP"];
    $VIEW_RESULT_PRIV_ID=$ROW["VIEW_RESULT_PRIV_ID"];//该投票在数据库中保存的"查看权限范围（角色）"信息  宋阳添加
	$VIEW_RESULT_USER_ID=$ROW["VIEW_RESULT_USER_ID"];//该投票在数据库中保存的"查看权限范围（人员）"信息  宋阳添加
    $SUBJECT_TITLE="";
    if(strlen($SUBJECT) > 50)
    {
       $SUBJECT_TITLE=$SUBJECT;
       $SUBJECT=csubstr($SUBJECT, 0, 50)."...";
    }
    $SUBJECT=td_htmlspecialchars($SUBJECT);
    $SUBJECT_TITLE=td_htmlspecialchars($SUBJECT_TITLE);
    
    if($TOP=="1")
       $SUBJECT="<font color=red><b>".$SUBJECT."</b></font>";

    if($ANONYMITY=="0")
       $ANONYMITY_DESC=_("不允许");
    else
       $ANONYMITY_DESC=_("允许");

    if($END_DATE=="0000-00-00 00:00:00")
       $END_DATE="";

    $query1="select USER_NAME,DEPT_ID from USER where USER_ID='$FROM_ID'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
    {
       $FROM_NAME=$ROW["USER_NAME"];
       $DEPT_ID=$ROW["DEPT_ID"];
    }

    $DEPT_NAME=dept_long_name($DEPT_ID);

    if($VOTE_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
      <td nowrap align="center"><u title="<?=_("部门：")?><?=$DEPT_NAME?>" style="cursor:hand"><?=$FROM_NAME?></u></td>
      <td>
<?
if($VIEW_PRIV!="2" && find_id($READERS,$_SESSION["LOGIN_USER_ID"]))
{
?>
      <a href="javascript:view_result('<?=$VOTE_ID?>');" title="<?=$SUBJECT_TITLE?>"><?=$SUBJECT?></a>
<?
}
else
{
?>
      <a href="javascript:open_vote('<?=$VOTE_ID?>');" title="<?=$SUBJECT_TITLE?>"><?=$SUBJECT?></a>
<?
}
?>
      </td>
      <td nowrap align="center"><?=$ANONYMITY_DESC?></td>
      <td nowrap align="center"><?=substr($BEGIN_DATE,0,10)?></td>
      <td nowrap align="center"><?=substr($END_DATE,0,10)?></td>
      <td nowrap align="center">
<?
if($VIEW_PRIV!="2")
{
?>
	<a href="javascript:<?if(checkUserLookToResultPriv($VIEW_RESULT_PRIV_ID, $VIEW_RESULT_USER_ID)){?>view_result('<?=$VOTE_ID?>')<?}elseif($VIEW_PRIV=="0"&&!find_id($READERS,$_SESSION["LOGIN_USER_ID"])){?>alert('<?=_("投票后才能查看投票结果！")?>')<?}else{?>view_result('<?=$VOTE_ID?>')<?}?>;"><?=_("查看结果")?></a>
<?
}
/*************即使投票被设为“不允许查看” 有查看权限的用户也可以查看*********/
elseif(checkUserLookToResultPriv($VIEW_RESULT_PRIV_ID, $VIEW_RESULT_USER_ID))
{
?>
	<a href="javascript:view_result('<?=$VOTE_ID?>');"><?=_("查看结果")?></a>
<?}
/*************即使投票被设为“不允许查看” 有查看权限的用户也可以查看*********/
?>
      </td>
    </tr>
<?
 }
?>

</table>
</body>

</html>
