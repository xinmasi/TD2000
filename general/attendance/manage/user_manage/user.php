<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");
include_once("../priv_users_func.php");

$MODULE_ID=9;
include_once("inc/my_priv.php");
$priv_users=module_priv_user_ids($MY_PRIV);

$HTML_PAGE_TITLE = _("��Ա���ڹ���");
include_once("inc/header.inc.php");
$PARA_ARRAY=get_sys_para("ENTRY_RESET_LEAVE");
$entry_reset_leave = $PARA_ARRAY["ENTRY_RESET_LEAVE"];//�Ƿ�������ְ���ڼ������
?>

<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
function CheckForm1()
{
   if(document.form1.DATE1.value=="")
   { alert("<?=_('��ʼ���ڲ���Ϊ�գ�')?>");
     return (false);
   }

   if(document.form1.DATE2.value=="")
   { alert("<?=_('��ֹ���ڲ���Ϊ�գ�')?>");
     return (false);
   }

   return (true);
}

function CheckForm2()
{
   if(document.form2.SOME_DATE.value=="")
   { alert("<?=_('��ѯ���ڲ���Ϊ�գ�')?>");
     return (false);
   }

   return (true);
}
function CheckForm3()
{
   if(document.form3.DATE1.value=="")
   { alert("<?=_('��ʼ���ڲ���Ϊ�գ�')?>");
     return (false);
   }

   if(document.form3.DATE2.value=="")
   { alert("<?=_('��ֹ���ڲ���Ϊ�գ�')?>");
     return (false);
   }

   return (true);
}
function remark(USER_ID,REGISTER_TYPE,REGISTER_TIME)
{
  URL="remark.php?USER_ID="+USER_ID+"&REGISTER_TYPE="+REGISTER_TYPE+"&REGISTER_TIME="+REGISTER_TIME;
  myleft=(screen.availWidth-650)/2;
  window.open(URL,"formul_edit","height=250,width=450,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}

function to_excel()
{
   DATE1=document.form1.DATE1.value;
   DATE2=document.form1.DATE2.value;
   USER_ID=document.form1.USER_ID.value;
   window.location="excel.php?USER_ID="+USER_ID+"&DATE1="+DATE1+"&DATE2="+DATE2;
}
function to_excel_shift()
{
   DATE1=document.form3.DATE1.value;
   DATE2=document.form3.DATE2.value;
   USER_ID=document.form3.USER_ID.value;
   window.location="excel_shift.php?USER_ID="+USER_ID+"&DATE1="+DATE1+"&DATE2="+DATE2;
}
</script>


<body class="attendance" topmargin="5">

<?
$SOME_DATE=date("Y-m-d",time());

$query1="SELECT * from USER_EXT,USER where USER.UID=USER_EXT.UID and USER.USER_ID='$USER_ID'";
$cursor1= exequery(TD::conn(),$query1);
if($ROW=mysql_fetch_array($cursor1))
{
   $DUTY_TYPE=$ROW["DUTY_TYPE"];
   $USER_NAME=$ROW["USER_NAME"];
   $DEPT_ID=$ROW["DEPT_ID"];
}

if($MODULE_ID!="" && ($DEPT_PRIV=="2" || $DEPT_PRIV=="3" || $DEPT_PRIV=="6" || $DEPT_PRIV=="4" || $ROLE_PRIV=="3"))
{
   $query1 = "SELECT * from MODULE_PRIV where UID='".$_SESSION["LOGIN_UID"]."' and MODULE_ID='$MODULE_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
   {
      $DEPT_ID_STR=$ROW["DEPT_ID"];
      $PRIV_ID_STR=$ROW["PRIV_ID"];
      $USER_ID_STR=$ROW["USER_ID"];
   }
}
else if($MODULE_ID=="" && $DEPT_PRIV=="2")
{
   $query = "SELECT POST_DEPT from USER where UID='".$_SESSION["LOGIN_UID"]."'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $DEPT_ID_STR=$ROW["POST_DEPT"];
}

if(!find_id($priv_users,$USER_ID) && $_SESSION["LOGIN_USER_PRIV"]!=1)
{
	  Message(_("����"),_("�����ڹ���Χ�ڵ��û�"));
   exit;
}
$CUR_DATE=date("Y-m-d",time());
$query = "SELECT LEAVE_TYPE,DATES_EMPLOYED from HR_STAFF_INFO where USER_ID='$USER_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	$LEAVE_TYPE=$ROW["LEAVE_TYPE"];
    $DATES_EMPLOYED = $ROW["DATES_EMPLOYED"];//��ְʱ��
}

if($DATES_EMPLOYED!="0000-00-00" && $DATES_EMPLOYED!="")
{
    $agearray = explode("-",$DATES_EMPLOYED);
    $cur = explode("-",$CUR_DATE);
    $year=$agearray[0];
    $JOB_AGE=date("Y")-$year;
    if($cur[1] > $agearray[1] || $cur[1]==$agearray[1] && $cur[2]>=$agearray[2])
    {
        $JOB_AGE++;
    }
}
else
{
    $JOB_AGE="";
}
if($entry_reset_leave=="1")
{   
    $sql = "select leave_day from attend_leave_param where working_years <= '$JOB_AGE' order by working_years DESC";
    $result= exequery(TD::conn(),$sql);
    if($ROW=mysql_fetch_array($result))
    {
        $LEAVE_TYPE = $ROW['leave_day'];
    }
}
if($DATES_EMPLOYED=="" || $DATES_EMPLOYED=="0000-00-00" || $entry_reset_leave==0)
{
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
    $BEGIN_TIME = $CUR_YEAR."$ANNUAL_BEGIN_TIME";
    $END_TIME = $CUR_YEAR."$ANNUAL_END_TIME";
    //$BEGIN_TIME=substr($CUR_DATE,0,4)."-01-01 00:00:01";
    //$END_TIME=substr($CUR_DATE,0,4)."-12-30 23:59:59";
    //�����ʽΪ-01-01 00:00:01����������1
    if('-01-01 00:00:01' != $ANNUAL_BEGIN_TIME){
        $CUR_YEAR +=1;
        $END_TIME = $CUR_YEAR.$ANNUAL_END_TIME;
    }
    $query = "SELECT * from ATTEND_LEAVE where USER_ID='$USER_ID' and (ALLOW='1' or ALLOW='3') and LEAVE_DATE1 >='$BEGIN_TIME' and LEAVE_DATE1 <='$END_TIME'";
    $cursor= exequery(TD::conn(),$query);
    $LEAVE_DAYS=0;
    $ANNUAL_LEAVE_DAYS=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $LEAVE_DATE1=$ROW["LEAVE_DATE1"];
        $LEAVE_DATE2=$ROW["LEAVE_DATE2"];
        $ANNUAL_LEAVE=$ROW["ANNUAL_LEAVE"];

        $DAY_DIFF= DateDiff_("d",$LEAVE_DATE1,$LEAVE_DATE2);

        $LEAVE_DAYS+=$DAY_DIFF;
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
    $cur_time  = date("Y-m-d H:i:s",time());
    
    $annual_leave_days = 0;
    
    if((int)$cur_month>(int)$month)
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
    $query = "SELECT * from ATTEND_LEAVE where USER_ID='$USER_ID' and (ALLOW='1' or ALLOW='3') and LEAVE_DATE1 >='$begin_time' and LEAVE_DATE1 <='$end_time'";
    $cursor= exequery(TD::conn(),$query);
    $LEAVE_DAYS=0;
    $ANNUAL_LEAVE_DAYS=0;
    while($ROW=mysql_fetch_array($cursor))
    {
       $LEAVE_DATE1=$ROW["LEAVE_DATE1"];
       $LEAVE_DATE2=$ROW["LEAVE_DATE2"];
       $ANNUAL_LEAVE=$ROW["ANNUAL_LEAVE"];

       $DAY_DIFF= DateDiff_("d",$LEAVE_DATE1,$LEAVE_DATE2);
       $LEAVE_DAYS+=$DAY_DIFF;
       $LEAVE_DAYS=number_format($LEAVE_DAYS, 1, '.', ' ');
       $ANNUAL_LEAVE_DAYS+=$ANNUAL_LEAVE;
       $ANNUAL_LEAVE_DAYS=number_format($ANNUAL_LEAVE_DAYS, 1, '.', ' ');
    }
}
$ANNUAL_LEAVE_LEFT=$LEAVE_TYPE-$ANNUAL_LEAVE_DAYS;

if($ANNUAL_LEAVE_LEFT < 0)
   $ANNUAL_LEAVE_LEFT = 0;
   
$MSG = sprintf(_("����������%.1f�죬ռ�����ݼ�%.1f�죬"), $LEAVE_DAYS,$ANNUAL_LEAVE_DAYS);
$TITLE_STR=$USER_NAME.$MSG;
$TITLE_STR.=sprintf(_("���ݼ�ʣ��%s��"),"<font color=\"red\">".$ANNUAL_LEAVE_LEFT."</font>");

Message("",$TITLE_STR);
if($DUTY_TYPE!='99')
{
//---- ȡ�涨���°�ʱ�� -----
$query = "SELECT * from ATTEND_CONFIG where DUTY_TYPE='$DUTY_TYPE'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $DUTY_NAME=$ROW["DUTY_NAME"];
    $GENERAL=$ROW["GENERAL"];

    $DUTY_TIME1=$ROW["DUTY_TIME1"];
    $DUTY_TIME2=$ROW["DUTY_TIME2"];
    $DUTY_TIME3=$ROW["DUTY_TIME3"];
    $DUTY_TIME4=$ROW["DUTY_TIME4"];
    $DUTY_TIME5=$ROW["DUTY_TIME5"];
    $DUTY_TIME6=$ROW["DUTY_TIME6"];

    $DUTY_TYPE1=$ROW["DUTY_TYPE1"];
    $DUTY_TYPE2=$ROW["DUTY_TYPE2"];
    $DUTY_TYPE3=$ROW["DUTY_TYPE3"];
    $DUTY_TYPE4=$ROW["DUTY_TYPE4"];
    $DUTY_TYPE5=$ROW["DUTY_TYPE5"];
    $DUTY_TYPE6=$ROW["DUTY_TYPE6"];
}

$WEEK=date("w",strtotime($SOME_DATE));
$HOLIDAY="";
$query="select * from ATTEND_HOLIDAY where BEGIN_DATE <='$SOME_DATE' and END_DATE>='$SOME_DATE'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $HOLIDAY="<font color='#008000'>"._("�ڼ���")."</font>";
else
{
   if(find_id($GENERAL,$WEEK))
      $HOLIDAY="<font color='#008000'>"._("������")."</font>";
}

if($HOLIDAY=="")
{
   $query="select * from ATTEND_EVECTION where USER_ID='$USER_ID' and ALLOW='1' and to_days(EVECTION_DATE1)<=to_days('$SOME_DATE') and to_days(EVECTION_DATE2)>=to_days('$SOME_DATE')";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $HOLIDAY="<font color='#008000'>"._("����")."</font>";
}

$query = "SELECT PARA_VALUE from SYS_PARA where PARA_NAME='NO_DUTY_USER'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $NO_DUTY_USER=$ROW["PARA_VALUE"];

if(find_id($NO_DUTY_USER,$USER_ID))
   $NO_DUTY_STR = sprintf(_("%sΪ��ǩ��Ա"),"<font color=red>".$USER_NAME)."</font>";
?>
<!----  ���°�Ǽ� ---->
<h5 class="attendance-title"><?=_("�������°�Ǽ�")?> (<?=$DUTY_NAME?>) <?=$NO_DUTY_STR?></h5>

<table class="table table-bordered"  width="95%" align="center">
    <tr class="">
      <th nowrap align="center"><?=_("�ǼǴ���")?></th>
      <th nowrap align="center"><?=_("�Ǽ�����")?></th>
      <th nowrap align="center"><?=_("�涨ʱ��")?></th>
      <th nowrap align="center"><?=_("�Ǽ�ʱ��")?></th>
      <th nowrap align="center"><?=_("�Ǽ�IP")?></th>
    </tr>
<?

 //---- �鿴�������°���� -----
 $CUR_DATE=date("Y-m-d",time()); 

 //---- ��1�� -----
 for($I=1;$I<=6;$I++)
 {
    $DUTY_TIME_I="DUTY_TIME".$I;
    $DUTY_TIME_I=$$DUTY_TIME_I;

    if($DUTY_TIME_I=="" || $DUTY_TIME_I=="00:00:00")
       continue;

    $HOLIDAY1="";
    if($HOLIDAY=="")
    {
       $query="select * from ATTEND_LEAVE where USER_ID='$USER_ID' and ALLOW='1' and LEAVE_DATE1<='$SOME_DATE $DUTY_TIME_I' and LEAVE_DATE2>='$SOME_DATE $DUTY_TIME_I'";
       $cursor= exequery(TD::conn(),$query);
       if($ROW=mysql_fetch_array($cursor))
          $HOLIDAY1="<font color='#008000'>"._("���")."</font>";
    }
    else
       $HOLIDAY1=$HOLIDAY;

    if($HOLIDAY==""&&$HOLIDAY1=="")
    {
       $query="select * from ATTEND_OUT where USER_ID='$USER_ID' and ALLOW='1' and to_days(SUBMIT_TIME)=to_days('$SOME_DATE') and OUT_TIME1<='".substr($DUTY_TIME_I,0,strrpos($DUTY_TIME_I,":"))."' and OUT_TIME2>='".substr($DUTY_TIME_I,0,strrpos($DUTY_TIME_I,":"))."'";
       $cursor= exequery(TD::conn(),$query);
       if($ROW=mysql_fetch_array($cursor))
          $HOLIDAY1="<font color='#008000'>"._("���")."</font>";
    }

    $REGISTER_TIME="";
    $REGISTER_IP="";
    $REMARK="";
    $query = "SELECT * from ATTEND_DUTY where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$CUR_DATE') and REGISTER_TYPE='$I'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
       $REGISTER_TIME=$ROW["REGISTER_TIME"];
       $REGISTER_TIME2=$ROW["REGISTER_TIME"];
       $REGISTER_IP=$ROW["REGISTER_IP"];
       $REMARK=$ROW["REMARK"];
       $REMARK=str_replace("\n","<br>",$REMARK);
       $REGISTER_TIME=strtok($REGISTER_TIME," ");
       $REGISTER_TIME=strtok(" ");

       if($HOLIDAY1=="" && $I%2!=0 && compare_time($REGISTER_TIME,$DUTY_TIME_I)==1)
          {
          	/*
          	 * 2014-06-26 ����
          	* ��ѯ���û���ǩ��ʱ����Ƿ��������ʱ��Σ������������ʾΪ��١�
          	* ע�͵Ĵ���Ϊԭ����
          	*/
          	$query = "select LEAVE_TYPE2 from ATTEND_LEAVE where USER_ID='" . $_GET ["USER_ID"] . "' and (ALLOW='1' or ALLOW='3') and LEAVE_DATE2>='$SOME_DATE $DUTY_TIME_I' and '$SOME_DATE $DUTY_TIME_I' >=LEAVE_DATE1";
          	$cursor = exequery ( TD::conn (), $query );
          	if ($ROW = mysql_fetch_array ($cursor)) { 
          		$LEAVE_TYPE2 = $ROW ["LEAVE_TYPE2"];
          		$LEAVE_TYPE2 = get_hrms_code_name ( $LEAVE_TYPE2, "ATTEND_LEAVE" );
          		$OUT = "<font color='#008000'>" . _ ( "(���" ) . "-$LEAVE_TYPE2</font>)";
          		$REGISTER_TIME .= " <span class=big4>" . _ ( "�ٵ�" ) . "</span>".$OUT;
          	}else{
          		$REGISTER_TIME .= " <span class=big4>" . _ ( "�ٵ�" ) . "</span>";
          	}
          	//$REGISTER_TIME .= " <span class=big4>" . _ ( "�ٵ�" ) . "</span>";
    	}

       if($HOLIDAY1==""&& $I%2==0 && compare_time($REGISTER_TIME,$DUTY_TIME_I)==-1)
		{
 				/*
				* 2014-06-26 ����
				* ��ѯ���û���ǩ��ʱ����Ƿ��������ʱ��Σ������������ʾΪ��١�
				* ע�͵Ĵ���Ϊԭ����
				*/
				$query = "select LEAVE_TYPE2 from ATTEND_LEAVE where USER_ID='" . $_GET ["USER_ID"]. "' and (ALLOW='1' or ALLOW='3') and LEAVE_DATE2>'$SOME_DATE $DUTY_TIME_I' and '$SOME_DATE $DUTY_TIME_I' >LEAVE_DATE1";
				$cursor = exequery ( TD::conn (), $query );
				if ($ROW = mysql_fetch_array ( $cursor )) {
					$LEAVE_TYPE2 = $ROW ["LEAVE_TYPE2"];
					$LEAVE_TYPE2 = get_hrms_code_name ( $LEAVE_TYPE2, "ATTEND_LEAVE" );
					$OUT = "<font color='#008000'>" . _ ( "(���" ) . "-$LEAVE_TYPE2</font>)";
					$REGISTER_TIME .= " <span class=big4>" . _ ( "����" ) . "</span>".$OUT;
				}else{
					$REGISTER_TIME .= " <span class=big4>" . _ ( "����" ) . "</span>";
				}
				//$REGISTER_TIME .= " <span class=big4>" . _ ( "����" ) . "</span>";
			}

       if($REMARK!="")
          $REMARK="<br>"._("��ע��").$REMARK;
    }

    if($I%2==0)
       $DUTY_TYPE_DESC=_("�°�Ǽ�");
    else
       $DUTY_TYPE_DESC=_("�ϰ�Ǽ�");

$MSG = sprintf(_("��%d�εǼ�"), $I);
?>
    <tr class="">
      <td nowrap align="center"><?=$MSG?></td>
      <td nowrap align="center"><?=$DUTY_TYPE_DESC?></td>
      <td nowrap align="center"><?=$DUTY_TIME_I?></td>
      <td nowrap align="center">
<?
 if($REGISTER_TIME=="" && $HOLIDAY1 =="")
      {
      		$query = "select LEAVE_TYPE2 from ATTEND_LEAVE where USER_ID='" . $_GET ["USER_ID"] . "' and (ALLOW='1' or ALLOW='3') and LEAVE_DATE2>'$SOME_DATE $DUTY_TIME_I' and '$SOME_DATE $DUTY_TIME_I' >LEAVE_DATE1";
				@$cursor = exequery ( TD::conn (), $query );
				if (@$ROW = mysql_fetch_array ( $cursor )) {
					$LEAVE_TYPE2 = $ROW ["LEAVE_TYPE2"];
					$LEAVE_TYPE2 = get_hrms_code_name ( $LEAVE_TYPE2, "ATTEND_LEAVE" );
					$OUT = "<font color='#008000'>" . _ ( "���" ) . "-$LEAVE_TYPE2</font>";
					echo $OUT;
		         }else{
		         	$$query="select * from ATTEND_EVECTION where USER_ID='" . $_GET ["USER_ID"] . "' and (ALLOW='1' or ALLOW='3') and to_days(EVECTION_DATE1)<=to_days('$SOME_DATE $DUTY_TIME_I') and to_days(EVECTION_DATE2)>=to_days('$SOME_DATE $DUTY_TIME_I')";
					$cursor= exequery(TD::conn(),$query, $connstatus);
					if($ROW=mysql_fetch_array($cursor))
					{
						echo "<font color='#008000'>"._("����")."</font>";
					}else{
						echo _("δ�Ǽ�");
					}

		         		         	
	             }		         	
	  }
      else
         echo $REGISTER_TIME.$HOLIDAY1;

      if($REMARK!="")
         echo "   <a href=\"javascript:remark('$USER_ID','$I','$REGISTER_TIME2');\" title=\""._("�޸ı�ע")."\">"._("�޸�")."</a>";

?>
      </td>
      <td nowrap align="center"><?=$REGISTER_IP?></td>
    </tr>
<?
 }
?>
</table>
<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>

<h5 class="attendance-title"><?=_("���ڲ�ѯ��ͳ��")?></h5>
<?
$CUR_DATE_FIRST=date("Y-m-01",time());
?>
<form action="search.php" name="form1" onSubmit="return CheckForm1();">
<table align="center" class="table table-bordered" width="95%">
<tr>
<th colspan=2>
<?=_("����ͳ��")?>
</th>
</tr>
<tr>
<td>
<?=_("��ʼ���ڣ�")?><input type="text" name="DATE1" size="10" maxlength="10" class="input-small" id="start_time" value="<?=$CUR_DATE_FIRST?>" onClick="WdatePicker({maxDate:'%y-%M-%d %H-%m-%s'})"/>
&nbsp;
<?=_("��ֹ���ڣ�")?><input type="text" name="DATE2" size="10" maxlength="10" class="input-small" value="<?=$CUR_DATE?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>
</td>
<td>
<input type="hidden" name="USER_ID" value="<?=$USER_ID?>">
<input type="submit" value=<?=_("ͳ��")?> class="btn">
<input type="button" value=<?=_("����")?> class="btn btn-primary" onClick="to_excel()" title=<?=_("������Ա���ڼ�¼")?> name="button">
</td>
</tr>
</table>
</form>
<form action="some_day.php" name="form2" onSubmit="return CheckForm2();">
<table align="center" class="table table-bordered" width=450>
<tr  >
<td colspan=2>
<?=_("���°�Ǽǲ�ѯ")?>
</td>
</tr>
<tr>
<td class=TableData>
<?=_("��ѯ���ڣ�")?><input type="text" name="SOME_DATE" size="10" maxlength="10" class="" value="<?=$CUR_DATE?>" onClick="WdatePicker({maxDate:'%y-%M-%d %H-%m-%s'})"/>
</td>
<td  width=80>
<input type="hidden" name="USER_ID" value="<?=$USER_ID?>">
<input type="submit" value=<?=_("��ѯ")?> class="btn btn-primary" title=<?=_("���°�Ǽǲ�ѯ")?>>
</td>
</tr>
</table>
</form>
<?
}
else
{

if($HOLIDAY=="")
{
   $query="select * from ATTEND_EVECTION where USER_ID='$USER_ID' and ALLOW='1' and to_days(EVECTION_DATE1)<=to_days('$SOME_DATE') and to_days(EVECTION_DATE2)>=to_days('$SOME_DATE')";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $HOLIDAY="<font color='#008000'>"._("����")."</font>";
}

$query = "SELECT PARA_VALUE from SYS_PARA where PARA_NAME='NO_DUTY_USER'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $NO_DUTY_USER=$ROW["PARA_VALUE"];

if(find_id($NO_DUTY_USER,$USER_ID))
   $NO_DUTY_STR = sprintf(_("%sΪ��ǩ��Ա"),"<font color=red>".$USER_NAME)."</font>";
?>
<!----  ���°�Ǽ� ---->
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("�������°�Ǽ�")?> (<?=_("�ְ���")?>) <?=$NO_DUTY_STR?></span>
    </td>
  </tr>
</table>

<table class="TableList"  width="95%" align="center">
    <tr class="TableHeader">
      <td nowrap align="center"><?=_("�Ǽ�����")?></td>
      <td nowrap align="center"><?=_("�Ǽ�ʱ��")?></td>
      <td nowrap align="center"><?=_("�Ǽ�IP")?></td>
    </tr>
<?

//---- �鿴�������°���� -----
$CUR_DATE=date("Y-m-d",time());
//---- ��1�� -----
$HOLIDAY1="";
if($HOLIDAY=="")
{
   $query="select * from ATTEND_LEAVE where USER_ID='$USER_ID' and ALLOW='1' and LEAVE_DATE1<='$SOME_DATE $DUTY_TIME_I' and LEAVE_DATE2>='$SOME_DATE $DUTY_TIME_I'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $HOLIDAY1="<font color='#008000'>"._("���")."</font>";
}
else
   $HOLIDAY1=$HOLIDAY;

if($HOLIDAY==""&&$HOLIDAY1=="")
{
   $query="select * from ATTEND_OUT where USER_ID='$USER_ID' and ALLOW='1' and to_days(SUBMIT_TIME)=to_days('$SOME_DATE') and OUT_TIME1<='".substr($DUTY_TIME_I,0,strrpos($DUTY_TIME_I,":"))."' and OUT_TIME2>='".substr($DUTY_TIME_I,0,strrpos($DUTY_TIME_I,":"))."'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $HOLIDAY1="<font color='#008000'>"._("���")."</font>";
}

$REGISTER_TIME="";
$REGISTER_IP="";
$REMARK="";
$query = "SELECT * from ATTEND_DUTY_SHIFT where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$CUR_DATE') and REGISTER_TYPE='$I'";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $REGISTER_TIME=$ROW["REGISTER_TIME"];
   $REGISTER_IP=$ROW["REGISTER_IP"];
   $SXB=$ROW["SXB"];
   $REGISTER_TIME=strtok($REGISTER_TIME," ");
   $REGISTER_TIME=strtok(" ");

   if($HOLIDAY1==""&& $I%2!=0 && compare_time($REGISTER_TIME,$DUTY_TIME_I)==1)
   	{
   		/*
   		 * 2014-06-26 ����
   		* ��ѯ���û���ǩ��ʱ����Ƿ��������ʱ��Σ������������ʾΪ��١�
   		* ע�͵Ĵ���Ϊԭ����
   		*/
   		$query = "select LEAVE_TYPE2 from ATTEND_LEAVE where USER_ID='" . $_GET ["USER_ID"]. "' and (ALLOW='1' or ALLOW='3') and LEAVE_DATE2>'$SOME_DATE $DUTY_TIME_I' and '$SOME_DATE $DUTY_TIME_I' >LEAVE_DATE1";
   		$cursor = exequery ( TD::conn (), $query );
   		if ($ROW = mysql_fetch_array ( $cursor )) {
   			$LEAVE_TYPE2 = $ROW ["LEAVE_TYPE2"];
   			$LEAVE_TYPE2 = get_hrms_code_name ( $LEAVE_TYPE2, "ATTEND_LEAVE" );
   			$OUT = "<font color='#008000'>" . _ ( "(���" ) . "-$LEAVE_TYPE2</font>)";
   			$REGISTER_TIME .= " <span class=big4>" . _ ( "�ٵ�" ) . "</span>".$OUT;
   		}else{
   			$REGISTER_TIME .= " <span class=big4>" . _ ( "�ٵ�" ) . "</span>";
   		}
   		//$REGISTER_TIME .= " <span class=big4>" . _ ( "�ٵ�" ) . "</span>";
   	}
     

   			if($HOLIDAY1==""&& $I%2==0 && compare_time($REGISTER_TIME,$DUTY_TIME_I)==-1)
			{
 				/*
				* 2014-06-26 ����
				* ��ѯ���û���ǩ��ʱ����Ƿ��������ʱ��Σ������������ʾΪ��١�
				* ע�͵Ĵ���Ϊԭ����
				*/
				$query = "select LEAVE_TYPE2 from ATTEND_LEAVE where USER_ID='" . $_GET ["USER_ID"] . "' and (ALLOW='1' or ALLOW='3') and LEAVE_DATE2>'$SOME_DATE $DUTY_TIME_I' and '$SOME_DATE $DUTY_TIME_I' >LEAVE_DATE1";
				$cursor = exequery ( TD::conn (), $query );
				if ($ROW = mysql_fetch_array ( $cursor )) {
					$LEAVE_TYPE2 = $ROW ["LEAVE_TYPE2"];
					$LEAVE_TYPE2 = get_hrms_code_name ( $LEAVE_TYPE2, "ATTEND_LEAVE" );
					$OUT = "<font color='#008000'>" . _ ( "(���" ) . "-$LEAVE_TYPE2</font>)";
					$REGISTER_TIME .= " <span class=big4>" . _ ( "����" ) . "</span>".$OUT;
				}else{
					$REGISTER_TIME .= " <span class=big4>" . _ ( "����" ) . "</span>";
				}
				//$REGISTER_TIME .= " <span class=big4>" . _ ( "����" ) . "</span>";
			}

   if($REMARK!="")
      $REMARK="<br>"._("��ע��").$REMARK;

   if($SXB=="1")
     $SXB=_("�ϰ�Ǽ�");
   else if($SXB=="2") 
     $SXB=_("�°�Ǽ�");
?>
    <tr class="TableData">
      <td nowrap align="center"><?=$SXB?></td>
      <td nowrap align="center"><?=$REGISTER_TIME?></td>
      <td nowrap align="center"><?=$REGISTER_IP?></td>
    </tr>
<?
}
$MSG = sprintf(_("��%d�εǼ�"), $I);
?>

</table>

<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("���ڲ�ѯ��ͳ��")?></span>
    </td>
  </tr>
</table>

<?
$CUR_DATE_FIRST=date("Y-m-01",time());
?>
<form action="search_shift.php" name="form3" onSubmit="return CheckForm3();">
<table align="center" class="TableList" width="95%">
<tr class=TableHeader >
<td colspan=2>
<?=_("����ͳ��")?>
</td>
</tr>
<tr>
<td class=TableData>
<?=_("��ʼ���ڣ�")?><input type="text" name="DATE1" size="10" maxlength="10" class="input-small" value="<?=$CUR_DATE_FIRST?>" onClick="WdatePicker()"/>
&nbsp;
<?=_("��ֹ���ڣ�")?><input type="text" name="DATE2" size="10" maxlength="10" class="input-small" value="<?=$CUR_DATE?>" onClick="WdatePicker()"/>
</td>
<td class=TableData>
<input type="hidden" name="USER_ID" value="<?=$USER_ID?>">
<input type="submit" value=<?=_("ͳ��")?> class="btn">
<input type="button" value=<?=_("����")?> class="btn btn-primary" onClick="to_excel_shift()" title=<?=_("�����ְ���Ա���ڼ�¼")?> name="button">
</td>
</tr>
</table>
</form>   
<? 	
}
?>
</body>
</html>