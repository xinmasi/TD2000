<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("ͼ�����");
include_once("inc/header.inc.php");
?>

<script Language="JavaScript">
function delete_manage(BORROW_ID)
{
    msg='<?=_("ȷ��Ҫɾ��ͼ��¼��")?>';
    if(window.confirm(msg))
    {
        URL="delete.php?BORROW_ID=" + BORROW_ID + "&STATUS=<?=$STATUS?>";
        window.location=URL;
    }
}

function return_book(BORROW_ID)
{
    msg='<?=_("ȷ��Ҫ��������")?>';
    if(window.confirm(msg))
    {
        URL="return.php?BORROW_ID=" + BORROW_ID + "&STATUS=<?=$STATUS?>";
        window.location=URL;
    }
}

function delete_flag(BORROW_ID)
{
    msg='<?=_("ȷ��Ҫɾ����")?>';
    if(window.confirm(msg))
    {
        URL="delete.php?BORROW_ID=" + BORROW_ID + "&DELETE_FLAG=1";
        window.location=URL;
    }
}
</script>


<body class="bodycolor">
<?
if($STATUS==0)
   $STATUS_DESC=_("��������");
elseif($STATUS==1)
   $STATUS_DESC=_("��׼����");
elseif($STATUS==2)
   $STATUS_DESC=_("δ׼����");
   
$query = "SELECT count(*) from BOOK_MANAGE where STATUS='$STATUS' and BUSER_ID='".$_SESSION["LOGIN_USER_ID"]."' and DELETE_FLAG!=1 ";
$cursor= exequery(TD::conn(),$query);
$BORROW_COUNT=0;
if($ROW=mysql_fetch_array($cursor))
   $BORROW_COUNT=$ROW[0];
   
if($BORROW_COUNT==0)
{
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
<tr>
  <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/book.gif" width="22" height="18"><span class="big3"> <?=$STATUS_DESC?></span>
  </td>
</tr>
</table>

<?
$MSG1 = sprintf(_("��%s"), $STATUS_DESC);
Message("",$MSG1);
exit;
}
if($B_ID)
{
    //�޸���������״̬--yc
    update_sms_status('73',$B_ID);
}else
{
    //�޸���������״̬--yc
    update_sms_status('73',0);
}

$MSG2 = sprintf(_("��%s����¼"), '<span class="big4">&nbsp;'.$BORROW_COUNT.'</span>&nbsp;');
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
<tr>
  <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/book.gif" width="22" height="18"><span class="big3"> <?=$STATUS_DESC?></span>
  </td>
  <td valign="bottom" class="small1" align="center"><?=$MSG2?>
  </td>
</tr>
</table>

<table class="TableList" width="95%" align="center">
<tr class="TableHeader">
  <td nowrap align="center"><?=_("����")?></td>
  <td nowrap align="center"><?=_("ͼ����")?></td>  
  <td nowrap align="center"><?=_("��������")?></td>
  <td nowrap align="center"><?=_("��������")?></td>
  <td nowrap align="center"><?=_("�Ǽ���")?></td>
  <td nowrap align="center"><?=_("״̬")?></td>
  <td nowrap align="center"><?=_("����")?></td>
</tr>

<?
//$query = "SELECT * from BOOK_MANAGE where STATUS='$STATUS' and BUSER_ID='".$_SESSION["LOGIN_USER_ID"]."' and DELETE_FLAG!=1 order by RETURN_DATE desc";
if($_SESSION["LOGIN_USER_PRIV"]=="1"||$POST_PRIV=="1") {
    $query = "SELECT * from BOOK_MANAGE where STATUS='$STATUS' and BUSER_ID='".$_SESSION["LOGIN_USER_ID"]."' and DELETE_FLAG!=1 order by RETURN_DATE desc";
}
else if($_SESSION["LOGIN_USER_PRIV_TYPE"]!="1" && $_SESSION["MYOA_IS_GROUP"]=="1")
{
    $dept_id = get_manage_dept_ids($_SESSION['LOGIN_UID']);
    $query1 = "SELECT * from USER where DEPT_ID in ('$dept_id')";
    $cursor1= exequery(TD::conn(),$query1);
    if(is_null($dept_id)) {
        while ($ROW1 = mysql_fetch_array($cursor1)) {
            $where = $ROW1['USER_ID'];
            $query = "SELECT * from BOOK_MANAGE where STATUS='$STATUS' and BUSER_ID in ('".$_SESSION["LOGIN_USER_ID"]."','$where')  and DELETE_FLAG!=1 order by RETURN_DATE desc";
        }
    }else{
        $query = "SELECT * from BOOK_MANAGE where STATUS='$STATUS' and BUSER_ID='".$_SESSION["LOGIN_USER_ID"]."' and DELETE_FLAG!=1 order by RETURN_DATE desc";
    }
}else{
    $query = "SELECT * from BOOK_MANAGE where STATUS='$STATUS' and BUSER_ID='".$_SESSION["LOGIN_USER_ID"]."' and DELETE_FLAG!=1 order by RETURN_DATE desc";
}
//===========================================================================================
$cursor= exequery(TD::conn(),$query);
$BORROW_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $BORROW_COUNT++;

    $BORROW_ID=$ROW["BORROW_ID"];
    $BUSER_ID=$ROW["BUSER_ID"];
    $BOOK_NO=$ROW["BOOK_NO"];
    $BORROW_DATE=$ROW["BORROW_DATE"];
    $BORROW_REMARK=$ROW["BORROW_REMARK"];
    $RUSER_ID=$ROW["RUSER_ID"];
    $RETURN_DATE=$ROW["RETURN_DATE"];

    $BOOK_STATUS=$ROW["BOOK_STATUS"];
    $STATUS=$ROW["STATUS"];
    $RENEW_FLAG=$ROW["RENEW_FLAG"]; //������
    $query1="select * from USER where USER_ID='$BUSER_ID'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
        $USER_NAME2=$ROW["USER_NAME"];

    $query1="select * from BOOK_INFO where BOOK_NO='$BOOK_NO'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
        $BOOK_NAME=$ROW["BOOK_NAME"];

    if($BOOK_STATUS==0 && $STATUS==0)
        $DESC = _("�������");
    if($BOOK_STATUS==0 && $STATUS==1)
        $DESC = _("������׼");
    if($BOOK_STATUS==0 && $STATUS==2)
        $DESC = _("����δ׼");
    if($BOOK_STATUS==1 && $STATUS==0)
        $DESC = _("�������");
    if($BOOK_STATUS==1 && $STATUS==1)
        $DESC = _("������׼");
    if($BOOK_STATUS==1 && $STATUS==2)
        $DESC = _("����δ׼");


    if($BORROW_COUNT%2==1)
        $TableLine="TableLine1";
    else
        $TableLine="TableLine2";
?>

<tr class="<?=$TableLine?>">
  <td align="center"><?=$BOOK_NAME?></td>
  <td align="center"><?=$BOOK_NO?></td>
  <td align="center"><?=$BORROW_DATE?></td>
  <td align="center"><?=$RETURN_DATE?></td>
  <td align="center"><?=$USER_NAME2?></td>
  <td align="center"><?=$DESC?></td>     
  <td nowrap align="center">
<?
if($STATUS==0 && $BOOK_STATUS==0)  //���Ĵ���
{
?>
      <a href="javascript:delete_manage(<?=$BORROW_ID?>);"> <?=_("ɾ��")?> </a>
<?
}
if($STATUS==1 && $BOOK_STATUS==0)  //������׼
{
?>
     <a href="javascript:return_book('<?=$BORROW_ID?>');"> <?=_("����")?></a>
<?	
	if($RENEW_FLAG==0 && strtotime($RETURN_DATE) > time()) //δ�������ڹ黹���ڷ�Χ��
	{
?>   
     <a href="#" onClick="window.open('renew.php?BORROW_ID=<?=$BORROW_ID?>&BOOK_NO=<?=$BOOK_NO?>&BORROW_DATE=<?=$BORROW_DATE?>&RETURN_DATE=<?=$RETURN_DATE?>','','height=400,width=550,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=220,top=180,resizable=yes');"><?=_("����")?> </a>
<?
}	}
if($STATUS==2 && $BOOK_STATUS==0)  //����δ׼
{
?>
      <a href="javascript:delete_manage(<?=$BORROW_ID?>);"> <?=_("ɾ��")?> </a>
<?
}
if($STATUS==1 && $BOOK_STATUS==1)  //������׼
{
?>
      <a href="javascript:delete_flag(<?=$BORROW_ID?>);"> <?=_("ɾ��")?> </a>
<?
}
if($STATUS==2 && $BOOK_STATUS==1)  //����δ׼
{
?>
      <a href="javascript:return_book('<?=$BORROW_ID?>');"> <?=_("����")?></a> 
<?
}
?>
  </td>
</tr>
<?
}//while
?>
</table>
</body>

</html>
