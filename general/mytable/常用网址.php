<?
$MODULE_FUNC_ID="";
$MODULE_DESC=_("������ַ");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'default';

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
$MODULE_OP='<a href="#" title="'._("ȫ��").'" class="all_more" onclick="view_more(\'url\',\''._("������ַ").'\',\'/general/url.php\');">'._("ȫ��").'&nbsp;</a>';

$COUNT=0;
$query = "SELECT * from URL where URL_TYPE='' and USER='' order by URL_NO limit 0,$MAX_COUNT";
$cursor= exequery(TD::conn(),$query);
while($ROW1=mysql_fetch_array($cursor))
{
   $COUNT++;
   if($COUNT==1)
      $MODULE_BODY.='<div style="float:left;width:49%;line-height:20px;"><b>'._("������ַ").'</b><ul>';

   $URL_DESC=$ROW1["URL_DESC"];
   $URL=$ROW1["URL"];

   $MODULE_BODY.='<li><a href="'.$URL.'" target="_blank">'.$URL_DESC.'</a></li>';
}

if($COUNT>0)
   $MODULE_BODY.= "</ul></div>";

$COUNT=0;
$query = "SELECT * from URL where URL_TYPE='' and USER='".$_SESSION["LOGIN_USER_ID"]."' order by URL_NO limit 0,$MAX_COUNT";
$cursor= exequery(TD::conn(),$query);
while($ROW1=mysql_fetch_array($cursor))
{
   $COUNT++;
   if($COUNT==1)
      $MODULE_BODY.='<div style="float:right;width:49%;line-height:20px;"><b>'._("������ַ").'</b><ul>';

   $URL_DESC=$ROW1["URL_DESC"];
   $URL=$ROW1["URL"];

   $MODULE_BODY.='<li><a href="'.$URL.'" target="_blank">'.$URL_DESC.'</a></li>';
}

if($COUNT>0)
   $MODULE_BODY.= "</ul></div>";
}
?>
