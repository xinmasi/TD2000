<form action="/general/address/private/address/query/search_submit.php"  method="post" name="form_address" target="_blank">
   <?=_("���룺")?><input type="text" name="ALL_NO" class="SmallInput" size="15"><br />
 <?=_("������")?><input type="text" name="PSN_NAME" class="SmallInput" size="15"><br />
 <?=_("��λ��")?><input type="text" name="DEPT_NAME" class="SmallInput" size="15"><br />
   <?=_("���飺")?><select name="GROUP_ID" class="SmallSelect">
      <option value="ALL_DEPT" selected><?=_("����")?></option>
      <option value="0"><?=_("Ĭ��")?></option>
<?
$query = "SELECT * from ADDRESS_GROUP where USER_ID='' order by GROUP_NAME";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $GROUP_ID=$ROW["GROUP_ID"];
   $GROUP_NAME=$ROW["GROUP_NAME"];
   $PRIV_DEPT=$ROW["PRIV_DEPT"];
   $PRIV_ROLE=$ROW["PRIV_ROLE"];
   $PRIV_USER=$ROW["PRIV_USER"];
	 if($PRIV_DEPT!="ALL_DEPT")
	 {
	    if(!find_id($PRIV_DEPT,$_SESSION["LOGIN_DEPT_ID"]) && !find_id($PRIV_ROLE,$_SESSION["LOGIN_USER_PRIV"]) && !find_id($PRIV_USER,$_SESSION["LOGIN_USER_ID"]))
	    {
	       continue;
	    }
   }

   echo '      <option value="'.$GROUP_ID.'">'.$GROUP_NAME.'</option>';

}
?>
</select><br />
 <?=_("�Ա�")?><select name="SEX" class="SmallSelect">
    <option value="ALL"><?=_("����")?></option>
    <option value="0"><?=_("��")?></option>
    <option value="1"><?=_("Ů")?></option>
  </select>&nbsp;&nbsp;
  <input type="submit" value="<?=_("��ѯ")?>" class="SmallButton" title="<?=_("ģ����ѯ")?>" name="button">
</form>