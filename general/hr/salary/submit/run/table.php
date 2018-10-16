<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
$FLAG=isset($_GET["FLAG"])? $_GET["FLAG"]:0;
$query = "SELECT ITEM_ID from SAL_ITEM where ISCOMPUTER!='1' and ISREPORT!='1'";
$cursor= exequery(TD::conn(),$query);
$FLOW_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    	$STYLE=$STYLE.$ROW["ITEM_ID"].",";
}

$HTML_PAGE_TITLE = _("人员工资录入");
include_once("inc/header.inc.php");
?>


<script>
function sort()
{
	window.location="table.php?DEPT_ID="+<?=$DEPT_ID ?>+"&FLOW_ID="+<?=$FLOW_ID ?>+"&FLAG=1";
}
function calculate(id)
{	
	var arr=document.getElementsByName("user_id");
	var total=0;
	for(var i=0;i<arr.length;i++)
	{
			var temp_id=arr[i].value+"_"+id;
			var temp=document.getElementById(temp_id).value;
			if(temp=="")
				temp=0;
			else
				temp=parseFloat(document.getElementById(temp_id).value);
			total+=temp;
	}
	document.getElementById("total_count").value=total;	
}
</script>

<body class="bodycolor" leftmargin="0">
<?php 
ob_start();
?>
<form action="table_submit.php"  method="post" name="form1">
<table class="TableBlock" align="center">
    <tr class="TableHeader" align="center">
      <td nowrap width="15%"><b><?=_("姓名")?></b></td>
<?
 $STYLE_ARRAY=explode(",",$STYLE);
 $ARRAY_COUNT=sizeof($STYLE_ARRAY);
 $COUNT=0;
 if($STYLE_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
 for($I=0;$I<$ARRAY_COUNT;$I++)
   {
   	 $query1 = "select ITEM_ID,ITEM_NAME from SAL_ITEM where ITEM_ID='$STYLE_ARRAY[$I]'";
     $cursor1= exequery(TD::conn(),$query1);
     if($ROW=mysql_fetch_array($cursor1))
     {
        $ITEM_NAME=$ROW["ITEM_NAME"];
        $ITEM_ID[$COUNT]=$ROW["ITEM_ID"];
     }
     $COUNT++;
?>
      <td nowrap align="center" onClick="clickTitle('<?=$ITEM_ID[$COUNT-1]?>')" style="cursor:hand"><b><?=$ITEM_NAME?></b></td>
<?
 }
?>
    </tr>
<?
//============================ 显示已定义用户 =======================================
 $DEPT_ID = intval($DEPT_ID);
 if(!$FLAG)
 		$query = "SELECT * from USER,USER_PRIV where DEPT_ID='$DEPT_ID' and USER.USER_PRIV=USER_PRIV.USER_PRIV order by PRIV_NO,USER_NO,USER_NAME";
 else
 		$query = "SELECT * from USER,USER_PRIV where DEPT_ID='$DEPT_ID' and USER.USER_PRIV=USER_PRIV.USER_PRIV order by USER_NAME";
 $cursor= exequery(TD::conn(),$query);
 $USER_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $USER_COUNT++;
    
    $USER_ID=$ROW["USER_ID"];
    $USER_NAME=$ROW["USER_NAME"];
    $STYLE_USER=$STYLE_USER.$USER_ID.",";
    if($USER_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableContent";
?>
    <tr class="TableLine1" align="center">
      <td nowrap><?=$USER_NAME?></td>
<?

  $query8="select * from SAL_DATA where FLOW_ID='$FLOW_ID' and USER_ID='$USER_ID'";
  $cursor8= exequery(TD::conn(),$query8);
  if($ROW8=mysql_fetch_array($cursor8))
     $OPERATION=2; //-- 将执行数据更新-- 
  else
     $OPERATION=1; //-- 将执行数据插入 --

  $query8="select * from SAL_DATA where FLOW_ID='$FLOW_ID' and USER_ID='$USER_ID' and IS_FINA_INPUT='1'";
  $cursor8= exequery(TD::conn(),$query8);
  if($ROW8=mysql_fetch_array($cursor8))
  {
    for($I=1;$I<=50;$I++)
    {
      $STR="S".$I;
      $STR2=$STR.$USER_ID;
      $$STR2=format_money($ROW8["$STR"]);
    }
    $MEMO=$ROW8["MEMO"];
    $PENSION_BASE = $ROW8["PENSION_BASE"];
    $PENSION_U = $ROW8["PENSION_U"];     
    $PENSION_P = $ROW8["PENSION_P"];     
    $MEDICAL_BASE = $ROW8["MEDICAL_BASE"];     
    $MEDICAL_U = $ROW8["MEDICAL_U"];     
    $MEDICAL_P = $ROW8["MEDICAL_P"];     
    $FERTILITY_BASE = $ROW8["FERTILITY_BASE"];     
    $FERTILITY_U = $ROW8["FERTILITY_U"];
    $UNEMPLOYMENT_BASE = $ROW8["UNEMPLOYMENT_BASE"];     
    $UNEMPLOYMENT_U = $ROW8["UNEMPLOYMENT_U"];     
    $UNEMPLOYMENT_P = $ROW8["UNEMPLOYMENT_P"];     
    $INJURIES_BASE = $ROW8["INJURIES_BASE"];     
    $INJURIES_U = $ROW8["INJURIES_U"];     
    $HOUSING_BASE = $ROW8["HOUSING_BASE"];     
    $HOUSING_U = $ROW8["HOUSING_U"];
    $HOUSING_P = $ROW8["HOUSING_P"];
    $INSURANCE_DATE = $ROW8["INSURANCE_DATE"];
    $INSURANCE_OTHER = $ROW8["INSURANCE_OTHER"];

  }
  else
  {
    $query8="select * from HR_SAL_DATA where USER_ID='$USER_ID'";
    $cursor8= exequery(TD::conn(),$query8);
    if($ROW8=mysql_fetch_array($cursor8))
    {
       for($I=1;$I<=50;$I++)
       {
         $STR="S".$I;
         $STR2=$STR.$USER_ID;
         $$STR2=format_money($ROW8["$STR"]);
       }
    
       $ALL_BASE = $ROW8["ALL_BASE"];
       $PENSION_BASE = $ROW8["PENSION_BASE"];
       $PENSION_U = $ROW8["PENSION_U"];     
       $PENSION_P = $ROW8["PENSION_P"];     
       $MEDICAL_BASE = $ROW8["MEDICAL_BASE"];     
       $MEDICAL_U = $ROW8["MEDICAL_U"];     
       $MEDICAL_P = $ROW8["MEDICAL_P"];     
       $FERTILITY_BASE = $ROW8["FERTILITY_BASE"];     
       $FERTILITY_U = $ROW8["FERTILITY_U"];
       $UNEMPLOYMENT_BASE = $ROW8["UNEMPLOYMENT_BASE"];     
       $UNEMPLOYMENT_U = $ROW8["UNEMPLOYMENT_U"];     
       $UNEMPLOYMENT_P = $ROW8["UNEMPLOYMENT_P"];     
       $INJURIES_BASE = $ROW8["INJURIES_BASE"];     
       $INJURIES_U = $ROW8["INJURIES_U"];     
       $HOUSING_BASE = $ROW8["HOUSING_BASE"];     
       $HOUSING_U = $ROW8["HOUSING_U"];
       $HOUSING_P = $ROW8["HOUSING_P"];
   }
  
  }

  $STYLE_ARRAY=explode(",",$STYLE);
  $ARRAY_COUNT=sizeof($STYLE_ARRAY);
  $COUNT=0;
  if($STYLE_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
  for($I=0;$I<$ARRAY_COUNT;$I++)
  {
   	 $query1 = "select ITEM_ID,ITEM_NAME from SAL_ITEM where ITEM_ID='$STYLE_ARRAY[$I]'";
     $cursor1= exequery(TD::conn(),$query1);
     if($ROW=mysql_fetch_array($cursor1))
     {
        	$ITEM_NAME=$ROW["ITEM_NAME"];
          $ITEM_ID[$COUNT]=$ROW["ITEM_ID"];
          $S_ID="S".$ITEM_ID[$COUNT].$USER_ID;
     }
     $COUNT++;
?>
      <td nowrap align="center">
      	<input type="text" style="text-align: right;" name="<?=$USER_ID?>_<?=$ITEM_ID[$COUNT-1]?>" class="SmallInput" value="<?=$$S_ID?>" size="10" onblur="calculate(<?=$ITEM_ID[$COUNT-1] ?>)">
      	<input type="hidden" name="user_id" value="<?=$USER_ID ?>" >
      </td>
<?
  }
?>
    <input type="hidden" name="<?=$USER_ID?>_OPERATION" class="SmallInput" value="<?=$OPERATION?>" size="10">
<?
 }
 if($USER_COUNT==0)
    $DEPT_COUNT--;
?>
    <tr class="TableLine1" align="center">
      <td nowrap><?=_("合计") ?></td>
      <td nowrap align="center"><input type="text" style="text-align: right;" id="total_count" name="total_count" class="BigStatic"  size="10" readonly></td>
    </tr>
</table>
<br>
<div align="center">
<input type="hidden" value="<?=$STYLE ?>"  name="STYLE">
<input type="hidden" value="<?=$FLOW_ID ?>"  name="FLOW_ID">
<input type="hidden" value="<?=$DEPT_ID ?>"  name="DEPT_ID">
<input type="hidden" value="<?=$STYLE_USER ?>"  name="STYLE_USER">
<input type="submit" value="<?=_("确定")?>" class="BigButton" title="<?=_("确定")?>" name="button">&nbsp;&nbsp;
<input type="button" value="<?=_("按拼音排序")?>" class="BigButton" title="<?=_("人员按拼音排序")?>" name="button" onClick="sort(<?=$FLAG ?>)">&nbsp;
</div>
</form>
<?php 
$form_str=ob_get_contents();
ob_end_clean();
if($COUNT > 0 && $USER_COUNT > 0)
	echo $form_str;
else
{
	if($USER_COUNT < 1)
		$content=_("此部门下无可录入薪酬的员工");
	else if($COUNT < 1 )
		$content=_("请在薪酬项目设置中添加财务录入薪酬项");
	Message(_("提示"), $content);
	Button_Back();
}
?>
</body>
</html>
<script language="JavaScript">
function clickTitle(ID)
{
  var str1=document.all("STYLE_USER").value;
  var id_value_array=str1.split(",");
  var temp=id_value_array.length-2;
  for(i=0;i<=temp;i++)
  {
  	control=id_value_array[i]+"_"+ID;
  	if(i==0)setvalue=document.all(control).value;
  	document.all(control).value=setvalue;
  }
}
</script>