<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
//2013-4-11 �������ѯ
if($IS_MAIN==1)
	$QUERY_MASTER=true;
else
   $QUERY_MASTER="";  

$HTML_PAGE_TITLE = _("��������");
include_once("inc/header.inc.php");
?>


<meta name="save" content="history">
<style>
.saveHistory  {behavior:url(#default#savehistory);}
</style>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/calendar.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>


<?
$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("H:i:s",time());

if($TASK_ID!="")
{
  $query="select * from TASK where TASK_ID='$TASK_ID'";
  $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
  if($ROW=mysql_fetch_array($cursor))
  {
    $TASK_ID=$ROW["TASK_ID"];
    $TASK_NO=$ROW["TASK_NO"];
    $USER_ID=$ROW["USER_ID"];

    if($USER_ID!=$_SESSION["LOGIN_USER_ID"])
       exit;

    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $END_DATE=$ROW["END_DATE"];

    if($BEGIN_DATE=="0000-00-00")
       $BEGIN_DATE="";

    if($END_DATE=="0000-00-00")
       $END_DATE="";

    $TASK_TYPE=$ROW["TASK_TYPE"];
    $TASK_STATUS=$ROW["TASK_STATUS"];
    $COLOR=$ROW["COLOR"];
    $IMPORTANT=$ROW["IMPORTANT"];
    $RATE=$ROW["RATE"];
    $FINISH_TIME=$ROW["FINISH_TIME"];
    $TOTAL_TIME=$ROW["TOTAL_TIME"];
    $USE_TIME=$ROW["USE_TIME"];
    $MANAGER_ID=$ROW["MANAGER_ID"];
    $querys="select USER_NAME from USER where USER_ID='$MANAGER_ID'";
    $cursors= exequery(TD::conn(),$querys);
    if($ROWS=mysql_fetch_array($cursors))
    {
    	$MANAGER_NAME=$ROWS['USER_NAME'];
    }
    if($FINISH_TIME=="0000-00-00 00:00:00")
       $FINISH_TIME="";
    if($TOTAL_TIME=="")
    $TOTAL_TIME=0;
    $SUBJECT=$ROW["SUBJECT"];
    $SUBJECT=td_htmlspecialchars($SUBJECT);

    $CONTENT=$ROW["CONTENT"];
  }
}
else
{
  $BEGIN_DATE=$CUR_DATE;
}
?>

<body class="bodycolor" >
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><span class="big3"> <?if($TASK_ID=="")echo _("�½�");else echo _("�༭");?><?=_("����")?></span>
    </td>
  </tr>
</table>

 <table class="table table-bordered" style="width:450px; margin:0 auto">
  <form action="update_other.php"  method="post" name="form1" >
    <tr>
      <td nowrap class=""><?=_("����ţ�")?></td>
      <td class="TableData" >
        <?=$TASK_NO?>
      </td>
       <td nowrap ><?=_("�����ˣ�")?></td>
      <td class="TableData" >
        <?=$MANAGER_NAME?>
      </td>
    </tr>
    <tr>
      <td nowrap > <?=_("���ͣ�")?></td>
      <td class="TableData" width=120>
          <?
          if($TASK_TYPE=="1") echo _("����");
          if($TASK_TYPE=="2") echo _("����");
         ?>
    
      </td>
      <td nowrap  width=40> <?=_("״̬��")?></td>
      <td class="TableData" width=180>
        <select name="TASK_STATUS">
          <option value="1" <?if($TASK_STATUS=="1") echo "selected";?>><?=_("δ��ʼ")?></option>
          <option value="2" <?if($TASK_STATUS=="2") echo "selected";?>><?=_("������")?></option>
          <option value="3" <?if($TASK_STATUS=="3") echo "selected";?>><?=_("�����")?></option>
          <option value="4" <?if($TASK_STATUS=="4") echo "selected";?>><?=_("�ȴ�������")?></option>
          <option value="5" <?if($TASK_STATUS=="5") echo "selected";?>><?=_("���Ƴ�")?></option>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap ><?=_("��ɫ��")?></td>
      <td class="TableData">
      	<?
      	 //������ɫ����
   $PARA_ARRAY=get_sys_para("CALENDAR_TASK_COLOR");
   $PARA_VALUE=$PARA_ARRAY["CALENDAR_TASK_COLOR"];
   $PARA_VALUE=explode(",",$PARA_VALUE);
   $CALENDAR_TASK_COLOR_0=$PARA_VALUE[0]; //��ɫ
   $CALENDAR_TASK_COLOR_1=$PARA_VALUE[1]; //��ɫ
   $CALENDAR_TASK_COLOR_2=$PARA_VALUE[2]; //��ɫ
   $CALENDAR_TASK_COLOR_3=$PARA_VALUE[3]; //��ɫ
   $CALENDAR_TASK_COLOR_4=$PARA_VALUE[4]; //��ɫ
   $CALENDAR_TASK_COLOR_5=$PARA_VALUE[5]; //��ɫ
   if($CALENDAR_TASK_COLOR_0=="")
      $CALENDAR_TASK_COLOR_0=_("��ɫ���");
   if($CALENDAR_TASK_COLOR_1=="")
      $CALENDAR_TASK_COLOR_1=_("��ɫ���");
   if($CALENDAR_TASK_COLOR_2=="")
      $CALENDAR_TASK_COLOR_2=_("��ɫ���");
   if($CALENDAR_TASK_COLOR_3=="")
      $CALENDAR_TASK_COLOR_3=_("��ɫ���");
   if($CALENDAR_TASK_COLOR_4=="")
      $CALENDAR_TASK_COLOR_4=_("��ɫ���");
   if($CALENDAR_TASK_COLOR_5=="")
      $CALENDAR_TASK_COLOR_5=_("��ɫ���");  
      	 if($COLOR=="")
      	    $COLOR_NAME= _("δָ��");
         if($COLOR=="1")
      	   $COLOR_NAME=$CALENDAR_TASK_COLOR_0;
         if($COLOR=="2")
      	    $COLOR_NAME=$CALENDAR_TASK_COLOR_1;
      	 if($COLOR=="3")
      	    $COLOR_NAME=$CALENDAR_TASK_COLOR_2;
      	 if($COLOR=="4")
      	   $COLOR_NAME =$CALENDAR_TASK_COLOR_3;
      	 if($COLOR=="5")
      	   $COLOR_NAME=$CALENDAR_TASK_COLOR_4;
      	 if($COLOR=="6")
      	   $COLOR_NAME=$CALENDAR_TASK_COLOR_5;
      	?>
      <span class="CalColor<?=$COLOR?>"><?=$COLOR_NAME?></span>
      </td>
      <td nowrap ><?=_("���ȼ���")?></td>
      <td class="TableData">
      		<?
      	 if($IMPORTANT=="")
      	   $IMPORTANT_NAME=_("δָ��");
         if($IMPORTANT=="1")
      	   $IMPORTANT_NAME=_("��Ҫ/����");
         if($IMPORTANT=="2")
      	   $IMPORTANT_NAME=_("��Ҫ/������");
      	 if($IMPORTANT=="3")
      	   $IMPORTANT_NAME=_("����Ҫ/����");
      	 if($IMPORTANT=="4")
      	    $IMPORTANT_NAME=_("����Ҫ/������");
      	
      	?>
      	<span class=CalLevel<?=$IMPORTANT?>><?=$IMPORTANT_NAME?></span>

      </td>
    </tr>
    <tr>
      <td nowrap ><?=_("������⣺")?></td>
      <td class="TableData" colspan=3>
     <?=$SUBJECT?>
      </td>
    </tr>
    <tr>
      <td nowrap ><?=_("��ֹ���ڣ�")?></td>
      <td class="TableData" colspan=3>
      <?=$BEGIN_DATE?>
        
        <?=_("������")?>
       <?=$END_DATE?>   
      </td>
    </tr>
    <tr>
      <td nowrap > <?=_("������ϸ��")?></td>
      <td class="TableData" colspan=3>
       <?=$CONTENT?>
      </td>
    </tr>
   
    <tr>
      <td nowrap ><?=_("��������")?></td>
      <td class="TableData" colspan=3>
        <?=_("�����")?> <INPUT type="text"name="RATE" class="input-small"  size="3" value="<?=$RATE?>"> %&nbsp;&nbsp;
        <?=_("���ʱ��")?> <input type="text" class="input-small" name="FINISH_TIME" size="20"  value="<?=$FINISH_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
       
      </td>
    </tr>
    <tr>
      <td nowrap ><?=_("��������")?></td>
      <td class="TableData" colspan=3>
        <?=_("��������")?> <input type="text" class="input-small" name="USE_TIME" size="4"  value="<?=$TOTAL_TIME?>"> <?=_("Сʱ")?><br>
        <?=_("ʵ�ʹ���")?> <input type="text" class="input-small" name="USE_TIME" size="4"  value="<?=$USE_TIME?>"> <?=_("Сʱ")?>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="4" nowrap>
      	<INPUT type="hidden" name="PAGE_START" value="<?=$PAGE_START?>">
      	<INPUT type="hidden" name="TASK_ID" value="<?=$TASK_ID?>">
        <button type="submit"  class="btn btn-info"><?=_("ȷ��")?></button>&nbsp;&nbsp;
        <button type="button"  class="btn" onClick="location='index.php'"><?=_("����")?></button>
      </td>
    </tr>
  </table>
</form>

</body>
</html>