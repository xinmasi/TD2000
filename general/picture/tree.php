<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

while (list($key, $value) = each($_GET))
   $$key=$value;
while (list($key, $value) = each($_POST))
   $$key=$value;
//======================================================
function ListTree($CUR_DIR,$PIC_ID)
{
   $SORT_COUNT=0;
   
   $dh = @opendir(iconv2os($CUR_DIR));
   if($dh === FALSE)
      return array();
   
   while (false !== ($FILE_NAME = readdir($dh)))
   {
   	  $FILE_NAME = iconv2oa($FILE_NAME);
   	  $FILE_PATH = $CUR_DIR."/".$FILE_NAME;
   	  if(is_file(iconv2os($FILE_PATH)) || $FILE_NAME=='.' || $FILE_NAME=='..' || $FILE_NAME=='tdoa_cache')
   	     continue;
   	  
   	  $SUB_COUNT=0;
   	  $dh1 = @opendir(iconv2os($FILE_PATH));
   	  if($dh1 === FALSE)
   	      continue;
   	  
      while (false !== ($FILE_NAME1 = readdir($dh1)))
      {
      	 $FILE_NAME1 = iconv2oa($FILE_NAME1);
         $FILE_PATH1 = $FILE_PATH."/".$FILE_NAME1;
         if(is_dir(iconv2os($FILE_PATH1)) && $FILE_NAME1!='.' && $FILE_NAME1!='..' && $FILE_NAME1!='tdoa_cache')
         {
   	        $SUB_COUNT++;
   	        break;
   	   	 }
      }
      
   	  $URL = $CUR_DIR.$FILE_NAME.'/';

      $SORT_ARRAY[$SORT_COUNT]["ID"]=dechex(crc32($PIC_ID.realpath($FILE_PATH)));
      $SORT_ARRAY[$SORT_COUNT]["NAME"]=$FILE_NAME;
      $SORT_ARRAY[$SORT_COUNT]["URL"]=$CUR_DIR.'/'.$FILE_NAME;
      $SORT_ARRAY[$SORT_COUNT]["SUB_COUNT"]=$SUB_COUNT;
      $SORT_COUNT++;
   }
   if($SORT_COUNT!=0)
   {
       foreach($SORT_ARRAY as $RES)
         $SORTAUX[]= strtolower($RES["NAME"]);
       $SORT_ASC=4;
       array_multisort($SORTAUX,$SORT_ASC,$SORT_ARRAY);
   }

   $FOLDER_ARRAY = array();
   for($I=0; $I< $SORT_COUNT; $I++)
   {
      $IS_LAZY = false;
      $JSON = "";
      $URL = "picture_view.php?SUB_DIR=".urlencode($SORT_ARRAY[$I]["NAME"])."&PIC_ID=".$PIC_ID."&CUR_DIR=".urlencode($SORT_ARRAY[$I]["URL"]);
      if($SORT_ARRAY[$I]["SUB_COUNT"] > 0)
      {
         $IS_LAZY = true;
         $JSON = "tree.php?PIC_ID=".$PIC_ID."&CUR_DIR=".urlencode($SORT_ARRAY[$I]["URL"]);
      }
      
      $FOLDER_ARRAY[] = array(
         "title" => td_iconv($SORT_ARRAY[$I]["NAME"], MYOA_CHARSET, 'utf-8'),
         "isFolder" => true,
         "isLazy" => $IS_LAZY,
         "key" => "folder_".$SORT_ARRAY[$I]["ID"],
         "icon" => 'folder.gif',
         "url" => td_iconv($URL, MYOA_CHARSET, 'utf-8'),
         "tooltip" => td_iconv($SORT_ARRAY[$I]["NAME"], MYOA_CHARSET, 'utf-8'),
         "json" => td_iconv($JSON, MYOA_CHARSET, 'utf-8'),
         "target" => 'list'
      );
   }

   return $FOLDER_ARRAY;
}

//----------------------------------------------------------
$FOLDER_ARRAY = array();
if(intval($PIC_ID)==0)
{
   $query = "SELECT * from PICTURE where find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_DEPT_ID) OR TO_DEPT_ID='ALL_DEPT' or  find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',TO_PRIV_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TO_USER_ID) order by PIC_ID,PIC_NAME desc";
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
      $PIC_ID=$ROW["PIC_ID"];
      $PIC_NAME=$ROW["PIC_NAME"];
      $PIC_PATH=$ROW["PIC_PATH"];

      $URL = "picture_view.php?&PIC_ID=".$PIC_ID;
      $JSON = "tree.php?PIC_ID=".$PIC_ID."&CUR_DIR=".urlencode($PIC_PATH);
      $FOLDER_ARRAY[] = array(
         "title" => td_iconv($PIC_NAME, MYOA_CHARSET, 'utf-8'),
         "isFolder" => true,
         "isLazy" => true,
         "key" => "folder_".dechex(crc32($PIC_ID.realpath($PIC_PATH))),
         "icon" => 'folder.gif',
         "url" => td_iconv($URL, MYOA_CHARSET, 'utf-8'),
         "tooltip" => td_iconv($PIC_NAME, MYOA_CHARSET, 'utf-8'),
         "json" => td_iconv($JSON, MYOA_CHARSET, 'utf-8'),
         "target" => 'list'
      );
   }
}
else
{
   $FOLDER_ARRAY = ListTree($CUR_DIR,$PIC_ID); 
}

ob_end_clean();
echo json_encode($FOLDER_ARRAY);
?>
