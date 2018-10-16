<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("人才档案详细信息");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif" width="17" height="17"><span class="big3"> <?=_("人才档案详细信息")?></span><br>
    </td>
  </tr>
</table>
<?
$query = "SELECT * from HR_RECRUIT_POOL where EXPERT_ID='$EXPERT_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{	
  $PLAN_NO=$ROW["PLAN_NO"];   
  $POSITION =$ROW["POSITION"];
  $EMPLOYEE_NAME=$ROW["EMPLOYEE_NAME"];
  $EMPLOYEE_SEX=$ROW["EMPLOYEE_SEX"];
  $EMPLOYEE_BIRTH=$ROW["EMPLOYEE_BIRTH"];
  //$EMPLOYEE_AGE=$ROW["EMPLOYEE_AGE"];
  $EMPLOYEE_NATIVE_PLACE=$ROW["EMPLOYEE_NATIVE_PLACE"];
  $EMPLOYEE_DOMICILE_PLACE=$ROW["EMPLOYEE_DOMICILE_PLACE"];
  $EMPLOYEE_NATIONALITY=$ROW["EMPLOYEE_NATIONALITY"];
  $EMPLOYEE_MARITAL_STATUS=$ROW["EMPLOYEE_MARITAL_STATUS"];
  $EMPLOYEE_POLITICAL_STATUS=$ROW["EMPLOYEE_POLITICAL_STATUS"];
  $EMPLOYEE_PHONE=$ROW["EMPLOYEE_PHONE"];
  $EMPLOYEE_EMAIL=$ROW["EMPLOYEE_EMAIL"];
	$JOB_BEGINNING=$ROW["JOB_BEGINNING"];	
  $EMPLOYEE_HEALTH=$ROW["EMPLOYEE_HEALTH"];   
  $EMPLOYEE_HIGHEST_SCHOOL =$ROW["EMPLOYEE_HIGHEST_SCHOOL"];
  $EMPLOYEE_HIGHEST_DEGREE=$ROW["EMPLOYEE_HIGHEST_DEGREE"];
  $GRADUATION_DATE=$ROW["GRADUATION_DATE"];
  $GRADUATION_SCHOOL=$ROW["GRADUATION_SCHOOL"];
  $EMPLOYEE_MAJOR=$ROW["EMPLOYEE_MAJOR"];
  $COMPUTER_LEVEL=$ROW["COMPUTER_LEVEL"];
  $FOREIGN_LANGUAGE1=$ROW["FOREIGN_LANGUAGE1"];
  $FOREIGN_LEVEL1=$ROW["FOREIGN_LEVEL1"];
  $FOREIGN_LANGUAGE2=$ROW["FOREIGN_LANGUAGE2"];
  $FOREIGN_LEVEL2=$ROW["FOREIGN_LEVEL2"];
  $FOREIGN_LANGUAGE3=$ROW["FOREIGN_LANGUAGE3"];
	$FOREIGN_LEVEL3=$ROW["FOREIGN_LEVEL3"];	
  $EMPLOYEE_SKILLS=$ROW["EMPLOYEE_SKILLS"];   
  $RESUME =$ROW["RESUME"];
  $JOB_INTENSION=$ROW["JOB_INTENSION"];
  $CAREER_SKILLS=$ROW["CAREER_SKILLS"];
  $WORK_EXPERIENCE=$ROW["WORK_EXPERIENCE"];
  $PROJECT_EXPERIENCE=$ROW["PROJECT_EXPERIENCE"];
  $RESIDENCE_PLACE=$ROW["RESIDENCE_PLACE"];
  $JOB_CATEGORY=$ROW["JOB_CATEGORY"];
  $JOB_INDUSTRY=$ROW["JOB_INDUSTRY"];
  $WORK_CITY=$ROW["WORK_CITY"];
  $EXPECTED_SALARY=$ROW["EXPECTED_SALARY"];
  $START_WORKING=$ROW["START_WORKING"];
  $REMARK=$ROW["REMARK"];
	$ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME =$ROW["ATTACHMENT_NAME"];
  $ADD_TIME =$ROW["ADD_TIME"];
  $PHOTO_NAME =$ROW["PHOTO_NAME"];
  $RECRU_CHANNEL =$ROW["RECRU_CHANNEL"];  
  
     
   $EMPLOYEE_HIGHEST_SCHOOL=get_hrms_code_name($EMPLOYEE_HIGHEST_SCHOOL,"STAFF_HIGHEST_SCHOOL");
   $EMPLOYEE_NATIVE_PLACE=get_hrms_code_name($EMPLOYEE_NATIVE_PLACE,"AREA");
	 $EMPLOYEE_POLITICAL_STATUS=get_hrms_code_name($EMPLOYEE_POLITICAL_STATUS,"STAFF_POLITICAL_STATUS");
	 $EMPLOYEE_HIGHEST_DEGREE=get_hrms_code_name($EMPLOYEE_HIGHEST_DEGREE,"EMPLOYEE_HIGHEST_DEGREE");	
	 $JOB_CATEGORY=get_hrms_code_name($JOB_CATEGORY,"JOB_CATEGORY");	
	 $POSITION_NAME=get_hrms_code_name($POSITION,"POOL_POSITION");
	 $EMPLOYEE_MAJOR_NAME=get_hrms_code_name($EMPLOYEE_MAJOR,"POOL_EMPLOYEE_MAJOR");
	 $RECRU_CHANNEL=get_hrms_code_name($RECRU_CHANNEL,"PLAN_DITCH");	 
  $query1 = "SELECT PLAN_NAME from HR_RECRUIT_PLAN where PLAN_NO='$PLAN_NO'";
  $cursor1= exequery(TD::conn(),$query1);
  if($ROW1=mysql_fetch_array($cursor1))
     $PLAN_NAME=$ROW1["PLAN_NAME"];    
	 
	if($EMPLOYEE_MARITAL_STATUS=="0") $EMPLOYEE_MARITAL_STATUS=_("未婚");
	if($EMPLOYEE_MARITAL_STATUS=="1") $EMPLOYEE_MARITAL_STATUS=_("已婚");
	if($EMPLOYEE_MARITAL_STATUS=="2") $EMPLOYEE_MARITAL_STATUS=_("离异");
	if($EMPLOYEE_MARITAL_STATUS=="3") $EMPLOYEE_MARITAL_STATUS=_("丧偶");

  if($EMPLOYEE_SEX==0) $EMPLOYEE_SEX=_("男");
	if($EMPLOYEE_SEX==1) $EMPLOYEE_SEX=_("女"); 
	
	if($START_WORKING=="") $START_WORKING= "";
	if($START_WORKING=="0") $START_WORKING= _("1周以内");
  if($START_WORKING=="1") $START_WORKING= _("1个月内");
  if($START_WORKING=="2") $START_WORKING= _("1~3个月");
  if($START_WORKING=="3") $START_WORKING= _("3个月后");
  if($START_WORKING=="4") $START_WORKING= _("随时到岗");
  
  
   /*年龄计算*/
   $CUR_DATE=date("Y-m-d",time());
   if($EMPLOYEE_BIRTH!="0000-00-00" && $EMPLOYEE_BIRTH!="")
  {
  	$agearray = explode("-",$EMPLOYEE_BIRTH);
  	$cur = explode("-",$CUR_DATE);
  	$year=$agearray[0];
  	$EMPLOYEE_AGE=date("Y")-$year;
  	if($cur[1] > $agearray[1] || $cur[1]==$agearray[1] && $cur[2]>=$agearray[2])
  	{
  		$EMPLOYEE_AGE++;
  	}
  }
else
  {
  	$STAFF_AGE="";
  }
  if($EMPLOYEE_AGE!="")
  {
  	$EMPLOYEE_AGE = $EMPLOYEE_AGE-1;
  }
?>
<table class="TableBlock" width="90%" align="center">
  <tr>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("计划名称：")?></td>
    <td  nowrap align="left" class="TableData" width="180"><?=$PLAN_NAME?></td>   
     <td  nowrap align="center" class="TableData" rowspan="9" colspan="2">
<?
   if($PHOTO_NAME=="")
      echo "<center>"._("暂无照片")."</center>";
   else
      echo "<center><A  align='center' border=0 href=\"#\"><img src='recruit_pic.php?PHOTO_NAME=$PHOTO_NAME' width='150' border=0></A></center>";
?>       	
     </td>
  </tr>
  <tr>  
    <td  nowrap align="left" width="120" class="TableContent"><?=_("应聘者姓名：")?></td>
    <td  nowrap align="left" class="TableData" width="180"><?=$EMPLOYEE_NAME?></td> 
  </tr>  
  <tr>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("性别：")?></td>
    <td  nowrap align="left" class="TableData" width="180"><?=$EMPLOYEE_SEX?></td>
  </tr>
  <tr>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("出生日期：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$EMPLOYEE_BIRTH=="0000-00-00"?"":$EMPLOYEE_BIRTH;?></td>
  </tr>  
  <tr>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("年龄：")?></td>
    <td  nowrap align="left" class="TableData" width="180"><?=$EMPLOYEE_AGE?></td>
  </tr>
  <tr>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("籍贯：")?></td>
    <td  nowrap align="left" class="TableData" width="180"><?=$EMPLOYEE_NATIVE_PLACE?><?=$EMPLOYEE_NATIVE_PLACE2?></td>
  </tr> 
  <tr>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("现居住城市：")?></td>
    <td  nowrap align="left" class="TableData" width="180"><?=$RESIDENCE_PLACE?></td>
  </tr>    
  <tr>
  <tr>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("户口所在地：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$EMPLOYEE_DOMICILE_PLACE?></td>
  </tr>
  </tr>        
  <tr>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("民族：")?></td>
    <td  nowrap align="left" class="TableData" width="180"><?=$EMPLOYEE_NATIONALITY?></td>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("婚姻状况：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$EMPLOYEE_MARITAL_STATUS?></td>
  </tr>
  <tr>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("健康状况：")?></td>
    <td  nowrap align="left" class="TableData" width="180"><?=$EMPLOYEE_HEALTH?></td>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("政治面貌：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$EMPLOYEE_POLITICAL_STATUS?></td>
  </tr>
  <tr>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("联系电话：")?></td>
    <td  nowrap align="left" class="TableData" width="180"><?=$EMPLOYEE_PHONE?></td>
    <td  nowrap align="left" width="120" class="TableContent">E_MAIL<?=_("：")?></td>
    <td  nowrap align="left" class="TableData" width="180"><?=$EMPLOYEE_EMAIL?></td>
  </tr>
  <tr>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("参加工作时间：")?></td>
    <td  nowrap align="left" class="TableData" width="180"><?=$JOB_BEGINNING=="0000-00-00"?"":$JOB_BEGINNING;?></td>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("期望从事职业：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$JOB_INTENSION?></td>
  </tr>
  <tr>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("岗位：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$POSITION_NAME?></td>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("期望工作性质：")?></td>
    <td  nowrap align="left" class="TableData" width="180"><?=$JOB_CATEGORY?></td>
  </tr>
  <tr>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("期望从事行业：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$JOB_INDUSTRY?></td>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("期望工作城市：")?></td>
    <td  nowrap align="left" class="TableData" width="180"><?=$WORK_CITY?></td>
  </tr>
  <tr>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("期望薪水：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$EXPECTED_SALARY?></td>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("到岗时间：")?></td>
    <td  nowrap align="left" class="TableData" width="180"><?=$START_WORKING?></td>
  </tr>
  <tr>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("学历：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$EMPLOYEE_HIGHEST_SCHOOL?></td>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("学位：")?></td>
    <td  nowrap align="left" class="TableData" width="180"><?=$EMPLOYEE_HIGHEST_DEGREE?></td>
  </tr>
  <tr>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("毕业时间：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$GRADUATION_DATE=="0000-00-00"?"":$GRADUATION_DATE;?></td>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("毕业学校：")?></td>
    <td  nowrap align="left" class="TableData" width="180"><?=$GRADUATION_SCHOOL?></td>
  </tr>
  <tr>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("专业：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$EMPLOYEE_MAJOR_NAME?></td>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("计算机水平：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$COMPUTER_LEVEL?></td>
  </tr>
  <tr>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("外语语种")?>1<?=_("：")?></td>
    <td  nowrap align="left" class="TableData" width="180"><?=$FOREIGN_LANGUAGE1?></td>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("外语水平")?>1<?=_("：")?></td>
    <td  nowrap align="left" class="TableData" width="180"><?=$FOREIGN_LEVEL1?></td>
  </tr>
  <tr>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("外语语种")?>2<?=_("：")?></td>
    <td  nowrap align="left" class="TableData" width="180"><?=$FOREIGN_LANGUAGE2?></td>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("外语水平")?>2<?=_("：")?></td>
    <td  nowrap align="left" class="TableData" width="180"><?=$FOREIGN_LEVEL2?></td>
  </tr>
  <tr>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("外语语种")?>3<?=_("：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$FOREIGN_LANGUAGE3?></td>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("外语水平")?>3<?=_("：")?></td>
    <td  nowrap align="left" class="TableData" width="180"><?=$FOREIGN_LEVEL3?></td>
  </tr>
  <tr>  
    <td  nowrap align="left" width="120" class="TableContent"><?=_("入库时间：")?></td>
    <td  nowrap align="left" class="TableData" width="180"><?=$ADD_TIME?></td> 
    <td  nowrap align="left" width="120" class="TableContent"><?=_("招聘渠道：")?></td>
    <td  nowrap align="left" class="TableData" width="180"><?=$RECRU_CHANNEL?></td> 
  </tr>   
  <tr>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("职业技能：")?></td>
    <td align="left" class="TableData" colspan="3"><?=$CAREER_SKILLS?></td>
  </tr>
  <tr>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("工作经验：")?></td>
    <td align="left" class="TableData" colspan="3"><?=$WORK_EXPERIENCE?></td>
  </tr>
  <tr>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("项目经验：")?></td>
    <td align="left" class="TableData" colspan="3"><?=$PROJECT_EXPERIENCE?></td>
  </tr>
  <tr>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("特长：")?></td>
    <td align="left" class="TableData" colspan="3"><?=$EMPLOYEE_SKILLS?></td>
  </tr>
  <tr>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("备注：")?></td>
    <td align="left" class="TableData" colspan="3"><?=$REMARK?></td>
  </tr>
  
<?
$ATTACH_ARRAY = trim_inserted_image($CONTENT, $ATTACHMENT_ID, $ATTACHMENT_NAME);
if($ATTACH_ARRAY["NAME"]!="")
{
?>
    <tr>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("附件文档")?>:</td>
    <td class="TableData" colspan="3"> <?=attach_link($ATTACH_ARRAY["ID"],$ATTACH_ARRAY["NAME"],1,1,1)?></td>
    </tr>
<?
}

if($ATTACH_ARRAY["IMAGE_COUNT"]>0)
{
?>
    <tr>
      <td  nowrap align="left" width="120" class="TableContent">
        <?=_("附件图片")?>: 
			</td>
		 <td class="TableData" width="180">	
<?
   $ATTACHMENT_ID_ARRAY=explode(",",$ATTACH_ARRAY["ID"]);
   $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACH_ARRAY["NAME"]);
   $ARRAY_COUNT=sizeof($ATTACHMENT_ID_ARRAY);
   for($I=0;$I<$ARRAY_COUNT;$I++)
   {
      if($ATTACHMENT_ID_ARRAY[$I]=="")
         continue;

      $MODULE=attach_sub_dir();
      $ATTACHMENT_ID1=$ATTACHMENT_ID_ARRAY[$I];
      $YM=substr($ATTACHMENT_ID1,0,strpos($ATTACHMENT_ID1,"_"));
      if($YM)
         $ATTACHMENT_ID1=substr($ATTACHMENT_ID1,strpos($ATTACHMENT_ID,"_")+1);
      $ATTACHMENT_ID_ENCODED=attach_id_encode($ATTACHMENT_ID1,$ATTACHMENT_NAME_ARRAY[$I]);

      if(is_image($ATTACHMENT_NAME_ARRAY[$I]))
      {
         $IMG_ATTR=td_getimagesize(attach_real_path($ATTACHMENT_ID_ARRAY[$I],$ATTACHMENT_NAME_ARRAY[$I]));
         if(is_array($IMG_ATTR) && $IMG_ATTR[0]>0 && $IMG_ATTR[1]>0)
            $WIDTH=floor($IMG_ATTR[0]*100/$IMG_ATTR[1]);
         else
            $WIDTH=100;
?>
                <a href="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>"><img src="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>" border="0"  width="<?=$WIDTH?>" height="100" alt="<?=_("文件名：")?><?=$ATTACHMENT_NAME_ARRAY[$I]?>"></a><?=_("　")?>
<?
      }
   }
?>
     </td>
   </tr>
<?
}
?>

  <tr>
    <td  nowrap align="left" width="120" class="TableContent"><?=_("简历：")?></td>
    <td align="left" class="TableData" colspan="3"><?=$RESUME?></td>
  </tr>
  <tr align="center" class="TableControl">
    <td colspan="4">
      <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close();" title="<?=_("关闭窗口")?>">
    </td>
  </tr>
</table>
<?
}
else
  Message("",_("未找到相应记录！"));
?>
</body>

</html>
