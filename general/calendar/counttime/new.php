<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/editor.php");
//2013-4-11 �������ѯ
if($IS_MAIN==1)
	$QUERY_MASTER=true;
else
   $QUERY_MASTER=""; 

$HTML_PAGE_TITLE = _("���˵���ʱ��");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/13/task_center.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/validation/validationEngine.jquery.min.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/validation/jquery.validationEngine.min.js" type="text/javascript" charset="utf-8"></script>
<script Language="JavaScript">
 jQuery(document).ready(function(){
    jQuery("#form1").validationEngine({promptPosition:"centerRight"});
}); 		

function change()
{
    if(document.getElementById("YEAR").checked)
    {
        document.getElementById("END_DATE").innerHTML="<?=_("�������ڣ�")?>";
    }
    else
    {
        document.getElementById("END_DATE").innerHTML="<?=_("��ֹ���ڣ�")?>";
    }    
}
window.onload = function(){  
	document.form1.CONTENT.focus();
   jQuery('a.PickColor').click(function(){
      document.form1.STYLE.value = jQuery(this).attr("index");
      jQuery(this).siblings().removeClass('PickColorActive');
      jQuery(this).addClass('PickColorActive');
   });
};
</script>

<?
$CUR_DATE=date("Y-m-d",time());
$DATE_STR = _("��ֹ���ڣ�");
if($ROW_ID!="")
{
   $query="select * from COUNTDOWN where ROW_ID='$ROW_ID'";
   $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
   if($ROW=mysql_fetch_array($cursor))
   {
      $ROW_ID=$ROW["ROW_ID"];
      $ORDER_NO=$ROW["ORDER_NO"];
      $TO_DEPT=$ROW["TO_DEPT"];
      $TO_PRIV=$ROW["TO_PRIV"];
      $TO_USER=$ROW["TO_USER"];
      $CONTENT=$ROW["CONTENT"];
      $END_TIME=$ROW["END_TIME"];
      $ANNUAL = $ROW["ANNUAL"];
      if($END_TIME=="0000-00-00")
      	$END_TIME="";
      $COUNT_TYPE=$ROW["COUNT_TYPE"];
      $BEGIN_TIME=$ROW["BEGIN_TIME"];
      $STYLE=$ROW["STYLE"];
   }
   else
     exit;
   
   $DEPT_NAME="";
   if($TO_DEPT=="ALL_DEPT")
      $DEPT_NAME=_("ȫ�岿��");
   else
      $DEPT_NAME=GetDeptNameById($TO_DEPT);
   $PRIV_NAME=GetPrivNameById($TO_PRIV);
   $USER_NAME=GetUserNameById($TO_USER);
   if($ANNUAL=="1")
   {
      $DATE_STR = _("�������ڣ�");
   }
}
?>
<body class="bodycolor" >
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"><?if($ROW_ID!=""){ echo _("�༭����ʱ��");?> <?}else echo _("�½�����ʱ��");?></span>
    </td>
  </tr>
</table>
<br>
<br>

<form enctype="multipart/form-data" action="add.php"  method="post" id="form1" name="form1" >
<table class="TableBlock" width="80%" align="center">
   <tr>
     <td nowrap class="TableData" width="110"> <?=_("���ƣ�")?></td>
     <td class="TableData" width="380">
       <input type="text" name="CONTENT" size="30" maxlength="50" class="BigInput validate[required]" data-prompt-position="centerRight:75,-6" value="<?=$CONTENT?>"><?=_("(��:���ڷż�)")?>
     </td>
   </tr>
   <tr>
     <td nowrap class="TableData">�Ƿ�����Ϊ��ȼ����գ� </td>
     <td class="TableData" colspan="3">
       <input type="checkbox" name="YEAR" id="YEAR" onClick="change();" <?if($ANNUAL==1){?>checked<?}?>> ����Ϊ��ȼ�����      </td>
    </tr>

   <tr id="down">
     <td nowrap class="TableData" width="15%"><span id="END_DATE"> <?=$DATE_STR?></span></td>
     <td class="TableData">
       <input type="text" name="END_TIME_D" size="10" maxlength="10" class="BigInput validate[required]" data-prompt-position="centerRight:0,-6" value="<?=$END_TIME?>" onClick="WdatePicker()">
     </td>
   </tr>
     <tr>
     <td nowrap class="TableData"> <?=_("������ɫ��")?></td>
     <td class="TableData">
<?
for($I=0; $I<17; $I++)
{
   echo '<a class="PickColor'.($STYLE == $I ? " PickColorActive" : "").'" href="javascript:;" index="'.$I.'"><span class="color_style_'.$I.'"></span></a>';
}
?>
   <?=_("(���Զ��屳����ɫ,3�����Զ���ɳ�ɫ,1�����Զ���ɺ�ɫ,�Զ��嵹��ʱ���ں��Զ���ɻ�ɫ)")?> </td>
   </tr>
    <tr>
     <td nowrap class="TableData" width="15%"> <?=_("����ţ�")?></td>
     <td class="TableData">
       <input type="text" name="ORDER_NO" size="5" maxlength="2" class="BigInput validate[required,custom[onlyNumberSp]]" data-prompt-position="bottomLeft:0,-6" value="<?=$ORDER_NO?>"> <?=_("(�ɲ�ָ��������ʱ��ʱ������ͬһ�찴���������)")?>
     </td>
   </tr>
    <tr align="center" class="TableControl">
      <td colspan="4" nowrap>
        <input type="hidden" name="ROW_ID" value="<?=$ROW_ID?>">
        <input type="hidden" name="CUR_DATE" value="<?=$CUR_DATE?>">
        <input type="hidden" name="STYLE" value="<?=$STYLE?>">
        <input type="submit" value="<?=_("����")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='index.php'">

      </td>
    </tr>
  </table>
</form>

</body>
</html>