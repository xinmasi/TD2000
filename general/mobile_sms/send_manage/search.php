<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
if(!$PAGE_SIZE)
   $PAGE_SIZE = 20;
if(!isset($start) || $start=="")
   $start=0;

$HTML_PAGE_TITLE = _("���ŷ��͹���");
include_once("inc/header.inc.php");
?>


<script>
function check_all()
{
 for (i=0;i<document.getElementsByName("email_select").length;i++)
 {
   if(document.getElementsByName("allbox")[0].checked)
      document.getElementsByName("email_select").item(i).checked=true;
   else
      document.getElementsByName("email_select").item(i).checked=false;
 }

 if(i==0)
 {
   if(document.getElementsByName("allbox")[0].checked)
      document.getElementsByName("email_select").checked=true;
   else
      document.getElementsByName("email_select").checked=false;
 }
}

function check_one(el)
{
   if(!el.checked)
      document.getElementsByName("allbox")[0].checked=false;
}
function delete_mail()
{
  delete_str="";
  for(i=0;i<document.getElementsByName("email_select").length;i++)
  {

      el=document.getElementsByName("email_select").item(i);
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(i==0)
  {
      el=document.getElementsByName("email_select");
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(delete_str=="")
  {
     alert("<?=_("Ҫɾ���ֻ����ţ�������ѡ������һ����")?>");
     return;
  }

  msg='<?=_("ȷ��Ҫɾ����ѡ�ֻ�������")?>';
  if(window.confirm(msg))
  {
    url="delete_sms.php?DELETE_STR="+ delete_str;
    location=url;
  }
}
</script>


<body class="bodycolor">

<?
  //----------- �Ϸ���У�� ---------
  if($BEGIN_DATE!="")
  {
    $TIME_OK=is_date_time($BEGIN_DATE);

    if(!$TIME_OK)
    { Message(_("����"),_("��ʼʱ���ʽ���ԣ�Ӧ���� 1999-1-2 14:55:20"));
      Button_Back();
      exit;
    }
  }

  if($END_DATE!="")
  {
    $TIME_OK=is_date_time($END_DATE);

    if(!$TIME_OK)
    { Message(_("����"),_("��ֹʱ���ʽ���ԣ�Ӧ���� 1999-1-2 14:55:20"));
      Button_Back();
      exit;
    }
  }

if($TO_ID!="")
{
  $MY_ARRAY=explode(",",$TO_ID);
  $ARRAY_COUNT=sizeof($MY_ARRAY);
  for($I=0;$I<$ARRAY_COUNT;$I++)
  { if($MY_ARRAY[$I]!="")
  	{
    $query = "SELECT * from USER where USER_ID='$MY_ARRAY[$I]'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
       $MOBIL_NO =$ROW["MOBIL_NO"];
       $USER_NAME =$ROW["USER_NAME"];
       if($MOBIL_NO!="")
          $PHONE.=$MOBIL_NO.",";
       else
          $USER_NAME_EMPTY.=$USER_NAME.",";
    }
   }
  }
}
if($TO_ID1!="")
{
   $TO_ID1=str_replace("\r\n",",",$TO_ID1);
   $PHONE.=$TO_ID1;
} 
if(substr($PHONE,-1)==",")                                                     
   $PHONE=substr($PHONE,0,-1); 
if(!isset($TOTAL_ITEMS))
 {
    $query = "SELECT count(*) from SMS2 where 1=1";
 if($BEGIN_DATE!="")
    $query.= " and SEND_TIME>='$BEGIN_DATE'";
 if($END_DATE!="")
    $query.= " and SEND_TIME<='$END_DATE'";
    
 if($SEND_FLAG!="ALL")
    $query.= " and SEND_FLAG='$SEND_FLAG'";

 $query.= " and FROM_ID='".$_SESSION["LOGIN_USER_ID"]."'";

 if($PHONE!="")
    $query.= " and find_in_set(PHONE,'$PHONE') ";

 if($CONTENT!="")
    $query.= " and CONTENT like '%$CONTENT%'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
 }

 $query = "SELECT * from SMS2 where 1=1";
 if($BEGIN_DATE!="")
    $query.= " and SEND_TIME>='$BEGIN_DATE'";
 if($END_DATE!="")
    $query.= " and SEND_TIME<='$END_DATE'";
    
 if($SEND_FLAG!="ALL")
    $query.= " and SEND_FLAG='$SEND_FLAG'";

 $query.= " and FROM_ID='".$_SESSION["LOGIN_USER_ID"]."'";

 if($PHONE!="")   
    $query.= " and find_in_set(PHONE,'$PHONE')";

 if($CONTENT!="")
    $query.= " and CONTENT like '%$CONTENT%'";

 if($DELETE_FLAG==1)
 {
    $query=str_replace("SELECT * from","delete from",$query)." and SEND_FLAG!='1'";
    exequery(TD::conn(),$query);
    Message(_("��ʾ"),_("ָ����Χ�ڵ��ֻ����ż�¼��ɾ����"));
    Button_Back();
    exit;
 }

?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/mobile_sms.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("���ŷ��Ͳ�ѯ���")?> </span>
    	 	<input type="button" class="BigButton" value="<?=_("ˢ��")?>" onClick="location.reload();">
    	<?
    	if($TOTAL_ITEMS>0){
    	?>
     <td align="right" valign="bottom" class="small1"><?=page_bar($start,$TOTAL_ITEMS,$PAGE_SIZE)?></td>
     <?
   }
     ?>
    </td>
  </tr>
</table>

<?

 $query1= "select * from USER where MOBIL_NO<>''";
 $cursor= exequery(TD::conn(),$query1);
 while($ROW=mysql_fetch_array($cursor))
 {
 	$USER_NAME=$ROW["USER_NAME"];
 	$MOBIL_NO=$ROW["MOBIL_NO"];
 	$MOBIL_NO_HIDDEN=$ROW["MOBIL_NO_HIDDEN"];
 	$PHONE_ARRAY[$MOBIL_NO][0]=$USER_NAME;
 	$PHONE_ARRAY[$MOBIL_NO][1]=$MOBIL_NO_HIDDEN;
 }
 
 $query1= "select PSN_NAME,DEPT_NAME,MOBIL_NO from ADDRESS where MOBIL_NO<>''";
 $cursor= exequery(TD::conn(),$query1);
 while($ROW=mysql_fetch_array($cursor))
 {
    $PSN_NAME=$ROW["PSN_NAME"];
    $DEPT_NAME=$ROW["DEPT_NAME"];
    $MOBIL_NO=$ROW["MOBIL_NO"];   
    $ADDRESS_ARRAY[$MOBIL_NO][0]=$PSN_NAME;
    $ADDRESS_ARRAY[$MOBIL_NO][1]=$DEPT_NAME;
 }

 $query.= " order by SEND_TIME desc,SMS_ID desc";
  $query .= " limit $start,$PAGE_SIZE";
  

 $cursor= exequery(TD::conn(),$query);
 $SMS_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $SMS_COUNT++;
   
    $SMS_ID=$ROW["SMS_ID"];
    $PHONE=$ROW["PHONE"];
    $CONTENT=$ROW["CONTENT"];
    $SEND_TIME=$ROW["SEND_TIME"];
    $SEND_FLAG=$ROW["SEND_FLAG"];

    switch($SEND_FLAG)
    {
      case "0":
          $SEND_FLAG_DESC=_("������");
          break;
      case "1":
          $SEND_FLAG_DESC=_("���ͳɹ�");
          break;
      case "2":
          $SEND_FLAG_DESC=_("���ͳ�ʱ��<a href=../new/submit.php?TO_ID1=$PHONE&CONTENT=$CONTENT>�ط�</a>");
          break;
      case "3":
          $SEND_FLAG_DESC=_("������...");
          break;
    }

    if($SMS_COUNT==1)
    {
?>
     <table class="TableList" width="95%" align="center">
      <tr class="TableHeader">
       <td nowrap align="center"><?=_("ѡ��")?></td>
       <td nowrap align="center"><?=_("������")?></td>
       <td nowrap align="center"><?=_("�ֻ�����")?></td>
       <td nowrap align="center"><?=_("����")?></td>
       <td nowrap align="center"><?=_("����ʱ��")?> <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_down.gif" width="11" height="10"></td>
       <td nowrap align="center"><?=_("״̬")?></td>
      </tr>
<?
    }

    if($SMS_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
       
    if($PHONE_ARRAY[$PHONE][0]=="")
    {
       if($ADDRESS_ARRAY[$PHONE][0]=="")
          $SENDER_NAME=_("δ֪");
       else
          $SENDER_NAME=$ADDRESS_ARRAY[$PHONE][0];
    }
    else
    {
       $SENDER_NAME=$PHONE_ARRAY[$PHONE][0];
    }
?>
    <tr class="<?=$TableLine?>">
      <td>&nbsp;<?if($SEND_FLAG!="1"){?><input type="checkbox" name="email_select" value="<?=$SMS_ID?>" onClick="check_one(self);"><?}?></td>
      <td nowrap align="center" title="<?=$ADDRESS_ARRAY[$PHONE][1]?>"><?if($SENDER_NAME){echo $SENDER_NAME;}else{if($PHONE_ARRAY[$PHONE][1]==0)echo $PHONE;else echo _("������");}?></td>
      <td nowrap align="center"><?if($PHONE_ARRAY[$PHONE][1]==0)echo $PHONE;else echo _("������");?></td>
      <td><?=td_htmlspecialchars($CONTENT)?></td>
      <td nowrap align="center"><?=$SEND_TIME?></td>
      <td nowrap align="center"><?=$SEND_FLAG_DESC?></td>
    </tr>
<?
 }//while

 if($SMS_COUNT==0)
   Message("",_("�޷����������ֻ����ż�¼"));
 else
 {
?>
<script>
if(document.all("email_select"))
{
  document.write("<tr class='TableControl'><td colspan='6'>&nbsp;<input type='checkbox' name='allbox' id='allbox_for' onClick='check_all();'><label for='allbox_for'><?=_("ȫѡ")?></label> &nbsp;<input type='button' value='<?=_("ɾ��")?>' class='SmallButton' onClick='delete_mail();' title='<?=_("ɾ����ѡ�ֻ�����")?>'></td></tr>");
  document.close();
}
</script>
</table>
<?
  
 }
?>
<br><center><input type="button" class="BigButton" value="<?=_("����")?>" onClick="location='index.php';"></center>
</body>
</html>
