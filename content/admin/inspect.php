<?php

class InspectPage extends Page {
	private static $KEYED_SLOTS = array("!quests", "!features", "!tutorial", "skills", "!kills", "!visited");
	
	private $data = '

[21:29] This release is EXPERIMENTAL. Need help? http://stendhalgame.org/wiki/AskForHelp - please report problems, suggestions and bugs. Remember to keep your password completely secret, never tell to another friend, player, or admin.
[21:29] Synchronized
[21:29] Inspecting hendrikus
hp: 29862
zoneid: int_semos_blacksmith
visibility: 100
outfit: 6089901
sentence: A famous quote.
clientid: -691359677
xp: 70
type: player
base_mana: 0
atk_xp: 3399
id: 78
karma: 87.08
atk: 17
teleclickmode: 
height: 1
level: 10
def: 379
description: You see hendrikus. He is a developer and not a player. Therefore his bank chest is completely empty. hendrikus is level 23 and has been playing 42 hours and 47 minutes.
age: 54170
name: hendrikus
db_id: 90073
resistance: 100
last_pvp_action_time: 1.19744391E12
adminlevel: 5000
invisible: 
width: 1
mana: 0
ghostmode: 
base_hp: 30570
def_item: 412
def_xp: 500002992
release: 0.86.1
atk_item: 100
y: 3
x: 26

[21:29] 
Slot !quests: 
   RPObject with Attributes of Class(): [paper_chase=Thanatos;5390;2009][armor_dagobert=done][Monogenes=done][plinks_toy=done][rainbow_beans=bought;1044987947176;taken;1244990639131][debuggera=friends][mithril_cloak=told_joke;5][Ketteh=learnt_manners][deathmatch=cancel][daily=leader kobold,0,1,0,0;1256510490887;null][id=1][meet_bunny_07=done][reverse_arrow=done][costume_party=done][sue_swap_kalavan_city_scroll=1;kalavan city scroll;1212092459599][seven_cherubs=;Cherubiel][meet_hayunn=start;rat,0,1,0,0][db_id=90075][AenihataFirstChat=done][TadFirstChat=done] and RPSlots  with maps and RPLink  and RPEvents 

[21:29] 
Slot !kills: 
   RPObject with Attributes of Class(): [solo.boar=1][shared.wolf=2][shared.caverat=1][solo.soldier kobold=3][solo.green dragon=1][solo.rat=19][id=0][solo.chicken=1][solo.veteran kobold=3][solo.orc warrior=2][solo.archer kobold=1][solo.troll=1][solo.snake=1][90076.db id=1][solo.cobra=1][solo.wolf=5][shared.rat=1][solo.caverat=10][solo.venomrat=2][solo.goblin=2] and RPSlots  with maps and RPLink  and RPEvents 

[21:29] 
Slot skills: 
   RPObject with Attributes of Class(): [id=4][db_id=90078] and RPSlots  with maps and RPLink  and RPEvents 

[21:29] 
Slot spells: 

[21:29] 
Slot !visited: 
   RPObject with Attributes of Class(): [0_semos_road_se=1275683336396][int_ados_house_62_1=1275683233815][int_nalwor_house1_basement=1278312070505][0_ados_city_n2=1275683339702][int_kirdneh_house_41=1257118531116][int_nalwor_weaponshop=1275683286756][int_kirdneh_house_42_1=1275683326480][int_kirdneh_house_42=1257118980997][giovanote_adventure_island=1262128814365][int_kirdneh_house_48_1=1257118666836][int_kirdneh_house_43=1275683098202][int_kirdneh_house_44=1276373645473][int_kirdneh_house_45=1257118878480][int_kirdneh_house_47=1275683121373][int_kirdneh_house_49=1275683161066][-1_semos_mine_n_e2=1196887081462][int_semos_house=1282242570916][int_0_nalwor_city_house_70=1257118957849][int_ados_bakery=1257118458314][int_ados_library=1275683313254][-1_amazon_tunnel_e=1275683170994][0_orril_river_s_w2=1276373686647][int_sedah_storage=1257118984303][int_ados_felinas_house=1257119146310][int_athor_dressing_room_m=1257118861948][-2_semos_mine_e=1257118838806][-1_semos_mine_n2_e2=1275683369478][int_kirdneh_house_28=1257119126471][int_kirdneh_house_27=1257118577398][-4_orril_dungeon=1275683389315][int_ados_fishermans_hut_north=1257118805744][-3_ados_outside_nw=1257118676755][0_ados_mountain_n_w2=1270398462513][int_nalwor_bank_1=1257118514588][-1_orril_dungeon=1257118375650][int_kirdneh_house_34=1257118388881][0_orril_river_s=1213825422551][int_ados_reverse_arrow=1280603102009][int_kirdneh_house_31=1275683300032][int_kirdneh_house_39=1257118484827][int_ados_bar_1=1275683180912][-1_ados_wall=1257119053735][int_kirdneh_house_37=1275683204063][int_kirdneh_house_36=1257118891718][-3_semos_jail=1267019863728][0_ados_wall_n=1280170484892][int_amazon_prison_hut=1257118603846][int_ados_meat_market=1257118564174][int_athor_dressing_room_f=1257118620375][0_ados_wall_s=1261300220933][int_kalavan_house_17=1257118967772][int_kalavan_house_18=1257118729677][int_kalavan_house_15=1257118468234][-1_fado_great_cave_nw=1257119004144][int_kalavan_house_16=1275683366170][int_kalavan_house_13=1257119136393][int_kalavan_house_12=1257119066962][int_kalavan_house_10=1275683111431][0_orril_castle=1212269089523][int_fado_church=1275683309949][6_kikareukin_islands=1270414132562][int_magic_lounge=1275683174300][db_id=90079][0_nalwor_city=1275683004517][int_adventure_island=1275683041947][0_nalwor_river_s=1257118335970][int_fado_woodcutter_lodge=1257118309519][int_kalavan_house_24=1275683362860][int_kalavan_house_25=1257118574093][int_ados_house_50_1=1257118650302][int_kalavan_house_20=1257119027285][int_magic_school_cellar=1275683296726][int_kalavan_house_21=1257118739592][int_kalavan_house_22=1257118643699][int_ados_house_51_1=1257118656914][int_kalavan_house_23=1257118961155][0_ados_ocean_e=1275683352933][int_wofol_bakery=1257118355808][-3_orril_dungeon=1275740503301][int_sedah_house6=1257118302905][0_semos_mountain_n2_e=1275683071745][int_sedah_house2=1257119080184][int_sedah_house5=1257118845417][int_ados_tavern_0=1261302445347][int_fado_elf_hut_2=1275683114760][int_fado_elf_hut_1=1257119093407][int_sedah_house1=1275683184218][int_fado_elf_hut_3=1257118789186][int_sedah_house0=1257118792499][0_fado_forest_s=1257119090103][0_ados_wall=1269803120001][0_semos_mountain_n2_w=1280938740679][0_ados_outside_w=1261300133472][0_fado_forest_e=1257118914863][int_semos_library=1277674599048][int_kalavan_castle=1257118537727][-1_nalwor_drows_tunnel_n=1257118944625][0_ados_rock=1267875492893][-1_semos_yeti_cave=1257118762738][-1_ados_outside_w=1270413598817][-2_kotoch_entrance=1275683131290][-2_orril_dungeon_e=1257118660221][0_semos_village_w=1280938585594][-1_fado_great_cave_n_w2=1197593282321][int_nalwor_library_1=1257118918169][int_ados_bank=1270399169030][int_athor_labyrinth_entrance=1257118471538][-1_ados_caves_s=1275683197441][int_fado_tavern=1257280177341][int_ados_house_60_1=1257118418645][int_ados_haunted_house_basement=1257118818970][0_kalavan_city_gardens=1267453982597][int_vault=1257118752819][int_kirdneh_inn=1278312452692][-2_semos_catacombs=1275683359545][0_semos_city=1280938344853][-1_ados_caves_e=1257118723066][0_ados_mountain_n2=1275683333090][0_ados_rock_w=1268052084786][int_ados_farm_house=1257118947931][-1_nalwor_drows_tunnel=1270419767880][int_ados_twilight_zone=1257118858642][-1_ados_outside_nw=1257118795815][-1_semos_jail=1277129993139][0_ados_city_s=1269588407069][bigmomma_adventure_island=1257279985627][0_ados_mountain_nw=1270403477494][0_nalwor_forest_w=1275683005392][0_ados_city_n=1275683177606][-1_fado_great_cave_w2=1257119143004][int_magic_clothing_boutique=1254685984253][0_nalwor_river_se=1257118871873][int_fado_bakery=1257118557563][0_ados_mountain_n2_w=1257118699903][int_magic_bric_a_brac=1257118392187][int_nalwor_inn_1=1257118593928][int_nalwor_bank=1257118461620][hell=1277028452035][0_nalwor_forest_n=1257119119890][-4_ados_abandoned_keep=1257118584008][0_orril_mountain_w=1276373703858][-1_nalwor_caves=1275683045306][-6_ados_abandoned_keep=1275683243738][0_semos_mountain_n_w4=1270937035916][0_semos_mountain_n_w3=1270935384549][0_semos_plains_n_e2=1275683349627][int_ados_house_61_1=1257118627157][0_amazon_island_se=1257118640381][int_athor_cocktail_bar=1257118415334][0_athor_island_w=1275683055219][int_ados_town_hall_3=1264276623072][int_ados_town_hall_2=1264261318969][int_ados_town_hall_1=1264275618359][0_semos_mountain_n_w2=1281200368805][int_magic_theater=1275683319865][int_nalwor_royal_hall_1=1257118759431][int_fado_house=1257280169223][0_ados_river_s2_w2=1275683207370][0_ados_coast_sw=1257118742899][0_athor_island_e=1257118306213][int_nalwor_tower_4=1275683293367][int_nalwor_tower_5=1257118597234][int_wofol_weaponshop=1257118865255][int_nalwor_tower_6=1257118769349][int_fado_battle_pit=1262807991627][0_orril_forest_n=1257885430872][int_semos_frank_house=1270414279548][int_mithrilbourgh_throne_room=1275683386007][int_nalwor_tower_0=1257118547646][int_nalwor_tower_3=1275683230510][0_ados_coast_s_w2=1257118451702][int_semos_storage_0=1275683094896][int_rat_bakery=1275683134598][int_rat_library=1275683283437][int_orril_jynath_house=1275683220594][-1_semos_caves=1270251473806][0_athor_island=1260744349248][-3_orril_dwarf_blacksmith=1257119050430][0_ados_mountain_n2_w2=1270398068252][-1_nalwor_drows_tunnel_nw=1275683091596][-1_ados_city=1275683260284][0_kalavan_city=1270403268085][id=5][int_fado_weaponshop=1257280096771][0_fado_forest_se=1260703940907][-1_nalwor_drows_tunnel_ne=1257118474845][int_ados_castle_dungeons=1257119116550][-1_athor_ship_w2=1267227168756][int_magic_school_1=1270251592696][-2_semos_mine_n2_e=1257118590622][0_semos_mountain_w2=1275760128460][-1_ados_caves_se=1267868630226][int_magic_school_0=1270308077847][-1_orril_mountain_w2=1257118342582][int_kirdneh_museum=1257118527810][int_ados_bar=1275683270205][int_sedah_mausoleum=1275683346319][int_ados_farm_house_1=1275683151147][-1_orril_castle_w=1257118501364][int_fado_lovers_room_10=1275683068438][int_fado_lovers_room_13=1275683075049][int_fado_lovers_room_12=1257118570786][int_fado_lovers_room_15=1257118299598][int_fado_lovers_room_14=1257118964461][shadon_vault=1275682269803][int_kalavan_house_3=1257119063656][int_fado_lovers_room_0=1275683379396][-1_semos_mine_n2_w2=1257118326050][int_wofol_house10=1257118954548][int_kalavan_house_5=1275683190830][int_kirdneh_river_house_1=1257118683367][int_kalavan_house_4=1257118345888][int_kirdneh_river_house_0=1257118696595][0_semos_plains_ne=1277554583840][int_kalavan_house_1=1257118842110][-2_orril_dungeon=1275683329785][-1_ados_wall_n=1257118719763][int_nalwor_pottery=1257118716453][int_ados_zaras_house_0=1270398848575][int_fado_lovers_room_9=1275683164374][int_rat_house5=1275683276818][int_fado_lovers_room_8=1257118445090][int_ados_zaras_house_1=1275683210675][int_fado_lovers_room_7=1257118815662][int_rat_house2=1275732503894][int_fado_lovers_room_5=1257119073574][int_fado_lovers_room_2=1257118934704][int_fado_lovers_room_1=1257119037201][int_rat_house6=1275683240432][int_semos_hostel=1263071965611][int_pathfinding=1257118898328][int_ados_town_hall=1264262102594][0_orril_mountain_w2=1257118511282][int_kirdneh_house_37_basement=1257118680060][int_kalavan_house_8=1257119086797][int_kalavan_house_9=1257118931399][int_kalavan_house_6=1257118726372][-2_semos_dungeon=1275683263589][int_kalavan_house_7=1257118431867][7_kikareukin_clouds=1266680835197][0_orril_forest_e=1275683084977][int_nalwor_inn_basement=1257118904942][int_fado_bank=1275683306642][int_wofol_blacksmith=1257118534422][int_ados_sewing_room=1270398710172][-2_semos_mine_w2=1261319253485][0_orril_mountain_n2_w2=1257119060349][int_fado_shepherd_house=1262901137525][1_kikareukin_cave=1257118455008][-3_kotoch_prison=1257118901635][int_kirdneh_bank=1275683247046][-1_semos_mine_nw=1257118428561][-1_ados_caves=1270398447121][int_sedah_barracks_0=1275683217288][int_ados_castle_left=1257118524503][int_ados_castle_right=1275683253658][int_ados_haunted_house=1257118521199][int_semos_townhall=1280508382353][int_kalavan_greenhouse_2=1257118560868][0_ados_outside_nw=1276496857821][-3_semos_dungeon=1275683372782][int_wofol_bar=1257119139699][int_ados_ross_house=1257279822024][0_ados_coast_s=1257118881787][0_ados_city=1275683280131][int_ados_house_56_1=1257118736289][int_ados_house_57_1=1257118478215][int_wofol_house1=1254679324731][-1_fado_great_cave_n_e2=1275683223901][-1_fado_great_cave_n_e3=1257118329355][int_kalavan_castle_1=1275683227210][int_wofol_house2=1257119000836][int_wofol_house9=1257118868561][1_dreamscape=1280122321537][int_wofol_house5=1257118637075][int_wofol_house4=1257118412029][int_wofol_house7=1257118653609][int_wofol_house6=1257118633770][int_afterlife=1275683088285][int_nalwor_house2=1257119043817][int_nalwor_house3=1275683038640][-2_semos_jail=1275502989420][int_mithrilbourgh_stores=1257118610458][0_amazon_island_ne=1257118567480][-1_semos_mine_n2_e=1275683147844][0_semos_mountain_n2=1280603158748][-2_semos_mine_n2_e3=1257118425258][-2_semos_mine_n2_e2=1257119152925][-1_athor_island_e=1257119129778][int_wofol_warehouse=1257118779271][int_0_semos_village_w_house_65=1199408487658][int_0_semos_village_w_house_66=1199563217934][int_rat_hostel=1275683303337][0_semos_forest_s=1270399011722][0_kalavan_castle=1257118464928][0_kirdneh_city=1276373653355][-5_ados_abandoned_keep=1257118951236][int_nalwor_house1=1276496891590][int_kalavan_castle_basement=1257118924787][int_semos_temple=1275826638813][-2_orril_lich_palace=1276516364819][-5_kanmararn_entrance=1257118732984][0_nalwor_forest_n_e2=1276373741953][-1_ados_abandoned_keep=1257118312825][0_orril_river_sw=1276373702353][0_fado_forest=1276373728424][int_oni_palace_2=1257118832191][int_magic_flower_shop=1257118441783][0_orril_river_se=1275683273511][int_semos_swanky_pad=1275683154456][int_nalwor_assassinhq_cellar=1257118385572][int_ados_barracks_1=1257118848724][0_semos_road_e=1277500601869][int_ados_barracks_0=1257118709835][int_semos_guard_house=1277637088753][-1_fado_great_cave_e=1262128777102][0_athor_ship_w2=1275683290063][int_magic_bank=1257118607153][int_athor_apartment_102=1257118332662][0_semos_mountain_n2_e2=1275683237124][-1_fado_great_cave_e3=1257118977689][-1_fado_great_cave_n=1257118544341][int_ados_house_63=1257118587313][int_ados_house_64=1257118448394][int_ados_house_65=1257118382264][int_oni_palace_1=1257119133086][int_oni_palace_0=1257118686673][-1_fado_great_cave_w=1257119010756][-1_fado_great_cave_e2=1275683051910][int_ados_house_60=1257119096721][int_ados_house_61=1257119020672][int_ados_house_62=1257118825580][int_athor_apartment_103=1275683356238][int_semos_bakery=1275683382702][int_athor_apartment_104=1257118802438][int_athor_apartment_105=1257118438478][int_athor_apartment_106=1257118974383][int_ados_house_67=1199563163783][int_athor_apartment_107=1257118994223][int_athor_apartment_108=1257118670141][int_ados_house_68=1257118997530][int_ados_house_69=1257118895022][int_nalwor_royal_hall=1257119100044][0_fado_city=1262806374672][-1_athor_island=1257118402106][0_semos_mountain_n2_w2=1270935404453][int_ados_house_54=1257119047125][int_ados_magician_house=1269625167929][int_magic_house6=1257119109938][int_semos_wizards_tower_9=1280938603832][int_ados_house_50=1257118359113][int_semos_wizards_tower_basement=1281185931718][int_ados_house_51=1275683256965][int_semos_wizards_tower_8=1280938680550][int_ados_house_58_1=1275683124679][int_semos_wizards_tower_7=1280938689268][int_magic_house1=1262127684237][int_semos_blacksmith=1282332541076][int_magic_house3=1275683144526][int_ados_house_58=1275683048603][int_magic_house5=1257118623852][int_ados_house_57=1275683187525][int_magic_house4=1257118693288][0_nalwor_forest_ne=1257118352503][-1_semos_dungeon=1272573570594][0_semos_plains_n=1280603079474][int_testing_grid=1257118494746][-2_ados_outside_nw=1257118799131][0_semos_plains_s=1280122321538][0_semos_mountain_n_e2=1261300085989][0_semos_plains_w=1277070438512][int_nalwor_secret_room=1257118421951][0_nalwor_forest_nw=1257118766045][int_admin_playground=1260703937914][int_nalwor_prison=1275683058521][-2_semos_mine_n_e3=1270413964135][int_semos_bank=1280606956804][-6_kanmararn_city=1275633381767][-1_semos_mine_n2=1270403654132][int_ados_house_73=1275683061825][int_ados_house_70=1257118550952][int_ados_house_71=1257118319436][int_sedah_house4_0=1275683250352][0_ados_swamp=1275683081665][int_ados_house_76=1257118706518][int_ados_house_77=1275683376090][int_ados_house_74=1257119040508][int_semos_storage_-1=1257118369038][int_kirdneh_townhall1=1257119113244][-1_ados_tunnel_w=1257118435173][int_fado_battle_arena=1262808118099][int_kalavan_cottage=1275683127985][-1_semos_catacombs_nw=1275683118065][int_fado_hotel_0=1257882227777][0_kirdneh_river_w=1275683157761][int_nalwor_postoffice=1275683078366][5_kikareukin_cave=1266680748029][-1_semos_mine_n_w2=1257118928093][-1_semos_catacombs_ne=1257118395493][-3_ados_abandoned_keep=1264240743702][-1_ados_city_s=1257118663526][hendrikus_vault=1277641565289][-2_semos_mine_e2=1257119123166][int_kirdneh_townhall=1257118498054][int_ados_church_1=1275683104815][int_ados_church_0=1257118349194][int_semos_tavern_1=1262550788131][int_semos_tavern_0=1282165713527][int_ados_castle_throne=1257118938010][int_wofol_library_0=1275683213980][-2_orril_dwarf_mine=1257118316130][0_ados_wall_n2=1261300326509] and RPSlots  with maps and RPLink  and RPEvents 

[21:29] 
Slot !tutorial: 
   RPObject with Attributes of Class(): [visit_semos_tavern=1][first_poisoned=1][timed_outfit=1][visit_kikareukin_cave=1][visit_magic_city_n=1][visit_magic_city=1][first_death=1][visit_sub2_semos_catacombs=1][id=6][new_release77=1][first_private_message=1][db_id=90080][new_release75=1][first_attacked=1][first_login=1][visit_semos_dungeon_2=1][return_guardhouse=1][new_release=1][visit_semos_caves=1][first_kill=1][visit_semos_plains=1][visit_semos_dungeon=1][first_move=1][visit_semos_city=1][visit_imperial_caves=1][new_release69=1][new_release80=1][timed_naked=1][timed_rules=1] and RPSlots  with maps and RPLink  and RPEvents 

[21:29] 
Slot !features: 
   RPObject with Attributes of Class(): [id=19][db_id=90093] and RPSlots  with maps and RPLink  and RPEvents 

[21:29] 
Slot bag: 
   Item, RPObject with Attributes of Class(item): [visibility=100][width=1][class=cloak][type=item][logid=10196][id=1][height=1][def=4][description=][name=dwarf cloak][subclass=dwarf_cloak][resistance=0][y=0][x=0] and RPSlots  with maps and RPLink  and RPEvents 
   Item, RPObject with Attributes of Class(item): [visibility=100][infostring=int_kirdneh_house_44 20 18][width=1][class=scroll][type=item][logid=10203][id=2][height=1][description=][name=marked scroll][subclass=marked][resistance=0][quantity=20][y=0][x=0] and RPSlots  with maps and RPLink  and RPEvents 
   Item, RPObject with Attributes of Class(item): [visibility=100][width=1][class=money][type=item][logid=10201][id=3][height=1][description=][name=money][subclass=gold][resistance=0][quantity=5836][y=0][x=0] and RPSlots  with maps and RPLink  and RPEvents 
   Item, RPObject with Attributes of Class(item): [visibility=100][width=1][class=food][frequency=10][type=item][logid=13205342][id=4][amount=1000][height=1][description=You see a spotted egg. They are said to be delicious.][name=spotted egg][subclass=spotted_egg][resistance=0][quantity=1][regen=10][y=0][x=0] and RPSlots  with maps and RPLink  and RPEvents 
   Item, RPObject with Attributes of Class(item): [infostring=kirdneh house 44;0;an unknown owner][class=key][type=item][logid=10209][id=5][persistent=1][description=You see a private key to House 44 in Kirdneh City.][name=house key][subclass=red][y=0][x=0] and RPSlots  with maps and RPLink  and RPEvents 
   Item, RPObject with Attributes of Class(item): [visibility=100][width=1][class=food][frequency=10][type=item][logid=13205348][id=6][amount=1980][height=1][description=You see a lovely chocolate easter egg.][name=easter egg][subclass=easter_egg][resistance=0][quantity=1][regen=30][y=0][x=0] and RPSlots  with maps and RPLink  and RPEvents 
   Item, RPObject with Attributes of Class(item): [visibility=100][width=1][class=food][frequency=15][type=item][logid=13478520][id=7][amount=20][height=1][description=You see a button mushroom.  It can be an ingredient, or you can eat it.][name=button mushroom][subclass=button_mushroom][resistance=0][quantity=6][regen=2][y=0][x=0] and RPSlots  with maps and RPLink  and RPEvents 
   Item, RPObject with Attributes of Class(item): [visibility=100][width=1][class=food][frequency=20][type=item][logid=13482416][id=8][amount=-60][height=1][description=You see a toadstool. Beware!][name=toadstool][subclass=toadstool][resistance=0][quantity=1][regen=-5][y=0][x=0] and RPSlots  with maps and RPLink  and RPEvents 
   Item, RPObject with Attributes of Class(item): [visibility=100][width=1][class=key][type=item][logid=247215][id=9][height=1][description=You see a silver dungeon key. Use it only if you are brave.][name=dungeon silver key][subclass=silver][resistance=0][y=0][bound=hendrikus][x=0] and RPSlots  with maps and RPLink  and RPEvents 
   Item, RPObject with Attributes of Class(item): [visibility=100][width=1][class=food][frequency=15][type=item][logid=13513981][id=10][amount=20][height=1][description=You see some cheese.  Eat it and see what happens.][name=cheese][subclass=cheese][resistance=0][quantity=4][regen=1][y=0][x=0] and RPSlots  with maps and RPLink  and RPEvents 
   Item, RPObject with Attributes of Class(item): [visibility=100][width=1][class=club][type=item][logid=13513983][id=11][atk=6][height=1][rate=4][description=You see a wooden club. It won\'t kill much but is better than no weapon.][name=club][subclass=club][resistance=0][undroppableondeath=1][y=0][x=0] and RPSlots  with maps and RPLink  and RPEvents 

[21:29] 
Slot rhand: 
   Item, RPObject with Attributes of Class(item): [min_level=40][visibility=100][width=1][class=sword][type=item][logid=10205][id=12][atk=29][height=1][rate=6][description=][name=ice sword][subclass=ice_sword][resistance=0][y=0][x=0] and RPSlots  with maps and RPLink  and RPEvents 

[21:29] 
Slot lhand: 
   Item, RPObject with Attributes of Class(item): [visibility=100][width=1][class=club][type=item][logid=10204][id=13][atk=100][height=1][rate=2][def=100][description=][name=rod of the gm][subclass=rod_of_the_gm][resistance=0][y=0][x=0] and RPSlots  with maps and RPLink  and RPEvents 

[21:29] 
Slot head: 

[21:29] 
Slot armor: 

[21:29] 
Slot legs: 

[21:29] 
Slot feet: 

[21:29] 
Slot finger: 

[21:29] 
Slot cloak: 
   Item, RPObject with Attributes of Class(item): [visibility=100][width=1][class=cloak][type=item][logid=10207][id=14][height=1][def=8][description=You see a blue elf cloak +2. The material is thick and warm.][name=blue elf cloak][subclass=blue_elf_cloak][resistance=0][y=0][x=0] and RPSlots  with maps and RPLink  and RPEvents 

[21:29] 
Slot keyring: 

[21:29] 
Slot bank: 
   Item, RPObject with Attributes of Class(item): [visibility=100][width=1][class=food][frequency=15][type=item][logid=10198][id=15][amount=20][height=1][description=][name=cheese][subclass=cheese][resistance=0][quantity=4][regen=1][y=0][x=0] and RPSlots  with maps and RPLink  and RPEvents 
   Item, RPObject with Attributes of Class(item): [visibility=100][width=1][class=money][type=item][logid=11714459][id=16][height=1][description=][name=money][subclass=gold][resistance=0][quantity=300][y=0][x=0] and RPSlots  with maps and RPLink  and RPEvents 

[21:29] 
Slot bank_ados: 
   Item, RPObject with Attributes of Class(item): [visibility=100][width=1][class=helmet][type=item][logid=10211][id=17][height=1][def=4][description=][name=chain helmet][subclass=chain_helmet][resistance=0][y=0][x=0] and RPSlots  with maps and RPLink  and RPEvents 

[21:29] 
Slot bank_fado: 
   Item, RPObject with Attributes of Class(item): [visibility=100][infostring=Ricardo][width=1][class=misc][type=item][logid=10212][id=18][height=1][description=][name=dice][subclass=dice][resistance=0][y=0][x=0] and RPSlots  with maps and RPLink  and RPEvents 

[21:29] 
Slot bank_nalwor: 

[21:29] 
Slot zaras_chest_ados: 

[21:29] Script "DeepInspect.class" was successfully executed.	
	';

	function writeContent() {

		if(getAdminLevel() < 5000) {
			die("Ooops!");
		}

		$parsedData = $this->parse($this->data);
		foreach ($parsedData as $inspectData) {
			$this->renderInspectResult($inspectData);
		}
	}

	private function parse($data) {
		$parser = new InspectParser($data);
		$parser->parse();

		// TODO: removed dummy data

		$res = array();
		$res[] = array();
		$res[0]['!quests'][]['mykey'] = 'myvalue';

		$res[0]['bag'][0]['type'] = 'item';
		$res[0]['bag'][0]['class'] = 'cloak';
		$res[0]['bag'][0]['logid'] = '10196';
		$res[0]['bag'][0]['def'] = '4';
		$res[0]['bag'][0]['name'] = 'dwarf cloak';
		$res[0]['bag'][0]['subclass'] = 'dwarf_cloak';

		$res[0]['bag'][1]['type'] = 'item';
		$res[0]['bag'][1]['class'] = 'scroll';
		$res[0]['bag'][1]['logid'] = '10203';
		$res[0]['bag'][1]['quantity'] = '20';
		$res[0]['bag'][1]['name'] = 'marked scroll';
		$res[0]['bag'][1]['subclass'] = 'marked';
		$res[0]['bag'][1]['infostring'] = 'int_kirdneh_house_44 20 18';

		return $res;
	}

	/**
	 * renders the result of a deep inspect
	 *
	 * @param $inspectData
	 */
	private function renderInspectResult($inspectData) {
		echo '<h1>Deep inspect of '.htmlspecialchars($inspectData['name']).'</h1>';
		$this->renderItemSlots($inspectData);
		$this->renderKeyedSlots($inspectData);
	}

	/**
	 * renders a slot with items
	 *
	 * @param $inspectData data of an deep inspect
	 */
	private function renderItemSlots($inspectData) {
		foreach ($inspectData as $slotName => $slot) {
			if (in_array($slotName, InspectPage::$KEYED_SLOTS)) {
				continue;
			}

			echo '<h2>'.htmlspecialchars($slotName).'</h2>';
			foreach ($slot as $item) {
				// TODO: render as quantity + icon
				// TODO: render table as javascript mouse over popup
				echo '<table class="prettytable"><tr><th>key</th><th>value</th></tr>';
				foreach ($item as $key => $value) {
					echo '<tr><td>'.htmlspecialchars($key).'</td><td>'.htmlspecialchars($value).'</td></tr>';
				}
				echo '</table>';
			}
		}
	}

	/**
	 * renders a slot with an object that is a map
	 *
	 * @param $inspectData data of an deep inspect
	 */
	private function renderKeyedSlots($inspectData) {
		foreach (InspectPage::$KEYED_SLOTS as $keyedSlot) {
			if (!isset($inspectData[$keyedSlot])) {
				continue;
			}
			echo '<h2>'.htmlspecialchars($keyedSlot).'</h2>';
			echo '<table class="prettytable"><tr><th>key</th><th>value</th></tr>';
			foreach ($inspectData[$keyedSlot][0] as $key => $value) {
				echo '<tr><td>'.htmlspecialchars($key).'</td><td>'.htmlspecialchars($value).'</td></tr>';
			}
			echo '</table>';
		}
	}
}

$page = new InspectPage();
?>