<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("积分结果");
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
    	<span class="big3"> <?=_("查询条件")?></span>
    </td>
  </tr> 
</table>
<table class="TableBlock" width="100%" align="center">
  <form enctype="multipart/form-data" action="index.php"  method="get" name="form1">
    <tr>
      <td nowrap class="TableData" align="center"> <?=_("积分类型：")?></td>
      <td class="TableData">
      <select id = "select_id" name="SELECT_NAME" class="BigSelect">
      	<?
      		$SELECT0 = "";$SELECT1 = "";$SELECT2 = "";$SELECT3 = "";
      		if($SELECT_NAME == '0' ){$SELECT0 = "selected";$SELECT1 = "";$SELECT2 = "";$SELECT3 = "";}
      		if($SELECT_NAME == '1'){$SELECT1 = "selected";$SELECT0 = "";$SELECT2 = "";$SELECT3 = "";}
      		if($SELECT_NAME == '3'){$SELECT3 = "selected";$SELECT1 = "";$SELECT2 = "";$SELECT0 = "";}
      	?>
        <option value="1" <?=$SELECT1?>><?=_("OA计算项目")?></option>
        <option value="3" <?=$SELECT3?>><?=_("自定义项积分录入")?></option>
        <option value="0" <?=$SELECT0?>><?=_("未定义项积分录入")?></option>
      </select>
      </td>
      <td nowrap class="TableData" align="center" > <?=_("积分时间:")?></td>
      <td class="TableData" align="center" nowrap>
      	<input type="text" name="USER_ID" size="12" style = "display:none" value = "<?=$USER_ID?>"/>
      	<input type="text" name="DEPT_ID" size="12" style = "display:none" value = "<?=$DEPT_ID?>"/>
        <?=_("从")?>&nbsp;<input type="text" name="begin" size="12" maxlength="10" class="BigInput" id="start_time" value="<?=$begin?>" onClick="WdatePicker()" /> <?=_("至")?>&nbsp;
        <input type="text" name="end" size="12" maxlength="10" class="BigInput" value="<?=$end?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})" />
      </td>
    </tr>
    <!--
    <tr id = "none_tr" style = "display:none">
      <td nowrap class="TableData" width="10%"  align="center" ><?=_("OA使用项")?></td>
      <td nowrap class="TableData" width="10%"  align="center" colspan="3">
	<input type="checkbox" name="DO_LIST[]" id="email" value="email" />&nbsp; <label for="email" ><?=_("邮件模块")?></label>
	<input type="checkbox" name="DO_LIST[]" id="workflow" value="workflow" />&nbsp; <label for="workflow" ><?=_("工作流模块")?></label>
	<input type="checkbox" name="DO_LIST[]" id="attend" value="attend" />&nbsp; <label for="attend" ><?=_("考勤")?></label>
	<input type="checkbox" name="DO_LIST[]" id="calendar" value="calendar" />&nbsp; <label for="calendar" ><?=_("日程安排")?></label>
	<input type="checkbox" name="DO_LIST[]" id="diary" value="diary" />&nbsp; <label for="diary" ><?=_("工作日志")?></label>
	<input type="checkbox" name="DO_LIST[]" id="project" value="project" />&nbsp; <label for="project" ><?=_("项目管理")?></label>
	<input type="checkbox" name="DO_LIST[]" id="news" value="news" />&nbsp; <label for="news" ><?=_("新闻公告")?></label>
	<input type="checkbox" name="DO_LIST[]" id="knowledge" value="knowledge" />&nbsp; <label for="knowledge" ><?=_("知识库")?></label>
	<input type="checkbox" name="DO_LIST[]" id="hrms" value="hrms" />&nbsp; <label for="hrms" ><?=_("人事档案")?></label>
      </td>
    </tr>
    -->
    <tr align="center" class="TableControl">
      <td colspan="8" nowrap>
        <input type="submit" value="<?=_("查询")?>" class="BigButton">&nbsp;&nbsp;
      </td>
    </tr>
  </table><Br/>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle">
    	<span class="big3"><?=trim(getUserNameById($USER_ID),",")?>-<?=_("积分详细")?></span>
    	<span class="big3" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=_("个人总积分：")?><?=_(getUser_SumPoint($USER_ID))?></span>
    	<span class="big3" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? 
    		$Rank = getUser_Rank($USER_ID);
    		if($Rank <=3 && $Rank >=1){
    			if($Rank == 1)$Rank = _("第 一 名");	
    			if($Rank == 2)$Rank = _("第 二 名");
    			if($Rank == 3)$Rank = _("第 三 名");
    			print_r(sprintf(_("积分排行：%s"), '<span class="big4">&nbsp;'.$Rank.'</span>&nbsp;'));
    		}
    		else if($Rank == -1)
    			print_r('<span>&nbsp;积分排行：此人暂无排行 </span>&nbsp;');
    		else
    			print_r('<span>&nbsp;积分排行：第 '.$Rank.' 名 </span>&nbsp;');
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
		if(find_dept_id($USER_ID) != '0'){//在职
			$query = "SELECT count(*) from HR_INTEGRAL_DATA as ia left join USER as u on ia.USER_ID = u.USER_ID left join DEPARTMENT as d on d.DEPT_ID = u.DEPT_ID left join USER_PRIV as up on up.USER_PRIV = u.USER_PRIV where 1=1 and ia.USER_ID = '".$USER_ID."' ".$WHERE_STR." ORDER BY u.USER_PRIV ";
		}
		if(find_dept_id($USER_ID) == '0'){//离职
			$query = "SELECT count(*) from HR_INTEGRAL_DATA as ia left join USER as u on u.USER_ID = ia.USER_ID left join USER_PRIV as up on up.USER_PRIV = u.USER_PRIV where 1=1 and ia.USER_ID = '".$USER_ID."' ".$WHERE_STR." ORDER BY u.USER_PRIV ";
		}

		 $cursor= exequery(TD::conn(),$query);
		 $USER_TOTAL=0;
		 if($ROW=mysql_fetch_array($cursor))
		  $USER_TOTAL=$ROW[0];
	?>
	<table border="0" cellspacing="0" width="95%" class="small" cellpadding="0" align="center">
	   <tr>
	      <td valign="bottom" class="small1"><?=sprintf(_("共%s条记录"), '<span class="big4">&nbsp;'.$USER_TOTAL.'</span>&nbsp;')?></td>
	      <td align="right" valign="bottom" class="small1"><?=page_bar($start,$USER_TOTAL,$ITEMS_IN_PAGE)?></td>
	   </tr>
	</table>
	<?
		if(find_dept_id($USER_ID) != '0'){//在职
	 	$query = "SELECT u.USER_NAME,u.DEPT_ID,u.USER_PRIV,u.NOT_LOGIN,u.SEX,ia.* from HR_INTEGRAL_DATA as ia left join USER as u on ia.USER_ID = u.USER_ID left join DEPARTMENT as d on d.DEPT_ID = u.DEPT_ID left join USER_PRIV as up on up.USER_PRIV = u.USER_PRIV where 1=1 and ia.USER_ID = '".$USER_ID."' ".$WHERE_STR." ORDER BY ia.CREATE_TIME DESC". " LIMIT " . $start . " ," . $ITEMS_IN_PAGE;
	 	}
	 	if(find_dept_id($USER_ID) == '0'){//离职
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
	      				print_r("未定义项积分录入");
	      		}
	      		if($INTEGRAL_TYPE == '1'){
	      				print_r("OA计算项目");
	      		}
	      		if($INTEGRAL_TYPE == '3'){
	      				print_r("自定义项积分录入");
	      		}
	      	?>	
	      </td>
	      <!--<td nowrap align="center"><?=$USER_NAME?></td>-->
	      <td nowrap align="center"><?
	      	if($ITEM_NO == 0){
	      		print_r("未定义");	
	      	}
	      	else{
	      		print_r($ITEM_NAME);	
	      	}
	      ?></td>
	      <td nowrap align="center"><?=trim(getUserNameById($CREATE_PERSON),",")?></td>
	      <td nowrap align="center"><?=$INTEGRAL_TIME?></td>
	      <td align="center"><?=$INTEGRAL_REASON?></td>
	      <td nowrap align="center"><?=$INTEGRAL_DATA?><?=_("分")?></td>
	    </tr>
	<?
	 }
	
	 if($USER_COUNT>0)
	 {
	?>
	    <thead class="TableHeader">
	      <td nowrap align="center"><?=_("积分类型")?></td>
	      <!--<td nowrap align="center"><?=_("用户名")?></td>-->
	      <td nowrap align="center"><?=_("积分项目")?></td>
	      <td nowrap align="center"><?=_("积分人（打分人）")?></td>
	      <td nowrap align="center"><?=_("积分时间")?></td>
	      <td nowrap align="center"><?=_("打分缘由")?></td>
	      <td nowrap align="center"><?=_("分值")?></td>
	    </thead>
	    </table>
	<?
	 }
	 else
	    Message("",_("暂无此人所选类型积分记录"));
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
	      <td valign="bottom" class="small1"><?=sprintf(_("共%s次系统统计记录"), '<span class="big4">&nbsp;'.$TOTAL.'</span>&nbsp;')?></td>
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
	      				echo _("OA计算项目");
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
	      <td nowrap align="center"><?=_("OA系统")?></td>
	      <td nowrap align="center"><?=$CREATE_TIME?></td>
	      <td nowrap align="center"><?=$value?><?=_("分")?></td>
	    </tr>

  		
  			<?
  			}
  	}
   				 if($OA_COUNT>0)
				 {
?>
				    <thead class="TableHeader">
				      <td nowrap align="center"><?=_("积分类型")?></td>
				      <!--<td nowrap align="center"><?=_("用户名")?></td>-->
				      <td nowrap align="center"><?=_("积分项目")?></td>
				      <td nowrap align="center"><?=_("积分人（打分人）")?></td>
				      <td nowrap align="center"><?=_("积分时间")?></td>
				      <td nowrap align="center"><?=_("分值")?></td>
				    </thead>
				    </table>
<?
  				}
  				else
	    			Message("",_("暂无此人所选类型积分记录"));
  }
?>

<br>
<div align="center">
 <input type="button"  value="<?=_("返回")?>" class="BigButton" onClick="history.back();">
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