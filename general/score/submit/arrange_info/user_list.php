<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
ob_start();
?>
    <table border="0" cellspacing="1" width="100%" class="small" bgcolor="#000000" cellpadding="3" align="center">
       <tr class="TableHeader" onclick="clickMenu('1')" style="cursor:hand" title="<?=_("点击伸缩列表")?>">
         <td nowrap align="center"><b><?=_("在职人员")?></b></td>
       </tr>
    </table>
    <table border="0" cellspacing="1" width="100%" class="small" cellpadding="3" id="1">
    <tr>
      <td>
<?
$PARA_URL1="";
$PARA_URL2="/general/diary/info/user_diary.php";
$PARA_TARGET="user_diary";
$PRIV_NO_FLAG=2;
$xname="diary_info";
$showButton=0;

include_once("inc/user_list/index.php");
?>
       </td>
     </tr>
    </table>
<?
 $query = "SELECT POST_PRIV from USER where UID='".$_SESSION["LOGIN_UID"]."'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
    $POST_PRIV=$ROW["POST_PRIV"];
 if($POST_PRIV=="1")
 {
?>
    <table border="0" cellspacing="1" width="100%" class="small" bgcolor="#000000" cellpadding="3" align="center">
       <tr class="TableHeader" onclick="clickMenu('0')" style="cursor:hand" title="<?=_("点击伸缩列表")?>">
         <td nowrap align="center"><b><?=_("离职人员/外部人员")?></b></td>
       </tr>
    </table>
    <table border="0" cellspacing="1" width="100%" class="small" bgcolor="#000000" cellpadding="3" id="0" style="display:none">
<?
    $query = "SELECT * from USER,USER_PRIV where DEPT_ID=0 and USER.USER_PRIV=USER_PRIV.USER_PRIV order by PRIV_NO,USER_NO,USER_NAME";
    $cursor= exequery(TD::conn(),$query);

    while($ROW=mysql_fetch_array($cursor))
    {
       $USER_COUNT++;
       $USER_ID=$ROW["USER_ID"];
       $USER_NAME=$ROW["USER_NAME"];
       $USER_PRIV=$ROW["USER_PRIV"];

       $query1 = "SELECT * from USER_PRIV where USER_PRIV='$USER_PRIV'";
       $cursor1= exequery(TD::conn(),$query1);
       if($ROW=mysql_fetch_array($cursor1))
          $USER_PRIV=$ROW["PRIV_NAME"];

?>
    <tr class="TableData" align="center">
      <td nowrap width="80"><?=$USER_PRIV?></td>
      <td nowrap><a href="user_diary.php?USER_ID=<?=$USER_ID?>" target="user_diary"><?=$USER_NAME?></a></td>
    </tr>
<?
    }
?>
    </table>
<?
}
?>

<script language="JavaScript">
function clickMenu(ID)
{

    targetelement=document.getElementById(ID);
    if (targetelement.style.display=="none")
        targetelement.style.display='';
    else
        targetelement.style.display="none";
}
</script>
