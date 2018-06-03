<?php
header('Content-type: text/plain; charset=UTF-8');
header('Content-type: application/json');

include __DIR__ . '/../lib/mysql_credentials.php';
require_once __DIR__ . '/../lib/auth.php';
require_logined_session();

$post = json_decode(file_get_contents('php://input'), true);

//挿入用
$sql
    = "INSERT INTO interest_user_list (userno, age, workobject_money_1, workobject_money_2, workobject_purposeoflife, workobject_health, workobject_contribution, workobject_asked, workobject_sparetime, workobject_skill, worktype_prune, worktype_agriculture, worktype_cleaning, worktype_housework, worktype_shopping, worktype_repair, worktype_caretaking, worktype_teaching, worktype_consulting, gyoushu_dai_it, gyoushu_dai_kikai, gyoushu_dai_sozai, gyoushu_dai_juutaku, gyoushu_dai_seikatsu, gyoushu_dai_shousha, gyoushu_dai_service, gyoushu_dai_leisure, gyoushu_dai_ryuutsu, gyoushu_dai_food, gyoushu_dai_mascomi, gyoushu_dai_kinnyuu, gyoushu_dai_consulting, gyoushu_dai_hudousan, gyoushu_dai_unyu, gyoushu_dai_kankyou, gyoushu_dai_kouteki, gyoushu_chu_software, gyoushu_chu_internet, gyoushu_chu_game, gyoushu_chu_tsushin, gyoushu_chu_sougoudenki, gyoushu_chu_computer, gyoushu_chu_kaden, gyoushu_chu_gameamuse, gyoushu_chu_seimitsu, gyoushu_chu_tsushinkiki, gyoushu_chu_handotai, gyoushu_chu_iryouyoukiki, gyoushu_chu_yusouyoukiki, gyoushu_chu_jayden, gyoushu_chu_plant, gyoushu_chu_sonotadenki, gyoushu_chu_mining, gyoushu_chu_hitetsukinzoku, gyoushu_chu_glass, gyoushu_chu_paper, gyoushu_chu_fabric, gyoushu_chu_celamic, gyoushu_chu_rubber, gyoushu_chu_cement, gyoushu_chu_housing, gyoushu_chu_interior, gyoushu_chu_food, gyoushu_chu_cosmetics, gyoushu_chu_commodity, gyoushu_chu_toy, gyoushu_chu_apparel, gyoushu_chu_sport, gyoushu_chu_stationary, gyoushu_chu_jewelry, gyoushu_chu_othermaker, gyoushu_chu_sougoushousha, gyoushu_chu_senmonshousha, gyoushu_chu_jinzaihaken, gyoushu_chu_outsourcing, gyoushu_chu_education, gyoushu_chu_iryou, gyoushu_chu_kankonsousai, gyoushu_chu_security, gyoushu_chu_buildingmaintenance, gyoushu_chu_esthetic, gyoushu_chu_fitnessclub, gyoushu_chu_otherservice, gyoushu_chu_leisureservice, gyoushu_chu_hotel, gyoushu_chu_tourism, gyoushu_chu_departmentstore, gyoushu_chu_ryuutsuu, gyoushu_chu_conveniencestore, gyoushu_chu_drugstore, gyoushu_chu_homecenter, gyoushu_chu_senmontensougou, gyoushu_chu_senmontenshokuhin, gyoushu_chu_senmontenjidousha, gyoushu_chu_senmontencamera, gyoushu_chu_senmontendenki, gyoushu_chu_senmontenbookmusic, gyoushu_chu_senmontenglasses, gyoushu_chu_senmontenfashion, gyoushu_chu_senmontensport, gyoushu_chu_senmonteninterior, gyoushu_chu_tsushinhanbai, gyoushu_chu_foodbusinesswashoku, gyoushu_chu_foodbusinessyoushoku, gyoushu_chu_foodbusinessasia, gyoushu_chu_foodbusinessfast, gyoushu_chu_broadcast, gyoushu_chu_newspaper, gyoushu_chu_advertisement, gyoushu_chu_displaydesign, gyoushu_chu_art, gyoushu_chu_kinyusougou, gyoushu_chu_gaishikinyu, gyoushu_chu_seihukeikinyu, gyoushu_chu_bank, gyoushu_chu_gaishibank, gyoushu_chu_sinyoukumiai, gyoushu_chu_sintaku, gyoushu_chu_toushisintaku, gyoushu_chu_shoken, gyoushu_chu_shouhintorihiki, gyoushu_chu_vc, gyoushu_chu_jigyoushakinyu, gyoushu_chu_credit, gyoushu_chu_rental, gyoushu_chu_seimeihoken, gyoushu_chu_kyousai, gyoushu_chu_sonotakinyu, gyoushu_chu_thinktank, gyoushu_chu_senmonconsultant, gyoushu_chu_kojinjimusho, gyoushu_chu_kensetsuconsul, gyoushu_chu_kensetsu, gyoushu_chu_sekkei, gyoushu_chu_setsubi, gyoushu_chu_reform, gyoushu_chu_hudousan, gyoushu_chu_kaiun, gyoushu_chu_buturyu, gyoushu_chu_environment, gyoushu_chu_kankyoukanrensetsubi, gyoushu_chu_electricity, gyoushu_chu_police, gyoushu_chu_kankouchou, gyoushu_chu_koueki, gyoushu_chu_seikyou, gyoushu_chu_noukyou, gyoushu_chu_nourinsuisan, shokushu_dai_eigyou, shokushu_dai_kikakukeiei, shokushu_dai_kanrijimu, shokushu_dai_hanbaifoodentertainment, shokushu_dai_biyoubridalhotelkoutsuu, shokushu_dai_iryouhukushi, shokushu_dai_hoikukyouikutuuyaku, shokushu_dai_creative, shokushu_dai_webinternetgame, shokushu_dai_consultantkinyuuhudousan, shokushu_dai_koukyouservice, shokushu_dai_itengineer, shokushu_dai_denkidenshikikaihandotai, shokushu_dai_kenchikudoboku, shokushu_dai_iyakushokuhinkagakusozai, shokushu_dai_ginoukousetsubihaisounourin, shokushu_chu_eigyou, shokushu_chu_coordinator, shokushu_chu_callcenter, shokushu_chu_marketing, shokushu_chu_md, shokushu_chu_jigyoukikaku, shokushu_chu_fcowner, shokushu_chu_koubai, shokushu_chu_keiri, shokushu_chu_jinji, shokushu_chu_jimu, shokushu_chu_supervisor, shokushu_chu_kouri, shokushu_chu_food, shokushu_chu_este, shokushu_chu_bridal, shokushu_chu_ryokou, shokushu_chu_koutsu, shokushu_chu_iryou, shokushu_chu_hukushi, shokushu_chu_hoiku, shokushu_chu_kyoushi, shokushu_chu_tuuyaku, shokushu_chu_koukoku, shokushu_chu_henshu, shokushu_chu_insatsu, shokushu_chu_fashion, shokushu_chu_kougyoudesign, shokushu_chu_housou, shokushu_chu_website, shokushu_chu_game, shokushu_chu_webshop, shokushu_chu_consultant, shokushu_chu_shigyou, shokushu_chu_kinyuusenmon1, shokushu_chu_kinyuusenmon2, shokushu_chu_hudousan, shokushu_chu_koumuin, shokushu_chu_gakkou, shokushu_chu_consultantanalyst, shokushu_chu_se1, shokushu_chu_se2, shokushu_chu_se3, shokushu_chu_packagesoftware, shokushu_chu_network, shokushu_chu_techsupport, shokushu_chu_shanaise, shokushu_chu_patent, shokushu_chu_research, shokushu_chu_circuit, shokushu_chu_seigyo, shokushu_chu_kikai, shokushu_chu_seisan, shokushu_chu_hinshitsuhosho, shokushu_chu_salesengineer, shokushu_chu_serviceengineer, shokushu_chu_cad, shokushu_chu_hyoukakensa, shokushu_chu_sekkei, shokushu_chu_sekoukanri, shokushu_chu_research2, shokushu_chu_chemistry, shokushu_chu_shokuhin, shokushu_chu_iyakuhin, shokushu_chu_ginoukou, shokushu_chu_sisetsu, shokushu_chu_haisou, shokushu_chu_nourinnsuisan, study_english, study_foreignlanguage, study_it, study_business, study_caretaking, study_housework, study_liberalarts, study_art, volunteer_health, volunteer_elderly, volunteer_disable, volunteer_children, volunteer_sport, volunteer_town, volunteer_safety, volunteer_nature, volunteer_disaster, volunteer_international, hobby_musicalinstrument, hobby_chorus, hobby_dance, hobby_shodo, hobby_kado, hobby_sado, hobby_wasai, hobby_knit, hobby_cooking, hobby_gardening, hobby_diy, hobby_painting, hobby_pottery, hobby_photo, hobby_writing, hobby_go, hobby_camp, hobby_watchsport, hobby_watchperformance, hobby_watchmovie, hobby_listenmusic, hobby_reading, hobby_pachinko, hobby_karaoke, hobby_game, hobby_attraction, hobby_train, hobby_car, sport_baseball, sport_tabletennis, sport_tennis, sport_badminton, sport_golf, sport_gateball, sport_bowling, sport_fishing, sport_swimming, sport_skiing, sport_climbing, sport_cycling, sport_jogging, sport_walking, sport_volleyball, sport_basketball, sport_football, sport_judo, sport_kendo, text_workobject, text_gyoushu, text_shokushu, text_study, text_volunteer, text_hobby, text_sport) VALUES ('"
    . $post['userno'] . "', '" . $post['age'] . "', '" . $post['workobject_money_1']
    . "', '" . $post['workobject_money_2'] . "', '" . $post['workobject_purposeoflife']
    . "', '" . $post['workobject_health'] . "', '" . $post['workobject_contribution']
    . "', '" . $post['workobject_asked'] . "', '" . $post['workobject_sparetime']
    . "', '" . $post['workobject_skill'] . "', '" . $post['worktype_prune'] . "', '"
    . $post['worktype_agriculture'] . "', '" . $post['worktype_cleaning'] . "', '"
    . $post['worktype_housework'] . "', '" . $post['worktype_shopping'] . "', '"
    . $post['worktype_repair'] . "', '" . $post['worktype_caretaking'] . "', '"
    . $post['worktype_teaching'] . "', '" . $post['worktype_consulting'] . "', '"
    . $post['gyoushu_dai_it'] . "', '" . $post['gyoushu_dai_kikai'] . "', '"
    . $post['gyoushu_dai_sozai'] . "', '" . $post['gyoushu_dai_juutaku'] . "', '"
    . $post['gyoushu_dai_seikatsu'] . "', '" . $post['gyoushu_dai_shousha'] . "', '"
    . $post['gyoushu_dai_service'] . "', '" . $post['gyoushu_dai_leisure'] . "', '"
    . $post['gyoushu_dai_ryuutsu'] . "', '" . $post['gyoushu_dai_food'] . "', '"
    . $post['gyoushu_dai_mascomi'] . "', '" . $post['gyoushu_dai_kinnyuu'] . "', '"
    . $post['gyoushu_dai_consulting'] . "', '" . $post['gyoushu_dai_hudousan'] . "', '"
    . $post['gyoushu_dai_unyu'] . "', '" . $post['gyoushu_dai_kankyou'] . "', '"
    . $post['gyoushu_dai_kouteki'] . "', '" . $post['gyoushu_chu_software'] . "', '"
    . $post['gyoushu_chu_internet'] . "', '" . $post['gyoushu_chu_game'] . "', '"
    . $post['gyoushu_chu_tsushin'] . "', '" . $post['gyoushu_chu_sougoudenki'] . "', '"
    . $post['gyoushu_chu_computer'] . "', '" . $post['gyoushu_chu_kaden'] . "', '"
    . $post['gyoushu_chu_gameamuse'] . "', '" . $post['gyoushu_chu_seimitsu'] . "', '"
    . $post['gyoushu_chu_tsushinkiki'] . "', '" . $post['gyoushu_chu_handotai']
    . "', '" . $post['gyoushu_chu_iryouyoukiki'] . "', '"
    . $post['gyoushu_chu_yusouyoukiki'] . "', '" . $post['gyoushu_chu_jayden'] . "', '"
    . $post['gyoushu_chu_plant'] . "', '" . $post['gyoushu_chu_sonotadenki'] . "', '"
    . $post['gyoushu_chu_mining'] . "', '" . $post['gyoushu_chu_hitetsukinzoku']
    . "', '" . $post['gyoushu_chu_glass'] . "', '" . $post['gyoushu_chu_paper'] . "', '"
    . $post['gyoushu_chu_fabric'] . "', '" . $post['gyoushu_chu_celamic'] . "', '"
    . $post['gyoushu_chu_rubber'] . "', '" . $post['gyoushu_chu_cement'] . "', '"
    . $post['gyoushu_chu_housing'] . "', '" . $post['gyoushu_chu_interior'] . "', '"
    . $post['gyoushu_chu_food'] . "', '" . $post['gyoushu_chu_cosmetics'] . "', '"
    . $post['gyoushu_chu_commodity'] . "', '" . $post['gyoushu_chu_toy'] . "', '"
    . $post['gyoushu_chu_apparel'] . "', '" . $post['gyoushu_chu_sport'] . "', '"
    . $post['gyoushu_chu_stationary'] . "', '" . $post['gyoushu_chu_jewelry'] . "', '"
    . $post['gyoushu_chu_othermaker'] . "', '" . $post['gyoushu_chu_sougoushousha']
    . "', '" . $post['gyoushu_chu_senmonshousha'] . "', '"
    . $post['gyoushu_chu_jinzaihaken'] . "', '" . $post['gyoushu_chu_outsourcing']
    . "', '" . $post['gyoushu_chu_education'] . "', '" . $post['gyoushu_chu_iryou']
    . "', '" . $post['gyoushu_chu_kankonsousai'] . "', '"
    . $post['gyoushu_chu_security'] . "', '"
    . $post['gyoushu_chu_buildingmaintenance'] . "', '"
    . $post['gyoushu_chu_esthetic'] . "', '" . $post['gyoushu_chu_fitnessclub']
    . "', '" . $post['gyoushu_chu_otherservice'] . "', '"
    . $post['gyoushu_chu_leisureservice'] . "', '" . $post['gyoushu_chu_hotel']
    . "', '" . $post['gyoushu_chu_tourism'] . "', '"
    . $post['gyoushu_chu_departmentstore'] . "', '" . $post['gyoushu_chu_ryuutsuu']
    . "', '" . $post['gyoushu_chu_conveniencestore'] . "', '"
    . $post['gyoushu_chu_drugstore'] . "', '" . $post['gyoushu_chu_homecenter']
    . "', '" . $post['gyoushu_chu_senmontensougou'] . "', '"
    . $post['gyoushu_chu_senmontenshokuhin'] . "', '"
    . $post['gyoushu_chu_senmontenjidousha'] . "', '"
    . $post['gyoushu_chu_senmontencamera'] . "', '"
    . $post['gyoushu_chu_senmontendenki'] . "', '"
    . $post['gyoushu_chu_senmontenbookmusic'] . "', '"
    . $post['gyoushu_chu_senmontenglasses'] . "', '"
    . $post['gyoushu_chu_senmontenfashion'] . "', '"
    . $post['gyoushu_chu_senmontensport'] . "', '"
    . $post['gyoushu_chu_senmonteninterior'] . "', '"
    . $post['gyoushu_chu_tsushinhanbai'] . "', '"
    . $post['gyoushu_chu_foodbusinesswashoku'] . "', '"
    . $post['gyoushu_chu_foodbusinessyoushoku'] . "', '"
    . $post['gyoushu_chu_foodbusinessasia'] . "', '"
    . $post['gyoushu_chu_foodbusinessfast'] . "', '" . $post['gyoushu_chu_broadcast']
    . "', '" . $post['gyoushu_chu_newspaper'] . "', '"
    . $post['gyoushu_chu_advertisement'] . "', '"
    . $post['gyoushu_chu_displaydesign'] . "', '" . $post['gyoushu_chu_art'] . "', '"
    . $post['gyoushu_chu_kinyusougou'] . "', '" . $post['gyoushu_chu_gaishikinyu']
    . "', '" . $post['gyoushu_chu_seihukeikinyu'] . "', '" . $post['gyoushu_chu_bank']
    . "', '" . $post['gyoushu_chu_gaishibank'] . "', '"
    . $post['gyoushu_chu_sinyoukumiai'] . "', '" . $post['gyoushu_chu_sintaku']
    . "', '" . $post['gyoushu_chu_toushisintaku'] . "', '"
    . $post['gyoushu_chu_shoken'] . "', '" . $post['gyoushu_chu_shouhintorihiki']
    . "', '" . $post['gyoushu_chu_vc'] . "', '" . $post['gyoushu_chu_jigyoushakinyu']
    . "', '" . $post['gyoushu_chu_credit'] . "', '" . $post['gyoushu_chu_rental']
    . "', '" . $post['gyoushu_chu_seimeihoken'] . "', '" . $post['gyoushu_chu_kyousai']
    . "', '" . $post['gyoushu_chu_sonotakinyu'] . "', '"
    . $post['gyoushu_chu_thinktank'] . "', '" . $post['gyoushu_chu_senmonconsultant']
    . "', '" . $post['gyoushu_chu_kojinjimusho'] . "', '"
    . $post['gyoushu_chu_kensetsuconsul'] . "', '" . $post['gyoushu_chu_kensetsu']
    . "', '" . $post['gyoushu_chu_sekkei'] . "', '" . $post['gyoushu_chu_setsubi']
    . "', '" . $post['gyoushu_chu_reform'] . "', '" . $post['gyoushu_chu_hudousan']
    . "', '" . $post['gyoushu_chu_kaiun'] . "', '" . $post['gyoushu_chu_buturyu']
    . "', '" . $post['gyoushu_chu_environment'] . "', '"
    . $post['gyoushu_chu_kankyoukanrensetsubi'] . "', '"
    . $post['gyoushu_chu_electricity'] . "', '" . $post['gyoushu_chu_police'] . "', '"
    . $post['gyoushu_chu_kankouchou'] . "', '" . $post['gyoushu_chu_koueki'] . "', '"
    . $post['gyoushu_chu_seikyou'] . "', '" . $post['gyoushu_chu_noukyou'] . "', '"
    . $post['gyoushu_chu_nourinsuisan'] . "', '" . $post['shokushu_dai_eigyou']
    . "', '" . $post['shokushu_dai_kikakukeiei'] . "', '"
    . $post['shokushu_dai_kanrijimu'] . "', '"
    . $post['shokushu_dai_hanbaifoodentertainment'] . "', '"
    . $post['shokushu_dai_biyoubridalhotelkoutsuu'] . "', '"
    . $post['shokushu_dai_iryouhukushi'] . "', '"
    . $post['shokushu_dai_hoikukyouikutuuyaku'] . "', '"
    . $post['shokushu_dai_creative'] . "', '" . $post['shokushu_dai_webinternetgame']
    . "', '" . $post['shokushu_dai_consultantkinyuuhudousan'] . "', '"
    . $post['shokushu_dai_koukyouservice'] . "', '"
    . $post['shokushu_dai_itengineer'] . "', '"
    . $post['shokushu_dai_denkidenshikikaihandotai'] . "', '"
    . $post['shokushu_dai_kenchikudoboku'] . "', '"
    . $post['shokushu_dai_iyakushokuhinkagakusozai'] . "', '"
    . $post['shokushu_dai_ginoukousetsubihaisounourin'] . "', '"
    . $post['shokushu_chu_eigyou'] . "', '" . $post['shokushu_chu_coordinator']
    . "', '" . $post['shokushu_chu_callcenter'] . "', '"
    . $post['shokushu_chu_marketing'] . "', '" . $post['shokushu_chu_md'] . "', '"
    . $post['shokushu_chu_jigyoukikaku'] . "', '" . $post['shokushu_chu_fcowner']
    . "', '" . $post['shokushu_chu_koubai'] . "', '" . $post['shokushu_chu_keiri']
    . "', '" . $post['shokushu_chu_jinji'] . "', '" . $post['shokushu_chu_jimu'] . "', '"
    . $post['shokushu_chu_supervisor'] . "', '" . $post['shokushu_chu_kouri'] . "', '"
    . $post['shokushu_chu_food'] . "', '" . $post['shokushu_chu_este'] . "', '"
    . $post['shokushu_chu_bridal'] . "', '" . $post['shokushu_chu_ryokou'] . "', '"
    . $post['shokushu_chu_koutsu'] . "', '" . $post['shokushu_chu_iryou'] . "', '"
    . $post['shokushu_chu_hukushi'] . "', '" . $post['shokushu_chu_hoiku'] . "', '"
    . $post['shokushu_chu_kyoushi'] . "', '" . $post['shokushu_chu_tuuyaku'] . "', '"
    . $post['shokushu_chu_koukoku'] . "', '" . $post['shokushu_chu_henshu'] . "', '"
    . $post['shokushu_chu_insatsu'] . "', '" . $post['shokushu_chu_fashion'] . "', '"
    . $post['shokushu_chu_kougyoudesign'] . "', '" . $post['shokushu_chu_housou']
    . "', '" . $post['shokushu_chu_website'] . "', '" . $post['shokushu_chu_game']
    . "', '" . $post['shokushu_chu_webshop'] . "', '"
    . $post['shokushu_chu_consultant'] . "', '" . $post['shokushu_chu_shigyou']
    . "', '" . $post['shokushu_chu_kinyuusenmon1'] . "', '"
    . $post['shokushu_chu_kinyuusenmon2'] . "', '" . $post['shokushu_chu_hudousan']
    . "', '" . $post['shokushu_chu_koumuin'] . "', '" . $post['shokushu_chu_gakkou']
    . "', '" . $post['shokushu_chu_consultantanalyst'] . "', '"
    . $post['shokushu_chu_se1'] . "', '" . $post['shokushu_chu_se2'] . "', '"
    . $post['shokushu_chu_se3'] . "', '" . $post['shokushu_chu_packagesoftware']
    . "', '" . $post['shokushu_chu_network'] . "', '"
    . $post['shokushu_chu_techsupport'] . "', '" . $post['shokushu_chu_shanaise']
    . "', '" . $post['shokushu_chu_patent'] . "', '" . $post['shokushu_chu_research']
    . "', '" . $post['shokushu_chu_circuit'] . "', '" . $post['shokushu_chu_seigyo']
    . "', '" . $post['shokushu_chu_kikai'] . "', '" . $post['shokushu_chu_seisan']
    . "', '" . $post['shokushu_chu_hinshitsuhosho'] . "', '"
    . $post['shokushu_chu_salesengineer'] . "', '"
    . $post['shokushu_chu_serviceengineer'] . "', '" . $post['shokushu_chu_cad']
    . "', '" . $post['shokushu_chu_hyoukakensa'] . "', '"
    . $post['shokushu_chu_sekkei'] . "', '" . $post['shokushu_chu_sekoukanri'] . "', '"
    . $post['shokushu_chu_research2'] . "', '" . $post['shokushu_chu_chemistry']
    . "', '" . $post['shokushu_chu_shokuhin'] . "', '" . $post['shokushu_chu_iyakuhin']
    . "', '" . $post['shokushu_chu_ginoukou'] . "', '" . $post['shokushu_chu_sisetsu']
    . "', '" . $post['shokushu_chu_haisou'] . "', '"
    . $post['shokushu_chu_nourinnsuisan'] . "', '" . $post['study_english'] . "', '"
    . $post['study_foreignlanguage'] . "', '" . $post['study_it'] . "', '"
    . $post['study_business'] . "', '" . $post['study_caretaking'] . "', '"
    . $post['study_housework'] . "', '" . $post['study_liberalarts'] . "', '"
    . $post['study_art'] . "', '" . $post['volunteer_health'] . "', '"
    . $post['volunteer_elderly'] . "', '" . $post['volunteer_disable'] . "', '"
    . $post['volunteer_children'] . "', '" . $post['volunteer_sport'] . "', '"
    . $post['volunteer_town'] . "', '" . $post['volunteer_safety'] . "', '"
    . $post['volunteer_nature'] . "', '" . $post['volunteer_disaster'] . "', '"
    . $post['volunteer_international'] . "', '" . $post['hobby_musicalinstrument']
    . "', '" . $post['hobby_chorus'] . "', '" . $post['hobby_dance'] . "', '"
    . $post['hobby_shodo'] . "', '" . $post['hobby_kado'] . "', '" . $post['hobby_sado']
    . "', '" . $post['hobby_wasai'] . "', '" . $post['hobby_knit'] . "', '"
    . $post['hobby_cooking'] . "', '" . $post['hobby_gardening'] . "', '"
    . $post['hobby_diy'] . "', '" . $post['hobby_painting'] . "', '"
    . $post['hobby_pottery'] . "', '" . $post['hobby_photo'] . "', '"
    . $post['hobby_writing'] . "', '" . $post['hobby_go'] . "', '" . $post['hobby_camp']
    . "', '" . $post['hobby_watchsport'] . "', '" . $post['hobby_watchperformance']
    . "', '" . $post['hobby_watchmovie'] . "', '" . $post['hobby_listenmusic'] . "', '"
    . $post['hobby_reading'] . "', '" . $post['hobby_pachinko'] . "', '"
    . $post['hobby_karaoke'] . "', '" . $post['hobby_game'] . "', '"
    . $post['hobby_attraction'] . "', '" . $post['hobby_train'] . "', '"
    . $post['hobby_car'] . "', '" . $post['sport_baseball'] . "', '"
    . $post['sport_tabletennis'] . "', '" . $post['sport_tennis'] . "', '"
    . $post['sport_badminton'] . "', '" . $post['sport_golf'] . "', '"
    . $post['sport_gateball'] . "', '" . $post['sport_bowling'] . "', '"
    . $post['sport_fishing'] . "', '" . $post['sport_swimming'] . "', '"
    . $post['sport_skiing'] . "', '" . $post['sport_climbing'] . "', '"
    . $post['sport_cycling'] . "', '" . $post['sport_jogging'] . "', '"
    . $post['sport_walking'] . "', '" . $post['sport_volleyball'] . "', '"
    . $post['sport_basketball'] . "', '" . $post['sport_football'] . "', '"
    . $post['sport_judo'] . "', '" . $post['sport_kendo'] . "', '"
    . $post['text_workobject'] . "', '" . $post['text_gyoushu'] . "', '"
    . $post['text_shokushu'] . "', '" . $post['text_study'] . "', '"
    . $post['text_volunteer'] . "', '" . $post['text_hobby'] . "', '"
    . $post['text_sport'] . "')";

if (!mysql_query($sql, $con)) {
    die('Error: ' . mysql_error());
} else {
    echo $_GET['jsoncallback'] . '({"status":"succeed"});';
}

mysql_close($con);

?>