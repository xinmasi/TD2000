<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("����������");
include_once("inc/header.inc.php");
?>

<script>
function delete_mr(MR_ID)
{
 msg='<?=_("ȷ��Ҫɾ���û�������")?>';
 if(window.confirm(msg))
 {
    URL="delete.php?MR_ID=" + MR_ID;
    window.location=URL;
 }
}

function delete_all()
{
 msg='<?=_("ȷ��Ҫɾ�����л�������")?>';
 if(window.confirm(msg))
 {
    URL="delete_all.php";
    window.location=URL;
 }
}
</script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�½�������")?></span>
    </td>
  </tr>
</table>
<div align="center">
  <input type="button"  value="<?=_("�½�������")?>" class="BigButton" onClick="location='new_room.php';" title="<?=_("�½������ң�����ӻ�����Ϣ")?>">
</div>
<br>

<?
$query = "SELECT count(*) from MEETING_ROOM";
$cursor= exequery(TD::conn(),$query);
$ROOM_COUNT=0;
if($ROW=mysql_fetch_array($cursor))
   $ROOM_COUNT=$ROW[0];

if($ROOM_COUNT==0)
{
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("���������")?></span>
    </td>
  </tr>
</table>
<?
Message("",_("�޶���Ļ�����"));
exit;
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("���������")?></span>
    </td>
		<?
  		$MSG_ROOM_COUNT=sprintf(_("��%s��������"),"<span class='big4'>&nbsp;".$ROOM_COUNT."</span>&nbsp;");
  	?>
    <td valign="bottom" class="small1"><?=$MSG_ROOM_COUNT ?>
    </td>
  </tr>
</table>
<table class="TableList" width="95%" align="center">
  <tr class="TableHeader">
    <td nowrap align="center"><?=_("����")?></td>
    <td nowrap align="center"><?=_("����������")?></td>
    <td nowrap align="center"><?=_("�豸���")?></td>
    <td nowrap align="center"><?=_("���ڵص�")?></td>
    <td nowrap align="center"><?=_("����������")?></td>
    <td nowrap align="center"><?=_("�����ҹ���Ա")?></td>
    <td nowrap align="center"><?=_("����Ȩ��(����)")?></td>
    <td nowrap align="center"><?=_("����Ȩ��(��Ա)")?></td>
    <td nowrap align="center"><?=_("����")?></td>
  </tr>

<?
$query = "SELECT * from MEETING_ROOM order by MR_ID";
$cursor= exequery(TD::conn(),$query);
$ROOM_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $ROOM_COUNT++;

   $MR_ID=$ROW["MR_ID"];
   $MR_NAME=$ROW["MR_NAME"];
   $MR_CAPACITY=$ROW["MR_CAPACITY"];
   $MR_DEVICE=$ROW["MR_DEVICE"];
   $MR_DESC=$ROW["MR_DESC"];
   $MR_PLACE=$ROW["MR_PLACE"];
   $OPERATOR=$ROW["OPERATOR"];
   $TO_ID=$ROW["TO_ID"];
   $SECRET_TO_ID=$ROW["SECRET_TO_ID"];
   $query1 = "SELECT USER_NAME from USER where find_in_set(USER_ID,'".$OPERATOR."')";
   $cursor1= exequery(TD::conn(),$query1);
   while($ROW1=mysql_fetch_array($cursor1))
   {
      $OPERATOR_NAME.=$ROW1["USER_NAME"].",";
   }
   $query1 = "SELECT USER_NAME from USER where find_in_set(USER_ID,'".$SECRET_TO_ID."')";
   $cursor1= exequery(TD::conn(),$query1);
   while($ROW1=mysql_fetch_array($cursor1))
   {
      $SECRET_TO_NAME.=$ROW1["USER_NAME"].",";
   }
   $TOK=strtok($TO_ID,",");
   while($TOK!="")
   {
    $query2 = "SELECT DEPT_NAME from DEPARTMENT where DEPT_ID='$TOK'";
    $cursor2= exequery(TD::conn(),$query2);
    if($ROW=mysql_fetch_array($cursor2))
       $DEPT_NAME.=$ROW["DEPT_NAME"].",";
    $TOK=strtok(",");
   }
   
   if($TO_ID=="ALL_DEPT")
      $DEPT_NAME=_("ȫ�岿��");

   if($ROOM_COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";
      
   if(strlen($MR_DESC)>15)
   {
      $MR_DESC_SHORT= csubstr($MR_DESC,0,15);
      $MR_DESC_SHORT=$MR_DESC_SHORT."......";
   }
   else
      $MR_DESC_SHORT=$MR_DESC;
?>

   <tr class="<?=$TableLine?>">
     <td nowrap align="center"><?=$MR_NAME?></td>
     <td nowrap align="center"><?=$MR_CAPACITY?></a></td>
     <td align="center"><?=$MR_DEVICE?></a></td>
     <td align="center"><?=$MR_PLACE?></td>
     <td align="center" title="<?=$MR_DESC?>"><? if($MR_DESC!="") echo $MR_DESC_SHORT;?> </td>   
     <td align="center"><?=$OPERATOR_NAME?></td>  
     <td align="center"><?=$DEPT_NAME?></td>   
     <td align="center"><?=$SECRET_TO_NAME?></td> 
     <td nowrap align="center">
     <a href="new_room.php?MR_ID=<?=$MR_ID?>"> <?=_("�޸�")?></a>&nbsp;&nbsp;&nbsp;
     <a href="javascript:delete_mr('<?=$MR_ID?>');"> <?=_("ɾ��")?></a>
     </td>
   </tr>
<?
   $OPERATOR_NAME="";
   $SECRET_TO_NAME="";
   $DEPT_NAME="";
}//while
?>
<tr class="TableControl">
 <td colspan="9" align="center">
    <input type="button"  value="<?=_("ȫ��ɾ��")?>" class="SmallButton" onClick="delete_all()" title="<?=_("ɾ�����л�����")?>">
 </td>
</tr>
</table>
</body>

</html>
