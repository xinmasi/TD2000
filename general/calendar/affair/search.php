<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
//2013-4-11 �������ѯ
if($IS_MAIN==1)
	$QUERY_MASTER=true;
else
   $QUERY_MASTER="";  

$HTML_PAGE_TITLE = _("�����ѯ");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/calendar/css/calendar_person.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/calendar.js"></script>
<script Language=JavaScript>
	
function delete_affair(AFF_ID,SEND_TIME_MIN_SEA,SEND_TIME_MAX_SEA,TYPES,CONTENT)
{
 msg='<?=_("ȷ��Ҫɾ����������")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?SEARCH=1&AFF_ID=" + AFF_ID+"&SEND_TIME_MIN="+SEND_TIME_MIN_SEA+"&SEND_TIME_MAX="+SEND_TIME_MAX_SEA+"&TYPE="+TYPES+"&CONTENT="+CONTENT;
  window.location=URL;
 }
}
function my_note(AFF_ID)
{
  my_left=document.body.scrollLeft+event.clientX-event.offsetX-250;
  my_top=document.body.scrollTop+event.clientY-event.offsetY+150;

  window.open("note.php?AFF_ID="+AFF_ID,"note_win"+AFF_ID,"height=400,width=550,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+ my_top +",left="+ my_left +",resizable=yes");
}
function check_all()
{
 for (i=0;i<document.getElementsByName("email_select").length;i++)
 {
   if(document.getElementById("allbox_for").checked)
      document.getElementsByName("email_select").item(i).checked=true;
   else
      document.getElementsByName("email_select").item(i).checked=false;
 }

 if(i==0)
 {
   if(document.getElementById("allbox_for").checked)
      document.getElementsByName("email_select").checked=true;
   else
      document.getElementsByName("email_select").checked=false;
 }
}

function check_one(el)
{
   if(!el.checked)
      document.getElementById("allbox_for").checked=false;
}
function get_checked()
{
  checked_str="";
  for(i=0;i<document.getElementsByName("email_select").length;i++)
  {

      el=document.getElementsByName("email_select").item(i);
      if(el.checked)
      {  val=el.value;
         checked_str+=val + ",";
      }
  }

  if(i==0)
  {
      el=document.getElementsByName("email_select");
      if(el.checked)
      {  val=el.value;
         checked_str+=val + ",";
      }
  }
  return checked_str;
}
function delete_mail(SEND_TIME_MIN_SEA,SEND_TIME_MAX_SEA,TYPES,CONTENT)
{
  delete_str=get_checked();
  if(delete_str=="")
  {
     alert("<?=_("Ҫɾ������������ѡ������һ����")?>");
     return;
  }
  msg='<?=_("ȷ��Ҫɾ����ѡ������")?>';
  if(window.confirm(msg))
  {
    url="delete.php?SEARCH=1&AFF_ID="+ delete_str +"&PAGE_START=<?=$PAGE_START?>&SEND_TIME_MIN="+SEND_TIME_MIN_SEA+"&SEND_TIME_MAX="+SEND_TIME_MAX_SEA+"&TYPE="+TYPES+"&CONTENT="+CONTENT;
    location=url;
  }
}
</script>

<body class="bodycolor">

<?
 $CUR_DATE=date("Y-m-d",time());
 $CUR_TIME=date("Y-m-d H:i:s",time());
  //-----------�Ϸ���У��---------
  if($SEND_TIME_MIN!="")
  {
    $TIME_OK=is_date($SEND_TIME_MIN);

    if(!$TIME_OK)
    { 
			$MSG1 = sprintf(_("���ڵĸ�ʽ���ԣ�Ӧ���� %s"), $CUR_DATE);
			Message(_("����"),$MSG1);
      Button_Back();
      exit;
    }
    $SEND_TIME_MIN_SEA=$SEND_TIME_MIN;
    $SEND_TIME_MIN=$SEND_TIME_MIN." 00:00:00";
    $SEND_TIME_MIN=strtotime($SEND_TIME_MIN);
  }

  if($SEND_TIME_MAX!="")
  {
    $TIME_OK=is_date($SEND_TIME_MAX);

    if(!$TIME_OK)
    { 
    	$MSG2 = sprintf(_("���ڵĸ�ʽ���ԣ�Ӧ���� %s"), $CUR_DATE);
			Message(_("����"),$MSG2);
      Button_Back();
      exit;
    }
    $SEND_TIME_MAX_SEA=$SEND_TIME_MAX;
    $SEND_TIME_MAX=$SEND_TIME_MAX." 23:59:59";
    $SEND_TIME_MAX=strtotime($SEND_TIME_MAX);
  }

 //------------------------ ���������ַ��� ------------------
 $CONDITION_STR="";
 if($TYPE =="2")
    $CONDITION_STR.=" and TYPE ='2'";
 else if($TYPE =="3")
    $CONDITION_STR.=" and TYPE ='3'";
 else if($TYPE =="4")
    $CONDITION_STR.=" and TYPE ='4'";
 else if($TYPE =="5")
    $CONDITION_STR.=" and TYPE ='5'";
$TYPE_SEA=$TYPE;
if($CONTENT!="")
{
    $CONDITION_STR.=" and CONTENT like '%".$CONTENT."%'";
    $CONTENT_SEA=$CONTENT;
}
 
if($SEND_TIME_MIN!="" && $SEND_TIME_MAX!="")
{
    if($SEND_TIME_MAX < $SEND_TIME_MIN)
    {
        Message("",_("����ʱ�䲻��С�ڿ�ʼʱ�䣡"));
        Button_Back();
        exit;
    }
    else
    {
        $CONDITION_STR.=" and ((BEGIN_TIME  >='$SEND_TIME_MIN' and BEGIN_TIME  <='$SEND_TIME_MAX') or (BEGIN_TIME  <='$SEND_TIME_MIN' and (END_TIME  >='$SEND_TIME_MIN' or END_TIME = 0)))";
    }
}
else if($SEND_TIME_MIN!="")
{
    $CONDITION_STR.=" and (END_TIME  >='$SEND_TIME_MIN' or END_TIME  = 0)";
}
else if($SEND_TIME_MAX!="")
{
    $CONDITION_STR.=" and BEGIN_TIME  <='$SEND_TIME_MAX'";
}

$query = "SELECT * from   AFFAIR where (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TAKER)) ".$CONDITION_STR." order by BEGIN_TIME,END_TIME ";
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small" style="margin-top:10px; margin-bottom:10px;">
  <tr>
    <td class="Big"><span class="big3"> <?=_("�����������ѯ���")?></span>
    </td>
  </tr>
</table>

<?
$DEL_COUNT=0;
$AFF_COUNT=0;
$cursor=exequery(TD::conn(),$query,$QUERY_MASTER);
while($ROW=mysql_fetch_array($cursor))
{
   $AFF_COUNT++;

    $AFF_ID =$ROW["AFF_ID"];
    $BEGIN_TIME=$ROW["BEGIN_TIME"];
    $BEGIN_TIME=date("Y-m-d",$BEGIN_TIME);
    $END_TIME=$ROW["END_TIME"];
    $USER_ID = $ROW["USER_ID"];
    if($END_TIME!=0)
    {
      $END_TIME=date("Y-m-d",$END_TIME);
    }
    $BEGIN_TIME_TIME=$ROW["BEGIN_TIME_TIME"];
    $END_TIME_TIME=$ROW["END_TIME_TIME"];
    $TYPE=$ROW["TYPE"];
    $REMIND_DATE  =$ROW["REMIND_DATE"];
    $REMIND_TIME  =$ROW["REMIND_TIME"];
    $CONTENT=$ROW["CONTENT"];
    $MANAGER_ID=$ROW["MANAGER_ID"];
    $END_TIME=$END_TIME=="0" ? "" : $END_TIME;
    
    $CONTENT=csubstr(strip_tags($CONTENT), 0, 100);
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
    case "6":
        $TYPE_DESC=_("������������");
        break;
    }


if($AFF_COUNT==1)
{
?>

<table class="table table-bordered table-hover" style="background-color:#fff" width="95%" align="center">
    <thead id="searchThead">
      <th width="40"><?=_("ѡ��")?></th>
      <th nowrap align="center"><?=_("��ʼ����")?><i class="icon-arrow-up"></i></th>
      <th nowrap align="center"><?=_("��������")?></th>
      <th nowrap align="center"><?=_("��ʼʱ��")?></th> 
      <th nowrap align="center"><?=_("����ʱ��")?></th>
      <th nowrap align="center"><?=_("��������")?></th>
      <th nowrap align="center"><?=_("��������")?></th>
      <th nowrap align="center"><?=_("����ʱ��")?></th>
      <th nowrap align="center"><?=_("��������")?></th>
      <th nowrap align="center" width="70"><?=_("����")?></th>
    </thead>

<?
    }
?>
    <tr class="searchContent">
    	
    	<td>&nbsp;<?if(($MANAGER_ID=="" && $USER_ID==$_SESSION["LOGIN_USER_ID"]) || $MANAGER_ID==$_SESSION["LOGIN_USER_ID"] || $_SESSION["LOGIN_USER_PRIV"]==1)
         {
            $DEL_COUNT++;
            
            ?><input type="checkbox" name="email_select" value="<?=$AFF_ID?>" onClick="check_one(self);">
         	<?}?>
      <td nowrap align="center"><?=$BEGIN_TIME?></td>
      <td nowrap align="center"><?=$END_TIME?></td>
      <td align="center" width=120><?=$BEGIN_TIME_TIME?></td>
      <td align="center" width=120><?=$END_TIME_TIME?></td>
      <td nowrap align="center"><?if($TYPE==2)echo _("��������");else if($TYPE==3)echo _("��������");else if($TYPE==4)echo _("��������");else if($TYPE==5)echo _("��������");else if($TYPE==6)echo _("������������");?></td>
      
      <td nowrap align="center"><?=substr($REMIND_DATE,0)?></td>
      <td nowrap align="center"><?=substr($REMIND_TIME,0)?></td>
      <td title="<?=$AFF_TITLE?>"><span class="type<?=$TYPE?>">&nbsp</span><a href="#" onClick="my_note(<?=$AFF_ID?>);"><?=csubstr(strip_tags($CONTENT),0,100);?></a></td>
      <td nowrap align="center">
      <?
         if(($MANAGER_ID=="" && $USER_ID==$_SESSION["LOGIN_USER_ID"]) || $MANAGER_ID==$_SESSION["LOGIN_USER_ID"] || $_SESSION["LOGIN_USER_PRIV"]==1)
         {
      ?>
          <a href="modify_search.php?AFF_ID=<?=$AFF_ID?>"> <?=_("�޸�")?></a>&nbsp;
          <a href="javascript:delete_affair(<?=$AFF_ID?>,'<?=$SEND_TIME_MIN_SEA?>','<?=$SEND_TIME_MAX_SEA?>','<?=$TYPE_SEA?>','<?=$CONTENT_SEA?>');"> <?=_("ɾ��")?></a>
      <?
         }
         else
         {
       ?>
          <a href="#" onClick="my_note(<?=$AFF_ID?>);"><?=_("�鿴")?></a>
      <?      
         }
      ?>    
      </td>
    </tr>
<?
 }

if($AFF_COUNT==0)
{
   Message("",_("�޷���������������"));
?>
 <center> <button type="button" class="btn" onClick="location='index.php'"><?=_("����")?></button> </center>
 <?
   exit;
}
else
{
?>
 <tr class="searchContent">
    
   <?
if($DEL_COUNT > 0)
{
?> 
    <td colspan="10" class="form-inline">
      <label class="checkbox" for="allbox_for">
      <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><?=_("ȫѡ")?>
      </label>
      <button class="btn" onClick="javascript:delete_mail('<?=$SEND_TIME_MIN_SEA?>','<?=$SEND_TIME_MAX_SEA?>','<?=$TYPE_SEA?>','<?=$CONTENT_SEA?>');" title="<?=_("ɾ����ѡ����")?>"><?=_("ɾ��")?></button>&nbsp;
    </td>
 <?
}
 ?>
  </tr>
</table>
<br>
<center> <button type="button" class="btn" onClick="location='index.php'"><?=_("����")?></button> </center>
<?
}

?>
</body>

</html>
