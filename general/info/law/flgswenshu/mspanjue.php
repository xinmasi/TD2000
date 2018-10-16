<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
?>
<style type="text/css">
<!--a            { text-decoration: none; font-size: 9pt; color: black; font-family: 宋体 }
.text        { font-size: 9pt; font-family: 宋体 }
.text1       { color: #0000A0; font-size: 11pt; font-family: 宋体 }
.text2       { color: #008080; font-size: 9pt; font-family: 宋体 }
.text3       { color: #0F8A91; font-size: 11pt; font-family: 宋体 }
.l100        { line-height: 14pt; font-size: 9pt }
td           { font-family: 宋体; font-size: 9pt; line-height: 13pt }
input        { font-size: 9pt; font-family: 宋体 }
p            { font-size: 9pt; font-family: 宋体 }
--></style>

<BODY class="bodycolor">

<BR>
<table width="500" class="TableBlock" align="center">
  <tr> 
    <td height="27" class="TableHeader"> 
      <div align="center"><?=_("民事判决类")?></font></div>
    </td>
  </tr>
  <tr> 
    <td height="54" valign="top" class="TableData">
      <ol>
        <li><a href="mspanjue/mspanjue00.php"><?=_("民事判决书（二审维持原判或者改判用）")?></a></li>
        <li><a href="mspanjue/mspanjue01.php"><?=_("民事判决书（选民资格案件用）")?></a></li>
        <li><a href="mspanjue/mspanjue02.php"><?=_("民事判决书（确认民事行为能力用）")?></a></li>
        <li><a href="mspanjue/mspanjue03.php"><?=_("民事判决书（宣告失踪或宣告死亡用）")?></a></li>
        <li><a href="mspanjue/mspanjue04.php"><?=_("民事判决书（撤销失踪宣告或死亡宣告用）")?></a></li>
        <li><a href="mspanjue/mspanjue05.php"><?=_("民事判决书（认定财产无主案件用）")?></a></li>
        <li><a href="mspanjue/mspanjue06.php"><?=_("民事判决书（撤销有关民事行为能力的宣告用）")?></a></li>
        <li><a href="mspanjue/mspanjue07.php"><?=_("民事判决书（指定监护人案件用）")?></a></li>
        <li><a href="mspanjue/mspanjue08.php"><?=_("民事判决书（本院决定再审的案件用）")?></a></li>
        <li><a href="mspanjue/mspanjue09.php"><?=_("民事判决书（撤销认定财产无主的判决用）")?></a></li>
        <li><a href="mspanjue/mspanjue10.php"><?=_("民事判决书（当事人申请再审的案件用）")?></a></li>
        <li><a href="mspanjue/mspanjue11.php"><?=_("民事判决书（上级法院指令再审的案件用）")?></a></li>
        <li><a href="mspanjue/mspanjue12.php"><?=_("民事判决书（依照审判监督程序提审的案件用）")?></a></li>
        <li><a href="mspanjue/mspanjue13.php"><?=_("民事判决书（抗诉的再审案件用）")?></a></li>
        <li><a href="mspanjue/mspanjue14.php"><?=_("民事判决书（一审民事案件用）")?></a></li>
        <li><a href="mspanjue/mspanjue15.php"><?=_("民事判决书（公示催告程序除权用）")?></a></li>
      </ol>
      </td>
  </tr>
</table>
<?Button_Back_Law();?></body>

</html>