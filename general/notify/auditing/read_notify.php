<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_cache.php");

$HTML_PAGE_TITLE = _("查看公告通知");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script Language=JavaScript>
function fw_notify(NOTIFY_ID)
{
 if(window.confirm("<?=_("确认要转发该公告通知吗？")?>"))
    parent.location="../manage/fw.php?NOTIFY_ID="+NOTIFY_ID;
}
function au_notify(NOTIFY_ID,AUTYPE)
{
 if(AUTYPE==1)
    parent.location="pass.php?FROM=1&NOTIFY_ID="+NOTIFY_ID;
 else
 	  parent.location="unpass.php?FROM=1&NOTIFY_ID="+NOTIFY_ID;
     
 
}
</script>

<body class="bodycolor">
<?
$FONT_SIZE = get_font_size("FONT_NOTIFY", 12);
$PARA_ARRAY=get_sys_para("NOTIFY_AUDITING_EDIT");
$NOTIFY_AUDITING_EDIT=$PARA_ARRAY["NOTIFY_AUDITING_EDIT"];
$CUR_DATE=date("Y-m-d",time());
$query = "SELECT * from NOTIFY where NOTIFY_ID='$NOTIFY_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $READERS=$ROW["READERS"];
   $PUBLISH=$ROW["PUBLISH"];
   $FORMAT=$ROW["FORMAT"];
   /*$END_DATE=$ROW["END_DATE"];
   $END_DATE=strtok($END_DATE," ");
   if($END_DATE=="0000-00-00")
      $END_DATE=""; */ 
   
   if ($FORMAT=="2")
   {
   	 
      $query1 = "SELECT CONTENT from NOTIFY where NOTIFY_ID='$NOTIFY_ID'";
      $cursor1= exequery(TD::conn(),$query1);
      if($ROW1=mysql_fetch_array($cursor1))
         $CONTENT=$ROW1["CONTENT"];
      if(strpos($CONTENT, '/file_folder/read.php?')>0)
   	{
         $CONTENT.="&BTN_CLOSE=1";
   	}  
         
      /*
      if(!find_id($READERS,$_SESSION["LOGIN_USER_ID"]))
      {
         $READERS.=$_SESSION["LOGIN_USER_ID"].",";
         $query = "update NOTIFY set READERS='$READERS' where NOTIFY_ID='$NOTIFY_ID'";
         exequery(TD::conn(),$query);
      }
      */
      Header("location: $CONTENT");
   }
   $IMG_TYPE_STR="gif,jpg,png,tiff,bmp,iff,jp2,jpx,jb2,jpc,xbm,wbmp,";

    $FROM_DEPT=$ROW["FROM_DEPT"];
    $FROM_ID=$ROW["FROM_ID"];
    $TO_ID=$ROW["TO_ID"];
    $PRIV_ID=$ROW["PRIV_ID"];
    $USER_ID=$ROW["USER_ID"];
    $SUBJECT=$ROW["SUBJECT"];
    $READERS=$ROW["READERS"];
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
    $PRINT=$ROW["PRINT"];
    $FORMAT=$ROW["FORMAT"];
    $TYPE_ID=$ROW["TYPE_ID"];    
    $SUBJECT_COLOR=$ROW["SUBJECT_COLOR"];
    $TR_HEIGHT=300;
    if($FORMAT=="1")
       $TR_HEIGHT=550;

    $SUBJECT=td_htmlspecialchars($SUBJECT);
    if($SUBJECT_COLOR!="")    
       $SUBJECT="<font color='".$SUBJECT_COLOR."'>".$SUBJECT."</font>";

    $CONTENT=$ROW["CONTENT"];
    $COMPRESS_CONTENT=@gzuncompress($ROW["COMPRESS_CONTENT"]);
    if($COMPRESS_CONTENT!=""&&$FORMAT!="2")
       $CONTENT=$COMPRESS_CONTENT;
    else
       $CONTENT=$ROW["CONTENT"];
    $CONTENT = preg_replace("/<\!--.*?-->/si","",$CONTENT);       
    if($CONTENT=="")
       $CONTENT="<br>"._("见附件");

    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $BEGIN_DATE=date("Y-m-d",$BEGIN_DATE);

    $TYPE_NAME=get_code_name($TYPE_ID,"NOTIFY");
    if($TYPE_NAME!="")
        $SUBJECT="<font color='".$SUBJECT_COLOR."'>[".$TYPE_NAME."]</font>".$SUBJECT;
    $query1 = "SELECT DEPT_NAME from DEPARTMENT where DEPT_ID='$FROM_DEPT'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
    {
       $FROM_DEPT_NAME=$ROW["DEPT_NAME"];
       $FROM_DEPT_LONG_NAME=dept_long_name($FROM_DEPT);
    }
 
    $FROM_UID=UserId2Uid($FROM_ID);
    if($FROM_UID!="")
    {
      $ROW=GetUserInfoByUID($FROM_UID,"USER_NAME,DEPT_ID");
      $FROM_NAME=$ROW["USER_NAME"];
      $DEPT_ID=$ROW["DEPT_ID"];
      $DEPT_NAME=dept_long_name($DEPT_ID);
    }
    else
    {
        $query1 = "SELECT USER_NAME,DEPT_ID from USER where USER_ID='$FROM_ID'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
        {
           $FROM_NAME=$ROW["USER_NAME"];
           $DEPT_ID=$ROW["DEPT_ID"];
           $DEPT_NAME=dept_long_name($DEPT_ID);
        }
        else
        {
           $FROM_NAME=$FROM_ID;
           $DEPT_NAME=_("用户已删除");
        }
    }
}
else
{
   Message(_("提示"),_("该公告已删除。"));	
   exit;   
} 
?>

  <table class="TableBlock no-top-border" width="100%" align="center">
    <tr>
    <td  width="100%" style="padding:0px"> 
     <table class="TableTop" width="100%" cellpadding="0" >
      <tr>        
        <td class="center" width="100%" <?if($SUBJECT_COLOR!="" && $SUBJECT_COLOR!="#000000"){?> style="background:url('<?=MYOA_STATIC_SERVER?>/static/images/table_top_bg_blue.jpg')  repeat-x;" <?}?>><?=$SUBJECT?></td>       
     </tr>
    </table>
 </td>
    </tr>
    <tr>
      <td class="TableContent" align="right">
      <?=_("发布部门：")?><u title="<?=_("部门：")?><?=$FROM_DEPT_LONG_NAME?>" style="cursor:hand"><?=$FROM_DEPT_NAME?></u>&nbsp;&nbsp;
      <?=_("发布人：")?><u title="<?=_("部门：")?><?=$DEPT_NAME?>" style="cursor:hand"><?=$FROM_NAME?></u>&nbsp;&nbsp;
      <?=_("发布于：")?><i><?=$BEGIN_DATE?></i>
      </td>
    </tr>
    <tr>
      <td class="TableData Content" colspan="2"  valign="top"  style="height:<?=$TR_HEIGHT?>;font-size:<?=$FONT_SIZE?>pt;line-height:normal;background:#ffffff;COLOR:#000000;" class="content">
<?
$ATTACHMENT_ID_ARRAY=explode(",",$ATTACHMENT_ID);
$ATTACHMENT_NAME_ARRAY=explode("*",$ATTACHMENT_NAME);
$ARRAY_COUNT=sizeof($ATTACHMENT_ID_ARRAY);

if($FORMAT!="1")
   echo $CONTENT;
else
{
   for($I=0;$I<$ARRAY_COUNT;$I++)
   {
      $EXT_NAME=strtolower(substr($ATTACHMENT_NAME_ARRAY[$I],-4));
      if($EXT_NAME==".mht"||$EXT_NAME==".htm"||$EXT_NAME==".html")
      {
         $MODULE=attach_sub_dir();
         $ATTACHMENT_ID1=$ATTACHMENT_ID_ARRAY[$I];
         $YM=substr($ATTACHMENT_ID1,0,strpos($ATTACHMENT_ID1,"_"));
         if($YM)
            $ATTACHMENT_ID1=substr($ATTACHMENT_ID1,strpos($ATTACHMENT_ID,"_")+1);
         $ATTACHMENT_ID_ENCODED=attach_id_encode($ATTACHMENT_ID1,$ATTACHMENT_NAME_ARRAY[$I]);
?>
      <iframe id=mhtFrame src="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>&DIRECT_VIEW=1"  scrolling="auto" width="100%" height="800"></iframe>
<?
         break;
      }
   }
}
?>
      </td>
    </tr>
<?
$ATTACH_ARRAY = trim_inserted_image($CONTENT, $ATTACHMENT_ID, $ATTACHMENT_NAME);
if($ATTACH_ARRAY["NAME"]!="")
{
?>
    <tr>
      <td class="TableData"><?=_("附件文件")?>:<br><?=attach_link($ATTACH_ARRAY["ID"],$ATTACH_ARRAY["NAME"],1,1,$PRINT,0,0,1,1,'',false,$FORMAT)?></td>
    </tr>
<?
}

if($ATTACH_ARRAY["IMAGE_COUNT"]>0)
{
?>
    <tr>
      <td class="TableData">
        <img src="<?=MYOA_STATIC_SERVER?>/static/images/image.gif" align="absmiddle" border="0">&nbsp;<?=_("附件图片")?>: <br><br>

<?
   $ATTACHMENT_ID_ARRAY=explode(",",$ATTACH_ARRAY["ID"]);
   $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACH_ARRAY["NAME"]);
   $ARRAY_COUNT=sizeof($ATTACHMENT_ID_ARRAY);
   for($I=0;$I<$ARRAY_COUNT;$I++)
   {
      if($ATTACHMENT_ID_ARRAY[$I]=="")
         continue;

      $MODULE=attach_sub_dir();
      $ATTACHMENT_ID1=$ATTACHMENT_ID_ARRAY[$I];
      $YM=substr($ATTACHMENT_ID1,0,strpos($ATTACHMENT_ID1,"_"));
      if($YM)
         $ATTACHMENT_ID1=substr($ATTACHMENT_ID1,strpos($ATTACHMENT_ID,"_")+1);
      $ATTACHMENT_ID_ENCODED=attach_id_encode($ATTACHMENT_ID1,$ATTACHMENT_NAME_ARRAY[$I]);
      
      if(is_image($ATTACHMENT_NAME_ARRAY[$I]))
      {
         $IMG_ATTR=td_getimagesize(attach_real_path($ATTACHMENT_ID_ARRAY[$I],$ATTACHMENT_NAME_ARRAY[$I]));
         if(is_array($IMG_ATTR) && $IMG_ATTR[0]>0 && $IMG_ATTR[1]>0)
            $WIDTH=floor($IMG_ATTR[0]*100/$IMG_ATTR[1]);
         else
            $WIDTH=100;
?>
                <a href="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>"><img src="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>" border="0"  width="<?=$WIDTH?>" height="100" alt="<?=_("文件名：")?><?=$ATTACHMENT_NAME_ARRAY[$I]?>"></a><?=_("　")?>
<?
      }
   }
?>
      </td>
    </tr>
<?
}
?>
    <tr align="center" class="TableControl">
      <td>
      
      <?
       if($NOTIFY_AUDITING_EDIT==1 && $PUBLISH==2)
       {
      ?>  	
            <input type="button" class="BigButton" value="<?=_("修改")?>" onClick="javaScript:window.location='../manage/modify.php?FROM=2&IS_AUDITING_EDIT=1&start=<?=$start?>&NOTIFY_ID=<?=$NOTIFY_ID?>'">
          &nbsp;
      <?
       }
       
      if($FROM_UNAUDITED==1)
      {
	  ?>
	     <input type="button" class="BigButton" value="<?=_("批准")?>" onClick="au_notify('<?=$NOTIFY_ID?>','1');">&nbsp;
	     <input type="button" class="BigButton" value="<?=_("不批准")?>" onClick="au_notify('<?=$NOTIFY_ID?>','0');">&nbsp;
	   <?
	    }
	   ?>
        <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="javascript:window.close();">
      </td>
    </tr>
  </table>

</body>
</html>