<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/editor.php"); 
//URL:webroot\general\bbs\edit.php

//2013-04-11 主从服务器查询判断
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";


$FONT_SIZE = get_font_size("FONT_DISCUSSION", 12);

$HTML_PAGE_TITLE = _("编辑或发表文章");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css" />
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<SCRIPT>
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function CheckForm()
{
  if(document.form1.SUBJECT.value.length > "100")
  {
    alert("<?=_("标题字符长度超过100!")?>");
    return false;
  }
    if(getEditorText('CONTENT').length==0 && getEditorHtml('CONTENT')=="")
  {
    alert("<?=_("内容不能为空!")?>");
    return false;
  }  

  if(NAME_SELECT==2 && document.form1.NICK_NAME.value=="")
  {
     alert("<?=_("署名不能为空!")?>");
     return false;
  }


  document.form1.OP.value="1";

  return true;
}

function upload_attach()
{
  if(CheckForm())
  {
     document.form1.OP.value="0";
     document.form1.submit();
  }
}

function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{

  var msg = sprintf("<?=_("确定要删除文件 '%s' 吗？")?>", ATTACHMENT_NAME);
  if(window.confirm(msg))
  {
    URL="delete_attach.php?BOARD_ID=<?=$BOARD_ID?>&PAGE_START=<?=$PAGE_START?>&COMMENT_ID=<?=$COMMENT_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
    window.location=URL;
  }
}

function InsertImage(src)
{
   AddImage2Editor('CONTENT', src);
}
//上传附件
function upload_attach2()
{
	
   var files=0;
   var inputs=document.getElementsByTagName("INPUT");
   for(var i=0;i<inputs.length; i++)
   {
      var input = inputs[i];
      if(isUndefined(input.type) || input.type.toLowerCase() != "file")
         continue;
      files++;
   }
   if(files <= 1)
   {
      alert("<?=_("您还没有添加附件")?>");
      return;
   }
   document.getElementById("PAGE_START").value="0";
   if(CheckForm())
   {
    document.getElementById("OP").value="0";
    document.getElementById("PAGE_START").value="0";
    document.form1.submit();
   }
}
</SCRIPT>


<body class="bodycolor" onLoad="document.form1.SUBJECT.focus()">

<?
$BOARD_ID=intval($BOARD_ID);
$BOARD_ID_OLD = $BOARD_ID;

if($COMMENT_ID!="")
   $TITLE = _("编辑文章");
else
   $TITLE = _("发表文章");
   

//------- 个人信息 --------
//$query = "SELECT USER_NAME,NICK_NAME,BBS_SIGNATURE from USER where UID='".$_SESSION["LOGIN_UID"]."'";
$query = "SELECT a.USER_NAME as USER_NAME,b.NICK_NAME as NICK_NAME,b.BBS_SIGNATURE as BBS_SIGNATURE from USER a, USER_EXT b where a.UID='".$_SESSION["LOGIN_UID"]."' AND a.UID=b.UID";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $USER_NAME=$ROW["USER_NAME"];
   $NICK_NAME=$ROW["NICK_NAME"];
   $BBS_SIGNATURE=$ROW["BBS_SIGNATURE"];
}

//------- 讨论区信息 -------
if($BOARD_ID!=""&&$BOARD_ID!="-1")
{
	$query = "SELECT * from BBS_BOARD where BOARD_ID='$BOARD_ID' and (DEPT_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',DEPT_ID) ".dept_other_sql("DEPT_ID")." or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID) ".priv_other_sql("PRIV_ID")." or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',BOARD_HOSTER))";
    $cursor = exequery(TD::conn(),$query,$QUERY_MASTER);
	if($ROW=mysql_fetch_array($cursor))
	{
	   $DEPT_ID = $ROW["DEPT_ID"];
	   $PRIV_ID = $ROW["PRIV_ID"];
	   $USER_ID1 = $ROW["USER_ID"];
	   $BOARD_NAME = $ROW["BOARD_NAME"];
	   $ANONYMITY_YN = $ROW["ANONYMITY_YN"];
	   $BOARD_HOSTER = $ROW["BOARD_HOSTER"];
	   $CATEGORY=$ROW["CATEGORY"];
	   $NEED_CHECK=$ROW["NEED_CHECK"];
	   if($CATEGORY!="")
		  $TYPE_ARRAY=explode(",",$CATEGORY);
	   $BOARD_NAME=str_replace("<","&lt",$BOARD_NAME);
	   $BOARD_NAME=str_replace(">","&gt",$BOARD_NAME);
//	   $BOARD_NAME=stripslashes($BOARD_NAME);
	}
	else
    {
        //----------讨论区权限控制---------
        exit;
    }
 
//----------讨论区权限控制---------
//if(!($DEPT_ID=="ALL_DEPT" || find_id($DEPT_ID,$_SESSION["LOGIN_DEPT_ID"]) || find_id($PRIV_ID,$_SESSION["LOGIN_USER_PRIV"]) || find_id($USER_ID1,$_SESSION["LOGIN_USER_ID"]) || find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"])))
//	 exit;
}
else
    $ANONYMITY_YN=1;//全局公告
//------- 文章信息 -------
if($COMMENT_ID!="")
{
   //------- 编辑或回复 -------
   $query = "SELECT * from BBS_COMMENT where COMMENT_ID='$COMMENT_ID'";
   $cursor = exequery(TD::conn(),$query,$QUERY_MASTER);
   if($ROW=mysql_fetch_array($cursor))
   {
      $COMMENT_ID = $ROW["COMMENT_ID"];
      $BOARD_ID = $ROW["BOARD_ID"];
      $AUTHOR_NAME = $ROW["AUTHOR_NAME"];
      $SUBJECT = $ROW["SUBJECT"];
      $SUBJECT=td_htmlspecialchars($SUBJECT);
      $USER_ID2 = $ROW["USER_ID"];
      $CONTENT = $ROW["CONTENT"];
      $ATTACHMENT_ID = $ROW["ATTACHMENT_ID"];
      $ATTACHMENT_NAME = $ROW["ATTACHMENT_NAME"];
      $SIGNED_YN = $ROW["SIGNED_YN"];
      $CONTENT=str_replace("\"","'",$CONTENT);
      $COMMENT_TYPE=$ROW["TYPE"];

   }

   //------- 回复 -------
   if($REPLY==1)
   {
      $SUBJECT = "Re:".$SUBJECT;
      $CONTENT = "";
   }
   
   if($REPLY!=1 && $USER_ID2!=$_SESSION["LOGIN_USER_ID"] && !find_id($BOARD_HOSTER,$_SESSION["LOGIN_USER_ID"]))
   {
	    message(_("提示"),_("无效操作"));
	    exit;
    }   
}

?>

<script>
<?
if($AUTHOR_NAME==$USER_NAME || $ANONYMITY_YN=="0")
   $NAME_SELECT=1;
else
{   
	 $NAME_SELECT=2;
}
?>

NAME_SELECT=<?=$NAME_SELECT?>;

function set_name(name)
{
  NAME_SELECT=name;
}
</script>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="title_list"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/bbs.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><a href="index.php"><?=_("讨论区")?></a> &raquo; <a href="board.php?BOARD_ID=<?=$_GET['BOARD_ID']?>"><?=$BOARD_NAME?></a> &raquo; <?=$TITLE?>
    </td>
  </tr>
</table>

<form name="form1" enctype="multipart/form-data" action="<? if($COMMENT_ID!="" && $REPLY=="") echo "update.php"; else echo "insert.php";?>" method="post" onSubmit="return CheckForm();">
<table class="TableBlock" width="100%" align="center">
<?
if($COMMENT_ID!="")
{
   $query1 = "SELECT * from BBS_COMMENT where COMMENT_ID='$COMMENT_ID' and BOARD_ID='$BOARD_ID'";
   $cursor1 = exequery(TD::conn(),$query1,$QUERY_MASTER);
   $COUNT = mysql_num_rows($cursor1);
   if($ROW=mysql_fetch_array($cursor1))
   {
      $CONTENT1 = $ROW["CONTENT"];
      $SUBJECT1 = $ROW["SUBJECT"];
	  $TYPE = $ROW["TYPE"];
   }

?>

    <tr>
      <td valign="top" class="TableData"><?=_("原帖：")?></td>	
    	<td width="906" class="TableData"><b><?=_("标题：")?><?=$SUBJECT1?></b><br><?=_("内容：")?><?=$CONTENT1?><br></td>
    </tr>
<?
}
?>
     <tr>
      <td nowrap class="TableData" width="78"><? if($REPLY == 1) echo _("回帖标题："); else echo _("标题：");?></td>
      <td class="TableData">
        <input type="text" name="SUBJECT" size="50" maxlength="90" class="BigInput" value="<?=$SUBJECT?>" >
      </td>
     </tr>
     <?
	 if($BOARD_ID!="-1")
	 {
	 ?>
     <tr> 
      <td nowrap class="TableData" width="78"><?=_("分类：")?></td>
      <td class="TableData">
        <select name="TYPE" class="BigSelect">
        	<?
        	if(count($TYPE_ARRAY)>0)
        	{
						foreach($TYPE_ARRAY as $ONETYPE)
						{
							if($ONETYPE!="")
			    		{
					?>
								<option value="<?=$ONETYPE?>" <? if($TYPE==$ONETYPE) echo "selected" ?>><?=$ONETYPE?></option>
					<?
							}
						}
					}
					else
					{
			?>
					<option value="<?=_("无分类")?>"><?=_("无分类")?></option>
					<?
					}
					?>
        </select>
      </td>
    </tr>
    <? 
	 }
	?>
    <tr>
      <td valign="top" nowrap class="TableData"><? if($REPLY == 1) echo _("回帖内容："); else echo _("内容：");?></td>
      <td class="TableData">
<?
$editor = new Editor('CONTENT');
$editor->Height = '299';
$editor->Config = array("contentsCss" => "body{font-size:".$FONT_SIZE."pt;}","model_type" => "06");
$editor->Value = $CONTENT ;
$editor->Create() ;
?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("附件文档：")?></td>
      <td class="TableData">
<?
if($ATTACHMENT_ID=="" || $REPLY==1)
   echo _("无附件");
else
   echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,1,1);
?>
      </td>
    </tr>
    <tr height="25">
      <td nowrap class="TableData"><?=_("附件选择：")?></td>
      <td class="TableData">
         <script>ShowAddFile();ShowAddImage();</script>
		 <script>$("ATTACHMENT_upload_div").innerHTML='<a href="javascript:upload_attach2();"><?=_("上传附件")?></a>'</script>&nbsp;
      </td>
	   
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("署名：")?></td>
      <td class="TableData">
        <input type="radio" name="AUTHOR_NAME" value="1" <? if(($AUTHOR_NAME==$USER_NAME || $ANONYMITY_YN=="0"))echo "checked";?> onClick="set_name(1)">
        <input type="text"  name="USER_NAME" size="10" maxlength="25" class="BigStatic" value="<?=$USER_NAME?>" readonly>
<?
if($ANONYMITY_YN=="1")
{
	 if($NICK_NAME=="")
      $NICK_NAME=$USER_NAME;
?>
        <input type="radio" name="AUTHOR_NAME" value="2" <? if($AUTHOR_NAME!=$USER_NAME)echo "checked";?> onClick="set_name(2)"><?=_("昵称")?>
        <input type="text" name="NICK_NAME" size="10" maxlength="25" class="BigInput" value="<?=$NICK_NAME?>">
<?
}
?>
      </td>
    </tr>
<?
if(trim($BBS_SIGNATURE)!="")
{
?>    
    <tr>
      <td nowrap class="TableData"><?=_("签名档：")?></td>
      <td class="TableData">
        <input type="checkbox" name="SIGNED_YN" id="SIGNED_YN" <?if($SIGNED_YN==1 || $COMMENT_ID=="" ||$REPLY==1) echo "checked";?>><label for="SIGNED_YN"><?=_("附加签名档")?></label>
      </td>
    </tr>
<?
}

/*if($COMMENT_ID!="" && $REPLY!="")//回复
{
?>
    <tr>
      <td nowrap class="TableData"><?=_("提醒发帖人：")?></td>
      <td class="TableData">
<?=sms_remind(18);?>
    </td>
    </tr>
<?
}
*/
if($BOARD_ID!="-1")
{
	 if($REPLY=="") $SEND_TITLE_FLAG=1;//编辑
	 $SMS_SELECT_REMIND = sms_select_remind(18);
    $SMS2_SELECT_REMIND = sms2_select_remind(18);
?>
    <tr>
      <td nowrap class="TableData"><?=_("事务提醒")?><br><?=_("讨论区人员：")?></td>
      <td class="TableData">
<? 
if($COMMENT_ID!="" && $REPLY=="1")
{
?>     	
        <input type="radio" name="SMS_SELECT_REMIND" id="SMS_SELECT_REMIND2" value="2" onClick="document.getElementById('SMS_SELECT_REMIND_SPAN').style.display='none';"><label for="SMS_SELECT_REMIND2"><?=_("提醒本帖人员")?></label>      	
<?
}
?>
      	<?=$SMS_SELECT_REMIND?>
      </td>
    </tr>
    <?
   if($SMS2_SELECT_REMIND!="")
   {
  ?>
      <tr>
        <td nowrap class="TableData"><?=_("手机短信提醒")?><br><?=_("讨论区人员：")?></td>
        <td class="TableData">
<? 
if($COMMENT_ID!="" && $REPLY=="1")
{
?>          	
          <input type="radio" name="SMS2_SELECT_REMIND" id="SMS2_SELECT_REMIND2" value="2" onClick="document.getElementById('SMS2_SELECT_REMIND_SPAN').style.display='none';"><label for="SMS2_SELECT_REMIND2"><?=_("提醒本帖人员")?></label>      	        	
<?
}
?>
        	<?=$SMS2_SELECT_REMIND?>
        </td>
      </tr>
  <?
   }
}
?>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
      <input type="hidden" value="<?=$SEND_TITLE_FLAG?>" name="SEND_TITLE_FLAG">
      <input type="hidden" value="<?=$ANONYMITY_YN?>" name="ANONYMITY_YN">
      <input type="hidden" value="<?=$_GET['BOARD_ID']?>" name="BOARD_ID">
      <input type="hidden" value="<?=$BOARD_ID_OLD?>" name="BOARD_ID_OLD">
      <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
      <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
    	<input type="hidden" value="<?=$COMMENT_ID?>" name="COMMENT_ID" >
    	<input type="hidden" value="<?=$REPLY?>" name="REPLY"  >
    	<input type="hidden" value="<?=$PAGE_START?>" name="PAGE_START" id="PAGE_START" >
    	<input type="hidden" name="OP" id="OP" value="">
    	<input type="hidden" name="NEED_CHECK" value="<?=$NEED_CHECK?>">
    	<input type="hidden" name="BOARD_HOSTER" value="<?=$BOARD_HOSTER?>">
      <input type="submit" value="<?=_("保存")?>" class="BigButton">&nbsp;&nbsp;
      <?
	  if($BTN_CLOSE==1)
	  {
	  ?>
      <input type="button" class="BigButton" value="<?=_("关闭")?>" onClick="window.close();">
      <?
	  }
	  else
	  {
	  ?>              
      <input type="button" class="BigButton" value="<?=_("返回")?>" onClick="history.go(-1);">
      <?
	  }
	  ?>
      </td>
    </tr>
</table>
</form>

</body>
</html>
