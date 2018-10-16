<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_cache.php");


$HTML_PAGE_TITLE = _("公告通知查阅情况");
include_once("inc/header.inc.php");
?>


<script>
function delete_reader(NOTIFY_ID)
{
 msg='<?=_("确认要清空查阅情况吗？")?>';
 if(window.confirm(msg))
 {
  URL="delete_reader.php?NOTIFY_ID=" + NOTIFY_ID;
  window.location=URL;
 }
}

function SetNums()
{
	document.form1.action="noread_remind.php";
	document.form1.submit();
}
</script>

<?
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";   
 $CUR_DATE=date("Y-m-d",time());
 $query = "SELECT SUBJECT,FROM_ID,TO_ID,PRIV_ID,PUBLISH,USER_ID,READERS,BEGIN_DATE,END_DATE from NOTIFY where NOTIFY_ID='$NOTIFY_ID'";
 $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
 if($ROW=mysql_fetch_array($cursor))
 {
     $SUBJECT=$ROW["SUBJECT"];
     $FROM_ID=$ROW["FROM_ID"];
     $TO_ID=$ROW["TO_ID"];
     $TO_ID_REAL=$ROW["TO_ID"];
     $PRIV_ID=$ROW["PRIV_ID"];
     $USER_ID_TO=$ROW["USER_ID"];
     $READERS=$ROW["READERS"];
     $PUBLISH=$ROW["PUBLISH"];
     $BEGIN_DATE=$ROW["BEGIN_DATE"];
     $END_DATE=$ROW["END_DATE"];
     
     if($END_DATE=="0")
       $END_DATE="";
     else 
        $END_DATE=date("Y-m-d",$END_DATE);  
     $BEGIN_DATE=date("Y-m-d",$BEGIN_DATE); 
     if($END_DATE!="")
       {
         if(compare_date($CUR_DATE,$END_DATE)>0)
         {
            $NOTIFY_STATUS=3;
         }
       }         
        
     $BEGIN_DATE=strtok($BEGIN_DATE," ");
     $FROM_UID=UserId2Uid($FROM_ID);
     if($FROM_UID!="")
     {
      $ROW=GetUserInfoByUID($FROM_UID,"USER_NAME,DEPT_ID");
      $FROM_NAME=$ROW["USER_NAME"];
      $DEPT_ID=$ROW["DEPT_ID"];
      $DEPT_NAME=dept_long_name($DEPT_ID);
     }
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
<form name="form1" method="post">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/notify.gif" align="absmiddle"><span class="big3"> <?=_("查阅情况")?></span>
   <?
   if($_SESSION["LOGIN_USER_PRIV"]==1)
   {
   ?>
      &nbsp;&nbsp;&nbsp;<input type="button" value="<?=_("清空查阅情况")?>" class="SmallButton" onClick="delete_reader(<?=$NOTIFY_ID?>);">
   <?
    }
   ?>
    </td>
    </tr>
</table>

<?
//------ 递归显示部门列表，支持按管理范围显示 --------
function dept_tree_list($DEPT_ID,$PRIV_OP,$READERS,$TO_ID,$USER_ID_TO,$PRIV_ID,$TO_ID_REAL,$NOTIFY_ID,$READ_COUNT,$UN_READ_COUNT)
{
  static $DEEP_COUNT,$READ_USER,$UN_READ_USER;
  
  $query = "SELECT DEPT_ID,DEPT_NAME,DEPT_PARENT from DEPARTMENT where DEPT_PARENT='$DEPT_ID' order by DEPT_NO";
  
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

      $return_arr=dept_tree_list($DEPT_ID,$PRIV_OP,$READERS,$TO_ID,$USER_ID_TO,$PRIV_ID,$TO_ID_REAL,$NOTIFY_ID,$READ_COUNT,$UN_READ_COUNT);
	  $OPTION_TEXT_CHILD = $return_arr['option_text'];
	  $UN_READ_COUNT     = $return_arr['un_count'];
	  $READ_COUNT        = $return_arr['read_count'];

      $UN_USER="";
      $USER_NAME_STR="";
      if($TO_ID=="ALL_DEPT" || find_id($TO_ID,$DEPT_ID))
      {
		 $query="select USER_ID,USER_NO,PRIV_NO,USER.USER_PRIV as USER_PRIV,USER_NAME,USER_PRIV_OTHER from USER,USER_PRIV where (DEPT_ID='$DEPT_ID' or find_in_set('$DEPT_ID',DEPT_ID_OTHER))  and (NOT_LOGIN = 0 or NOT_MOBILE_LOGIN = 0) and USER.USER_PRIV=USER_PRIV.USER_PRIV order by PRIV_NO,USER_NO,USER_NAME";
         $cursor1= exequery(TD::conn(),$query);
         while($ROW1=mysql_fetch_array($cursor1))
         {
            $USER_ID=$ROW1["USER_ID"];
            $USER_PRIV=$ROW1["USER_PRIV"];
            $USER_PRIV_OTHER=$ROW1["USER_PRIV_OTHER"];
            $USER_NAME=$ROW1["USER_NAME"];
            if(find_id($READERS,$USER_ID))
            {
               $READ_TIME="";
               $query="select TIME from APP_LOG where MODULE ='4' and OPP_ID='$NOTIFY_ID' and USER_ID='$USER_ID' and TYPE='1'";
               $cursor2= exequery(TD::conn(),$query);
               if($ROW2=mysql_fetch_array($cursor2))
               {
                  $READ_TIME=$ROW2["TIME"];
               }
               
               if(!find_id($READ_USER,$USER_NAME))
               {
              	  $READ_COUNT++;
               }
               $READ_USER .=  $USER_NAME.$POSTFIX1;
               
               if($READ_TIME!="")
               {
                  $USER_NAME_STR.=$USER_NAME."(".$READ_TIME.")".$POSTFIX;
                  
               }
               else
               {
                  $USER_NAME_STR.=$USER_NAME.$POSTFIX;
                  
               }  
            }
            else
            {
            	 if(($TO_ID=="ALL_DEPT") || find_id($TO_ID_REAL,$DEPT_ID) || child_in_toid($TO_ID_REAL,$DEPT_ID)|| find_id($PRIV_ID,$USER_PRIV) || check_id($PRIV_ID,$USER_PRIV_OTHER,1)!="" || find_id($USER_ID_TO,$USER_ID))
            	 {
                  $UN_USER.=$USER_NAME.$POSTFIX;
                  $UN_USER_ID_STR.=$USER_ID.$POSTFIX1;
                  if(!find_id($UN_READ_USER,$USER_ID))
                  {
                  	$UN_READ_COUNT++;
                  }
                  $UN_READ_USER .=$UN_USER_ID_STR;
               }
            }
         }
         $USER_NAME_STR=substr($USER_NAME_STR,0,-strlen($POSTFIX));
         $UN_USER=substr($UN_USER,0,-strlen($POSTFIX));
         
      }
              
      $READ_LEN=strlen($USER_NAME_STR);
      $UNREAD_LEN=strlen($UN_USER);

      if($DEPT_PRIV==1)
      {
      	 $OPTION_TEXT.="
  <tr class=TableData>
    <td class=\"TableContent\">".$DEEP_COUNT1._("├").$DEPT_NAME."</td>
    <td style=\"\">".csubstr(strip_tags($USER_NAME_STR),0,$READ_LEN).(strlen($USER_NAME_STR)>$READ_LEN?"...":"")."</td>
    <td style=\"\">".csubstr(strip_tags($UN_USER),0,$UNREAD_LEN).(strlen($UN_USER)>$UNREAD_LEN?"...":"")."</td>
    
  </tr>";
      }

      if($OPTION_TEXT_CHILD!="")
         $OPTION_TEXT.=$OPTION_TEXT_CHILD;
  }//while
  

  $OPTION_TEXT.="<input type=\"hidden\" name=\"USER_ID_STR\" value=\"".$UN_USER_ID_STR."\">";

  $DEEP_COUNT=$DEEP_COUNT1;
  
  $arr = array(
  	'option_text' => $OPTION_TEXT,
	'un_count'    => $UN_READ_COUNT,
	'read_count'  => $READ_COUNT
  );
  
  return $arr;
}

$READ_COUNT=$UN_READ_COUNT=0;
$arr2=dept_tree_list(0,0,$READERS,$TO_ID,$USER_ID_TO,$PRIV_ID,$TO_ID_REAL,$NOTIFY_ID,$READ_COUNT,$UN_READ_COUNT);

$OPTION_TEXT   = $arr2['option_text'];
$UN_READ_COUNT = $arr2['un_count'];
$READ_COUNT    = $arr2['read_count'];

if($OPTION_TEXT=="")
   Message(_("提示"),_("无人查阅"));
else
 {
?>
  <table class="TableTop" width="100%" align="center">
   <tr>
      <td class="left"></td>
      <td class="center"><?=$SUBJECT?></td>
      <td class="right"></td>
   </tr>
 </table>
  <table class="TableBlock" width="100%" align="center">
    <tr>
      <td class="TableContent" align="right" colspan="3">
      <u title="<?=_("部门：")?><?=$DEPT_NAME?>" style="cursor:hand"><?=$FROM_NAME?></u>&nbsp;&nbsp;
      <?=_("发布于：")?><i><?=$BEGIN_DATE?></i>
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
  <?if ($NOTIFY_STATUS!=3){?>
  <table class="TableBlock"" width="100%" >
  <tr>
        <td><?=_("提醒未查阅人员:")?></td>
        <input type="hidden"  name="NOTIFY_ID" value="<?=$NOTIFY_ID?>">
        <td align="left"><?=sms_remind(1);?>
        </td>
       
  </tr>
 <tr align="center">
      <td colspan="2" nowrap><input class="BigButton" onClick="SetNums()" type="button" value="<?=_("确定")?>"/>&nbsp;&nbsp;
      <input class="BigButton" onClick="window.close();" type="button" value="<?=_("关闭")?>"/></td>
      
   </tr>
  </table>
  <?}?>
</form>
  
<?
 }
?>

</body>
</html>