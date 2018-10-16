<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_cache.php");

$HTML_PAGE_TITLE = _("公告通知发布情况");
include_once("inc/header.inc.php");
?>



<?
 $query = "SELECT TO_ID,PRIV_ID,USER_ID from NOTIFY where NOTIFY_ID='$NOTIFY_ID'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
     $TO_ID=$ROW["TO_ID"];
     $PRIV_ID=$ROW["PRIV_ID"];
     $USER_ID=$ROW["USER_ID"];
 }
 
 $TO_NAME="";
 if($TO_ID=="ALL_DEPT")
    $TO_NAME=_("全体部门");
 else
    $TO_NAME=GetDeptNameById($TO_ID);
 
 $PRIV_NAME=GetPrivNameById($PRIV_ID);

  if($USER_ID!="")
      $USER_UID=UserId2Uid($USER_ID);
   if($USER_UID!="")
   {
   	 $USER_NAME=GetUserInfoByUID($USER_UID,"USER_NAME");
   }

?>
<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/notify.gif" align="absmiddle"><span class="big3"> <?=_("发布情况")?></span>
    </td>
    </tr>
</table>

<?
if($TO_ID==""&&$PRIV_ID==""&&$USER_ID=="")
{
   Message("",_("无发布范围。"));
}
else
{
   ?>
   <table class="TableBlock" width="100%" align="center">
    <tr>
      <td nowrap class="TableData" width="130"> <font color="#0000FF"><b><?=_("发布范围(部门)：")?></b></font></td>
      <td class="TableData"><?=$TO_NAME?></td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="130"> <font color="#0000FF"><b><?=_("发布范围(人员)：")?></b></font></td>
      <td class="TableData"><?=$USER_NAME?></td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="130"> <font color="#0000FF"><b><?=_("发布范围(角色)：")?></b></font></td>
      <td class="TableData"><?=$PRIV_NAME?></td>
    </tr>
   </table>
  <?
} 
?>
<br>
<center><input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close();"></center>
</body>
</html>