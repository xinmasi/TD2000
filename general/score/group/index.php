<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("����ָ�꼯����");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script>
function CheckForm()
{
   if(document.form1.GROUP_NAME.value=="")
   { alert("<?=_("����ָ�꼯���Ʋ��ܿգ�����")?>");
     return (false);
   }
   return (true);
}

</script>

<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" WIDTH="22" HEIGHT="22" align="absmiddle"><span class="big3"> <?=_("�½�����ָ�꼯")?></span><br>
    </td>
  </tr>
</table>
<br>

<div align="center">

 <table width="80%" align="center" class="TableBlock" style="text-align: left;">
  <form action="add.php?CUR_PAGE=<?=$CUR_PAGE?>"  method="post" name="form1" onsubmit="return CheckForm();">
    <tr>
      <td nowrap class="TableData"><span style="color: red;">*</span><?=_("����ָ�꼯���ƣ�")?></td>
      <td class="TableData">
         <INPUT type="text"name="GROUP_NAME" maxlength="25" class=BigInput size="20"><?=_("(�������25����)")?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("����ָ�꼯������")?></td>
      <td class="TableData">
        <textarea name="GROUP_DESC" cols="45" rows="3" class="BigInput"></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("�趨��������ģ�飺")?></td>
      <td class="TableData">
       <input type="checkbox" name="DIARY" id="DIARY_ID"><label for="DIARY_ID"><?=_("���˹�����־")?></label>&nbsp;&nbsp;<input type="checkbox" name="CALENDAR" id="CALENDAR_ID"><label for="CALENDAR_ID"><?=_("�����ճ̰���")?></label>    
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData">&nbsp;<?=_("���������ã�")?></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID" value="<?=$TO_ID?>">
        <textarea cols=40 name=TO_NAME rows=2 class="BigStatic" wrap="yes" readonly><?=$TO_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept('6')"><?=_("���")?></a>
       <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("���")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData">&nbsp;<?=_("����Ա���ã�")?></td>
      <td class="TableData">
        <input type="hidden" name="USER_ID" value="<?=$USER_ID ?>">
        <textarea cols=40 name="USER_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$USER_NAME ?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('123','6','USER_ID', 'USER_NAME')"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('USER_ID', 'USER_NAME')"><?=_("���")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData">&nbsp;<?=_("����ɫ���ã�")?></td>
      <td class="TableData">
        <input type="hidden" name="PRIV_ID" value="<?=$PRIV_ID ?>">
        <textarea cols=40 name="PRIV_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$PRIV_NAME ?></textarea>   	
        <a href="javascript:;" class="orgAdd" onClick="SelectPriv('6','PRIV_ID', 'PRIV_NAME')"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('PRIV_ID', 'PRIV_NAME')"><?=_("���")?></a><br>
        <?=_("����ָ�꼯ʹ�÷�Χȡ���š���Ա�ͽ�ɫ�Ĳ���")?>
      </td>
    </tr>
    <tfoot align="center" class="TableFooter">
      <td colspan="2" nowrap>
        <input type="submit" value="<?=_("�½�")?>" class="BigButton">&nbsp;&nbsp;
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
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/score.gif" WIDTH="20" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("����ָ�꼯����")?></span><br>
    </td>
  </tr>
</table>

<?
   Message("",_("�޿���ָ�꼯"));
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
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/score.gif"  WIDTH="20" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("����ָ�꼯����")?></span><br>
    </td>
<?
    $MSG_COUNT = sprintf(_("��%s��"),"<span class='big4'>&nbsp;".$VOTE_COUNT."</span>&nbsp;");
?>
    <td align="right" valign="bottom" class="small1"><?=$MSG_COUNT?>
    </td>
    <td align="right" valign="bottom" class="small1">
       <a class="A1" href="index.php?CUR_PAGE=1"><?=_("��ҳ")?></a>&nbsp;
       <a class="A1" href="index.php?CUR_PAGE=<?=$PAGE_COUNT?>"><?=_("ĩҳ")?></a>&nbsp;&nbsp;
<?
if($CUR_PAGE%$PAGES==0)
   $J=$PAGES;
else
   $J=$CUR_PAGE%$PAGES;

if($CUR_PAGE> $PAGES)
{
  $PAGE_UP = sprintf(_("��%dҳ"),$PAGES);
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
   $PAGE_DOWN = sprintf(_("��%dҳ"),$PAGES);   
?>
       <a class="A1" href="index.php?CUR_PAGE=<?=$I?>"><?=$PAGE_DOWN?></a>&nbsp;&nbsp;
<?
}
if($CUR_PAGE-1>=1)
{
?>
       <a class="A1" href="index.php?CUR_PAGE=<?=$CUR_PAGE-1?>"><?=_("��һҳ")?></a>&nbsp;
<?
}
else
{
?>
       <?=_("��һҳ")?>&nbsp;
<?
}

if($CUR_PAGE+1<= $PAGE_COUNT)
{
?>
       <a class="A1" href="index.php?CUR_PAGE=<?=$CUR_PAGE+1?>"><?=_("��һҳ")?></a>&nbsp;
<?
}
else
{
?>
       <?=_("��һҳ")?>&nbsp;
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
  	<td nowrap align="center"><?=_("����ָ�꼯����")?></td>
  	<td nowrap align="center"><?=_("����ָ�꼯����")?></td>
    <td nowrap align="center"><?=_("����")?></td>
  </tr>

<?
 //============================ ��ʾ����ָ�꼯=======================================

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
       <a href="detail/?GROUP_ID=<?=$GROUP_ID?>&CUR_PAGE=<?=$CUR_PAGE?>"><?=_("ָ�꼯��ϸ")?></a>
      <a href="modify.php?GROUP_ID=<?=$GROUP_ID?>&CUR_PAGE=<?=$CUR_PAGE?>"> <?=_("�޸�")?></a>
      <a href="javascript:delete_vote('<?=$GROUP_ID?>','<?=$CUR_PAGE?>');"> <?=_("ɾ��")?></a>
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
	 msg='<?=_("ȷ��Ҫɾ����ָ�꼯��")?>';
	 if(window.confirm(msg))
	 {
	  URL="delete.php?GROUP_ID=" + GROUP_ID + "&CUR_PAGE=" + CUR_PAGE;
	  window.location=URL;
	 }
}


function delete_all()
{
	 msg='<?=_("ȷ��Ҫɾ������ָ�꼯��")?>';
	 if(window.confirm(msg))
	 {
	  URL="delete_all.php";
	  window.location=URL;
	 }
}

</script>