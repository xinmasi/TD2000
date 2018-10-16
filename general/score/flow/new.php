<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("�½���������");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
function CheckForm()
{
    if(document.form1.FLOW_TITLE.value=="")
   { alert("<?=_("����������ⲻ�ܿգ���")?>");
   	 form1.FLOW_TITLE.focus()
     return (false);
   }
    if(document.form1.FLOW_TITLE.value.length >= 23)
    { alert("<?=_("��������������22���֣�")?>");
    	 form1.FLOW_TITLE.focus()
      return (false);
    }
   if(document.form1.PARTICIPANT_TO_ID.value=="")
   { alert("<?=_("�������˲��ܿգ���")?>");
     return (false);
   }

  if(document.form1.SECRET_TO_ID.value=="")
   { alert("<?=_("�����˲��ܿգ���")?>");
     return (false);
   }


   if(document.form1.GROUP_ID.value=="")
   { alert("<?=_("����ָ�꼯���ܿգ���")?>");
     return (false);
   }
   

   document.form1.submit();
}

function DateCompare(datestr1,datestr2){

 var miStart = Date.parse(datestr1.replace(/-/g,'/'));
 var miEnd   = Date.parse(datestr2.replace(/-/g,'/'));
 if((miEnd-miStart)<=0) return 0;
 return 1;

}

</script>


<body class="bodycolor">
<?
$FLOW_ID=intval($FLOW_ID);
if($FLOW_ID!="")
{
  $query = "SELECT * from SCORE_FLOW where FLOW_ID='$FLOW_ID'";
  $cursor= exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor))
  {
    $GROUP_ID=$ROW["GROUP_ID"];
    $FLOW_TITLE=$ROW["FLOW_TITLE"];
    $FLOW_DESC=$ROW["FLOW_DESC"];
    $FLOW_FLAG=$ROW["FLOW_FLAG"];
    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $END_DATE=$ROW["END_DATE"];
    $RANKMAN=$ROW["RANKMAN"];
    $PARTICIPANT_TO_ID=$ROW["PARTICIPANT"];
		$PARTICIPANT_TO_NAME=GetUserNameById($PARTICIPANT_TO_ID);
    $ANONYMITY=$ROW["ANONYMITY"];
    
    $COPY_TO_ID=$ROW["VIEW_USER_ID"];
		$COPY_TO_NAME=GetUserNameById($COPY_TO_ID);
		
		$IS_SELF_ASSESSMENT=$ROW["IS_SELF_ASSESSMENT"];
    if($BEGIN_DATE=="0000-00-00")
       $BEGIN_DATE="";
    if($END_DATE=="0000-00-00")
       $END_DATE="";

    $RAN_NAME="";
    $TOK=strtok($RANKMAN,",");
    while($TOK!="")
    {
      $query1="select * from USER where USER_ID='$TOK'";
      $cursor1= exequery(TD::conn(),$query1);
      if($ROW=mysql_fetch_array($cursor1))
         $RAN_NAME.=$ROW["USER_NAME"].",";

      $TOK=strtok(",");
    }

    $PARTI_NAME="";
    $TOK=strtok($PARTICIPANT,",");
    while($TOK!="")
    {
      $query1="select * from USER where USER_ID='$TOK'";
      $cursor1= exequery(TD::conn(),$query1);
      if($ROW=mysql_fetch_array($cursor1))
         $PARTI_NAME.=$ROW["USER_NAME"].",";

      $TOK=strtok(",");
    }
  }
}
?>
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?if($FLOW_ID=="")echo _("�½�");else echo _("�༭");?><?=_("��������")?></span>
    </td>
  </tr>
</table>

<table width="70%" align="center" class="TableBlock">
  <form enctype="multipart/form-data" action="add.php"  method="post" name="form1">
  	<tr>
      <td nowrap class="TableData">&nbsp;<?=_("����������⣺")?></td>
      <td class="TableData">
        <input type="text" name="FLOW_TITLE" size="44" maxlength="22" class="BigInput" value="<?=$FLOW_TITLE?>"> <?=_("(�������22����)")?>
      </td>
    </tr>

     <tr>
      <td nowrap class="TableData">&nbsp;<?=_("�����ˣ�")?></td>
      <td class="TableData">
        <input type="hidden" name="SECRET_TO_ID" value="<?=$RANKMAN?>">
        <textarea cols=40 name="SECRET_TO_NAME" rows="3" class="BigStatic" wrap="yes" readonly><?=$RAN_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('124','','SECRET_TO_ID', 'SECRET_TO_NAME','1')"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('SECRET_TO_ID', 'SECRET_TO_NAME')"><?=_("���")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData">&nbsp;<?=_("�������ˣ�")?></td>
      <td class="TableData">
        <input type="hidden" name="PARTICIPANT_TO_ID" value="<?=$PARTICIPANT_TO_ID ?>">
        <textarea cols=40 name="PARTICIPANT_TO_NAME" rows=5 class="BigStatic" wrap="yes" readonly><?=$PARTICIPANT_TO_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('124','','PARTICIPANT_TO_ID', 'PARTICIPANT_TO_NAME','1')"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('PARTICIPANT_TO_ID', 'PARTICIPANT_TO_NAME')"><?=_("���")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData">&nbsp;<?=_("��������鿴��(����Ա)��")?></td>
      <td class="TableData">
        <input type="hidden" name="COPY_TO_ID" value="<?=$COPY_TO_ID ?>">
        <textarea cols=40 name="COPY_TO_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$COPY_TO_NAME ?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('124','6','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("���")?></a>
      </td>
   </tr>
    <tr>
      <td nowrap class="TableData"><?=_("���չ���Χ��")?></td>
      <td class="TableData">
        <input type="checkbox" name="FLOW_FLAG"  id="Id_FLOWFLAG" <? if($FLOW_FLAG=="1") echo "checked";?> ><label for="Id_FLOWFLAG"><?=_("���չ���Χ")?></label>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("������")?></td>
      <td class="TableData">
        <input type="checkbox" name="IS_SELF_ASSESSMENT"  id="IS_SELF_ASSESSMENT" <? if($IS_SELF_ASSESSMENT==1) echo "checked";?>>
        <label for="IS_SELF_ASSESSMENT"><?=_("������")?></label>
      </td>	
    </tr>

    <tr>
      <td nowrap class="TableData"><?=_("����ָ�꼯��")?></td>
      <td class="TableData">
         <select name="GROUP_ID" class="BigSelect">
<?
$query="select * from SCORE_GROUP";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $GROUPID=$ROW["GROUP_ID"];
   $GROUPNAME=$ROW["GROUP_NAME"];
?>
               <option value="<?=$GROUPID?>" <?if($GROUPID==$GROUP_ID) echo "selected";?>><?=$GROUPNAME?></option>
<?
}
?>
        </select>

      </td>
    </tr>

    <tr>
      <td nowrap class="TableData"> <?=_("������")?></td>
      <td class="TableData">
        <input type="checkbox" name="ANONYMITY" id="Id_ANONYMITY" <?if($ANONYMITY=="1") echo "checked";?>><label for="Id_ANONYMITY"><?=_("��������")?></label>
      </td>
    </tr>
    <tr>

      <td nowrap class="TableData"> <?=_("��Ч�ڣ�")?></td>
      <td class="TableData">
        <?=_("��Ч���ڣ�")?><input type="text" name="BEGIN_DATE" size="10" maxlength="10" class="BigInput" id="start_time" value="<?=$BEGIN_DATE?>" onClick="WdatePicker()"/>
        <?=_("Ϊ��Ϊ������Ч")?><br>
        <?=_("��ֹ���ڣ�")?><input type="text" name="END_DATE" size="10" maxlength="10" class="BigInput" value="<?=$END_DATE?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>
        <?=_("Ϊ��Ϊ�ֶ���ֹ")?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("���ѣ�")?></td>
      <td class="TableData">
<?=sms_remind(15);?>
      </td>
    </tr>
     <tr>
      <td nowrap class="TableData"><?=_("������")?></td>
      <td class="TableData">
        <textarea name="FLOW_DESC" cols="45" rows="5" class="BigInput"><?=$FLOW_DESC?></textarea>
      </td>
    </tr>
    <tfoot align="center" class="TableFooter">
      <td colspan="2" nowrap>
        <input type="hidden" name="FLOW_ID" value="<?=$FLOW_ID?>">
        <input type="button" value="<?=_("����")?>" class="BigButton" onClick="return CheckForm();">&nbsp;&nbsp;
<?
if($FLOW_ID!="")
{
?>
        <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='index1.php?CUR_PAGE=$CUR_PAGE'">
<?
}
?>
      </td>
    </tfoot>
  </table>
</form>

</body>
</html>