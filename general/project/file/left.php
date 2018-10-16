<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/file_folder.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/tree.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/tree.js<?=$GZIP_POSTFIX?>"></script>
<script>
jQuery.noConflict();
(function($){
   $(window).resize(function(){
      var height = $(window).height() - $('#tree_container').offset().top - $('#capacity').outerHeight();
      if(!$.browser.msie)
         height -= $('#tree_container').outerHeight() - $('#tree_container').height();
      $('#tree_container').height(height - 10);
      $('#file_main').height($(window).height());
      $('#center').css('background-position-y', $(window).height()/2);
   });
   
   $(document).ready(function(){
      $('#center').click(function(){
         if($('#left:visible').length > 0)
            $(this).attr('class', 'scroll-right');
         else
            $(this).attr('class', 'scroll-left');
         $('#left').toggle();
      });
      
      $('#center').hover(
         function(){
            var className = $(this).attr('class');
            if(className.indexOf('-active') < 0)
               $(this).attr('class', className + '-active');
         },
         function(){
            var className = $(this).attr('class');
            if(className.indexOf('-active') > 0)
               $(this).attr('class', className.substr(0, className.length-7));
         }
      );
      
      $('#top_menu > div > a').click(function(){
         if($('#xtree'+$(this).attr('index')+':visible').length > 0)
            return;
         
         $('#top_menu > div > a').toggleClass('active');
         $('#xtree1').toggle();
         $('#xtree1_root').toggle();
         $('#xtree2').toggle();
         
         if($('#xtree2:visible').length > 0 && $('#xtree2').html() == '')
            xtree2.BuildTree();
      });
      
      $('#tree_size').click(function(){
         var obj = this;
         $(obj).html('<img src="<?=MYOA_STATIC_SERVER?>/static/images/loading_16.gif" align="absMiddle"> <?=_("正在计算")?>...');
         $.ajax({
            type: 'GET',
            url: 'tree_size.php',
            dataType: 'text',
            success: function(data){
               $(obj).html(data);
            },
            error: function (request, textStatus, errorThrown){
               $(obj).html('<?=_("错误：")?>' + request.status);
            }
         });
      });
   });
})(jQuery);
</script>

<body class="bodycolor" scroll="no" style="overflow:hidden">
<?
$query = "select PROJ_ID,PROJ_MANAGER,PROJ_OWNER,PROJ_VIEWER,PROJ_USER FROM PROJ_PROJECT WHERE PROJ_STATUS='$PROJ_STATUS' and (find_in_set('".$_SESSION["LOGIN_USER_ID"]."',REPLACE(PROJ_USER,'|','')) || PROJ_MANAGER='".$_SESSION["LOGIN_USER_ID"]."' || PROJ_OWNER='".$_SESSION["LOGIN_USER_ID"]."' || find_in_set('".$_SESSION["LOGIN_USER_ID"]."',PROJ_VIEWER))";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
	 $PROJ_ID=$ROW["PROJ_ID"];
	 $PROJ_MANAGER=$ROW["PROJ_MANAGER"];
	 $PROJ_OWNER=$ROW["PROJ_OWNER"];
	 $PROJ_VIEWER=$ROW["PROJ_VIEWER"];
	 $PROJ_USER=$ROW["PROJ_USER"];
	 
	 if($PROJ_OWNER==$_SESSION["LOGIN_USER_ID"] || $PROJ_MANAGER==$_SESSION["LOGIN_USER_ID"] || find_id($PROJ_VIEWER,$_SESSION["LOGIN_USER_ID"]))
	 	 	$FLAG=1;	 
	 else
	 {
	 	$PROJ_USER=str_replace("|","",$PROJ_USER);
	 	if(!find_id($PROJ_USER,$_SESSION["LOGIN_USER_ID"])) 
	 	   continue;
  	    $query1 = "select 1 from PROJ_FILE_SORT WHERE PROJ_ID='$PROJ_ID' and (find_in_set('".$_SESSION["LOGIN_USER_ID"]."',VIEW_USER) || find_in_set('".$_SESSION["LOGIN_USER_ID"]."',MANAGE_USER) || find_in_set('".$_SESSION["LOGIN_USER_ID"]."',NEW_USER) || find_in_set('".$_SESSION["LOGIN_USER_ID"]."',MODIFY_USER) )";
  	    $cursor1= exequery(TD::conn(),$query1);
  	    while(mysql_fetch_array($cursor1))
  	    {
  	 	 	$FLAG=1;
  	 	 	break;
  	    }
	 }
} 
?>

<table width="100%">
   <tr>
      <td id="left">
         <div style="margin:5px"><span class="Big3"><image src="<?=MYOA_STATIC_SERVER?>/static/images/menu/project.png" align="absmiddle"><?=_("项目列表")?></span></div>
         <table class="BlockTop2"><tr><td class="left"></td><td class="center">&nbsp;</td><td class="right"></td></tr></table>
         <div id="tree_container">
<?
if($FLAG==1)
{
    
    if(!isset($xtree_id) || $xtree_id=="")
       $xtree_id="project_file_tree";
       
    if(!isset($xtree) || $xtree=="")
       $xtree="proj_tree.php?PROJ_STATUS=".$PROJ_STATUS;
?>
    
    <div id="<?=$xtree_id?>"></div>
    <link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/images/org/ui.dynatree.css<?=$GZIP_POSTFIX?>">
    <script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/tree.js<?=$GZIP_POSTFIX?>"></script>
    <script type="text/javascript">
       var tree = new Tree("<?=$xtree_id?>", "<?=$xtree?>", '<?=MYOA_STATIC_SERVER?>/static/images/',false, 3);
       tree.BuildTree();
    </script>

<?
}
else
{
	if($PROJ_STATUS==2) 
	    $MSG=_("无进行中项目！");
	elseif($PROJ_STATUS==3) 
	    $MSG=_("无已结束历史项目！");
    Message("",$MSG,'blank');
}
?>
         </div>
      </td>
      <td id="center" class="scroll-left">
      </td>
      <td id="right">
         <iframe id="file_main" name="file_main" src="blank.php" onload="jQuery(window).triggerHandler('resize');" border="0" frameborder="0" framespacing="0" marginheight="0" marginwidth="0" style="width:100%;height:100%;"></iframe>
      </td>
   </tr>
</table>
</body>
</html>