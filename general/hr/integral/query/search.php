<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("积分结果");
include_once("inc/header.inc.php");
?>

<?
$SELECT_NAME=isset($SELECT_NAME)?$SELECT_NAME:"";
$FROM_DEPT_ID=$DEPT_ID;//存部门
$NEW_DEPT_ID=intval($NEW_DEPT_ID);
$WHERE_STR = "";
if($TO_ID!="")
	$WHERE_STR.=" and find_in_set(USER.USER_ID,'$TO_ID') ";
?>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<body class="bodycolor" >
<?
	if($DEPT_ID!="")//部门
	 {
	    if($DEPT_ID!="0")// 查询全部人员
	    {
	       $DEPT_ID_CHILD = td_trim(GetChildDeptId($DEPT_ID));
	       $WHERE_STR.=" and USER.DEPT_ID in ($DEPT_ID_CHILD)";
	    }
	    else
	    {
	       $WHERE_STR.=" and USER.DEPT_ID='0'";
	    }
	 }
  if($SEX!="")// 性别
    $WHERE_STR.=" and USER.SEX='$SEX'";
  if($USER_PRIV!="")//角色
    $WHERE_STR.=" and USER.USER_PRIV='$USER_PRIV'";
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" WIDTH="22" HEIGHT="20" align="absmiddle">
    	<span class="big3"> <?=_("查询条件")?></span>
    </td>
  </tr> 
</table>
<table class="TableBlock" width="100%" align="center">
  <form enctype="multipart/form-data" action="search.php"  method="get" name="form1">
    <tr>
      <td nowrap class="TableData" width="10%" align="center"> <?=_("积分类型：")?></td>
      <td class="TableData">
      <select id = "select_id" name="SELECT_NAME" class="BigSelect">
      	<?
      		$SELECT1 = "";$SELECT2 = "";
      		if($SELECT_NAME == '1'){$SELECT1 = "selected";$SELECT2 = "";}
      		if($SELECT_NAME == '2'){$SELECT2 = "selected";$SELECT1 = "";}
      	?>
        <option value=""  selected></option>
        <option value="1" <?=$SELECT1?>><?=_("自动录入项")?></option>
        <option value="2" <?=$SELECT2?>><?=_("手动录入项")?></option>
      </select>
      </td>
      <td nowrap class="TableData" width="10%" align="center"> <?=_("积分时间:")?></td>
      <td class="TableData" align="center">
      	<input type="text" name="USER_ID" size="12" style = "display:none" value = "<?=$USER_ID?>"/>
      	<input type="text" name="DEPT_ID" size="12" style = "display:none" value = "<?=$DEPT_ID?>"/>
      	<input type="text" name="SEX" size="12" style = "display:none" value = "<?=$SEX?>"/>
      	<input type="text" name="USER_NAME" size="12" style = "display:none" value = "<?=$USER_NAME?>"/>
      	<input type="text" name="USER_PRIV" size="12" style = "display:none" value = "<?=$USER_PRIV?>"/><?=_("从")?>&nbsp;
        <input type="text" name="begin" size="12" maxlength="10" class="BigInput" value="<?=$begin?>" onClick="WdatePicker()" /> <?=_("至")?>&nbsp;
        <input type="text" name="end" size="12" maxlength="10" class="BigInput" value="<?=$end?>" onClick="WdatePicker()" />
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="4" nowrap>
        <input type="submit" value="<?=_("查询")?>" class="BigButton">&nbsp;&nbsp;
      </td>
    </tr>
  </table><Br/>
  
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" WIDTH="22" HEIGHT="20" align="absmiddle">
    	<span class="big3"> <?=_("查询结果")?></span>
    </td>
  </tr>
  
</table>
<?
        $rank_info = array();
        $query="";
	if($begin!="")
	{
		$WHERE_STR_IN.=" and INTEGRAL_TIME >= '$begin' ";
		$WHERE_STR_OA.=" and CREATE_TIME >= '$begin' ";
	}
	if($end!="")
	{
		$WHERE_STR_IN.=" and INTEGRAL_TIME <= '$end' ";
		$WHERE_STR_OA.=" and CREATE_TIME <= '$end' ";
	}
	if($SELECT_NAME==1)
	{
                $query="SELECT hr_integral_oa.USER_ID,SUM(hr_integral_oa.SUM) AS SUMALL from hr_integral_oa,USER where hr_integral_oa.USER_ID=USER.USER_ID $WHERE_STR $WHERE_STR_OA GROUP BY hr_integral_oa.USER_ID order by SUMALL DESC";
                $cursor=exequery(TD::conn(),$query);
                while($ROW=mysql_fetch_array($cursor))
                {
                    $USRE_ID_RANK=$ROW["USER_ID"];
                    $SUM=$ROW["SUMALL"];
                    if($USRE_ID_RANK !="")
                    {
                        $rank_info[$USRE_ID_RANK]=$SUM;
                    }    
                }
	}
	else if($SELECT_NAME==2)
	{
		$query="SELECT hr_integral_data.USER_ID,SUM(hr_integral_data.INTEGRAL_DATA) AS SUMALL from hr_integral_data,USER where hr_integral_data.USER_ID=USER.USER_ID $WHERE_STR $WHERE_STR_IN GROUP BY hr_integral_data.USER_ID order by SUMALL DESC";
                $cursor=exequery(TD::conn(),$query);
                while($ROW=mysql_fetch_array($cursor))
                {
                    $USRE_ID_RANK=$ROW["USER_ID"];
                    $SUM=$ROW["SUMALL"];
                    if($USRE_ID_RANK !="")
                    {
                        $rank_info[$USRE_ID_RANK]=$SUM;
                    }    
                }
	}
	else
	{
                $query="SELECT hr_integral_oa.USER_ID,SUM(hr_integral_oa.SUM) as SUM1 from hr_integral_oa,USER where hr_integral_oa.USER_ID=USER.USER_ID $WHERE_STR $WHERE_STR_OA GROUP BY hr_integral_oa.USER_ID";
                $cursor=exequery(TD::conn(),$query);
                while($ROW=mysql_fetch_array($cursor))
                {
                    $USRE_ID_RANK=$ROW["USER_ID"];
                    $SUM1=$ROW["SUM1"];
                    if($USRE_ID_RANK !="")
                    {
                        $rank_info[$USRE_ID_RANK]=$SUM1;
                    }    
                }
                $query="SELECT hr_integral_data.USER_ID,SUM(hr_integral_data.INTEGRAL_DATA) as SUM2 from hr_integral_data,USER where hr_integral_data.USER_ID=USER.USER_ID $WHERE_STR $WHERE_STR_IN GROUP BY hr_integral_data.USER_ID";
                $cursor=exequery(TD::conn(),$query);
                while($ROW=mysql_fetch_array($cursor))
                {
                    $USRE_ID_RANK=$ROW["USER_ID"];
                    $SUM2=$ROW["SUM2"];
                    if($USRE_ID_RANK !="")
                    {
                        if(array_key_exists($USRE_ID_RANK,$rank_info))
                        {
                            $rank_info[$USRE_ID_RANK]=$SUM2;
                        }
                        else
                        {
                             $rank_info[$USRE_ID_RANK]+=$SUM2;
                        }
                    }    
                }
//		$query="
//		SELECT USER_ID,SUM1 AS SUMALL 
//		from ((SELECT hr_integral_oa.USER_ID,SUM(hr_integral_oa.SUM) as SUM1 from hr_integral_oa,USER where hr_integral_oa.USER_ID=USER.USER_ID $WHERE_STR $WHERE_STR_OA GROUP BY hr_integral_oa.USER_ID )
//		UNION
//		(SELECT hr_integral_data.USER_ID,SUM(hr_integral_data.INTEGRAL_DATA) as SUM1 from hr_integral_data,USER where hr_integral_data.USER_ID=USER.USER_ID $WHERE_STR $WHERE_STR_IN GROUP BY hr_integral_data.USER_ID)) AS INTEGRAL_TEM 
//		GROUP BY INTEGRAL_TEM.USER_ID ORDER BY SUMALL DESC";
	}        
	$USER_TOTAL=count($rank_info);
	$ITEMS_IN_PAGE=10;
	if(!isset($start) || $start=="")
	 $start=0;
	
?>
<table border="0" cellspacing="0" width="95%" class="small" cellpadding="0" align="center">
   <tr>
      <td valign="bottom" class="small1"><?=sprintf(_("共%s条记录"), '<span class="big4">&nbsp;'.$USER_TOTAL.'</span>&nbsp;')?></td>
      <td align="right" valign="bottom" class="small1"><?=page_bar($start,$USER_TOTAL,$ITEMS_IN_PAGE)?></td>
   </tr>
</table>
<?

 $end=$start+$ITEMS_IN_PAGE;
 $USER_COUNT=0;
foreach($rank_info as $rank_key=>$rank_value)
 {
 	$USER_COUNT++;        
 	if($USER_COUNT< $start+1 || $USER_COUNT > $end)
 		continue;
 	$USER_ID=$rank_key;
 	$SUMALL=round($rank_value,2);
 	
 	$query_user="select USER_NAME,DEPT_ID,USER_PRIV,SEX from USER where USER_ID='$USER_ID'";
 	$cursor_user=exequery(TD::conn(),$query_user);
 	if($ROW_USER=mysql_fetch_array($cursor_user))
 	{
 		$USER_NAME=$ROW_USER["USER_NAME"];
 		$DEPT_ID1=$ROW_USER["DEPT_ID"];
 		$USER_PRIV=$ROW_USER["USER_PRIV"];
 		$SEX=$ROW_USER["SEX"];
 	}
 	
    if($DEPT_ID1 != '0' && $DEPT_ID1 !="")
    {
    	$query1 = "SELECT * from DEPARTMENT where DEPT_ID='".$DEPT_ID1."'";
	    $cursor1= exequery(TD::conn(),$query1);
	    if($ROW=mysql_fetch_array($cursor1))
	      $DEPT_NAME=$ROW["DEPT_NAME"];
	    else
	      $DEPT_NAME=_("离职人员/外部人员");
    }
    else
    		$DEPT_NAME=_("离职人员/外部人员");

    $query1 = "SELECT * from USER_PRIV where USER_PRIV='$USER_PRIV'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
         $USER_PRIV=$ROW["PRIV_NAME"];

    $DEPT_LONG_NAME=dept_long_name($DEPT_ID1);
    if($USER_COUNT==$start+1)
    {
?>
<table class="TableList" width="100%">
<?
    }
    if($USER_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>" title="<?=$TR_TITLE?>" style="<?=$STYLE_STR?>">
      <td nowrap align="center"><?=$DEPT_NAME?></td>
      <!--<td nowrap align="center"><?=$USER_NAME?></td>-->
      <td nowrap align="center"><a href="point_specific.php?USER_ID=<?=$USER_ID?>"><?=$USER_NAME?></a></td>
      <td nowrap align="center"><?=$USER_PRIV?></td>
      <td nowrap align="center"><?if($SEX == 0)echo _("男");else echo _("女");?></td>
      <td nowrap align="center"><?=$SUMALL?><?=_("分")?></td>
      <td nowrap align="center">
      <a href="point_specific.php?USER_ID=<?=$USER_ID?>"><?=_("查看详细")?></a>
      </td>
    </tr>
<?
 }
 if($USER_COUNT>0)
 {
?>
    <thead class="TableHeader">
      <td nowrap align="center"><?=_("部门")?></td>      
      <td nowrap align="center"><?=_("姓名")?></td>
      <td nowrap align="center"><?=_("角色")?></td>
      <td nowrap align="center"><?=_("性别")?></td>
      <td nowrap align="center"><?=_("总积分数")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
    </thead>
    </table>
<?
 }
 else
    Message("",_("无符合条件的用户"));
?>

<br>
<div align="center">
 <input type="button"  value="<?=_("返回")?>" class="BigButton" onClick="parent.location='query.php'">
</div>

</body>
<!--
<frameset rows="40,*"  cols="*" frameborder="no" border="0" framespacing="0" id="frame1">
    <frame name="user_title" scrolling="no" noresize src="title.php" frameborder="NO">
    <frameset rows="*"  cols="200,*" frameborder="no" border="0" framespacing="0" id="frame2">
       <frame name="user_list" scrolling="auto" noresize src="user_list.php" frameborder="NO">
       <frame name="user_main" scrolling="auto" src="query.php" frameborder="NO">
    </frameset>
</frameset>-->
</html>
