<?php
class Log
{
    private $db;
    public function __construct(){ $this->db = db_connect(); }

    public function record($u,$status): void
    {
        $this->db->prepare("INSERT INTO login_log(username,status) VALUES(?,?)")
                 ->execute([$u,$status]);
    }

    public function tooManyBad($u): bool
    {
        $q="SELECT COUNT(*) FROM login_log
            WHERE username=? AND status='bad'
              AND ts > DATE_SUB(NOW(), INTERVAL 60 SECOND)";
        $st=$this->db->prepare($q); $st->execute([$u]);
        return $st->fetchColumn() >= 3;
    }
}