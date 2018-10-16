<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
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
			else if(digit = "-")
			{
				 ++count;	
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
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/score.gif" align="absmiddle" width=22 height=20><span class="big3"> <?=_("考核指标集明细")?></span>
    </td>
  </tr>
</table>
<br>
<table width="60%" align="center" class="TableList">
  <tr class="TableHeader" align="center">
    <td width="50%"><?=_("考核项目")?></td>
    <td width="20%"><?=_("分值范围")?></td>
  </tr>
<?
	$query = "SELECT * from SCORE_ITEM where GROUP_ID='$GROUP_ID'";
	$cursor= exequery(TD::conn(),$query);
	while($ROW=mysql_fetch_array($cursor))
	{
	   $ITEM_ID=$ROW["ITEM_ID"];
	   $ITEM_NAME=$ROW["ITEM_NAME"];
	   $MAX=$ROW["MAX"];
	   $MIN=$ROW["MIN"];

?>
	  <tr class="TableData">
	    <td align="center"><?=$ITEM_NAME?></td>
	    <td align="center"><?=$MIN?><?=_("～")?><?=$MAX?></td>
	  </tr>
<?
	}
?>
</table>
<div align="center">
   <br>
   <input type="button" class="BigButton" value="<?=_("返回")?>" onClick="location='../index.php?CUR_PAGE=<?=$CUR_PAGE?>'">
</div>

</body>
</html>