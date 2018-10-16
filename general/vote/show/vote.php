<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("投票");
include_once("inc/header.inc.php");
?>


<body class="bodycolor">
<?
//验证是否有子投票项无投票信息
$query = "SELECT * from VOTE_TITLE where PARENT_ID='$VOTE_ID' order by VOTE_NO,SEND_TIME";
$cursor2= exequery(TD::conn(),$query);
while($ROW2=mysql_fetch_array($cursor2))
{
    $VOTE_ID1   = $ROW2["VOTE_ID"];
    $TYPE       = $ROW2["TYPE"];

    if($TYPE=="0" || $TYPE=="1")
    {
        $query = "SELECT ITEM_ID from VOTE_ITEM where VOTE_ID='$VOTE_ID1' order by ITEM_ID";
        $cursor= exequery(TD::conn(),$query);
        if(!mysql_affected_rows()>0)
        {
            Message(_("错误"),_("您有子投票项未设置投票选项"));
            Button_Back();
            exit;
        }
    }
}

$query = "select READERS,VIEW_PRIV from VOTE_TITLE  where VOTE_ID='$VOTE_ID' and PUBLISH='1' and (TO_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID)".dept_other_sql("TO_ID")." or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID) or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID)".priv_other_sql("PRIV_ID").")";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $READERS=$ROW["READERS"];
   $VIEW_PRIV=$ROW["VIEW_PRIV"];
}
else
   exit;

if(!find_id($READERS,$_SESSION["LOGIN_USER_ID"]))
{
   $READERS.=$_SESSION["LOGIN_USER_ID"].",";
   $query = "update VOTE_TITLE set READERS='$READERS'  where VOTE_ID='$VOTE_ID'";
   exequery(TD::conn(),$query);
}
else
{
   Message(_("错误"),_("您已经进行过投票"));
   Button_Back();
   exit;
}

$TOK=strtok($ITEM_ID,",");
while($TOK!="")
{
   $query = "select VOTE_USER from VOTE_ITEM  where ITEM_ID='$TOK'";
   $cursor=exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $VOTE_USER=$ROW["VOTE_USER"];
   if($ANONYMITY=="1"||find_id($VOTE_USER,$_SESSION["LOGIN_USER_ID"]))
      $query = "update VOTE_ITEM set VOTE_COUNT=VOTE_COUNT+1 where ITEM_ID='$TOK'";
   else
      $query = "update VOTE_ITEM set VOTE_COUNT=VOTE_COUNT+1,VOTE_USER='$VOTE_USER".$_SESSION["LOGIN_USER_ID"].",' where ITEM_ID='$TOK'";
   exequery(TD::conn(),$query);
   $TOK=strtok(",");
}

while (list($key, $value) = each($_POST))
{
   $ARRAY=explode("_", substr($key, 10));
   if(substr($key, 0, 10)!="VOTE_DATA_" || !find_id($ITEM_ID, $ARRAY[0])&&$ARRAY[1]!=0)
      continue;
   
   $query = "insert into VOTE_DATA (USER_ID, ITEM_ID, FIELD_NAME, FIELD_DATA) values('".$_SESSION["LOGIN_USER_ID"]."', '$ARRAY[0]', '$ARRAY[1]', '$value')";
   exequery(TD::conn(),$query);
}
Message("",_("投票完成"));
?>
<? //if($FROM!=1){?>
  <!--  <script> opener.location.reload();</script>-->
<? //}

if($VIEW_PRIV!="2")
{
?>
<center><input type="button" class="BigButton" value="<?=_("返回")?>" onclick="location='show_reader.php?VOTE_ID=<?=$VOTE_ID?>&IS_MAIN=1';"></center>
<?
}
else
{
?>
<center><input type="button" class="BigButton" value="<?=_("关闭")?>" onclick="window.close();"></center>
<?
}
?>