<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("����");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script>
function clear_group(GROUP_ID,GROUP_NAME)
{
  var msg = sprintf("<?=_("ȷ��Ҫ��� '%s' �������ϵ����")?>", GROUP_NAME);
  if(window.confirm(msg))
  {
     URL="clear.php?GROUP_ID=" + GROUP_ID;
     window.location=URL;
  }
}
</script>

<body class="bodycolor">
<div class="PageHeader">
   <div class="title"><?=_("����")?></div>
</div>
<table class="TableBlock" width="80%">
   <thead class="TableHeader">
      <td nowrap align="center"><?=_("��������")?></td>
      <td nowrap align="center" width="300"><?=_("����")?></td>
   </thead>
  <tr class="TableData">
    <td nowrap align="center"><?=_("Ĭ��")?></td>
    <td nowrap align="center">
<?
if($_SESSION["LOGIN_USER_PRIV"]==1 && $GROUP_ID==0)
{
?>    	
    	<a href="javascript:clear_group('<?=$GROUP_ID?>','<?=$GROUP_NAME?>');"> <?=_("���")?></a>
      <a href="import.php?GROUP_ID=<?=$GROUP_ID?>"> <?=_("����")?></a>
<?
}
?>
      <a href="export.php?GROUP_ID=0&TYPE=0" target="_blank"> <?=_("����")?>Foxmail<?=_("��ʽ")?></a>
      <a href="export.php?GROUP_ID=0&TYPE=1" target="_blank"> <?=_("����")?>OutLook<?=_("��ʽ")?></a>
      <a href="print.php?GROUP_ID=0" target="_blank"> <?=_("��ӡ")?></a>
    </td>
  </tr>
<?

 //============================ ������� =======================================
 $query = "SELECT * from ADDRESS_GROUP where USER_ID='' order by ORDER_NO asc,GROUP_NAME asc";
 $cursor= exequery(TD::conn(),$query);

 while($ROW=mysql_fetch_array($cursor))
 {
    $PRIV_DEPT=$ROW["PRIV_DEPT"];
	  $PRIV_ROLE=$ROW["PRIV_ROLE"];
	  $PRIV_USER=$ROW["PRIV_USER"];
	  if($PRIV_DEPT!="ALL_DEPT")
	  	 if(!find_id($PRIV_DEPT,$_SESSION["LOGIN_DEPT_ID"]) and !find_id($PRIV_ROLE,$_SESSION["LOGIN_USER_PRIV"]) and !find_id($PRIV_USER,$_SESSION["LOGIN_USER_ID"]) and !check_id($PRIV_ROLE,$_SESSION["LOGIN_USER_PRIV_OTHER"],true)!="" and !check_id($PRIV_DEPT,$_SESSION["LOGIN_DEPT_ID_OTHER"],true)!="")
	        continue;

    $GROUP_ID=$ROW["GROUP_ID"];
    $GROUP_NAME=$ROW["GROUP_NAME"];
    
    //������û��ά��Ȩ��
    $PRIV_FLAG = 0;
    if($_SESSION["LOGIN_USER_PRIV"]!=1)
      $query1 = "select GROUP_ID,GROUP_NAME from ADDRESS_GROUP where GROUP_ID='$GROUP_ID' and (find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',SUPPORT_DEPT) or SUPPORT_DEPT='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',SUPPORT_USER))";
    else
      $query1 = "select GROUP_ID,GROUP_NAME from ADDRESS_GROUP where GROUP_ID='$GROUP_ID'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW1=mysql_fetch_array($cursor1))
       $PRIV_FLAG = 1;

?>
    <tr class="TableData">
      <td nowrap align="center"><?=$GROUP_NAME?></td>
      <td nowrap align="center">
<?
    if($PRIV_FLAG==1)
    {
?>        	
    	    <a href="javascript:clear_group('<?=$GROUP_ID?>','<?=$GROUP_NAME?>');"> <?=_("���")?></a>
          <a href="import.php?GROUP_ID=<?=$GROUP_ID?>"> <?=_("����")?></a>
<?
    }
?>          
          <a href="export.php?GROUP_ID=<?=$GROUP_ID?>&TYPE=0" target="_blank"> <?=_("����")?>Foxmail<?=_("��ʽ")?></a>
          <a href="export.php?GROUP_ID=<?=$GROUP_ID?>&TYPE=1" target="_blank"> <?=_("����")?>OutLook<?=_("��ʽ")?></a>
          <a href="print.php?GROUP_ID=<?=$GROUP_ID?>" target="_blank"> <?=_("��ӡ")?></a>
      </td>
    </tr>
<?
  }
?>
</table>

</body>
</html>
