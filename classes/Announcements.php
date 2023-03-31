<?php

class Announcements
{

    private ?DB $_db;
    private ?ActivityLogger $log;

    protected $tableName;



    public function __construct()
    {
        $this->_db = DB::getInstance();
        $this->log = new ActivityLogger();

        $this->tableName = 'partners_announcements';
    }

    public function getAnnouncements($filters = [])
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

    public function getAnnouncementsCustomData($sql, $parameters = [])
    {
        $announcementsCustomData = $this->_db->query($sql, $parameters)->results();
        return $announcementsCustomData;
    }

    protected function getAnnouncement($announcementId)
    {
        $data = $this->_db->get("*", $this->tableName, ["id", "=", $announcementId]);
        if (!$data->error()) {
            if (!$data->count()) {
                return $data->first();
            } else {
                return [];
            }
        }
        return ["error" => 1];
    }
}
