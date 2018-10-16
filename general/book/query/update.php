<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("续借登记");
include_once("inc/header.inc.php");
?>
<body class="bodycolor">
<?
if($BORROW_ID!="")
{
    $query="SELECT RETURN_DATE,RENEW_FLAG,BOOK_NO FROM BOOK_MANAGE WHERE BORROW_ID='$BORROW_ID'";
    $cursor=exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $return_date =$ROW["RETURN_DATE"]; //初始归还时间
        $renew_flag =$ROW["RENEW_FLAG"];
        $book_no =$ROW["BOOK_NO"];
    }
    if(strtotime($return_date) < time())
    {
        Message(_("错误"),_("当前已超出还书日期，请先归还此书。"));
        Button_Close();
        exit;
    }
    if($renew_flag == 1)
    {
        Message(_("错误"),_("已续借过此书。"));
        Button_Close();
        exit;
    }
    if($BORROW_REMARK!= "")
    {//更新借书日期及备注
        $query="UPDATE BOOK_MANAGE SET RETURN_DATE='$RETURN_DATE', BORROW_REMARK='$BORROW_REMARK',RENEW_FLAG='1' WHERE BORROW_ID='$BORROW_ID' ";
    }
    else{
        $query="UPDATE BOOK_MANAGE SET RETURN_DATE='$RETURN_DATE', RENEW_FLAG='1' WHERE BORROW_ID='$BORROW_ID' ";
    }
    exequery(TD::conn(),$query);
    //取该图书所属部门的图书管理员
    $MANAGER_ID = "";
    $query = "SELECT MANAGER_ID from BOOK_MANAGER where find_in_set('$BELONG_DEPT',MANAGE_DEPT_ID) or MANAGE_DEPT_ID='ALL_DEPT'";
    $cursor= exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
        $MANAGER_ID .= $ROW["MANAGER_ID"];

    //内部事务提醒
    if($MANAGER_ID!="")
    {
        //事务提醒
        $MSG = sprintf(_("续借的图书(编号：%s)将于%s到期!"), $book_no,$RETURN_DATE);
        send_sms("",$_SESSION["LOGIN_USER_ID"],$MANAGER_ID,73,$_SESSION["LOGIN_USER_NAME"].$MSG,"/book/query/query.php?STATUS=1");
    }

    Message(_("提示"),_("续借成功"));
}else{
    Message(_("错误"),_("系统繁忙"));
    Button_Close();
    exit;
}
?>
<script>window.opener.document.location.reload();</script>
<br>
<div align="center">
    <input type="button" value="<?=_("关闭")?>" class="BigButton" onclick="window.close();">
</div>
</body>
</html>