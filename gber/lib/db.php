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

    public function getMatchingParamByWorkid($workid){
        $stmt = $this->pdo->prepare('SELECT * FROM matchingparam_work WHERE workid = :workid');
        $stmt->execute(['workid' => $workid]);
        $row = $stmt->fetch();
        unset($row['matchingparamid']);
        unset($row['groupno']);
        unset($row['workid']);
        return $row ? array_values($row) : [];
    }

}

$db = DB::getInstance();
$groupnamerecords =  $db->getGroupNameRecords();
$groupnamelist = array();
foreach($groupnamerecords as $key => $value){
    $groupnamelist[] = $value['groupname'];
}

?>