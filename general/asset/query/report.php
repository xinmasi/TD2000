<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("�ʲ�����");
include_once("inc/header.inc.php");
?>





<body topmargin="1" leftmargin="0">
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/asset.gif" align="absmiddle"><span class="big5">&nbsp;&nbsp;<?=_("�ʲ�����")?></span><br>
    </td>
    <td align="right">
     <input type="button" class="SmallButton" value="<?=_("�ر�")?>" onClick="window.close();">&nbsp;&nbsp;<input type="button" class="SmallButton" value="<?=_("��ӡ")?>" onClick="window.print();">
   </td>
  </tr>
</table>
<table class="TableList" width="95%" align="center">
  <tr class=TableHeader align=center>
    <td width="30%"><?=_("����")?></td>
    <td nowrap valign="bottom"><?=_("���ʲ�ԭֵ")?></td>
    <td nowrap valign="bottom"><?=_("���ۼ��۾�")?></td>
  </tr>
<?
//------ �ݹ���ʾ�����б�֧�ְ�����Χ��ʾ --------
function dept_tree_list($DEPT_ID)
{
  static $DEEP_COUNT;
  static $TOTAL_SUM;
  $DEPT_ID=intval($DEPT_ID);
  $query = "SELECT * from DEPARTMENT where DEPT_PARENT='$DEPT_ID'";
  $cursor= exequery(TD::conn(),$query);
  $OPTION_TEXT="";
  $DEEP_COUNT1=$DEEP_COUNT;
  $DEEP_COUNT.=_("��");
  while($ROW=mysql_fetch_array($cursor))
  {
      $COUNT++;
      $DEPT_ID=$ROW["DEPT_ID"];
      $DEPT_NAME=$ROW["DEPT_NAME"];
      $DEPT_PARENT=$ROW["DEPT_PARENT"];
      if(is_dept_priv($DEPT_ID)!="1")continue;
      $DEPT_NAME=td_htmlspecialchars($DEPT_NAME);
      $DEPT_NAME=stripslashes($DEPT_NAME);

      $OPTION_TEXT_CHILD=dept_tree_list($DEPT_ID);

      //------��������--------
        $query1="SELECT CPTL_VAL,SUM_DPCT
                 FROM `DEPARTMENT` a
                 LEFT OUTER JOIN `CP_CPTL_INFO` b ON a.DEPT_ID = b.DEPT_ID
                 WHERE a.DEPT_ID ='$DEPT_ID'";
         $cursor1= exequery(TD::conn(),$query1);
         $TOTAL_CPTL_VAL=0;
         $TOTAL_SUM_DPCT=0;         
         while($ROW=mysql_fetch_array($cursor1))
         {
         	  $SUM_DPCT=$ROW["SUM_DPCT"];
         	  $CPTL_VAL=$ROW["CPTL_VAL"];
         	  $TOTAL_SUM_DPCT=$TOTAL_SUM_DPCT + $SUM_DPCT;         	  
         	  $TOTAL_CPTL_VAL=$TOTAL_CPTL_VAL + $CPTL_VAL;
         	} 

      //---------------------
       $OPTION_TEXT.="
       <tr class=TableData>
         <td>".$DEEP_COUNT1._("��").$DEPT_NAME."</a></td>
         <td nowrap valign=\"bottom\">".$TOTAL_CPTL_VAL."</td>
         <td nowrap valign=\"bottom\">".$TOTAL_SUM_DPCT."</td>
      </tr>";
      if($OPTION_TEXT_CHILD!="")
         $OPTION_TEXT.=$OPTION_TEXT_CHILD;

  }//while

  $DEEP_COUNT=$DEEP_COUNT1;
  return $OPTION_TEXT;
}

if($DEPT_ID=="")
   $DEPT_ID=0;

echo $OPTION_TEXT=dept_tree_list($DEPT_ID);
?>
</body>
</html>
