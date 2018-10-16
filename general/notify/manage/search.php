<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_cache.php");
if(!isset($TYPE))
   $TYPE="0";
if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("NOTIFY", 10);
if(!isset($start) || $start=="")
   $start=0;

$HTML_PAGE_TITLE = _("����֪ͨ��ѯ");
include_once("inc/header.inc.php");
?>

<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script>
function open_notify(NOTIFY_ID)
{
 URL="../show/read_notify.php?IS_MANAGE=1&NOTIFY_ID="+NOTIFY_ID;
 myleft=(screen.availWidth-780)/2;
 window.open(URL,"read_notify","height=500,width=780,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=100,left="+myleft+",resizable=yes");
}

function open_urladdress(NOTIFY_ID)
{
 URL="../show/url_address.php?NOTIFY_ID="+NOTIFY_ID;
 myleft=(screen.availWidth-780)/2;
 window.open(URL,"read_notify","height=500,width=780,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=100,left="+myleft+",resizable=yes");
}

function show_reader(NOTIFY_ID)
{
 URL="show_reader.php?NOTIFY_ID="+NOTIFY_ID;
 myleft=(screen.availWidth-500)/2;
 window.open(URL,"read_notify","height=350,width=500,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}

function modify_notify(NOTIFY_ID,start)
{
  URL="modify.php?FROM=1&NOTIFY_ID="+NOTIFY_ID+"&start="+start;
  myleft=(screen.availWidth-500)/2;
  window.open(URL,"modify_notify","height=500,width=700,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}

function delete_notify(NOTIFY_ID)
{
 msg='<?=_("ȷ��Ҫɾ�������֪ͨ��")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?DELETE_STR=" + NOTIFY_ID+"&SEARCH=1&SEND_TIME_MIN=<?=$SEND_TIME_MIN?>&SEND_TIME_MAX=<?=$SEND_TIME_MAX?>&SUBJECT=<?=$SUBJECT?>&CONTENT=<?=$CONTENT?>&FORMAT=<?=$FORMAT?>&TYPE_ID=<?=$TYPE_ID?>&PUBLISH=<?=$PUBLISH?>&TOP=<?=$TOP?>&TO_ID=<?=$TO_ID?>&STAT=<?=$STAT?>";
  window.location=URL;
 }
}
jQuery(function(){
    jQuery("#allbox_for").click(function(){
        if(jQuery("#allbox_for").is(":checked"))
        {
            jQuery("[name='email_select']").attr("checked",'true');
        }
        else
        {
            jQuery("[name='email_select']").removeAttr("checked");
        }
    })
    
    jQuery("[name='email_select']").click(function(){
        jQuery("#allbox_for").removeAttr("checked");
    })
});

function get_checked()
{
    checked_str="";
    
    jQuery("input[name='email_select']:checkbox").each(function(){ 
        if(jQuery(this).attr("checked")){
            checked_str += jQuery(this).val()+","
        }
    })
    
    return checked_str;
}
function cancel_top()
{
  delete_str=get_checked();

  if(delete_str=="")
  {
     alert("<?=_("Ҫȡ������֪ͨ�ö���������ѡ������һ����")?>");
     return;
  }

  msg='<?=_("ȷ��Ҫȡ����ѡ����֪ͨ���ö���")?>';
  if(window.confirm(msg))
  {
    url="notop.php?FROM_SELECT=1&SEARCH_FLAG=1&DELETE_STR="+ delete_str +"&start=<?=$start?>&SEARCH=1&SEND_TIME_MIN=<?=$SEND_TIME_MIN?>&SEND_TIME_MAX=<?=$SEND_TIME_MAX?>&SUBJECT=<?=$SUBJECT?>&CONTENT=<?=$CONTENT?>&FORMAT=<?=$FORMAT?>&TYPE_ID=<?=$TYPE_ID?>&PUBLISH=<?=$PUBLISH?>&TOP=<?=$TOP?>&TO_ID=<?=$TO_ID?>&STAT=<?=$STAT?>";
    location=url;
  }
}


function notify_end()
{
  delete_str=get_checked();

  if(delete_str=="")
  {
     alert("<?=_("Ҫ��ֹ����֪ͨ��������ѡ������һ����")?>");
     return;
  }

  msg='<?=_("ȷ��Ҫ��ֹ��ѡ����֪ͨ��")?>';
  if(window.confirm(msg))
  {
    url="search_end.php?DELETE_STR="+ delete_str +"&start=<?=$start?>&SEARCH=1&SEND_TIME_MIN=<?=$SEND_TIME_MIN?>&SEND_TIME_MAX=<?=$SEND_TIME_MAX?>&SUBJECT=<?=$SUBJECT?>&CONTENT=<?=$CONTENT?>&FORMAT=<?=$FORMAT?>&TYPE_ID=<?=$TYPE_ID?>&PUBLISH=<?=$PUBLISH?>&TOP=<?=$TOP?>&TO_ID=<?=$TO_ID?>&STAT=<?=$STAT?>";
    alert(url);
    location=url;
  }
}

function delete_mail()
{
  delete_str=get_checked();

  if(delete_str=="")
  {
     alert("<?=_("Ҫɾ������֪ͨ��������ѡ������һ����")?>");
     return;
  }

  msg='<?=_("ȷ��Ҫɾ����ѡ����֪ͨ��")?>';
  if(window.confirm(msg))
  {
    url="delete.php?DELETE_STR="+ delete_str +"&start=<?=$start?>&SEARCH=1&SEND_TIME_MIN=<?=$SEND_TIME_MIN?>&SEND_TIME_MAX=<?=$SEND_TIME_MAX?>&SUBJECT=<?=$SUBJECT?>&CONTENT=<?=$CONTENT?>&FORMAT=<?=$FORMAT?>&TYPE_ID=<?=$TYPE_ID?>&PUBLISH=<?=$PUBLISH?>&TOP=<?=$TOP?>&TO_ID=<?=$TO_ID?>&STAT=<?=$STAT?>";
    location=url;
  }
}

function my_affair(NOTIFY_ID )
{
  myleft=(screen.availWidth-250)/2;
  mytop=(screen.availHeight-200)/2;
  URL="note.php?NOTIFY_ID="+NOTIFY_ID;
  window.open(URL,"","height=200,width=250,status=0,toolbar=no,menubar=no,location=no,scrollbars=auto,resizable=no,top="+mytop+",left="+myleft);
}
</script>

<body class="bodycolor">

<?
$PARA_ARRAY=get_sys_para("NOTIFY_EDIT_PRIV,NOTIFY_TOP_DAYS");
$NOTIFY_EDIT_PRIV=$PARA_ARRAY["NOTIFY_EDIT_PRIV"];
$TOP_MAX_DAYS=$PARA_ARRAY["NOTIFY_TOP_DAYS"]; //����ö�����
$CUR_DATE=date("Y-m-d",time());
$CUR_DATE_U=time();
$ROW=GetUserInfoByUID($_SESSION["LOGIN_UID"],"POST_PRIV,POST_DEPT,DEPT_ID");
$POST_PRIV=$ROW["POST_PRIV"];
$DEPT_ID1=td_trim($ROW["POST_DEPT"]);
$DEPT_ID2=$ROW["DEPT_ID"];
$WHERE_STR="SEARCH=1";
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";
//-------�Ϸ���У�� ---------
if($SEND_TIME_MIN!="")
{ 
   $WHERE_STR.="&SEND_TIME_MIN=$SEND_TIME_MIN";
   $SEND_TIME_MIN1=$SEND_TIME_MIN." 00:00:00";
  
}
if($SEND_TIME_MAX!="")
{
   $WHERE_STR.="&SEND_TIME_MAX=$SEND_TIME_MAX";
   $SEND_TIME_MAX1=$SEND_TIME_MAX." 23:59:59";
}
//------------------------ ���������ַ��� ------------------
$CONDITION_STR="";
if($SUBJECT!="")
{
   $CONDITION_STR.=" and SUBJECT like '%".$SUBJECT."%'";
   $WHERE_STR.="&SUBJECT=$SUBJECT";
}
if($CONTENT!="")
{
   $CONDITION_STR.=" and CONTENT like '%".$CONTENT."%'";
   $WHERE_STR.="&CONTENT=$CONTENT";
}
if($SEND_TIME_MIN!="")
   $CONDITION_STR.=" and SEND_TIME>='$SEND_TIME_MIN1'";
if($SEND_TIME_MAX!="")
   $CONDITION_STR.=" and SEND_TIME<='$SEND_TIME_MAX1'";
if($FORMAT!="")
{
   $CONDITION_STR.=" and FORMAT='$FORMAT'";
   $WHERE_STR.="&FORMAT=$FORMAT";
}
if($TYPE_ID!="")
{
   $CONDITION_STR.=" and TYPE_ID='$TYPE_ID'";
   $WHERE_STR.="&TYPE_ID=$TYPE_ID";
}
if($PUBLISH!="")
{
   $WHERE_STR.="&PUBLISH=$PUBLISH";
   if($PUBLISH==1)
      $CONDITION_STR.=" and PUBLISH='1'";
   else
      $CONDITION_STR.=" and PUBLISH<>'1'";	
} 
if($TOP!="")
{
   $CONDITION_STR.=" and TOP='$TOP'";
   $WHERE_STR.="&TOP=$TOP";
}
if($TO_ID!="")
{
   $CONDITION_STR.=" and find_in_set(FROM_ID,'$TO_ID')";
   $WHERE_STR.="&TO_ID=$TO_ID";
}
if($STAT!="")
{
   $WHERE_STR.="&STAT=$STAT";
   if($STAT==1)
      $CONDITION_STR.=" and BEGIN_DATE>'$CUR_DATE_U'";
   else if($STAT==2)
      $CONDITION_STR.=" and BEGIN_DATE<='$CUR_DATE_U' and (END_DATE='0' or END_DATE>='$CUR_DATE_U') and PUBLISH='1'";
   else if($STAT==3)
      $CONDITION_STR.=" and END_DATE!='0' and END_DATE<='$CUR_DATE_U'";  
} 
if($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION['LOGIN_USER_PRIV_TYPE']!='1')
{
    $dept_id = get_manage_dept_ids($_SESSION['LOGIN_UID']);
    if($dept_id)
    {
        $dept_str = $dept_id;
    }
    else
    {
        $dept_str = $_SESSION["LOGIN_DEPT_ID"];
    }
    $UID = rtrim(GetUidByOther('','',$dept_str),",");
    $user_id = rtrim(GetUserIDByUid($UID),",");
    if($user_id!='')
    {
        $query0 = "SELECT COUNT(NOTIFY_ID) from NOTIFY where find_in_set(FROM_ID,'".$user_id."') or find_in_set(FROM_DEPT,'".$dept_str."')";
    }
    else
    {
        $query0 = "SELECT COUNT(NOTIFY_ID) from NOTIFY where FROM_ID ='".$_SESSION["LOGIN_USER_ID"]."'";
    }
}
else
{
    if($_SESSION["LOGIN_USER_PRIV"]=="1"||$POST_PRIV=="1")
        $query0 = "SELECT count(NOTIFY_ID) from NOTIFY where 1=1";
    else if ($POST_PRIV=="0" || $POST_PRIV=='6')
        $query0="SELECT COUNT(NOTIFY_ID) FROM NOTIFY LEFT JOIN USER ON NOTIFY.FROM_ID=USER.USER_ID WHERE USER.DEPT_ID='$DEPT_ID2'";
    else if ($POST_PRIV=="2")
        $query0="SELECT COUNT(NOTIFY_ID) FROM NOTIFY  WHERE  find_in_set(FROM_DEPT,'$DEPT_ID1') or FROM_ID='".$_SESSION["LOGIN_USER_ID"]."'";
}


$query0.=$CONDITION_STR;
$cursor=exequery(TD::conn(),$query0,$QUERY_MASTER);
if($ROW=mysql_fetch_array($cursor))
   $TOTAL_ITEMS=$ROW[0];
?>

<table border="0" width="95%" cellspacing="0" cellpadding="3" class="small" >
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("����֪ͨ��ѯ���")?></span><br>
    </td>
<?
if($TOTAL_ITEMS>0){
 ?>
   <td align=right><?=page_bar($start,$TOTAL_ITEMS,$PAGE_SIZE)?></td>
 <?
}
 ?>
  </tr>
</table>

<?
$LIST_CLAUSE = " NOTIFY_ID,FROM_ID,TO_ID,SUBJECT,FORMAT,TOP,TOP_DAYS,PRIV_ID,USER_ID,TYPE_ID,PUBLISH,AUDITER,SEND_TIME,BEGIN_DATE,END_DATE,SUBJECT_COLOR ";
$LIST_CLAUSE2= " a.NOTIFY_ID,a.FROM_ID,a.TO_ID,SUBJECT,a.FORMAT,TOP,a.TOP_DAYS,a.PRIV_ID,a.USER_ID,a.TYPE_ID,a.PUBLISH,a.AUDITER,a.SEND_TIME,a.BEGIN_DATE,a.END_DATE,a.SUBJECT_COLOR ";
$POSTFIX = _("��");
if($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION['LOGIN_USER_PRIV_TYPE']!='1')
{
    $dept_id = get_manage_dept_ids($_SESSION['LOGIN_UID']);
    if($dept_id)
    {
        $dept_str = $dept_id;
    }
    else
    {
        $dept_str = $_SESSION["LOGIN_DEPT_ID"];
    }
    $UID = rtrim(GetUidByOther('','',$dept_str),",");
    $user_id = rtrim(GetUserIDByUid($UID),",");
    if($user_id!='')
    {
        $query = "SELECT ".$LIST_CLAUSE." from NOTIFY where find_in_set(FROM_ID,'".$user_id."')";
    }
    else
    {
        $query = "SELECT ".$LIST_CLAUSE." from NOTIFY where FROM_ID ='".$_SESSION["LOGIN_USER_ID"]."'";
    }
}
else
{
    if($_SESSION["LOGIN_USER_PRIV"]=="1"||$POST_PRIV=="1")
        $query = "SELECT ".$LIST_CLAUSE." from NOTIFY where 1=1";
    else if ($POST_PRIV=="0" || $POST_PRIV=="6")
        $query = "SELECT ".$LIST_CLAUSE2." from NOTIFY a left join USER b on a.FROM_ID=b.USER_ID where b.DEPT_ID='$DEPT_ID2'";
    else if ($POST_PRIV=="2")
        $query = "SELECT ".$LIST_CLAUSE2." from NOTIFY a where (find_in_set(FROM_DEPT,'$DEPT_ID1') or a.FROM_ID='$LOGIN_USER_ID')";
}
$query.=$CONDITION_STR." order by TOP desc,SEND_TIME desc";
$query .= " limit $start,$PAGE_SIZE";
$cursor=exequery(TD::conn(),$query,$QUERY_MASTER);
$NOTIFY_COUNT=0;
$ROW_ARRAY=array();
$USER_ID_STR="";
$FROM_ID_STR="";
$TYPE_ID_STR="";
while($ROW1=mysql_fetch_array($cursor))
{
   $USER_ID1=$ROW1["USER_ID"];
   $NOTIFY_ID1=$ROW1["NOTIFY_ID"];
   $FROM_ID1=$ROW1["FROM_ID"];
   $TO_ID1=$ROW1["TO_ID"];
   $SUBJECT1=$ROW1["SUBJECT"];
   $TOP1=$ROW1["TOP"];
   $TOP_DAYS1=$ROW1["TOP_DAYS"];
   $PRIV_ID1=$ROW1["PRIV_ID"];
   $TYPE_ID1=$ROW1["TYPE_ID"];
   $PUBLISH1=$ROW1["PUBLISH"];
   $FORMAT1=$ROW1["FORMAT"];
   $SUBJECT_COLOR1=$ROW1["SUBJECT_COLOR"];
   $AUDITER1=$ROW1["AUDITER"]; 
   $SEND_TIME1=$ROW1["SEND_TIME"];
   $BEGIN_DATE1=$ROW1["BEGIN_DATE"];
   $END_DATE1=$ROW1["END_DATE"];
   $USER_ID_STR.=$USER_ID1.",";
   $FROM_ID_STR.=$FROM_ID1.",";
   if($TYPE_ID1!="")
   	$TYPE_ID_STR.="'".$TYPE_ID1."',";
   $ROW_ARRAY[]= array("FROM_ID"=> $FROM_ID1,"USER_ID"=> $USER_ID1,"TO_ID"=> $TO_ID1,"NOTIFY_ID"=> $NOTIFY_ID1,"SUBJECT"=> $SUBJECT1,"TOP"=> $TOP1,"TOP_DAYS"=> $TOP_DAYS1,"PRIV_ID"=> $PRIV_ID1,"FORMAT"=> $FORMAT1,"SUBJECT_COLOR"=> $SUBJECT_COLOR1,"TYPE_ID"=> $TYPE_ID1,"PUBLISH" => $PUBLISH1,"AUDITER"=> $AUDITER1,"SEND_TIME"=> $SEND_TIME1,"BEGIN_DATE"=> $BEGIN_DATE1,"END_DATE"=> $END_DATE1 );
   
}
$USER_ID_STR=$USER_ID_STR.",".$FROM_ID_STR;   
 if(td_trim($USER_ID_STR)!="")
{
   $USER_UID=UserId2Uid($USER_ID_STR);
   if($USER_UID!="")
   {
      $USER_NAME_ARRAY=GetUserInfoByUID($USER_UID,"USER_NAME,DEPT_ID");
   }
    
}
//�������Ͳ�ѯ
$TYPE_ID_STR=td_trim($TYPE_ID_STR);
$TYPE_NAME_ARRAY=array();
$TYPE_NAME		=	"";
if($TYPE_ID_STR!="")
{
	$query1=	"select CODE_NAME,CODE_EXT,CODE_NO from SYS_CODE where PARENT_NO='NOTIFY' and CODE_NO in ($TYPE_ID_STR)";
	$cursor1= exequery(TD::conn(),$query1);
   while($ROW_CODE=mysql_fetch_array($cursor1))
	{
		$TYPE_ID_KEY=$ROW_CODE["CODE_NO"];
		$TYPE_NAME=$ROW_CODE["CODE_NAME"];
		$CODE_EXT=unserialize($ROW["CODE_EXT"]);
		if(is_array($CODE_EXT) && $CODE_EXT[MYOA_LANG_COOKIE] != "")
		$TYPE_NAME = $CODE_EXT[MYOA_LANG_COOKIE];
		$TYPE_NAME_ARRAY[$TYPE_ID_KEY]= $TYPE_NAME;
   }
}
foreach ($ROW_ARRAY as $key=> $val){
   $ROW=$val;
   $NOTIFY_COUNT++;
   $NOTIFY_ID=$ROW["NOTIFY_ID"];
   $FROM_ID=$ROW["FROM_ID"];
   $TO_ID_SHOW=$ROW["TO_ID"];
   $SUBJECT_SHOW=$ROW["SUBJECT"];
   $TOP_SHOW=$ROW["TOP"];
   $TOP_DAYS_SHOW=$ROW["TOP_DAYS"];
   $PRIV_ID=$ROW["PRIV_ID"];
   $USER_ID=$ROW["USER_ID"];
   $TYPE_ID_SHOW=$ROW["TYPE_ID"];
   $FORMAT=$ROW["FORMAT"];
   $PUBLISH_SHOW=$ROW["PUBLISH"];
   $SUBJECT_COLOR=$ROW["SUBJECT_COLOR"];
   $AUDITER=$ROW["AUDITER"];
   $SUBJECT_TITLE="";
   if(strlen($SUBJECT_SHOW) > 50)
   {
      $SUBJECT_TITLE=$SUBJECT_SHOW;
      $$SUBJECT_SHOW=csubstr($SUBJECT_SHOW, 0, 50)."...";
   }
   $SUBJECT_SHOW=td_htmlspecialchars($SUBJECT_SHOW);
   $SUBJECT_TITLE=td_htmlspecialchars($SUBJECT_TITLE);

   if($PUBLISH_SHOW=="0")
      $PUBLISH_DESC="<font color=red>"._("δ����")."</font>";
   else
      $PUBLISH_DESC="";

   $SEND_TIME=$ROW["SEND_TIME"];
   $BEGIN_DATE=$ROW["BEGIN_DATE"];
   $END_DATE=$ROW["END_DATE"];
   
   $BEGIN_DATE=date("Y-m-d",$BEGIN_DATE);
   if ($END_DATE=="0")
      $END_DATE="";
   else 
      $END_DATE=date("Y-m-d",$END_DATE);

   /*$BEGIN_DATE=strtok($BEGIN_DATE," ");
   $END_DATE=strtok($END_DATE," ");

   if($END_DATE=="0000-00-00")
      $END_DATE="";*/

   $FROM_UID=UserId2Uid($FROM_ID);
   if($FROM_UID!="")
   {
      $FROM_NAME=$USER_NAME_ARRAY[$FROM_UID]["USER_NAME"];
      $DEPT_ID=$USER_NAME_ARRAY[$FROM_UID]["DEPT_ID"];
   }
   else //����û�����ȡ���� ���ݿ��в�
   {
      $query1="select USER_NAME,DEPT_ID from USER where USER_ID='$FROM_ID'";
      $cursor1= exequery(TD::conn(),$query1);
      if($ROW=mysql_fetch_array($cursor1))
      {
         $FROM_NAME=$ROW["USER_NAME"];
         $DEPT_ID=$ROW["DEPT_ID"];
      }
      else
      {
         $FROM_NAME=$FROM_ID;   
      }
         
   }
   $DEPT_NAME=dept_long_name($DEPT_ID);
   $TYPE_NAME=$TYPE_NAME_ARRAY[$TYPE_ID_SHOW];

   if($TO_ID_SHOW=="ALL_DEPT")
      $TO_NAME=_("ȫ�岿��");
   else
      $TO_NAME=GetDeptNameById($TO_ID_SHOW);  
   $PRIV_NAME=GetPrivNameById($PRIV_ID);    
   $USER_NAME="";
   if($USER_ID!="")
   {   
      $USER_UID=UserId2Uid($USER_ID);
      $UID_ARRAY=explode(',',$USER_UID);
      $USER_ID_ARRAY=explode(',',$USER_ID);
      for($I=0;$I<count($USER_ID_ARRAY);$I++)
      {
         if($USER_ID_ARRAY[$I]=="")
         continue;
         if($UID_ARRAY[$I]!="" )
         {
            $USER_NAME1=$USER_NAME_ARRAY[$UID_ARRAY[$I]]["USER_NAME"];
         }
         else  //���������ȡ����uid��ֵ�Ǹ���user_id�����ݿ��л�ȡ
         {
            $query1="select USER_NAME from USER where USER_ID='$USER_ID_ARRAY[$I]'";
            $cursor1= exequery(TD::conn(),$query1);
            if($ROW=mysql_fetch_array($cursor1))
               $USER_NAME1=$ROW["USER_NAME"];
            else
               $USER_NAME1=$USER_ID_ARRAY[$I];   
         }
         $USER_NAME.=$USER_NAME1.",";
      }
   }
   $TO_NAME_TITLE="";
   $TO_NAME_STR="";  
   if($TO_NAME!="")
   {
      if(substr($TO_NAME,-strlen($POSTFIX))==$POSTFIX)
         $TO_NAME=substr($TO_NAME,0,-strlen($POSTFIX));
      $TO_NAME_TITLE.=_("���ţ�").$TO_NAME;
      $TO_NAME_STR.="<font color=#0000FF><b>"._("���ţ�")."</b></font>".csubstr(strip_tags($TO_NAME),0,20).(strlen($TO_NAME)>20?"...":"")."<br>";
   }

   if($PRIV_NAME!="")
   {
      if(substr($PRIV_NAME,-strlen($POSTFIX))==$POSTFIX)
         $PRIV_NAME=substr($PRIV_NAME,0,-strlen($POSTFIX));
      if($TO_NAME_TITLE!="")
         $TO_NAME_TITLE.="\n\n";
      $TO_NAME_TITLE.=_("��ɫ��").$PRIV_NAME;
      $TO_NAME_STR.="<font color=#0000FF><b>"._("��ɫ��")."</b></font>".csubstr(strip_tags($PRIV_NAME),0,20).(strlen($PRIV_NAME)>20?"...":"")."<br>";
   }

   if($USER_NAME!="")
   {
      if(substr($USER_NAME,-1)==",")
         $USER_NAME=substr($USER_NAME,0,-1);
      if($TO_NAME_TITLE!="")
         $TO_NAME_TITLE.="\n\n";
      $TO_NAME_TITLE.=_("��Ա��").$USER_NAME;
      $TO_NAME_STR.="<font color=#0000FF><b>"._("��Ա��")."</b></font>".csubstr(strip_tags($USER_NAME),0,20).(strlen($USER_NAME)>20?"...":"")."<br>";
   }

   if(compare_date($CUR_DATE,$BEGIN_DATE)<0)
   {
      $NOTIFY_STATUS=1;
      $NOTIFY_STATUS_STR=_("����Ч");
   }
   else
   {
      $NOTIFY_STATUS=2;
      if ($PUBLISH_SHOW!="2"&&$PUBLISH_SHOW!="3")
         $NOTIFY_STATUS_STR="<font color='#00AA00'><b>"._("��Ч")."</font>";
      else
      {
         if ($PUBLISH_SHOW=="2")
            $NOTIFY_STATUS_STR="<font color='blue'><b>"._("������")."</font>";
         if ($PUBLISH_SHOW=="3")
            $NOTIFY_STATUS_STR="<font color='red'><b>"._("δͨ��")."</font><br><a href='javascript:my_affair($NOTIFY_ID)'; title="._("����鿴�������").">"._("�������")."</a>"; 
      }
   }

   if($END_DATE!="" || $PUBLISH=="0")
   {
     if(compare_date($CUR_DATE,$END_DATE)>0)
     {
        $NOTIFY_STATUS=3;
        $NOTIFY_STATUS_STR="<font color='#FF0000'><b>"._("��ֹ")."</font>";
     }
   }
   
  if($TOP_SHOW=="1")
   {
   	  if($TOP_MAX_DAYS!="")//�������������ö�����
   	  {
   	  	 if($TOP_DAYS_SHOW!=0 && $TOP_DAYS_SHOW<=$TOP_MAX_DAYS)//����������ö��������������֮��
   	  	 {
   	  	 	$TOP_END_DATE=date("Y-m-d",strtotime($BEGIN_DATE)+($TOP_DAYS_SHOW-1)*24*60*60);
   	  	 	
   	  	 }
   	  	 else
   	  	   $TOP_END_DATE=date("Y-m-d",strtotime($BEGIN_DATE)+($TOP_MAX_DAYS-1)*24*60*60);   
   	  }
   	  else
   	  {
   	     if($TOP_DAYS_SHOW!=0)
   	         $TOP_END_DATE=date("Y-m-d",strtotime($BEGIN_DATE)+($TOP_DAYS_SHOW-1)*24*60*60);
   	     else
   	         $TOP_END_DATE="";
   	  	
   	  }
   	  if($TOP_END_DATE=="" || compare_date($CUR_DATE,$TOP_END_DATE)!=1)  	  
         $SUBJECT_SHOW="<font color=red><b>".$SUBJECT_SHOW."</b> <img src='".MYOA_STATIC_SERVER."/static/images/arrow_up.gif' title='"._("���ö�")."'></font>";
      else
      {
         $NOTIFY_ID=intval($NOTIFY_ID); 
         $SUBJECT_SHOW="<font color='".$SUBJECT_COLOR."'>".$SUBJECT_SHOW."</font>";
         $update="update NOTIFY set TOP=0 where NOTIFY_ID='$NOTIFY_ID' and TOP=1";//���ı�־
         exequery(TD::conn(),$update);
      }
   }
   else
      $SUBJECT_SHOW="<font color='".$SUBJECT_COLOR."'>".$SUBJECT_SHOW."</font>";

   if($PUBLISH_SHOW=="0")
      $NOTIFY_STATUS_STR="";

   if($NOTIFY_COUNT==1)
   {
?>
<table class="TableList" width="95%" align="center">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("ѡ��")?></td>
      <td nowrap align="center"><?=_("������")?></td>
      <td nowrap align="center"><?=_("����")?></td>
      <td nowrap align="center"><?=_("������Χ")?></td>
      <td nowrap align="center"><?=_("����")?></td>
      <td nowrap align="center"><?=_("����ʱ��")?> <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_down.gif" width="11" height="10"></td>
      <td nowrap align="center"><?=_("��Ч����")?></td>
      <td nowrap align="center"><?=_("��ֹ����")?></td>
      <td nowrap align="center"><?=_("��ǰ״̬")?></td>
      <td nowrap align="center"><?=_("����")?></td>
    </tr>
<?
   }
   if($NOTIFY_COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";
?>
 <tr class="<?=$TableLine?>">
      <td>&nbsp;<input type="checkbox" name="email_select" value="<?=$NOTIFY_ID?>">
      <td nowrap align="center"><u title="<?=_("���ţ�")?><?=$DEPT_NAME?>" style="cursor:hand"><?="$FROM_NAME";?></u></td>
      <td nowrap align="center"><?=$TYPE_NAME?></td>
      <td style="cursor:hand" title="<?=$TO_NAME_TITLE?>"><?=$TO_NAME_STR?></td>
      <td><a href="javascript:open_notify('<?=$NOTIFY_ID?>');" title="<?=$SUBJECT_TITLE?>"><?=$SUBJECT_SHOW?></a></td>
      <td align="center"><?=$SEND_TIME?></a></td>
      <td nowrap align="center"><?=$BEGIN_DATE?></td>
      <td nowrap align="center"><?=$END_DATE?></td>
      <td nowrap align="center"><?=$NOTIFY_STATUS_STR==""? $PUBLISH_DESC:$NOTIFY_STATUS_STR?></td>
      <td nowrap >
      <?if (($NOTIFY_STATUS==2|| $NOTIFY_STATUS==3) && $PUBLISH_SHOW==1)
      {
      ?>
      <a href="javascript:show_reader('<?=$NOTIFY_ID?>');" title="<?=_("�������")?>"> <?=_("�������")?></a>&nbsp;
       <?
      }
      if($NOTIFY_STATUS==1&&$PUBLISH_SHOW=="1" && ($_SESSION["LOGIN_USER_PRIV"]=="1" || $AUDITER==""))//����������OA����Ա���Խ���
      {
      ?>
      <a href="manage.php?NOTIFY_ID=<?=$NOTIFY_ID?>&OPERATION=1&start=<?=$start?>&SEARCH=1&SEND_TIME_MIN=<?=$SEND_TIME_MIN?>&SEND_TIME_MAX=<?=$SEND_TIME_MAX?>&SUBJECT=<?=$SUBJECT?>&CONTENT=<?=$CONTENT?>&FORMAT=<?=$FORMAT?>&TYPE_ID=<?=$TYPE_ID?>&PUBLISH=<?=$PUBLISH?>&TOP=<?=$TOP?>&TO_ID=<?=$TO_ID?>&STAT=<?=$STAT?>"> <?=_("������Ч")?></a>&nbsp;
      <?
      }
      else if($NOTIFY_STATUS==2&&$PUBLISH_SHOW=="1" && ($_SESSION["LOGIN_USER_PRIV"]=="1" || $AUDITER=="")) //����������OA����Ա���Խ���
      {
      ?>
      <a href="manage.php?NOTIFY_ID=<?=$NOTIFY_ID?>&OPERATION=2&start=<?=$start?>&SEARCH=1&SEND_TIME_MIN=<?=$SEND_TIME_MIN?>&SEND_TIME_MAX=<?=$SEND_TIME_MAX?>&SUBJECT=<?=$SUBJECT?>&CONTENT=<?=$CONTENT?>&FORMAT=<?=$FORMAT?>&TYPE_ID=<?=$TYPE_ID?>&PUBLISH=<?=$PUBLISH?>&TOP=<?=$TOP?>&TO_ID=<?=$TO_ID?>&STAT=<?=$STAT?>"> <?=_("��ֹ")?></a>&nbsp;
      <?
      }
      else if($NOTIFY_STATUS==3&&$PUBLISH_SHOW=="1" && ($_SESSION["LOGIN_USER_PRIV"]=="1" || $AUDITER==""))//����������OA����Ա���Խ���
      {
      ?>
      <a href="manage.php?NOTIFY_ID=<?=$NOTIFY_ID?>&OPERATION=3&start=<?=$start?>&SEARCH=1&SEND_TIME_MIN=<?=$SEND_TIME_MIN?>&SEND_TIME_MAX=<?=$SEND_TIME_MAX?>&SUBJECT=<?=$SUBJECT?>&CONTENT=<?=$CONTENT?>&FORMAT=<?=$FORMAT?>&TYPE_ID=<?=$TYPE_ID?>&PUBLISH=<?=$PUBLISH?>&TOP=<?=$TOP?>&TO_ID=<?=$TO_ID?>&STAT=<?=$STAT?>"> <?=_("��Ч")?></a>&nbsp;
      <?
      }
      if($_SESSION["LOGIN_USER_PRIV"]=="1"||($PUBLISH_SHOW!=1&&$PUBLISH_SHOW!=2)||($PUBLISH_SHOW==1 && $NOTIFY_EDIT_PRIV=="1" && $AUDITER==""))  //oa����Ա�����޸ġ������δͨ����δ�����Ŀ����޸ģ�����ֱ�ӷ��������ÿ��޸ģ�
      {
      ?>
         <a href="javascript:modify_notify('<?=$NOTIFY_ID?>','<?=$start?>')" > <?=_("�޸�")?></a>&nbsp;
         <a href="javascript:delete_notify('<?=$NOTIFY_ID?>')"> <?=_("ɾ��")?></a>
      <?
      }
      ?> 
      </td>
    </tr>
<?
}
if($NOTIFY_COUNT==0)
{
   Message("",_("�޷��������Ĺ���֪ͨ"));
   Button_Back();
   exit;
}
else
{
?>
<tr class="TableControl">
<td colspan="10">
    <input type="checkbox" name="allbox" id="allbox_for" ><label for="allbox_for"><?=_("ȫѡ")?></label> &nbsp;
    <a href="javascript:cancel_top();" title="<?=_("����ȡ���ö�")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/cancel_top.gif" align="absMiddle"><?=_("ȡ���ö�")?></a>&nbsp;
     <?
     if($_SESSION["LOGIN_USER_PRIV"]==1)
     {
     ?>
    <a href="javascript:delete_mail();" title="<?=_("ɾ����ѡ����֪ͨ")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("ɾ��")?></a>&nbsp;
    <?
     }
    ?> 
   <!-- <a href="javascript:notify_end();" title="<?=_("������ֹ��ѡ����֪ͨ")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/sms_type11.gif" align="absMiddle"><?=_("��ֹ")?></a>&nbsp;-->
</td>
</tr>
</table>
<?

}
?>
 <br><center><input type="button" value="<?=_("����")?>" class="BigButton" onClick="location.href='query.php'"></center> 
</body>

</html>
