<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
$query="select IS_SELF_ASSESSMENT from SCORE_FLOW where FLOW_ID='$FLOW_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
	$IS_SELF_ASSESSMENT=$ROW["IS_SELF_ASSESSMENT"];
	$COUNT=0;
$query="select count(*) from SCORE_SELF_DATA where FLOW_ID='$FLOW_ID' and PARTICIPANT='$USER_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
	$COUNT=$ROW[0];
if($IS_SELF_ASSESSMENT==0)
{

$HTML_PAGE_TITLE = _("����¼��");
include_once("inc/header.inc.php");
?>


<script>
	function CheckForm(id)
	{
				var id_name;
				for(i=1;i<=id;i++)
				{
						id_name="value"+i;
						var temp=document.getElementById(id_name).value;	
						if(temp=="")
						{
							alert("<?=_("¼��Ŀ��˷�������Ϊ�գ�")?>");
							document.getElementById(id_name).focus();
							return false;
						}
				}
				return true;
	}
</script>


<body class="bodycolor">

<?
 $query = "SELECT * from USER where USER_ID='$USER_ID'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
    $USER_NAME=$ROW["USER_NAME"];
 }
?>
<table border="0"  cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/salary.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("��������¼��")?>(<?=$USER_NAME?>)</span>
    </td>

  </tr>
</table>

<div align="center">

<form name=form1 method="post" action="submit.php">
<?
   $FLOW_ID =intval($FLOW_ID );
   //-- ���Ȳ�ѯ�Ƿ���¼�������--
   $query="select * from SCORE_DATE where FLOW_ID='$FLOW_ID' and RANKMAN='".$_SESSION["LOGIN_USER_ID"]."' and PARTICIPANT='$USER_ID'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
     $SCORE=$ROW["SCORE"];//--- ȡ������---
     $MEMO=$ROW["MEMO"];//--- ȡ����ע---
     $MY_SCORE=explode(",",$SCORE);
     $MY_MEMO=explode(",",$MEMO);
     $OPERATION=2; //-- ��ִ�����ݸ��� --

   }
   else
     $OPERATION=1; //-- ��ִ�����ݲ��� --

	$query="select GROUP_REFER from SCORE_GROUP where GROUP_ID='$GROUP_ID' ";
	$cursor= exequery(TD::conn(),$query);
	if($ROW=mysql_fetch_array($cursor))
	{
		$GROUP_REFER=$ROW["GROUP_REFER"];
	}

//-- ���ɿ�����Ŀ--
 $query="select * from SCORE_ITEM where GROUP_ID='$GROUP_ID' ";
 $cursor= exequery(TD::conn(),$query);
 $ITEM_COUNT=0;

 while($ROW=mysql_fetch_array($cursor))
 {
    $ITEM_COUNT++;

    $ITEM_ID=$ROW["ITEM_ID"];
    $ITEM_NAME=$ROW["ITEM_NAME"];
    $ITEM_NAME=str_replace("\n","<br/>",$ITEM_NAME);
    $MAX=$ROW["MAX"];
    $MIN=$ROW["MIN"];
    $ITEM_EXPLAIN= $ROW["ITEM_EXPLAIN"];
    $ITEM_EXPLAIN=str_replace("\n","<br/>",$ITEM_EXPLAIN);
    if($ITEM_COUNT==1)
    {
?>
    		<table width="95%" class="TableBlock">
<?
    }
?>
		<tr class="TableData">
		  <td nowrap align="left" width="30%"><?=$ITEM_NAME?>(<?=$MIN?><?=_("��")?><?=$MAX?>)<br/><?=_('��ֵ˵����').$ITEM_EXPLAIN ?></td>
		  <td nowrap align="center" width="20%">
<?
			  if($RECALL=='1')
			  {
			  		$value="value".$ITEM_COUNT;
?>
    				<input type="text" name="value<?=$ITEM_COUNT?>" id="value<?=$ITEM_COUNT?>" class="BigInputMoney" size="10" maxlength="14" value="<?=$$value?>">
<?
    	  }
		    else
		    {
?>
    				<input type="text" name="value<?=$ITEM_COUNT?>" id="value<?=$ITEM_COUNT?>" class="BigInputMoney" size="10" maxlength="14" value="<?=$MY_SCORE[$ITEM_COUNT-1]?>">
<?
    		}
?>
		    <input type="hidden" value="<?=$ITEM_NAME?>" name="<?=$ITEM_COUNT?>NAME">
		    <input type="hidden" value="<?=$MIN?>" name="<?=$ITEM_COUNT?>MIN">
		    <input type="hidden" value="<?=$MAX?>" name="<?=$ITEM_COUNT?>MAX">
		  </td>
    	<td nowrap align="center" width="40%">
<?
	    if($RECALL=='1')
	    {
	    	$MEMO_DATA=$ITEM_COUNT."MEMO";
?>
		    <!--  <input type="text" name="<?=$ITEM_COUNT?>MEMO" class="BigInputMoney" size="20" maxlength="14" value="<?=$$MEMO_DATA?>">-->
		      <textarea name="<?=$ITEM_COUNT?>MEMO" id="<?=$ITEM_COUNT?>MEMO" class="BigInputMoney" rows="2" cols="80"><?=$$MEMO_DATA ?></textarea>
<?
    	}
	    else
	    {
?>
		   <!-- <input type="text" name="<?=$ITEM_COUNT?>MEMO" class="BigInputMoney" size="20" maxlength="14" value="<?=$MY_MEMO[$ITEM_COUNT-1]?>"> -->
					<textarea name="<?=$ITEM_COUNT?>MEMO" id="<?=$ITEM_COUNT?>MEMO" class="BigInputMoney" rows="2" cols="25"><?=$MY_MEMO[$ITEM_COUNT-1]?></textarea>
<?    }
?>
    	</td>
    </tr>
<?
 }

 if($ITEM_COUNT>0)
 {
   if($GROUP_REFER!="")
   {  
?>     <tr class="TableData">
      	<td nowrap colspan="3" align="left">
<?
	      if(find_id($GROUP_REFER,"DIARY"))
	      {
?>
      		<a href="javascript:;" onClick="window.open('arrange_info/user_diary.php?USER_ID=<?=$USER_ID?>','','height=500,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=60,resizable=yes');"><?=_("�鿴")?><?=$USER_NAME?><?=_("������־")?></a>
<?
	      }
	      if(find_id($GROUP_REFER,"CALENDAR"))
	      {
?>
       		&nbsp;&nbsp;<a href="javascript:;" onClick="window.open('arrange_work/index.php?USER_ID=<?=$USER_ID?>','','height=500,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=60,resizable=yes');"><?=_("�鿴")?><?=$USER_NAME?><?=_("��������")?></a>   
<?
      	}
?>     
      	</td>    
       </tr>
<?
    }
    else
    {
?>  
			<a href="javascript:;" onClick="window.open('self_assignment.php?FLOW_ID=<?=$FLOW_ID ?>&GROUP_ID=<?=$GROUP_ID ?>&USER_ID=<?=$USER_ID ?>','','height=500,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=60,resizable=yes');"><?=_("�鿴")?><?=$USER_NAME?><?=_("����")?></a>
<?
		}
?>
    <tfoot align="center" class="TableFooter">
      <td nowrap colspan="3">
        <input type="hidden" value="<?=$OPERATION?>" name="OPERATION">
        <input type="hidden" value="<?=$USER_ID?>" name="PARTICIPANT">
        <input type="hidden" value="<?=$FLOW_ID?>" name="FLOW_ID">
        <input type="hidden" value="<?=$GROUP_ID?>" name="GROUP_ID">
        <input type="hidden" value="<?=$ITEM_COUNT?>" name="ITEM_COUNT">
        <input type="submit" value="<?=_("ȷ��")?>" class="BigButton" onClick="return CheckForm(<?=$ITEM_COUNT ?>);">&nbsp;&nbsp;&nbsp;
        <input type="button" value="<?=_("ȡ��")?>" class="BigButton" onClick="location='blank.php?FLOW_ID=<?=$FLOW_ID?>'">
      </td>
    </tfoot>

    <thead class="TableHeader">
      <td nowrap align="center"><?=_("������Ŀ")?></td>
      <td nowrap align="center"><?=_("����")?></td>
      <td nowrap align="center"><?=_("��ע")?></td>
    </thead>
    </table>
<?
 }
 else
    message("",_("��δ���忼����Ŀ����������������ϵ��"));
?>

</form>
</div>

</body>
</html>
<?
}
else
{
	if($COUNT==0)
	{

$HTML_PAGE_TITLE = _("����¼��");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
  Message("����",_("�ÿ���������Ҫ���������Ƚ���������Ȼ���ٽ��п��ˣ�"));
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
	<tr>
		<td align="center">
			<input type="button" value="<?=_("����") ?>" class="BigButton" onClick="javascript:parent.location='index.php'">
			<input type="button" value="<?=_("������������") ?>" class="BigButton" onClick="javascript:parent.location='remind.php?FLOW_ID=<?=$FLOW_ID ?>&GROUP_ID=<?=$GROUP_ID ?>&USER_ID=<?=$USER_ID ?>'">
		</td>	
	</tr>
</table>
</body>
</html>
<?
	}
	else
	{

$HTML_PAGE_TITLE = _("����¼��");
include_once("inc/header.inc.php");
?>


<script>
	function CheckForm(id)
	{
				var id_name;
				for(i=1;i<=id;i++)
				{
						id_name="value"+i;
						var temp=document.getElementById(id_name).value;	
						if(temp=="")
						{
							alert("<?=_("¼��Ŀ��˷�������Ϊ�գ�")?>");
							document.getElementById(id_name).focus();
							return false;
						}
				}
				return true;
	}
</script>


<body class="bodycolor">

<?
 $query = "SELECT * from USER where USER_ID='$USER_ID'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
    $USER_NAME=$ROW["USER_NAME"];
 }
?>
<table border="0"  cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/salary.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("��������¼��")?>(<?=$USER_NAME?>)</span>
    </td>

  </tr>
</table>

<div align="center">

<form name=form1 method="post" action="submit.php">
<?
   $FLOW_ID =intval($FLOW_ID );
   //-- ���Ȳ�ѯ�Ƿ���¼�������--
   $query="select * from SCORE_DATE where FLOW_ID='$FLOW_ID' and RANKMAN='".$_SESSION["LOGIN_USER_ID"]."' and PARTICIPANT='$USER_ID'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
     $SCORE=$ROW["SCORE"];//--- ȡ������---
     $MEMO=$ROW["MEMO"];//--- ȡ����ע---
     $MY_SCORE=explode(",",$SCORE);
     $MY_MEMO=explode(",",$MEMO);
     $OPERATION=2; //-- ��ִ�����ݸ��� --

   }
   else
     $OPERATION=1; //-- ��ִ�����ݲ��� --

	$query="select GROUP_REFER from SCORE_GROUP where GROUP_ID='$GROUP_ID' ";
	$cursor= exequery(TD::conn(),$query);
	if($ROW=mysql_fetch_array($cursor))
	{
		$GROUP_REFER=$ROW["GROUP_REFER"];
	}

//-- ���ɿ�����Ŀ--
 $query="select * from SCORE_ITEM where GROUP_ID='$GROUP_ID' ";
 $cursor= exequery(TD::conn(),$query);
 $ITEM_COUNT=0;

 while($ROW=mysql_fetch_array($cursor))
 {
    $ITEM_COUNT++;

    $ITEM_ID=$ROW["ITEM_ID"];
    $ITEM_NAME=$ROW["ITEM_NAME"];
    $ITEM_NAME=str_replace("\n","<br/>",$ITEM_NAME);
    $MAX=$ROW["MAX"];
    $MIN=$ROW["MIN"];
    $ITEM_EXPLAIN= $ROW["ITEM_EXPLAIN"];
    $ITEM_EXPLAIN=str_replace("\n","<br/>",$ITEM_EXPLAIN);
    if($ITEM_COUNT==1)
    {
?>
    		<table width="95%" class="TableBlock">
<?
    }
?>
		<tr class="TableData">
		  <td nowrap align="left" width="30%"><?=$ITEM_NAME?>(<?=$MIN?><?=_("��")?><?=$MAX?>)<br/><?=_('��ֵ˵����').$ITEM_EXPLAIN ?></td>
		  <td nowrap align="center" width="20%">
<?
			  if($RECALL=='1')
			  {
			  		$value="value".$ITEM_COUNT;
?>
    				<input type="text" name="value<?=$ITEM_COUNT?>" id="value<?=$ITEM_COUNT?>" class="BigInputMoney" size="10" maxlength="14" value="<?=$$value?>">
<?
    	  }
		    else
		    {
?>
    				<input type="text" name="value<?=$ITEM_COUNT?>" id="value<?=$ITEM_COUNT?>" class="BigInputMoney" size="10" maxlength="14" value="<?=$MY_SCORE[$ITEM_COUNT-1]?>">
<?
    		}
?>
		    <input type="hidden" value="<?=$ITEM_NAME?>" name="<?=$ITEM_COUNT?>NAME">
		    <input type="hidden" value="<?=$MIN?>" name="<?=$ITEM_COUNT?>MIN">
		    <input type="hidden" value="<?=$MAX?>" name="<?=$ITEM_COUNT?>MAX">
		  </td>
    	<td nowrap align="center" width="40%">
<?
	    if($RECALL=='1')
	    {
	    	$MEMO_DATA=$ITEM_COUNT."MEMO";
?>
		    <!--  <input type="text" name="<?=$ITEM_COUNT?>MEMO" class="BigInputMoney" size="20" maxlength="14" value="<?=$$MEMO_DATA?>">-->
		      <textarea name="<?=$ITEM_COUNT?>MEMO" id="<?=$ITEM_COUNT?>MEMO" class="BigInputMoney" rows="2" cols="25"><?=$$MEMO_DATA ?></textarea>
<?
    	}
	    else
	    {
?>
		   <!-- <input type="text" name="<?=$ITEM_COUNT?>MEMO" class="BigInputMoney" size="20" maxlength="14" value="<?=$MY_MEMO[$ITEM_COUNT-1]?>"> -->
					<textarea name="<?=$ITEM_COUNT?>MEMO" id="<?=$ITEM_COUNT?>MEMO" class="BigInputMoney" rows="2" cols="25"><?=$MY_MEMO[$ITEM_COUNT-1]?></textarea>
<?    }
?>
    	</td>
    </tr>
<?
 }

 if($ITEM_COUNT>0)
 {
   if($GROUP_REFER!="")
   {  
?>     <tr class="TableData">
      	<td nowrap colspan="3" align="left">
<?
	      if(find_id($GROUP_REFER,"DIARY"))
	      {
?>
      		<a href="javascript:;" onClick="window.open('arrange_info/user_diary.php?USER_ID=<?=$USER_ID?>','','height=500,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=60,resizable=yes');"><?=_("�鿴")?><?=$USER_NAME?><?=_("������־")?></a>
<?
	      }
	      if(find_id($GROUP_REFER,"CALENDAR"))
	      {
?>
       		&nbsp;&nbsp;<a href="javascript:;" onClick="window.open('arrange_work/index.php?USER_ID=<?=$USER_ID?>','','height=500,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=60,resizable=yes');"><?=_("�鿴")?><?=$USER_NAME?><?=_("��������")?></a>   
<?
      	}
?>      &nbsp;&nbsp;<a href="javascript:;" onClick="window.open('self_assignment.php?FLOW_ID=<?=$FLOW_ID ?>&GROUP_ID=<?=$GROUP_ID ?>&USER_ID=<?=$USER_ID ?>','','height=500,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=60,resizable=yes');"><?=_("�鿴")?><?=$USER_NAME?><?=_("����")?></a>
      	</td>    
       </tr>
<?
    }
    else
    {
?>  
			<a href="javascript:;" onClick="window.open('self_assignment.php?FLOW_ID=<?=$FLOW_ID ?>&GROUP_ID=<?=$GROUP_ID ?>&USER_ID=<?=$USER_ID ?>','','height=500,width=800,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=60,resizable=yes');"><?=_("�鿴")?><?=$USER_NAME?><?=_("����")?></a>
<?
		}
?>
    <tfoot align="center" class="TableFooter">
      <td nowrap colspan="3">
        <input type="hidden" value="<?=$OPERATION?>" name="OPERATION">
        <input type="hidden" value="<?=$USER_ID?>" name="PARTICIPANT">
        <input type="hidden" value="<?=$FLOW_ID?>" name="FLOW_ID">
        <input type="hidden" value="<?=$GROUP_ID?>" name="GROUP_ID">
        <input type="hidden" value="<?=$ITEM_COUNT?>" name="ITEM_COUNT">
        <input type="submit" value="<?=_("ȷ��")?>" class="BigButton" onClick="return CheckForm(<?=$ITEM_COUNT ?>);">&nbsp;&nbsp;&nbsp;
        <input type="button" value="<?=_("ȡ��")?>" class="BigButton" onClick="location='blank.php?FLOW_ID=<?=$FLOW_ID?>'">
      </td>
    </tfoot>

    <thead class="TableHeader">
      <td nowrap align="center"><?=_("������Ŀ")?></td>
      <td nowrap align="center"><?=_("����")?></td>
      <td nowrap align="center"><?=_("��ע")?></td>
    </thead>
    </table>
<?
 }
 else
    message("",_("��δ���忼����Ŀ����������������ϵ��"));
?>

</form>
</div>

</body>
</html>
<?		
	}
}
?>
