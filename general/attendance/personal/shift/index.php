<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/chinese_date.php");
$connstatus = ($connstatus) ? true : false;
$PARA_ARRAY = get_sys_para("DUTY_MACHINE");
$DUTY_MACHINE=$PARA_ARRAY["DUTY_MACHINE"];
?>
<?
$HTML_PAGE_TITLE = _("���°�Ǽ�");
include_once("inc/header.inc.php");
?>



<script language="JavaScript">
function remark(REGISTER_TYPE,REGISTER_TIME)
{
  URL="remark.php?REGISTER_TYPE="+REGISTER_TYPE+"&REGISTER_TIME="+REGISTER_TIME;
  myleft=(screen.availWidth-650)/2;
  window.open(URL,"formul_edit","height=250,width=450,status=0,toolbar=no,menubar=no,location=no,scrollbars=no,top=150,left="+myleft+",resizable=yes");
}
</script>
<body class="bodycolor">

<?
 //---- IP���鿪ʼ ---
 $USER_IP=get_client_ip();
 if(!check_ip($USER_IP,"1",$_SESSION["LOGIN_USER_ID"]))
 {
    Message(_("����"),sprintf(_("����Ȩ�޴Ӹ�IP(%s)����!"), $USER_IP));
    exit;
  }
 //---- IP������� ---

 $query1="SELECT * from USER_EXT,USER where USER.UID=USER_EXT.UID and USER.USER_ID='$USER_ID'";
 $cursor1= exequery(TD::conn(),$query1);
 if($ROW=mysql_fetch_array($cursor1))
    $DUTY_TYPE=$ROW["DUTY_TYPE"];


 //---- ȡ�涨���°�ʱ�� -----
  $DUTY_TYPE=intval($DUTY_TYPE);
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
?>

<!----  ���°�Ǽ� ---->
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small" align="center">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("�������°�Ǽ�")?> (<?=$DUTY_NAME?>
    	 <?=_("��ǰʱ�䣺")?><span id="timetable"></span>)</span><br>
<SCRIPT LANGUAGE="JavaScript">
<?
list($CUR_YEAR,$CUR_MON,$CUR_DAY,$CUR_HOUR,$CUR_MINITE,$CUR_SECOND) = DateTimeEx(hexdec(dechex(time()+1)));
$CUR_MON--;
$TIME_STR="$CUR_YEAR,$CUR_MON,$CUR_DAY,$CUR_HOUR,$CUR_MINITE,$CUR_SECOND";
?>
var OA_TIME1 = new Date(<?=$TIME_STR?>);
function get_time()
{
  window.setTimeout( "get_time()", 1000 );
  
  timestr=OA_TIME1.toLocaleString();
  document.getElementById("timetable").innerHTML=timestr;
  OA_TIME1.setSeconds(OA_TIME1.getSeconds()+1);
}

get_time();

</SCRIPT>
    </td>
  </tr>
</table>

<?
$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());
$FLAG=0;
$STR="";
//������������������°�Ǽ�
//1  �ڼ���
$query="select * from ATTEND_HOLIDAY where BEGIN_DATE <='$CUR_DATE' and END_DATE>='$CUR_DATE'";
$cursor1= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor1))
{
  $FLAG=1;
  $STR=_("�ڼ��ղ�����Ǽ�");
}

//2  �����ڼ�
/*
$query="select * from ATTEND_EVECTION where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and STATUS='1' and to_days(EVECTION_DATE1)<=to_days('$CUR_DATE') and to_days(EVECTION_DATE2)>=to_days('$CUR_DATE')";
$cursor1= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor1))
{
  $FLAG=1;
  $STR=_("�������ǰ������Ǽ�");
}
*/

//3  ���
$query="select * from ATTEND_LEAVE where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and ALLOW='1' and LEAVE_DATE1<='$CUR_TIME' and LEAVE_DATE2>='$CUR_TIME'";
$cursor1= exequery(TD::conn(),$query, $connstatus);
if($ROW=mysql_fetch_array($cursor1))
{
  $FLAG=1;
  $STR=_("��������ǰ������Ǽ�");
}

//4  �����
// $query="select count(*) from ATTEND_OUT where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and STATUS='1' and to_days(SUBMIT_TIME)=to_days('$CUR_DATE') and OUT_TIME1<='".substr($DUTY_TIME,0,strrpos($DUTY_TIME,":"))."' and OUT_TIME2>='".substr($DUTY_TIME,0,strrpos($DUTY_TIME,":"))."'";
// $cursor1= exequery(TD::conn(),$query);
// if($ROW=mysql_fetch_array($cursor1))
//    $FLAG=1;

if($FLAG==1)
{
  message("","$STR");
  exit;
}

$query = "SELECT * from SYS_PARA where PARA_NAME='DUTY_INTERVAL_BEFORE1'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $DUTY_INTERVAL_BEFORE1=$ROW["PARA_VALUE"];

$query = "SELECT * from SYS_PARA where PARA_NAME='DUTY_INTERVAL_AFTER1'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $DUTY_INTERVAL_AFTER1=$ROW["PARA_VALUE"];

$query = "SELECT * from SYS_PARA where PARA_NAME='DUTY_INTERVAL_BEFORE2'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $DUTY_INTERVAL_BEFORE2=$ROW["PARA_VALUE"];

$query = "SELECT * from SYS_PARA where PARA_NAME='DUTY_INTERVAL_AFTER2'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $DUTY_INTERVAL_AFTER2=$ROW["PARA_VALUE"];
//�ְ࿼�ڵǼ�  qpp by 2012-06-15
$query = "SELECT * from SYS_PARA where PARA_NAME='SHIFT_USER'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $SHIFT_USER=$ROW["PARA_VALUE"];
}

Message("", sprintf(_("�涨ʱ��֮ǰ %s ���ӵ�֮�� %s �������ʱ��ɽ����ϰ�Ǽǣ��涨ʱ��֮ǰ %s ���ӵ�֮�� %s �������ʱ��ɽ����°�Ǽ�"), $DUTY_INTERVAL_BEFORE1, $DUTY_INTERVAL_AFTER1, $DUTY_INTERVAL_BEFORE2, $DUTY_INTERVAL_AFTER2,$SHIFT_USER));

$SOME_DATE=date("Y-m-d");
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

 //---- �鿴�������°���� -----
?>
<table class="TableList" align="center" width="95%">
    <tr class="TableHeader">
      <td nowrap align="center"><?=_("�ǼǴ���")?></td>
      <td nowrap align="center"><?=_("�Ǽ�����")?></td>
      <td nowrap align="center"><?=_("�涨ʱ��")?></td>
      <td nowrap align="center"><?=_("�Ǽ�ʱ��")?></td>
      <td nowrap align="center"><?=_("����")?></td>
    </tr>
<?
for($I=1;$I<=6;$I++)
{
 $STR="DUTY_TIME".$I;
 $DUTY_TIME=$$STR;

 if($DUTY_TIME=="")
    continue;

 $STR="DUTY_TYPE".$I;
 $DUTY_TYPE=$$STR;

 $REGISTER_TIME="";
 $SIGN="0";

 //--- ѭ��6�β�ѯ���°�ǼǼ�¼ ---
 $query = "SELECT * from ATTEND_DUTY where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and to_days(REGISTER_TIME)=to_days('$CUR_DATE') and REGISTER_TYPE='$I'";
 $cursor= exequery(TD::conn(),$query,true);
 if($ROW=mysql_fetch_array($cursor))
 {
    $REGISTER_TIME=$ROW["REGISTER_TIME"];
    $REGISTER_TIME_BAK=$ROW["REGISTER_TIME"];
    $REGISTER_TIME=strtok($REGISTER_TIME," ");
    $REGISTER_TIME=strtok(" ");

    if($HOLIDAY==""&&$DUTY_TYPE=="1" && compare_time($REGISTER_TIME,$DUTY_TIME)==1)
      {$REGISTER_TIME.=" <span class=big4>"._("�ٵ�")."</span>";$SIGN="1";}

    if($HOLIDAY==""&&$DUTY_TYPE=="2" && compare_time($REGISTER_TIME,$DUTY_TIME)==-1)
      {$REGISTER_TIME.=" <span class=big4>"._("����")."</span>";$SIGN="1";}
 }

 $DUTY_INTERVAL_BEFORE="DUTY_INTERVAL_BEFORE".$DUTY_TYPE;
 $DUTY_INTERVAL_AFTER="DUTY_INTERVAL_AFTER".$DUTY_TYPE;

 if($DUTY_TYPE=="1")
    $DUTY_TYPE_NAME=_("�ϰ�Ǽ�");
 else
    $DUTY_TYPE_NAME=_("�°�Ǽ�");

 if($REGISTER_TIME=="")
    $REGISTER_TIME_DESC=_("δ�Ǽ�");
 else
    $REGISTER_TIME_DESC=$REGISTER_TIME;

$MSG = sprintf(_("��%d�εǼ�"), $I);
?>
 <tr class="TableData">
   <td nowrap align="center"><?=$MSG?></td>
   <td nowrap align="center"><?=$DUTY_TYPE_NAME?></td>
   <td nowrap align="center"><?=$DUTY_TIME?></td>
   <td nowrap align="center"><?=$REGISTER_TIME_DESC?></td>
   <td nowrap align="center">
<?
 if($REGISTER_TIME=="") //δ�Ǽ�
 {
    if($DUTY_MACHINE==1)
       echo _("��ʹ�ÿ��ڻ�����");
    else
    {
      $REGISTER_TIME=$CUR_DATE." ".$DUTY_TIME;
      if(strtotime($REGISTER_TIME)-strtotime($CUR_TIME)<=$$DUTY_INTERVAL_BEFORE*60 && strtotime($CUR_TIME)-strtotime($REGISTER_TIME)<=$$DUTY_INTERVAL_AFTER*60)
      {
?>
       <a href="submit.php?REGISTER_TYPE=<?=$I?>"><?=$DUTY_TYPE_NAME?></a>
<?
      }
      else
        echo _("���ڵǼ�ʱ���");
    }//no DUTY_MACHINE
 }
 else //�ѵǼ�
 {
?>
    <?=_("�ѿ���")?> <a href="javascript:remark('<?=$I?>','<?=$REGISTER_TIME_BAK?>');"><?=_("˵�����")?></a>
<?
    //�°�������30�������ٴδ򿨣��������ʧ�󣬵�������
    $A=strtotime($CUR_TIME)-strtotime($REGISTER_TIME_BAK);
    if($DUTY_TYPE==2 && $DUTY_MACHINE!=1 && strtotime($CUR_TIME)-strtotime($REGISTER_TIME_BAK)<=1800)
    {
?>
      <a href="submit.php?REGISTER_TYPE=<?=$I?>"><?=_("���½����°�Ǽ�")?></a>
<?
    }
 }
?>
   </td>
 </tr>

<?
}//for
?>

</table>

</body>
</html>