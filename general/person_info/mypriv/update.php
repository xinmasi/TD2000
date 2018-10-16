<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/itask/itask.php");
include_once("inc/utility_cache.php");
//URL:webroot\general\person_info\avatar\update.php

$HTML_PAGE_TITLE = _("账户信息设置");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
if(!$EDIT_BYNAME)
{
    Message(_("错误"), _("用户名不允许修改，请联系系统管理员"));
    Button_Back();
    exit;
}
/*
if($BYNAME==$_SESSION["LOGIN_USER_ID"])
{
    Message(_("错误"),_("用户名已被占用"));
    Button_Back();
    exit;
}
*/

if(td_trim($BYNAME)!="")
{
    $query="select * from USER where UID!='".$_SESSION["LOGIN_UID"]."' and BYNAME='$BYNAME'";
    $cursor= exequery(TD::conn(),$query,true);
    if($ROW=mysql_fetch_array($cursor))
    {
        Message(_("错误"),sprintf(_("用户名 %s 已被占用"),$BYNAME));
        Button_Back();
        exit;
    }
}else{
    Message(_("错误"),sprintf(_("用户名不能为空")));
    Button_Back();
    exit;
}

$query ="update USER set BYNAME='$BYNAME' where UID='".$_SESSION["LOGIN_UID"]."'";
exequery(TD::conn(),$query);

updateUserCache($_SESSION["LOGIN_UID"]);

//Message(_("提示"),_("已保存修改"));

//------------------- 保存 -----------------------
Message(_("提示"),_("已保存修改"));
?>
<br><center><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location.href='index.php?start=<?=$start?>&IS_MAIN=1'"></center>    
</body>
</html>
