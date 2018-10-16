<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("check_priv.inc.php");
include_once("inc/utility_cache.php");
$HTML_PAGE_TITLE = _("����ͳ��");
include_once("inc/header.inc.php");
/*
 * 2014-06-17  ����
 * function GET_DEPT_ID($a);
 * �ݹ��ȡָ�����ŵ���������ID���ַ�������my_dept_tree2���ʹ�ÿ��Ը���Ȩ�޻�ȡ��Ӧ���ż����������ŵ������б�
 * ���� $a :ָ�����ŵ�ID�š�
 * ����ֵ $DEPT_ID_STR :�ò��ż����������ŵ�ID�ַ�����
 */
function GET_DEPT_ID($a){
	$query="select DEPT_ID from department where DEPT_PARENT=$a";
	$cursor= exequery(TD::conn(),$query);
	while($ROW=mysql_fetch_array($cursor)){
		$DEPT_ID_STR.=$ROW["DEPT_ID"].",";
		$DEPT_ID_STR.=GET_DEPT_ID($ROW["DEPT_ID"]);
	
	}
	return $DEPT_ID_STR;
}
/*
 *2014-06-17  ����
 * function my_dept_tree2($DEPT_ID,$DEPT_CHOOSE,$POST_OP,$NO_CHILD_DEPT=0);
 * ��ȡ�û������ź�ָ�����ŵ������б�
 * ����$DEPT_ID��Ĭ�� 0��$DEPT_CHOOSE ��Ĭ��0��$POST_OP ��ָ������ID��$NO_CHILD_DEPT ����ѡ��
 * ����ֵ$OPTION_TEXT :����option��ǩ�Ĳ����ַ�����
 */
function my_dept_tree2($DEPT_ID,$DEPT_CHOOSE,$POST_OP,$NO_CHILD_DEPT=0)
{
	if(is_array($POST_OP))
	{
		$DEPT_PRIV=$POST_OP["DEPT_PRIV"];
		$DEPT_ID_STR=$POST_OP["DEPT_ID_STR"];
	}
	if($DEPT_ID==0)
		$LEVEL=0;	
	$DEPT_PRIV_ID_STR = $POST_OP.',';
	//$DEPT_PRIV_ID_STR.= GET_DEPT_ID($POST_OP);
   
	//$DEPT_PRIV_ID_STR = my_dept_priv_id($DEPT_PRIV, $DEPT_ID_STR);
	$DEPARTMENT_ARRAY = TD::get_cache('SYS_DEPARTMENT');
	while($DEPT = current($DEPARTMENT_ARRAY))
	{
		$ID=key($DEPARTMENT_ARRAY);
		if($ID==$DEPT_ID)
			$LEVEL=$DEPT["DEPT_LEVEL"];

		if(!isset($LEVEL) && $ID!=$DEPT_ID)
		{
			next($DEPARTMENT_ARRAY);
			continue;
		}

		if($NO_CHILD_DEPT && $NO_CHILD_DEPT==$ID)
		{
			while($DEPT_NEXT = next($DEPARTMENT_ARRAY))
			{
				if($DEPT_NEXT["DEPT_LEVEL"]<=$DEPARTMENT_ARRAY[$NO_CHILD_DEPT]["DEPT_LEVEL"])
					break;
			}
			prev($DEPARTMENT_ARRAY);
		}

		if($LEVEL>=$DEPT["DEPT_LEVEL"] && $ID!=$DEPT_ID)
			break;

		$DEPT_NAME=$DEPT["DEPT_NAME"];
		$DEPT_NAME=td_htmlspecialchars($DEPT_NAME);
		 if(!$POST_OP || find_id($DEPT_PRIV_ID_STR, $ID))
		{
			$OPTION_TEXT.="<option ";
			if($ID==$DEPT_CHOOSE)
				$OPTION_TEXT.="selected ";
			$OPTION_TEXT.="value=$ID>".$DEPT["DEPT_LINE"].$DEPT_NAME."</option>\n";
           
        }
        
		next($DEPARTMENT_ARRAY);
	}
	if(!find_id($DEPT_PRIV_ID_STR, $DEPT_CHOOSE) && $DEPARTMENT_ARRAY[$DEPT_CHOOSE] != ""){
		$OPTION_TEXT = "<option selected value='".$DEPARTMENT_ARRAY[$DEPT_CHOOSE]."'>".$DEPARTMENT_ARRAY[$DEPT_CHOOSE]["DEPT_LINE"].$DEPARTMENT_ARRAY[$DEPT_CHOOSE]["DEPT_NAME"]."</option>".$OPTION_TEXT;
	
    }
    
	return $OPTION_TEXT;
}

?>


<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
function CheckForm1()
{
   if(document.form1.DATE1.value=="")
   { alert("<?=_("��ʼ���ڲ���Ϊ�գ�")?>");
     return (false);
   }

   if(document.form1.DATE2.value=="")
   { alert("<?=_("��ֹ���ڲ���Ϊ�գ�")?>");
     return (false);
   }
   if(document.form1.DEPARTMENT1.value=="")
   { alert("<?=_("ͳ�Ʋ��Ų���Ϊ�գ�")?>");
     return (false);
   }
   return (true);
}

function set_date(str)
{
  document.form1.DATE1.value=str;
}
function set_date2(str)
{
  document.form1.DATE2.value=str;
}
</script>

<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("����ͳ��")?></span>
    </td>
  </tr>
</table>

<?
$CUR_DATE=date("Y-m-d",time());
$CUR_DATE_FIRST=date("Y-m-01",time());
$CUR_DATE_END = date('Y-m-d', strtotime("$CUR_DATE_FIRST +1 month -1 day")); 

$query = "SELECT POST_PRIV,POST_DEPT from USER where UID='".$_SESSION["LOGIN_UID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $USER_POST_PRIV=$ROW["POST_PRIV"];
   $USER_POST_DEPT=$ROW["POST_DEPT"];   
}
?>

<table align="center" class="TableList" width=450>
<form action="search/" name="form1" onsubmit="return CheckForm1();">
<tr class=TableHeader >
<td colspan=2>
<?=_("����ͳ��")?>
</td>
</tr>
<tr>
<td class="TableContent">
<?=_("����")?>
</td>
<td class="TableData">
<select name="DEPARTMENT1" style="width:170px;" class="BigSelect">
	<?
	if($DEPT_PRIV==0||$DEPT_PRIV==1||$DEPT_PRIV==2)
	{
		 if($DEPT_PRIV==1){
		    echo '<option value="ALL_DEPT">'._("���в���").'</option>';
	        echo my_dept_tree(0,$DEPT_ID,array("DEPT_PRIV" => $DEPT_PRIV,"DEPT_ID_STR" => $DEPT_ID_STR));
	     }else{
			$query="select DEPT_ID from hr_manager where FIND_IN_SET('".$_SESSION["LOGIN_USER_ID"]."',DEPT_HR_MANAGER)";
           $cursor= exequery(TD::conn(),$query);
			while($ROW=mysql_fetch_array($cursor))
			{
				echo my_dept_tree2(0,0,$ROW["DEPT_ID"]);
			}
			//echo my_dept_tree(0,$DEPT_ID,array("DEPT_PRIV" => $DEPT_PRIV,"DEPT_ID_STR" => $DEPT_ID_STR));
	     }
	}
	else
	{
		if($USER_POST_PRIV=="1")
		{
	     echo my_dept_tree(0,$DEPT_ID,1);
	  }
	  if($USER_POST_PRIV=="0")
		{
	     echo my_dept_tree(0,$DEPT_ID,array("DEPT_PRIV" => "0","DEPT_ID_STR" => $_SESSION["LOGIN_DEPT_ID"]));
	  }
	  if($USER_POST_PRIV=="2")
		{	
	     echo my_dept_tree(0,$DEPT_ID,array("DEPT_PRIV" => "2","DEPT_ID_STR" => $USER_POST_DEPT));
	    }  
	}
	?>
</select>
</td>

<tr>
<td class="TableContent">
<?=_("��ʼ����")?>
</td>
<td class="TableData">
<input type="text" name="DATE1" size="10" id="start_time" maxlength="10" class="BigInput" value="<?=$CUR_DATE_FIRST?>" onClick="WdatePicker()"/>
<a href="javascript:set_date('<?=$CUR_DATE?>')"><?=_("��Ϊ����")?></a>
</td>
</tr>
<tr>
<td class="TableContent">
<?=_("��ֹ����")?>
</td>
<td class="TableData">
<input type="text" name="DATE2" size="10" maxlength="10" class="BigInput" value="<?=$CUR_DATE_END?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>
<a href="javascript:set_date2('<?=$CUR_DATE?>')"><?=_("��Ϊ����")?></a>
</td>
</tr>
<tr class="TableControl">
<td colspan=2 align=center>
<input type="submit" value="<?=_("����ͳ��")?>" class="BigButton" title="<?=_("����ͳ��")?>">
</td>
</tr>
</table>
</form>

</body>
</html>