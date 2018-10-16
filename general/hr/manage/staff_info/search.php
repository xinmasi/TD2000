<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_field.php");
include_once("check_priv.inc.php");

$PAGE_SIZE =10;
$CUR_DATE=date("Y-m-d",time());
if(!isset($start) || $start=="")
   $start=0;
  


$sql = "SELECT DEPT_ID FROM hr_manager WHERE  (find_in_set('".$_SESSION['LOGIN_USER_ID']."',DEPT_HR_MANAGER) or find_in_set('".$_SESSION['LOGIN_USER_ID']."',DEPT_HR_SPECIALIST))";
$cursor1 = exequery(TD::conn(),$sql);
while($arr=mysql_fetch_array($cursor1))
{
    $DEPT_ID_HR .= $arr['DEPT_ID'].",";
}
$DEPT_ID_HR = td_trim($DEPT_ID_HR); 

if($WHERE_STR!="" && substr(trim($WHERE_STR),0,2)=="or" && $_SESSION['LOGIN_USER_PRIV']!=1)
{
	$WHERE_STR = str_replace("or", "", $WHERE_STR);
}

if($DEPT_ID_HR!="" && $_SESSION['LOGIN_USER_PRIV']!=1)
{
	if($WHERE_STR=="")
	{
		$WHERE_STR.=" b.DEPT_ID in ($DEPT_ID_HR)";
	}
	else
	{
		$WHERE_STR.=" or b.DEPT_ID in ($DEPT_ID_HR)";
	}
	$WHERE_STR = str_replace("and", "", $WHERE_STR);
	$WHERE_STR = " and (".$WHERE_STR.")";
	
}

//获取人事专员和人力资源管理员
$sql = "SELECT DEPT_HR_MANAGER,DEPT_HR_SPECIALIST FROM hr_manager";
$cursor1 = exequery(TD::conn(),$sql);
while($arr=mysql_fetch_array($cursor1))
{
	$DEPT_HR_MANAGER    .= $arr['DEPT_HR_MANAGER'];
	$DEPT_HR_SPECIALIST .= $arr['DEPT_HR_SPECIALIST'];
}

$DEPT_HR_MANAGER = array_unique(explode(',',$DEPT_HR_MANAGER));
$DEPT_HR_MANAGER = implode(",",$DEPT_HR_MANAGER);


$DEPT_HR_SPECIALIST = array_unique(explode(',',$DEPT_HR_SPECIALIST));
$DEPT_HR_SPECIALIST = implode(",",$DEPT_HR_SPECIALIST);

$prive = 0;
if($_SESSION['LOGIN_USER_PRIV']==1)
{
	$prive = 1;
}else
{
	find_id(td_trim($DEPT_HR_SPECIALIST),$_SESSION['LOGIN_USER_ID'])?$prive=2:"";
	find_id(td_trim($DEPT_HR_MANAGER),$_SESSION['LOGIN_USER_ID'])?$prive=1:"";
}

$HTML_PAGE_TITLE = _("人事档案查询");
include_once("inc/header.inc.php");
?>

<script>
function delete_user(USER_ID)
{
  if(confirm("<?=_("确定要删除该人事档案吗？删除后将不可恢复")?>"))
     location = "delete.php?USER_ID="+USER_ID;
}

function check_one(el)
{
   if(!el.checked)
      document.getElementsByName("allbox").item(0).checked=false;
}

function check_all()
{
 for (i=0;i<document.getElementsByName("hrms_select").length;i++)
 {
   if(document.getElementsByName("allbox").item(0).checked)
      document.getElementsByName("hrms_select").item(i).checked=true;
   else
      document.getElementsByName("hrms_select").item(i).checked=false;
 }

 if(i==0)
 {
   if(document.getElementsByName("allbox").item(0).checked)
      document.getElementsByName("hrms_select").checked=true;
   else
      document.getElementsByName("hrms_select").checked=false;
 }
}
function delete_mail()
{
  delete_str="";
  for(i=0;i<document.getElementsByName("hrms_select").length;i++)
  {

      el=document.getElementsByName("hrms_select").item(i);
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(i==0)
  {
      el=document.getElementsByName("hrms_select");
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(delete_str=="")
  {
     alert("<?=_("要删除人事档案，请至少选择其中一条。")?>");
     return;
  }

  msg='<?=_("确认要删除所选人事档案吗？")?>';
  if(window.confirm(msg))
  {
    url="delete.php?USER_ID="+ delete_str +"&start=<?=$start?>";
    location=url;
  }
}
</script>
<?
//------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($USER_ID!="")
   $CONDITION_STR.=" and b.BYNAME='$USER_ID'";
if($USER_PRIV!="")
   $CONDITION_STR.=" and c.USER_PRIV='$USER_PRIV'";
if($STAFF_NAME!="")
   $CONDITION_STR.=" and (a.STAFF_NAME='$STAFF_NAME' or b.USER_NAME='$STAFF_NAME')";
if($STAFF_NO!="")
   $CONDITION_STR.=" and a.STAFF_NO='$STAFF_NO'";
if($WORK_NO!="")
  $CONDITION_STR.=" and a.WORK_NO='$WORK_NO'";
if($BEFORE_NAME!="")
  $CONDITION_STR.=" and a.BEFORE_NAME='$BEFORE_NAME'";
if($STAFF_E_NAME!="")
  $CONDITION_STR.=" and a.STAFF_E_NAME='$STAFF_E_NAME'";
if($STAFF_CARD_NO!="")
  $CONDITION_STR.=" and a.STAFF_CARD_NO='$STAFF_CARD_NO'";
if($STAFF_SEX!="")
  $CONDITION_STR.=" and a.STAFF_SEX='$STAFF_SEX'";
if($STAFF_BIRTH1!="")
  $CONDITION_STR.=" and a.STAFF_BIRTH>='$STAFF_BIRTH1'";
if($STAFF_BIRTH2!="")
  $CONDITION_STR.=" and a.STAFF_BIRTH<='$STAFF_BIRTH2'";
if($STAFF_NATIVE_PLACE!="")
  $CONDITION_STR.=" and a.STAFF_NATIVE_PLACE='$STAFF_NATIVE_PLACE'";
if($STAFF_NATIONALITY!="")
  $CONDITION_STR.=" and a.STAFF_NATIONALITY='$STAFF_NATIONALITY'";
if($STAFF_MARITAL_STATUS!="")
  $CONDITION_STR.=" and a.STAFF_MARITAL_STATUS='$STAFF_MARITAL_STATUS'";
if($STAFF_POLITICAL_STATUS!="")
  $CONDITION_STR.=" and a.STAFF_POLITICAL_STATUS='$STAFF_POLITICAL_STATUS'";
if($JOIN_PARTY_TIME1!="")
  $CONDITION_STR.=" and a.JOIN_PARTY_TIME>='$JOIN_PARTY_TIME1'";
if($JOIN_PARTY_TIME2!="")
  $CONDITION_STR.=" and a.JOIN_PARTY_TIME<='$JOIN_PARTY_TIME2'";
if($STAFF_PHONE!="")
  $CONDITION_STR.=" and a.STAFF_PHONE='$STAFF_PHONE'";
if($STAFF_MOBILE!="")
  $CONDITION_STR.=" and a.STAFF_MOBILE='$STAFF_MOBILE'";
if($STAFF_LITTLE_SMART!="")
  $CONDITION_STR.=" and a.STAFF_LITTLE_SMART='$STAFF_LITTLE_SMART'";
if($STAFF_MSN!="")
  $CONDITION_STR.=" and a.STAFF_MSN='$STAFF_MSN'";
if($STAFF_QQ!="")
  $CONDITION_STR.=" and a.STAFF_QQ='$STAFF_QQ'";
if($STAFF_EMAIL!="")
  $CONDITION_STR.=" and a.STAFF_EMAIL='$STAFF_EMAIL'";
if($HOME_ADDRESS!="")
  $CONDITION_STR.=" and a.HOME_ADDRESS='$HOME_ADDRESS'";
if($JOB_BEGINNING1!="")
  $CONDITION_STR.=" and a.JOB_BEGINNING>='$JOB_BEGINNING1'";
if($JOB_BEGINNING2!="")
  $CONDITION_STR.=" and a.JOB_BEGINNING<='$JOB_BEGINNING2'";
if($WORK_AGE!="")
  $CONDITION_STR.=" and a.WORK_AGE='$WORK_AGE'";
if($STAFF_HEALTH!="")
  $CONDITION_STR.=" and a.STAFF_HEALTH='$STAFF_HEALTH'";
if($STAFF_DOMICILE_PLACE!="")
  $CONDITION_STR.=" and a.STAFF_DOMICILE_PLACE='$STAFF_DOMICILE_PLACE'";
if($STAFF_HIGHEST_SCHOOL!="")
  $CONDITION_STR.=" and a.STAFF_HIGHEST_SCHOOL='$STAFF_HIGHEST_SCHOOL'";
if($STAFF_HIGHEST_DEGREE!="")
  $CONDITION_STR.=" and a.STAFF_HIGHEST_DEGREE='$STAFF_HIGHEST_DEGREE'";
if($GRADUATION_DATE1!="")
  $CONDITION_STR.=" and a.GRADUATION_DATE>='$GRADUATION_DATE1'";
if($GRADUATION_DATE2!="")
  $CONDITION_STR.=" and a.GRADUATION_DATE<='$GRADUATION_DATE2'";
if($GRADUATION_SCHOOL!="")
  $CONDITION_STR.=" and a.GRADUATION_SCHOOL='$GRADUATION_SCHOOL'";
if($STAFF_MAJOR!="")
  $CONDITION_STR.=" and a.STAFF_MAJOR='$STAFF_MAJOR'";
if($COMPUTER_LEVEL!="")
  $CONDITION_STR.=" and a.COMPUTER_LEVEL='$COMPUTER_LEVEL'";
if($FOREIGN_LANGUAGE1!="")
  $CONDITION_STR.=" and a.FOREIGN_LANGUAGE1='$FOREIGN_LANGUAGE1'";
if($FOREIGN_LANGUAGE2!="")
  $CONDITION_STR.=" and a.FOREIGN_LANGUAGE2='$FOREIGN_LANGUAGE2'";
if($FOREIGN_LANGUAGE3!="")
  $CONDITION_STR.=" and a.FOREIGN_LANGUAGE3='$FOREIGN_LANGUAGE3'";
if($FOREIGN_LEVEL1!="")
  $CONDITION_STR.=" and a.FOREIGN_LEVEL1='$FOREIGN_LEVEL1'";
if($FOREIGN_LEVEL2!="")
  $CONDITION_STR.=" and a.FOREIGN_LEVEL2='$FOREIGN_LEVEL2'";
if($FOREIGN_LEVEL3!="")
  $CONDITION_STR.=" and a.FOREIGN_LEVEL3='$FOREIGN_LEVEL3'";
if($STAFF_SKILLS!="")
  $CONDITION_STR.=" and a.STAFF_SKILLS='$STAFF_SKILLS'";
if($WORK_TYPE!="")
  $CONDITION_STR.=" and a.WORK_TYPE='$WORK_TYPE'";
if($DEPT_ID!="")
  $CONDITION_STR.=" and a.DEPT_ID='$DEPT_ID'";
if($ADMINISTRATION_LEVEL!="")
  $CONDITION_STR.=" and a.ADMINISTRATION_LEVEL='$ADMINISTRATION_LEVEL'";
if($JOB_POSITION!="")
  $CONDITION_STR.=" and a.JOB_POSITION='$JOB_POSITION'";
if($PRESENT_POSITION!="")
  $CONDITION_STR.=" and a.PRESENT_POSITION='$PRESENT_POSITION'";
if($DATES_EMPLOYED1!="")
  $CONDITION_STR.=" and a.DATES_EMPLOYED>='$DATES_EMPLOYED1'";
if($DATES_EMPLOYED2!="")
  $CONDITION_STR.=" and a.DATES_EMPLOYED<='$DATES_EMPLOYED2'";
if($JOB_AGE!="")
  $CONDITION_STR.=" and a.JOB_AGE='$JOB_AGE'";
if($BEGIN_SALSRY_TIME1!="")
  $CONDITION_STR.=" and a.BEGIN_SALSRY_TIME>='$BEGIN_SALSRY_TIME1'";
if($BEGIN_SALSRY_TIME2!="")
  $CONDITION_STR.=" and a.BEGIN_SALSRY_TIME<='$BEGIN_SALSRY_TIME2'";
if($STAFF_OCCUPATION!="")
  $CONDITION_STR.=" and a.STAFF_OCCUPATION='$STAFF_OCCUPATION'";
if($SHOW_LEAVE=='1')
   $CONDITION_STR.=" and (b.DEPT_ID!='0' and (a.DEPT_ID!='0' or a.DEPT_ID is NULL))";
if($WORK_STATUS!="")
   $CONDITION_STR.=" and a.WORK_STATUS='$WORK_STATUS'";
//------------------------ 展现字段的信息 ------------------
$SYS_PARA_ARRAY = get_sys_para("HR_MANAGER_ARCHIVES");
$HRMS_OPEN_FIELDS=$SYS_PARA_ARRAY["HR_MANAGER_ARCHIVES"];

$OPEN_ARRAY=explode("|",$HRMS_OPEN_FIELDS);
$HR_LIST_SQL=str_replace(',', ",a.", trim($OPEN_ARRAY[0],','));
$FIELD_ARRAY=explode(",",$OPEN_ARRAY[0]);
$NAME_ARRAY=explode(",",$OPEN_ARRAY[1]);
if($HR_LIST_SQL!="")
{
    $HR_LIST_SQL=',a.'.$HR_LIST_SQL;
}
$query_hr = "select b.USER_PRIV_NAME,a.PHOTO_NAME,b.PHOTO,a.STAFF_NAME,a.USER_ID".$HR_LIST_SQL." from HR_STAFF_INFO a
 LEFT OUTER JOIN USER b ON a.USER_ID = b.USER_ID
 LEFT OUTER JOIN USER_PRIV  c ON b.USER_PRIV=c.USER_PRIV
 LEFT OUTER JOIN DEPARTMENT d ON a.DEPT_ID=d.DEPT_ID
WHERE 1=1 ".field_where_str("HR_STAFF_INFO",(empty($_POST)?$_GET:$_POST),"a.USER_ID").$CONDITION_STR.$WHERE_STR." order by c.PRIV_NO,b.USER_NO,b.USER_NAME limit $start,$PAGE_SIZE";
$cursor_hr=exequery(TD::conn(),$query_hr);

$query_hr2 = "select b.USER_PRIV_NAME,a.STAFF_NAME,a.USER_ID".$HR_LIST_SQL." from HR_STAFF_INFO a
 LEFT OUTER JOIN USER b ON a.USER_ID = b.USER_ID
 LEFT OUTER JOIN USER_PRIV  c ON b.USER_PRIV=c.USER_PRIV
 LEFT OUTER JOIN DEPARTMENT d ON a.DEPT_ID=d.DEPT_ID
WHERE 1=1 ".field_where_str("HR_STAFF_INFO",(empty($_POST)?$_GET:$_POST),"a.USER_ID").$CONDITION_STR.$WHERE_STR;
$cursor_hr2=exequery(TD::conn(),$query_hr2);
$COUNT = mysql_num_rows($cursor_hr2);

?>
<body class="bodycolor" topmargin="5">
<?
if($COUNT <= 0)
{
   Message("", _("无符合条件的人事档案"));
   ?>
   <center>
   <button type="submit" onclick="location='query.php'" style="width:50px;height:25px;font-size:16px">返回</button>
   </center>
   <?
   //Button_Back();
   exit;
}
?>
	<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("人事档案查询结果")?></span><br>
    	</td>
<?
$QSTRING="";
foreach ($_POST as $key=> $value)
   $QSTRING.=$key."=".$value."&";

$THE_FOUR_VAR = $QSTRING."start";

if($_SERVER['QUERY_STRING'] == "")
{
	$_SERVER['QUERY_STRING'] = $QSTRING;
}
?>
    <td align="right" valign="bottom" class="small1"><?=page_bar($start,$COUNT,$PAGE_SIZE,$THE_FOUR_VAR,null,false,1)?></td>
	</tr>
</table>
<table class="TableList" width="100%">
   <thead class="TableHeader">
      <td nowrap align="center"><?=_("选择")?></td>
      <td nowrap align="center"><?=_("姓名")?></td>
      <td nowrap align="center"><?=_("用户头像")?></td>
      <td nowrap align="center"><?=_("档案头像")?></td>
      <td nowrap align="center"><?=_("OA角色")?></td>
      <?
for($I=0;$I<count($FIELD_ARRAY);$I++)
{
   if($FIELD_ARRAY[$I]=="" || $NAME_ARRAY[$I]=="")
      continue;
   echo "<td nowrap align='center'>".$NAME_ARRAY[$I]."</td>";

}

?>
      <td width="150"><?=_("操作")?></td>
   </thead>
<?
while($ROW = mysql_fetch_array($cursor_hr))
{
   $USER_ID = $ROW['USER_ID'];
   $STAFF_NO = $ROW['STAFF_NO'];
   $USER_NAME = $ROW['USER_NAME'];
   $USER_PRIV_NAME_INFO=$ROW['USER_PRIV_NAME'];
   if($USER_NAME=="")
      $USER_NAME=substr(GetUserNameById($USER_ID),0,-1);
   if($USER_NAME=="")
   {
       $query = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$USER_ID'";
       $cursor= exequery(TD::conn(),$query);
      if($ROW=mysql_fetch_array($cursor))
          $USER_NAME=$ROW["STAFF_NAME"];
      $USER_NAME=$USER_NAME."("."<font color='red'>"._("用户已删除")."</font>".")";
    }
   $WORK_NO = $ROW['WORK_NO'];
   $STAFF_SEX = $ROW['STAFF_SEX'];
   $STAFF_CARD_NO = $ROW['STAFF_CARD_NO'];
   $STAFF_BIRTH = $ROW['STAFF_BIRTH'];
   $STAFF_NATIVE_PLACE = $ROW['STAFF_NATIVE_PLACE'];
   $STAFF_NATIONALITY = $ROW['STAFF_NATIONALITYX'];
   $STAFF_MARITAL_STATUS = $ROW['STAFF_MARITAL_STATUS'];
   $STAFF_POLITICAL_STATUS = $ROW['STAFF_POLITICAL_STATUS'];
   $WORK_STATUS = $ROW['WORK_STATUS'];
   $ATTACHMENT = $ROW['ATTACHMENT'];
   $JOIN_PARTY_TIME = $ROW['JOIN_PARTY_TIME'];
   $STAFF_PHONE = $ROW['STAFF_PHONE'];
   $STAFF_MOBILE = $ROW['STAFF_MOBILE'];
   $STAFF_LITTLE_SMART = $ROW['STAFF_LITTLE_SMART'];
   $STAFF_MSN = $ROW['STAFF_MSN'];
   $STAFF_QQ = $ROW['STAFF_QQ'];
   $STAFF_EMAIL = $ROW['STAFF_EMAIL'];
   $HOME_ADDRESS = $ROW['HOME_ADDRESS'];
   $JOB_BEGINNING = $ROW['JOB_BEGINNING'];
   $WORK_AGE = $ROW['WORK_AGE'];
   $STAFF_HEALTH = $ROW['STAFF_HEALTH'];
   $STAFF_DOMICILE_PLACE = $ROW['STAFF_DOMICILE_PLACE'];
   $STAFF_HIGHEST_SCHOOL = $ROW['STAFF_HIGHEST_SCHOOL'];
   $STAFF_HIGHEST_DEGREE = $ROW['STAFF_HIGHEST_DEGREE'];
   $GRADUATION_SCHOOL = $ROW['GRADUATION_SCHOOL'];
   $STAFF_MAJOR = $ROW['STAFF_MAJOR'];
   $COMPUTER_LEVEL = $ROW['COMPUTER_LEVEL'];
   $FOREIGN_LANGUAGE1 = $ROW['FOREIGN_LANGUAGE1'];
   $FOREIGN_LANGUAGE2 = $ROW['FOREIGN_LANGUAGE2'];
   $FOREIGN_LANGUAGE3 = $ROW['FOREIGN_LANGUAGE3'];
   $FOREIGN_LEVEL1 = $ROW['FOREIGN_LEVEL1'];
   $FOREIGN_LEVEL2 = $ROW['FOREIGN_LEVEL2'];
   $FOREIGN_LEVEL3 = $ROW['FOREIGN_LEVEL3'];
   $STAFF_SKILLS = $ROW['STAFF_SKILLS'];
   $DEPT_ID = $ROW['DEPT_ID'];
   $WORK_TYPE = $ROW['WORK_TYPE'];
   $ADMINISTRATION_LEVEL = $ROW['ADMINISTRATION_LEVEL'];
   $JOB_POSITION = $ROW['JOB_POSITION'];
   $PRESENT_POSITION = $ROW['PRESENT_POSITION'];
   $DATES_EMPLOYED = $ROW['DATES_EMPLOYED'];
   $JOB_AGE = $ROW['JOB_AGE'];
   $BEGIN_SALSRY_TIME = $ROW['BEGIN_SALSRY_TIME'];
   $STAFF_OCCUPATION = $ROW['STAFF_OCCUPATION'];
   $ATTACHMENT_NAME = $ROW['ATTACHMENT_NAME'];
   $PHOTO=$ROW["PHOTO"];
   $PHOTO_NAME=$ROW["PHOTO_NAME"];
 ?>
<tr class="TableData">
      <td align="center"><input type="checkbox" name="hrms_select" value="<?=$USER_ID?>" onClick="check_one(self);"></td>
      <td align="center"><?=$USER_NAME?></td>
<?
if($PHOTO=="")
{
?>
        <td nowrap align="center" style="font-weight: bold; color: red"><?=_("未上传")?></td>
<?
}
else
{
?>
        <td nowrap align="center"><?=_("已上传")?></td>
<?
}
if($PHOTO_NAME=="")
{
?>
        <td nowrap align="center" style="font-weight: bold; color: red"><?=_("未上传")?></td>
<?
}
else
{
?>
        <td nowrap align="center"><?=_("已上传")?></td>
<?
}
?>
        <td nowrap align="center"><?=$USER_PRIV_NAME_INFO?></td>
<?
    for($I=0;$I<count($FIELD_ARRAY);$I++)
    {
       if($FIELD_ARRAY[$I]=="" || $NAME_ARRAY[$I]=="")
          continue;
       if($ROW[$FIELD_ARRAY[$I]]=="0000-00-00")
       {
           $ROW[$FIELD_ARRAY[$I]]="";
       }
       if($FIELD_ARRAY[$I]=="STAFF_SEX")
       {
           if($ROW[$FIELD_ARRAY[$I]]=="0")
              $ROW[$FIELD_ARRAY[$I]] = _("男");
           else
              $ROW[$FIELD_ARRAY[$I]] = _("女");
       }
       if($FIELD_ARRAY[$I]=="STAFF_MARITAL_STATUS")
       {
           if($ROW[$FIELD_ARRAY[$I]]=="0")
              $ROW[$FIELD_ARRAY[$I]] = _("未婚");
           elseif($ROW[$FIELD_ARRAY[$I]]=="1")
              $ROW[$FIELD_ARRAY[$I]] = _("已婚");
           elseif($ROW[$FIELD_ARRAY[$I]]=="1")
              $ROW[$FIELD_ARRAY[$I]] = _("离异");
           elseif($ROW[$FIELD_ARRAY[$I]]=="1")
              $ROW[$FIELD_ARRAY[$I]] = _("丧偶");
       }

       if($FIELD_ARRAY[$I]=="WORK_JOB" && $ROW[$FIELD_ARRAY[$I]]!="")
       {
           $query11="select CODE_NAME from `hr_code` where PARENT_NO='POOL_POSITION' and CODE_NO='".$ROW[$FIELD_ARRAY[$I]]."'";
           $cursor11= exequery(TD::conn(),$query11);
            if($ROW11=mysql_fetch_array($cursor11))
            {

                $ROW[$FIELD_ARRAY[$I]]=$ROW11["CODE_NAME"];
            }
       }

       if($FIELD_ARRAY[$I]=="WORK_LEVEL" && $ROW[$FIELD_ARRAY[$I]]!="")
       {
           $query11="select CODE_NAME from `hr_code` where PARENT_NO='WORK_LEVEL' and CODE_NO='".$ROW[$FIELD_ARRAY[$I]]."'";
           $cursor11= exequery(TD::conn(),$query11);
            if($ROW11=mysql_fetch_array($cursor11))
            {

                $ROW[$FIELD_ARRAY[$I]]=$ROW11["CODE_NAME"];
            }
       }

       if($FIELD_ARRAY[$I]=="STAFF_OCCUPATION" && $ROW[$FIELD_ARRAY[$I]]!="")
       {
           $query11="select CODE_NAME from `hr_code` where PARENT_NO='STAFF_OCCUPATION' and CODE_NO='".$ROW[$FIELD_ARRAY[$I]]."'";
           $cursor11= exequery(TD::conn(),$query11);
            if($ROW11=mysql_fetch_array($cursor11))
            {

                $ROW[$FIELD_ARRAY[$I]]=$ROW11["CODE_NAME"];
            }
       }

       if($FIELD_ARRAY[$I]=="EMPLOYEE_HIGHEST_DEGREE" && $ROW[$FIELD_ARRAY[$I]]!="")
       {
           $query11="select CODE_NAME from `hr_code` where PARENT_NO='EMPLOYEE_HIGHEST_DEGREE' and CODE_NO='".$ROW[$FIELD_ARRAY[$I]]."'";
           $cursor11= exequery(TD::conn(),$query11);
            if($ROW11=mysql_fetch_array($cursor11))
            {

                $ROW[$FIELD_ARRAY[$I]]=$ROW11["CODE_NAME"];
            }
       }

       if($FIELD_ARRAY[$I]=="PRESENT_POSITION" && $ROW[$FIELD_ARRAY[$I]]!="")
       {
           $query11="select CODE_NAME from `hr_code` where PARENT_NO='PRESENT_POSITION' and CODE_NO='".$ROW[$FIELD_ARRAY[$I]]."'";
           $cursor11= exequery(TD::conn(),$query11);
            if($ROW11=mysql_fetch_array($cursor11))
            {

                $ROW[$FIELD_ARRAY[$I]]=$ROW11["CODE_NAME"];
            }
       }

       if($FIELD_ARRAY[$I]=="STAFF_HIGHEST_DEGREE" && $ROW[$FIELD_ARRAY[$I]]!="")
       {
           $query11="select CODE_NAME from `hr_code` where PARENT_NO='EMPLOYEE_HIGHEST_DEGREE' and CODE_NO='".$ROW[$FIELD_ARRAY[$I]]."'";
           $cursor11= exequery(TD::conn(),$query11);
            if($ROW11=mysql_fetch_array($cursor11))
            {

                $ROW[$FIELD_ARRAY[$I]]=$ROW11["CODE_NAME"];
            }
       }
       if($FIELD_ARRAY[$I]=="STAFF_HIGHEST_SCHOOL" && $ROW[$FIELD_ARRAY[$I]]!="")
       {
           $query11="select CODE_NAME from `hr_code` where PARENT_NO='STAFF_HIGHEST_SCHOOL' and CODE_NO='".$ROW[$FIELD_ARRAY[$I]]."'";
           $cursor11= exequery(TD::conn(),$query11);
            if($ROW11=mysql_fetch_array($cursor11))
            {

                $ROW[$FIELD_ARRAY[$I]]=$ROW11["CODE_NAME"];
            }
       }
       if($FIELD_ARRAY[$I]=="STAFF_TYPE" && $ROW[$FIELD_ARRAY[$I]]!="")
       {
           $query11="select CODE_NAME from `hr_code` where PARENT_NO='HR_STAFF_TYPE' and CODE_NO='".$ROW[$FIELD_ARRAY[$I]]."'";
           $cursor11= exequery(TD::conn(),$query11);
            if($ROW11=mysql_fetch_array($cursor11))
            {

                $ROW[$FIELD_ARRAY[$I]]=$ROW11["CODE_NAME"];
            }
       }
       if($FIELD_ARRAY[$I]=="WORK_STATUS" && $ROW[$FIELD_ARRAY[$I]]!="")
       {
           $query11="select CODE_NAME from `hr_code` where PARENT_NO='WORK_STATUS' and CODE_NO='".$ROW[$FIELD_ARRAY[$I]]."'";
           $cursor11= exequery(TD::conn(),$query11);
            if($ROW11=mysql_fetch_array($cursor11))
            {

                $ROW[$FIELD_ARRAY[$I]]=$ROW11["CODE_NAME"];
            }
       }
       if($FIELD_ARRAY[$I]=="STAFF_POLITICAL_STATUS" && $ROW[$FIELD_ARRAY[$I]]!="")
       {
           $query11="select CODE_NAME from `hr_code` where PARENT_NO='STAFF_POLITICAL_STATUS' and CODE_NO='".$ROW[$FIELD_ARRAY[$I]]."'";
           $cursor11= exequery(TD::conn(),$query11);
            if($ROW11=mysql_fetch_array($cursor11))
            {

                $ROW[$FIELD_ARRAY[$I]]=$ROW11["CODE_NAME"];
            }
       }
       if($FIELD_ARRAY[$I]=="STAFF_NATIVE_PLACE" && $ROW[$FIELD_ARRAY[$I]]!="")
       {
           $query11="select CODE_NAME from `hr_code` where PARENT_NO='AREA' and CODE_NO='".$ROW[$FIELD_ARRAY[$I]]."'";
           $cursor11= exequery(TD::conn(),$query11);
            if($ROW11=mysql_fetch_array($cursor11))
            {

                $ROW[$FIELD_ARRAY[$I]]=$ROW11["CODE_NAME"];
            }
       }

       echo "<td nowrap align='center'>".$ROW[$FIELD_ARRAY[$I]]."</td>";
    }
?>
      <td align="center">
      	<a href="javascript:;" onClick="window.open('staff_detail.php?USER_ID=<?=$USER_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
        <a href="staff_info.php?USER_ID=<?=$USER_ID?>"><?=_("编辑")?></a>&nbsp;&nbsp;
        <? if ($prive==1){?><a href="javascript:delete_user('<?=$USER_ID?>');"><?=_("删除")?></a><?}?>
      </td>
</tr>
<?
}
?>
<tr class="TableControl">
      <td colspan="<?=count($FIELD_ARRAY)+5?>">
         <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("全选")?></label> &nbsp;
         <? if ($prive==1){?><a href="javascript:delete_mail();" title="<?=_("删除所选人事")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("删除所选人事")?></a>&nbsp;<?}?>
      </td>
   </tr>
</table>
<br>
<div align="center">
   <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='query.php';">
</div>
</body>
</html>