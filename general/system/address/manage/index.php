<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("�������");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script>
function delete_group(GROUP_ID)
{
 msg='<?=_("ȷ��Ҫɾ���÷�����")?>\n<?=_("ע�⣺�÷����µ���ϵ�˽�ȫ��ת�뵽Ĭ�Ϸ����У�")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?GROUP_ID=" + GROUP_ID;
  window.location=URL;
 }
}

function clear_group(GROUP_ID,GROUP_NAME)
{
  var msg = sprintf("<?=_("ȷ��Ҫ��� '%s' �������ϵ����")?>", GROUP_NAME);
  if(window.confirm(msg))
  {
     URL="clear.php?GROUP_ID=" + GROUP_ID;
     window.location=URL;
  }
}
</script>

<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�½�����")?></span><br>
    </td>
  </tr>
</table>

<div align="center">
<input type="button" value="<?=_("�½�����")?>" class="BigButton" onClick="location='new.php';" title="<?=_("�����µķ��飬¼�������Ϣ")?>">
</div>

<br>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("�������")?></span>
    </td>
  </tr>
</table>

<br>

<table class="TableList" width="95%" align="center">
  <tr class="TableData">
    <td nowrap align="center"><?=_("Ĭ��")?></td>
    <td nowrap align="center"><?=_("ȫ�岿��")?></td>
    <td nowrap align="center"><?=_("ȫ���ɫ")?></td>
    <td nowrap align="center"><?=_("ȫ����Ա")?></td>
    <td>
      <a href="javascript:clear_group('0','<?=_("Ĭ��")?>');"> <?=_("���")?></a>
      <a href="import.php?GROUP_ID=0"> <?=_("����")?></a>
            <a href="print.php?GROUP_ID=0" target="_blank"> <?=_("��ӡ")?></a><br>
      <a href="export.php?GROUP_ID=0&TYPE=0" target="_blank"> <?=_("����Foxmail��ʽ")?></a>
      <a href="export.php?GROUP_ID=0&TYPE=1" target="_blank"> <?=_("����OutLook��ʽ")?></a>
    </td>
  </tr>

<?
 //============================ ������� =======================================
 $query = "select * from ADDRESS_GROUP where USER_ID='' order by ORDER_NO asc,GROUP_NAME asc";
 $cursor= exequery(TD::conn(),$query);

 while($ROW=mysql_fetch_array($cursor))
 {
    $GROUP_ID=$ROW["GROUP_ID"];
    $GROUP_NAME=$ROW["GROUP_NAME"];
    $PRIV_DEPT=$ROW["PRIV_DEPT"];
    $PRIV_ROLE=$ROW["PRIV_ROLE"];
    $PRIV_USER=$ROW["PRIV_USER"];

    if($PRIV_DEPT=="ALL_DEPT")
       $PRIV_DEPT_STR=_("ȫ�岿��");
    else
    {
       $PRIV_DEPT_STR="";
       $TOK=strtok($PRIV_DEPT,",");
       while($TOK!="")
       {
         if($PRIV_DEPT_STR!="")
            $PRIV_DEPT_STR.=",";
         $query1="select * from DEPARTMENT where DEPT_ID='$TOK'";
         $cursor1= exequery(TD::conn(),$query1);
         if($ROW=mysql_fetch_array($cursor1))
            $PRIV_DEPT_STR.=$ROW["DEPT_NAME"];
         $TOK=strtok(",");
        }
     }

     $PRIV_ROLE_STR="";
     $TOK=strtok($PRIV_ROLE,",");
     while($TOK!="")
     {
       if($PRIV_ROLE_STR!="")
          $PRIV_ROLE_STR.=",";
       $query1="select * from USER_PRIV where USER_PRIV='$TOK'";
       $cursor1= exequery(TD::conn(),$query1);
       if($ROW=mysql_fetch_array($cursor1))
          $PRIV_ROLE_STR.=$ROW["PRIV_NAME"];
       $TOK=strtok(",");
      }

     $PRIV_USER_STR="";
     $TOK=strtok($PRIV_USER,",");
     while($TOK!="")
     {
       if($PRIV_USER_STR!="")
          $PRIV_USER_STR.=",";
       $query1="select * from USER where USER_ID='$TOK'";
       $cursor1= exequery(TD::conn(),$query1);
       if($ROW=mysql_fetch_array($cursor1))
          $PRIV_USER_STR.=$ROW["USER_NAME"];
       $TOK=strtok(",");
      }
?>
    <tr class="TableData">
      <td nowrap align="center"><?=$GROUP_NAME?></td>
      <td align="center"><?=$PRIV_DEPT_STR?></td>
      <td align="center"><?=$PRIV_ROLE_STR?></td>
      <td align="center"><?=$PRIV_USER_STR?></td>
      <td>
          <a href="edit.php?GROUP_ID=<?=$GROUP_ID?>"> <?=_("�༭")?></a>
          <a href="javascript:delete_group(<?=$GROUP_ID?>);"> <?=_("ɾ��")?></a>
          <a href="javascript:clear_group('<?=$GROUP_ID?>','<?=$GROUP_NAME?>');"> <?=_("���")?></a>
          <a href="import.php?GROUP_ID=<?=$GROUP_ID?>"> <?=_("����")?></a>     
          <a href="javascript:;" onClick="window.open('support.php?GROUP_ID=<?=$GROUP_ID?>&GROUP_NAME=<?=urlencode($GROUP_NAME)?>','','height=600,width=600,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=350,top=300,resizable=yes');"><?=_("ά��Ȩ��")?></a>
          <br>
          <a href="print.php?GROUP_ID=<?=$GROUP_ID?>" target="_blank"> <?=_("��ӡ")?></a>
          <a href="export.php?GROUP_ID=<?=$GROUP_ID?>&TYPE=0" target="_blank"> <?=_("����Foxmail��ʽ")?></a>
          <a href="export.php?GROUP_ID=<?=$GROUP_ID?>&TYPE=1" target="_blank"> <?=_("����OutLook��ʽ")?></a>

      </td>
    </tr>
<?
 }
?>
   <thead class="TableHeader">
      <td nowrap align="center"><?=_("��������")?></td>
      <td nowrap align="center"><?=_("���Ų���")?></td>
      <td nowrap align="center"><?=_("���Ž�ɫ")?></td>
      <td nowrap align="center"><?=_("������Ա")?></td>
      <td nowrap align="center" width="300"><?=_("����")?></td>
   </thead>
   </table>

</body>
</html>
