<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("���ֲ�ѯ");
include_once("menu_top.php");
  $query = "SELECT MAX(PRIV_NO) from USER_PRIV";
  $cursor= exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor))
     $PRIV_NO_MAX=$ROW["0"];

  $query = "SELECT USER_PRIV from USER_PRIV where PRIV_NO='$PRIV_NO_MAX'";
  $cursor= exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor))
     $USER_PRIV_MAX=$ROW["0"];
  
  if($USER_PRIV_MAX==$_SESSION["LOGIN_USER_PRIV"] && $_SESSION["LOGIN_USER_PRIV"]!=1)
  {
?>
     <body class="bodycolor">
<?
     Message(_("��ʾ"),_("���Ľ�ɫȨ��Ϊ��ͣ��޷����л��ֹ���"));
     exit;
  }
?>

