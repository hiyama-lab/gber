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

    public function registerUser($mail, $nickname, $pass, $date) {
        $stmt = $this->pdo->prepare('INSERT INTO db_user (mail,nickname,pass,registered_date) VALUES (:mail, :nickname, :pass, :date)');
        return $stmt->execute([':mail' => $mail,
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

}
?>