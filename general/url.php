<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("³£ÓÃÍøÖ·");
include_once("inc/header.inc.php");
?>





<body class="bodycolor"">

<!------------------------- ÏÔÊ¾¸öÈËÍøÖ· ------------------------------------------->
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("¸öÈËÍøÖ·")?></span>
    </td>
  </tr>
</table>

<div align="center">
<?
 $query = "SELECT * from URL where URL_TYPE='' and USER='".$_SESSION["LOGIN_USER_ID"]."' order by URL_NO";
 $cursor= exequery(TD::conn(),$query);

 $URL_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $URL_COUNT++;
    $URL_ID=$ROW["URL_ID"];
    $URL_NO=$ROW["URL_NO"];
    $URL_DESC=$ROW["URL_DESC"];
    $URL=$ROW["URL"];

    if($URL_COUNT==1)
    {
?>
    <table class="TableBlock" width="450">
<?
    }
    if($URL_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
      <td nowrap align="center"><?=$URL_NO?></td>
      <td nowrap align="center"><?=$URL_DESC?></td>
      <td nowrap align="center"><A href="<?=$URL?>" target="_blank"><?=$URL?></A></td>
    </tr>
<?
 }

 if($URL_COUNT>0)
 {
?>
    <thead class="TableHeader">
      <td nowrap align="center"><?=_("ÐòºÅ")?></td>
      <td nowrap align="center"><?=_("ËµÃ÷")?></td>
      <td nowrap align="center"><?=_("ÍøÖ·")?></td>
    </thead>
    </table>
<?
 }
 else
    Message("",_("ÉÐÎ´Ìí¼ÓÍøÖ·"));
?>

<br><br>
<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>

<!-------------------------- ÏÔÊ¾¹«¹²ÍøÖ· ------------------------------------------>
</div>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("¹«¹²ÍøÖ·")?></span>
    </td>
  </tr>
</table>


<div align="center">
<?
 $query = "SELECT * from URL where URL_TYPE='' and USER='' order by URL_NO";
 $cursor= exequery(TD::conn(),$query);

 $URL_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $URL_COUNT++;
    $URL_ID=$ROW["URL_ID"];
    $URL_NO=$ROW["URL_NO"];
    $URL_DESC=$ROW["URL_DESC"];
    $URL=$ROW["URL"];

    if($URL_COUNT==1)
    {
?>

    <table class="TableBlock" width="450">

<?
    }
    if($URL_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
      <td nowrap align="center"><?=$URL_NO?></td>
      <td nowrap align="center"><?=$URL_DESC?></td>
      <td nowrap align="center"><A href="<?=$URL?>" target="_blank"><?=$URL?></A></td>
    </tr>
<?
 }

 if($URL_COUNT>0)
 {
?>
    <thead class="TableHeader">
      <td nowrap align="center"><?=_("ÐòºÅ")?></td>
      <td nowrap align="center"><?=_("ËµÃ÷")?></td>
      <td nowrap align="center"><?=_("ÍøÖ·")?></td>
    </thead>
    </table>
<?
 }
 else
    Message("",_("ÉÐÎ´Ìí¼ÓÍøÖ·"));
?>

</div>

</body>
</html>