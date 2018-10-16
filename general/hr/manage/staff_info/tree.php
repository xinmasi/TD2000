<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/ip2add.php");
ob_end_clean();

$PARENT_ID=$DEPT_ID;
//$_SESSION["LOGIN_USER_ID"]="admin";
//$_SESSION["LOGIN_DEPT_ID"]="1";
//$_SESSION["LOGIN_USER_PRIV"]="1";

if($MODULE_ID!="" && ($DEPT_PRIV=="2" || $DEPT_PRIV=="3" || $DEPT_PRIV=="4" || $ROLE_PRIV=="3"))
{
   $query1 = "SELECT * from MODULE_PRIV where UID='".$_SESSION["LOGIN_UID"]."' and MODULE_ID='$MODULE_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
   {
      $DEPT_ID_STR=$ROW["DEPT_ID"];
      $PRIV_ID_STR=$ROW["PRIV_ID"];
      $USER_ID_STR=$ROW["USER_ID"];
   }
}
else if($MODULE_ID=="" && $DEPT_PRIV=="2")
{
   $query = "SELECT POST_DEPT from USER where UID='".$_SESSION["LOGIN_UID"]."'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $DEPT_ID_STR=$ROW["POST_DEPT"];
}

if($MODULE_ID=="0")
   $EXCLUDE_UID_STR=my_exclude_uid();

//--------------------------------------------
$ORG_ARRAY = array();
if($PARENT_ID==0)
{
	$query = "SELECT * from UNIT";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $UNIT_NAME=$ROW["UNIT_NAME"];

   $DEPT_ARRAY = userListTree($PARENT_ID,$MANAGE_FLAG,$PRIV_NO,$PARA_URL1,$PARA_URL2,$PARA_TARGET,$PARA_ID,$PARA_VALUE,$PWD,$SHOW_IP,$DEPT_PRIV,$ROLE_PRIV,$DEPT_ID_STR,$PRIV_ID_STR,$USER_ID_STR,$EXCLUDE_UID_STR,$MODULE_ID);
   $ORG_ARRAY = array(
      "title" => td_iconv($UNIT_NAME, MYOA_CHARSET, 'utf-8'),
      "isFolder" => true,
      "isLazy" => false,
      "expand" => true,
      "key" => "dept_0",
      "dept_id" => "0",
      "icon" => 'root.png',
      "tooltip" => td_iconv($UNIT_NAME, MYOA_CHARSET, 'utf-8'),
      "children" => $DEPT_ARRAY
   );
}
else
{
  $ORG_ARRAY = userListTree($PARENT_ID,$MANAGE_FLAG,$PRIV_NO,$PARA_URL1,$PARA_URL2,$PARA_TARGET,$PARA_ID,$PARA_VALUE,$PWD,$SHOW_IP,$DEPT_PRIV,$ROLE_PRIV,$DEPT_ID_STR,$PRIV_ID_STR,$USER_ID_STR,$EXCLUDE_UID_STR,$MODULE_ID);
}

echo json_encode($ORG_ARRAY);

//======================================================
function userListTree($PARENT_ID,$MANAGE_FLAG,$PRIV_NO,$PARA_URL1,$PARA_URL2,$PARA_TARGET,$PARA_ID,$PARA_VALUE,$PWD,$SHOW_IP,$DEPT_PRIV,$ROLE_PRIV,$DEPT_ID_STR,$PRIV_ID_STR,$USER_ID_STR,$EXCLUDE_UID_STR,$MODULE_ID)
{
   
   $ONLINE_USER_ARRAY = TD::get_cache('SYS_ONLINE_USER');
   $DEPT_PRIV_I=$DEPT_PRIV=="3" || $DEPT_PRIV=="4" || is_dept_priv($PARENT_ID, $DEPT_PRIV, $DEPT_ID_STR);
   if($DEPT_PRIV_I==1)
   {
      $query1 = "SELECT UID,HR_STAFF_INFO.USER_ID,USER_NAME,USER.SEX,LAST_VISIT_TIME,LAST_VISIT_IP,PRIV_NO from USER_PRIV,USER left join HR_STAFF_INFO on USER.USER_ID=HR_STAFF_INFO.USER_ID where USER.DEPT_ID=0 and HR_STAFF_INFO.DEPT_ID!=0 and HR_STAFF_INFO.DEPT_ID='$PARENT_ID' and USER.USER_PRIV=USER_PRIV.USER_PRIV";
      if($_SESSION["LOGIN_USER_PRIV"]!="1")
      {
         if($ROLE_PRIV=="0")
            $query1 .= " and USER_PRIV.PRIV_NO>$PRIV_NO";
         else if($ROLE_PRIV=="1")
            $query1 .= " and USER_PRIV.PRIV_NO>=$PRIV_NO";
         else if($ROLE_PRIV=="3")
         {
         	 $PRIV_ID_STR=td_trim($PRIV_ID_STR);
         	 if($PRIV_ID_STR!="")
            $query1 .= " and USER.USER_PRIV in ($PRIV_ID_STR)";
         }
         if($PRIV_NO_FLAG=="3")
         {
            $query1 .= " and USER_PRIV.USER_PRIV!=1";
         }
         if($DEPT_PRIV=="3"||$DEPT_PRIV=="4")
         {
         	 $USER_ID_STR=td_trim($USER_ID_STR);
         	 if($USER_ID_STR!="")
             $query1 .= " and USER.USER_ID in ($USER_ID_STR)";
         }
      }
      if($MANAGE_FLAG!="1")
         $query1.= " and NOT_LOGIN='0'";

      $query1.= " order by PRIV_NO,USER_NO,USER_NAME";
      $cursor1= exequery(TD::conn(),$query1);
      while($ROW=mysql_fetch_array($cursor1))
      {
          $UID=$ROW["UID"];
          $USER_ID=$ROW["USER_ID"];
          $USER_NAME=$ROW["USER_NAME"];
          $SEX=$ROW["SEX"];
          $LAST_VISIT_TIME=$ROW["LAST_VISIT_TIME"];
          $PRIV_NO1=$ROW["PRIV_NO"];
          $LAST_VISIT_IP="";

          if($SEX=="")
             $SEX="0";
          if(is_array($ONLINE_USER_ARRAY[strval($UID)]))
          {
          	 $ONLINE = 1;
             if($SHOW_IP)
             {
                $LAST_VISIT_IP=$ROW["LAST_VISIT_IP"];
                if($LAST_VISIT_IP=="")
                {
                   $query= "select IP from SYS_LOG where TYPE='1' and USER_ID='$USER_ID' order by TIME desc limit 0,1";
                   $cursor= exequery(TD::conn(),$query);
                   if($ROW=mysql_fetch_array($cursor))
                   $LAST_VISIT_IP=$ROW["IP"];
                }
                $LAST_VISIT_IP=" (".td_htmlspecialchars(convertip($LAST_VISIT_IP))." ".$LAST_VISIT_IP.")";
             }
          }
          else
          	 $ONLINE = 0;

          $IMG_NAME = "U".$SEX.$ONLINE.".png";

					$USER_OP_SMS = ($OP_SMS=="1" && !find_id($EXCLUDE_UID_STR, $UID)) ? true : false;
          $USER_NAME=td_htmlspecialchars($USER_NAME);

          if($PARA_ID=="")
             $URL="$PARA_URL2?USER_ID=$USER_ID";
          else if($PARA_ID=="ISPIRIT"&&$PARA_VALUE=="1")
             $URL="/ispirit/go.php?CUR_UID=".$_SESSION["LOGIN_UID"]."&amp;SID=session_id()&amp;URL=$PARA_URL2?USER_ID=$USER_ID";
          else
             $URL="$PARA_URL2?USER_ID=$USER_ID&amp;$PARA_ID=$PARA_VALUE";

         $DEPT_ARRAY[] = array(
            "title" => td_iconv($USER_NAME, MYOA_CHARSET, 'utf-8'),
            "isFolder" => false,
            "isLazy" => false,
            "key" => "user_".$UID,
            "uid" => $UID,
            "user_id" => td_iconv($USER_ID, MYOA_CHARSET, 'utf-8'),
            "icon" => $IMG_NAME,
            "online" => $ONLINE ? true : false,
            "op_sms" => $USER_OP_SMS,
            "url" => td_iconv($URL, MYOA_CHARSET, 'utf-8'),
            "tooltip" => td_iconv($USER_TITLE_DISP, MYOA_CHARSET, 'utf-8'),
            "onload" => td_iconv("RAP('$USER_ID')", MYOA_CHARSET, 'utf-8'),
            "target" => $PARA_TARGET
         );
      }//while
   }

   $DEPARTMENT_ARRAY = TD::get_cache('SYS_DEPARTMENT');
   while(list($DEPT_ID1, $DEPT) = each($DEPARTMENT_ARRAY))
   {
      if($DEPT["DEPT_PARENT"]!=$PARENT_ID)
         continue;

      $DEPT_NAME1=$DEPT["DEPT_NAME"];
      $DEPT_NAME1=td_htmlspecialchars($DEPT_NAME1);
      $DEPT_NAME1=stripslashes($DEPT_NAME1);

      $IS_ORG=IsOrgByDeptID($DEPT_ID1);

      $CHILD_COUNT=0;
      $query = "SELECT 1 from DEPARTMENT where DEPT_PARENT='$DEPT_ID1' limit 0,1";
      $cursor2= exequery(TD::conn(),$query);
      if($ROW1=mysql_fetch_array($cursor2))
         $CHILD_COUNT++;
      $query = "SELECT 1 from USER where DEPT_ID='$DEPT_ID1' or find_in_set('$DEPT_ID1', DEPT_ID_OTHER) limit 0,1";
      $cursor2= exequery(TD::conn(),$query);
      if($ROW1=mysql_fetch_array($cursor2))
         $CHILD_COUNT++;

      $DEPT_PRIV_FLAG=is_dept_priv($DEPT_ID1, $DEPT_PRIV, $DEPT_ID_STR);
      $DEPT_PRIV_I1=$DEPT_PRIV=="3" || $DEPT_PRIV=="4" || $DEPT_PRIV_FLAG;

      if($DEPT_PRIV_FLAG==0)
      {
         $DEPT_PRIV_FLAG_TEMP=$DEPT_PRIV;
         if($DEPT_PRIV_FLAG_TEMP=="")
            $DEPT_PRIV_FLAG_TEMP=GetPostPrivByUserID($_SESSION["LOGIN_USER_ID"]);
         if($DEPT_PRIV_FLAG_TEMP==6)
            continue;
      }

      $TITLE = $DEPT_PRIV_I1 == 1 ? "[$DEPT_NAME1]" : $DEPT_NAME1;
      $URL = $TARGET = $JSON = "";
      if($PARA_URL1!="" && $DEPT_PRIV_I1==1)
      {
         $URL = "$PARA_URL1?DEPT_ID=$DEPT_ID1&$PARA_ID=$PARA_VALUE";
         $TARGET = $PARA_TARGET;
      }

      $IS_LAZY = false;
      if($CHILD_COUNT>0 || $DEPT_PRIV_I==1)
      {
         $JSON="/general/hr/manage/staff_info/tree.php?DEPT_ID=$DEPT_ID1&PARA_URL1=$PARA_URL1&PARA_URL2=$PARA_URL2&PARA_TARGET=$PARA_TARGET&PRIV_NO=$PRIV_NO&PARA_ID=$PARA_ID&PARA_VALUE=$PARA_VALUE&MANAGE_FLAG=$MANAGE_FLAG&MODULE_ID=$MODULE_ID&SHOW_IP=$SHOW_IP&PWD=$PWD&DEPT_PRIV=$DEPT_PRIV&ROLE_PRIV=$ROLE_PRIV&PRIV_NO_FLAG=$PRIV_NO_FLAG&OP_SMS=$OP_SMS&rand=".mt_rand();
         $IS_LAZY = true;
      }

      $DEPT_ARRAY[] = array(
         "title" => td_iconv($TITLE, MYOA_CHARSET, 'utf-8'),
         "isFolder" => true,
         "isLazy" => $IS_LAZY,
         "key" => "dept_".$DEPT_ID1,
         "dept_id" => $DEPT_ID1,
         "icon" => $IS_ORG==1 ? 'org.png' : false,
         "url" => td_iconv($URL, MYOA_CHARSET, 'utf-8'),
         "tooltip" => td_iconv($DEPT_NAME1, MYOA_CHARSET, 'utf-8'),
         "json" => td_iconv($JSON, MYOA_CHARSET, 'utf-8'),
         "target" => $TARGET
      );
   }//while

   return $DEPT_ARRAY;
}
?>
