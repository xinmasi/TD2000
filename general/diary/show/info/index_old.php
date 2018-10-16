<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("工作日志查询");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/layout_left.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>

<script>
jQuery.noConflict();
(function($){
   $(window).resize(function(){
      $('#user_diary').height(Math.max($(window).height(), $(user_diary.document.body).attr('scrollHeight')+10));
   });
   
   $(document).ready(function(){
      $('a[index]').click(function(){
         $(this).toggleClass('header-active');
         $('#container_'+$(this).attr('index')).toggle();
      });
   });
})(jQuery);
</script>
<body class="bodycolor">
<table width="100%">
   <tr>
      <td id="left">
         <div id="left_top" class="PageHeader diary_icon"></div>
         <table class="BlockTop">
            <tr>
               <td class="left"></td>
               <td class="center">
                  <a href="javascript:;" index="1" class="header header-active" target="user_diary"><?=_("在职人员")?></a>
               </td>
               <td class="right"></td>
            </tr>
         </table>
         <div class="container treeList" id="container_1">
<?
$PARA_URL1 = "";
$PARA_URL2 = "/general/diary/show/info/user_diary.php";
$PARA_TARGET = "user_diary";
$PRIV_NO_FLAG = "2";
$MANAGE_FLAG = "1";
$MODULE_ID = "4";
$xname = "diary_info";
$showButton = "0";
include_once("inc/user_list/index.php");
?>
         </div>
<?
$query = "SELECT POST_PRIV from USER where UID='".$_SESSION["LOGIN_UID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $POST_PRIV=$ROW["POST_PRIV"];
    
if($POST_PRIV=="1")
{
	
   $query = "SELECT * from USER_PRIV where USER_PRIV='".$_SESSION["LOGIN_USER_PRIV"]."'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $PRIV_NO=$ROW["PRIV_NO"];
?>
         <div class="head"><a href="javascript:;" index="2" class="header" target="user_diary"><?=_("离职人员/外部人员")?></a></div>
         <div class="container" id="container_2" style="display:none;">
            <table class="TableList" width="100%" align="center">
<?
   if($_SESSION["LOGIN_USER_PRIV"]!="1")
      $query = "SELECT * from USER,USER_PRIV where DEPT_ID=0 and USER.USER_PRIV=USER_PRIV.USER_PRIV and USER_PRIV.PRIV_NO>'$PRIV_NO' and USER_PRIV.USER_PRIV!=1 order by PRIV_NO,USER_NO,USER_NAME";
   else
      $query = "SELECT * from USER,USER_PRIV where DEPT_ID=0 and USER.USER_PRIV=USER_PRIV.USER_PRIV order by PRIV_NO,USER_NO,USER_NAME";

   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
      $USER_COUNT++;
      $USER_ID=$ROW["USER_ID"];
      $USER_NAME=$ROW["USER_NAME"];
      $USER_PRIV=$ROW["USER_PRIV"];

      $query1 = "SELECT * from USER_PRIV where USER_PRIV='$USER_PRIV'";
      $cursor1= exequery(TD::conn(),$query1);
      if($ROW=mysql_fetch_array($cursor1))
         $USER_PRIV=$ROW["PRIV_NAME"];
?>
   <tr class="TableData" align="center">
     <td nowrap width="80"><?=$USER_PRIV?></td>
     <td nowrap><a href="/general/diary/show/info/user_diary.php?USER_ID=<?=$USER_ID?>&DEPT_ID=0" target="user_diary"><?=$USER_NAME?></a></td>
   </tr>
<?
   }
   echo '</table></div>';
}
?>
         <div class="head"><a href="user_query.php" class="header" target="user_diary"><?=_("员工日志查询")?></a></div>
         <table class="BlockBottom">
            <tr>
               <td class="left"></td>
               <td class="center">
                  <a href="diary_body.php" class="header" target="user_diary"><?=_("最新10篇员工日志")?></a>
               </td>
               <td class="right"></td>
            </tr>
         </table>
      </td>
      <td id="right">
         <iframe id="user_diary" name="user_diary" src="blank.php" onload="jQuery(window).triggerHandler('resize');" border="0" frameborder="0" framespacing="0" marginheight="0" marginwidth="0" style="width:100%;height:100%;"></iframe>
      </td>
   </tr>
</table>
</body>
</html>