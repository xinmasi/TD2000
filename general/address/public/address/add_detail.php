<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_field.php");


$HTML_PAGE_TITLE = _("��ϵ������");
include_once("inc/header.inc.php");
?>



<?
 //============================ ��ʾ��ϵ������ =======================================
 $query = "SELECT * from ADDRESS where ADD_ID='$ADD_ID'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
    $PSN_NAME=$ROW["PSN_NAME"];
    $SEX=$ROW["SEX"];
    $BIRTHDAY=$ROW["BIRTHDAY"];

    $NICK_NAME=$ROW["NICK_NAME"];
    $MINISTRATION=$ROW["MINISTRATION"];
    $MATE=$ROW["MATE"];
    $CHILD=$ROW["CHILD"];

    $DEPT_NAME=$ROW["DEPT_NAME"];
    $ADD_DEPT=$ROW["ADD_DEPT"];
    $POST_NO_DEPT=$ROW["POST_NO_DEPT"];
    $TEL_NO_DEPT=$ROW["TEL_NO_DEPT"];
    $FAX_NO_DEPT=$ROW["FAX_NO_DEPT"];

    $ADD_HOME=$ROW["ADD_HOME"];
    $POST_NO_HOME=$ROW["POST_NO_HOME"];
    $TEL_NO_HOME=$ROW["TEL_NO_HOME"];
    $MOBIL_NO=$ROW["MOBIL_NO"];
    $BP_NO=$ROW["BP_NO"];
    $EMAIL=$ROW["EMAIL"];
    $OICQ_NO=$ROW["OICQ_NO"];
    $ICQ_NO=$ROW["ICQ_NO"];
    $PSN_NO=$ROW["PSN_NO"];
    $NOTES=$ROW["NOTES"];
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

    if($SEX=="0")
       $SEX=_("��");
    else if($SEX=="1")
       $SEX=_("Ů");
    else
       $SEX="";
    if($BIRTHDAY=="0000-00-00")
       $BIRTHDAY="";
?>

<body class="bodycolor">
<table class="TableTop" width="100%">
   <tr>
      <td class="left"></td>
      <td class="center subject">
         <?=_("��ϵ����Ϣ")?> - <?=$PSN_NAME?>
      </td>
      <td class="right"></td>
   </tr>
</table>
<table class="TableBlock no-top-border" width="100%" align="center">
    <tr>
      <td class="TableData" width="40%" rowspan="6" style="background-color:lightcyan;">
<?
   if($ATTACHMENT_NAME=="")
      echo "<center>"._("������Ƭ")."</center>";
   else
   {
	   $URL_ARRAY = attach_url($ATTACHMENT_ID,$ATTACHMENT_NAME);
?>
       <a href="<?=$URL_ARRAY["view"]?>" title="<?=_("����鿴�Ŵ�ͼƬ")?>" target="_blank"><img src="<?=$URL_ARRAY["view"]?>" width='250' border=1 alt="<?=_("�ļ�����")?><?=$ATTACHMENT_NAME?>"></a>
<?
	}
?>	
      </td>
      <td nowrap class="TableData" width="100"> <?=_("�Ա�")?></td>
      <td class="TableData"><?=$SEX?></td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("���գ�")?></td>
      <td class="TableData"><?=$BIRTHDAY?></td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("�ǳƣ�")?></td>
      <td class="TableData"><?=$NICK_NAME?></td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("ְ��")?></td>
      <td class="TableData"><?=$MINISTRATION?></td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("��ż��")?></td>
      <td class="TableData"><?=$MATE?></td>    
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("��Ů��")?></td>
      <td class="TableData"><?=$CHILD?></td>    
    </tr>
</table>
<br>
<table class="TableTop" width="100%">
   <tr>
      <td class="left"></td>
      <td class="center subject">
         <?=_("������λ��Ϣ")?>
      </td>
      <td class="right"></td>
   </tr>
</table>
<table class="TableBlock no-top-border" width="100%" align="center">
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("��λ���ƣ�")?></td>
      <td class="TableData"><?=$DEPT_NAME?></td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("��λ��ַ��")?></td>
      <td class="TableData"><?=$ADD_DEPT?></td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("��λ�ʱࣺ")?></td>
      <td class="TableData"><?=$POST_NO_DEPT?></td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("�����绰��")?></td>
      <td class="TableData"><?=$TEL_NO_DEPT?></td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("�������棺")?></td>
      <td class="TableData"><?=$FAX_NO_DEPT?></td>
    </tr>
</table>
<br>
<table class="TableTop" width="100%">
   <tr>
      <td class="left"></td>
      <td class="center subject">
         <?=_("��ͥ��Ϣ")?>
      </td>
      <td class="right"></td>
   </tr>
</table>
<table class="TableBlock no-top-border" width="100%" align="center">
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("��ͥסַ��")?></td>
      <td class="TableData"><?=$ADD_HOME?></td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("��ͥ�ʱࣺ")?></td>
      <td class="TableData"><?=$POST_NO_HOME?></td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("��ͥ�绰��")?></td>
      <td class="TableData"><?=$TEL_NO_HOME?></td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("�ֻ���")?></td>
      <td class="TableData"><?=$MOBIL_NO?></td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("С��ͨ��")?></td>
      <td class="TableData"><?=$BP_NO?></td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("�����ʼ���")?></td>
      <td class="TableData"><?=$EMAIL?></td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("QQ��")?></td>
      <td class="TableData"><?=$OICQ_NO?></td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("MSN��")?></td>
      <td class="TableData"><?=$ICQ_NO?></td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("��ע��")?></td>
      <td class="TableData"><?=$NOTES?></td>
    </tr>
    <tr>
      <td nowrap class="TableData" colspan="2">
        <?=get_field_table(get_field_text("ADDRESS",$ADD_ID))?>
      </td>
    </tr>
</table>

  <br>
  <center><input type="button" value="<?=_("��ӡ")?>" class="BigButton" onclick="document.execCommand('Print');" title="<?=_("ֱ�Ӵ�ӡ���ҳ��")?>">&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="window.close();"></center>
<?
}
else
      Message("",_("δ�ҵ���Ӧ��¼��"));
?>
</body>
</html>