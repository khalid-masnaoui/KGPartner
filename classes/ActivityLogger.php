<?php
require_once __DIR__ . "/../functions/getip.php";
require_once __DIR__ . "/../functions/curlgetcontent.php";

/* 
 * A class for logging users/admins/partners actions
 *
 * Written by: khalid el masnaoui
 *
 */

class ActivityLogger
{
    private ?DB $db;
    protected array $logsData = ["partner_id" => '', "action" => '', "page" => '', "object" => '', "description" => '', "ip_address" => '', "country_code" => ''];

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->db = DB::getInstance();

    }

    /**
     * addLog : addlog for the activity
     *
     * @param  string $action
     * @param  string $description
     * @param  ?string $object
     * @return bool
     */
    function addLog(string $action, string $description, ?string $object = null): bool
    {

        //prepare logs data
        $page = isset($_SERVER['HTTP_REFERER']) ? basename($_SERVER['HTTP_REFERER']) : '';
        $page = explode('.', $page);
        $page = $page[0];

        $ip = getIP();

        $partner = new user();
        $partner_id = $partner->data()["id"];

        $this->logsData["partner_id"] = $partner_id;
        $this->logsData["action"] = $action;
        $this->logsData["page"] = $page;
        $this->logsData["object"] = $object;
        $this->logsData["description"] = $description;
        $this->logsData["ip_address"] = trim($ip);

        //get country code
        $countryCode = null;

        $ipData = @json_decode(curl_get_file_contents("http://ip-api.com/json/$ip"));

        if ($ipData && $ipData->countryCode != null) {
            $countryCode = $ipData->countryCode;
        }

        $this->logsData["country_code"] = $countryCode;

        return $this->registerLog();
    }

    /**
     * registerLog : insert log into db
     *
     * @return bool
     */
    function registerLog(): bool
    {

        $inserted = $this->db->insert('partner_activity_logs', $this->logsData);
        if ($inserted->error()) {
            return false;
        }
        return true;


    }
}
?>
