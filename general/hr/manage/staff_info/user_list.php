<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("check_priv.inc.php");

if($ROLE_PRIV == "0")
{
    $WHERE_STR.=" and c.PRIV_NO>'$MY_PRIV_NO'";
}
else if($ROLE_PRIV == "1")
    $WHERE_STR.=" and c.PRIV_NO>='$MY_PRIV_NO'";
else if($ROLE_PRIV == "3")
{
    $PRIV_ID_STR=td_trim($PRIV_ID_STR);
    if($PRIV_ID_STR!="")
        $WHERE_STR.=" and c.PRIV_NO in ($PRIV_ID_STR)";
}

$query = "SELECT count(b.USER_ID) from USER b
 LEFT OUTER JOIN USER_PRIV  c ON b.USER_PRIV=c.USER_PRIV
 LEFT OUTER JOIN DEPARTMENT d ON b.DEPT_ID=d.DEPT_ID
WHERE d.DEPT_ID='$DEPT_ID' ".$WHERE_STR;
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
  $USER_COUNT = $ROW["0"];

$query = "SELECT count(b.USER_ID) from HR_STAFF_INFO a
 LEFT OUTER JOIN USER b ON a.USER_ID = b.USER_ID
 LEFT OUTER JOIN USER_PRIV  c ON b.USER_PRIV=c.USER_PRIV
 LEFT OUTER JOIN DEPARTMENT d ON b.DEPT_ID=d.DEPT_ID
WHERE d.DEPT_ID='$DEPT_ID' ".$WHERE_STR;
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
  $HRMS_COUNT1 = $ROW["0"];

$HRMS_COUNT=$USER_COUNT-$HRMS_COUNT1;
$query="select DEPT_NAME from DEPARTMENT where DEPT_ID='$DEPT_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
  $DEPT_NAME = $ROW["DEPT_NAME"];

$HTML_PAGE_TITLE = $DEPT_NAME;


$is_manager = 0;
//��ȡ����רԱ��������Դ����Ա
$sql = "SELECT DEPT_HR_MANAGER,DEPT_HR_SPECIALIST FROM hr_manager WHERE (find_in_set('".$_SESSION['LOGIN_USER_ID']."',DEPT_HR_MANAGER) or find_in_set('".$_SESSION['LOGIN_USER_ID']."',DEPT_HR_SPECIALIST)) and DEPT_ID='$DEPT_ID'";
$cursor1 = exequery(TD::conn(),$sql);
if($arr=mysql_fetch_array($cursor1))
{
    $is_manager = 1;
}

include_once("inc/header.inc.php");
?>


<script Language="JavaScript">
function delete_all(DEPT_ID)
{
  msg='<?=_("ȷ��Ҫɾ���ò��������û�������")?>\n<?=_("ɾ���󽫲��ɻָ���ȷ��ɾ���������д��ĸ��OK��")?>';
  if(window.prompt(msg,"") == "OK")
  {
    URL="delete.php?DEPT_ID=" + DEPT_ID;
    window.location=URL;
  }
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
	var DEPT_NAME='<?=$DEPT_NAME?>';
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
     alert("<?=_("Ҫɾ�����µ�����������ѡ������һ����")?>");
     return;
  }

  msg='<?=_("ȷ��Ҫɾ����ѡ���µ�����")?>';
  if(window.confirm(msg))
  {
    url="delete.php?USER_ID="+ delete_str+"&FLAG=1&DEPT_NAME="+DEPT_NAME;
    location=url;
  }
}

</script>
<body class="bodycolor" topmargin="5">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
 <tr>
  <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�½��û�����")?> (<?=$DEPT_NAME?>)</span>
  </td>
 </tr>
</table>
<? if($_SESSION['LOGIN_USER_PRIV']==1 || $is_manager == 1){?>
<div align="center">
  <input type="button" value="<?=_("�½��û�����")?>" class="BigButton" title="<?=_("�½��û�����")?>" onClick="location='new.php?DEPT_ID=<?=$DEPT_ID?>&SOURCE=new_hrms';">
</div>
<? }?>
<br>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <a name="bottom">[<?=$DEPT_NAME?>]<?=sprintf(_("�û������б�%s�ˣ�δ����%s��"), $USER_COUNT, $HRMS_COUNT)?></span>
    </td>
  </tr>
</table>
<?

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

 $USER_COUNT=0;

 $query1="select b.USER_PRIV_NAME,a.PHOTO_NAME,b.PHOTO,a.STAFF_NAME,a.USER_ID".$HR_LIST_SQL."
 from HR_STAFF_INFO a
 LEFT OUTER JOIN USER b ON a.USER_ID = b.USER_ID
 LEFT OUTER JOIN USER_PRIV  c ON b.USER_PRIV=c.USER_PRIV
 LEFT OUTER JOIN DEPARTMENT d ON b.DEPT_ID=d.DEPT_ID
WHERE d.DEPT_ID='$DEPT_ID'".$WHERE_STR;
 $query1=$query1." order by c.PRIV_NO,b.USER_NO,b.USER_NAME";
 $cursor= exequery(TD::conn(),$query1);
 while($ROW=mysql_fetch_array($cursor))
 {
   $USER_COUNT++;

   $STAFF_NAME=$ROW["STAFF_NAME"];
   $STAFF_SEX=$ROW["STAFF_SEX"];
   $USER_ID=$ROW["USER_ID"];
   $STAFF_BIRTH=$ROW["STAFF_BIRTH"];
   $STAFF_NATIONALITY=$ROW["STAFF_NATIONALITY"];
   $STAFF_NATIVE_PLACE=$ROW["STAFF_NATIVE_PLACE"];
   $STAFF_NATIVE_PLACE2=$ROW["STAFF_NATIVE_PLACE2"];
   $STAFF_POLITICAL_STATUS=$ROW["STAFF_POLITICAL_STATUS"];
   $STAFF_CARD_NO=$ROW["STAFF_CARD_NO"];
   $STAFF_NATIVE_PLACE=get_hrms_code_name($STAFF_NATIVE_PLACE,"AREA");
   $STAFF_POLITICAL_STATUS=get_hrms_code_name($STAFF_POLITICAL_STATUS,"STAFF_POLITICAL_STATUS");
   $PHOTO=$ROW["PHOTO"];
   $PHOTO_NAME=$ROW["PHOTO_NAME"];
      $USER_PRIV_NAME_INFO=$ROW['USER_PRIV_NAME'];
      
    $STAFF_NAME=substr(GetUserNameById($USER_ID),0,-1);
    $sql2 = "update HR_STAFF_INFO set STAFF_NAME='$STAFF_NAME' where USER_ID='$USER_ID'";
    exequery(TD::conn(),$sql2);
   if($USER_COUNT==1)
   {
?>
<table width="100%" class="TableList">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("ѡ��")?></td>
      <td nowrap align="center"><?=_("����")?></td>
      <td nowrap align="center"><?=_("�û�ͷ��")?></td>
      <td nowrap align="center"><?=_("����ͷ��")?></td>
      <td nowrap align="center"><?=_("OA��ɫ")?></td>
      <?
for($I=0;$I<count($FIELD_ARRAY);$I++)
{
   if($FIELD_ARRAY[$I]=="" || $NAME_ARRAY[$I]=="")
      continue;
   echo "<td nowrap align='center'>".$NAME_ARRAY[$I]."</td>";

}

?>
      <td nowrap align="center"><?=_("����")?></td>
   </tr>
<?
   }
?>
 <tr class="TableData">
 	<td>&nbsp;<input type="checkbox" name="hrms_select" value="<?=$USER_ID?>" onClick="check_one(self);">
        <td nowrap align="center"><?=$STAFF_NAME?></td>
<?
if($PHOTO=="")
{
?>
        <td nowrap align="center" style="font-weight: bold; color: red"><?=_("δ�ϴ�")?></td>
<?
}
else
{
?>
        <td nowrap align="center"><?=_("���ϴ�")?></td>
<?
}
if($PHOTO_NAME=="")
{
?>
        <td nowrap align="center" style="font-weight: bold; color: red"><?=_("δ�ϴ�")?></td>
<?
}
else
{
?>
        <td nowrap align="center"><?=_("���ϴ�")?></td>
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
              $ROW[$FIELD_ARRAY[$I]] = _("��");
           else
              $ROW[$FIELD_ARRAY[$I]] = _("Ů");
       }
       if($FIELD_ARRAY[$I]=="STAFF_MARITAL_STATUS")
       {
           if($ROW[$FIELD_ARRAY[$I]]=="0")
              $ROW[$FIELD_ARRAY[$I]] = _("δ��");
           elseif($ROW[$FIELD_ARRAY[$I]]=="1")
              $ROW[$FIELD_ARRAY[$I]] = _("�ѻ�");
           elseif($ROW[$FIELD_ARRAY[$I]]=="1")
              $ROW[$FIELD_ARRAY[$I]] = _("����");
           elseif($ROW[$FIELD_ARRAY[$I]]=="1")
              $ROW[$FIELD_ARRAY[$I]] = _("ɥż");
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
      <td nowrap align="center">
        <a href="staff_info.php?USER_ID=<?=$USER_ID?>"> <?=_("�༭")?></a>
      </td>
    </tr>
<?
} //ѭ������

	if (($USER_COUNT!="")&&($USER_COUNT!=0) && ($_SESSION['LOGIN_USER_PRIV']==1 || $is_manager ==1))
	{
?>

    <tr class="TableFooter">
     	<td colspan="<?=count($FIELD_ARRAY)+5?>" nowrap>
     	<input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("ȫѡ")?></label> &nbsp;
      <a href="javascript:delete_mail();" title="<?=_("ɾ����ѡ���µ���")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("ɾ��")?></a>&nbsp;
      <a href="javascript:delete_all('<?=$DEPT_ID?>');" title="<?=_("ɾ���ò���ȫ�����µ���")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("ȫ��ɾ��")?></a>&nbsp;
	</td>
    </tr>
<?
     }
if($USER_COUNT==0)
{
   Message("",_("�ò�����δ�������µ�����Ϣ"));
}
?>
</table>
</body>
</html>