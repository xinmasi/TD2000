<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");


$query = "SELECT * from HR_RECRUIT_FILTER where FILTER_ID='$FILTER_ID'";
	 $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
      $FILTER_COUNT++;
    
    	$FILTER_ID=$ROW["FILTER_ID"];
    	$USER_ID=$ROW["USER_ID"];
      $NEW_TIME=$ROW["NEW_TIME"];
      $EXPERT_ID=$ROW["EXPERT_ID"];
      $EMPLOYEE_NAME=$ROW["EMPLOYEE_NAME"];
      $PLAN_NO=$ROW["PLAN_NO"];    
      $POSITION=$ROW["POSITION"];
      $EMPLOYEE_MAJOR=$ROW["EMPLOYEE_MAJOR"];
      $EMPLOYEE_PHONE=$ROW["EMPLOYEE_PHONE"];
      $TRANSACTOR_STEP=$ROW["TRANSACTOR_STEP"];
      $STEP_FLAG=$ROW["STEP_FLAG"];
      $END_FLAG=$ROW["END_FLAG"];
      $NEXT_DATE_TIME=$ROW["NEXT_DATE_TIME"];
      $NEXT_TRANSA_STEP=$ROW["NEXT_TRANSA_STEP"];
      
      $FILTER_METHOD1=$ROW["FILTER_METHOD1"];
      $FILTER_DATE_TIME1=$ROW["FILTER_DATE_TIME1"];
      $FIRST_CONTENT1=$ROW["FIRST_CONTENT1"];
      $FIRST_VIEW1=$ROW["FIRST_VIEW1"];
      $TRANSACTOR_STEP1=$ROW["TRANSACTOR_STEP1"];
      $PASS_OR_NOT1=$ROW["PASS_OR_NOT1"];
      $NEXT_TRANSA_STEP1=$ROW["NEXT_TRANSA_STEP1"];
      $NEXT_DATE_TIME1=$ROW["NEXT_DATE_TIME1"];      
      
      $FILTER_METHOD2=$ROW["FILTER_METHOD2"];
      $FILTER_DATE_TIME2=$ROW["FILTER_DATE_TIME2"];
      $FIRST_CONTENT2=$ROW["FIRST_CONTENT2"];
      $FIRST_VIEW2=$ROW["FIRST_VIEW2"];
      $TRANSACTOR_STEP2=$ROW["TRANSACTOR_STEP2"];
      $PASS_OR_NOT2=$ROW["PASS_OR_NOT2"];
      $NEXT_TRANSA_STEP2=$ROW["NEXT_TRANSA_STEP2"];
      $NEXT_DATE_TIME2=$ROW["NEXT_DATE_TIME2"];
      
      $FILTER_METHOD3=$ROW["FILTER_METHOD3"];
      $FILTER_DATE_TIME3=$ROW["FILTER_DATE_TIME3"];
      $FIRST_CONTENT3=$ROW["FIRST_CONTENT3"];
      $FIRST_VIEW3=$ROW["FIRST_VIEW3"];
      $TRANSACTOR_STEP3=$ROW["TRANSACTOR_STEP3"];
      $PASS_OR_NOT3=$ROW["PASS_OR_NOT3"];
      $NEXT_TRANSA_STEP3=$ROW["NEXT_TRANSA_STEP3"];
      $NEXT_DATE_TIME3=$ROW["NEXT_DATE_TIME3"];
      
      $FILTER_METHOD4=$ROW["FILTER_METHOD4"];
      $FILTER_DATE_TIME4=$ROW["FILTER_DATE_TIME4"];
      $FIRST_CONTENT4=$ROW["FIRST_CONTENT4"];
      $FIRST_VIEW4=$ROW["FIRST_VIEW4"];
      $TRANSACTOR_STEP4=$ROW["TRANSACTOR_STEP4"];
      $PASS_OR_NOT4=$ROW["PASS_OR_NOT4"];
      $NEXT_TRANSA_STEP4=$ROW["NEXT_TRANSA_STEP4"];
      
     
      $query1 = "SELECT PLAN_NAME from HR_RECRUIT_PLAN where PLAN_NO='$PLAN_NO'";
      $cursor1= exequery(TD::conn(),$query1);
      if($ROW1=mysql_fetch_array($cursor1))
         $PLAN_NAME=$ROW1["PLAN_NAME"]; 
         
      $NEXT_TRANSA_STEP_NAME=substr(GetUserNameById($NEXT_TRANSA_STEP),0,-1);
      $TRANSACTOR_STEP_NAME=substr(GetUserNameById($TRANSACTOR_STEP),0,-1);  
      
      
      $NEXT_TRANSA_STEP1_NAME=substr(GetUserNameById($NEXT_TRANSA_STEP1),0,-1);
      $TRANSACTOR_STEP1_NAME=substr(GetUserNameById($TRANSACTOR_STEP1),0,-1); 
      $FILTER_METHOD1=get_hrms_code_name($FILTER_METHOD1,"HR_RECRUIT_FILTER"); 
           
      $NEXT_TRANSA_STEP2_NAME=substr(GetUserNameById($NEXT_TRANSA_STEP2),0,-1);
      $TRANSACTOR_STEP2_NAME=substr(GetUserNameById($TRANSACTOR_STEP2),0,-1); 
      $FILTER_METHOD2=get_hrms_code_name($FILTER_METHOD2,"HR_RECRUIT_FILTER"); 
      
      $NEXT_TRANSA_STEP3_NAME=substr(GetUserNameById($NEXT_TRANSA_STEP3),0,-1);
      $TRANSACTOR_STEP3_NAME=substr(GetUserNameById($TRANSACTOR_STEP3),0,-1); 
      $FILTER_METHOD3=get_hrms_code_name($FILTER_METHOD3,"HR_RECRUIT_FILTER"); 
      
      $TRANSACTOR_STEP4_NAME=substr(GetUserNameById($TRANSACTOR_STEP4),0,-1); 
      $FILTER_METHOD4=get_hrms_code_name($FILTER_METHOD4,"HR_RECRUIT_FILTER");
      $USER_ID=substr(GetUserNameById($USER_ID),0,-1);
    }

$HTML_PAGE_TITLE = _("招聘详细信息");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>

<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" width="17" height="17"><span class="big3"> <?=_("招聘筛选详细信息")?></span><br></td>
  </tr>
</table>
<table class="TableBlock" width="90%" align="center">
  <tr>
   <td nowrap class="TableContent" colspan="4" align="center"><?=_("基本信息")?><?if($END_FLAG==2) echo "<div style='color:red'>("._("已通过筛选").")</div>"; else if($END_FLAG==1) echo "<div style='color:red'>("._("未通过筛选").")</div>"; else echo "<div style='color:red'>("._("待筛选").")</div>"; ?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("应聘者姓名：")?></td>
    <td class="TableData" ><?=$EMPLOYEE_NAME?></td>
    <input type="hidden" name="EMPLOYEE_NAME" value="<?=$EMPLOYEE_NAME?>">
   <td nowrap align="left" width="120" class="TableContent"><?=_("计划名称：")?></td>
    <td class="TableData"><?=$PLAN_NAME?></td>  
    <input type="hidden" name="PLAN_NAME" value="<?=$PLAN_NAME?>">
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("应聘岗位：")?></td>
    <td class="TableData" ><?=$POSITION?></td> 
    <input type="hidden" name="POSITION" value="<?=$POSITION?>">
    <td nowrap align="left" width="120" class="TableContent"><?=_("所学专业：")?></td>
    <td class="TableData" ><?=$EMPLOYEE_MAJOR?></td>
    <input type="hidden" name="EMPLOYEE_MAJOR" value="<?=$EMPLOYEE_MAJOR?>">    
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("联系电话：")?></td>
    <td class="TableData"><?=$EMPLOYEE_PHONE?></td>
    <input type="hidden" name="EMPLOYEE_PHONE" value="<?=$EMPLOYEE_PHONE?>">
    <td nowrap align="left" width="120" class="TableContent"><?=_("发起人：")?></td>
    <td class="TableData" ><?=$TRANSACTOR_STEP_NAME?></td> 
    <input type="hidden" name="TRANSACTOR_STEP" value="<?=$TRANSACTOR_STEP?>">     
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("下一次筛选办理人：")?></td>
    <td class="TableData"><?=$NEXT_TRANSA_STEP_NAME?>    </td>
    <input type="hidden" name="$NEXT_TRANSA_STEP" value="<?=$$NEXT_TRANSA_STEP?>">
    <td nowrap align="left" width="120" class="TableContent"><?=_("下一次筛选时间：")?></td>
    <td class="TableData"><?=$NEXT_DATE_TIME=="0000-00-00 00:00:00"?"":$NEXT_DATE_TIME?>    </td> 
    <input type="hidden" name="NEXT_DATE_TIME" value="<?=$ONE_DATE_TIME?>">   
  </tr>
</table>
<?
if($STEP_FLAG >=2)
{
?>
<br>
<table class="TableBlock" width="90%" align="center">
  <tr>
    <td nowrap class="TableContent" colspan="4" align="center" ><?=_("筛选步骤一")?></td>
  </tr>
 <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("初选时间：")?></td>
    <td class="TableData"><?=$FILTER_DATE_TIME1?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("初选方式：")?></td>
    <td class="TableData" ><?=$FILTER_METHOD1?> </td> 
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("初选内容：")?></td>
    <td class="TableData" colspan=3><?=$FIRST_CONTENT1?> </td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("初选意见：")?></td>
    <td class="TableData" colspan=3><?=$FIRST_VIEW1?></td>
  </tr>  
  <tr>
  	<td nowrap align="left" width="120" class="TableContent"><?=_("初选办理人：")?></td>
    <td class="TableData" ><?=$NEXT_TRANSA_STEP_NAME?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("是否通过：")?></td>
    <td class="TableData"><?if($PASS_OR_NOT1==1) echo _("通过");else echo _("未通过");?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("下一步骤办理人：")?></td>
    <td class="TableData"><?=$NEXT_TRANSA_STEP1_NAME?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("下一次筛选时间：")?></td>
    <td class="TableData"><?=$NEXT_DATE_TIME1=="0000-00-00 00:00:00"?"":$NEXT_DATE_TIME1?> </td>
  </tr>
</table>
<?
}
if($STEP_FLAG >=3)
{
?>
<br>
<table class="TableBlock" width="90%" align="center">
  <tr>
    <td nowrap class="TableContent" colspan="4" align="center" ><?=_("筛选步骤二")?></td>
  </tr>
 <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("复选时间：")?></td>
    <td class="TableData"><?=$FILTER_DATE_TIME2?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("复选方式：")?></td>
    <td class="TableData" ><?=$FILTER_METHOD2?> </td> 
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("复选内容：")?></td>
    <td class="TableData" colspan=3><?=$FIRST_CONTENT2?> </td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("复选意见：")?></td>
    <td class="TableData" colspan=3><?=$FIRST_VIEW2?></td>
  </tr>  
  <tr>
  	<td nowrap align="left" width="120" class="TableContent"><?=_("复选办理人：")?></td>
    <td class="TableData" ><?=$NEXT_TRANSA_STEP1_NAME?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("是否通过：")?></td>
    <td class="TableData"><?if($PASS_OR_NOT2==1) echo _("通过");else echo _("未通过");?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("下一步骤办理人：")?></td>
    <td class="TableData"><?=$NEXT_TRANSA_STEP2_NAME?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("下一次筛选时间：")?></td>
    <td class="TableData"><?=$NEXT_DATE_TIME2=="0000-00-00 00:00:00"?"":$NEXT_DATE_TIME2?> </td>
  </tr>
</table>

<?	
}
if($STEP_FLAG >=4)
{
?>
<br>
<table class="TableBlock" width="90%" align="center">
  <tr>
    <td nowrap class="TableContent" colspan="4" align="center" ><?=_("筛选步骤三")?></td>
  </tr>
 <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("决选时间：")?></td>
    <td class="TableData"><?=$FILTER_DATE_TIME3?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("决选方式：")?></td>
    <td class="TableData" ><?=$FILTER_METHOD3?> </td> 
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("决选内容：")?></td>
    <td class="TableData" colspan=3><?=$FIRST_CONTENT3?> </td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("决选意见：")?></td>
    <td class="TableData" colspan=3><?=$FIRST_VIEW3?></td>
  </tr>  
  <tr>
  	<td nowrap align="left" width="120" class="TableContent"><?=_("决选办理人：")?></td>
    <td class="TableData" ><?=$TRANSACTOR_STEP2_NAME?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("是否通过：")?></td>
    <td class="TableData"><?if($PASS_OR_NOT3==1) echo _("通过");else echo _("未通过");?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("下一步骤办理人：")?></td>
    <td class="TableData"><?=$NEXT_TRANSA_STEP3_NAME?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("下一次筛选时间：")?></td>
    <td class="TableData"><?=$NEXT_DATE_TIME3=="0000-00-00 00:00:00"?"":$NEXT_DATE_TIME3?> </td>
  </tr>
</table>

<? }if($STEP_FLAG >=5){?>
<br>
<table class="TableBlock" width="90%" align="center">  
	<tr>
    <td nowrap class="TableContent" colspan="4" align="center" ><?=_("筛选步骤四")?></td>
  </tr>
 <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("加试时间：")?></td>
    <td class="TableData"><?=$FILTER_DATE_TIME4?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("加试方式：")?></td>
    <td class="TableData" ><?=$FILTER_METHOD4?> </td> 
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("加试内容：")?></td>
    <td class="TableData" colspan=3><?=$FIRST_CONTENT4?> </td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("加试意见：")?></td>
    <td class="TableData" colspan=3><?=$FIRST_VIEW4?></td>
  </tr>  
  <tr>
  	<td nowrap align="left" width="120" class="TableContent"><?=_("加试办理人：")?></td>
    <td class="TableData" ><?=$TRANSACTOR_STEP3_NAME?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("是否通过：")?></td>
    <td class="TableData"><?if($PASS_OR_NOT4==1) echo _("通过");else echo _("未通过");?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("下一步骤办理人：")?></td>
    <td class="TableData"><?=$NEXT_TRANSA_STEP4_NAME?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("下一次筛选时间：")?></td>
    <td class="TableData"><?=$NEXT_DATE_TIME4=="0000-00-00 00:00:00"?"":$NEXT_DATE_TIME4?> </td>
  </tr>
</table>

<?
}
?>
<table class="TableBlock" width="90%" align="center">
    <tr align="center" class="TableControl">
      <td colspan=4 nowrap>
        <input type="button" value="<?=_("关闭")?>" class="BigButton" onclick="window.close();" title="<?=_("关闭窗口")?>">
      </td>
    </tr>
  </table>
</body>
</html>