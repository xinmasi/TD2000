<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
?><style type="text/css">
<!--a            { text-decoration: none; font-size: 9pt; color: black; font-family: 宋体 }
.text        { font-size: 9pt; font-family: 宋体 }
.text1       { color: #0000A0; font-size: 11pt; font-family: 宋体 }
.text2       { color: #008080; font-size: 9pt; font-family: 宋体 }
.text3       { color: #0F8A91; font-size: 11pt; font-family: 宋体 }
.l100        { line-height: 14pt; font-size: 9pt }
td           { font-family: 宋体; font-size: 9pt; line-height: 13pt }
input        { font-size: 9pt; font-family: 宋体 }
p            { font-size: 9pt; font-family: 宋体 }
--></style><BODY class="bodycolor">

<BR>
<table width="500" class="TableBlock" align="center">
  <tr> 
    <td height="27" class="TableHeader">
      <div align="center">定期租船合同（二）</font></div>
    </td>
  </tr>
  <tr>
    <td height="54" valign="top" class="TableData">
      <p align="center"></p>
      <p align="center"></p>
      <p align="center"> </p>
      <p> 　　<BR> 
　　（波尔太摩格式）<BR> 
　　（１９７４年７月１日最新修订）<BR> 
　　<BR> 
　　此格式由英国航运总会文件委员会和东京航运交易所文件委员会采纳。<BR> 
　　经１９０９年２月６日、１９１１年３月１３日、１９１２年３月６日、１９２０年６月１０日、<BR> 
　　１９３９年３月１日、１９５０年１月１日和１９７４年７月１日修订。<BR> 
　　<BR> 
　　第一部分<BR> 
　　<BR> 
　　┌─────────────────┬────────────────┐<BR> 
　　│1.船舶经纪人　　　　　　　　　　　│波罗的海国际航运公会　　　　　　│<BR> 
　　│　　　　　　　　　　　　　　　　　│统一定期租船合同(各栏于1974年列 │<BR> 
　　│　　　　　　　　　　　　　　　　　│出)　　　　　　　　　　　　　　 │<BR> 
　　│　　　　　　　　　　　　　　　　　│代号：“1939年波尔太摩”　　　　│<BR> 
　　│　　　　　　　　　　　　　　　　　├────────────────┤<BR> 
　　│　　　　　　　　　　　　　　　　　│2.地点和日期　　　　　　　　　　│<BR> 
　　├─────────────────┼────────────────┤<BR> 
　　│3.船舶所有人/营业所在地　　　　　 │4.承租人/营业所在地　　　　　　 │<BR> 
　　├─────────────────┼────────────────┤<BR> 
　　│5.船名　　　　　　　　　　　　　　│6.总登记吨/净登记吨　　　　　　 │<BR> 
　　├─────────────────┼────────────────┤<BR> 
　　│7.船级　　　　　　　　　　　　　　│8.指示马力　　　　　　　　　　　│<BR> 
　　├─────────────────┼────────────────┤<BR> 
　　│9.运输部夏季干舷高度时的总载重吨( │　　　　　　　　　　　　　　　　│<BR> 
　　│大约)　　　　　　　　　　　　　　 │10.散装/包装容积(立方英尺)　　　│<BR> 
　　├─────────────────┤　　　　　　　　　　　　　　　　│<BR> 
　　│11.永久性燃料(大约)　　　　　　　 │　　　　　　　　　　　　　　　　│<BR> 
　　├─────────────────┴────────────────┤<BR> 
　　│12.船速(节)(大约)燃油消耗(吨)(大约)　　　　　　　　　　　　　　　　 │<BR> 
　　├──────────────────────────────────┤<BR> 
　　│13.现在动态　　　　　　　　　　　　　　　　　　　　　　　　　　　　 │<BR> 
　　├─────────────────┬────────────────┤<BR> 
　　│14.租期(第1条)　　　　　　　　　　│15.交船港口(第1条)　　　　　　　│<BR> 
　　│　　　　　　　　　　　　　　　　　├────────────────┤<BR> 
　　│　　　　　　　　　　　　　　　　　│16.交船时间(第1条)　　　　　　　│<BR> 
　　├─────────────────┴────────────────┤<BR> 
　　│17.(a)航行区域(2条) (b)经特别约定的除外货物　　　　　　　　　　　　 │<BR> 
　　├──────────────────────────────────┤<BR> 
　　│18.交船时的燃料(载明最低和最大数量)(第5条)　　　　　　　　　　　　　│<BR> 
　　├─────────────────┬────────────────┤<BR> 
　　│19.租金(第6条)　　　　　　　　　　│20.租金的支付(载明货币名称和支付│<BR> 
　　│　　　　　　　　　　　　　　　　　│地点,以及受益人和银行帐号(第6条)│<BR> 
　　├─────────────────┼────────────────┤<BR> 
　　│21.还船地点或区域(第7条)　　　　　│22.战争(仅当约定第(C)款时填入)第│<BR> 
　　│　　　　　　　　　　　　　　　　　│(21条)　　　　　　　　　　　　　│<BR> 
　　├─────────────────┼────────────────┤<BR> 
　　│23.解约日(第22条)　　　　　　　　 │24.仲裁地点(仅当约定伦敦以外地点│<BR> 
　　│　　　　　　　　　　　　　　　　　│时填入)(第23条)　　　　　　　　 │<BR> 
　　├─────────────────┼────────────────┤<BR> 
　　│25.经纪人佣金及向何人支付(第25条) │26.有关约定的特别规定的附加条款 │<BR> 
　　│　　　　　　　　　　　　　　　　　│数　　　　　　　　　　　　　　　│<BR> 
　　└─────────────────┴────────────────┘<BR> 
　　兹相互同意应按本租船合同第一部分和第二部分所订条件，履行本合同。当条件发生抵触时，第一<BR> 
　　部分的规定优先于第二部分，但以所抵触的范围为限。<BR> 
　　┌─────────────────┬────────────────┐<BR> 
　　│签字（船舶所有人）　　　　　　　　│签字（承租人）　　　　　　　　　│<BR> 
　　│　　　　　　　　　　　　　　　　　│　　　　　　　　　　　　　　　　│<BR> 
　　│　　　　　　　　　　　　　　　　　│　　　　　　　　　　　　　　　　│<BR> 
　　└─────────────────┴────────────────┘　　<BR> 
　　第二部分<BR> 
　　<BR> 
　　“波尔太摩１９３９”统一定期租船合同（各栏于１９７４年列出）<BR> 
　　兹由第３栏所列的船舶所有人和第４栏所列的承租人，双方协议如下：船名见第５栏，登记总／净<BR> 
　　吨见第６栏，船级见第７栏，指示马力见第８栏，在英国运输部夏季干舷高度时可装的包括燃料、物<BR> 
　　料、食品和锅炉用水在内的载重量大约吨数见第９栏，根据建造者的测定，船舶具有的散装／包装容积<BR> 
　　（立方英尺）见第１０栏，扣除永久性燃料，其大约吨数见第１１栏，船舶在良好天气和平静水域条件<BR> 
　　下满载航行速度见第１２栏，此时消耗优质威尔士煤或燃油的大约吨数见１２栏，现在动态见第１３<BR> 
　　栏。<BR> 
　　１．租期／交船地点／交船时间<BR> 
　　船舶所有人出租、承租人承租本船的期间为第１４栏规定的日历数，并在上午９点至下午６点，星<BR> 
　　期六从上午９点至下午２点（不包括星期天或法定节假日，除非承租人接受船舶），在第１５栏规定的<BR> 
　　港口、承租人所指定的船舶能始终安全浮泊并可供使用的泊位交船且将船舶置于承租人控制时起算。船<BR> 
　　舶在各方面适于货物运输。<BR> 
　　船舶应在第１６栏规定的时间内交付。<BR> 
　　２．贸易<BR> 
　　本船只能在第１７栏规定的地域内，在其能始终安全浮泊的良好和安全港口或地点之间从事合法贸<BR> 
　　易，运输合法货物。<BR> 
　　船舶不得装运活牲畜及有害的、易燃或危险货物（如酸性物质、炸药、电石、硅铁、石脑油、汽<BR> 
　　油、焦油或其它任何制品）。<BR> 
　　３．船舶所有人提供的项目<BR> 
　　船舶所有人应提供所有食品和船员工资、船舶保险、甲板和机舱所有物料并支付费用，并且在租期<BR> 
　　内，维持船体和船机处于充分有效状态。<BR> 
　　船舶所有人应为每一舱口提供一名起货机司机。如需更多的起货机司机，或卸载工人拒绝或不允许<BR> 
　　与船员一起工作，则承租人应提供合格的岸上起货司机并支付费用。<BR> 
　　４．承租人应提供的项目<BR> 
　　承租人应提供并支付所有包括厨房用煤在内的燃煤、燃油、锅炉用水、港口费用、引航费（不论强<BR> 
　　制引航与否），运河舵工、舢板费、照明、拖轮、领事费（但与船长、船员有关的除外）、运河、码头<BR> 
　　及其它费用，包括任何外国市政税和国税，同时应支付交船港和还船港的一切码头、港口费和吨税（除<BR> 
　　非是因在交船前或还船后所运的货物而发生的）、代理费、佣金，并安排和支付装载、平舱、积载（包<BR> 
　　括垫舱物料和防移板，但船上已备有的除外）、卸载、过磅和理货、交货、舱口检验，供给为其服务的<BR> 
　　官员和人员的伙食以及其它各项费用，包括因检疫造成的滞留及费用（包括熏舱和消毒费用）。<BR> 
　　所有实际用于装货和卸货的绳索、吊货网和特殊吊货索，以及任何特殊工具，包括按港口习惯要求<BR> 
　　用于系泊的特殊缆绳、钢索和锚链，均由承租人负责。船舶应配备有起货机、吊杆、滑轮和起重能力达<BR> 
　　２吨的普通吊货索。<BR> 
　　５．燃料<BR> 
　　承租人在交船时和船舶所有人在还船时应接受船上尚存的全部燃煤或燃油，并按各自港口的现价支<BR> 
　　付费用。<BR> 
　　在还船时，船上剩余燃煤或燃油的数量不得少于或超过１８栏规定的吨数。<BR> 
　　６．租金<BR> 
　　自按第一条规定起算至船舶交还船舶所有人止，承租人应以第１９栏规定的租金，每３０天支付租<BR> 
　　金。<BR> 
　　支付租金须用第２０栏规定的货币，以该栏规定的方式，无折扣地每３０天现金预付。<BR> 
　　如果欠付租金，船舶所有人有权将船舶从承租人的营运中撤回，而无须提出任何声明，也无须通过<BR> 
　　任何法院或其它形式的干预，且不影响船舶所有人根据本租船合同所具有的对承租人的任何索赔权利。<BR> 
　　７．还船<BR> 
　　租期届满时，承租人应在第２１栏规定的地点或范围内选择一不冻港，以船舶交给他时的同样良好<BR> 
　　状态（自损耗损除外），在上午９点至下午６点，星期六在上午９点至下午２点之间还船，但是还船之<BR> 
　　日不应是星期日或法定节假日。<BR> 
　　通知<BR> 
　　承租人应至少在１０天前将还船的港口和大约日期通知船舶所有人。<BR> 
　　如果对船舶指定航次将超过租期，承租人可使用船舶至航次结束，但以能合理地估计该航次将允许<BR> 
　　大约在租期届满时还船为限。但是，对于任何超过租期届满之日的时间，当市场价格高于合同规定的租<BR> 
　　金率时，承租人应按市场价格支付。<BR> 
　　８．载货舱室<BR> 
　　船舶所有载货舱容，包括合法的甲板载货容积，除保留船长、船员、属具、船具、家具、食品和物<BR> 
　　料占用的适当而足够的空间外，均由承租人支配。<BR> 
　　９．船长<BR> 
　　船长应尽速完成所有航次，并同海员一起给承租人提供习惯性帮助。有关雇佣、代理或其它安排，<BR> 
　　船长应服从承租人的指示。因船长、高级船员或代理人签发提单或其它文件，或在其它方面遵照承租人<BR> 
　　的指示，以及由于船舶证书不规范或货物超载而引起的各种后果和责任，承租人应向船舶所有人赔偿。<BR> 
　　船舶所有人对由于货物短缺、混票、标志、包件数、或由于积载不当或其它原因引起的货物损坏或索<BR> 
　　赔，均不负责任。<BR> 
　　如果承租人有理由对船长、高级船员和轮机员的行为表示不满时，船舶所有人在收到意见书后，应<BR> 
　　立即调查事实，并在必要与可能时予以撤换。<BR> 
　　１０．指示和航行日志<BR> 
　　承租人应向船长提供各种指示和航行指令。船长和轮机员应对航行日志作完整和正确记录，供承租<BR> 
　　人或其代理人查阅。<BR> 
　　１１．停租等<BR> 
　　（ａ）如船舶进干坞或为维持船舶的效能而采取其它必要措施、船员或物料不足、机器损坏、船体<BR> 
　　受损或其它事故，以致妨碍或阻碍船舶工作并持续２４小时以上，则对船舶因此所损失的不能立即按要<BR> 
　　求进行营运的任何时间，应停付租金。预付的租金应作相应扣减。<BR> 
　　（ｂ）如船舶由于天气原因被迫驶往港口或锚地，或船舶驶往浅水港口、河流或有浅滩的港口，或<BR> 
　　货物遭受意外事故，则船舶的任何延误和／或由此延误而引起的费用，均由承租人负责，即使此种延误<BR> 
　　和／或费用、或者两者发生的原因是由于船舶所有人的雇佣人员的疏忽。<BR> 
　　１２．清洗锅炉<BR> 
　　清洗锅炉应在营运期间任何可能的时候进行。如不可能，则承租人应给予船舶所有人必要的清洗时<BR> 
　　间。如船舶因此停留超过４８小时，则应停付租金，直至船舶重新准备就绪。<BR> 
　　１３．责任和负责<BR> 
　　船舶所有人对由于其本人或其经理人未尽适当谨慎使船舶适航和适于航次需要，或因其本人或其经<BR> 
　　理人本身的行为，疏忽或不履行职责造成的交船延误或租期内的延误，以及船上货物的灭失或损坏负<BR> 
　　责。在任何其它情况下，船舶所有人对任何原因造成的任何损坏或延误，即使是由于其雇佣人员的疏忽<BR> 
　　或不履行职责所致，概不负责。船舶所有人对无论是部分或全面的罢工、停工或关厂或禁止工作（包括<BR> 
　　船长、高级船员和普通船员）所引起或造成的灭失或损坏，概不负责。<BR> 
　　承租人应对由于违反本租船合同的规定装载的货物或不当或粗心添加燃料、装载、积载和卸载货<BR> 
　　物，或其他由本身或其雇佣人员的不当或疏忽行为所造成的对船舶或船舶所有人的灭失或损坏负责。<BR> 
　　１４．垫款<BR> 
　　如经要求，承租人或其代理人应在任何港口向船长垫付船舶的日常费用所必需的款项，年息仅为<BR> 
　　６％。此项垫款从租金中扣除。<BR> 
　　１５．除外港口<BR> 
　　船舶不能被指示也无义务进入：<BR> 
　　（ａ）伤寒或瘟疫盛行的任何地区，或船长、高级船员根据法律无义务将船舶驶往的任何地区。<BR> 
　　（ｂ）任何冰封地区或在船舶抵达时由于冰冻原因，灯标、灯船、航行标志和浮标将被或可能被撤<BR> 
　　走的任何地区，或具有通常由于冰冻原因船舶不能抵达或在装货或卸货结束后不能驶离的危险的地区。<BR> 
　　船舶无义务破冰。如果由于冰冻原因，船长担心船舶被冰封和／或受损，因而认为继续停留在装货或卸<BR> 
　　货地点具有危险，则他有权将船舶驶往一便利的不冻地点，等待承租人新的指示。<BR> 
　　由于上述任何原因造成的不可预见的延误，由承租人负责。<BR> 
　　１６．船舶灭失<BR> 
　　一旦船舶灭失或失踪，租金从船舶灭失之日起停付，如灭失的日期不能确定，则从船舶最后一次报<BR> 
　　告之日起到预计的抵达目的地之日止，支付半数租金。任何预付的租金应作相应的扣减。<BR> 
　　１７．加班<BR> 
　　如经要求，船舶应昼夜进行作业，承租人应偿还船舶所有人根据加班的小时数和雇佣合同中规定的<BR> 
　　费率支付给高级船员和普通船员的加班费用。<BR> 
　　１８．留置权<BR> 
　　船舶所有人得因根据本租船合同提出的任何索赔对属于定期承租人的所有货物和转手运费以及任何<BR> 
　　提单运费行使留置权。承租人得因事先支付但未获得的所有款项而对船舶有留置权。<BR> 
　　１９．救助报酬<BR> 
　　由于对其它船舶的救助和救援而获得所有报酬，在扣除船长和船员的应得份额以及所有法定的和包<BR> 
　　括根据本租船合同为救助所损失的时间而支付的租金在内的其它费用，以及损害修理和所消耗的燃煤或<BR> 
　　燃油后，由船舶所有人和承租人平分。承租人应受船舶所有人为得到救助报酬及确定其数额而采取的措<BR> 
　　施的约束。<BR> 
　　２０．转租<BR> 
　　承租人有权转租船舶，但应给予船舶所有人预期通知。但是，最初的承租人始终对本租船合同的适<BR> 
　　当履行向船舶所有人负责。<BR> 
　　２１．战争<BR> 
　　（ａ）除非事先征得船舶所有人同意，否则，如由于任何实际或威胁中的战争行为，战争的敌对行<BR> 
　　动、类似战争的行动、海盗行为或敌对行为、或任何人、组织或国家对本船或任何其它船舶或其货物的<BR> 
　　恶意破坏，革命、内战、民众骚动或因实施国际法而将使船舶进入危险地区，船舶不能被指示前往或继<BR> 
　　续驶往任何此种地区或进行此种航次或营运，也不能因实施某种制裁使船舶冒任何风险或受任何惩罚，<BR> 
　　亦不能使船舶承运任何货物而使其有被扣押、捕获、罚款的风险，或受交战国或交战方或任何政府或统<BR> 
　　治者的任何方式的干扰。<BR> 
　　（ｂ）如船舶驶近或被带往或被指示驶往该种地区或面临上述风险，（１）船舶所有人有权随时按<BR> 
　　其认为适当的条件，对其在船舶上的利益和／或租金因此可能涉及的任何风险进行投保。如经要求，承<BR> 
　　租人应偿还船舶所有人支付的保险费。并且，（２）尽管有第１１条的规定，对所损失的全部时间，包<BR> 
　　括由于船长、高级船员或普通船员的伤亡或船员拒绝前往该地区或置于此种危险所损失的时间，租金照<BR> 
　　付。<BR> 
　　（ｃ）如船长、高级船员和／或普通船员的工资、食品和／或甲板和／或机舱物料的费用和／或船<BR> 
　　舶保险费，由于上述（ａ）款所述情况或在任何此种情况存在期间而有所增加，所增加的费用应加入租<BR> 
　　金，关在船舶所有人提交帐单时，由承租人支付。此种帐单应按提交。<BR> 
　　（ｄ）船舶有权服从船旗国政府或任何其它政府，或任何按照或声称按照该政府授权的行事的人发<BR> 
　　出的有关船舶离港、抵港、航线、挂港、停港、目的港、交货或任何其它事项的命令或指示，或服从根<BR> 
　　据船舶战争险条款任何委员会或个人有权发出的此种命令或指示。<BR> 
　　（ｅ）如船旗国卷入战争、敌对行动，类似战争的行动、革命或民众骚动之中，船舶所有人和承租<BR> 
　　人均可解除本租船合同。除另有协议者外，船舶应在目的港还给船舶所有人，或者，如由于（ａ）款规<BR> 
　　定的情况，船舶不能抵达或进入目的港，则在卸完船上任何货物后，在船舶所有人选择的附近一未封锁<BR> 
　　的安全港口还船。<BR> 
　　（ｆ）为服从本条规定而为或不为任何行为，均不得视为违约。<BR> 
　　（ｇ）款供选择，并且，除非根据第２２栏另有约定外，否则应视为删除。<BR> 
　　２２．解约<BR> 
　　如船舶未能在第２３栏规定的日期之前交付，承租人有权解除合同。<BR> 
　　如船舶未能在解约日之前交付，则如经要求，承租人应在收到此种通知后４８小时内作出其解除合<BR> 
　　同还是接受船舶的声明。<BR> 
　　２３．仲裁<BR> 
　　本租船合同引起的任何争议应在伦敦（或第２４栏约定的其它地点）提交仲裁。船舶所有人指定一<BR> 
　　名仲裁员，另一名仲裁员由承租人指定，如该两名仲裁员不能达成一致决定，则以由其指定的首席仲裁<BR> 
　　员的决定为准。仲裁员或首席仲裁员的裁决为终局，对双方均有约束力。<BR> 
　　２４．共同海损<BR> 
　　共同海损根据１９７４年约克－－安特卫普规则进行理算，租金不分摊共同海损。<BR> 
　　２５．佣金<BR> 
　　船舶所有人按根据本租船合同支付的租金，以第２５栏规定的费率，向第２５栏规定的当事人支付<BR> 
　　佣金。但在任何情况下，佣金不少于补偿经纪人的实际开支和其工作的合理报酬的必要数额。如由于任<BR> 
　　何当事一方违约而全部租金未付，则对此负责的一方应赔偿经纪人的佣金损失。<BR> 
　　如当事双方协议解除本租船合同，则船舶所有人应赔偿经纪人的佣金损失。但在此种情况下，佣金<BR> 
　　不超过按一年的租金计算的经纪人费用。<BR> 
　　<BR> 
　　油轮程租船合同<BR> 
　　<BR> 
　　现有蒸汽／摩托油轮＿＿＿＿号，悬挂＿＿＿＿旗，＿＿＿＿净登记吨，有大约装油＿＿＿＿容<BR> 
　　积，和船级＿＿＿＿，现正在＿＿＿＿的船东＿＿＿＿与租船人＿＿＿＿于本日相互达成协议。<BR> 
　　１．本船应紧密、坚实、牢固，在各方面适用于本航次，在本航次中保持如此状态，除海上风险<BR> 
　　外，应尽快开航和驶往＿＿＿＿或其附近可使船安全到达（经常浮起）的地方，并按租船人要求装满全<BR> 
　　部的＿＿＿＿吨散装货物，但不得超过除属具、船具、给养和设备之外本船可以合理地积载和装运的数<BR> 
　　量（应在可伸缩的油舱中给货物的伸缩部分留出足够的舱容），将如此装载的货物驶往（如提单所指定<BR> 
　　的）＿＿＿＿或其附近可使船安全到达（经常浮起）的地方，并按装货时每吨（２０英担）支付<BR> 
　　＿＿＿＿运费后，如数交货。<BR> 
　　２．运费应在交货之时，在伦敦无折扣地以现金支付，扣除一切在装港给船长的借支和有关保险<BR> 
　　费。如果需要，在卸港的港口费用应以现行的汇率用现金垫付。<BR> 
　　３．由于货物原因而产生的税和其他费用应由租船人支付，由于船舶原因而产生的税和其他费用应<BR> 
　　由船东支付。<BR> 
　　４．货物泵入船舱由租船人承担费用和风险，货物泵出船舱由船方承担费用，但船方的风险仅限于<BR> 
　　船舷以内。如果装卸港口的规定允许船舶生火，船方提供油泵和所需蒸汽以及人员操作。如果港口不允<BR> 
　　许船舶生火，租船人应解决装卸货所需要的蒸汽和承担费用。<BR> 
　　５．租船人可允许用＿＿＿＿晴天连续小时（星期日和节假日除外，除非已使用或船舶已经滞期）<BR> 
　　进行装卸作业。租船人有权在晚上或除外时间进行装卸，但应支付由此而产生的额外费用。<BR> 
　　６．船舶应按租船人的指示，去到达一个能使其经常浮起的地方或码头或驳船旁边装卸货，但任何<BR> 
　　可能产生的驶船费和风险由租船人承担。租船人有权在卸港将船舶从一个卸货泊位移到另一个卸货泊<BR> 
　　位，并承担所发生的一切费用。<BR> 
　　７．装卸时间应从船舶准备好接货或卸货时起算，船长给租船人代理６小时通知，无论是否靠泊。<BR> 
　　８．滞期费应按每连续日支付＿＿＿＿，不足一日者按比例计算，但如果在装港或卸港由于发生意<BR> 
　　外事故或机器故障所导致的延误时间，租船人应将滞期费减少至每连续日＿＿＿＿，不足一日者按比例<BR> 
　　计算。<BR> 
　　９．船方对天灾、海上风险、火灾、船长和船员非法行为、公敌、海盗、盗贼、统治者的拘捕或扣<BR> 
　　留、碰撞、搁浅，以及其他航行事故，即使是由于引水员、船长、船员或其他船东雇员的疏忽、过失或<BR> 
　　判断失误所造成，均不负责。船方对由于爆炸、锅炉爆炸、轴的损坏，或任何机器和船壳的潜在缺陷造<BR> 
　　成损失，只要这些不是由于船东、船舶管理人和船舶经营人未克尽职责而造成，都不负责。船方有权按<BR> 
　　任何顺序停靠任何港口，没有引水员而开航，或拖带和救助遇难船舶，或为救助生命和财产而绕航，或<BR> 
　　为船东单独的利益而救助。船方对由于罢工、停工、民事动乱、商业争端、或任何有意图或进一步行<BR> 
　　动，不论船东是否是其中一方，工人罢工、停工、骚乱或洪水，或其他事故或超出任何一方控制之外而<BR> 
　　导致阻止或延迟装卸所造成直接或间接的灭失、损坏或延迟，都概不负责。<BR> 
　　１０．如果租船人不能提供全部的货物，船方有权不开航，直到船舶油舱能够被装到适航状态，并<BR> 
　　且运费要按船舶装载全部货物来计算支付。<BR> 
　　１１．船东因未收到所有运费、空舱费、滞期费和弥补这些费用而对货物享有绝对留置权。<BR> 
　　１２．船舶允许拖带或被拖带，援助遇难的船舶，或为补充燃料煤或油而停靠任何港口。<BR> 
　　１３．本合同应受装运货物之船舶的船旗国法律管辖，除非在发生海损或共同海损时，应根据<BR> 
　　“１９５０年约克－安特卫普规则”进行解决。<BR> 
　　１４．如果由于冰冻不能进入装港或卸港，船舶应驶向最近的安全和开放可进入的港口，并把船舶<BR> 
　　到达用电报通知给租船人或收货人，租船人或收货人有义务用电报指定（由他们选择）不冰冻和有能力<BR> 
　　装或卸散油设备的港口。从船舶被冰冻停工，直到船舶到达最终港口卸完所用的全部时间应由租船人按<BR> 
　　每连续日支付＿＿＿＿，不足一日者按比例计算。<BR> 
　　１５．如果船舶到达装港或卸港外，面临船舶被冰冻的危险，船长应用电报和租船人联系，租船人<BR> 
　　也应以电报答复，并指示船舶驶往上述以外的没有冰冻危险的和有装载或／接受散油设备的一个港口，<BR> 
　　或者停留在原港口，由租船人承担风险，并赔偿船舶可能耽误的时间，按每连续日支付＿＿＿＿，不足<BR> 
　　一日者按比例计算。<BR> 
　　１６．船长应负责经常保持船舶油舱、油管和油泵的清洁，但如果租船人所装的油与以前装在油舱<BR> 
　　里的种类不同，由租船人负担费用。船方对由租方装载不同品种的油所产生的任何后果不负责任。船方<BR> 
　　对于渗漏不负责任。<BR> 
　　１７．如果租船人将船舶派往有检疫的任何港口，时间应按船舶被耽误的每小时计算，并且本合同<BR> 
　　第８条款规定的全部装卸时间已被使用完毕，但如果在船舶驶往港口途中才宣布的检疫，租船人不负责<BR> 
　　由检疫隔离所产生的延误。<BR> 
　　１８．在履行本租约合同所产生的任何争议应在伦敦解决，船东和租船人各指定一名仲裁员－－商<BR> 
　　人或经纪人，如该两名被选出的仲裁员不能达成一致决定，则应在指定一名首席仲裁员－－商人或经纪<BR> 
　　人，他们的决定为终局。如果双方中的一方在接到另一方的要求在２１天内疏忽或拒绝指定仲裁员，则<BR> 
　　单独指定的仲裁员有权自行决定，并且他的决定对双方有约束力。为了执行裁决，本协议具有法律效<BR> 
　　力。<BR> 
　　１９．不履行本租船合同的罚金应以估计的损失金额和为弥补损失产生的费用来计算。<BR> 
　　２０．船舶应通知船东在装港和卸港的代理办理海关业务。<BR> 
　　２１．受载日不得在＿＿＿＿之前，除非租船人同意。<BR> 
　　２２．如果船舶在＿＿＿＿前没作好装货准备，租船人有选择取消租船合同的权利。<BR> 
　　２３．船长签发提供给他的提单对本租船合同不受损害。<BR> 
　　２４．租船人有权转让本租船合同，但在如此情况下，原租船人应继续负责完全和真实的履行租船<BR> 
　　合同。<BR> 
　　<BR> 
　　　　 </p>
    </td>
  </tr>
</table>
<?Button_Back_Law();?></body></html>