<?php

class Notifications
{

    private ?DB $_db;
    private ?ActivityLogger $log;

    protected $tableName;



    public function __construct()
    {
        $this->_db = DB::getInstance();
        $this->log = new ActivityLogger();

        $this->tableName = 'partners_notifications';
    }

    public function getNotifications($filters = [])
    {
        $data = $this->_db->get("*", $this->tableName, $filters);
        if (!$data->error()) {
            if (!$data->count()) {
                return $data->results();
            } else {
                return [];
            }
        }
        return ["error" => 1];
    }

    public function getNotificationsCustomData($sql, $parameters = [])
    {
        $notificationsCustomData = $this->_db->query($sql, $parameters)->results();
        return $notificationsCustomData;
    }

    protected function getNotification($notificationId)
    {
        $data = $this->_db->get("*", $this->tableName, [["id", "=", $notificationId]]);
        if (!$data->error()) {
            if (!$data->count()) {
                return $data->first();
            } else {
                return [];
            }
        }
        return ["error" => 1];
    }

    public function checkUnseenNotifications()
    {
        $partner = new user();
        $partnerId = $partner->data()["id"];

        $data = $this->_db->get("id", $this->tableName, [["partner_id", "=", $partnerId], ["status", "=", 0]]);
        if (!$data->error()) {
            return $data->count();
        }
        return -1;
    }

    public function markNotificationsAsSeen()
    {

        $partner = new user();
        $partnerId = $partner->data()["id"];

        if ($this->tableName != "partners_notifications") {
            return;
        }
        $markNotificationsSeen = $this->_db->update($this->tableName, [["partner_id", "=", $partnerId]], ["status" => 1]);
        return;
    }

}
