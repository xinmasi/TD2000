<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("���°�Ǽǲ�ѯ");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script language="JavaScript">
function remark(USER_ID,REGISTER_TYPE,REGISTER_TIME)
{
  URL="remark.php?USER_ID="+USER_ID+"&REGISTER_TYPE="+REGISTER_TYPE+"&REGISTER_TIME="+REGISTER_TIME;
  myleft=(screen.availWidth-650)/2;
  window.open(URL,"formul_edit","height=250,width=450,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}

</script>


<body class="attendance">

<?
 $query1="SELECT * from USER_EXT,USER where USER.UID=USER_EXT.UID and USER.USER_ID='$USER_ID'";
 $cursor1= exequery(TD::conn(),$query1);
 if($ROW=mysql_fetch_array($cursor1))
 {
    $DUTY_TYPE=$ROW["DUTY_TYPE"];
    $USER_NAME=$ROW["USER_NAME"];
    $DEPT_ID=$ROW["DEPT_ID"];
 }

 if(!is_dept_priv($DEPT_ID) && $_SESSION["LOGIN_USER_PRIV"]!=1)
 {
  	 Message(_("����"),_("�����ڹ���Χ�ڵ��û�").$DEPT_ID);
    exit;
 }

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
   $cursor= exequery(TD::conn(),$query, $connstatus);
   if($ROW=mysql_fetch_array($cursor))
      $HOLIDAY="<font color='#008000'>"._("����")."</font>";
}
?>

<!----  ���°�Ǽ� ---->
<h5 class="attendance-title"><?=_("���°�Ǽǲ�ѯ")?> (<?=$DUTY_NAME?>)  <?=$SOME_DATE?></h5>

<br>

<table class="table table-bordered" align="center">
    <tr class="">
      <th nowrap align="center"><?=_("�ǼǴ���")?></th>
      <th nowrap align="center"><?=_("�Ǽ�����")?></th>
      <th nowrap align="center"><?=_("�涨ʱ��")?></th>
      <th nowrap align="center"><?=_("�Ǽ�ʱ��")?></th>
      <th nowrap align="center"><?=_("�Ǽ�")?>IP</th>
    </tr>
<?
for($I=1;$I<=6;$I++)
 {
    $DUTY_TIME_I="DUTY_TIME".$I;
    $DUTY_TIME_I=$$DUTY_TIME_I;
    if($I%2==0)
    {
        $DUTY_TYPE_I = 2;
    }else
    {
        $DUTY_TYPE_I = 1;
    }

    if($DUTY_TIME_I=="" || $DUTY_TIME_I=="00:00:00")
       continue;

    $HOLIDAY1="";
    if($HOLIDAY=="")
    {
       $query="select LEAVE_TYPE2 from ATTEND_LEAVE where USER_ID='$USER_ID' and (ALLOW='1' or ALLOW='3') and LEAVE_DATE1<='$SOME_DATE $DUTY_TIME_I' and LEAVE_DATE2>='$SOME_DATE $DUTY_TIME_I'";
       $cursor= exequery(TD::conn(),$query, $connstatus);
       if($ROW=mysql_fetch_array($cursor)){
			$LEAVE_TYPE2 = $ROW ["LEAVE_TYPE2"];
			$LEAVE_TYPE2 = get_hrms_code_name ( $LEAVE_TYPE2, "ATTEND_LEAVE" );
            $HOLIDAY1="<font color='#008000'>"._("���").  "-$LEAVE_TYPE2</font>";
       }
    }
    else
       $HOLIDAY1=$HOLIDAY;

    if($HOLIDAY==""&&$HOLIDAY1=="")
    {
       $query="select * from ATTEND_OUT where USER_ID='$USER_ID' and ALLOW='1' and to_days(SUBMIT_TIME)=to_days('$SOME_DATE') and OUT_TIME1<='".substr($DUTY_TIME_I,0,strrpos($DUTY_TIME_I,":"))."' and OUT_TIME2>='".substr($DUTY_TIME_I,0,strrpos($DUTY_TIME_I,":"))."'";
       $cursor= exequery(TD::conn(),$query, $connstatus);
       if($ROW=mysql_fetch_array($cursor))
          $HOLIDAY1="<font color='#008000'>"._("���")."</font>";
    }

    $REGISTER_TIME="";
    $REGISTER_IP="";
    $REMARK="";
    $query = "SELECT * from ATTEND_DUTY where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$SOME_DATE') and REGISTER_TYPE='$I'";
    $cursor= exequery(TD::conn(),$query, $connstatus);
    if($ROW=mysql_fetch_array($cursor))
    {
       $REGISTER_TIME2=$ROW["REGISTER_TIME"];
       $REGISTER_TIME=$ROW["REGISTER_TIME"];
       $REGISTER_IP=$ROW["REGISTER_IP"];
       $REMARK=$ROW["REMARK"];
       $REGISTER_TIME=strtok($REGISTER_TIME," ");
       $REGISTER_TIME=strtok(" ");

    if ( $DUTY_TYPE_I == "1" && compare_time ( $REGISTER_TIME, $DUTY_TIME_I ) == 1) {
			if($HOLIDAY1!=''){
				$REGISTER_TIME .= " <span class=big4>" . _ ( "�ٵ�" ) . "</span>(".$HOLIDAY1.')';
			}else{
				$REGISTER_TIME .= " <span class=big4>" . _ ( "�ٵ�" ) . "</span>";
			}
		}
		
		if ($DUTY_TYPE_I == "2" && compare_time ( $REGISTER_TIME, $DUTY_TIME_I ) == - 1) {
			if($HOLIDAY1!=''){
				$REGISTER_TIME .= " <span class=big4>" . _ ( "����" ) . "</span>(".$HOLIDAY1.')';
			}else{
				$REGISTER_TIME .= " <span class=big4>" . _ ( "����" ) . "</span>";
			}
			
		}

       if($REMARK!="")
          $REMARK="<br>"._("��ע��").$REMARK;
    }

    if($DUTY_TYPE_I=="1")
       $DUTY_TYPE_DESC=_("�ϰ�Ǽ�");
    else
       $DUTY_TYPE_DESC=_("�°�Ǽ�");

$MSG = sprintf(_("��%d�εǼ�"), $I);
?>
    <tr class="">
      <td nowrap align="center"><?=$MSG?></td>
      <td nowrap align="center"><?=$DUTY_TYPE_DESC?></td>
      <td nowrap align="center"><?=$DUTY_TIME_I?></td>
      <td nowrap align="center">
<?
      if($REGISTER_TIME=="")
      {
         if($HOLIDAY1=="")
            echo _("δ�Ǽ�");
         else
            echo $HOLIDAY1;

      }
      else
         echo $REGISTER_TIME.$REMARK;

      if($REMARK!="")
         echo "<a href=\"javascript:remark('$USER_ID','$I','$REGISTER_TIME2');\" title=\""._("�޸ı�ע")."\">"._("�޸�")."</a>";

?>
      </td>
      <td nowrap align="center"><?=$REGISTER_IP?></td>
    </tr>
<?
 }
?>
</table>

<br>
<div align="center">
  <input type="button"  value="<?=_("�޸ĵǼ�ʱ��")?>" class="btn btn-primary" onClick="location='duty_edit.php?DUTY_TYPE=<?=$DUTY_TYPE?>&USER_ID=<?=$USER_ID?>&SOME_DATE=<?=$SOME_DATE?>'">&nbsp;
  <input type="button"  value="<?=_(" ��    �� ")?>" class="btn " onClick="location='user.php?USER_ID=<?=$USER_ID?>'">
</div>

</body>
</html>