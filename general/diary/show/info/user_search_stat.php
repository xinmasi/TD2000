<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");
include_once("check_priv.inc.php");

$HTML_PAGE_TITLE = _("������־��ѯ");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>


<body class="bodycolor">
<?
  //----------- �Ϸ���У�� ---------
if($BEGIN_DATE!="")
{
  $TIME_OK=is_date($BEGIN_DATE);

  if(!$TIME_OK)
  { Message(_("����"),_("��ʼ���ڸ�ʽ���ԣ�Ӧ���� 1999-1-2"));
    Button_Back();
    exit;
  }
}

if($END_DATE!="")
{
  $TIME_OK=is_date($END_DATE);

  if(!$TIME_OK)
  { Message(_("����"),_("��ֹ���ڸ�ʽ���ԣ�Ӧ���� 1999-1-2"));
    Button_Back();
    exit;
  }
}
?>
<div class="big3">
   <div class="title"><? if($FROM_WORKSTAT==1) echo _("����򱨡���");?><?=_("��ѯ���")?></div>
</div>
<?
if($BEGIN_DATE!="")
{
   $URL_BEGIN_DATE = $BEGIN_DATE;
   $BEGIN_DATE.=" 00:00:00";
   $WHERE_STR = " and DIA_DATE >= '$BEGIN_DATE'";
}
if($END_DATE!="")
{
   $URL_END_DATE = $END_DATE;  
   $END_DATE.=" 23:59:59";
   $WHERE_STR .= " and DIA_DATE <= '$END_DATE'";
}
if($SUBJECT!="")
{
   $URL_SUBJECT = $SUBJECT;
   $WHERE_STR .= " and SUBJECT like '%$SUBJECT%'";
}
if($TO_ID1!="")
{
   $WHERE_STR .= " and find_in_set(DIARY.USER_ID,'$TO_ID1')";
}
if($TO_ID!="" && $TO_ID!="ALL_DEPT")
{
   $TO_ID .= "," . GetChildDeptId($TO_ID);
   if(substr($TO_ID,-1,1)==",")
      $TO_ID=substr($TO_ID,0,-1);
   $TO_ID = str_replace(",,", ",", $TO_ID);

   $WHERE_STR .= " and b.DEPT_ID in ($TO_ID)";
}
if($PRIV_ID!="")
{
   if(substr($PRIV_ID,-1,1)==",")
      $PRIV_ID=substr($PRIV_ID,0,-1);   
   $WHERE_STR .= " and b.USER_PRIV in ($PRIV_ID)";
}
if($COPY_TO_ID!="")
{
   if(substr($COPY_TO_ID,-1,1)==",")
      $COPY_TO_ID=substr($COPY_TO_ID,0,-1);  
   $WHERE_STR .= " and find_in_set(DIARY.USER_ID ,'$COPY_TO_ID')";
}

if($DIA_TYPE=="")
   $WHERE_STR .= " and DIA_TYPE!='2'";
else
   $WHERE_STR .= " and DIA_TYPE='$DIA_TYPE'";

//============================ ��ʾ��־ =======================================
$query = "SELECT DIARY.DIA_ID,DIARY.DIA_DATE,DIARY.DIA_TYPE,DIARY.SUBJECT,DIARY.COMPRESS_CONTENT,DIARY.CONTENT,DIARY.ATTACHMENT_ID,DIARY.ATTACHMENT_NAME,DIARY.LAST_COMMENT_TIME,DIARY.USER_ID from DIARY left join USER b on b.USER_ID = DIARY.USER_ID LEFT OUTER JOIN USER_PRIV  g ON b.USER_PRIV=g.USER_PRIV where 1=1 ".$WHERE_STR;
$query .= " order by DIA_DATE desc,DIA_ID desc";
$cursor= exequery(TD::conn(),$query);
$DIA_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $DIA_ID=$ROW["DIA_ID"];
   $DIA_DATE=$ROW["DIA_DATE"];
   $DIA_DATE=strtok($DIA_DATE," ");
   $DIA_TYPE=$ROW["DIA_TYPE"];
   $SUBJECT=$ROW["SUBJECT"];
   $NOTAGS_CONTENT=$ROW["CONTENT"];    
   $CONTENT=@gzuncompress($ROW["COMPRESS_CONTENT"]);
   if($CONTENT=="")   
      $CONTENT=$NOTAGS_CONTENT;     
   $USER_ID=$ROW["USER_ID"];
   
   if(!is_user_priv($USER_ID, $MY_PRIV))
      continue;
   $DIA_COUNT++;   
   
   $query1 = "SELECT USER_NAME,DEPT_ID from USER where USER_ID='$USER_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
   {
      $USER_NAME=$ROW1["USER_NAME"];
      $DEPT_ID=$ROW1["DEPT_ID"];      
   }
   
   $DEPT_NAME= dept_long_name($DEPT_ID);
       
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

   if($SUBJECT=="")
      $SUBJECT=csubstr(strip_tags($CONTENT),0,30).(strlen($CONTENT)>30?"...":"");

   $query1 = "SELECT count(*) from DIARY_COMMENT where DIA_ID='$DIA_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
      $COMMENT_COUNT=$ROW1[0];

   if($DIA_COUNT==1)
   {
?>
   <table class="TableList" width="100%">
<?
   }
   
   if($DIA_COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";
?>
   <tr class="<?=$TableLine?>">
     <td nowrap align="center"><?=$DIA_DATE?></td>
     <td nowrap align="center" width="60"><?=$USER_NAME?></td> 
     <td nowrap align="center" width="60"><?=$DEPT_NAME?></td>     
     <td width="160"><a href="read.php?DIA_ID=<?=$DIA_ID?>&USER_ID=<?=urlencode($USER_ID)?>&FROM=1&FROM_WORKSTAT=<?=$FROM_WORKSTAT?>&USER_NAME=<?=urlencode($USER_NAME)?>&BEGIN_DATE=<?=$URL_BEGIN_DATE?>&END_DATE=<?=$URL_END_DATE?>&SUBJECT=<?=urlencode($URL_SUBJECT)?>"><?=$SUBJECT?></a></td>
     <td><?=attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,0,1,1)?></td>
 <? if($FROMTYPE!="WORK_STAT")
    {
 ?>  	  
     <td width="60"><center>
     	<!--<a href="share.php?DIA_ID=<?=$DIA_ID?>&USER_ID=<?=$USER_ID?>&SUBJECT=<?=$SUBJECT?>&USER_NAME=<?=$USER_NAME?>&FROM_FLAG=2"><?=_("ָ������Χ")?></a>-->
     	<a href="comment.php?DIA_ID=<?=$DIA_ID?>&USER_ID=<?=$USER_ID?>&FROM=1&USER_NAME=<?=urlencode($USER_NAME)?>&BEGIN_DATE=<?=$URL_BEGIN_DATE?>&END_DATE=<?=$URL_END_DATE?>&SUBJECT=<?=urlencode($URL_SUBJECT)?>"><?=_("����")?></a> </center>
     </td>
  <?
    }
  ?> 
   </tr>
<?
}//while

if($DIA_COUNT==0)
{
  Message("",_("�޷�����������־��¼"));
}
else
{
?>
  <thead class="TableHeader">
     <td nowrap align="center" width="80"><?=_("����")?> <img border=0 src="<?=MYOA_IMG_SERVER?>/images/arrow_down.gif" width="11" height="10"></td>
     <td nowrap align="center" width="50"><?=_("����")?></td>
     <td nowrap align="center" width="50"><?=_("����")?></td>
     <td nowrap align="center"><?=_("��־����")?></td>
     <td nowrap align="center"><?=_("����")?></td>
    <? 
    if($FROMTYPE!="WORK_STAT")
    {
    ?> 
     <td nowrap align="center" width="30"><?=_("����")?></td>     
    <?
    }
    ?>
  </thead>
  </table><br>
<?
}

if($DIA_COUNT > 0)
{
	 $MSG2 = sprintf(_("�� %d ƪ��־"), $DIA_COUNT);
   Message("",$MSG2);
}

if($FROMTYPE=="WORK_STAT")
{
?>
   <br><div align="center">
      <input type="button"  value="<?=_("ȷ��")?>" class="BigButton" onClick="window.close();">
   </div>
<?
}	
else
{
?>
<div align="center">
 <input type="button"  value="<?=_("����")?>" class="BigButton" onClick="location='user_query.php';">
</div>
<?
}
?>
</body>
</html>
