<?php
function updateMatchingParam_human($workparam, $workerno, $num, $plus)
{
    extract($workparam);
    if ($plus) {
        mysql_query("UPDATE matchingparam_human SET worktype_prune = worktype_prune + "
            . $num . "*" . $worktype_prune
            . ", worktype_agriculture = worktype_agriculture + " . $num . "*"
            . $worktype_agriculture . ", worktype_cleaning = worktype_cleaning + "
            . $num . "*" . $worktype_cleaning
            . ", worktype_housework = worktype_housework + " . $num . "*"
            . $worktype_housework . ", worktype_shopping = worktype_shopping + "
            . $num . "*" . $worktype_shopping
            . ", worktype_repair = worktype_repair + " . $num . "*" . $worktype_repair
            . ", worktype_caretaking = worktype_caretaking + " . $num . "*"
            . $worktype_caretaking . ", worktype_teaching = worktype_teaching + "
            . $num . "*" . $worktype_teaching
            . ", worktype_consulting = worktype_consulting + " . $num . "*"
            . $worktype_consulting . ", study_english = study_english + " . $num . "*"
            . $study_english . ", study_foreignlanguage = study_foreignlanguage + "
            . $num . "*" . $study_foreignlanguage . ", study_it = study_it + " . $num . "*"
            . $study_it . ", study_business = study_business + " . $num . "*"
            . $study_business . ", study_caretaking = study_caretaking + " . $num . "*"
            . $study_caretaking . ", study_housework = study_housework + " . $num . "*"
            . $study_housework . ", study_liberalarts = study_liberalarts + " . $num
            . "*" . $study_liberalarts . ", study_art = study_art + " . $num . "*"
            . $study_art . ", volunteer_health = volunteer_health + " . $num . "*"
            . $volunteer_health . ", volunteer_elderly = volunteer_elderly + " . $num
            . "*" . $volunteer_elderly . ", volunteer_disable = volunteer_disable + "
            . $num . "*" . $volunteer_disable
            . ", volunteer_children = volunteer_children + " . $num . "*"
            . $volunteer_children . ", volunteer_sport = volunteer_sport + " . $num
            . "*" . $volunteer_sport . ", volunteer_town = volunteer_town + " . $num
            . "*" . $volunteer_town . ", volunteer_safety = volunteer_safety + " . $num
            . "*" . $volunteer_safety . ", volunteer_nature = volunteer_nature + "
            . $num . "*" . $volunteer_nature
            . ", volunteer_disaster = volunteer_disaster + " . $num . "*"
            . $volunteer_disaster
            . ", volunteer_international = volunteer_international + " . $num . "*"
            . $volunteer_international
            . ", hobby_musicalinstrument = hobby_musicalinstrument + " . $num . "*"
            . $hobby_musicalinstrument . ", hobby_chorus = hobby_chorus + " . $num
            . "*" . $hobby_chorus . ", hobby_dance = hobby_dance + " . $num . "*"
            . $hobby_dance . ", hobby_shodo = hobby_shodo + " . $num . "*" . $hobby_shodo
            . ", hobby_kado = hobby_kado + " . $num . "*" . $hobby_kado
            . ", hobby_sado = hobby_sado + " . $num . "*" . $hobby_sado
            . ", hobby_wasai = hobby_wasai + " . $num . "*" . $hobby_wasai
            . ", hobby_knit = hobby_knit + " . $num . "*" . $hobby_knit
            . ", hobby_cooking = hobby_cooking + " . $num . "*" . $hobby_cooking
            . ", hobby_gardening = hobby_gardening + " . $num . "*" . $hobby_gardening
            . ", hobby_diy = hobby_diy + " . $num . "*" . $hobby_diy
            . ", hobby_painting = hobby_painting + " . $num . "*" . $hobby_painting
            . ", hobby_pottery = hobby_pottery + " . $num . "*" . $hobby_pottery
            . ", hobby_photo = hobby_photo + " . $num . "*" . $hobby_photo
            . ", hobby_writing = hobby_writing + " . $num . "*" . $hobby_writing
            . ", hobby_go = hobby_go + " . $num . "*" . $hobby_go
            . ", hobby_camp = hobby_camp + " . $num . "*" . $hobby_camp
            . ", hobby_watchsport = hobby_watchsport + " . $num . "*"
            . $hobby_watchsport
            . ", hobby_watchperformance = hobby_watchperformance + " . $num . "*"
            . $hobby_watchperformance . ", hobby_watchmovie = hobby_watchmovie + "
            . $num . "*" . $hobby_watchmovie
            . ", hobby_listenmusic = hobby_listenmusic + " . $num . "*"
            . $hobby_listenmusic . ", hobby_reading = hobby_reading + " . $num . "*"
            . $hobby_reading . ", hobby_pachinko = hobby_pachinko + " . $num . "*"
            . $hobby_pachinko . ", hobby_karaoke = hobby_karaoke + " . $num . "*"
            . $hobby_karaoke . ", hobby_game = hobby_game + " . $num . "*" . $hobby_game
            . ", hobby_attraction = hobby_attraction + " . $num . "*"
            . $hobby_attraction . ", hobby_train = hobby_train + " . $num . "*"
            . $hobby_train . ", hobby_car = hobby_car + " . $num . "*" . $hobby_car
            . ", trip_daytrip = trip_daytrip + " . $num . "*" . $trip_daytrip
            . ", trip_domestic = trip_domestic + " . $num . "*" . $trip_domestic
            . ", trip_international = trip_international + " . $num . "*"
            . $trip_international . ", sport_baseball = sport_baseball + " . $num . "*"
            . $sport_baseball . ", sport_tabletennis = sport_tabletennis + " . $num
            . "*" . $sport_tabletennis . ", sport_tennis = sport_tennis + " . $num . "*"
            . $sport_tennis . ", sport_badminton = sport_badminton + " . $num . "*"
            . $sport_badminton . ", sport_golf = sport_golf + " . $num . "*"
            . $sport_golf . ", sport_gateball = sport_gateball + " . $num . "*"
            . $sport_gateball . ", sport_bowling = sport_bowling + " . $num . "*"
            . $sport_bowling . ", sport_fishing = sport_fishing + " . $num . "*"
            . $sport_fishing . ", sport_swimming = sport_swimming + " . $num . "*"
            . $sport_swimming . ", sport_skiing = sport_skiing + " . $num . "*"
            . $sport_skiing . ", sport_climbing = sport_climbing + " . $num . "*"
            . $sport_climbing . ", sport_cycling = sport_cycling + " . $num . "*"
            . $sport_cycling . ", sport_jogging = sport_jogging + " . $num . "*"
            . $sport_jogging . ", sport_walking = sport_walking + " . $num . "*"
            . $sport_walking . " WHERE userno = '" . $workerno . "'")
        or die ("Query error: " . mysql_error());
    } else {
        mysql_query("UPDATE matchingparam_human SET worktype_prune = worktype_prune - "
            . $num . "*" . $worktype_prune
            . ", worktype_agriculture = worktype_agriculture - " . $num . "*"
            . $worktype_agriculture . ", worktype_cleaning = worktype_cleaning - "
            . $num . "*" . $worktype_cleaning
            . ", worktype_housework = worktype_housework - " . $num . "*"
            . $worktype_housework . ", worktype_shopping = worktype_shopping - "
            . $num . "*" . $worktype_shopping
            . ", worktype_repair = worktype_repair - " . $num . "*" . $worktype_repair
            . ", worktype_caretaking = worktype_caretaking - " . $num . "*"
            . $worktype_caretaking . ", worktype_teaching = worktype_teaching - "
            . $num . "*" . $worktype_teaching
            . ", worktype_consulting = worktype_consulting - " . $num . "*"
            . $worktype_consulting . ", study_english = study_english - " . $num . "*"
            . $study_english . ", study_foreignlanguage = study_foreignlanguage - "
            . $num . "*" . $study_foreignlanguage . ", study_it = study_it - " . $num . "*"
            . $study_it . ", study_business = study_business - " . $num . "*"
            . $study_business . ", study_caretaking = study_caretaking - " . $num . "*"
            . $study_caretaking . ", study_housework = study_housework - " . $num . "*"
            . $study_housework . ", study_liberalarts = study_liberalarts - " . $num
            . "*" . $study_liberalarts . ", study_art = study_art - " . $num . "*"
            . $study_art . ", volunteer_health = volunteer_health - " . $num . "*"
            . $volunteer_health . ", volunteer_elderly = volunteer_elderly - " . $num
            . "*" . $volunteer_elderly . ", volunteer_disable = volunteer_disable - "
            . $num . "*" . $volunteer_disable
            . ", volunteer_children = volunteer_children - " . $num . "*"
            . $volunteer_children . ", volunteer_sport = volunteer_sport - " . $num
            . "*" . $volunteer_sport . ", volunteer_town = volunteer_town - " . $num
            . "*" . $volunteer_town . ", volunteer_safety = volunteer_safety - " . $num
            . "*" . $volunteer_safety . ", volunteer_nature = volunteer_nature - "
            . $num . "*" . $volunteer_nature
            . ", volunteer_disaster = volunteer_disaster - " . $num . "*"
            . $volunteer_disaster
            . ", volunteer_international = volunteer_international - " . $num . "*"
            . $volunteer_international
            . ", hobby_musicalinstrument = hobby_musicalinstrument - " . $num . "*"
            . $hobby_musicalinstrument . ", hobby_chorus = hobby_chorus - " . $num
            . "*" . $hobby_chorus . ", hobby_dance = hobby_dance - " . $num . "*"
            . $hobby_dance . ", hobby_shodo = hobby_shodo - " . $num . "*" . $hobby_shodo
            . ", hobby_kado = hobby_kado - " . $num . "*" . $hobby_kado
            . ", hobby_sado = hobby_sado - " . $num . "*" . $hobby_sado
            . ", hobby_wasai = hobby_wasai - " . $num . "*" . $hobby_wasai
            . ", hobby_knit = hobby_knit - " . $num . "*" . $hobby_knit
            . ", hobby_cooking = hobby_cooking - " . $num . "*" . $hobby_cooking
            . ", hobby_gardening = hobby_gardening - " . $num . "*" . $hobby_gardening
            . ", hobby_diy = hobby_diy - " . $num . "*" . $hobby_diy
            . ", hobby_painting = hobby_painting - " . $num . "*" . $hobby_painting
            . ", hobby_pottery = hobby_pottery - " . $num . "*" . $hobby_pottery
            . ", hobby_photo = hobby_photo - " . $num . "*" . $hobby_photo
            . ", hobby_writing = hobby_writing - " . $num . "*" . $hobby_writing
            . ", hobby_go = hobby_go - " . $num . "*" . $hobby_go
            . ", hobby_camp = hobby_camp - " . $num . "*" . $hobby_camp
            . ", hobby_watchsport = hobby_watchsport - " . $num . "*"
            . $hobby_watchsport
            . ", hobby_watchperformance = hobby_watchperformance - " . $num . "*"
            . $hobby_watchperformance . ", hobby_watchmovie = hobby_watchmovie - "
            . $num . "*" . $hobby_watchmovie
            . ", hobby_listenmusic = hobby_listenmusic - " . $num . "*"
            . $hobby_listenmusic . ", hobby_reading = hobby_reading - " . $num . "*"
            . $hobby_reading . ", hobby_pachinko = hobby_pachinko - " . $num . "*"
            . $hobby_pachinko . ", hobby_karaoke = hobby_karaoke - " . $num . "*"
            . $hobby_karaoke . ", hobby_game = hobby_game - " . $num . "*" . $hobby_game
            . ", hobby_attraction = hobby_attraction - " . $num . "*"
            . $hobby_attraction . ", hobby_train = hobby_train - " . $num . "*"
            . $hobby_train . ", hobby_car = hobby_car - " . $num . "*" . $hobby_car
            . ", trip_daytrip = trip_daytrip - " . $num . "*" . $trip_daytrip
            . ", trip_domestic = trip_domestic - " . $num . "*" . $trip_domestic
            . ", trip_international = trip_international - " . $num . "*"
            . $trip_international . ", sport_baseball = sport_baseball - " . $num . "*"
            . $sport_baseball . ", sport_tabletennis = sport_tabletennis - " . $num
            . "*" . $sport_tabletennis . ", sport_tennis = sport_tennis - " . $num . "*"
            . $sport_tennis . ", sport_badminton = sport_badminton - " . $num . "*"
            . $sport_badminton . ", sport_golf = sport_golf - " . $num . "*"
            . $sport_golf . ", sport_gateball = sport_gateball - " . $num . "*"
            . $sport_gateball . ", sport_bowling = sport_bowling - " . $num . "*"
            . $sport_bowling . ", sport_fishing = sport_fishing - " . $num . "*"
            . $sport_fishing . ", sport_swimming = sport_swimming - " . $num . "*"
            . $sport_swimming . ", sport_skiing = sport_skiing - " . $num . "*"
            . $sport_skiing . ", sport_climbing = sport_climbing - " . $num . "*"
            . $sport_climbing . ", sport_cycling = sport_cycling - " . $num . "*"
            . $sport_cycling . ", sport_jogging = sport_jogging - " . $num . "*"
            . $sport_jogging . ", sport_walking = sport_walking - " . $num . "*"
            . $sport_walking . " WHERE userno = '" . $workerno . "'")
        or die ("Query error: " . mysql_error());
    }
}


function updateMatchingParam_work($workparam, $groupno, $workid, $num)
{
    extract($workparam);
    mysql_query("UPDATE matchingparam_work SET worktype_prune = worktype_prune + "
        . $num . "*" . $worktype_prune
        . ", worktype_agriculture = worktype_agriculture + " . $num . "*"
        . $worktype_agriculture . ", worktype_cleaning = worktype_cleaning + " . $num
        . "*" . $worktype_cleaning . ", worktype_housework = worktype_housework + "
        . $num . "*" . $worktype_housework
        . ", worktype_shopping = worktype_shopping + " . $num . "*"
        . $worktype_shopping . ", worktype_repair = worktype_repair + " . $num . "*"
        . $worktype_repair . ", worktype_caretaking = worktype_caretaking + " . $num
        . "*" . $worktype_caretaking . ", worktype_teaching = worktype_teaching + "
        . $num . "*" . $worktype_teaching
        . ", worktype_consulting = worktype_consulting + " . $num . "*"
        . $worktype_consulting . ", study_english = study_english + " . $num . "*"
        . $study_english . ", study_foreignlanguage = study_foreignlanguage + "
        . $num . "*" . $study_foreignlanguage . ", study_it = study_it + " . $num . "*"
        . $study_it . ", study_business = study_business + " . $num . "*"
        . $study_business . ", study_caretaking = study_caretaking + " . $num . "*"
        . $study_caretaking . ", study_housework = study_housework + " . $num . "*"
        . $study_housework . ", study_liberalarts = study_liberalarts + " . $num . "*"
        . $study_liberalarts . ", study_art = study_art + " . $num . "*" . $study_art
        . ", volunteer_health = volunteer_health + " . $num . "*" . $volunteer_health
        . ", volunteer_elderly = volunteer_elderly + " . $num . "*"
        . $volunteer_elderly . ", volunteer_disable = volunteer_disable + " . $num
        . "*" . $volunteer_disable . ", volunteer_children = volunteer_children + "
        . $num . "*" . $volunteer_children . ", volunteer_sport = volunteer_sport + "
        . $num . "*" . $volunteer_sport . ", volunteer_town = volunteer_town + " . $num
        . "*" . $volunteer_town . ", volunteer_safety = volunteer_safety + " . $num . "*"
        . $volunteer_safety . ", volunteer_nature = volunteer_nature + " . $num . "*"
        . $volunteer_nature . ", volunteer_disaster = volunteer_disaster + " . $num
        . "*" . $volunteer_disaster
        . ", volunteer_international = volunteer_international + " . $num . "*"
        . $volunteer_international
        . ", hobby_musicalinstrument = hobby_musicalinstrument + " . $num . "*"
        . $hobby_musicalinstrument . ", hobby_chorus = hobby_chorus + " . $num . "*"
        . $hobby_chorus . ", hobby_dance = hobby_dance + " . $num . "*" . $hobby_dance
        . ", hobby_shodo = hobby_shodo + " . $num . "*" . $hobby_shodo
        . ", hobby_kado = hobby_kado + " . $num . "*" . $hobby_kado
        . ", hobby_sado = hobby_sado + " . $num . "*" . $hobby_sado
        . ", hobby_wasai = hobby_wasai + " . $num . "*" . $hobby_wasai
        . ", hobby_knit = hobby_knit + " . $num . "*" . $hobby_knit
        . ", hobby_cooking = hobby_cooking + " . $num . "*" . $hobby_cooking
        . ", hobby_gardening = hobby_gardening + " . $num . "*" . $hobby_gardening
        . ", hobby_diy = hobby_diy + " . $num . "*" . $hobby_diy
        . ", hobby_painting = hobby_painting + " . $num . "*" . $hobby_painting
        . ", hobby_pottery = hobby_pottery + " . $num . "*" . $hobby_pottery
        . ", hobby_photo = hobby_photo + " . $num . "*" . $hobby_photo
        . ", hobby_writing = hobby_writing + " . $num . "*" . $hobby_writing
        . ", hobby_go = hobby_go + " . $num . "*" . $hobby_go
        . ", hobby_camp = hobby_camp + " . $num . "*" . $hobby_camp
        . ", hobby_watchsport = hobby_watchsport + " . $num . "*" . $hobby_watchsport
        . ", hobby_watchperformance = hobby_watchperformance + " . $num . "*"
        . $hobby_watchperformance . ", hobby_watchmovie = hobby_watchmovie + " . $num
        . "*" . $hobby_watchmovie . ", hobby_listenmusic = hobby_listenmusic + " . $num
        . "*" . $hobby_listenmusic . ", hobby_reading = hobby_reading + " . $num . "*"
        . $hobby_reading . ", hobby_pachinko = hobby_pachinko + " . $num . "*"
        . $hobby_pachinko . ", hobby_karaoke = hobby_karaoke + " . $num . "*"
        . $hobby_karaoke . ", hobby_game = hobby_game + " . $num . "*" . $hobby_game
        . ", hobby_attraction = hobby_attraction + " . $num . "*" . $hobby_attraction
        . ", hobby_train = hobby_train + " . $num . "*" . $hobby_train
        . ", hobby_car = hobby_car + " . $num . "*" . $hobby_car
        . ", trip_daytrip = trip_daytrip + " . $num . "*" . $trip_daytrip
        . ", trip_domestic = trip_domestic + " . $num . "*" . $trip_domestic
        . ", trip_international = trip_international + " . $num . "*"
        . $trip_international . ", sport_baseball = sport_baseball + " . $num . "*"
        . $sport_baseball . ", sport_tabletennis = sport_tabletennis + " . $num . "*"
        . $sport_tabletennis . ", sport_tennis = sport_tennis + " . $num . "*"
        . $sport_tennis . ", sport_badminton = sport_badminton + " . $num . "*"
        . $sport_badminton . ", sport_golf = sport_golf + " . $num . "*" . $sport_golf
        . ", sport_gateball = sport_gateball + " . $num . "*" . $sport_gateball
        . ", sport_bowling = sport_bowling + " . $num . "*" . $sport_bowling
        . ", sport_fishing = sport_fishing + " . $num . "*" . $sport_fishing
        . ", sport_swimming = sport_swimming + " . $num . "*" . $sport_swimming
        . ", sport_skiing = sport_skiing + " . $num . "*" . $sport_skiing
        . ", sport_climbing = sport_climbing + " . $num . "*" . $sport_climbing
        . ", sport_cycling = sport_cycling + " . $num . "*" . $sport_cycling
        . ", sport_jogging = sport_jogging + " . $num . "*" . $sport_jogging
        . ", sport_walking = sport_walking + " . $num . "*" . $sport_walking
        . " WHERE groupno = '" . $groupno . "' and workid = '" . $workid . "'")
    or die ("Query error: " . mysql_error());
}

?>