<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("借书登记");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
if($BORROW_DATE!="")
{
    $TIME_OK=is_date($BORROW_DATE);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("借书日期格式不对，应形如 1999-1-2"));
        ?>
        <br>
        <div align="center"><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='new.php?TO_ID=<?=$TO_ID?>&BOOK_NO=<?=$BOOK_NO?>&BORROW_DATE=<?=$BORROW_DATE?>&RETURN_DATE=<?=$RETURN_DATE?>&BORROW_REMARK=<?=$BORROW_REMARK?>'">
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
        Message(_("错误"),_("还书日期格式不对，应形如 1999-1-2"));
        ?>
        <br>
        <div align="center"><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='new.php?TO_ID=<?=$TO_ID?>&BOOK_NO=<?=$BOOK_NO?>&BORROW_DATE=<?=$BORROW_DATE?>&RETURN_DATE=<?=$RETURN_DATE?>&BORROW_REMARK=<?=$BORROW_REMARK?>'">
        </div>
        <?
        exit;
    }
}

if($BORROW_DATE!="" && $RETURN_DATE!="" && compare_date($RETURN_DATE,$BORROW_DATE)<=0)
{
    Message(_("错误"),_("归还日期不能小于借书日期！"));
    Button_Back();
    exit;
}

//是否有要借的书
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
    Message(_("提示"),_("所借阅的图书不存在"));
    ?>
    <br>
    <div align="center"><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='new.php?TO_ID=<?=$TO_ID?>&BOOK_NO=<?=$BOOK_NO?>&BORROW_DATE=<?=$BORROW_DATE?>&RETURN_DATE=<?=$RETURN_DATE?>&BORROW_REMARK=<?=$BORROW_REMARK?>'">
    </div>
    <?
    exit;
}

//是否有权借
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
    Message(_("提示"),_("你无权借阅本书"));
    ?>
    <br>
    <div align="center"><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='new.php?TO_ID=<?=$TO_ID?>&BOOK_NO=<?=$BOOK_NO?>&BORROW_DATE=<?=$BORROW_DATE?>&RETURN_DATE=<?=$RETURN_DATE?>&BORROW_REMARK=<?=$BORROW_REMARK?>'">
    </div>
    <?
    exit;
}

$query = "SELECT count(*) from BOOK_MANAGE where BOOK_NO='$BOOK_NO' and ((BOOK_STATUS='0' and STATUS='0') or (BOOK_STATUS='0' and STATUS='1') or (BOOK_STATUS='1' and STATUS='0'))";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $LEND_COUNT=$ROW[0];

//是否该书已经借出
if($LEND==1 || $LEND_COUNT>=$AMT)
{
    Message(_("提示"),_("该图书已经借出"));
    ?>
    <br>
    <div align="center"><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='new.php?TO_ID=<?=$TO_ID?>&BOOK_NO=<?=$BOOK_NO?>&BORROW_DATE=<?=$BORROW_DATE?>&RETURN_DATE=<?=$RETURN_DATE?>&BORROW_REMARK=<?=$BORROW_REMARK?>'">
    </div>
    <?
    exit;
}

$query="INSERT into BOOK_MANAGE(BUSER_ID,BOOK_NO,BORROW_DATE,BORROW_REMARK,RETURN_DATE,STATUS,REG_FLAG) values ('$TO_ID','$BOOK_NO','$BORROW_DATE','$BORROW_REMARK','$RETURN_DATE','0','0')";
exequery(TD::conn(),$query);
//$ROW_ID=mysql_insert_id();

//取该图书所属部门的图书管理员
$MANAGER_ID = "";
$query = "SELECT MANAGER_ID from BOOK_MANAGER where find_in_set('$BELONG_DEPT',MANAGE_DEPT_ID) or MANAGE_DEPT_ID='ALL_DEPT'";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
    $MANAGER_ID .= $ROW["MANAGER_ID"];

//内部事务提醒
if($MANAGER_ID!="")
    send_sms("",$_SESSION["LOGIN_USER_ID"],$MANAGER_ID,73,$_SESSION["LOGIN_USER_NAME"]._("提交了借书申请，请审批。"),"book/borrow_manage/borrow/");

Message(_("提示"),_("保存成功"));
?>
<br>
<div align="center">
    <input type="button" value="<?=_("关闭")?>" class="BigButton" onclick="javascript:window.close();">
</div>
</body>
</html>
