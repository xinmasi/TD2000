<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");

//2013-04-11 ����������ѯ
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";


if($PARENT_ID!="" && $PARENT_ID!="0")
{
   $query = "SELECT * from VOTE_TITLE where VOTE_ID='$PARENT_ID'";
   $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
   if($ROW=mysql_fetch_array($cursor))
   {
      $TO_ID=$ROW["TO_ID"];
      $PRIV_ID=$ROW["PRIV_ID"];
      $VIEW_RESULT_PRIV_ID=$ROW["VIEW_RESULT_PRIV_ID"];
      $COPY_TO_ID=$ROW["USER_ID"];
      $VIEW_RESULT_USER_ID=$ROW["VIEW_RESULT_USER_ID"];
      $TYPE=$ROW["TYPE"];
      $MAX_NUM=$ROW["MAX_NUM"];
      $MIN_NUM=$ROW["MIN_NUM"];
      $ANONYMITY=$ROW["ANONYMITY"];
      $VIEW_PRIV=$ROW["VIEW_PRIV"];
      $BEGIN_DATE=$ROW["BEGIN_DATE"];
      $END_DATE=$ROW["END_DATE"];
      
      if($BEGIN_DATE=="0000-00-00")
         $BEGIN_DATE="";
      if($END_DATE=="0000-00-00")
         $END_DATE="";
      if($TO_ID=="ALL_DEPT")
         $TO_NAME=_("ȫ�岿��");
      else
      {
         $TO_NAME="";
         $TOK=strtok($TO_ID,",");
         while($TOK!="")
         {
            $query1="select DEPT_NAME from DEPARTMENT where DEPT_ID='$TOK'";
            $cursor1= exequery(TD::conn(),$query1);
            if($ROW=mysql_fetch_array($cursor1))
               $TO_NAME.=$ROW["DEPT_NAME"].",";
            $TOK=strtok(",");
         }
      }
        $PRIV_NAME=GetPrivNameById($PRIV_ID);
        $LOOK_TO_PRIV_NAME=GetPrivNameById($VIEW_RESULT_PRIV_ID);
        $COPY_TO_NAME=GetUserNameById($COPY_TO_ID);
        $LOOK_TO_USER_NAME=GetUserNameById($VIEW_RESULT_USER_ID);
   }
}
if($VOTE_ID!="")
{
    $query = "SELECT * from VOTE_TITLE where VOTE_ID='$VOTE_ID'";
    $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
    if($ROW=mysql_fetch_array($cursor))
    {
        $TO_ID                  = $ROW["TO_ID"];
        $PARENT_ID              = $ROW["PARENT_ID"];
        $PRIV_ID                = $ROW["PRIV_ID"];
        $VIEW_RESULT_PRIV_ID    = $ROW["VIEW_RESULT_PRIV_ID"];
        $COPY_TO_ID             = $ROW["USER_ID"];
        $VIEW_RESULT_USER_ID    = $ROW["VIEW_RESULT_USER_ID"];
        $SUBJECT                = $ROW["SUBJECT"];
        $CONTENT                = $ROW["CONTENT"];
        $TYPE                   = $ROW["TYPE"];
        $MAX_NUM                = $ROW["MAX_NUM"];
        $MIN_NUM                = $ROW["MIN_NUM"];
        $ANONYMITY              = $ROW["ANONYMITY"];
        $VIEW_PRIV              = $ROW["VIEW_PRIV"];
        $BEGIN_DATE             = $ROW["BEGIN_DATE"];
        $END_DATE               = $ROW["END_DATE"];
        $PUBLISH                = $ROW["PUBLISH"];
        $VOTE_NO                = $ROW["VOTE_NO"];
        $ATTACHMENT_ID          = $ROW["ATTACHMENT_ID"];
        $ATTACHMENT_NAME        = $ROW["ATTACHMENT_NAME"];
        $TOP                    = $ROW["TOP"];
        if($BEGIN_DATE=="0000-00-00 00:00:00")
        {
            $BEGIN_DATE="";
        }
        if($END_DATE=="0000-00-00 00:00:00")
        {
            $END_DATE="";
        }
        if($TO_ID=="ALL_DEPT")
        {
            $TO_NAME=_("ȫ�岿��");
        }
        else
        {
            $TO_NAME = "";
            $TOK     = strtok($TO_ID,",");
            while($TOK!="")
            {
                $query1     = "select DEPT_NAME from DEPARTMENT where DEPT_ID='$TOK'";
                $cursor1    = exequery(TD::conn(),$query1);
                if($ROW=mysql_fetch_array($cursor1))
                $TO_NAME.=$ROW["DEPT_NAME"].",";
                $TOK=strtok(",");
            }
        }
        $PRIV_NAME          = GetPrivNameById($PRIV_ID);
        $LOOK_TO_PRIV_NAME  = GetPrivNameById($VIEW_RESULT_PRIV_ID);
        $COPY_TO_NAME       = GetUserNameById($COPY_TO_ID);
        $LOOK_TO_USER_NAME  = GetUserNameById($VIEW_RESULT_USER_ID);
   }
}

$HTML_PAGE_TITLE = _("ͶƱ����");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/validation/validationEngine.jquery.min.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/validation/jquery.validationEngine.min.js" type="text/javascript" charset="utf-8"></script>
<script Language="JavaScript">
jQuery(document).ready(function(){
    jQuery("#form1").validationEngine({promptPosition:"centerRight"});
}); 
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function CheckForm(flag)
{
<?
if($PARENT_ID=="" || $PARENT_ID=="0")
{
?>
   if(document.form1.TO_ID.value=="" && document.form1.PRIV_ID.value=="" && document.form1.COPY_TO_ID.value=="")
   { 
      alert("<?=_("������ָ��һ�ַ�����Χ��")?>");
      return (false);
   }
<?
}
?>
    var subject = document.form1.SUBJECT.value;
    var reg = /^\s*$/;
    if(reg.test(subject))
    {
        var regs = /\s+/g;
        subject = subject.replace(regs,"");
    }
    if(subject == "")
    { 
       alert("<?=_("ͶƱ�ı��ⲻ��Ϊ�գ�")?>");
       return (false);
    } 
   if(document.form1.TYPE.value=="1")
   { 
      var min = document.getElementById("MIN_NUM").value;
      var max = document.getElementById("MAX_NUM").value;
      min = parseInt(min);
      max = parseInt(max);
      if(min > max)
	   {
	      alert("<?=_("��ѡ�����У���������ѡ����ӦС���������ѡ���")?>");
         return (false);
	   }
   } 
   /****����  ͶƱ������*****/
   if (flag=="0")
   {
	  msg='<?=_("��պ����ݲ��ָܻ���ȷ��Ҫִ�иò�����")?>';
	  if(!window.confirm(msg)){
		return;
	  }
      if(document.form1.PUBLISH.value=="")
      {
         document.form1.PUBLISH.value="0";
      }
   } 
   if(flag=="1")
   {
      document.form1.PUBLISH.value="1";
   }
    /****����  ͶƱ�������� songyang*****/
   if (flag=="2")
   {
      if(document.form1.PUBLISH.value=="")
      {
         document.form1.PUBLISH.value="0";
      }
      document.form1.VOTE_COUNT_CLEAR.value="0";
	}
   document.form1.submit();
}

function upload_attach()
{
   if(document.form1.PUBLISH.value=="")
      document.form1.PUBLISH.value="0";
 
   <?
   if($PARENT_ID=="" || $PARENT_ID=="0")
   {
   ?>
      if(document.form1.TO_ID.value=="" && document.form1.PRIV_ID.value=="" && document.form1.COPY_TO_ID.value=="")
      { 
         alert("<?=_("������ָ��һ�ַ�����Χ��")?>");
         return;
      }
  <?
   }
  ?>
   // if(document.form1.SUBJECT.value=="")
   // { 
      // alert("<?=_("ͶƱ�ı��ⲻ��Ϊ�գ�")?>");
      // return;
   // }
   
   document.form1.OP.value="0";
   document.form1.submit();
  
}

function change_type()
{
   if(document.form1.TYPE.value=="1")
      document.getElementById("MAX_NUM_DESC").style.display="";
   else
      document.getElementById("MAX_NUM_DESC").style.display="none";
}

function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
   var msg = sprintf("<?=_("ȷ��Ҫɾ���ļ� '%s' ��")?>", ATTACHMENT_NAME);
   if(window.confirm(msg))
   {
      URL="delete_attach.php?VOTE_ID=<?=$VOTE_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
      window.location=URL;
   }
}

</script>

<body class="bodycolor" onLoad="javascript:form1.SUBJECT.focus();">
<?
if($MAX_NUM=="")
   $MAX_NUM=0;
if($MIN_NUM=="")
   $MIN_NUM=0;
?>
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?if($VOTE_ID!="")echo _("�޸�");else echo _("�½�");?><?=_("ͶƱ")?></span>
    <?=help('005','skill/erp/vote_manage');?>
    </td>
  </tr>
</table>

<form enctype="multipart/form-data" action="add.php"  method="post" id="form1" name="form1">
<table class="TableBlock" width="70%" align="center">
<tr>
   <td nowrap class="TableData"> <?=_("���⣺")?><font color="red">(*)</font></td>
   <td class="TableData">
     <input type="text" name="SUBJECT" size="42" maxlength="100" class="BigInput validate[required]" data-prompt-position="centerRight:0,-7" value="<?=td_htmlspecialchars($SUBJECT)?>">
   </td>
</tr>
<?
if($PARENT_ID=="" || $PARENT_ID=="0")
{
?>
    <tr>
      <td nowrap class="TableData"><?=_("������Χ�����ţ���")?></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID" value="<?=$TO_ID?>">
        <textarea cols=40 name="TO_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$TO_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept('7')"><?=_("���")?></a>
       <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("���")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("������Χ����ɫ����")?></td>
      <td class="TableData">
        <input type="hidden" name="PRIV_ID" value="<?=$PRIV_ID?>">
        <textarea cols=40 name="PRIV_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$PRIV_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectPriv('7','PRIV_ID','PRIV_NAME')"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('PRIV_ID', 'PRIV_NAME')"><?=_("���")?></a>
      </td>
   </tr>
   <tr>
      <td nowrap class="TableData"><?=_("������Χ����Ա����")?></td>
      <td class="TableData">
        <input type="hidden" name="COPY_TO_ID" value="<?=$COPY_TO_ID?>">
        <textarea cols=40 name="COPY_TO_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$COPY_TO_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('119','7','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("���")?></a>
      </td>
   </tr>
      <tr>
	  <td nowrap class="TableData"><?=_("�鿴Ȩ�޷�Χ����ɫ����")?></td>
      <td class="TableData">
        <input type="hidden" name="VIEW_RESULT_PRIV_ID" value="<?=$VIEW_RESULT_PRIV_ID?>">
        <textarea cols=40 name="LOOK_TO_PRIV_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$LOOK_TO_PRIV_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectPriv('7','VIEW_RESULT_PRIV_ID', 'LOOK_TO_PRIV_NAME')"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('VIEW_RESULT_PRIV_ID', 'LOOK_TO_PRIV_NAME')"><?=_("���")?></a>
      </td>
   </tr>
   <tr>
      <td nowrap class="TableData"><?=_("�鿴Ȩ�޷�Χ����Ա����")?></td>
      <td class="TableData">
        <input type="hidden" name="VIEW_RESULT_USER_ID" value="<?=$VIEW_RESULT_USER_ID?>">
        <textarea cols=40 name="LOOK_TO_USER_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$LOOK_TO_USER_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('119','7','VIEW_RESULT_USER_ID', 'LOOK_TO_USER_NAME')"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('VIEW_RESULT_USER_ID', 'LOOK_TO_USER_NAME')"><?=_("���")?></a>
      </td>
   </tr>
<?
}
?>
    <tr>
      <td nowrap class="TableData"><?=_("ͶƱ������")?></td>
      <td class="TableData">
        <textarea name="CONTENT" class="BigInput"  cols="50" rows="3"><?=$CONTENT?></textarea>
      </td>
    </tr>
<?
if($PARENT_ID)
{
?>
    <tr>
      <td nowrap class="TableData"> <?=_("����ţ�")?></td>
      <td class="TableData">
        <input type="text" name="VOTE_NO" size="10" maxlength="20" class="BigInput" value="<?=$VOTE_NO?>">
      </td>
    </tr>
<?
}
?>
    <tr>
      <td nowrap class="TableData"> <?=_("���ͣ�")?></td>
      <td class="TableData">
        <select name="TYPE" class="BigSelect" onChange="change_type();">
          <option value="0" <?if($TYPE=="0") echo "selected";?>><?=_("��ѡ")?></option>
          <option value="1" <?if($TYPE=="1") echo "selected";?>><?=_("��ѡ")?></option>
          <option value="2" <?if($TYPE=="2") echo "selected";?>><?=_("�ı�����")?></option>
        </select>
<?
		$NUM_MSG1 = sprintf(_("�������ѡ��%s�"),"<input type='input' name='MAX_NUM' id='MAX_NUM' value='".$MAX_NUM."' size='2' class='SmallInput validate[custom[onlyNumberSp]]' data-prompt-position='centerRight:0,-7'>");
		$NUM_MSG2 = sprintf(_("��������ѡ��%s�"),"<input type='input' name='MIN_NUM' id='MIN_NUM' value='".$MIN_NUM."' size='2' class='SmallInput validate[custom[onlyNumberSp]]' data-prompt-position='centerRight:0,-7'>");
?>
        <span id="MAX_NUM_DESC" style="display:<?if($TYPE!="1") echo "none";?>"><?=$NUM_MSG1?>&nbsp;<?=$NUM_MSG2?><?=_("0������")?></span>
      </td>
    </tr>
<?
if($PARENT_ID=="" || $PARENT_ID=="0")
{
?>
    <tr>
      <td nowrap class="TableData"> <?=_("�鿴ͶƱ�����")?></td>
      <td class="TableData">
        <select name="VIEW_PRIV" class="BigSelect">
          <option value="0" <?if($VIEW_PRIV=="0") echo "selected";?>><?=_("ͶƱ������鿴")?></option>
          <option value="1" <?if($VIEW_PRIV=="1") echo "selected";?>><?=_("ͶƱǰ����鿴")?></option>
          <option value="2" <?if($VIEW_PRIV=="2") echo "selected";?>><?=_("������鿴")?></option>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("����ͶƱ��")?></td>
      <td class="TableData">
        <input type="checkbox" name="ANONYMITY" id="ANONYMITY" <?if($ANONYMITY=="1") echo "checked";?>><label for="ANONYMITY"><?=_("��������ͶƱ")?></label>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("��Ч�ڣ�")?></td>
      <td class="TableData">
        <?=_("��Ч���ڣ�")?><input type="text" name="BEGIN_DATE" size="10" maxlength="10" id="start_time" class="BigInput" value="<?=substr($BEGIN_DATE,0,10)?>" onClick="WdatePicker()"/>
        <?=_("Ϊ��Ϊ������Ч")?><br>
        <?=_("��ֹ���ڣ�")?><input type="text" name="END_DATE" size="10" maxlength="10" class="BigInput" value="<?=substr($END_DATE,0,10)?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>
        <?=_("Ϊ��Ϊ�ֶ���ֹ")?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("�ö���")?></td>
      <td class="TableData"><input type="checkbox" name="TOP" id="TOP" <?if($TOP=="1") echo "checked";?>><label for="TOP"><?=_("ʹͶƱ�ö�����ʾΪ��Ҫ")?></label>      
      </td>
    </tr>
    <tr class="TableData">
      <td nowrap><?=_("�����ĵ���")?></td>
      <td nowrap><?=attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,0,1,1)?></td>
    </tr>
    <tr height="25">
      <td nowrap class="TableData"><?=_("����ѡ��")?></td>
      <td class="TableData">
         <script>ShowAddFile();</script>
         <script>$("ATTACHMENT_upload_div").innerHTML='<a href="javascript:upload_attach();"><?=_("�ϴ�����")?></a>'</script>
      </td>
    </tr>
    <?
    if ($VOTE_ID!="")
	{
	?>
    <tr>
      <td nowrap class="TableData"> <?=_("����Ϣ���ѣ�")?></td>
      <td class="TableData">
<?=sms_remind(11);?>
      </td>
    </tr>
<?
   }
}
?>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="hidden" name="VOTE_ID" value="<?=$VOTE_ID?>">
        <input type="hidden" name="VOTE_COUNT_CLEAR" value="1">
        <input type="hidden" name="start" value="<?=$start?>">
        <input type="hidden" name="PARENT_ID" value="<?=$PARENT_ID?>">
        <input type="hidden" name="PUBLISH" value="<?=$PUBLISH?>">
        <input type="hidden" name="OP" value="">
        <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
        <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
<?
//ͶƱ��ĿΪ�գ����޸�ҳ�����ط�����ť�������ͶƱ��Ŀ���ſ��Է���
$query1="select * from VOTE_ITEM where VOTE_ID='$VOTE_ID'";
$cursor= exequery(TD::conn(),$query1,$QUERY_MASTER);
if($PUBLISH=="0" && $TYPE!="2" && mysql_fetch_row($cursor)){
    $status = 1;
}else{
    $status = 0;
}
if(($VOTE_ID!="")&&($PARENT_ID=="" || $PARENT_ID=="0")&&($PUBLISH!="1")&&$status==1)
{
?>
        <input type="button" value="<?=_("����")?>" class="BigButton" onClick="CheckForm('1');">&nbsp;&nbsp;
<?
}
?>
        <input title="�����ʷͶƱ���ݲ�����" type="button" value="<?=_("������ղ�����")?>" class="BigButton" onClick="CheckForm('0');">&nbsp;&nbsp;
        <input type="button" value="<?=_("����")?>" class="BigButton" onClick="CheckForm('2');">&nbsp;&nbsp;
        <input type="reset" value="<?=_("����")?>" class="BigButton">&nbsp;&nbsp;
<?
if($VOTE_ID!=""&&($PARENT_ID=="" || $PARENT_ID=="0"))
{
?>
        <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='index1.php?start=<?=$start?>'">
<?
}

if($PARENT_ID!=""&&$PARENT_ID!="0")
{
?>
        <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='vote.php?PARENT_ID=<?=$PARENT_ID?>&start=<?=$start?>'">
<?
}
?>
      </td>
    </tr>
  </table>
</form>

</body>
</html>