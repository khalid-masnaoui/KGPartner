<?php

/**
 * Summary of Rates
 * @author KhalidElMasnaoui
 * @copyright (c)) 2023
 */
class Rates
{
    /**
     * Summary of _db
     * @var DB|null
     */
    private ?DB $_db;
    /**
     * Summary of log
     * @var ActivityLogger|null
     */
    private ?ActivityLogger $log;
    /**
     * Summary of tableName
     * @var string
     */
    protected string $tableName;

    /**
     * Summary of __construct
     */
    public function __construct()
    {
        $this->_db = DB::getInstance();
        $this->log = new ActivityLogger();

        $this->tableName = 'clients_partners_rates';
    }

    /**
     * Summary of getClientsCommissionsRates
     * @param int $clientId
     * @return array
     */
    public function getClientsCommissionsRates($clientId)
    {
        $data = $this->_db->get("*", $this->tableName, [["client_id", "=", $clientId]]);
        if (!$data->error()) {
            if ($data->count()) {
                return $data->results();
            } else {
                return [];
            }
        }
        return ["error" => 1];
    }

    /**
     * Summary of getClientsCommissionsRatesPartner
     * @param int $partnerId
     * @return array
     */
    public function getClientsCommissionsRatesPartner($partnerId)
    {
        $data = $this->_db->get("*", $this->tableName, [["partner_id", "=", $partnerId]]);
        if (!$data->error()) {
            if ($data->count()) {
                return $data->results();
            } else {
                return [];
            }
        }
        return ["error" => 1];
    }

    /**
     * Summary of getClientHeadParentRate
     * @param int $clientId
     * @return array
     */
    public function getClientHeadParentRate($clientId)
    {
        $data = $this->_db->get("pt_id, rate", "clients", [["id", "=", $clientId]]);
        if (!$data->error()) {
            $parentPtId = $data->first()["pt_id"];

            if ($parentPtId !== 'self') {
                $parentIds = array_filter(explode("/", $parentPtId));
                $headParentId = $parentIds[0];

                $rateData = $this->_db->get("rate", "partner_users", [["id", "=", $headParentId]]);
                if (!$rateData->error()) {
                    return $rateData->first();
                }
            } else {
                return array("rate" => $data->first()["rate"]);
            }

        }
        return ["error" => 1];
    }

    /**
     * Summary of getClientsCommissionsRatesCustom
     * @param string $sql
     * @param array $parametersQuery
     * @return array|bool
     */
    public function getClientsCommissionsRatesCustom($sql, $parametersQuery)
    {

        $data = $this->_db->query($sql, $parametersQuery);
        if ($data->error()) {
            return false;
        }
        return $data->results();

    }

    /**
     * Summary of upsertCommissionsRates
     * @param int $parentPtId
     * @param string $clientId
     * @param string $clientRate
     * @return bool
     */
    public function upsertCommissionsRates($parentPtId, $clientId, $clientRate)
    {
        $parentIds = array_filter(explode("/", $parentPtId));

        $count = count($parentIds);
        $holders = array_fill(0, $count, '?');
        $holders = implode(",", $holders);



        //get the parents rates
        $sql = "SELECT id , rate FROM partner_users where id IN ($holders) order by id desc"; //or order by rate desc
        $parentsRatesQuery = $this->_db->query($sql, $parentIds);
        if ($parentsRatesQuery->error()) {
            return false;
        }
        $parentsRates = $parentsRatesQuery->results();


        //calculate commission rates for every partner for the specific client
        $baseRate = $clientRate;
        $upsertSql = "INSERT INTO {$this->tableName} (client_id, partner_id, rate) VALUES ";
        $parametersQuery = [];
        foreach ($parentsRates as $key => $value) {
            $rate = $value["rate"];
            $commissionRate = $baseRate - $rate;
            $baseRate = $rate;

            $upsertSql .= " (?,?,?),";
            $parametersQuery[] = $clientId;
            $parametersQuery[] = $value["id"];
            $parametersQuery[] = $commissionRate;
        }

        $upsertSql = rtrim($upsertSql, ",");

        $upsertSql .= " ON DUPLICATE KEY UPDATE rate = VALUES(rate)";

        $upsertQuery = $this->_db->query($upsertSql, $parametersQuery);
        if ($upsertQuery->error()) {
            return false;
        }

        return true;
    }

    /**
     * Summary of upsertCommissionsRatesPartner
     * @param int $partnerId
     * @return void
     */
    public function upsertCommissionsRatesPartner($partnerId)
    {

        $sql = "SELECT cl.pt_id , cl.rate as clientRate, cpr.client_id FROM {$this->tableName} cpr JOIN clients cl ON  cpr.client_id = cl.id where cpr.partner_id = ?";
        $relatedClients = $this->getClientsCommissionsRatesCustom($sql, [$partnerId]);

        foreach ($relatedClients as $key => $value) {
            $upsert = $this->upsertCommissionsRates($value["pt_id"], $value["client_id"], $value["clientRate"]);
        }
    }

}
