<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_field.php");
include_once("inc/utility_file.php");
include_once("inc/lunar.class.php");

$query="select * from HR_STAFF_INFO where USER_ID='$USER_ID'";
$cursor= exequery(TD::conn(),$query);

$CUR_DATE=date("Y-m-d",time());
if($ROW=mysql_fetch_array($cursor))
{
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $DEPT_ID=$ROW["DEPT_ID"];
   if($DEPT_ID!=0)
      $DEPT_NAME=substr(GetDeptNameById($DEPT_ID),0,-1);
   else
      $DEPT_NAME=_("离职人员/外部人员");
   $STAFF_NO=$ROW["STAFF_NO"];
   $WORK_NO=$ROW["WORK_NO"];
   $WORK_TYPE=$ROW["WORK_TYPE"];
   $STAFF_NAME=$ROW["STAFF_NAME"];
   $BEFORE_NAME=$ROW["BEFORE_NAME"];
   $STAFF_E_NAME=$ROW["STAFF_E_NAME"];
   $STAFF_CARD_NO=$ROW["STAFF_CARD_NO"];   
   $STAFF_SEX=$ROW["STAFF_SEX"];
   $STAFF_BIRTH=$ROW["STAFF_BIRTH"];
   $BLOOD_TYPE=$ROW["BLOOD_TYPE"];
   $IS_LUNAR=$ROW["IS_LUNAR"];
//   $STAFF_AGE=$ROW["STAFF_AGE"];
   $STAFF_NATIVE_PLACE=$ROW["STAFF_NATIVE_PLACE"];
   $STAFF_NATIVE_PLACE2=$ROW["STAFF_NATIVE_PLACE2"];
   $STAFF_DOMICILE_PLACE=$ROW["STAFF_DOMICILE_PLACE"];
   $STAFF_NATIONALITY=$ROW["STAFF_NATIONALITY"];
   $STAFF_MARITAL_STATUS=$ROW["STAFF_MARITAL_STATUS"];
   $STAFF_POLITICAL_STATUS=$ROW["STAFF_POLITICAL_STATUS"];
   $HRMS_PHOTO=$ROW["PHOTO_NAME"];
   $COMPUTER_LEVEL=$ROW["COMPUTER_LEVEL"];   
   $JOIN_PARTY_TIME=$ROW["JOIN_PARTY_TIME"];
   $STAFF_PHONE=$ROW["STAFF_PHONE"];
   $STAFF_MOBILE=$ROW["STAFF_MOBILE"];
   $STAFF_LITTLE_SMART=$ROW["STAFF_LITTLE_SMART"];
   $STAFF_EMAIL=$ROW["STAFF_EMAIL"];   
   $STAFF_MSN=$ROW["STAFF_MSN"];
   $JOB_POSITION=$ROW["JOB_POSITION"];  
   $STAFF_QQ=$ROW["STAFF_QQ"];
   $HOME_ADDRESS=$ROW["HOME_ADDRESS"];
   $BANK1=$ROW["BANK1"];
   $BANK_ACCOUNT1=$ROW["BANK_ACCOUNT1"];
   $BANK2=$ROW["BANK2"];
   $BANK_ACCOUNT2=$ROW["BANK_ACCOUNT2"];
   $OTHER_CONTACT=$ROW["OTHER_CONTACT"];
   $JOB_BEGINNING=$ROW["JOB_BEGINNING"];
   $WORK_AGE=$ROW["WORK_AGE"];
   $BEGIN_SALSRY_TIME=$ROW["BEGIN_SALSRY_TIME"];
   $STAFF_HEALTH=$ROW["STAFF_HEALTH"];
   $STAFF_HIGHEST_SCHOOL=$ROW["STAFF_HIGHEST_SCHOOL"];
   $STAFF_HIGHEST_DEGREE=$ROW["STAFF_HIGHEST_DEGREE"];
   $GRADUATION_DATE=$ROW["GRADUATION_DATE"];
   $GRADUATION_SCHOOL=$ROW["GRADUATION_SCHOOL"];
   $STAFF_MAJOR=$ROW["STAFF_MAJOR"];
   $FOREIGN_LANGUAGE1=$ROW["FOREIGN_LANGUAGE1"];
   $FOREIGN_LEVEL1=$ROW["FOREIGN_LEVEL1"];
   $FOREIGN_LANGUAGE2=$ROW["FOREIGN_LANGUAGE2"];
   $FOREIGN_LEVEL2=$ROW["FOREIGN_LEVEL2"];
   $FOREIGN_LANGUAGE3=$ROW["FOREIGN_LANGUAGE3"];
   $FOREIGN_LEVEL3=$ROW["FOREIGN_LEVEL3"];
   $STAFF_SKILLS=$ROW["STAFF_SKILLS"];
   $STAFF_OCCUPATION=$ROW["STAFF_OCCUPATION"];
   $ADMINISTRATION_LEVEL=$ROW["ADMINISTRATION_LEVEL"];
   $PRESENT_POSITION=$ROW["PRESENT_POSITION"];
   $DATES_EMPLOYED=$ROW["DATES_EMPLOYED"];
   $JOB_AGE=$ROW["JOB_AGE"];
   $STAFF_CS=$ROW["STAFF_CS"];
   $WORK_STATUS=$ROW["WORK_STATUS"];
   $STAFF_CTR=$ROW["STAFF_CTR"];   
   $REMARK=$ROW["REMARK"];
   $STAFF_COMPANY=$ROW["STAFF_COMPANY"];
   $RESUME=$ROW["RESUME"];   
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"]; 	 
	 $CERTIFICATE=$ROW["CERTIFICATE"];
	 $SURETY=$ROW["SURETY"];
	 $BODY_EXAMIM=$ROW["BODY_EXAMIM"];
	 $INSURE=$ROW["INSURE"];	 
	 $USERDEF1=$ROW["USERDEF1"];
	 $USERDEF2=$ROW["USERDEF2"];
	 $USERDEF3=$ROW["USERDEF3"];
	 $USERDEF4=$ROW["USERDEF4"];
	 $USERDEF5=$ROW["USERDEF5"];
	 $STAFF_TYPE=$ROW["STAFF_TYPE"];
	 $LEAVE_TYPE=$ROW["LEAVE_TYPE"];
	 $WORK_STATUS=$ROW["WORK_STATUS"];	
	 $ADD_TIME=$ROW["ADD_TIME"];
	 $LAST_UPDATE_TIME=$ROW["LAST_UPDATE_TIME"];
	 
		$STAFF_NATIVE_PLACE_NAME=get_hrms_code_name($STAFF_NATIVE_PLACE,"AREA");
		$STAFF_POLITICAL_STATUS_NAME=get_hrms_code_name($STAFF_POLITICAL_STATUS,"STAFF_POLITICAL_STATUS");
		$WORK_STATUS_NAME=get_hrms_code_name($WORK_STATUS,"WORK_STATUS");
		$STAFF_HIGHEST_SCHOOL_NAME=get_hrms_code_name($STAFF_HIGHEST_SCHOOL,"STAFF_HIGHEST_SCHOOL");
		$STAFF_HIGHEST_DEGREE_NAME=get_hrms_code_name($STAFF_HIGHEST_DEGREE,"EMPLOYEE_HIGHEST_DEGREE");
		$PRESENT_POSITION_NAME=get_hrms_code_name($PRESENT_POSITION,"PRESENT_POSITION");
		$STAFF_OCCUPATION_NAME=get_hrms_code_name($STAFF_OCCUPATION,"STAFF_OCCUPATION");
		$STAFF_TYPE=get_hrms_code_name($STAFF_TYPE,"HR_STAFF_TYPE");
		$WORK_STATUS=get_hrms_code_name($WORK_STATUS,"WORK_STATUS");

	if($STAFF_BIRTH!="0000-00-00"){
		//从lunar.php中获取生肖
		$ANIMAL = get_animal($STAFF_BIRTH,$IS_LUNAR);
		//从lunar.php中获取星座
		$SIGN = get_zodiac_sign($STAFF_BIRTH,$IS_LUNAR);
    }
}

$HTML_PAGE_TITLE = _("人事档案详细信息");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script language="javascript">
function open_pic(AVATAR)
{
	url=AVATAR;
	window.open(url,"<?=$STAFF_NAME?><?=_("的头像")?>","toolbar=0,status=0,menubar=0,scrollbars=yes,resizable=1")
}
</script>

<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_manage.gif" width="17" height="17"><span class="big3"> <?=_("人事档案详细信息")?><? if($ADD_TIME=="0000-00-00 00:00:00" && $LAST_UPDATE_TIME=="0000-00-00 00:00:00") echo "";elseif($ADD_TIME!="0000-00-00 00:00:00" && $LAST_UPDATE_TIME=="0000-00-00 00:00:00") echo sprintf(_("（建档时间：%s）"), $ADD_TIME);elseif($ADD_TIME=="0000-00-00 00:00:00" && $LAST_UPDATE_TIME!="0000-00-00 00:00:00") echo sprintf(_("（最后修改时间：%s）"), $LAST_UPDATE_TIME);else echo sprintf(_("（建档时间：%s 最后修改时间：%s）"), $ADD_TIME, $LAST_UPDATE_TIME);?></span><br></td>
  </tr>
</table>
<?
$query = "SELECT PHOTO,BYNAME from USER where USER_ID='$USER_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	$PHOTO  = $ROW['PHOTO'];
	$BYNAME = $ROW['BYNAME'];
	
}
if($HRMS_PHOTO != "")
   $URL_ARRAY = attach_url_old('hrms_pic', urlencode($HRMS_PHOTO));
else if($PHOTO != "")
   $URL_ARRAY = attach_url_old('photo', $PHOTO);
else
{
   $AVATAR_PATH = MYOA_STATIC_SERVER."/static/images/avatar/".$STAFF_SEX.".png";
   $URL_ARRAY['view'] = $AVATAR_PATH;
   //$URL_ARRAY = attach_url_old('hrms_pic', "hrms_pic_".$STAFF_SEX.".png");
}
$AVATAR = $URL_ARRAY['view'];
?>
<table class="TableBlock" width="80%" align="center">
  <tr>
    <td nowrap class="TableHeader" colspan="6"><b>&nbsp;<?=_("基本信息")?></b></td>
  </tr>
  <tr>
    <td nowrap align="left" class="TableContent">OA<?=_("用户名：")?></td>
    <td class="TableData"  colspan="4"><?=$BYNAME?></td>
    <td class="TableData" align="center" rowspan="6" colspan="2">

<div class="avatar"><a href="javascript:open_pic('<?=$AVATAR?>')"><img src="<?=urldecode($AVATAR)?>" width="130" style="max-width:130px;max-height:200px;" ></a></div>
    </td>
  </tr>
<?
  $query9 = "SELECT PRIV_NAME from USER a left outer join USER_PRIV b on a.USER_PRIV=b.USER_PRIV where a.USER_ID='$USER_ID'";
  $cursor9= exequery(TD::conn(),$query9);
  if($ROW9=mysql_fetch_array($cursor9))
     $PRIV_NAME=$ROW9["PRIV_NAME"];
?>
  <tr>
    <td nowrap align="left" class="TableContent"><?=_("部门：")?></td>
    <td nowrap align="left" class="TableData" ><?=$DEPT_NAME?></td>
    <td nowrap align="left" class="TableContent"><?=_("角色：")?></td>
    <td class="TableData"  colspan="2"><?=$PRIV_NAME?></td>
  </tr>
  <tr>
  	<td nowrap align="left" class="TableContent"><?=_("编号：")?></td>
    <td nowrap align="left" class="TableData"><?=$STAFF_NO?></td>  
    <td nowrap align="left" class="TableContent"><?=_("工号：")?></td>
    <td class="TableData" colspan="2"><?=$WORK_NO?></td>          
  </tr>
  <tr>
  	<td nowrap align="left" class="TableContent"><?=_("姓名：")?></td>
    <td class="TableData"><?=$STAFF_NAME?></td>  
     <td nowrap align="left" class="TableContent"><?=_("曾用名：")?></td>
    <td class="TableData" colspan="2"><?=$BEFORE_NAME?></td>         
  </tr>	
  <tr>
    <td nowrap align="left" class="TableContent"><?=_("英文名：")?></td>
    <td class="TableData"><?=$STAFF_E_NAME?></td>  
    <td nowrap align="left" class="TableContent"><?=_("性别：")?></td>
    <td class="TableData" colspan="2"><? if($STAFF_SEX=="0") echo _("男");else if($STAFF_SEX=="1") echo _("女");else echo _("未填写");?></td>         
  </tr>
<?
if($STAFF_BIRTH!="0000-00-00" && $STAFF_BIRTH!="")
  {
  	$agearray = explode("-",$STAFF_BIRTH);
  	$cur = explode("-",$CUR_DATE);
  	$year=$agearray[0];
  	$STAFF_AGE=date("Y")-$year;
  	if($cur[1] > $agearray[1] || $cur[1]==$agearray[1] && $cur[2]>=$agearray[2])
  	{
  		$STAFF_AGE++;
  	}
  }
else
  {
  	$STAFF_AGE="";
  }
if($STAFF_AGE!="")
{
  $query10="update HR_STAFF_INFO set STAFF_AGE='$STAFF_AGE' where USER_ID='$USER_ID'";
  exequery(TD::conn(),$query10);
}
?>
  <tr>
  	<td nowrap align="left" class="TableContent"><?=_("身份证号：")?></td>
    <td class="TableData" ><?=$STAFF_CARD_NO?></td>  
    <td nowrap align="left" class="TableContent"><?=_("出生日期：")?></td>
    <td class="TableData" colspan="2"><?=$STAFF_BIRTH=="0000-00-00"?"":$STAFF_BIRTH;?> </td>             
  </tr>
  <tr>
    <td nowrap align="left" class="TableContent"><?=_("生肖")?></td>
    <td class="TableData"  ><?=$ANIMAL?></td>
    <td nowrap align="left" class="TableContent"><?=_("星座")?></td>
    <td class="TableData"  ><?=$SIGN?></td>
    <td nowrap align="left" class="TableContent"><?=_("血型")?></td>
    <td class="TableData"><?=$BLOOD_TYPE?> </td>
  </tr>
  <tr>
    <td nowrap align="left" class="TableContent"><?=_("年龄：")?></td>
    <td class="TableData"><?=$STAFF_AGE?></td>
    <td nowrap align="left" class="TableContent"><?=_("年休假：")?></td>
    <td class="TableData"  colspan="3"><?=$LEAVE_TYPE?></td>  
  </tr>
  <tr>
    <td nowrap align="left" class="TableContent"><?=_("籍贯：")?></td>
    <td class="TableData" ><?=$STAFF_NATIVE_PLACE_NAME?><?=$STAFF_NATIVE_PLACE2?></td>    
    <td nowrap align="left" class="TableContent"><?=_("民族：")?></td>
    <td class="TableData" colspan="3"><?=$STAFF_NATIONALITY?></td>            
  </tr>  
  <tr>
    <td nowrap align="left" class="TableContent"><?=_("婚姻状况：")?></td>
    <td class="TableData">
        <? if($STAFF_MARITAL_STATUS=="0") echo _("未婚");?>
        <? if($STAFF_MARITAL_STATUS=="1") echo _("已婚");?>
        <? if($STAFF_MARITAL_STATUS=="2") echo _("离异");?>
        <? if($STAFF_MARITAL_STATUS=="3") echo _("丧偶");?>
    </td>
    <td nowrap align="left" class="TableContent"><?=_("健康状况：")?></td>
    <td class="TableData"  colspan="3"><?=$STAFF_HEALTH?></td>
  <tr>
    <td nowrap align="left" class="TableContent"><?=_("政治面貌：")?></td>
    <td class="TableData"><?=$STAFF_POLITICAL_STATUS_NAME?></td>
    <td nowrap align="left" class="TableContent"><?=_("入党时间：")?></td>
    <td class="TableData"  colspan="3"><?=$JOIN_PARTY_TIME=="0000-00-00"?"":$JOIN_PARTY_TIME;?></td>
  </tr>
  <tr>
  	<td nowrap align="left" class="TableContent"><?=_("户口类别：")?></td>
    <td class="TableData" ><?=$STAFF_TYPE?></td>
    <td nowrap align="left" class="TableContent"><?=_("户口所在地：")?></td>
    <td class="TableData"  colspan="3"><?=$STAFF_DOMICILE_PLACE?></td> 
  </tr>
  <tr>
    <td nowrap class="TableHeader" colspan="6"><b>&nbsp;<?=_("职位情况及联系方式：")?></b></td>
  </tr>
  <tr>
  	<td nowrap align="left" class="TableContent"><?=_("工种：")?></td>
    <td class="TableData" ><?=$WORK_TYPE?></td>
    <td nowrap align="left" class="TableContent"><?=_("行政级别：")?></td>
    <td class="TableData" ><?=$ADMINISTRATION_LEVEL?></td>
    <td nowrap align="left" class="TableContent"><?=_("员工类型：")?></td>
    <td class="TableData"><?=$STAFF_OCCUPATION_NAME?> </td>        
  </tr>
  <tr>
    <td nowrap align="left" class="TableContent"><?=_("职务：")?></td>
    <td class="TableData" ><?=$JOB_POSITION?></td>
    <td nowrap align="left" class="TableContent"><?=_("职称：")?></td>
    <td class="TableData" ><?=$PRESENT_POSITION_NAME?></td>
    <td nowrap align="left" class="TableContent"><?=_("入职时间：")?></td>
    <td class="TableData" ><?=$DATES_EMPLOYED=="0000-00-00"?"":$DATES_EMPLOYED;?></td>                 
  </tr>
  <tr>
    <td nowrap align="left" class="TableContent"><?=_("本单位工龄：")?></td>
    <td class="TableData" ><?=$JOB_AGE?></td>
    <td nowrap align="left" class="TableContent"><?=_("起薪时间：")?></td>
    <td class="TableData" ><?=$BEGIN_SALSRY_TIME=="0000-00-00"?"":$BEGIN_SALSRY_TIME;?></td>
    <td nowrap align="left" class="TableContent"><?=_("在职状态：")?></td>
    <td class="TableData" ><?=$WORK_STATUS?></td>               
  </tr>
  <tr>
    <td nowrap align="left" class="TableContent"><?=_("总工龄：")?></td>
    <td class="TableData" ><?=$WORK_AGE?></td>
    <td nowrap align="left" class="TableContent"><?=_("参加工作时间：")?></td>
    <td class="TableData" ><?=$JOB_BEGINNING=="0000-00-00"?"":$JOB_BEGINNING;?></td>
    <td nowrap align="left" class="TableContent"><?=_("联系电话：")?></td>
    <td class="TableData" ><?=$STAFF_PHONE?></td>                
  </tr>     
  <tr>        
    <td nowrap align="left" class="TableContent"><?=_("手机号码：")?></td>
    <td class="TableData"  ><?=$STAFF_MOBILE?></td>
    <!--<td nowrap align="left" class="TableContent"><?=_("小灵通：")?></td>
    <td class="TableData" ><?=$STAFF_LITTLE_SMART?></td>-->
    <td nowrap align="left" class="TableContent"><?=_("MSN：")?></td>
    <td class="TableData"  colspan="3"><?=$STAFF_MSN?></td>                   
  </tr>
<tr>
    <td nowrap align="left" class="TableContent"><?=_("电子邮件：")?></td>
    <td class="TableData" ><?=$STAFF_EMAIL?></td>
    <td nowrap align="left" class="TableContent"><?=_("家庭地址：")?></td>
    <td class="TableData"  colspan="3"><?=$HOME_ADDRESS?></td>                
  </tr>  
  <tr>     
    <td nowrap align="left" class="TableContent"><?=_("QQ：")?></td>
    <td class="TableData" ><?=$STAFF_QQ?></td> 
    <td nowrap align="left" class="TableContent"><?=_("其他联系方式：")?></td>
    <td class="TableData"  colspan="3"><?=$OTHER_CONTACT?></td>                
  </tr>
    <tr>
    <td nowrap align="left" class="TableContent"><?=_("开户行1：")?></td>
    <td class="TableData"  width="180"><?=$BANK1?></td> 
    <td nowrap align="left" class="TableContent"><?=_("账户1：")?></td>
    <td class="TableData"  colspan="3"><?=$BANK_ACCOUNT1?></td>  
  </tr>
    <tr>
    <td nowrap align="left" class="TableContent"><?=_("开户行2：")?></td>
    <td class="TableData"  width="180"><?=$BANK2?></td>
    <td nowrap align="left" class="TableContent"><?=_("账户2：")?></td>
    <td class="TableData"  colspan="3"><?=$BANK_ACCOUNT2?></td>  
  </tr>
  <tr>
    <td nowrap class="TableHeader" colspan="6"><b>&nbsp;<?=_("教育背景：")?></b></td>
  </tr>              
  <tr>
    <td nowrap align="left" class="TableContent"><?=_("学历：")?></td>
    <td class="TableData" ><?=$STAFF_HIGHEST_SCHOOL_NAME?></td>
    <td nowrap align="left" class="TableContent"><?=_("学位：")?></td>
    <td class="TableData" ><?=$STAFF_HIGHEST_DEGREE_NAME?></td>
    <td nowrap align="left" class="TableContent"><?=_("毕业时间：")?></td>
    <td class="TableData" ><?=$GRADUATION_DATE=="0000-00-00"?"":$GRADUATION_DATE;?></td>                 
  </tr>       
  <tr>
    <td nowrap align="left" class="TableContent"><?=_("毕业学校：")?></td>
    <td class="TableData" ><?=$GRADUATION_SCHOOL?></td>
    <td nowrap align="left" class="TableContent"><?=_("专业：")?></td>
    <td class="TableData" ><?=$STAFF_MAJOR?></td>
    <td nowrap align="left" class="TableContent"><?=_("计算机水平：")?></td>
    <td class="TableData" ><?=$COMPUTER_LEVEL?></td>                 
  </tr>       
  <tr>
    <td nowrap align="left" class="TableContent"><?=_("外语语种1：")?></td>
    <td class="TableData" ><?=$FOREIGN_LANGUAGE1?></td>
    <td nowrap align="left" class="TableContent"><?=_("外语语种2：")?></td>
    <td class="TableData" ><?=$FOREIGN_LANGUAGE2?></td>
    <td nowrap align="left" class="TableContent"><?=_("外语语种3：")?></td>
    <td class="TableData" ><?=$FOREIGN_LANGUAGE3?></td>                 
  </tr>       
  <tr>
    <td nowrap align="left" class="TableContent"><?=_("外语水平1：")?></td>
    <td class="TableData" ><?=$FOREIGN_LEVEL1?></td>
    <td nowrap align="left" class="TableContent"><?=_("外语水平2：")?></td>
    <td class="TableData" ><?=$FOREIGN_LEVEL2?></td>
    <td nowrap align="left" class="TableContent"><?=_("外语水平3：")?></td>
    <td class="TableData" ><?=$FOREIGN_LEVEL3?></td>                 
  </tr>  
  <tr>
    <td nowrap align="left" class="TableContent"><?=_("特长：")?></td>
    <td class="TableData"  colspan="5"><?=$STAFF_SKILLS?></td>             
  </tr>
  <tr>
    <td nowrap align="left" colspan="3" class="TableHeader"><?=_("职务情况：")?></td>
    <td nowrap align="left" colspan="3" class="TableHeader"><?=_("担保记录：")?></td> 
  </tr>
  <tr>
    <td class="TableData" colspan="3"><?=$CERTIFICATE?></td>
    <td class="TableData" colspan="3"><?=$SURETY?></td>
  </tr>
  <tr>
  	<td nowrap class="TableHeader" colspan="3"><?=_("社保缴纳情况：")?></td>
  	<td nowrap class="TableHeader" colspan="3"><?=_("体检记录：")?></td>
  </tr>
  <tr>
    <td class="TableData" colspan="3"><?=$INSURE?></td>
    <td class="TableData" colspan="3"><?=$BODY_EXAMIM?></td>
  </tr>           
  <tr>       	
    <td colspan="6">
      <table width="100%" class="TableBlock">
        <tr>
           <td class="TableHeader" colspan="2"><?=_("自定义字段：")?></td>
        </tr>
       <?=get_hrm_table(get_field_text("HR_STAFF_INFO", $USER_ID))?>
      </table>    	    	
    </td>
  </tr>
  <tr>
    <td nowrap align="left" colspan="6" class="TableHeader"><?=_("备注：")?></td>
  </tr>   
  <tr>
    <td nowrap class="TableData" colspan="6" style="vertical-align:top;"><?=$REMARK==""?_("未填写"):$REMARK;?></td>               
  </tr>                                    
  <tr>
  	<td nowrap  class="TableHeader" colspan="6"><?=_("附件文档：")?></td>
  </tr>
  <tr>    
    <td nowrap align="left" class="TableData" colspan="6">
<?
    if($ATTACHMENT_ID=="")
       echo _("无附件");
    else
       echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,0,1,0);

?>
    </td>
  </tr>         
  <tr>
    <td nowrap align="left" class="TableHeader" colspan="6"><?=_("简历：")?></td>                
  </tr>       
  <tr>
    <td nowrap class="TableData" colspan="6" height="100" style="vertical-align:top;"><?=$RESUME==""?_("未填写"):$RESUME;?></td>                 
  </tr>
  <tr align="center" class="TableControl">
    <td colspan="6">
      <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close();" title="<?=_("关闭窗口")?>">
    </td>
  </tr>                     
</table>
</body>
</html>