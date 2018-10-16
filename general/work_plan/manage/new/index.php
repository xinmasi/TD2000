<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/editor.php");

$HTML_PAGE_TITLE = _("新建工作计划");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function CheckForm()
{
   if(document.form1.TO_ID.value==""&& document.form1.TO_ID3.value==""&& document.form1.TCOPY_TO_ID.value=="")
   { alert("<?=_("发布范围（部门或人员）和参与人不能同时为空！")?>");
     return (false);
   }

   if(document.form1.NAME.value=="")
   { alert("<?=_("工作计划的标题不能为空！")?>");
     return (false);
   }
   
   if(document.form1.BEGIN_DATE.value !=""&&document.form1.END_DATE.value !=""&&document.form1.BEGIN_DATE.value>document.form1.END_DATE.value)
   { alert("<?=_("生效日期不能迟于终止日期！")?>");
     return (false);
   }
   document.form1.OP.value="0";

   return true;
}

function InsertImage(src)
{
   AddImage2Editor('CONTENT', src);
}
function sendForm(publish)
{
 document.form1.PUBLISH.value=publish;
 if(CheckForm())
 {
    document.form1.OP.value="0";
    document.form1.submit();
  }
}

function upload_attach()
{
  if(CheckForm())
  {
     document.form1.OP.value="1";
     document.form1.submit();
  }
}

function openRemark()
{
   myleft=(screen.availWidth-520)/2;
   mytop=(screen.availHeight-180)/2;
   window.open("remark.php","","height=180,width=520,status=0,toolbar=no,menubar=no,location=no,scrollbars=no,resizable=yes,top="+mytop+",left="+myleft);	
}

function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
  var msg = sprintf("<?=_("确定要删除文件 '%s' 吗？")?>", ATTACHMENT_NAME);
  if(window.confirm(msg))
  {
    URL="delete_attach.php?PLAN_ID=<?=$PLAN_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
    window.location=URL;
  }
}

</script>

<?

if($PLAN_ID!="")
{
 $CUR_DATE=date("Y-m-d",time());
 $PLAN_ID=intval($PLAN_ID);
 $query = "SELECT * from WORK_PLAN where PLAN_ID='$PLAN_ID'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
    $PLAN_ID=$ROW["PLAN_ID"];
    $NAME=$ROW["NAME"];
    $CONTENT=$ROW["CONTENT"];
    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $END_DATE=$ROW["END_DATE"];
    $TYPE=$ROW["TYPE"];
    $TO_ID=$ROW["TO_ID"];
    $MANAGER=$ROW["MANAGER"];
    $PARTICIPATOR=$ROW["PARTICIPATOR"];
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
    $ATTACHMENT_COMMENT=$ROW["ATTACHMENT_COMMENT"];
    $REMARK=$ROW["REMARK"];
    $TO_PERSON_ID=$ROW["TO_PERSON_ID"];
    $OPINION_LEADER=$ROW["OPINION_LEADER"];

 }

 if($BEGIN_DATE=="0000-00-00")
    $BEGIN_DATE="";
 if($END_DATE=="0000-00-00")
    $END_DATE="";

 $TO_NAME="";
 $TOK=strtok($TO_ID,",");
 while($TOK!="")
 {
   $query1="select DEPT_NAME from DEPARTMENT where DEPT_ID='$TOK'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
      $TO_NAME.=$ROW1["DEPT_NAME"].",";

   $TOK=strtok(",");
 }
 if($TO_ID=="ALL_DEPT")
    $TO_NAME=_("全体部门");

 $COPY_TO_NAME="";
 $TOK=strtok($PARTICIPATOR,",");
 while($TOK!="")
 {
   $query1="select USER_NAME from USER where USER_ID='$TOK'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
      $TCOPY_TO_NAME.=$ROW1["USER_NAME"].",";

   $TOK=strtok(",");
 }

 $TO_PERSON_NAME="";
 $TOK=strtok($TO_PERSON_ID,",");
 while($TOK!="")
 {
   $query1="select USER_NAME from USER where USER_ID='$TOK'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
      $TO_PERSON_NAME.=$ROW1["USER_NAME"].",";

   $TOK=strtok(",");
 }

 $SECRET_TO_NAME="";
 $TOK=strtok($MANAGER,",");
 while($TOK!="")
 {
   $query1="select USER_NAME from USER where USER_ID='$TOK'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
      $SECRET_TO_NAME.=$ROW1["USER_NAME"].",";

   $TOK=strtok(",");
 }

 $OPINION_LEADER_NAME="";
 $TOK=strtok($OPINION_LEADER,",");
 while($TOK!="")
 {
   $query1="select USER_NAME from USER where USER_ID='$TOK'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
      $OPINION_LEADER_NAME.=$ROW1["USER_NAME"].",";

   $TOK=strtok(",");
 }
}
?>
<body class="bodycolor">
<?
if($PLAN_ID=="")
   $TITLE=_("新建工作计划");
else
   $TITLE=_("编辑工作计划");
?>
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle" width="22" height="20"><span class="big3"> <?=$TITLE?> </span>&nbsp;
    	<span class="small"><a href="javascript:;"  onClick="openRemark();"><img src="<?=MYOA_STATIC_SERVER?>/static/images/question.gif" align="absmiddle" width="20" height="20"><?=_("查看说明")?></a></span>
    </td>
  </tr>
</table>

<form enctype="multipart/form-data" action="submit.php"  method="post" name="form1">
<table class="TableBlock" width="90%" align="center">
    <tr>
      <td nowrap class="TableData"> <?=_("计划类型与名称：")?></td>
      <td class="TableData">
        <select name="TYPE" class="BigSelect">
<?
 $query = "SELECT * from PLAN_TYPE order by TYPE_NO";
 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
    $TYPE_ID=$ROW["TYPE_ID"];
    $TYPE_NAME=$ROW["TYPE_NAME"];
?>
        <option value="<?=$TYPE_ID?>" <?if($TYPE==$TYPE_ID) echo "selected";?>><?=$TYPE_NAME?></option>
<?
 }
?>
        </select>&nbsp;<?=_("名称")?>:
        <input type="text" name="NAME" size="46" maxlength="200" class="BigInput" value="<?=$NAME?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("发布范围(部门)：")?></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID" value="<?=$TO_ID?>">
        <textarea cols=45 name="TO_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$TO_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept()"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("发布范围(人员)：")?></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID3" value="<?=$TO_PERSON_ID?>">
        <textarea cols=45 name="TO_NAME3" rows="2" class="BigStatic" wrap="yes" readonly><?=$TO_PERSON_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('96','','TO_ID3', 'TO_NAME3')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('TO_ID3', 'TO_NAME3')"><?=_("清空")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("参与人：")?></td>
      <td class="TableData">
        <input type="hidden" name="TCOPY_TO_ID" value="<?=$PARTICIPATOR?>">
        <textarea cols=45 name="TCOPY_TO_NAME" rows="2" class="BigStatic" wrap="yes" readonly><?=$TCOPY_TO_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('96','','TCOPY_TO_ID', 'TCOPY_TO_NAME')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('TCOPY_TO_ID', 'TCOPY_TO_NAME')"><?=_("清空")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("负责人：")?></td>
      <td class="TableData">
        <input type="hidden" name="SECRET_TO_ID" value="<?=$MANAGER?>">
        <textarea cols=45 name="SECRET_TO_NAME" rows="2" class="BigStatic" wrap="yes" readonly><?=$SECRET_TO_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('96','','SECRET_TO_ID', 'SECRET_TO_NAME')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('SECRET_TO_ID', 'SECRET_TO_NAME')"><?=_("清空")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("批注领导：")?></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID4" value="<?=$OPINION_LEADER?>">
        <textarea cols=45 name="TO_NAME4" rows="2" class="BigStatic" wrap="yes" readonly><?=$OPINION_LEADER_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('96','','TO_ID4', 'TO_NAME4')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('TO_ID4', 'TO_NAME4')"><?=_("清空")?></a>
      </td>
    </tr>
    <tr>
	 <td nowrap class="TableData"><?=_("附件：")?></td>
      <td class="TableData">
<?
      if($ATTACHMENT_NAME=="")
         echo _("无");
      else
         echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,0,0);
?>
      </td>
    </tr>
    <tr height="25">
      <td nowrap class="TableData"><?=_("附件选择：")?></td>
      <td class="TableData">
         <script>ShowAddFile();ShowAddImage();</script>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("附件说明：")?></td>
      <td class="TableData">
        <textarea cols=55 name="ATTACHMENT_COMMENT" rows=2 class="BigINPUT" wrap="yes"><?=$ATTACHMENT_COMMENT?></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("备注：")?></td>
      <td class="TableData">
        <textarea cols=55 name="REMARK" rows=2 class="BigINPUT" wrap="yes"><?=$REMARK?></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("有效期：")?></td>
      <td class="TableData">
        <?=_("开始于：")?><input type="text" name="BEGIN_DATE" size="10" id="start_time" maxlength="10" class="BigInput" value="<?=$BEGIN_DATE?>" onClick="WdatePicker()">
       
        <?=_("为空为立即生效")?> &nbsp;
        <?=_("结束于：")?><input type="text" name="END_DATE" size="10" maxlength="10" class="BigInput" value="<?=$END_DATE?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})" >
      
        <?=_("为空为手动终止")?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("提醒：")?></td>
      <td class="TableData">
         <?=sms_remind(12);?>
      </td>
    </tr>
    <tr>
      <td class="TableData" colspan="2"><?=_("计划内容：")?></td>
    </tr>
    <tr id="EDITOR">
      <td class="TableData" colspan="4">
<?
$editor = new Editor('CONTENT') ;
$editor->Config = array('model_type' => '09');
$editor->Value = $CONTENT ;
$editor->Create() ;
?>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="hidden" name="OP" value="">
        <input type="hidden" name="PUBLISH" value="">
        <input type="hidden" name="PLAN_ID" value="<?=$PLAN_ID?>">
        <input type="hidden" name="CREATOR" value="<?=$_SESSION["LOGIN_USER_ID"]?>">
        <input type="hidden" name="CREATE_DATE" value="<?=date('Y-m-d')?>">
        <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
        <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
        <input type="button" value="<?=_("提交")?>" class="BigButton" onClick="sendForm('1');">&nbsp;&nbsp;
        <input type="button" value="<?=_("保存")?>" class="BigButton" onClick="sendForm('0');">&nbsp;&nbsp;
<?
if($PLAN_ID!="")
{
?>
        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="history.back();">
<?
}
?>
      </td>
    </tr>
  </table>
</form>

</body>
</html>