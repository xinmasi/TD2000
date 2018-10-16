<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
?>
<html>
<?
$AFF_ID=intval($AFF_ID);
  $query="select * from AFFAIR where AFF_ID='$AFF_ID'";
  $cursor= exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor))
  {
    $USER_ID=$ROW["USER_ID"];
    $TYPE=$ROW["TYPE"];
    $REMIND_DATE=$ROW["REMIND_DATE"];
    $REMIND_TIME=$ROW["REMIND_TIME"];
    $CONTENT=$ROW["CONTENT"];

    if($USER_ID!=$_SESSION["LOGIN_USER_ID"])
       exit;

    if($TYPE=="2")
       $AFF_TIME=_("每日 ").$REMIND_TIME;
    elseif($TYPE=="3")
    {
       if($REMIND_DATE=="1")
          $REMIND_DATE=_("一");
       elseif($REMIND_DATE=="2")
          $REMIND_DATE=_("二");
       elseif($REMIND_DATE=="3")
          $REMIND_DATE=_("三");
       elseif($REMIND_DATE=="4")
          $REMIND_DATE=_("四");
       elseif($REMIND_DATE=="5")
          $REMIND_DATE=_("五");
       elseif($REMIND_DATE=="6")
          $REMIND_DATE=_("六");
       elseif($REMIND_DATE=="0")
          $REMIND_DATE=_("日");
       $AFF_TIME=_("每周").$REMIND_DATE." ".$REMIND_TIME;
    }
    elseif($TYPE=="4")
       $AFF_TIME=_("每月").$REMIND_DATE._("日 ").$REMIND_TIME;
    elseif($TYPE=="5")
       $AFF_TIME=_("每年").str_replace("-",_("月"),$REMIND_DATE)._("日 ").$REMIND_TIME;
    $CONTENT=str_replace("<","&lt",$CONTENT);
    $CONTENT=str_replace(">","&gt",$CONTENT);
    $CONTENT=stripslashes($CONTENT);

    $TITLE=csubstr($CONTENT,0,10);
  }
?>

<body bgcolor="#FFFFCC" topmargin="5">

<div class="small">
<?=$AFF_TIME?>
<hr>

<?=$CONTENT?>
</div>
</body>
</html>
