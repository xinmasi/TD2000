<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>



<body class="bodycolor">

<?
 //------------- ��ȡ����Χ ------------------
 $query = "SELECT POST_PRIV,POST_DEPT from USER where USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
    $POST_PRIV=$ROW["POST_PRIV"];
    $POST_DEPT=$ROW["POST_DEPT"];
 }

 if($POST_PRIV=="1")
    $DEPT_PRIV=_("ȫ��");
 else if($POST_PRIV=="2")
    $DEPT_PRIV=_("ָ������");
 else
 {
    $query1="select DEPT_NAME from DEPARTMENT where DEPT_ID='".$_SESSION["LOGIN_DEPT_ID"]."'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
       $DEPT_PRIV=$ROW["DEPT_NAME"];
 }
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/system.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=sprintf(_("������Դ - ���ֲ�ѯ"))?></span></td>
  </tr>
</table>
</body>
</html>
