<?
$SESSION_WRITE_CLOSE = 0;
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_cache.php");

$HTML_PAGE_TITLE = _("�����������");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
$BKIMAGE = $_FILES['BKIMAGE']['tmp_name'];
if($BKIMAGE != "")
{
   if(MYOA_IS_DEMO)
   {
      Message(_("��ʾ"),_("��ʾ�治���Զ��屳��ͼƬ"));
      Button_Back();
      exit;
   }
   
   $EXT_NAME = strtolower(substr($_FILES['BKIMAGE']['name'], strrpos($_FILES['BKIMAGE']['name'], ".")));
   if($EXT_NAME!=".jpg" && $EXT_NAME!=".jpeg" && $EXT_NAME!=".gif" && $EXT_NAME!=".png")
   {
      Message(_("����"),_("ͼƬ��ʽֻ����jpg��gif��png"));
      Button_Back();
      exit;
   }
   
   $PARA_ARRAY=get_sys_para("MYTABLE_BKGROUND");
   while(list($PARA_NAME, $PARA_VALUE) = each($PARA_ARRAY))
      $$PARA_NAME = $PARA_VALUE;
   
   if($MYTABLE_BKGROUND > 0 && intval($_FILES['BKIMAGE']['size']) > $MYTABLE_BKGROUND*1024)
   {
      Message(_("����"),sprintf(_("ͼƬ��С���ܳ���%sKB"),$MYTABLE_BKGROUND));
      Button_Back();
      exit;
   }
   
   $BKGROUND = "users_".$_SESSION["LOGIN_UID"].$EXT_NAME;
   if(file_exists($BKIMAGE))
   {
      $RESULT = add_attach_old($BKIMAGE, "background", "users_".$_SESSION["LOGIN_UID"].$EXT_NAME);
      if(!$RESULT)
         $BKGROUND = "0";
      unlink($BKIMAGE);
   }
}

$SOUND_SRC = $_FILES['CUSTOM_SOUND']['tmp_name'];
if($CALL_SOUND == "-1" && $SOUND_SRC != "")
{
   $RESULT = add_attach_old($SOUND_SRC, "swf", $_SESSION["LOGIN_UID"].".swf");
   if(!$RESULT)
      $CALL_SOUND = "1";
}

if(isset($THEME) && $THEME=="")
   $THEME="13";

$WEATHER_CITY = $WEATHER_CITY_NAME;

if($SHOW_WEATHER!="on")
   $WEATHER_CITY="0";
$SHOW_RSS = $SHOW_RSS=="on"?"1":"0";

//ʱ�������¿��������Ƿ���������ʾ��
if(($THEME == '10' || $THEME == '13') && $INTRO_TYPE == '1')
{
    $query_intro = "SELECT USER_PARA FROM user WHERE UID='".$_SESSION["LOGIN_UID"]."'";
    $cursor_intro = exequery(TD::conn(), $query_intro);
    if($row_intro=mysql_fetch_array($cursor_intro))
    {
        $user_para = $row_intro['USER_PARA'];
        $user_paras = unserialize($user_para);
        
        $user_paras['login_first'] = 1;
        $user_para = serialize($user_paras);
        $query_reshow = "UPDATE user set USER_PARA = '$user_para'  WHERE UID='".$_SESSION["LOGIN_UID"]."'";
        exequery(TD::conn(),$query_reshow);
    }
}

//------------------- ���� -----------------------
$query ="update USER set CALL_SOUND='$CALL_SOUND',SMS_ON='$SMS_ON',MENU_TYPE='$MENU_TYPE',PANEL='$PANEL',MENU_IMAGE='$MENU_IMAGE',WEATHER_CITY='$WEATHER_CITY',SHOW_RSS='$SHOW_RSS',MENU_EXPAND='$MENU_EXPAND'";
if(isset($THEME))
   $query .= ",THEME='$THEME'";
if($BKGROUND != "0")
   $query .= ",BKGROUND='$BKGROUND'";
$query.=" where UID='".$_SESSION["LOGIN_UID"]."'";
exequery(TD::conn(),$query);

updateUserCache($_SESSION["LOGIN_UID"]);

Message(_("��ʾ"),_("�ѱ����޸�"));

if(isset($THEME))
{
   $_SESSION['LOGIN_THEME'] = $THEME;
}
?>

<script>
parent.parent.parent.location.reload();
</script>

<div align="center">
 <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='index.php'">
</div>

</body>
</html>
