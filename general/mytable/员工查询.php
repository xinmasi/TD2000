<?
$MODULE_FUNC_ID="";
$MODULE_DESC=_("Ա����ѯ");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'info';

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
$MODULE_BODY.='<form action="/general/ipanel/user/search.php" name="form1" >'.
    	 _("������").'<input type="text" name="USER_NAME" class="SmallInput" size="20" maxlength="25"><br>'.
       _("���ţ�").'<select name="DEPT_ID" class="SmallSelect" style="width:128pt">
          <option value="" selected>'._("����").'</option>';
$MODULE_BODY.= my_dept_tree(0,0,'2');
$MODULE_BODY.='</select><br>'.
        _("�Ա�").'<select name="SEX" class="SmallSelect">
          <option value="" selected>'._("����").' </option>
          <option value="0">'._("��").' </option>
          <option value="1">'._("Ů").' </option>
        </select>&nbsp;';
$MODULE_BODY.='      <input type="submit" value="'._("��ѯ").'" class="SmallButton" title="'._("ģ����ѯ").'" name="button">
   </form>';
 }