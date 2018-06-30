<?php
class DB {
    private $pdo;

    private function __construct() {
        $dsn = "mysql:host={$_ENV["MYSQL_SERVER"]};dbname={$_ENV["MYSQL_DATABASE"]};charset=utf8mb4;";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        $this->pdo = new PDO($dsn, $_ENV["MYSQL_USER"], $_ENV["MYSQL_PASSWORD"], $options);
    }

    final public static function getInstance() {
        static $instance;
        return $instance ?: $instance = new self;
    }

    final public function __clone() {
        throw new Exception("this instance is singleton class.");
    }


    public function findUserByMail($mail) {
        $stmt = $this->pdo->prepare('SELECT * FROM db_user WHERE mail = :mail');
        $stmt->execute([':mail' => $mail]);
        return $stmt->fetch();
    }

    public function findUserById($userno){
        $stmt = $this->pdo->prepare('SELECT * FROM db_user WHERE userno = :userno');
        $stmt->execute([':userno' => $userno]);
        return $stmt->fetch();
    }

    public function registerUser($mail, $nickname, $pass, $date) {
        $stmt = $this->pdo->prepare('INSERT INTO db_user (mail,nickname,pass,registered_date) VALUES (:mail, :nickname, :pass, :date)');
        return $stmt->execute([
            ':mail' => $mail,
            ':nickname' => $nickname,
            ':pass' => $pass,
            ':date' => $date
        ]);
    }

    public function isRegisteredUser($mail) {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM db_user WHERE mail = :mail');
        $stmt->execute([':mail' => $mail]);
        return $stmt->fetchColumn();
    }

    public function addToPhotodata($userno) {
        $stmt = $this->pdo->prepare('INSERT INTO photodata (userno) VALUES (:userno)');
        return $stmt->execute([':userno' => $userno]);
    }

    public function addToMatchingParam($userno) {
        $stmt = $this->pdo->prepare('INSERT INTO matchingparam_human (userno) VALUES (:userno)');
        return $stmt->execute([':userno' => $userno]);
    }

    public function addToQuestionnaire($userno) {
        $stmt = $this->pdo->prepare('INSERT INTO questionnaire_socialactivity (userno) VALUES (:userno)');
        return $stmt->execute([':userno' => $userno]);
    }

    public function addToGrouplist($groupno, $userno) {
        $stmt = $this->pdo->prepare('INSERT INTO grouplist (groupno, userno) VALUES (:groupno, :userno)');
        return $stmt->execute([':groupno' => $groupno, ':userno' => $userno]);
    }

    public function addToActivityLog($userno, $queryname){
        $date = date('Y-m-d G:i:s');
        $stmt = $this->pdo->prepare('INSERT INTO activity_logs (userno, queryname, datetime) VALUES (:userno, :queryname, :date)');
        return $stmt->execute([
            ':userno' => $userno,
            ':queryname' => $queryname,
            ':date' => $date
        ]);
    }

    public function isMaster($userno){
        $stmt = $this->pdo->prepare('SELECT master FROM db_user WHERE userno = :userno');
        $stmt->execute([':userno' => $userno]);
        return $stmt->fetchColumn() == 1 ? true : false;
    }

    public function getQandaSocial($userno){
        $stmt = $this->pdo->prepare('SELECT * FROM questionnaire_socialactivity WHERE userno = :userno');
        $stmt->execute([':userno' => $userno]);
        return $stmt->fetch();
    }

    public function getQandaWorks($userno){
        $stmt = $this->pdo->prepare('SELECT id,worktitle,summary FROM helplist WHERE id NOT IN (SELECT DISTINCT workid FROM helpmatching WHERE applyuserno= :userno ) and summary IS NOT NULL');
        $stmt->execute([':userno' => $userno]);
        return $stmt->fetchAll();
    }

    public function getGroupInfo($userno){
        $stmt = $this->pdo->prepare('SELECT groupno,admin FROM grouplist WHERE userno = :userno and groupno > 0');
        $stmt->execute([':userno' => $userno]);
        return $stmt->fetchAll();
    }

    public function getUnansweredOfferNum($userno, $groupno)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM workdate LEFT JOIN worklist ON workdate.workid=worklist.id WHERE (workerno IN (SELECT taker FROM caretakerlist WHERE giver = :userno) or workerno = :userno) and worklist.groupno = :groupno and workdate.status='0' and workdate.workday > DATE_SUB(CURRENT_DATE(),interval 1 day)");
        $stmt->execute([
            ':userno' => $userno,
            ':groupno' => $groupno
        ]);
        return $stmt->fetchColumn();
    }

    public function getUnwrittenReportNum($userno, $groupno){
        $stmt = $this->pdo->prepare("SELECT dateid FROM workdate LEFT JOIN worklist ON workdate.workid=worklist.id WHERE (workerno IN (SELECT taker FROM caretakerlist WHERE giver = :userno) or workerno = :userno) and worklist.groupno = :groupno and workdate.status='1' and reportflag='0' and workdate.workday < CURRENT_DATE()");
        $stmt->execute([
            ':userno' => $userno,
            ':groupno' => $groupno
        ]);
        return $stmt->fetchColumn();
    }

    public function getBbsNewPostNum($groupno){
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM bbs_group WHERE datetime > DATE_SUB(CURRENT_DATE(),interval 1 day) AND groupno = :groupno");
        $stmt->execute([':groupno' => $groupno]);
        return $stmt->fetchColumn();
    }

    public function getNewMessageNum($userno){
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM messageeach LEFT JOIN messagemember ON messageeach.messageid = messagemember.messageid WHERE messageeach.messagedate > DATE_SUB(CURRENT_DATE(),interval 1 day) and messagemember.memberid = :userno");
        $stmt->execute([':userno' => $userno]);
        return $stmt->fetchColumn($userno);
    }

    public function getGroupNameRecords(){
        $stmt = $this->pdo->prepare("SELECT groupname FROM groupnamelist");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getSchedule($userno, $year, $month){
        $stmt = $this->pdo->prepare("SELECT * FROM schedule WHERE userno = :userno AND year = :year AND month = :month");
        $stmt->execute([
           ':userno' => $userno,
           ':year' => $year,
           ':month' => $month
        ]);
        return $stmt->fetchAll();
    }

    public function addSchedule($userno, $year, $month){
        $stmt = $this->pdo->prepare("INSERT INTO schedule (userno, year, month) VALUES (:userno, :year, :month)");
        return $stmt->execute([
            ':userno' => $userno,
            ':year' => $year,
            ':month' => $month
        ]);
    }

    public function updateSchedule($userno, $dateprefix, $am, $pm, $year, $month, $lastupdated){
        $stmt = $this->pdo->prepare("UPDATE schedule SET ${dateprefix}_am = :am, ${dateprefix}_pm = :pm, lastupdated = :lastupdated WHERE userno = :userno AND year = :year AND month = :month");
        return $stmt->execute([
            ':userno' => $userno,
            ':am' => $am,
            ':pm' => $pm,
            ':year' => $year,
            ':month' => $month,
            ':lastupdated' => $lastupdated
        ]);
    }

    public function isClientOfWork($userno, $workid){
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM helplist WHERE id = :workid and userno = :userno");
        $stmt->execute([
            ':userno' => $userno,
            ':workid' => $workid
        ]);
        return $stmt->fetchColumn() == 1 ? true : false;
    }

    public function isGroupAdmin($userno, $groupno){
        $stmt = $this->pdo->prepare("SELECT admin FROM grouplist WHERE userno = :userno and groupno = :groupno");
        $stmt->execute([
           ':userno' => $userno,
           ':groupno' => $groupno
        ]);
        return $stmt->fetchColumn() == 1 ? true : false;
    }

    public function isSomeAdmin($userno){
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM grouplist WHERE userno = :userno and admin = 1");
        $stmt->execute([
            ':userno' => $userno,
        ]);
        return $stmt->fetchColumn() > 0 ? true : false;
    }

    public function isGroupMember($userno, $groupno){
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM grouplist WHERE userno = :userno and groupno = :groupno");
        $stmt->execute([
            ':userno' => $userno,
            ':groupno' => $groupno
        ]);
        return $stmt->fetchColumn() > 0 ? true : false;
    }

    public function updateAccountWithPass($userno, $mail, $nickname, $pass){
        $stmt = $this->pdo->prepare("UPDATE db_user SET mail = :mail, nickname = :nickname, pass = :pass WHERE userno = :userno");
        return $stmt->execute([
            ':userno' => $userno,
            ':mail' => $mail,
            ':nickname' => $nickname,
            ':pass' => $pass
        ]);
    }

    public function updateAccountWithoutPass($userno, $mail, $nickname) {
        $stmt = $this->pdo->prepare("UPDATE db_user SET mail = :mail, nickname = :nickname WHERE userno = :userno");
        return $stmt->execute([
            ':userno' => $userno,
            ':mail' => $mail,
            ':nickname' => $nickname,
        ]);
    }

    public function getOngoingWork($groupno, $userno){
        $stmt = $this->pdo->prepare("SELECT worklist.worktitle,worklist.content,worklist.workdatetime,worklist.id,workinterest.interest FROM worklist LEFT OUTER JOIN workinterest ON worklist.id = workinterest.workid AND workinterest.userno = :userno WHERE worklist.groupno = :groupno AND worklist.status='2' ORDER BY workinterest.interest LIMIT 100");
        $stmt->execute([':groupno' => $groupno, ':userno' => $userno]);
        return $stmt->fetchAll();
    }

    public function getMatchingParamByUserno($userno){
        $stmt = $this->pdo->prepare('SELECT * FROM matchingparam_human WHERE userno = :userno');
        $stmt->execute(['userno' => $userno]);
        $row = $stmt->fetch();
        unset($row['matchingparamid']);
        unset($row['userno']);
        return array_values($row);
    }

    public function getMatchingParamByWorkid($workid, $groupno){
        $stmt = $this->pdo->prepare('SELECT * FROM matchingparam_work WHERE workid = :workid AND groupno = :groupno');
        $stmt->execute([
            'workid' => $workid,
            'groupno' => $groupno
        ]);
        $row = $stmt->fetch();
        unset($row['matchingparamid']);
        unset($row['groupno']);
        unset($row['workid']);
        return $row ? array_values($row) : [];
    }

    public function getTaggedWorksAll(){
        $stmt = $this->pdo->prepare("SELECT worktitle,id FROM helplist WHERE id IN (SELECT DISTINCT workid FROM matchingparam_work WHERE groupno='0')");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTaggedWorksGroup($groupno){
        $stmt = $this->pdo->prepare("SELECT worktitle,id FROM worklist WHERE groupno = :groupno AND id IN (SELECT DISTINCT workid FROM matchingparam_work WHERE groupno = :groupno) and status < 5");
        $stmt->execute(['groupno' => $groupno]);
        return $stmt->fetchAll();
    }

    public function getUntaggedWorksAll(){
        $stmt = $this->pdo->prepare("SELECT worktitle,id FROM helplist WHERE id NOT IN (SELECT DISTINCT workid FROM matchingparam_work WHERE groupno='0')");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getUntaggedWorksGroup($groupno){
        $stmt = $this->pdo->prepare("SELECT worktitle,id FROM worklist WHERE groupno = :groupno AND id NOT IN (SELECT DISTINCT workid FROM matchingparam_work WHERE groupno = :groupno) and status < 5");
        $stmt->execute(['groupno' => $groupno]);
        return $stmt->fetchAll();
    }

    public function getWorkDetailAll($workid){
        $stmt = $this->pdo->prepare("SELECT worktitle, content FROM helplist WHERE id = :workid");
        $stmt->execute(['workid' => $workid]);
        return $stmt->fetch();
    }

    public function getWorkDetailGroup($workid){
        $stmt = $this->pdo->prepare("SELECT worktitle, content FROM worklist WHERE id = :workid");
        $stmt->execute(['workid' => $workid]);
        return $stmt->fetch();
    }

    public function getAllWorktypes($groupno){
        $stmt = $this->pdo->prepare("SELECT id, name FROM predefined_work WHERE groupno = :groupno");
        $stmt->execute(['groupno' => $groupno]);
        return $stmt->fetchAll();
    }

    public function getWorktypeById($worktypeid){
        $stmt = $this->pdo->prepare("SELECT * FROM predefined_work WHERE id = :worktypeid");
        $stmt->execute(['worktypeid' => $worktypeid]);
        return $stmt->fetch();
    }

    public function insertMatchingParamWork($workp){
        $stmt = $this->pdo->prepare("INSERT INTO matchingparam_work (groupno, workid, worktype_prune, worktype_agriculture, worktype_cleaning, worktype_housework, worktype_shopping, worktype_repair, worktype_caretaking, worktype_teaching, worktype_consulting, study_english, study_foreignlanguage, study_it, study_business, study_caretaking, study_housework, study_liberalarts, study_art, volunteer_health, volunteer_elderly, volunteer_disable, volunteer_children, volunteer_sport, volunteer_town, volunteer_safety, volunteer_nature, volunteer_disaster, volunteer_international, hobby_musicalinstrument, hobby_chorus, hobby_dance, hobby_shodo, hobby_kado, hobby_sado, hobby_wasai, hobby_knit, hobby_cooking, hobby_gardening, hobby_diy, hobby_painting, hobby_pottery, hobby_photo, hobby_writing, hobby_go, hobby_camp, hobby_watchsport, hobby_watchperformance, hobby_watchmovie, hobby_listenmusic, hobby_reading, hobby_pachinko, hobby_karaoke, hobby_game, hobby_attraction, hobby_train, hobby_car, trip_daytrip, trip_domestic, trip_international, sport_baseball, sport_tabletennis, sport_tennis, sport_badminton, sport_golf, sport_gateball, sport_bowling, sport_fishing, sport_swimming, sport_skiing, sport_climbing, sport_cycling, sport_jogging, sport_walking)
 VALUES (:groupno, :workid, :worktype_prune, :worktype_agriculture, :worktype_cleaning, :worktype_housework, :worktype_shopping, :worktype_repair, :worktype_caretaking, :worktype_teaching, :worktype_consulting, :study_english, :study_foreignlanguage, :study_it, :study_business, :study_caretaking, :study_housework, :study_liberalarts, :study_art, :volunteer_health, :volunteer_elderly, :volunteer_disable, :volunteer_children, :volunteer_sport, :volunteer_town, :volunteer_safety, :volunteer_nature, :volunteer_disaster, :volunteer_international, :hobby_musicalinstrument, :hobby_chorus, :hobby_dance, :hobby_shodo, :hobby_kado, :hobby_sado, :hobby_wasai, :hobby_knit, :hobby_cooking, :hobby_gardening, :hobby_diy, :hobby_painting, :hobby_pottery, :hobby_photo, :hobby_writing, :hobby_go, :hobby_camp, :hobby_watchsport, :hobby_watchperformance, :hobby_watchmovie, :hobby_listenmusic, :hobby_reading, :hobby_pachinko, :hobby_karaoke, :hobby_game, :hobby_attraction, :hobby_train, :hobby_car, :trip_daytrip, :trip_domestic, :trip_international, :sport_baseball, :sport_tabletennis, :sport_tennis, :sport_badminton, :sport_golf, :sport_gateball, :sport_bowling, :sport_fishing, :sport_swimming, :sport_skiing, :sport_climbing, :sport_cycling, :sport_jogging, :sport_walking)");
        return $stmt->execute($workp);
    }

    public function insertWorktype($worktype){
        $stmt = $this->pdo->prepare("INSERT INTO predefined_work (groupno, name, worktype_prune, worktype_agriculture, worktype_cleaning, worktype_housework, worktype_shopping, worktype_repair, worktype_caretaking, worktype_teaching, worktype_consulting, study_english, study_foreignlanguage, study_it, study_business, study_caretaking, study_housework, study_liberalarts, study_art, volunteer_health, volunteer_elderly, volunteer_disable, volunteer_children, volunteer_sport, volunteer_town, volunteer_safety, volunteer_nature, volunteer_disaster, volunteer_international, hobby_musicalinstrument, hobby_chorus, hobby_dance, hobby_shodo, hobby_kado, hobby_sado, hobby_wasai, hobby_knit, hobby_cooking, hobby_gardening, hobby_diy, hobby_painting, hobby_pottery, hobby_photo, hobby_writing, hobby_go, hobby_camp, hobby_watchsport, hobby_watchperformance, hobby_watchmovie, hobby_listenmusic, hobby_reading, hobby_pachinko, hobby_karaoke, hobby_game, hobby_attraction, hobby_train, hobby_car, trip_daytrip, trip_domestic, trip_international, sport_baseball, sport_tabletennis, sport_tennis, sport_badminton, sport_golf, sport_gateball, sport_bowling, sport_fishing, sport_swimming, sport_skiing, sport_climbing, sport_cycling, sport_jogging, sport_walking) VALUES (:groupno, :name, :worktype_prune, :worktype_agriculture, :worktype_cleaning, :worktype_housework, :worktype_shopping, :worktype_repair, :worktype_caretaking, :worktype_teaching, :worktype_consulting, :study_english, :study_foreignlanguage, :study_it, :study_business, :study_caretaking, :study_housework, :study_liberalarts, :study_art, :volunteer_health, :volunteer_elderly, :volunteer_disable, :volunteer_children, :volunteer_sport, :volunteer_town, :volunteer_safety, :volunteer_nature, :volunteer_disaster, :volunteer_international, :hobby_musicalinstrument, :hobby_chorus, :hobby_dance, :hobby_shodo, :hobby_kado, :hobby_sado, :hobby_wasai, :hobby_knit, :hobby_cooking, :hobby_gardening, :hobby_diy, :hobby_painting, :hobby_pottery, :hobby_photo, :hobby_writing, :hobby_go, :hobby_camp, :hobby_watchsport, :hobby_watchperformance, :hobby_watchmovie, :hobby_listenmusic, :hobby_reading, :hobby_pachinko, :hobby_karaoke, :hobby_game, :hobby_attraction, :hobby_train, :hobby_car, :trip_daytrip, :trip_domestic, :trip_international, :sport_baseball, :sport_tabletennis, :sport_tennis, :sport_badminton, :sport_golf, :sport_gateball, :sport_bowling, :sport_fishing, :sport_swimming, :sport_skiing, :sport_climbing, :sport_cycling, :sport_jogging, :sport_walking)");
        return $stmt->execute($worktype);
    }

    public function updateWorktype($worktype){
        $stmt = $this->pdo->prepare("UPDATE predefined_work SET name = :name, worktype_prune = :worktype_prune, worktype_agriculture = :worktype_agriculture, worktype_cleaning = :worktype_cleaning, worktype_housework = :worktype_housework, worktype_shopping = :worktype_shopping, worktype_repair = :worktype_repair, worktype_caretaking = :worktype_caretaking, worktype_teaching = :worktype_teaching, worktype_consulting = :worktype_consulting, study_english = :study_english, study_foreignlanguage = :study_foreignlanguage, study_it = :study_it, study_business = :study_business, study_caretaking = :study_caretaking, study_housework = :study_housework, study_liberalarts = :study_liberalarts, study_art = :study_art, volunteer_health = :volunteer_health, volunteer_elderly = :volunteer_elderly, volunteer_disable = :volunteer_disable, volunteer_children = :volunteer_children, volunteer_sport = :volunteer_sport, volunteer_town = :volunteer_town, volunteer_safety = :volunteer_safety, volunteer_nature = :volunteer_nature, volunteer_disaster = :volunteer_disaster, volunteer_international = :volunteer_international, hobby_musicalinstrument = :hobby_musicalinstrument, hobby_chorus = :hobby_chorus, hobby_dance = :hobby_dance, hobby_shodo = :hobby_shodo, hobby_kado = :hobby_kado, hobby_sado = :hobby_sado, hobby_wasai = :hobby_wasai, hobby_knit = :hobby_knit, hobby_cooking = :hobby_cooking, hobby_gardening = :hobby_gardening, hobby_diy = :hobby_diy, hobby_painting = :hobby_painting, hobby_pottery = :hobby_pottery, hobby_photo = :hobby_photo, hobby_writing = :hobby_writing, hobby_go = :hobby_go, hobby_camp = :hobby_camp, hobby_watchsport = :hobby_watchsport, hobby_watchperformance = :hobby_watchperformance, hobby_watchmovie = :hobby_watchmovie, hobby_listenmusic = :hobby_listenmusic, hobby_reading = :hobby_reading, hobby_pachinko = :hobby_pachinko, hobby_karaoke = :hobby_karaoke, hobby_game = :hobby_game, hobby_attraction = :hobby_attraction, hobby_train = :hobby_train, hobby_car = :hobby_car, trip_daytrip = :trip_daytrip, trip_domestic = :trip_domestic, trip_international = :trip_international, sport_baseball = :sport_baseball, sport_tabletennis = :sport_tabletennis, sport_tennis = :sport_tennis, sport_badminton = :sport_badminton, sport_golf = :sport_golf, sport_gateball = :sport_gateball, sport_bowling = :sport_bowling, sport_fishing = :sport_fishing, sport_swimming = :sport_swimming, sport_skiing = :sport_skiing, sport_climbing = :sport_climbing, sport_cycling = :sport_cycling, sport_jogging = :sport_jogging, sport_walking = :sport_walking WHERE id = :id AND groupno = :groupno");
        return $stmt->execute($worktype);
    }
}

$db = DB::getInstance();
$groupnamerecords =  $db->getGroupNameRecords();
$groupnamelist = array();
foreach($groupnamerecords as $key => $value){
    $groupnamelist[] = $value['groupname'];
}

?>