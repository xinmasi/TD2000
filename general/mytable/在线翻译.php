<!--=============== Google���߷��� =====================-->
<?
$MODULE_FUNC_ID="";
$MODULE_DESC=_("�е����߷���");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'info';

$MODULE_BODY.= '
   <script type="text/javascript">
   function trans(req)
   {
      if(typeof(req) != "object")
      {
         var q = $("trans_text").value;
         if(q == "")
         {
            $("trans_result").value = "'._("������Ҫ���������").'";
            return;
         }
         
         var trans_url = "translate.php?q=" + encodeURIComponent(q);
         _get(trans_url, "", trans);
      }
      else
      {
         $("trans_result").value = req.status == 200 ? req.responseText : ("'._("����").' " + req.status);
      }
   }
   </script>
<input type="button" class="SmallButton" style="display:inline;" value="'._("����").'" onClick="trans();"></input>
<textarea id="trans_text" class="SmallInput" style="width:98%;height:'.(floor(($SHOW_COUNT*20-40)/2)).'px;background:#ffffcc;"></textarea>
<textarea id="trans_result" class="SmallInput" style="width:98%;height:'.(floor(($SHOW_COUNT*20-40)/2)).'px;background:#ffffcc;"></textarea>';
?>