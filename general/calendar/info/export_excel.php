<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");
include_once("check_priv.inc.php");

$HTML_PAGE_TITLE = _("�ճ̰��ŵ���");
include_once("inc/header.inc.php");
ob_end_clean();
Header("Cache-control: private");
Header("Content-type: application/vnd.ms-excel");
Header("Content-Disposition: attachment; ".get_attachment_filename(_("�ճ̰���").".xls"));
?>
<?
if($CAL_TYPE==0) //�ճ�
{
?>

<body>
  <table border="1" cellspacing="1" width="95%" class="small" cellpadding="3">
      <tr style="BACKGROUND: #D3E5FA; color: #000000; font-weight: bold;">
        <td nowrap align="center"><?=_("����")?></td>
        <td nowrap align="center"><?=_("�û�")?></td>
        <td nowrap align="center"><?=_("��ʼʱ��")?></td>
        <td nowrap align="center"><?=_("����ʱ��")?></td>
        <td nowrap align="center"><?=_("��������")?></td>
        <td nowrap align="center"><?=_("��������")?></td>
        <td nowrap align="center"><?=_("���ȳ̶�")?></td>
        <td nowrap align="center"><?=_("������")?></td>
        <td nowrap align="center"><?=_("״̬")?></td>
      </tr>
<?
}
if($CAL_TYPE==1) //����������
{
?>
<body>
  <table border="1" cellspacing="1" width="95%" class="small" cellpadding="3">
      <tr style="BACKGROUND: #D3E5FA; color: #000000; font-weight: bold;"> 
       <td nowrap align="center"><?=_("����")?></td>      
       <td nowrap align="center"><?=_("�û�")?></td>
    	 <td nowrap align="center"><?=_("��ʼ����")?> <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_up.gif" width="11" height="10"></td>
	    <td nowrap align="center"><?=_("��������")?></td>
	    <td nowrap align="center"><?=_("��ʼʱ��")?></td>
	    <td nowrap align="center"><?=_("����ʱ��")?></td>
       <td nowrap align="center"><?=_("��������")?></td>
       <td nowrap align="center"><?=_("��������")?></td>
       <td nowrap align="center"><?=_("����ʱ��")?></td>
       <td nowrap align="center"><?=_("��������")?></td>
       <td nowrap align="center"><?=_("������")?></td>
      </tr>
<?
}
if($CAL_TYPE==2)//����
{
?>
<body>
  <table border="1" cellspacing="1" width="95%" class="small" cellpadding="3">
      <tr style="BACKGROUND: #D3E5FA; color: #000000; font-weight: bold;"> 
       <td nowrap align="center"><?=_("����")?></td>      
       <td nowrap align="center"><?=_("�û�")?></td>
       <td nowrap align="center"><?=_("���")?></td>
       <td nowrap align="center"><?=_("��ʼʱ��")?> <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_up.gif" width="11" height="10"></td>
       <td nowrap align="center"><?=_("����ʱ��")?></td>
       <td nowrap align="center"><?=_("��������")?></td>
       <td nowrap align="center"><?=_("�������")?></td>
       <td nowrap align="center"><?=_("״̬")?></td>
       <td nowrap align="center"><?=_("��������")?></td>
       <td nowrap align="center"><?=_("������")?></td>
      </tr> 
<?
}
$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());
$CUR_TIME_U=time();
 //----------- �Ϸ���У�� ---------
if($SEND_TIME_MIN!="")
{
   $TIME_OK=is_date($SEND_TIME_MIN);

   if(!$TIME_OK)
   { 
   	 $MSG = sprintf(_("���ڵĸ�ʽ���ԣ�Ӧ���� %s"), $CUR_DATE);
   	 Message(_("����"),$MSG);
     Button_Back();
     exit;
   }
   $SEND_TIME_MIN=$SEND_TIME_MIN." 00:00:00";
   $SEND_TIME_MIN_U=strtotime($SEND_TIME_MIN);
}

if($SEND_TIME_MAX!="")
{
   $TIME_OK=is_date($SEND_TIME_MAX);

   if(!$TIME_OK)
   { 
   	 $MSG = sprintf(_("���ڵĸ�ʽ���ԣ�Ӧ���� %s"), $CUR_DATE);
   	 Message(_("����"),$MSG);
     Button_Back();
     exit;
   }
   $SEND_TIME_MAX=$SEND_TIME_MAX." 23:59:59";
   $SEND_TIME_MAX_U=strtotime($SEND_TIME_MAX);
}
   $USER_ID_STR="";
   $query = "SELECT USER_ID,USER_NAME,DEPT_ID from USER,USER_PRIV where USER.USER_PRIV=USER_PRIV.USER_PRIV and (USER.NOT_LOGIN='0' or USER.NOT_MOBILE_LOGIN='0') ".$WHERE_STR." order by PRIV_NO,USER_NO,USER_NAME";//--------------------------------------------------�иĶ�
   $cursor1= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor1))
   {
      $USERS[$ROW["USER_ID"]]["DEPT"]=dept_long_name($ROW["DEPT_ID"]);
      $USERS[$ROW["USER_ID"]]["NAME"]=$ROW["USER_NAME"];
      $USER_ID_STR.=$ROW["USER_ID"].",";
   }
  $USER_ID_STR_ARRAY=explode(",",td_trim($USER_ID_STR));
//������ճ�...................................................
if($CAL_TYPE==0)
{
   //------------------------ ���������ַ��� ------------------
    $CONDITION_STR="";
    if($CAL_LEVEL=="0")
       $CONDITION_STR.=" and CAL_LEVEL=''";
    else if($CAL_LEVEL=="1")
       $CONDITION_STR.=" and CAL_LEVEL='1'";
    else if($CAL_LEVEL=="2")
       $CONDITION_STR.=" and CAL_LEVEL='2'";
    else if($CAL_LEVEL=="3")
       $CONDITION_STR.=" and CAL_LEVEL='3'";
    else if($CAL_LEVEL=="4")
       $CONDITION_STR.=" and CAL_LEVEL='4'";
   
    if($CONTENT!="")
       $CONDITION_STR.=" and CONTENT like '%".$CONTENT."%'";
    if($SEND_TIME_MIN!="")
       $CONDITION_STR.=" and CAL_TIME>='$SEND_TIME_MIN_U'";
    if($SEND_TIME_MAX!="")
       $CONDITION_STR.=" and END_TIME<='$SEND_TIME_MAX_U'";
   
    if($OVER_STATUS=="1")
       $CONDITION_STR.=" and OVER_STATUS='0' and CAL_TIME>'$CUR_TIME_U'";
    else if($OVER_STATUS=="2")
       $CONDITION_STR.=" and OVER_STATUS='0' and CAL_TIME<='$CUR_TIME_U' and END_TIME>='$CUR_TIME_U'";
    else if($OVER_STATUS=="3")
       $CONDITION_STR.=" and OVER_STATUS='0' and END_TIME<'$CUR_TIME_U'";
    else if($OVER_STATUS=="4")
       $CONDITION_STR.=" and OVER_STATUS='1'";
   

   $CAL_COUNT=0;
   $CODE_NAME=array();
   $MANAGER=array();
   //��������������ճ�(find_in_set('$USER_ID',TAKER) or find_in_set('$USER_ID',OWNER))
   for($I=0;$I<count($USER_ID_STR_ARRAY);$I++)
   {
      if($USER_ID_STR_ARRAY[$I]=="")
   		continue;
      $query = "SELECT * from CALENDAR where (USER_ID='$USER_ID_STR_ARRAY[$I]' or find_in_set('$USER_ID_STR_ARRAY[$I]',TAKER) or find_in_set('$USER_ID_STR_ARRAY[$I]',OWNER)) and CAL_TYPE!='2'".$CONDITION_STR." order by CAL_TIME,END_TIME";
      $cursor=exequery(TD::conn(),$query);
      while($ROW=mysql_fetch_array($cursor))
      {
         $USER_ID=$ROW["USER_ID"];
         $CAL_TIME=$ROW["CAL_TIME"];
         $CAL_TIME=date("Y-m-d H:i:s",$CAL_TIME);
         $END_TIME=$ROW["END_TIME"];
         $END_TIME=date("Y-m-d H:i:s",$END_TIME);
         $CAL_TYPE=$ROW["CAL_TYPE"];
         $CAL_LEVEL=$ROW["CAL_LEVEL"];
         $CONTENT=$ROW["CONTENT"];
         $MANAGER_ID=$ROW["MANAGER_ID"];
         $OVER_STATUS=$ROW["OVER_STATUS"];
         $CONTENT=td_htmlspecialchars($CONTENT);  
         $USER_NAME=$USERS[$USER_ID]["NAME"];
         $DEPT_NAME=$USERS[$USER_ID]["DEPT"]; 
         $MANAGER_NAME="";
         if($MANAGER_ID!="")
         {
            if(!array_key_exists($CAL_TYPE, $MANAGER))
            {
             $query = "SELECT USER_NAME from USER where USER_ID='$MANAGER_ID'";
             $cursor1= exequery(TD::conn(),$query);
             if($ROW1=mysql_fetch_array($cursor1))
                $MANAGER[$MANAGER_ID]=$ROW1["USER_NAME"];
            }
          $MANAGER_NAME=$MANAGER[$MANAGER_ID];
       }
   
       if(!array_key_exists($CAL_TYPE, $MANAGER))
          $CODE_NAME[$CAL_TYPE]=get_code_name($CAL_TYPE,"CAL_TYPE");
       $CAL_TYPE=$CODE_NAME[$CAL_TYPE];
       
       if($OVER_STATUS=="" || $OVER_STATUS=="1")
          $OVER_STATUS1="<font color='#00AA00'><b>"._("�����")."</span>";
       elseif(compare_time($CUR_TIME,$END_TIME)>0)
          $OVER_STATUS1="<font color='#FF0000'><b>"._("�ѳ�ʱ")."</span>";
       else if(compare_time($CUR_TIME,$CAL_TIME)<0)
          $OVER_STATUS1="<font color='#0000AA'><b>"._("δ��")."</span>";
       else
          $OVER_STATUS1="<font color='#00AA00'><b>"._("������")."</span>";
   ?>
       <tr style="BACKGROUND: #FFFFFF;">
         <td><?=$DEPT_NAME?></td>
         <td><?=$USER_NAME?></td>
         <td><?=substr($CAL_TIME,0,-3)?></td>
         <td><?=substr($END_TIME,0,-3)?></td>
         <td><?=$CAL_TYPE?></td>
         <td><?=$CONTENT?></td>
         <td><?=($CAL_LEVEL!="" ? cal_level_desc($CAL_LEVEL) : "")?></td>
         <td><?=$MANAGER_NAME?></td>
         <td><?=$OVER_STATUS1?></td>
       </tr>
   <?
    }
   }
   ?>
  </table>
<?
}
if($CAL_TYPE==1)//����������
{
     $CONDITION_STR="";
       
    if($CONTENT!="")
       $CONDITION_STR.=" and CONTENT like '%".$CONTENT."%'";
    if($SEND_TIME_MIN!="")
       $CONDITION_STR.=" and BEGIN_TIME>='$SEND_TIME_MIN'";
    if($SEND_TIME_MAX!="")
       $CONDITION_STR.=" and END_TIME<='$SEND_TIME_MAX'";
   $AFF_COUNT=0;
   $DEL_COUNT=0;
   $CODE_NAME=array();
   $MANAGER=array();
   for($I=0;$I<count($USER_ID_STR_ARRAY);$I++)
   {
      $Tquery = "SELECT * from AFFAIR where (USER_ID='$USER_ID_STR_ARRAY[$I]' or find_in_set('$USER_ID_STR_ARRAY[$I]',TAKER)) and CAL_TYPE<>'2'".$CONDITION_STR." order by BEGIN_TIME,END_TIME ";   
      $Tcursor= exequery(TD::conn(),$Tquery);
      while($ROW=mysql_fetch_array($Tcursor))
      {
         $AFF_COUNT++;
         $AFF_ID =$ROW["AFF_ID"];
         $BEGIN_TIME=$ROW["BEGIN_TIME"];
         $BEGIN_TIME=date("Y-m-d",$BEGIN_TIME);
         $END_TIME=$ROW["END_TIME"];
         
         if($END_TIME!=0)
         {
            $END_TIME=date("Y-m-d",$END_TIME);
         }
         $BEGIN_TIME_TIME=$ROW["BEGIN_TIME_TIME"];
         $END_TIME_TIME=$ROW["END_TIME_TIME"];
         
         $END_TIME=$END_TIME=="0" ? "" : $END_TIME;
         
         //$END_TIME=date("Y-m-d H:i:s",$END_TIME);
         $TYPE=$ROW["TYPE"];
         $REMIND_DATE  =$ROW["REMIND_DATE"];
         $REMIND_TIME  =$ROW["REMIND_TIME"];
         $CONTENTS=$ROW["CONTENT"];
         $MANAGER_ID=$ROW["MANAGER_ID"];
         $CONTENTS=strip_tags($CONTENTS);
         $USER_ID_C=$ROW["USER_ID"];
         $USER_ID=$USER_ID_STR_ARRAY[$I];
         $MANAGER_ID=$ROW["MANAGER_ID"];
         $OVER_STATUS=$ROW["OVER_STATUS"];
         $OWNER=$ROW["OWNER"];
         $USER_NAME=$USERS[$USER_ID]["NAME"];
         $DEPT_NAME=$USERS[$USER_ID]["DEPT"];
         $MANAGER_NAME="";
         if($MANAGER_ID!="")
         {           
             $query = "SELECT USER_NAME from USER where USER_ID='$MANAGER_ID'";
             $cursor1= exequery(TD::conn(),$query);
             if($ROW1=mysql_fetch_array($cursor1))
                $MANAGER_NAME=$ROW1["USER_NAME"];
         }
         switch($TYPE)
         {
            case "2":
            $TYPE_DESC=_("��������");
            break;
            case "3":
            $TYPE_DESC=_("��������");
            if($REMIND_DATE=="1")
               $REMIND_DATE=_("��һ");
            elseif($REMIND_DATE=="2")
               $REMIND_DATE=_("�ܶ�");
            elseif($REMIND_DATE=="3")
               $REMIND_DATE=_("����");
            elseif($REMIND_DATE=="4")
               $REMIND_DATE=_("����");
            elseif($REMIND_DATE=="5")
               $REMIND_DATE=_("����");
            elseif($REMIND_DATE=="6")
               $REMIND_DATE=_("����");
            elseif($REMIND_DATE=="0")
               $REMIND_DATE=_("����");
            break;
            case "4":
            $TYPE_DESC=_("��������");
            $REMIND_DATE.=_("��");
            break;
            case "5":
            $TYPE_DESC=_("��������");
            $REMIND_DATE=str_replace("-",_("��"),$REMIND_DATE)._("��");
            break;
         }       
?>

         <tr style="BACKGROUND: #FFFFFF;">
         <td><?=$DEPT_NAME?></td>
         <td><?=$USER_NAME?></td>
         <td><?=$BEGIN_TIME?></td>
         <td><?=$END_TIME?></td>
         <td><?=$BEGIN_TIME_TIME?></td>
         <td><?=$END_TIME_TIME?></td>
         <td><?if($TYPE==2)echo _("��������");else if($TYPE==3)echo _("��������");else if($TYPE==4)echo _("��������");else if($TYPE==5)echo _("��������");?></td>
         <td> <?=substr($REMIND_DATE,0)?></td>
         <td><?=substr($REMIND_TIME,0)?></td>
         <td><?=$CONTENTS?></td>
         <td><?=$MANAGER_NAME?></td>
       </tr>
<?
         }
 }
 ?>
   </table>
<?
}
if($CAL_TYPE==2) //����
{
 //------------------------ ���������ַ��� ------------------
   $CONDITION_STR="";
   if($IMPORTANT =="0")
      $CONDITION_STR.=" and IMPORTANT =''";
   else if($IMPORTANT =="1")
      $CONDITION_STR.=" and IMPORTANT ='1'";
   else if($IMPORTANT =="2")
      $CONDITION_STR.=" and IMPORTANT ='2'";
   else if($IMPORTANT =="3")
      $CONDITION_STR.=" and IMPORTANT ='3'";
   else if($IMPORTANT =="4")
      $CONDITION_STR.=" and IMPORTANT ='4'";
     
   if($CONTENT!="")
      $CONDITION_STR.=" and (CONTENT like '%".$CONTENT."%' or SUBJECT like '%".$CONTENT."%')";
   if($SEND_TIME_MIN!="")
      $CONDITION_STR.=" and BEGIN_DATE >='$SEND_TIME_MIN'";
   if($SEND_TIME_MAX!="")
      $CONDITION_STR.=" and END_DATE<='$SEND_TIME_MAX'";
   if($TASK_STATUS=="1")
      $CONDITION_STR.=" and TASK_STATUS='1' and BEGIN_DATE>'$CUR_DATE'";
   else if($TASK_STATUS=="2")
      $CONDITION_STR.=" and TASK_STATUS='2' and BEGIN_DATE<='$CUR_DATE' and END_DATE>='$CUR_DATE'";
   else if($TASK_STATUS=="3")
      $CONDITION_STR.=" and TASK_STATUS='3' and END_DATE<'$CUR_DATE'";
   else if($TASK_STATUS=="4")
      $CONDITION_STR.=" and TASK_STATUS='4'";
   else if($TASK_STATUS=="5")
      $CONDITION_STR.=" and TASK_STATUS='5'";
   $TASK_COUNT=0;
   for($I=0;$I<count($USER_ID_STR_ARRAY);$I++)
   {
      $query = "SELECT * from  TASK where USER_ID='$USER_ID_STR_ARRAY[$I]' and TASK_TYPE<>'2'".$CONDITION_STR." order by BEGIN_DATE,END_DATE ";
      $cursor=exequery(TD::conn(),$query);
      
      while($ROW=mysql_fetch_array($cursor))
      {
        $TASK_COUNT++;
        $TASK_ID =$ROW["TASK_ID"];
        $BEGIN_DATE=$ROW["BEGIN_DATE"];
        $END_DATE=$ROW["END_DATE"];
        $TASK_TYPE=$ROW["TASK_TYPE"];
        $SUBJECT =$ROW["SUBJECT"];
        $CONTENTS=$ROW["CONTENT"];
        $IMPORTANTS=$ROW["IMPORTANT"];
        $TASK_STATUSS=$ROW["TASK_STATUS"];
        $TASK_NO =$ROW["TASK_NO"];
         $USER_ID_C=$ROW["USER_ID"];
         $USER_ID=$USER_ID_STR_ARRAY[$I];
         $MANAGER_ID=$ROW["MANAGER_ID"];
         $USER_NAME=$USERS[$USER_ID]["NAME"];
         $DEPT_NAME=$USERS[$USER_ID]["DEPT"];
         $MANAGER_NAME="";
         if($MANAGER_ID!="")
         {           
             $query = "SELECT USER_NAME from USER where USER_ID='$MANAGER_ID'";
             $cursor1= exequery(TD::conn(),$query);
             if($ROW1=mysql_fetch_array($cursor1))
                $MANAGER_NAME=$ROW1["USER_NAME"];
         }
   ?>
         <tr style="BACKGROUND: #FFFFFF;">
         <td><?=$DEPT_NAME?></td>
         <td><?=$USER_NAME?></td>
         <td><?=$TASK_NO?></td>
         <td><?=$BEGIN_DATE?></td>
         <td ><?=$END_DATE?></td>
         <td><? if($TASK_TYPE==1)echo _("����");else echo _("����");?></td>
         <td><?=$SUBJECT?></td>
         <td> <?if($TASK_STATUSS==1)echo _("δ��ʼ");else if($TASK_STATUSS==2)echo _("������");else if($TASK_STATUSS==3)echo _("�����");else if($TASK_STATUSS==4)echo _("�ȴ�������");else if($TASK_STATUSS==5)echo _("���ƴ�");?></td>
         <td><?=$CONTENTS?></td>
         <td><?=$MANAGER_NAME?></td>
       </tr>
   
   <?
      }
   }
?>
   </table>
<? 
}
?>
</body>
</html>