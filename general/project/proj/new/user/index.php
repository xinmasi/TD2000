<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

//���������ô�ģ��ʹ��
$STR = isset($FROM)?"FROM=NEWTASK":"";

$HTML_PAGE_TITLE = _("��Ŀ��Ա");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.min.css<?=$GZIP_POSTFIX?>" />


<script>
function check_form()
{
	if(document.form1.PROJ_PRIV.value=="" || document.form1.USER_ID.value=="")
	{
		alert("<?=_("��Ա���ɫ����Ϊ�գ�")?>");
		return(false);
	}
    return(true);
}
</script>

<body class="bodycolor" >
	<div style="padding:10px;">
		<table class="table table-bordered " style="">
			<tr class="info">
				<td colspan="3"><strong><?=_("�����Ŀ��Ա")?></strong></td>
			</tr>
			<tr>
				<form name="form1" method="post" action="submit.php?<?= $STR;?>" onsubmit="return check_form();">
				<td>1.<?=_("��Ŀ��ɫ")?><br/>2.<?=_("ѡ����Ա")?><br/>3.<?=_("���")?></td>
				<td colspan="2">
					<select name="PROJ_PRIV" >
					 <?=code_list("PROJ_ROLE","")?>
					</select><br/>
					<textarea style="OVERFLOW-Y: auto; width:350px; height:60px;" readOnly name="USER_NAME"></textarea>
					<br/>
					<INPUT type="hidden" name="USER_ID">
					<a href="javascript:;" class="orgAdd" onClick="SelectUser('186','','USER_ID', 'USER_NAME')"><?=_("ѡ��")?></a>
					<a href="javascript:;" class="orgClear" onClick="ClearUser('USER_ID', 'USER_NAME')"><?=_("���")?></a>
					<br/><br/>
					<input type="hidden" name="PROJ_ID" value="<?=$PROJ_ID?>">
					<input type="submit" value="<?=_("���")?>" class="btn btn-success" title="<?=_("�����Ŀ��Ա")?>">
				</td>
				</form>
			</tr>
			
			
<?
if($PROJ_ID)
{
  $query = "select PROJ_USER,PROJ_PRIV from PROJ_PROJECT where PROJ_ID='$PROJ_ID'";
  $cursor = exequery(TD::conn(), $query);
  if($ROW = mysql_fetch_array($cursor))
  {
  	 $PROJ_USER = $ROW["PROJ_USER"];
  	 $PROJ_PRIV = $ROW["PROJ_PRIV"];
  }
  
  $PROJ_USER_ARRAY = td_explode("|",$PROJ_USER);
  $PROJ_PRIV_ARRAY = explode("|",$PROJ_PRIV);

  $COUNT=0;
  for($i=0; $i < count($PROJ_PRIV_ARRAY); $i++)
  {
  	 if($PROJ_PRIV_ARRAY[$i]=='')
  	   continue;
  	 $COUNT++;
  	 if($COUNT==1)
  	   echo '
        <tr class="info">
         <td nowrap align="center">��Ŀ��ɫ</td>
         <td nowrap align="center">����</td>
         <td nowrap align="center" width="80">����</td>  	  	
        </tr>';
  	 
  	 $PROJ_USER_NAME="";
  	 $PRIV_NAME = get_code_name($PROJ_PRIV_ARRAY[$i],"PROJ_ROLE");
  	 $query = "select USER_NAME from USER WHERE FIND_IN_SET(USER_ID,'$PROJ_USER_ARRAY[$i]')";
     $cursor = exequery(TD::conn(), $query);
     while($ROW = mysql_fetch_array($cursor))
  	    $PROJ_USER_NAME .= $ROW["USER_NAME"].",";
  	    
  	 echo '
  	      <tr>
            <td nowrap align="center" width="100">'.$PRIV_NAME.'</td>
            <td>'.$PROJ_USER_NAME.'</td>
            <td nowrap align="center">
              <a href="delete.php?PROJ_ID='.$PROJ_ID.'&PROJ_PRIV_DEL='.$PROJ_PRIV_ARRAY[$i].'">ɾ��</a>
            </td>  	  	
          </tr>';
  }
}
?>
			
		</table>
	</div>
	<?
if($COUNT ==0 )
   Message("",_("��δ�����Ŀ��Ա��"));
   
if(!empty($STR)){
   ?>
   <div align="center">
	<button onclick="location.href='/general/project/proj/new/task/new.php?PROJ_ID=<?= $PROJ_ID?>'"  class="btn btn-small">����</button> 
	</div>
   <?
   }
?>
</body>
</html>