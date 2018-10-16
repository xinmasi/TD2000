<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
$connstatus = ($connstatus) ? true : false;
?>

<script>
function CheckForm()
{
   if(document.form1.ITEM_NAME.value=="")
   { alert("<?=_("考核项目不能空！！！")?>");
   	 form1.ITEM_NAME.focus();
     return (false);
   }
    if(document.form1.MIN.value=="")
   { alert("<?=_("最小值不能空！！！")?>");
   	 form1.MIN.focus();
     return (false);
   }
    if(document.form1.MAX.value=="")
   { alert("<?=_("最大值不能空！！！")?>");
   	  form1.MAX.focus();
     return (false);
   }
   if(checknum(document.form1.MAX.value)=="0")
   { alert("<?=_("分值范围必须数字！！！")?>");
   	  form1.MAX.focus();
     return (false);
   }
     if(checknum(document.form1.MIN.value)=="0")
   { alert("<?=_("分值范围必须是数字！！！")?>");
   	 form1.MIN.focus();
     return (false);
   }

   if(Number(document.form1.MIN.value)>=Number(document.form1.MAX.value))
   { alert("<?=_("分值范围输入不正确！！！")?>");
   	 form1.MIN.focus();
     return (false);
   }
   return (true);
}

function checknum(p)
{

var l = p.length;
var count=0;
for(var i=0; i<l; i++)
{
var digit = p.charAt(i);
if(digit == "." )
{
  ++count;
  if(count>1)
  {
   return 0;
  }
  }
else if(digit < "0" || digit > "9")
  {
   return 0;
  }
  }
return 1;
}


</script>
<?
$HTML_PAGE_TITLE = _("考核指标集明细");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">

<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">

  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/score_item.gif" align="absmiddle"><span class="big3"> &nbsp;<?=_("考核指标集明细")?></span>
    </td>
  </tr>
</table>
<br>
<table border="0" width="70%" cellpadding="2" cellspacing="1" align="center" bgcolor="#000000" class="small">
  <tr class="TableHeader" align="center">
    <td width="30%"><?=_("考核项目")?></td>
    <td width="20%"><?=_("分值范围")?></td>
    <td width="20%"><?=_("操作")?></td>
  </tr>
<?
$GROUP_ID=intval($GROUP_ID);
$query = "SELECT * from SCORE_ITEM where GROUP_ID='$GROUP_ID'";
$cursor= exequery(TD::conn(),$query, $connstatus);
while($ROW=mysql_fetch_array($cursor))
{
   $ITEM_ID=$ROW["ITEM_ID"];
   $ITEM_NAME=$ROW["ITEM_NAME"];
   $MAX=$ROW["MAX"];
   $MIN=$ROW["MIN"];

?>
  <tr class="TableData">
    <form method="post" action="update.php?GROUP_ID=<?=$GROUP_ID?> & ITEM_ID=<?=$ITEM_ID?> & CUR_PAGE=<?=$CUR_PAGE?>">
    <td align="center">&nbsp;
     <INPUT type="text" name="ITEM_NAME" class=SmallInput size="20" value="<?=$ITEM_NAME?>">
    </td>

    <td align="center">
     <INPUT type="text"name="MIN" class=SmallInput size="3" value="<?=$MIN?>"><?=_("～")?>
     <INPUT type="text"name="MAX" class=SmallInput size="3" value="<?=$MAX?>">
    </td>

    <td align="center">

    <input type="submit" name="submit" value="<?=_("修改")?>" class="SmallButton">&nbsp;&nbsp;
    <input type="button" value="<?=_("删除")?>" class="SmallButton" name="button" onclick="if (confirm('<?=_("您真的要删除该条选项吗？")?>\n\n<?=_("注意：该操作不可恢复！")?>'))location.href='delete.php?ITEM_ID=<?=$ITEM_ID?>&GROUP_ID=<?=$GROUP_ID?>&CUR_PAGE=<?=$CUR_PAGE?>'">
    </td>
    </form>
  </tr>
<?
}
?>
  <tr class="TableControl">
    <form method="post" name="form1" action="add.php?GROUP_ID=<?=$GROUP_ID?>&CUR_PAGE=<?=$CUR_PAGE?>" onsubmit="return CheckForm();">
    <td colspan="4"><?=_("考核项目")?>:
    	<INPUT type="text"name="ITEM_NAME" class=SmallInput size="20">
    &nbsp;
     <?=_("分值范围")?>:
     <INPUT type="text"name="MIN" class=SmallInput size="3"><?=_("～")?>
     <INPUT type="text"name="MAX" class=SmallInput size="3">
   &nbsp;&nbsp;
     <input type="submit" name="submit" class="SmallButton" value="<?=_("添加")?>"></td>
    </form>
  </tr>
</table>

<div align="center">
   <br>
   <input type="button" class="BigButton" value="<?=_("返回")?>" onclick="location='../index.php?CUR_PAGE=<?=$CUR_PAGE?>'">
</div>
</body>
</html>