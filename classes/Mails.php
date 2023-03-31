<?php

class Mails
{

    private ?DB $_db;
    private ?ActivityLogger $log;

    protected $tableName;



    public function __construct()
    {
        $this->_db = DB::getInstance();
        $this->log = new ActivityLogger();

        $this->tableName = 'partners_mailbox';
    }

    // $fields = ["subject", "message", "type", "created_at"]
    public function getMails($filters = [])
    {
        $data = $this->_db->get("*", $this->tableName, $filters);
        if (!$data->error()) {
            if ($data->count()) {
                return $data->results();
            } else {
                return [];
            }
        }
        return ["error" => 1];
    }

    public function getMailsCustomData($sql, $parameters = [])
    {
        $mailsCustomData = $this->_db->query($sql, $parameters)->results();
        return $mailsCustomData;
    }

    protected function getMail($mailId)
    {
        $data = $this->_db->get("*", $this->tableName, [["id", "=", $mailId]]);
        if (!$data->error()) {
            if ($data->count()) {
                return $data->first();
            } else {
                return [];
            }
        }
        return ["error" => 1];
    }

    public function replyMail($mailId, $mail, $action = '', $description = '')
    {
        $partner = new user();
        $partnerId = $partner->data()["id"];

        $fields["partner_id"] = $partnerId;
        $fields["mail_id"] = $mailId;
        $fields["message"] = $mail;

        $sent = $this->_db->insert("partners_replies", $fields);
        if ($sent->error()) {
            return false;
        }

        //log the action
        $this->log->addLog($action, $description);

        return true;
    }

    public function getPreviousReplies($mailId, $partnerId)
    {
        $data = $this->_db->get("*", "partners_replies", [["mail_id", "=", $mailId], ["partner_id", "=", $partnerId]], 'ORDER BY created_at desc');
        if (!$data->error()) {
            return $data->results();
        }
        return ["error" => 1];
    }

    public function marOneMailAsSeen($mailId)
    {

        $marOneMailAsSeen = $this->_db->update($this->tableName, [["id", "=", $mailId]], ["status" => 1]);

        return;
    }

    public function checkUnseenMails()
    {
        $partner = new user();
        $partnerId = $partner->data()["id"];

        $data = $this->_db->get("id", $this->tableName, [["receiver", "=", $partnerId], ["status", "=", 0]]);
        if (!$data->error()) {
            return $data->count();
        }
        return -1;
    }

}
