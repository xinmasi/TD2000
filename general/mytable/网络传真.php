<?
$MODULE_FUNC_ID="184";
$MODULE_DESC=_("���紫��");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'fax';

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
$MODULE_OP='<a href="/general/fax/receive/index.php" title="'._("�մ���").'"><img src="'.MYOA_STATIC_SERVER.'/static/images/new.gif"/></a>&nbsp;';
$MODULE_OP.='<a href="#" title="'._("������").'" class="email_edit" onclick="view_more(\'fax_new\',\''._("������").'\',\'/general/fax/new/index.php\');">&nbsp;</a>';


$MODULE_BODY.= "<ul>";

$FAX_NAME="";
$query="SELECT NAME from EFAX_ACCOUNT where find_in_set('".$_SESSION["LOGIN_USER_ID"]."',RECEIVE_PRIV)";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $FAX_NAME.="'".$ROW["NAME"]."',";
}
if(substr($FAX_NAME,strlen($FAX_NAME)-1,1)==",")
   $FAX_NAME=substr($FAX_NAME,0,-1);

if($FAX_NAME!="")
{
   $query = "SELECT ID,SEND_NO,RECEIVE_TIME from EFAX_RECEIVE_BOX where FAX_NAME in ($FAX_NAME) and STATE<>'-1' order by RECEIVE_TIME desc limit 0,$MAX_COUNT";
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
   $COUNT++;
   $ID=$ROW["ID"];
   $SEND_NO=$ROW["SEND_NO"];
   $RECEIVE_TIME=$ROW["RECEIVE_TIME"];   //update.php?UPDATE_STR==$ID
   
   $MODULE_BODY.='<li><u title="'._("���ͷ�����").'" style="cursor:hand"><a href="/general/fax/receive/update.php?UPDATE_STR='.$ID.'">'._("�Է����룺").$SEND_NO.'</a></u>';
   $MODULE_BODY.="&nbsp;&nbsp;(".$RECEIVE_TIME.")";

   $MODULE_BODY.='</li>';
   }

   if($COUNT==0)
      $MODULE_BODY.="<li>"._("���޴���")."</li>";
}
else
{
      $MODULE_BODY.="<li>"._("��û���մ����Ȩ�ޣ�")."</li>";
}

$MODULE_BODY.= "<ul>";
}
?>