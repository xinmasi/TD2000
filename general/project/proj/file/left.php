<?
include_once("inc/auth.inc.php");
include_once("../proj_priv.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/tree.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>

<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script>
jQuery.noConflict();
(function($){
   $(window).resize(function(){
      var height = $(window).height() - $('#tree_container').offset().top ;
      if(!$.browser.msie)
         height -= $('#tree_container').outerHeight() - $('#tree_container').height();
      $('#tree_container').height(height - 10);
      $('#file_main').height($(window).height()-10);
      $('#center').css('background-position-y', $(window).height()/2);
   });
})(jQuery);
</script>
   
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/tree.js<?=$GZIP_POSTFIX?>"></script>
<body class="bodycolor">
<table width="100%">
   <tr>
      <td id="left">
      
    <div id="tree_container">
<?

$query = "select PROJ_ID,PROJ_MANAGER,PROJ_OWNER,PROJ_VIEWER,PROJ_USER FROM PROJ_PROJECT WHERE PROJ_ID='$PROJ_ID' and (find_in_set('".$_SESSION["LOGIN_USER_ID"]."',REPLACE(PROJ_USER,'|','')) || PROJ_MANAGER='".$_SESSION["LOGIN_USER_ID"]."' || PROJ_OWNER='".$_SESSION["LOGIN_USER_ID"]."' || find_in_set('".$_SESSION["LOGIN_USER_ID"]."',PROJ_VIEWER))";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
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

if($FLAG==1)
{
    if(!isset($xtree_id) || $xtree_id=="")
       $xtree_id="project_file_tree";
       
    if(!isset($xtree) || $xtree=="")
       $xtree="sort_tree.php?PROJ_ID=".$PROJ_ID;
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

?>
</div>
      </td>
      <td id="right">
         <iframe id="file_main" name="file_main" src="<?=$MENU_LEFT_CONFIGS['href']?>"  onload="jQuery(window).triggerHandler('resize');" border="0" frameborder="0" framespacing="0" marginheight="0" marginwidth="0" style="width:100%;height:100%;"></iframe>
      </td>
</body>
</html>