<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_cache.php");

$HTML_PAGE_TITLE = _("编辑个人资料");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
$BIRTHDAY=$BIR_YEAR."-".$BIR_MON."-".$BIR_DAY;
if($MOBIL_NO_HIDDEN=="on")
    $MOBIL_NO_HIDDEN="1";
else
    $MOBIL_NO_HIDDEN="0";

if($IS_LUNAR=="on")
    $IS_LUNAR="1";
else
    $IS_LUNAR="0";

$SEX_OLD = GetUserInfoByUID($_SESSION["LOGIN_UID"], "SEX");
if($SEX_OLD != $SEX)
{
    set_sys_para(array("ORG_UPDATE" => date("Y-m-d H:i:s")));
    
    //更新SESSION中头像信息
    session_start();
    $_SESSION["LOGIN_AVATAR"] = $SEX;
}

//------------------- 保存 -----------------------
$query ="update USER set SEX='$SEX',BIRTHDAY='$BIRTHDAY',IS_LUNAR='$IS_LUNAR',";
$query.="TEL_NO_DEPT='$TEL_NO_DEPT',FAX_NO_DEPT='$FAX_NO_DEPT',ADD_HOME='$ADD_HOME',";
$query.="POST_NO_HOME='$POST_NO_HOME',TEL_NO_HOME='$TEL_NO_HOME',MOBIL_NO='$MOBIL_NO',BP_NO='$BP_NO',EMAIL='$EMAIL',OICQ_NO='$OICQ_NO',ICQ_NO='$ICQ_NO',MSN='$MSN',MOBIL_NO_HIDDEN='$MOBIL_NO_HIDDEN' ";
$query.="where UID='".$_SESSION["LOGIN_UID"]."'";
exequery(TD::conn(),$query);

$query="update USER set AVATAR='$SEX' where UID='".$_SESSION["LOGIN_UID"]."' and (AVATAR='0' || AVATAR='1')";
exequery(TD::conn(),$query);

$query2="update HR_STAFF_INFO set STAFF_SEX='$SEX',BLOOD_TYPE='$BLOOD_TYPE',STAFF_BIRTH='$BIRTHDAY',IS_LUNAR='$IS_LUNAR' where USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
exequery(TD::conn(),$query2);

updateUserCache($_SESSION["LOGIN_UID"],true);

Message(_("提示"),_("已保存修改"));
?>

<div align="center">
    <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php?IS_MAIN=1'">
</div>

</body>
<script>
    parent.document.getElementById("TEL_NO_DEPT").value="<?=$TEL_NO_DEPT?>";
    parent.document.getElementById("MOBIL_NO").value="<?=$MOBIL_NO?>";
    parent.document.getElementById("EMAIL").value = "<?=$EMAIL?>";
</script>    
</html>