<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("���ֽ��");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("func.func.php");
$WHERE_STR = "";
$USER_ID=$_SESSION["LOGIN_USER_ID"];
	
if($SELECT_NAME=="")
	$SELECT_NAME=3;
	
$ITEMS_IN_PAGE=10;
if(!isset($start) || $start=="")
	$start=0;
?>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<body class="bodycolor" >
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" WIDTH="22" HEIGHT="20" align="absmiddle">
    	<span class="big3"> <?=_("��ѯ����")?></span>
    </td>
  </tr> 
</table>
<table class="TableBlock" width="100%" align="center">
  <form enctype="multipart/form-data" action="index.php"  method="get" name="form1">
    <tr>
      <td nowrap class="TableData" align="center"> <?=_("�������ͣ�")?></td>
      <td class="TableData">
      <select id = "select_id" name="SELECT_NAME" class="BigSelect">
      	<?
      		$SELECT0 = "";$SELECT1 = "";$SELECT2 = "";$SELECT3 = "";
      		if($SELECT_NAME == '0' ){$SELECT0 = "selected";$SELECT1 = "";$SELECT2 = "";$SELECT3 = "";}
      		if($SELECT_NAME == '1'){$SELECT1 = "selected";$SELECT0 = "";$SELECT2 = "";$SELECT3 = "";}
      		if($SELECT_NAME == '3'){$SELECT3 = "selected";$SELECT1 = "";$SELECT2 = "";$SELECT0 = "";}
      	?>
        <option value="1" <?=$SELECT1?>><?=_("OA������Ŀ")?></option>
        <option value="3" <?=$SELECT3?>><?=_("�Զ��������¼��")?></option>
        <option value="0" <?=$SELECT0?>><?=_("δ���������¼��")?></option>
      </select>
      </td>
      <td nowrap class="TableData" align="center" > <?=_("����ʱ��:")?></td>
      <td class="TableData" align="center" nowrap>
      	<input type="text" name="USER_ID" size="12" style = "display:none" value = "<?=$USER_ID?>"/>
      	<input type="text" name="DEPT_ID" size="12" style = "display:none" value = "<?=$DEPT_ID?>"/>
        <?=_("��")?>&nbsp;<input type="text" name="begin" size="12" maxlength="10" class="BigInput" id="start_time" value="<?=$begin?>" onClick="WdatePicker()" /> <?=_("��")?>&nbsp;
        <input type="text" name="end" size="12" maxlength="10" class="BigInput" value="<?=$end?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})" />
      </td>
    </tr>
    <!--
    <tr id = "none_tr" style = "display:none">
      <td nowrap class="TableData" width="10%"  align="center" ><?=_("OAʹ����")?></td>
      <td nowrap class="TableData" width="10%"  align="center" colspan="3">
	<input type="checkbox" name="DO_LIST[]" id="email" value="email" />&nbsp; <label for="email" ><?=_("�ʼ�ģ��")?></label>
	<input type="checkbox" name="DO_LIST[]" id="workflow" value="workflow" />&nbsp; <label for="workflow" ><?=_("������ģ��")?></label>
	<input type="checkbox" name="DO_LIST[]" id="attend" value="attend" />&nbsp; <label for="attend" ><?=_("����")?></label>
	<input type="checkbox" name="DO_LIST[]" id="calendar" value="calendar" />&nbsp; <label for="calendar" ><?=_("�ճ̰���")?></label>
	<input type="checkbox" name="DO_LIST[]" id="diary" value="diary" />&nbsp; <label for="diary" ><?=_("������־")?></label>
	<input type="checkbox" name="DO_LIST[]" id="project" value="project" />&nbsp; <label for="project" ><?=_("��Ŀ����")?></label>
	<input type="checkbox" name="DO_LIST[]" id="news" value="news" />&nbsp; <label for="news" ><?=_("���Ź���")?></label>
	<input type="checkbox" name="DO_LIST[]" id="knowledge" value="knowledge" />&nbsp; <label for="knowledge" ><?=_("֪ʶ��")?></label>
	<input type="checkbox" name="DO_LIST[]" id="hrms" value="hrms" />&nbsp; <label for="hrms" ><?=_("���µ���")?></label>
      </td>
    </tr>
    -->
    <tr align="center" class="TableControl">
      <td colspan="8" nowrap>
        <input type="submit" value="<?=_("��ѯ")?>" class="BigButton">&nbsp;&nbsp;
      </td>
    </tr>
  </table><Br/>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle">
    	<span class="big3"><?=trim(getUserNameById($USER_ID),",")?>-<?=_("������ϸ")?></span>
    	<span class="big3" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=_("�����ܻ��֣�")?><?=_(getUser_SumPoint($USER_ID))?></span>
    	<span class="big3" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? 
    		$Rank = getUser_Rank($USER_ID);
    		if($Rank <=3 && $Rank >=1){
    			if($Rank == 1)$Rank = _("�� һ ��");	
    			if($Rank == 2)$Rank = _("�� �� ��");
    			if($Rank == 3)$Rank = _("�� �� ��");
    			print_r(sprintf(_("�������У�%s"), '<span class="big4">&nbsp;'.$Rank.'</span>&nbsp;'));
    		}
    		else if($Rank == -1)
    			print_r('<span>&nbsp;�������У������������� </span>&nbsp;');
    		else
    			print_r('<span>&nbsp;�������У��� '.$Rank.' �� </span>&nbsp;');
    		?></span>
    </td>
  </tr>
</table>
<?

	if($SELECT_NAME==3 || $SELECT_NAME==0)
	{
		if($begin!="")
			$WHERE_STR.=" and  ia.INTEGRAL_TIME >= '$begin'";
		if($end!="")
			$WHERE_STR.=" and  ia.INTEGRAL_TIME <= '$end'";
		$WHERE_STR.= " and INTEGRAL_TYPE='$SELECT_NAME' ";
		if(find_dept_id($USER_ID) != '0'){//��ְ
			$query = "SELECT count(*) from HR_INTEGRAL_DATA as ia left join USER as u on ia.USER_ID = u.USER_ID left join DEPARTMENT as d on d.DEPT_ID = u.DEPT_ID left join USER_PRIV as up on up.USER_PRIV = u.USER_PRIV where 1=1 and ia.USER_ID = '".$USER_ID."' ".$WHERE_STR." ORDER BY u.USER_PRIV ";
		}
		if(find_dept_id($USER_ID) == '0'){//��ְ
			$query = "SELECT count(*) from HR_INTEGRAL_DATA as ia left join USER as u on u.USER_ID = ia.USER_ID left join USER_PRIV as up on up.USER_PRIV = u.USER_PRIV where 1=1 and ia.USER_ID = '".$USER_ID."' ".$WHERE_STR." ORDER BY u.USER_PRIV ";
		}

		 $cursor= exequery(TD::conn(),$query);
		 $USER_TOTAL=0;
		 if($ROW=mysql_fetch_array($cursor))
		  $USER_TOTAL=$ROW[0];
	?>
	<table border="0" cellspacing="0" width="95%" class="small" cellpadding="0" align="center">
	   <tr>
	      <td valign="bottom" class="small1"><?=sprintf(_("��%s����¼"), '<span class="big4">&nbsp;'.$USER_TOTAL.'</span>&nbsp;')?></td>
	      <td align="right" valign="bottom" class="small1"><?=page_bar($start,$USER_TOTAL,$ITEMS_IN_PAGE)?></td>
	   </tr>
	</table>
	<?
		if(find_dept_id($USER_ID) != '0'){//��ְ
	 	$query = "SELECT u.USER_NAME,u.DEPT_ID,u.USER_PRIV,u.NOT_LOGIN,u.SEX,ia.* from HR_INTEGRAL_DATA as ia left join USER as u on ia.USER_ID = u.USER_ID left join DEPARTMENT as d on d.DEPT_ID = u.DEPT_ID left join USER_PRIV as up on up.USER_PRIV = u.USER_PRIV where 1=1 and ia.USER_ID = '".$USER_ID."' ".$WHERE_STR." ORDER BY ia.CREATE_TIME DESC". " LIMIT " . $start . " ," . $ITEMS_IN_PAGE;
	 	}
	 	if(find_dept_id($USER_ID) == '0'){//��ְ
	 		$query = "SELECT u.USER_NAME,u.DEPT_ID,u.USER_PRIV,u.NOT_LOGIN,u.SEX,ia.* from HR_INTEGRAL_DATA as ia left join USER as u on u.USER_ID = ia.USER_ID left join USER_PRIV as up on up.USER_PRIV = u.USER_PRIV where 1=1 and ia.USER_ID = '".$USER_ID."' ".$WHERE_STR." ORDER BY ia.CREATE_TIME DESC". " LIMIT " . $start . " ," . $ITEMS_IN_PAGE;
	 	}
	 $cursor= exequery(TD::conn(),$query);
	 $USER_COUNT=0;
	 while($ROW=mysql_fetch_array($cursor))
	 {
	    $USER_NAME=$ROW["USER_NAME"];
	    //$PASSWORD=$ROW["PASSWORD"];
	    $DEPT_ID1=$ROW["DEPT_ID"];
	    $USER_PRIV=$ROW["USER_PRIV"];
	    $NOT_LOGIN = $ROW["NOT_LOGIN"];
	    $SEX = $ROW["SEX"];
		//----------------QHJ----------------------------------
		$INTEGRAL_TYPE=$ROW["INTEGRAL_TYPE"];
		$USER_ID=$ROW["USER_ID"];
		$INTEGRAL_REASON=$ROW["INTEGRAL_REASON"];
		$INTEGRAL_DATA=$ROW["INTEGRAL_DATA"];
		$CREATE_PERSON=$ROW["CREATE_PERSON"];
		$INTEGRAL_TIME=$ROW["INTEGRAL_TIME"];
		$ITEM_NO=$ROW["ITEM_ID"];
		$ITEM_NAME = getItemName($ITEM_NO);
		$USER_COUNT++;
	    if($USER_COUNT==1)
	    {
	?>
	<table class="TableList" width="100%">
	<?
	    }
	    if($USER_COUNT%2==1)
	       $TableLine="TableLine1";
	    else
	       $TableLine="TableLine2";
	
	    $TR_TITLE = "";
	    $STYLE_STR = "";
	    if ($NOT_LOGIN) {
	    	$STYLE_STR = "COLOR:gray;";
	    }
	?>
	    <tr class="<?=$TableLine?>" title="<?=$TR_TITLE?>" style="<?=$STYLE_STR?>">
	      <td nowrap align="center">
	      	<?
	      		if($INTEGRAL_TYPE == '0'){
	      				print_r("δ���������¼��");
	      		}
	      		if($INTEGRAL_TYPE == '1'){
	      				print_r("OA������Ŀ");
	      		}
	      		if($INTEGRAL_TYPE == '3'){
	      				print_r("�Զ��������¼��");
	      		}
	      	?>	
	      </td>
	      <!--<td nowrap align="center"><?=$USER_NAME?></td>-->
	      <td nowrap align="center"><?
	      	if($ITEM_NO == 0){
	      		print_r("δ����");	
	      	}
	      	else{
	      		print_r($ITEM_NAME);	
	      	}
	      ?></td>
	      <td nowrap align="center"><?=trim(getUserNameById($CREATE_PERSON),",")?></td>
	      <td nowrap align="center"><?=$INTEGRAL_TIME?></td>
	      <td align="center"><?=$INTEGRAL_REASON?></td>
	      <td nowrap align="center"><?=$INTEGRAL_DATA?><?=_("��")?></td>
	    </tr>
	<?
	 }
	
	 if($USER_COUNT>0)
	 {
	?>
	    <thead class="TableHeader">
	      <td nowrap align="center"><?=_("��������")?></td>
	      <!--<td nowrap align="center"><?=_("�û���")?></td>-->
	      <td nowrap align="center"><?=_("������Ŀ")?></td>
	      <td nowrap align="center"><?=_("�����ˣ�����ˣ�")?></td>
	      <td nowrap align="center"><?=_("����ʱ��")?></td>
	      <td nowrap align="center"><?=_("���Ե��")?></td>
	      <td nowrap align="center"><?=_("��ֵ")?></td>
	    </thead>
	    </table>
	<?
	 }
	 else
	    Message("",_("���޴�����ѡ���ͻ��ּ�¼"));
  }
  else 
  {
  	if($begin!="")
		$WHERE_STR.=" and  CREATE_TIME >= '$begin'";
	if($end!="")
		$WHERE_STR.=" and  CREATE_TIME <= '$end'";
	$query1="select count(*) from HR_INTEGRAL_OA where USER_ID='$USER_ID' $WHERE_STR";
	$cursor1=exequery(TD::conn(),$query1);
	if($ROW=mysql_fetch_array($cursor1))
		$TOTAL=$ROW[0];
?>
	<table border="0" cellspacing="0" width="95%" class="small" cellpadding="0" align="center">
	   <tr>
	      <td valign="bottom" class="small1"><?=sprintf(_("��%s��ϵͳͳ�Ƽ�¼"), '<span class="big4">&nbsp;'.$TOTAL.'</span>&nbsp;')?></td>
	      <td align="right" valign="bottom" class="small1"><?=page_bar($start,$TOTAL,$ITEMS_IN_PAGE)?></td>
	   </tr>
	</table>
<?
  	$query="select * from HR_INTEGRAL_OA where USER_ID='$USER_ID' $WHERE_STR  LIMIT " . $start . " ," . $ITEMS_IN_PAGE;
  	$cursor=exequery(TD::conn(),$query);
  	$OA_COUNT=0;
  	while($ROW=mysql_fetch_assoc($cursor))
  	{
  		$USER_ID=$ROW["USER_ID"];
  		$CREATE_TIME=$ROW["CREATE_TIME"];
  		$RECORD_ID=$ROW["RECORD_ID"];
  		foreach($ROW as $key => $value)
  		{
  			if($value==0) continue;
  			if(in_array($key,array("RECORD_ID","USER_ID","SUM","MEMO","CREATE_TIME"))) continue;
  			
  			
  			if(in_array($key,array("RS001","RS002","RS003","RS004","RS005")))
  				$OA_TYPE=2;
  			else 
  				$OA_TYPE=1;
  			//if($SELECT_NAME==1 && $OA_TYPE==2) continue;
  			//if($SELECT_NAME==2 && $OA_TYPE==1) continue;
  			$OA_COUNT++;	
  			if($OA_COUNT%2==1)
		       $TableLine="TableLine1";
		    else
		       $TableLine="TableLine2";
		
  			if($OA_COUNT==1) echo "<table class=\"TableList\" width=\"100%\">";
?>
  		<tr class="<?=$TableLine?>"  >
	      <td nowrap align="center">
	      	<?
	      				echo _("OA������Ŀ");
	      	?>	
	      </td>
	      <!--<td nowrap align="center"><?=$USER_NAME?></td>-->
	      <td nowrap align="center"><?
	      	if($OA_TYPE == 1)
	      		echo getItemNameByNo($key);
	      	else
	      		echo $RS_ARR[$key];	
	      ?>
	      </td>
	      <td nowrap align="center"><?=_("OAϵͳ")?></td>
	      <td nowrap align="center"><?=$CREATE_TIME?></td>
	      <td nowrap align="center"><?=$value?><?=_("��")?></td>
	    </tr>

  		
  			<?
  			}
  	}
   				 if($OA_COUNT>0)
				 {
?>
				    <thead class="TableHeader">
				      <td nowrap align="center"><?=_("��������")?></td>
				      <!--<td nowrap align="center"><?=_("�û���")?></td>-->
				      <td nowrap align="center"><?=_("������Ŀ")?></td>
				      <td nowrap align="center"><?=_("�����ˣ�����ˣ�")?></td>
				      <td nowrap align="center"><?=_("����ʱ��")?></td>
				      <td nowrap align="center"><?=_("��ֵ")?></td>
				    </thead>
				    </table>
<?
  				}
  				else
	    			Message("",_("���޴�����ѡ���ͻ��ּ�¼"));
  }
?>

<br>
<div align="center">
 <input type="button"  value="<?=_("����")?>" class="BigButton" onClick="history.back();">
</div>

</body>
</html>
<!--
<script>
	function click_select(){
		if(document.getElementById("select_id").value == "0"){
			document.getElementById("none_tr").style.display = "none";
		}
		if(document.getElementById("select_id").value == "1"){
			document.getElementById("none_tr").style.display = "";
		}
		if(document.getElementById("select_id").value == "2"){
			document.getElementById("none_tr").style.display = "none";
		}
		if(document.getElementById("select_id").value =="3"){
			document.getElementById("none_tr").style.display = "none";
		}
	}

</script>-->