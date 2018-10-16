<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");


$HTML_PAGE_TITLE = _("退休人员");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$SYS_PARA_ARRAY = get_sys_para("RETIRE_AGE");
$PARA_VALUE=$SYS_PARA_ARRAY["RETIRE_AGE"];

$AGE_ARRAY=explode(",",$PARA_VALUE);//[0]为男退休年龄，[1]为女退休年龄
$query="";
//------退休人员-----
$query1="select *  from HR_STAFF_INFO a 
         LEFT OUTER JOIN USER b ON a.USER_ID = b.USER_ID
         LEFT OUTER JOIN DEPARTMENT f ON b.DEPT_ID=f.DEPT_ID";
$query1.=" where 1=1";
$query1.=" and b.DEPT_ID!='0' ";
$cursor= exequery(TD::conn(),$query1);//echo $query1;
$HRMS_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   	$DEPT_ID=$ROW["DEPT_ID"];
    $USER_ID=$ROW["USER_ID"];
   	$STAFF_SEX=$ROW["STAFF_SEX"];
   	$STAFF_BIRTH=$ROW["STAFF_BIRTH"];
    $WORK_STATUS=$ROW["WORK_STATUS"];
    if($STAFF_BIRTH=="0000-00-00"||$STAFF_BIRTH=="1900-01-01")continue;
    if($WORK_STATUS == "02"||$WORK_STATUS == "03"||$WORK_STATUS == "04") continue;
   	if($QUERY_DATE1!="")
   	{
   	 	if($STAFF_SEX=="0")
   	 	{
       $YEAR_MIN=date("Y",strtotime($QUERY_DATE1))-$AGE_ARRAY[0];
       $YEAR_MIN.=date("-m-d",strtotime($QUERY_DATE1));
       if(compare_date($YEAR_MIN,$STAFF_BIRTH)!="-1")
         continue;
      }
      else
      {
       $YEAR_MIN=date("Y",strtotime($QUERY_DATE1))-$AGE_ARRAY[1];
       $YEAR_MIN.=date("-m-d",strtotime($QUERY_DATE1));
       if(compare_date($YEAR_MIN,$STAFF_BIRTH)!="-1")
         continue;
      }
    }
    if($QUERY_DATE2!="")
   	{
   	 	if($STAFF_SEX=="0")
   	 	{
       $YEAR_MAX=date("Y",strtotime($QUERY_DATE2))-$AGE_ARRAY[0];
        date("Y",strtotime($QUERY_DATE2));
       $YEAR_MAX.=date("-m-d",strtotime($QUERY_DATE2));
       if(compare_date($YEAR_MAX,$STAFF_BIRTH)=="-1")
       continue;
      }
      else
      {
       $YEAR_MAX=date("Y",strtotime($QUERY_DATE2))-$AGE_ARRAY[1];
       $YEAR_MAX.=date("-m-d",strtotime($QUERY_DATE2));
       if(compare_date($YEAR_MAX,$STAFF_BIRTH)=="-1")
       continue;
      }
    }
    $HRMS_COUNT++;
}
if($HRMS_COUNT!=0)
{
?>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hrms.gif" HEIGHT="20"><span class="big3"> <?=_("退休人员")?></span><br>
    </td>
    <td valign="bottom" align="right"><span class="small1"><?=sprintf(_("共%s条信息"), '<span class="big4">'.$HRMS_COUNT.'</span>')?></span>
    </td>
  </tr>
</table>
<?
  $HRMS_COUNT=0;
 $query1="select *  from HR_STAFF_INFO a 
         LEFT OUTER JOIN USER b ON a.USER_ID = b.USER_ID
         LEFT OUTER JOIN DEPARTMENT f ON b.DEPT_ID=f.DEPT_ID";
$query1.=" where 1=1";
$query1.=" and b.DEPT_ID!='0' ";
 $cursor= exequery(TD::conn(),$query1);//echo $query1;
 while($ROW=mysql_fetch_array($cursor))
 {
 	 $NATIVE_PLACENAME="";
   $USER_ID=$ROW["USER_ID"];
   $USER_NAME=$ROW["USER_NAME"];
   $DEPT_NAME=$ROW["DEPT_NAME"];
   $DEPT_ID=$ROW["DEPT_ID"];
   $STAFF_SEX=$ROW["STAFF_SEX"];
   $STAFF_BIRTH=$ROW["STAFF_BIRTH"];
   $CONTRACT_DATE1=$ROW["CONTRACT_DATE1"];
   $CONTRACT_DATE2=$ROW["CONTRACT_DATE2"];
    $WORK_STATUS=$ROW["WORK_STATUS"];
   $query1 = "SELECT CODE_NAME from HR_CODE where PARENT_NO='AREA'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))$NATIVE_PLACENAME=$ROW["CODE_NAME"];
   if($STAFF_SEX=="0")
    {
    	$SEX_DESC=_("男");
    }
   else if($STAFF_SEX=="1")
    {
      $SEX_DESC=_("女");
    }
   else
    {
      $SEX_DESC="";
    }
   if($STAFF_BIRTH=="0000-00-00"||$STAFF_BIRTH=="1900-01-01")continue;
   if($WORK_STATUS == "02"||$WORK_STATUS == "03"||$WORK_STATUS == "04") continue;
   if($QUERY_DATE1!="")
   {
   	 	if($STAFF_SEX=="0")
   	 	{
       $YEAR_MIN=date("Y",strtotime($QUERY_DATE1))-$AGE_ARRAY[0];
       $YEAR_MIN.=date("-m-d",strtotime($QUERY_DATE1));
      if(compare_date($YEAR_MIN,$STAFF_BIRTH)!="-1")
       continue;
      }
      else
      {
       $YEAR_MIN=date("Y",strtotime($QUERY_DATE1))-$AGE_ARRAY[1];
       $YEAR_MIN.=date("-m-d",strtotime($QUERY_DATE1));
      if(compare_date($YEAR_MIN,$STAFF_BIRTH)!="-1")
       continue;
      }
   }
   if($QUERY_DATE2!="")
   {
   	 	if($STAFF_SEX=="0")
   	 	{
       $YEAR_MAX=date("Y",strtotime($QUERY_DATE2))-$AGE_ARRAY[0];
       $YEAR_MAX.=date("-m-d",strtotime($QUERY_DATE2));
       if(compare_date($YEAR_MAX,$STAFF_BIRTH)=="-1")
       continue;
      }
      else
      {
       $YEAR_MAX=date("Y",strtotime($QUERY_DATE2))-$AGE_ARRAY[1];
       $YEAR_MAX.=date("-m-d",strtotime($QUERY_DATE2));
       if(compare_date($YEAR_MAX,$STAFF_BIRTH)=="-1")
       continue;
      }
   }
   $HRMS_COUNT++;
   if($HRMS_COUNT==1)
   {
?>
<table width="100%" class="TableList">
  <tr class="TableHeader">
  	  <td nowrap align="center"><?=_("部门")?></td>
      <td nowrap align="center"><?=_("姓名")?></td>
      <td nowrap align="center"><?=_("性别")?></td>
      <td nowrap align="center"><?=_("出生日期")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
    </tr>
<?
   }
?>
 <tr class="TableData">
      <td nowrap align="center"><?=$DEPT_NAME?></td>
      <td nowrap align="center"><?=$USER_NAME?></td>
      <td nowrap align="center"><?=$SEX_DESC?></td>
      <td nowrap align="center"><?=$STAFF_BIRTH?></td>
      <td nowrap align="center">
        <a href="javascript:;" onClick="window.open('staff_detail.php?USER_ID=<?=$USER_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;</a>
      </td>
    </tr>
<?
}
?>
</table>
<?
}
else
{
  Message("",_("无符合条件的退休记录!"));
}  
?>
<br>
<div align="center">
<?
if($FROM_TABLE == 1) 
{
?>
  <input type="button" class="BigButton" value="<?=_("返回")?>" onclick="history.back();">
  
<?
}else{
?>
<input type="button" class="SmallButton" value="<?=_("关闭")?>" onClick="window.close();">
<?
}
?>
</div>    
</body>
</html>
