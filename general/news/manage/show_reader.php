<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_cache.php");
//2013-04-11 
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";



$HTML_PAGE_TITLE = _("新闻查阅情况");
include_once("inc/header.inc.php");
?>


<script>
function delete_reader(NEWS_ID)
{
 msg='<?=_("确认要清空查阅情况吗？")?>';
 if(window.confirm(msg))
 {
  URL="delete_reader.php?NEWS_ID=" + NEWS_ID;
  window.location=URL;
 }
}
</script>


<?
 $query = "SELECT * from NEWS where NEWS_ID='$NEWS_ID'";
 $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
 if($ROW=mysql_fetch_array($cursor))
 {
     $SUBJECT=$ROW["SUBJECT"];
     $TO_ID=$ROW["TO_ID"];
     $TO_ID_REAL=$ROW["TO_ID"];
     $PRIV_ID=$ROW["PRIV_ID"];
     $USER_ID_TO=$ROW["USER_ID"];
     $PROVIDER=$ROW["PROVIDER"];
     $READERS=$ROW["READERS"];
     $NEWS_TIME=$ROW["NEWS_TIME"];

    $ROW=GetUserInfoByUID(UserId2Uid($PROVIDER),"USER_NAME,DEPT_ID");
    $PROVIDER_NAME=$ROW["USER_NAME"];
    $DEPT_ID=$ROW["DEPT_ID"];   
    $DEPT_NAME=dept_long_name($DEPT_ID);
    
 }

 if($TO_ID!="ALL_DEPT")
 {
    $TOK=strtok($PRIV_ID,",");
    while($TOK!="")
    {
       $query1 = "SELECT DEPT_ID from USER where USER_PRIV='$TOK' or find_in_set('$TOK',USER_PRIV_OTHER) and (NOT_LOGIN = 0 or NOT_MOBILE_LOGIN = 0)";
       $cursor1= exequery(TD::conn(),$query1);
       while($ROW=mysql_fetch_array($cursor1))
       {
          $DEPT_ID=$ROW["DEPT_ID"];
          if(!find_id($TO_ID,$DEPT_ID))
             $TO_ID.=$DEPT_ID.",";
       }

       $TOK=strtok(",");
    }
 }

 if($TO_ID!="ALL_DEPT")
 {
   $TOK=strtok($USER_ID_TO,",");
   while($TOK!="")
   {
      $query1 = "SELECT DEPT_ID from USER where USER_ID='$TOK'";
      $cursor1= exequery(TD::conn(),$query1);
      if($ROW=mysql_fetch_array($cursor1))
      {
         $DEPT_ID=$ROW["DEPT_ID"];
         if(!find_id($TO_ID,$DEPT_ID))
            $TO_ID.=$DEPT_ID.",";
      }
      $TOK=strtok(",");
   }
 }
?>
<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/news.gif" align="absmiddle"><span class="big3"> <?=_("查阅情况")?></span>
      &nbsp;&nbsp;&nbsp;<input type="button" value="<?=_("清空查阅情况")?>" class="SmallButton" onClick="delete_reader(<?=$NEWS_ID?>);">
      &nbsp;&nbsp;<input type="button" value="<?=_("完整显示查阅情况")?>" class="SmallButton" onClick="location='show_reader.php?NEWS_ID=<?=$NEWS_ID?>&DISPLAY_ALL=1'">
    </td>
    </tr>
</table>
<?
//------ 递归显示部门列表，支持按管理范围显示 --------
function dept_tree_list($DEPT_ID,$PRIV_OP,$READERS,$TO_ID,$USER_ID_TO,$PRIV_ID,$TO_ID_REAL,$DISPLAY_ALL,$READ_COUNT,$UN_READ_COUNT)
{
  static $DEEP_COUNT,$READ_USER,$UN_READ_USER;  
  $DEPT_ID=intval($DEPT_ID);
  $query = "SELECT * from DEPARTMENT where DEPT_PARENT='$DEPT_ID' order by DEPT_NO";
  $cursor= exequery(TD::conn(),$query);
  $POSTFIX = _("，");
  $POSTFIX1 = _(",");
  $OPTION_TEXT="";
  $DEEP_COUNT1=$DEEP_COUNT;
  $DEEP_COUNT.=_("　");
  while($ROW=mysql_fetch_array($cursor))
  {
      $COUNT++;
      $DEPT_ID=$ROW["DEPT_ID"];
      $DEPT_NAME=$ROW["DEPT_NAME"];
      $DEPT_PARENT=$ROW["DEPT_PARENT"];

      if($TO_ID!="ALL_DEPT" && !find_id($TO_ID,$DEPT_ID) && !child_in_toid($TO_ID,$DEPT_ID))
         continue;

      $DEPT_NAME=td_htmlspecialchars($DEPT_NAME);

      $DEPT_PRIV=1;

      $return_arr=dept_tree_list($DEPT_ID,$PRIV_OP,$READERS,$TO_ID,$USER_ID_TO,$PRIV_ID,$TO_ID_REAL,$DISPLAY_ALL,$READ_COUNT,$UN_READ_COUNT);
	  
	  $OPTION_TEXT_CHILD = $return_arr['option_text'];
	  $UN_READ_COUNT     = $return_arr['un_count'];
	  $READ_COUNT        = $return_arr['read_count'];

      $UN_USER="";
      $USER_NAME_STR="";
      if($TO_ID=="ALL_DEPT" || find_id($TO_ID,$DEPT_ID))
      {
         $query="select USER_ID,USER_PRIV,USER_NAME,USER_PRIV_OTHER from USER where (DEPT_ID='$DEPT_ID' or find_in_set('$DEPT_ID',DEPT_ID_OTHER)) and (NOT_LOGIN = 0 or NOT_MOBILE_LOGIN = 0) order by USER_NO,USER_NAME";
         $cursor1= exequery(TD::conn(),$query);
         while($ROW1=mysql_fetch_array($cursor1))
         {
            $USER_ID=$ROW1["USER_ID"];
            $USER_PRIV=$ROW1["USER_PRIV"];
            $USER_PRIV_OTHER=$ROW1["USER_PRIV_OTHER"];
            $USER_NAME=$ROW1["USER_NAME"];
            if(find_id($READERS,$USER_ID))
            {
            	if(!find_id($READ_USER,$USER_NAME))
               {
              	  $READ_COUNT++;
               }
               $READ_USER .=  $USER_NAME.$POSTFIX1;
               $USER_NAME_STR.=$USER_NAME.$POSTFIX;
            }
            else
            {
            	 if(($TO_ID=="ALL_DEPT") || find_id($TO_ID_REAL,$DEPT_ID) || child_in_toid($TO_ID_REAL,$DEPT_ID)|| find_id($PRIV_ID,$USER_PRIV) || check_id($PRIV_ID,$USER_PRIV_OTHER,1)!="" || find_id($USER_ID_TO,$USER_ID))
            	 {
            	 	
	                  $UN_USER.=$USER_NAME.$POSTFIX;
	                  if(!find_id($UN_READ_USER,$USER_NAME))
	                  {
	                  	$UN_READ_COUNT++;
	                  }
	                  $UN_READ_USER .=$USER_NAME.$POSTFIX1;
	                  
                 }
            }
         }
         $USER_NAME_STR=substr($USER_NAME_STR,0,-strlen($POSTFIX));
         $UN_USER=substr($UN_USER,0,-strlen($POSTFIX));
      }

      if($DISPLAY_ALL=="")
      {
         $READ_LEN=30;
         $UNREAD_LEN=30;
      }
      else
      {
         $READ_LEN=strlen($USER_NAME_STR);
         $UNREAD_LEN=strlen($UN_USER);
      }

      if($DEPT_PRIV==1)
      {
      	 $OPTION_TEXT.="
  <tr class=TableData>
    <td class=\"TableContent\">".$DEEP_COUNT1._("├").$DEPT_NAME."</td>
    <td style=\"cursor:hand\" title=\"$USER_NAME_STR\">".csubstr(strip_tags($USER_NAME_STR),0,$READ_LEN).(strlen($USER_NAME_STR)>$READ_LEN?"...":"")."</td>
    <td style=\"cursor:hand\" title=\"$UN_USER\">".csubstr(strip_tags($UN_USER),0,$UNREAD_LEN).(strlen($UN_USER)>$UNREAD_LEN?"...":"")."</td>
  </tr>";
      }

      if($OPTION_TEXT_CHILD!="")
         $OPTION_TEXT.=$OPTION_TEXT_CHILD;

  }//while

  $DEEP_COUNT=$DEEP_COUNT1;
  
  $arr = array(
  	'option_text' => $OPTION_TEXT,
	'un_count'    => $UN_READ_COUNT,
	'read_count'  => $READ_COUNT
  );

  return $arr;
}

$READ_COUNT=$UN_READ_COUNT=0;
$arr2=dept_tree_list(0,0,$READERS,$TO_ID,$USER_ID_TO,$PRIV_ID,$TO_ID_REAL,$DISPLAY_ALL,$READ_COUNT,$UN_READ_COUNT);

$OPTION_TEXT   = $arr2['option_text'];
$UN_READ_COUNT = $arr2['un_count'];
$READ_COUNT    = $arr2['read_count'];

if($OPTION_TEXT=="")
   Message(_("提示"),_("无人查阅"));
else
 {
?>
  <table class="TableList" width="100%" align="center">
    <tr>
      <td class="TableHeader" align="center" colspan="3"><?=$SUBJECT?></td>
    </tr>
    <tr>
      <td class="TableContent" align="right" colspan="3">
      <u title="<?=_("部门：")?><?=$DEPT_NAME?>" style="cursor:hand"><?=$PROVIDER_NAME?></u>&nbsp;&nbsp;
      <?=_("发布于：")?><i><?=$NEWS_TIME?></i>
      </td>
    </tr>
    <tr class="TableHeader">
      <td nowrap align="center"><?=_("部门/成员单位")?></td>
      <td nowrap align="center"><?=_("已读人员")?></td>
      <td nowrap align="center"><?=_("未读人员")?></td>
    </tr>
    <?=$OPTION_TEXT?>
    <tfoot class="TableControl">
      <td nowrap align="center"><b><?=_("合计：")?></b></td>
      <td nowrap align="center"><b><?=$READ_COUNT?></b></td>
      <td nowrap align="center"><b><?=$UN_READ_COUNT?></b></td>
    </tfoot>
  </table>
<?
 }
?>

</body>
</html>