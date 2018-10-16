<?
include_once("inc/auth.inc.php");
include_once("inc/editor.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_field.php");
include_once("inc/utility_file.php");
$CUR_DATE=date("Y-m-d",time());
$query="select * from HR_STAFF_INFO where USER_ID='$USER_ID'";
$cursor= exequery(TD::conn(),$query);
$ROW1=mysql_num_rows($cursor);

if($ROW=mysql_fetch_array($cursor))
{
   $USER_ID=$ROW["USER_ID"];
   $DEPT_ID=$ROW["DEPT_ID"];
   if($DEPT_ID!=0)
      $DEPT_NAME=substr(GetDeptNameById($DEPT_ID),0,-1);
   else
      $DEPT_NAME=_("离职人员");
   $STAFF_NO=$ROW["STAFF_NO"];
   $WORK_NO=$ROW["WORK_NO"];
   $WORK_TYPE=$ROW["WORK_TYPE"];
   $STAFF_NAME=$ROW["STAFF_NAME"];
   $BEFORE_NAME=$ROW["BEFORE_NAME"];
   $STAFF_E_NAME=$ROW["STAFF_E_NAME"];
   $STAFF_CARD_NO=$ROW["STAFF_CARD_NO"];
   $STAFF_SEX=$ROW["STAFF_SEX"]==0?0:1;
   $STAFF_BIRTH=$ROW["STAFF_BIRTH"];
   //$STAFF_AGE=$ROW["STAFF_AGE"];
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
	 $WORK_LEVEL=$ROW["WORK_LEVEL"];
	 $WORK_JOB=$ROW["WORK_JOB"];

		$STAFF_NATIVE_PLACE_NAME=get_hrms_code_name($STAFF_NATIVE_PLACE,"AREA");
		$STAFF_POLITICAL_STATUS_NAME=get_hrms_code_name($STAFF_POLITICAL_STATUS,"STAFF_POLITICAL_STATUS");
		$WORK_STATUS_NAME=get_hrms_code_name($WORK_STATUS,"WORK_STATUS");
		$STAFF_HIGHEST_SCHOOL_NAME=get_hrms_code_name($STAFF_HIGHEST_SCHOOL,"STAFF_HIGHEST_SCHOOL");
		$STAFF_HIGHEST_DEGREE_NAME=get_hrms_code_name($STAFF_HIGHEST_DEGREE,"EMPLOYEE_HIGHEST_DEGREE");
		$PRESENT_POSITION_NAME=get_hrms_code_name($PRESENT_POSITION,"PRESENT_POSITION");
		$STAFF_OCCUPATION_NAME=get_hrms_code_name($STAFF_OCCUPATION,"STAFF_OCCUPATION");
		$STAFF_TYPE=get_hrms_code_name($STAFF_TYPE,"HR_STAFF_TYPE");
		$WORK_STATUS=get_hrms_code_name($WORK_STATUS,"WORK_STATUS");
		$WORK_LEVEL=get_hrms_code_name($WORK_LEVEL,"WORK_LEVEL");
		$WORK_JOB=get_hrms_code_name($WORK_JOB,"POOL_POSITION");
}
//---------查看相关信息是否存在----------
$query = "SELECT * from  HR_STAFF_CONTRACT where STAFF_NAME='$USER_ID'";
$cursor= exequery(TD::conn(),$query);
$INFO_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $INFO_COUNT++;
}
$query = "SELECT * from HR_STAFF_INCENTIVE where STAFF_NAME='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $INFO_COUNT++;
}
$query = "SELECT * from  HR_STAFF_LICENSE where STAFF_NAME='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $INFO_COUNT++;
}
$query = "SELECT * from HR_STAFF_LEARN_EXPERIENCE where STAFF_NAME='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $INFO_COUNT++;
}
$query = "SELECT * from HR_STAFF_WORK_EXPERIENCE where STAFF_NAME='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $INFO_COUNT++;
}
$query = "SELECT * from HR_STAFF_LABOR_SKILLS where STAFF_NAME='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $INFO_COUNT++;
}
$query = "SELECT * from HR_STAFF_RELATIVES where STAFF_NAME ='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $INFO_COUNT++;
}
$query = "SELECT * from HR_STAFF_TRANSFER where TRANSFER_PERSON='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $INFO_COUNT++;
}
$query = "SELECT * from HR_STAFF_LEAVE where LEAVE_PERSON='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $INFO_COUNT++;
}
$query = "SELECT * from HR_STAFF_REINSTATEMENT where REINSTATEMENT_PERSON='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $INFO_COUNT++;
}
$query = "SELECT * from HR_STAFF_TITLE_EVALUATION where BY_EVALU_STAFFS='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $INFO_COUNT++;
}
$query = "SELECT * from HR_STAFF_CARE where BY_CARE_STAFFS='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $INFO_COUNT++;
}

$HTML_PAGE_TITLE = _("人事档案");
include_once("inc/header.inc.php");
?>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script>
function show(targetid,INFO_COUNT)
{
     if(document.getElementById)
     {
        var target1=document.getElementById(targetid);
        if(target1.style.display!="block")
        	  target1.style.display="block";
        else
        	  target1.style.display="none";

//        var target2=document.getElementById("back_ground");
//        if(target2.style.display!="block")
//        	  target2.style.display="block";
//        else
//        	  target2.style.display="none";
//        var bb=document.body;
//        target2.style.width = bb.scrollWidth+"px";
//        target2.style.height = bb.scrollHeight+"px";
     }
}
function view_item(USER_ID)
{
  URL="use_item.php?USER_ID="+USER_ID;
  myleft=(screen.availWidth-500)/2;
  window.open(URL,"items","height=360,width=500,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
</script>
<style media="print" type="text/css">
.Noprint{display:none;}
.PageNext{page-break-after: always;}
</style>
<body class="bodycolor print">
<?

if($ROW1==0)
{
   Message(_("提示"),_("该用户未建档"));
   exit;
}

?>
<table border="0" width="770" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td>
    	  <img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=sprintf(_("人员档案（%s）"), substr(GetUserNameById($USER_ID),0,-1)==""?_("用户已删"):substr(GetUserNameById($USER_ID),0,-1))?></span>&nbsp;&nbsp;
        <a href="javascript:show('other_info','<?=$INFO_COUNT?>');"><?=_("相关信息")?></a>&nbsp;&nbsp;
        <a href="javascript:view_item('<?=$USER_ID?>')"><?=_("查看领用物品")?></a>
    </td>
  </tr>
</table>
<div id="other_info" name="other_info_name"style="display: none;clear: both;position:absolute;top:60px;left:60px;right:40px;border:solid 1px black;z-index:2;">
	<iframe ID="other_info_iframe" name="iframe_staff" frameborder=0 scrolling=no src="./other_info/?USER_ID=<?=urlencode($USER_ID)?>" width="100%" height="350"></iframe>
</div>
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
<div class="avatar"><a href="javascript:open_pic('<?=$AVATAR?>')"><img src="<?=urldecode($AVATAR)?>"  width="130" style="max-width:130px;max-height:200px;"></a></div>
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
    <td nowrap align="left" class="TableData" ><?=$STAFF_NO?></td>
    <td nowrap align="left" class="TableContent"><?=_("工号：")?></td>
    <td class="TableData"  colspan="2"><?=$WORK_NO?></td>
  </tr>
  <tr>
  	<td nowrap align="left" class="TableContent"><?=_("姓名：")?></td>
    <td class="TableData" ><?=$STAFF_NAME?></td>
    <td nowrap align="left" class="TableContent"><?=_("曾用名：")?></td>
    <td class="TableData"  colspan="2"><?=$BEFORE_NAME?></td>
  </tr>
  <tr>
  <td nowrap align="left" class="TableContent"><?=_("英文名：")?></td>
  <td class="TableData"><?=$STAFF_E_NAME?></td>
   <td nowrap align="left" class="TableContent"><?=_("性别：")?></td>
    <td class="TableData"  colspan="2"><? if($STAFF_SEX=="0") echo _("男");else echo _("女");?></td>
  </tr>
  <tr>
  	<td nowrap align="left" class="TableContent"><?=_("身份证号：")?></td>
    <td class="TableData"  ><?=$STAFF_CARD_NO?></td>
    <td nowrap align="left" class="TableContent"><?=_("出生日期：")?></td>
    <td class="TableData"  colspan="2"><?=$STAFF_BIRTH=="0000-00-00"?"":$STAFF_BIRTH?> </td>
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
	$STAFF_AGE=$STAFF_AGE-1;
  $query10="update HR_STAFF_INFO set STAFF_AGE='$STAFF_AGE' where USER_ID='$USER_ID'";
  exequery(TD::conn(),$query10);
}
?>
  <tr>
    <td nowrap align="left" class="TableContent"><?=_("年龄：")?></td>
    <td class="TableData" ><?=$STAFF_AGE?></td>
    <td nowrap align="left" class="TableContent"><?=_("婚姻状况：")?></td>
    <td class="TableData" colspan="3" >
        <? if($STAFF_MARITAL_STATUS=="0") echo _("未婚");?>
        <? if($STAFF_MARITAL_STATUS=="1") echo _("已婚");?>
        <? if($STAFF_MARITAL_STATUS=="2") echo _("离异");?>
        <? if($STAFF_MARITAL_STATUS=="3") echo _("丧偶");?>
    </td>
  </tr>
  <tr>
    <td nowrap align="left" class="TableContent"><?=_("籍贯：")?></td>
    <td class="TableData"  ><?=$STAFF_NATIVE_PLACE_NAME?><?=$STAFF_NATIVE_PLACE2?></td>
    <td nowrap align="left" class="TableContent"><?=_("民族：")?></td>
    <td class="TableData"  colspan="3"><?=$STAFF_NATIONALITY?></td>
  </tr>
  <tr>

    <td nowrap align="left" class="TableContent"><?=_("健康状况：")?></td>
    <td class="TableData" ><?=$STAFF_HEALTH?></td>
    <td nowrap align="left" class="TableContent"><?=_("年休假：")?></td>
    <td class="TableData" colspan="3"><?=$LEAVE_TYPE?></td>
  <tr>
    <td nowrap align="left" class="TableContent"><?=_("政治面貌：")?></td>
    <td class="TableData" ><?=$STAFF_POLITICAL_STATUS_NAME?></td>
    <td nowrap align="left" class="TableContent"><?=_("入党时间：")?></td>
    <td class="TableData"   colspan="3"><?=$JOIN_PARTY_TIME=="0000-00-00"?"":$JOIN_PARTY_TIME?></td>
  </tr>
  <tr>
  	<td nowrap align="left" class="TableContent"><?=_("户口类别：")?></td>
    <td class="TableData"  ><?=$STAFF_TYPE?></td>
    <td nowrap align="left" class="TableContent"><?=_("户口所在地：")?></td>
    <td class="TableData"   colspan="3"><?=$STAFF_DOMICILE_PLACE?></td>
  </tr>
  <tr>
    <td nowrap class="TableHeader" colspan="6"><b>&nbsp;<?=_("职位情况及联系方式：")?></b></td>
  </tr>
  <tr>
  	<td nowrap align="left" class="TableContent"><?=_("工种：")?></td>
    <td class="TableData"  ><?=$WORK_TYPE?></td>
    <td nowrap align="left" class="TableContent"><?=_("行政级别：")?></td>
    <td class="TableData" style=" width: 15%"><?=$ADMINISTRATION_LEVEL?></td>
    <td nowrap align="left" class="TableContent"><?=_("员工类型：")?></td>
    <td class="TableData"><?=$STAFF_OCCUPATION_NAME?> </td>
  </tr>
  <tr>
    <td nowrap align="left" class="TableContent"><?=_("职务：")?></td>
    <td class="TableData"  ><?=$JOB_POSITION?></td>
    <td nowrap align="left" class="TableContent"><?=_("职称：")?></td>
    <td class="TableData"  ><?=$PRESENT_POSITION_NAME?></td>
    <td nowrap align="left" class="TableContent"><?=_("入职时间：")?></td>
    <td class="TableData"  ><?=$DATES_EMPLOYED=="0000-00-00"?"":$DATES_EMPLOYED?></td>
  </tr>
  <tr>
    <td nowrap align="left" class="TableContent"><?=_("职称级别：")?></td>
    <td class="TableData"><?=$WORK_LEVEL?></td>
    <td nowrap align="left" class="TableContent"><?=_("岗位：")?></td>
    <td class="TableData" colspan="3"><?=$WORK_JOB?></td>
  </tr>
  <tr>
    <td nowrap align="left" class="TableContent"><?=_("本单位工龄：")?></td>
    <td class="TableData"  ><?=$JOB_AGE?></td>
    <td nowrap align="left" class="TableContent"><?=_("起薪时间：")?></td>
    <td class="TableData"  ><?=$BEGIN_SALSRY_TIME=="0000-00-00"?"":$BEGIN_SALSRY_TIME?></td>
    <td nowrap align="left" class="TableContent"><?=_("在职状态：")?></td>
    <td class="TableData"  ><?=$WORK_STATUS?></td>
  </tr>
  <tr>
    <td nowrap align="left" class="TableContent"><?=_("总工龄：")?></td>
    <td class="TableData"  ><?=$WORK_AGE?></td>
    <td nowrap align="left" class="TableContent"><?=_("参加工作时间：")?></td>
    <td class="TableData"  ><?=$JOB_BEGINNING=="0000-00-00"?"":$JOB_BEGINNING?></td>
    <td nowrap align="left" class="TableContent"><?=_("联系电话：")?></td>
    <td class="TableData"  ><?=$STAFF_PHONE?></td>
  </tr>
  <tr>
    <td nowrap align="left" class="TableContent"><?=_("手机号码：")?></td>
    <td class="TableData"   ><?=$STAFF_MOBILE?></td>
    <!--<td nowrap align="left" class="TableContent"><?=_("小灵通：")?></td>
    <td class="TableData"  ><?=$STAFF_LITTLE_SMART?></td>-->
    <td nowrap align="left" class="TableContent"><?=_("MSN：")?></td>
    <td class="TableData"  colspan="3" ><?=$STAFF_MSN?></td>
  </tr>
<tr>
    <td nowrap align="left" class="TableContent"><?=_("电子邮件：")?></td>
    <td class="TableData"  ><?=$STAFF_EMAIL?></td>
    <td nowrap align="left" class="TableContent"><?=_("家庭地址：")?></td>
    <td class="TableData"   colspan="3"><?=$HOME_ADDRESS?></td>
  </tr>
  <tr>
    <td nowrap align="left" class="TableContent"><?=_("QQ：")?></td>
    <td class="TableData"  ><?=$STAFF_QQ?></td>
    <td nowrap align="left" class="TableContent"><?=_("其他联系方式：")?></td>
    <td class="TableData"   colspan="3"><?=$OTHER_CONTACT?></td>
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
    <td class="TableData"  ><?=$STAFF_HIGHEST_SCHOOL_NAME?></td>
    <td nowrap align="left" class="TableContent"><?=_("学位：")?></td>
    <td class="TableData"  ><?=$STAFF_HIGHEST_DEGREE_NAME?></td>
    <td nowrap align="left" class="TableContent"><?=_("毕业时间：")?></td>
    <td class="TableData"  > <?=$GRADUATION_DATE=="0000-00-00"?"":$GRADUATION_DATE?> </td>
  </tr>
  <tr>
    <td nowrap align="left" class="TableContent"><?=_("毕业学校：")?></td>
    <td class="TableData"  ><?=$GRADUATION_SCHOOL?></td>
    <td nowrap align="left" class="TableContent"><?=_("专业：")?></td>
    <td class="TableData"  ><?=$STAFF_MAJOR?></td>
    <td nowrap align="left" class="TableContent"><?=_("计算机水平：")?></td>
    <td class="TableData"  ><?=$COMPUTER_LEVEL?></td>
  </tr>
  <tr>
    <td nowrap align="left" class="TableContent"><?=_("外语语种1：")?></td>
    <td class="TableData"  ><?=$FOREIGN_LANGUAGE1?></td>
    <td nowrap align="left" class="TableContent"><?=_("外语语种2：")?></td>
    <td class="TableData"  ><?=$FOREIGN_LANGUAGE2?></td>
    <td nowrap align="left" class="TableContent"><?=_("外语语种3：")?></td>
    <td class="TableData"  ><?=$FOREIGN_LANGUAGE3?></td>
  </tr>
  <tr>
    <td nowrap align="left" class="TableContent"><?=_("外语水平1：")?></td>
    <td class="TableData"  ><?=$FOREIGN_LEVEL1?></td>
    <td nowrap align="left" class="TableContent"><?=_("外语水平2：")?></td>
    <td class="TableData"  ><?=$FOREIGN_LEVEL2?></td>
    <td nowrap align="left" class="TableContent"><?=_("外语水平3：")?></td>
    <td class="TableData"  ><?=$FOREIGN_LEVEL3?></td>
  </tr>
  <tr>
    <td nowrap align="left" class="TableContent"><?=_("特长：")?></td>
    <td class="TableData"   colspan="5"><?=$STAFF_SKILLS?></td>
  </tr>
  <tr>
    <td nowrap align="left" colspan="3" class="TableHeader"><?=_("职务情况：")?></td>
    <td nowrap align="left" colspan="3" class="TableHeader"><?=_("担保记录：")?></td>
  </tr>
  <tr>
    <td class="TableData" colspan="3" style="vertical-align:top;"><?=$CERTIFICATE?></td>
    <td class="TableData" colspan="3" style="vertical-align:top;"><?=$SURETY?></td>
  </tr>
  <tr>
  	<td nowrap class="TableHeader" colspan="3"><b><?=_("社保缴纳情况：")?></b></td>
  	<td nowrap class="TableHeader" colspan="3"><b><?=_("体检记录：")?></b></td>
  </tr>
  <tr>
    <td class="TableData" colspan="3" style="vertical-align:top;"><?=$INSURE?></td>
    <td class="TableData" colspan="3" style="vertical-align:top;"><?=$BODY_EXAMIM?></td>
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
    <td nowrap class="TableData" colspan="6"><?=$REMARK==""?_("未填写"):$REMARK?></td>
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
    <td nowrap class="TableData" colspan="6" style="vertical-align:top;"><?=$RESUME==""?_("未填写"):$RESUME?></td>
  </tr>
</table>
	<OBJECT classid="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2" height=0 id=WebBrowser name=WebBrowser width=0></OBJECT>

<br>
<br>

<center><input type= "button"   name= "Button"  class="SmallButton" value= "<?=_("打印预览")?>" onClick= "print_view()"> <input type="button" value="<?=_("打印")?>" class="SmallButton" onClick="window.print()"></center>
<script language="javascript">
function print_view()
{
	if(self.frames.name!="")
	{
		 var url=self.location.href;
		 window.open(url,"","toolbar=no, menubar=no, scrollbars=yes, resizable=yes, location=no, status=no");
	}
	else
	{
     window.document.all.WebBrowser.ExecWB(7,1);
	}
}
function open_pic(AVATAR)
{
	url=AVATAR;
	window.open(url,"<?=sprintf(_("%s的头像"), $STAFF_NAME)?>","toolbar=0,status=0,menubar=0,scrollbars=yes,resizable=1")
}
</script>
</body>
</html>