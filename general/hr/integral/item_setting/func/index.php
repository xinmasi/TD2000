<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("����������");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script Language="JavaScript">
function delete_code(ITEM_ID,ITEM_NAME,TYPE_ID)
{
 var msg = sprintf("<?=_("ȷ��Ҫɾ�������� '%s' ��")?>", ITEM_NAME);
 if(window.confirm(msg))
 {
  URL="delete.php?ITEM_ID=" + ITEM_ID+"&TYPE_ID="+TYPE_ID;
  location=URL;
 }
}
</script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/system.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("����������")?></span>
    </td>
  </tr>
</table>

<?
 $query = "SELECT * from HR_INTEGRAL_ITEM_TYPE where TYPE_ID='$TYPE_ID'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
    $TYPE_NO =$ROW["TYPE_NO"];
    $TYPE_NAME =$ROW["TYPE_NAME"];
    $TYPE_FROM =$ROW["TYPE_FROM"];
    $TYPE_BRIEF = $ROW["TYPE_BRIEF"];
    if($TYPE_NAME == "��λ"){
        $TYPE_NAME = "ӦƸ��λ";
    }
 }

?>

<table class="TableBlock" align="center">
     <tr class="TableHeader" align="center">
      <td nowrap title="<?=$TYPE_NAME?>" colspan="2">
        &nbsp;&nbsp;<b><?=$TYPE_NAME?></b>&nbsp;&nbsp;
      </td>
     </tr>
     <?
     if($TYPE_FROM==3)
     {
     ?>
     <tr>
      <td class="TableControl" align="center" colspan="2">
        <input type="button" value="<?=_("���ӻ�����")?>" class="BigButton" onclick="location='new.php?TYPE_ID=<?=$TYPE_ID?>';">
      </td>
     </tr>
<?
	  }
   if($TYPE_NAME==_("ѧ��"))
   {
   	   $query2 = "SELECT * from HR_CODE where PARENT_NO='STAFF_HIGHEST_SCHOOL' order by CODE_ORDER";
       $cursor2= exequery(TD::conn(),$query2); 
       $item_ids="";
       while($ROW2=mysql_fetch_array($cursor2))
       {
          $CODE_NAME=$ROW2["CODE_NAME"];
          $CODE_NO=$ROW2["CODE_NO"];
         $query3 = "SELECT * from HR_INTEGRAL_ITEM where TYPE_ID='$TYPE_ID' and ITEM_NAME='$CODE_NAME'";
		$cursor3= exequery(TD::conn(),$query3);
		if($ROW3=mysql_fetch_array($cursor3))
		{
		   $ITEM_ID =$ROW3["ITEM_ID"];
		   $item_ids.=$ITEM_ID.",";
		   $ITEM_NO =$ROW3["ITEM_NO"];
		   $ITEM_NAME=$ROW3["ITEM_NAME"];
		   $ITEM_VALUE =$ROW3["ITEM_VALUE"];
		   $USED = $ROW3["USED"];
		}
		else
		{
           $query4="insert into HR_INTEGRAL_ITEM (ITEM_NO,ITEM_NAME,TYPE_ID,ITEM_BRIEF,ITEM_VALUE,CREATE_PERSON,CREATE_TIME,USED,ITEM_ORDER,WEIGHT) values ('XL$CODE_NO','$CODE_NAME','$TYPE_ID','','','','','','','')";
           exequery(TD::conn(),$query4);
           $item_ids.=mysql_insert_id().",";
         }
       }
       
       $item_ids=td_trim($item_ids);   
       if($item_ids!="")
       {
          $query_update="update HR_INTEGRAL_ITEM set USED=0 where TYPE_ID='$TYPE_ID' and ITEM_ID NOT IN ($item_ids)";
          exequery(TD::conn(),$query_update);
       }
   }
   if($TYPE_NAME==_("ְ��"))
   {
   	   $query2 = "SELECT * from HR_CODE where PARENT_NO='PRESENT_POSITION' order by CODE_ORDER";
       $cursor2= exequery(TD::conn(),$query2); 
       $item_ids="";
       while($ROW2=mysql_fetch_array($cursor2))
       {
          $CODE_NAME=$ROW2["CODE_NAME"];
          $CODE_NO=$ROW2["CODE_NO"];
         $query3 = "SELECT * from HR_INTEGRAL_ITEM where TYPE_ID='$TYPE_ID' and ITEM_NAME='$CODE_NAME'";
		$cursor3= exequery(TD::conn(),$query3);
		if($ROW3=mysql_fetch_array($cursor3))
		{
		   $ITEM_ID =$ROW3["ITEM_ID"];
		   $item_ids.=$ITEM_ID.",";
		   $ITEM_NO =$ROW3["ITEM_NO"];
		   $ITEM_NAME=$ROW3["ITEM_NAME"];
		   $ITEM_VALUE =$ROW3["ITEM_VALUE"];
		   $USED = $ROW3["USED"];
		}
		else
		{
           $query4="insert into HR_INTEGRAL_ITEM (ITEM_NO,ITEM_NAME,TYPE_ID,ITEM_BRIEF,ITEM_VALUE,CREATE_PERSON,CREATE_TIME,USED,ITEM_ORDER,WEIGHT) values ('ZC$CODE_NO','$CODE_NAME','$TYPE_ID','','','','','','','')";
           exequery(TD::conn(),$query4);
           $item_ids.=mysql_insert_id().",";
         }
       }
       $item_ids=td_trim($item_ids);
       if($item_ids!="")
       {
          $query_update="update HR_INTEGRAL_ITEM set USED=0 where TYPE_ID='$TYPE_ID' and ITEM_ID NOT IN ($item_ids)";
          exequery(TD::conn(),$query_update);
       }
   }
   if($TYPE_NAME==_("֤������"))
   {
   	   $query2 = "SELECT * from HR_CODE where PARENT_NO='HR_STAFF_LICENSE1' order by CODE_ORDER";
       $cursor2= exequery(TD::conn(),$query2);
       $item_ids=""; 
       while($ROW2=mysql_fetch_array($cursor2))
       {
          $CODE_NAME=$ROW2["CODE_NAME"];
          $CODE_NO=$ROW2["CODE_NO"];
         $query3 = "SELECT * from HR_INTEGRAL_ITEM where TYPE_ID='$TYPE_ID' and ITEM_NAME='$CODE_NAME'";
		$cursor3= exequery(TD::conn(),$query3);
		if($ROW3=mysql_fetch_array($cursor3))
		{
		   $ITEM_ID =$ROW3["ITEM_ID"];
		   $item_ids.=$ITEM_ID.",";
		   $ITEM_NO =$ROW3["ITEM_NO"];
		   $ITEM_NAME=$ROW3["ITEM_NAME"];
		   $ITEM_VALUE =$ROW3["ITEM_VALUE"];
		   $USED = $ROW3["USED"];
		}
		else
		{
           $query4="insert into HR_INTEGRAL_ITEM (ITEM_NO,ITEM_NAME,TYPE_ID,ITEM_BRIEF,ITEM_VALUE,CREATE_PERSON,CREATE_TIME,USED,ITEM_ORDER,WEIGHT) values ('ZZ$CODE_NO','$CODE_NAME','$TYPE_ID','','','','','','','')";
           exequery(TD::conn(),$query4);
           $item_ids.=mysql_insert_id().",";
         }
       }
       $item_ids=td_trim($item_ids);
       if($item_ids!="")
       {
          $query_update="update HR_INTEGRAL_ITEM set USED=0 where TYPE_ID='$TYPE_ID' and ITEM_ID NOT IN ($item_ids)";
          exequery(TD::conn(),$query_update);
       }
   }
   if($TYPE_NAME==_("��λ"))
   {
   	   $query2 = "SELECT * from HR_CODE where PARENT_NO='POOL_POSITION' order by CODE_ORDER";
       $cursor2= exequery(TD::conn(),$query2);
       $item_ids=""; 
       while($ROW2=mysql_fetch_array($cursor2))
       {
          $CODE_NAME=$ROW2["CODE_NAME"];
          $CODE_NO=$ROW2["CODE_NO"];
         $query3 = "SELECT * from HR_INTEGRAL_ITEM where TYPE_ID='$TYPE_ID' and ITEM_NAME='$CODE_NAME'";
		$cursor3= exequery(TD::conn(),$query3);
		if($ROW3=mysql_fetch_array($cursor3))
		{
		   $ITEM_ID =$ROW3["ITEM_ID"];
		   $item_ids.=$ITEM_ID.",";
		   $ITEM_NO =$ROW3["ITEM_NO"];
		   $ITEM_NAME=$ROW3["ITEM_NAME"];
		   $ITEM_VALUE =$ROW3["ITEM_VALUE"];
		   $USED = $ROW3["USED"];
		}
		else
		{
           $query4="insert into HR_INTEGRAL_ITEM (ITEM_NO,ITEM_NAME,TYPE_ID,ITEM_BRIEF,ITEM_VALUE,CREATE_PERSON,CREATE_TIME,USED,ITEM_ORDER,WEIGHT) values ('GW$CODE_NO','$CODE_NAME','$TYPE_ID','','','','','','','')";
           exequery(TD::conn(),$query4);
           $item_ids.=mysql_insert_id().",";
         }
       }
       $item_ids=td_trim($item_ids);
       if($item_ids!="")
       {
          $query_update="update HR_INTEGRAL_ITEM set USED=0 where TYPE_ID='$TYPE_ID' and ITEM_ID NOT IN ($item_ids)";
          exequery(TD::conn(),$query_update);
       }
   }
      $query1 = "SELECT * from HR_INTEGRAL_ITEM where TYPE_ID='$TYPE_ID' order by ITEM_ORDER";
      $cursor1= exequery(TD::conn(),$query1);
      while($ROW=mysql_fetch_array($cursor1))
      {
        $ITEM_ID =$ROW["ITEM_ID"];
        $ITEM_NO =$ROW["ITEM_NO"];
        $ITEM_NAME =$ROW["ITEM_NAME"];
        $ITEM_VALUE=$ROW["ITEM_VALUE"];
?>
        <tr class="TableData">
          <td nowrap title="<?=$ITEM_NAME?>" >
            &nbsp;<b><?=$ITEM_NAME?></b>&nbsp;<?=_("�ɵ�")." <b> ".$ITEM_VALUE." </b> "._("��")?>
          </td>
          <td nowrap>&nbsp;
           <a href="edit.php?ITEM_ID=<?=$ITEM_ID?>"> <?=_("�༭")?></a>&nbsp;&nbsp;
<?
if($TYPE_FROM==3)
{
?>
           <a href="javascript:delete_code(<?=$ITEM_ID?>,'<?=$ITEM_NAME?>','<?=$TYPE_ID?>');"> <?=_("ɾ��")?></a>
<?
}
?>
          </td>
        </tr>

<?
      }//while
?>
    </table>
<br>

<div align="center">
<input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="window.close();" title="<?=_("�رմ���")?>">
</div>

</body>
</html>