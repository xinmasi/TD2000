<?
$MODULE_FUNC_ID="106";
$MODULE_DESC=_("公共通讯簿查询");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'info';

$MODULE_BODY.='
  <form action="/general/address/private/address/query/search_submit.php"  method="post" name="form1" target="_blank" >'.
 _("号码：").'<input type="text" name="ALL_NO" class="SmallInput" size="10" maxlength="25">&nbsp;&nbsp;'.
        _("分组：").'<select name="GROUP_ID" class="SmallSelect">
          <option value="ALL_DEPT" selected>'._("所有").'</option>
          <option value="0">'._("默认").'</option>';

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

   $MODULE_BODY.='<option value="'.$GROUP_ID.'">'.$GROUP_NAME.'</option>';

}

$MODULE_BODY.='</select><br>'.
 _("姓名：").'<input type="text" name="PSN_NAME" class="SmallInput" size="10" maxlength="25">&nbsp;&nbsp;'.
 _("单位：").'<input type="text" name="DEPT_NAME" size="10" class="SmallInput"><br>'.
 _("性别：").'<select name="SEX" class="SmallSelect">
    <option value="ALL">'._("所有").'</option>
    <option value="0">'._("男").'</option>
    <option value="1">'._("女").'</option>
  </select>&nbsp;
  <input type="submit" value="'._("查询").'" class="SmallButton" title="'._("模糊查询").'" name="button">
</form>';

?>
