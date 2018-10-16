<?
   $SESSION_WRITE_CLOSE = 0;
   include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
   include_once("inc/utility_cache.php");
   ob_clean();

   $THEME_ARRAY = array();
   $THEME_ARRAY = array_filter(explode(",",MYOA_FASHION_THEME));

   if(!isset($themeid) || !in_array($themeid,$THEME_ARRAY)){
       echo "err#"._("参数不正确");
       exit;
   }
   
   $sql= "update USER set THEME = '$themeid' where UID ='".$_SESSION["LOGIN_UID"]."';";
   $cursor= exequery(TD::conn(),$sql);
   
   if(mysql_affected_rows()>=0){
      
      updateUserCache($_SESSION["LOGIN_UID"]);
      $_SESSION["LOGIN_THEME"] = $themeid;
      
      echo "+ok";
      exit;
   }else{
      echo "err#"._("更新失败");
      exit;
   }

?>