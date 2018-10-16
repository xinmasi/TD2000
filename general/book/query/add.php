<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("����Ǽ�");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
if($BORROW_DATE!="")
{
    $TIME_OK=is_date($BORROW_DATE);

    if(!$TIME_OK)
    {
        Message(_("����"),_("�������ڸ�ʽ���ԣ�Ӧ���� 1999-1-2"));
        ?>
        <br>
        <div align="center"><input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='new.php?TO_ID=<?=$TO_ID?>&BOOK_NO=<?=$BOOK_NO?>&BORROW_DATE=<?=$BORROW_DATE?>&RETURN_DATE=<?=$RETURN_DATE?>&BORROW_REMARK=<?=$BORROW_REMARK?>'">
        </div>

        <?
        exit;
    }
}

if($RETURN_DATE!="")
{
    $TIME_OK=is_date($RETURN_DATE);

    if(!$TIME_OK)
    {
        Message(_("����"),_("�������ڸ�ʽ���ԣ�Ӧ���� 1999-1-2"));
        ?>
        <br>
        <div align="center"><input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='new.php?TO_ID=<?=$TO_ID?>&BOOK_NO=<?=$BOOK_NO?>&BORROW_DATE=<?=$BORROW_DATE?>&RETURN_DATE=<?=$RETURN_DATE?>&BORROW_REMARK=<?=$BORROW_REMARK?>'">
        </div>
        <?
        exit;
    }
}

if($BORROW_DATE!="" && $RETURN_DATE!="" && compare_date($RETURN_DATE,$BORROW_DATE)<=0)
{
    Message(_("����"),_("�黹���ڲ���С�ڽ������ڣ�"));
    Button_Back();
    exit;
}

//�Ƿ���Ҫ�����
$query = "SELECT * from BOOK_INFO where BOOK_NO='$BOOK_NO'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $LEND=$ROW["LEND"];
    $AMT=$ROW["AMT"];
    $BELONG_DEPT = $DEPT=$ROW["DEPT"];
    $OPEN=$ROW["OPEN"];
}
else
{
    Message(_("��ʾ"),_("�����ĵ�ͼ�鲻����"));
    ?>
    <br>
    <div align="center"><input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='new.php?TO_ID=<?=$TO_ID?>&BOOK_NO=<?=$BOOK_NO?>&BORROW_DATE=<?=$BORROW_DATE?>&RETURN_DATE=<?=$RETURN_DATE?>&BORROW_REMARK=<?=$BORROW_REMARK?>'">
    </div>
    <?
    exit;
}

//�Ƿ���Ȩ��
$query = "SELECT DEPT_ID from USER where USER_ID='$TO_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $DEPT_ID=$ROW["DEPT_ID"];

$OPEN_ARR=explode(";", $OPEN);

//if($OPEN=="1")
//$OPEN="ALL_DEPT";

if (!find_id($OPEN_ARR[0], $_SESSION["LOGIN_DEPT_ID"]) && !find_id($OPEN_ARR[1], $_SESSION["LOGIN_USER_ID"]) && !find_id($OPEN_ARR[2], $_SESSION["LOGIN_USER_PRIV"]) && $OPEN_ARR[0]!="ALL_DEPT")

//if(($OPEN=="0" && $DEPT!=$DEPT_ID) or ($OPEN!="ALL_DEPT" && !strpos($OPEN,",")) or (!find_id($OPEN,$DEPT_ID) && $OPEN!="ALL_DEPT"))
{
    Message(_("��ʾ"),_("����Ȩ���ı���"));
    ?>
    <br>
    <div align="center"><input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='new.php?TO_ID=<?=$TO_ID?>&BOOK_NO=<?=$BOOK_NO?>&BORROW_DATE=<?=$BORROW_DATE?>&RETURN_DATE=<?=$RETURN_DATE?>&BORROW_REMARK=<?=$BORROW_REMARK?>'">
    </div>
    <?
    exit;
}

$query = "SELECT count(*) from BOOK_MANAGE where BOOK_NO='$BOOK_NO' and ((BOOK_STATUS='0' and STATUS='0') or (BOOK_STATUS='0' and STATUS='1') or (BOOK_STATUS='1' and STATUS='0'))";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $LEND_COUNT=$ROW[0];

//�Ƿ�����Ѿ����
if($LEND==1 || $LEND_COUNT>=$AMT)
{
    Message(_("��ʾ"),_("��ͼ���Ѿ����"));
    ?>
    <br>
    <div align="center"><input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='new.php?TO_ID=<?=$TO_ID?>&BOOK_NO=<?=$BOOK_NO?>&BORROW_DATE=<?=$BORROW_DATE?>&RETURN_DATE=<?=$RETURN_DATE?>&BORROW_REMARK=<?=$BORROW_REMARK?>'">
    </div>
    <?
    exit;
}

$query="INSERT into BOOK_MANAGE(BUSER_ID,BOOK_NO,BORROW_DATE,BORROW_REMARK,RETURN_DATE,STATUS,REG_FLAG) values ('$TO_ID','$BOOK_NO','$BORROW_DATE','$BORROW_REMARK','$RETURN_DATE','0','0')";
exequery(TD::conn(),$query);
//$ROW_ID=mysql_insert_id();

//ȡ��ͼ���������ŵ�ͼ�����Ա
$MANAGER_ID = "";
$query = "SELECT MANAGER_ID from BOOK_MANAGER where find_in_set('$BELONG_DEPT',MANAGE_DEPT_ID) or MANAGE_DEPT_ID='ALL_DEPT'";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
    $MANAGER_ID .= $ROW["MANAGER_ID"];

//�ڲ���������
if($MANAGER_ID!="")
    send_sms("",$_SESSION["LOGIN_USER_ID"],$MANAGER_ID,73,$_SESSION["LOGIN_USER_NAME"]._("�ύ�˽������룬��������"),"book/borrow_manage/borrow/");

Message(_("��ʾ"),_("����ɹ�"));
?>
<br>
<div align="center">
    <input type="button" value="<?=_("�ر�")?>" class="BigButton" onclick="javascript:window.close();">
</div>
</body>
</html>
