<?
//URL:webroot\general\news\show\relay.php
include_once("inc/auth.inc.php");
if($start=="")
   $start=0;

$HTML_PAGE_TITLE = _("回复评论");
include_once("inc/header.inc.php");
?>




<script>
function CheckForm()
{
   if(document.form1.CONTENT.value=="")
   { alert("<?=_("评论的内容不能为空！")?>");
     return (false);
   }
   
   if(document.getElementsByName("AUTHOR_NAME").length >1 && document.getElementsByName("AUTHOR_NAME").item(1).checked && document.form1.NICK_NAME.value=="")
   { alert("<?=_("昵称不能为空！")?>");
     return (false);
   }
   return (true);
}
</script>
<body class="bodycolor">
<?
 $query = "SELECT SUBJECT,ANONYMITY_YN from NEWS where NEWS_ID='$NEWS_ID'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
    $SUBJECT=$ROW["SUBJECT"];
    $ANONYMITY_YN=$ROW["ANONYMITY_YN"];
 }
 
 //$query = "SELECT NICK_NAME from USER where UID='".$_SESSION["LOGIN_UID"]."'";
 $query = "SELECT NICK_NAME from USER_EXT where UID='".$_SESSION["LOGIN_UID"]."'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
    $NICK_NAME=$ROW["NICK_NAME"];
 }
?>
<form action="relay_submit.php"  method="post" name="form1" onSubmit="return CheckForm();">
  <table class="TableBlock" width="95%" align="center">
     <tr>
      <td class="TableHeader" colspan="2">
        <img src="<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif"> <?=_("回复评论：")?>
      </td>
    </tr>
    <tr>
      <td align="center" class="TableData"><?=_("内容：")?></td>
      <td class="TableData">
        <textarea cols="57" name="CONTENT" rows="5" class="BigInput" wrap="on"></textarea>
      </td>
    </tr>
    <tr>
      <td align="center" class="TableData"><?=_("署名：")?></td>
      <td class="TableData">
        <input type="radio" name="AUTHOR_NAME" value="USER_ID" <? if($ANONYMITY_YN=="0")echo "checked";?>>
        <input type="text"  name="USER_NAME" size="10" maxlength="25" class="BigStatic" value="<?=$_SESSION["LOGIN_USER_NAME"]?>" readonly>
<?
        if($ANONYMITY_YN=="1")
        {
?>
        <input type="radio" name="AUTHOR_NAME" value="NICK_NAME" checked><?=_("昵称")?>
        <input type="text" name="NICK_NAME" size="10" maxlength="25" class="BigInput" value="<?=$NICK_NAME?>">
<?
        }
?>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td nowrap colspan="2">
        <input type="hidden" value="<?=$COMMENT_ID?>" name="PARENT_ID">
        <input type="hidden" value="<?=$NEWS_ID?>" name="NEWS_ID">
        <input type="hidden" value="<?=$MANAGE?>" name="MANAGE">
        <input type="submit" value="<?=_("回复")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='re_news.php?NEWS_ID=<?=$NEWS_ID?>&start=<?=$start?>'">
      </td>
    </tr>
  </table>
</form>