<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("show_functions.php");//��ʾvote��Ϣ��һЩ���ú���

if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("VOTE", 15);
if(!isset($start))
   $start=0;

if(!isset($ASC_DESC))
{
	$ASC_DESC = 0;
}

$HTML_PAGE_TITLE = _("�ѷ�����ͶƱ");
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
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vote.gif" align="absmiddle"><span class="big3"> <?=_("�ѷ�����ͶƱ")?></span><br>
    </td>
  </tr>
</table>
<br>

<?
   Message("",_("���ѷ�����ͶƱ"));
   exit;
 }
?>

<table border="0" width="95%" cellspacing="0" cellpadding="3" class="small" align="center">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vote.gif" align="absmiddle"><span class="big3"> <?=_("�ѷ�����ͶƱ")?></span><br>
    </td>
    <td align="right" valign="bottom" class="small1"><?=sprintf(_("��%s��"),"<span class='big4'>&nbsp;".$VOTE_COUNT."</span>&nbsp;");?>
    </td>
    <td align="right" valign="bottom" class="small1"><?=page_bar($start,$VOTE_COUNT,$PAGE_SIZE)?></td>
    </tr>
</table>

<table class="TableList" width="95%" align="center">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("������")?></td>
      <td nowrap align="center"><?=_("����")?></td>
      <td nowrap align="center"><?=_("����")?></td>
      <td nowrap align="center" onClick="order_by(<?=$ASC_DESC==0?"1":"0";?>);"><?=_("��Ч����")?><?=$ORDER_IMG?></td>
      <td nowrap align="center"><?=_("��ֹ����")?></td>
      <td nowrap align="center"><?=_("����")?></td>
    </tr>

<?
 //============================ ��ʾ�ѷ���ͶƱ =======================================
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
    $VIEW_RESULT_PRIV_ID=$ROW["VIEW_RESULT_PRIV_ID"];//��ͶƱ�����ݿ��б����"�鿴Ȩ�޷�Χ����ɫ��"��Ϣ  �������
	$VIEW_RESULT_USER_ID=$ROW["VIEW_RESULT_USER_ID"];//��ͶƱ�����ݿ��б����"�鿴Ȩ�޷�Χ����Ա��"��Ϣ  �������
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
       $ANONYMITY_DESC=_("������");
    else
       $ANONYMITY_DESC=_("����");

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
      <td nowrap align="center"><u title="<?=_("���ţ�")?><?=$DEPT_NAME?>" style="cursor:hand"><?=$FROM_NAME?></u></td>
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
	<a href="javascript:<?if(checkUserLookToResultPriv($VIEW_RESULT_PRIV_ID, $VIEW_RESULT_USER_ID)){?>view_result('<?=$VOTE_ID?>')<?}elseif($VIEW_PRIV=="0"&&!find_id($READERS,$_SESSION["LOGIN_USER_ID"])){?>alert('<?=_("ͶƱ����ܲ鿴ͶƱ�����")?>')<?}else{?>view_result('<?=$VOTE_ID?>')<?}?>;"><?=_("�鿴���")?></a>
<?
}
/*************��ʹͶƱ����Ϊ��������鿴�� �в鿴Ȩ�޵��û�Ҳ���Բ鿴*********/
elseif(checkUserLookToResultPriv($VIEW_RESULT_PRIV_ID, $VIEW_RESULT_USER_ID))
{
?>
	<a href="javascript:view_result('<?=$VOTE_ID?>');"><?=_("�鿴���")?></a>
<?}
/*************��ʹͶƱ����Ϊ��������鿴�� �в鿴Ȩ�޵��û�Ҳ���Բ鿴*********/
?>
      </td>
    </tr>
<?
 }
?>

</table>
</body>

</html>
