<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("员工离职信息");
include_once("inc/header.inc.php");

$LEAVE_PERSON_ID_ZHI=GetUidByUserID($_POST["LEAVE_PERSON"]);
$LEAVE_PERSON_ID_ZHI1=  trim($LEAVE_PERSON_ID_ZHI,',');
$query="select * from im_group where GROUP_CREATOR='$LEAVE_PERSON_ID_ZHI1'";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    if($ROW["GROUP_UID"] =="")
    {
        continue;
    }
    $GROUP_ID=$ROW["GROUP_ID"];
    $namezhi="value".$ROW["GROUP_ID"];
    $namezhiid=$_POST[$namezhi];
    $query1="update im_group  set GROUP_CREATOR='$namezhiid' where GROUP_ID='$GROUP_ID'";
    exequery(TD::conn(),$query1);
}

$query="select * from im_discuss_group where DISC_GROUP_CREATOR='$LEAVE_PERSON_ID_ZHI1'";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    if($ROW["DISC_GROUP_UID"] =="")
    {
        continue;
    }
    $GROUP_ID=$ROW["DISC_GROUP_ID"];
    $namezhi="value_dis".$ROW["DISC_GROUP_ID"];
    $namezhiid=$_POST[$namezhi];
    $query1="update im_discuss_group  set DISC_GROUP_CREATOR='$namezhiid' where DISC_GROUP_ID='$GROUP_ID'";
    exequery(TD::conn(),$query1);
}
Message("",_("用户").$_POST["LEAVE_PERSON"]._("离职已办理完毕!"));
?>
<center>
    <input type="button" value="<?=_("返回")?>" onclick="location='new.php'" class="BigButton">
</center>