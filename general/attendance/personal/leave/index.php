<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/flow_hook.php");
$connstatus = ($connstatus) ? true : false;
$query = "select * from SMS2_PRIV";
$cursor2=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor2))
   $TYPE_PRIV=$ROW["TYPE_PRIV"];

$query="select * from SYS_PARA where PARA_NAME='SMS_REMIND'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $PARA_VALUE=$ROW["PARA_VALUE"];

$SMS2_REMIND_TMP=substr($PARA_VALUE,strpos($PARA_VALUE,"|")+1);
$SMS2_REMIND=substr($SMS2_REMIND_TMP,0,strpos($SMS2_REMIND_TMP,"|"));

$HTML_PAGE_TITLE = _("���");
include_once("inc/header.inc.php");
$PARA_ARRAY=get_sys_para("LEAVE_BY_SENIORITY,ENTRY_RESET_LEAVE");
$entry_reset_leave = $PARA_ARRAY["ENTRY_RESET_LEAVE"];//�Ƿ�������ְ���ڼ������
$leave_by_seniority = $PARA_ARRAY["LEAVE_BY_SENIORITY"];//�Ƿ���������������
?>

<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script Language=JavaScript>
//window.setTimeout('this.location.reload();',120000);

function leave_confirm(LEAVE_ID)
{
  var msg='<?=_("ȷ��Ҫ����������")?>';
  if(window.confirm(msg))
  {
<?
if(find_id($TYPE_PRIV,6)) //����ģ���Ƿ������ֻ�����
{
?>
	  if(document.all("LEAVE_SMS2_REMIND"+LEAVE_ID).checked)
	     MOBILE_FLAG=1;
    else
<?
}
?>
       MOBILE_FLAG=0;
    URL="back.php?LEAVE_ID="+LEAVE_ID+"&MOBILE_FLAG="+MOBILE_FLAG;
    window.location=URL;
  }
}

function form_view(RUN_ID)
{
window.open("/general/workflow/list/print/?RUN_ID="+RUN_ID,"","status=0,toolbar=no,menubar=no,width="+(screen.availWidth-12)+",height="+(screen.availHeight-38)+",location=no,scrollbars=yes,resizable=yes,left=0,top=0");
}

function delete_alert(LEAVE_ID)
{
    msg='<?=_("ȷ��Ҫɾ���������Ϣ��")?>';
    
    if(window.confirm(msg))
    {
        URL="delete.php?LEAVE_ID=" + LEAVE_ID;
        window.location=URL;
    }
}

</script>


<body class="bodycolor attendance" topmargin="5">

<?
//�޸���������״̬--yc
update_sms_status('6',0);

$CUR_DATE=date("Y-m-d",time());
$query = "SELECT LEAVE_TYPE,DATES_EMPLOYED from HR_STAFF_INFO where USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	 $LEAVE_TYPE=$ROW["LEAVE_TYPE"];//���ݼ��ܼ�
     $DATES_EMPLOYED = $ROW["DATES_EMPLOYED"];//��ְʱ��
}     

//��ȡSYS_PARA���ݿ�����ݼٿ�ʼʱ��ͽ���ʱ��20131014
$query="select * from SYS_PARA where PARA_NAME='ANNUAL_BEGIN_TIME'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $ANNUAL_BEGIN_TIME=$ROW["PARA_VALUE"];
$query="select * from SYS_PARA where PARA_NAME='ANNUAL_END_TIME'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $ANNUAL_END_TIME=$ROW["PARA_VALUE"];

$CUR_YEAR = date("Y",time());
$CUR_M = date("m",time());
$CUR_D = date("d",time());

$ANNUAL_BEGIN_TIME_ARRAY=explode('-',$ANNUAL_BEGIN_TIME);

$DAY_INFO = strtotime($CUR_YEAR.$ANNUAL_BEGIN_TIME);
$DAY_INFO1=date('d',$DAY_INFO);


if($CUR_M < $ANNUAL_BEGIN_TIME_ARRAY[1])
{
    $CUR_YEAR1=$CUR_YEAR-1;
    $BEGIN_TIME = strtotime($CUR_YEAR1.$ANNUAL_BEGIN_TIME);
    $END_TIME = strtotime($CUR_YEAR.$ANNUAL_END_TIME);
}
elseif($CUR_M == $ANNUAL_BEGIN_TIME_ARRAY[1])
{
	if($CUR_D < $DAY_INFO1)
	{
		$CUR_YEAR1=$CUR_YEAR-1;
    	$BEGIN_TIME = strtotime($CUR_YEAR1.$ANNUAL_BEGIN_TIME);
    	$END_TIME = strtotime($CUR_YEAR.$ANNUAL_END_TIME);
	}
	else
	{
		$CUR_YEAR1=$CUR_YEAR+1;
    	$BEGIN_TIME = strtotime($CUR_YEAR.$ANNUAL_BEGIN_TIME);
    	$END_TIME = strtotime($CUR_YEAR1.$ANNUAL_END_TIME);
	}
}
else
{
	$CUR_YEAR1=$CUR_YEAR+1;
    $BEGIN_TIME = strtotime($CUR_YEAR.$ANNUAL_BEGIN_TIME);
    $END_TIME = strtotime($CUR_YEAR1.$ANNUAL_END_TIME);
}
if($DATES_EMPLOYED!="0000-00-00" && $DATES_EMPLOYED!="")
{
    $agearray = explode("-",$DATES_EMPLOYED);
    $cur = explode("-",$CUR_DATE);
    $year=$agearray[0];
    $month=(int)$agearray[1];
    $day=(int)$agearray[2];
    if(date("Y")>=$year)
    {
        if((int)date("m")>$month ||((int)date("m")==$month && (int)date("d")>=$day))
        {
            $JOB_AGE=date("Y")-$year;
        }
        else
        {
            $JOB_AGE=date("Y")-$year-1;
        }
    }
    else
    {
        $JOB_AGE=0;
    }
}
else
{
    $JOB_AGE="";
}
if($leave_by_seniority=="1")
{   
    $sql = "select leave_day from attend_leave_param where working_years <= '$JOB_AGE' order by working_years DESC";
    $result= exequery(TD::conn(),$sql);
    if($ROW=mysql_fetch_array($result))
    {
        $LEAVE_TYPE = $ROW['leave_day'];
    }
    else
    {
        $LEAVE_TYPE = 0;
    }
}
if($DATES_EMPLOYED=="" || $DATES_EMPLOYED=="0000-00-00" || $entry_reset_leave==0)
{
    $BEGIN_TIME=date('Y-m-d',$BEGIN_TIME);
    $END_TIME=date('Y-m-d',$END_TIME);
    //$BEGIN_TIME=substr($CUR_DATE,0,4)."-01-01 00:00:01";
    //$END_TIME=substr($CUR_DATE,0,4)."-12-30 23:59:59";
    //�����ʽΪ-01-01 00:00:01����������1
    //if('-01-1'!= $ANNUAL_BEGIN_TIME_ARRAY[0]){
    //    $CUR_YEAR +=1;
    //    $END_TIME = $CUR_YEAR.$ANNUAL_END_TIME;
    //}
    $query = "SELECT * from ATTEND_LEAVE where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and (ALLOW='1' or ALLOW='3' or ALLOW='0') and LEAVE_DATE1 >='$BEGIN_TIME' and LEAVE_DATE1 <='$END_TIME'";
    $cursor= exequery(TD::conn(),$query, $connstatus);
    $LEAVE_DAYS=0;
    $ANNUAL_LEAVE_DAYS=0;
    while($ROW=mysql_fetch_array($cursor))
    {
       $LEAVE_DATE1=$ROW["LEAVE_DATE1"];
       $LEAVE_DATE2=$ROW["LEAVE_DATE2"];
       $ANNUAL_LEAVE=$ROW["ANNUAL_LEAVE"];

       $DAY_DIFF= DateDiff_("d",$LEAVE_DATE1,$LEAVE_DATE2);
       if($ANNUAL_LEAVE=='0.0')
       {    
            $LEAVE_DAYS+=$DAY_DIFF;
       }
       else
       {
            $LEAVE_DAYS+=$ANNUAL_LEAVE;    
       }
       $LEAVE_DAYS=number_format($LEAVE_DAYS, 1, '.', ' ');
       $ANNUAL_LEAVE_DAYS+=$ANNUAL_LEAVE;
       $ANNUAL_LEAVE_DAYS=number_format($ANNUAL_LEAVE_DAYS, 1, '.', ' ');
    }
}
else
{
    $str   = strtok($DATES_EMPLOYED,"-");
    $year  = $str;
    $str   = strtok("-");
    $month = $str;
    $str   = strtok(" ");
    $day   = $str;
    
    $cur_year  = date("Y",time());
    $cur_month = date("m",time());
    $cur_day   = date("d",time());
    $cur_time  = date("Y-m-d H:i:s",time());
    
    $annual_leave_days = 0;
    
    if((int)$cur_month>(int)$month || ((int)$cur_month==(int)$month && (int)$cur_day>(int)$day))
    {
        $begin_time = $cur_year."-".$month."-".$day." 00:00:01";
        $cur_years  = $cur_year+1;
        $end_time   = $cur_years."-".$month."-".$day." 00:00:01";;
        
    }else
    {
        $cur_years  = $cur_year-1;
        $begin_time = $cur_years."-".$month."-".$day." 00:00:01";
        $end_time   = $cur_year."-".$month."-".$day." 00:00:01";;
    }
    $query = "SELECT * from ATTEND_LEAVE where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and (ALLOW='1' or ALLOW='3' or ALLOW='0') and LEAVE_DATE1 >='$begin_time' and LEAVE_DATE1 <='$end_time'";
    $cursor= exequery(TD::conn(),$query, $connstatus);
    $LEAVE_DAYS=0;
    $ANNUAL_LEAVE_DAYS=0;
    while($ROW=mysql_fetch_array($cursor))
    {
       $LEAVE_DATE1=$ROW["LEAVE_DATE1"];
       $LEAVE_DATE2=$ROW["LEAVE_DATE2"];
       $ANNUAL_LEAVE=$ROW["ANNUAL_LEAVE"];

       $DAY_DIFF= DateDiff_("d",$LEAVE_DATE1,$LEAVE_DATE2);
       if($ANNUAL_LEAVE=='0.0')
       {    
            $LEAVE_DAYS+=$DAY_DIFF;
       }
       else
       {
            $LEAVE_DAYS+=$ANNUAL_LEAVE;    
       }
       $LEAVE_DAYS=number_format($LEAVE_DAYS, 1, '.', ' ');
       $ANNUAL_LEAVE_DAYS+=$ANNUAL_LEAVE;
       $ANNUAL_LEAVE_DAYS=number_format($ANNUAL_LEAVE_DAYS, 1, '.', ' ');
    }
}
$ANNUAL_LEAVE_LEFT=number_format(($LEAVE_TYPE-$ANNUAL_LEAVE_DAYS), 1, '.', ' ');
if($ANNUAL_LEAVE_LEFT < 0)
   $ANNUAL_LEAVE_LEFT=0;

$MSG = sprintf(_("����������%.1f�죬ռ�����ݼ�%.1f�죬"), $LEAVE_DAYS,$ANNUAL_LEAVE_DAYS);
$TITLE_STR=$MSG;
$TITLE_STR.=_("���ݼ�ʣ��")."<font color=\"red\">".$ANNUAL_LEAVE_LEFT."</font>"._("��");
?>
<h5 class="attendance-title"><span class="big3"> <?=_("��ٵǼ�")?> (<?=$TITLE_STR?>)</span></h5><br>
 
<br>

<div align="center">
<input type="button"  value="<?=_("��ٵǼ�")?>" class="btn btn-primary" onClick="location='new/?ANNUAL_LEAVE_LEFT=<?=$ANNUAL_LEAVE_LEFT?>';" title="<?=_("�½���ٵǼ�")?>">&nbsp;&nbsp;
<input type="button"  value="<?=_("�����ʷ��¼")?>" class="btn" onClick="location='history.php';" title="<?=_("�鿴��������ټ�¼")?>">
<br>
<br>
<table class="table  table-bordered"  width="95%">
    <thead class="">
        <th nowrap align="center"><?=_("���ԭ��")?></th>
        <th nowrap align="center"><?=_("�������")?></th>
        <th nowrap align="center"><?=_("����ʱ��")?></th>
        <th nowrap align="center"><?=_("ռ���ݼ�")?></th>
        <th nowrap align="center"><?=_("������Ա")?></th>
        <th nowrap align="center"><?=_("��ʼʱ��")?></th>
        <th nowrap align="center"><?=_("����ʱ��")?></th>
        <th nowrap align="center"><?=_("��������ʱ��")?></th>
        <th nowrap align="center"><?=_("״̬")?></th>
        <th nowrap align="center"><?=_("����")?></th>
    </thead>
<?
 //---- ���е������� -----
 $LEAVE_COUNT=0;

 $query = "SELECT * from ATTEND_LEAVE where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and STATUS='1'";
 $cursor= exequery(TD::conn(),$query, $connstatus);
 while($ROW=mysql_fetch_array($cursor))
 {
    $LEAVE_COUNT++;

    $LEAVE_ID=$ROW["LEAVE_ID"];
    $LEADER_ID=$ROW["LEADER_ID"];
    $LEAVE_DATE1=$ROW["LEAVE_DATE1"];
    $LEAVE_DATE2=$ROW["LEAVE_DATE2"];
    $LEAVE_TYPE=$ROW["LEAVE_TYPE"];
    $LEAVE_TYPE2=$ROW["LEAVE_TYPE2"];
    $ANNUAL_LEAVE=$ROW["ANNUAL_LEAVE"];
    $REASON=$ROW["REASON"];
    $RECORD_TIME=$ROW["RECORD_TIME"];
    $DESTROY_TIME=$ROW["DESTROY_TIME"];
    if($DESTROY_TIME=="0000-00-00 00:00:00")
       $DESTROY_TIME="";

    $LEAVE_TYPE2=get_hrms_code_name($LEAVE_TYPE2,"ATTEND_LEAVE");

    $LEAVE_TYPE=str_replace("<","&lt",$LEAVE_TYPE);
    $LEAVE_TYPE=str_replace(">","&gt",$LEAVE_TYPE);
    $LEAVE_TYPE=gbk_stripslashes($LEAVE_TYPE);

    $ALLOW=$ROW["ALLOW"];

    if($ALLOW=="0")
       $ALLOW_DESC=_("������");
    else if($ALLOW=="1")
       $ALLOW_DESC="<font color=green>"._("����׼")."</font>";
    else if($ALLOW=="2")
       $ALLOW_DESC="<font color=red>"._("δ��׼")."</font>";
    else if($ALLOW=="3")
       $ALLOW_DESC=_("��������");

    $LEADER_NAME="";
    $query1 = "SELECT * from USER where USER_ID='$LEADER_ID'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
       $LEADER_NAME=$ROW["USER_NAME"];
?>
    <tr class="TableData">
      <td style="word-break:break-all" align="left">
<?
echo $LEAVE_TYPE;
if($REASON!="")
{
   echo "<br>";
   echo "<font color=red>"._("δ׼ԭ��").$REASON."</font>";
}
$MSG2 = sprintf(_("%.1f��"), $ANNUAL_LEAVE);
?>

      </td>
      <td nowrap align="center"><?=$LEAVE_TYPE2?></td>
      <td nowrap align="center"><?=$RECORD_TIME?></td>
      <td nowrap align="center"><?=$MSG2?></td>
<?
      $is_run_hook=is_run_hook("LEAVE_ID",$LEAVE_ID);
      if($is_run_hook!=0)
      {
?>
      <td nowrap align="center"><a href="javascript:;" onClick="form_view('<?=$is_run_hook?>')"><?=_("�鿴����")?></a></td>
<?
      }
      else
      {
?>
      <td nowrap align="center"><?=$LEADER_NAME?></td>
<?
      }
?>
      <td nowrap align="center"><?=$LEAVE_DATE1?></td>
      <td nowrap align="center"><?=$LEAVE_DATE2?></td>
      <td nowrap align="center"><?=$DESTROY_TIME?></td>
      <td nowrap align="center" title="<?if($ALLOW==2) echo _("ԭ��")."\n".$REASON?>"><?=$ALLOW_DESC?></td>
      <td nowrap align="center">
<?
    if($ALLOW=="0" || $ALLOW=="2")
    {
    	if($is_run_hook!=0)
      {
    	   $query2 = "SELECT * from FLOW_RUN where RUN_ID='$is_run_hook' and DEL_FLAG='0'";
         $cursor2= exequery(TD::conn(),$query2);
         if(!$ROW2=mysql_fetch_array($cursor2))
         {
?>
      <a href="delete.php?LEAVE_ID=<?=$LEAVE_ID?>"><?=_("ɾ��")?></a>
<?
         }
     }
     else
     {
?>
      <a href="edit.php?LEAVE_ID=<?=$LEAVE_ID?>"><?=_("�޸�")?></a>
      <a  href="javascript:delete_alert('<?=$LEAVE_ID?>');"><?=_("ɾ��")?></a>
<?
     	}
    }
    else if($ALLOW=="1")
    {

       if(find_id($TYPE_PRIV,6)) //����ģ���Ƿ������ֻ�����
       {
?>
      <input type="checkbox" name="LEAVE_SMS2_REMIND<?=$LEAVE_ID?>" id="LEAVE_SMS2_REMIND<?=$LEAVE_ID?>" <?if(find_id($SMS2_REMIND,"6")) echo "checked";?>><label for="LEAVE_SMS2_REMIND<?=$LEAVE_ID?>"><?=_("�����ֻ���������")?></label>
<?
       }
?>
      <a href="javascript:leave_confirm('<?=$LEAVE_ID?>');"><?=_("��������")?></a>
<?
    }
?>
    </td>
    </tr>
<?
 }

 if($LEAVE_COUNT==0)
 {
?>
    <tr><td colspan="10"><div class="emptyTip"><?=_("����ټ�¼")?></div></td></tr>
<?
 }
?>
</table>
</div>

</body>
</html>
