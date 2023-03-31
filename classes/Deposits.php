<?php

/**
 * Summary of Deposits
 * @author KhalidElMasnaoui
 * @copyright (c)) 2023
 */
class Deposits
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

        $this->tableName = 'deposits';
    }

    /**
     * Summary of getAllDeposits
     * @return array
     */
    public function getAllDeposits()
    {
        $data = $this->_db->get("*", $this->tableName, []);
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
     * Summary of getAllDepositsClient
     * @param int $clientId
     * @return array
     */
    public function getAllDepositsClient($clientId)
    {
        $data = $this->_db->get("*", $this->tableName, ["client_id", "=", $clientId]);
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
     * Summary of getAllDepositsCustom
     * @param string $sql
     * @param array $parametersQuery
     * @return array|bool
     */
    public function getAllDepositsCustom($sql, $parametersQuery)
    {

        $data = $this->_db->query($sql, $parametersQuery);
        if ($data->error()) {
            return false;
        }
        return $data->results();

    }

    /**
     * Summary of makeDeposit
     * @param array $fields
     * @param string $action
     * @param string $description
     * @param ?string $object
     * @return bool
     */
    public function makeDeposit($fields, $action = '', $description = '', $object = null, $type = 1)
    {
        $partner = new user();
        $partnerId = $partner->data()["id"];

        $fields["admin_id"] = "p:" . $partnerId;

        $make = $this->_db->insert($this->tableName, $fields);
        if ($make->error()) {
            return false;
        }
        $depositId = $make->lastId();


        //get client rate
        $rate = $this->_db->get("rate", "clients", [["id", "=", $fields["client_id"]]])->first()["rate"];

        //history {with rate?}
        $historyFields = ["amount" => $fields["amount"], "type" => $type, "rate" => $rate];
        $history = $this->insertDepositHistory($historyFields, $depositId);

        //balance of the client
        $creditAmount = $this->upsertClientBalance($fields["client_id"], $fields["amount"], $rate);
        if ($creditAmount === false) {
            return false;
        }

        $updatedParentBalance = $this->_db->query("UPDATE partner_users SET wa_balance = wa_balance + ? WHERE id = ?", [-$fields["amount"], $partnerId]);

        // // partners commissions {and history}
        // $commissions = $this->insertPartnersCommissions($depositId, $fields["client_id"], $creditAmount);


        // //site commissions && site balance
        // $siteCommissions = $this->insertSiteCommissions($depositId, $fields["client_id"], $creditAmount);

        //log the action
        $description = $description . ". <<--amount[" . $fields["amount"] . "]>>";
        $this->log->addLog($action, $description, $object);

        return true;
    }

    /**
     * Summary of updateDeposit
     * @param int $depositId
     * @param mixed $amount
     * @param string $action
     * @param string $description
     * @return bool
     */
    public function updateDeposit($depositId, $amount, $action = '', $description = '')
    {
        $partner = new user();
        $partnerId = $partner->data()["id"];

        //get deposit data
        $sql = "SELECT d.client_id, d.history_id, d.amount , dh.rate FROM {$this->tableName} d JOIN deposits_history 
                        dh ON d.history_id = dh.id WHERE d.id = ?";
        $depositData = $this->_db->query($sql, [$depositId])->first();

        //get client rate from history!
        $rate = $depositData["rate"];

        //update deposit table
        $today = new DateTime();
        $today = $today->format("Y-m-d H:i:s");

        $updateDeposit = $this->_db->update($this->tableName, [["id", "=", $depositId]], ["amount" => $amount, "updated_at" => $today]);
        if ($updateDeposit->error()) {
            return false;
        }

        //insert new deposit history {type:update}
        $historyFields = ["amount" => $amount, "type" => 2, "rate" => $rate, "history_id" => $depositData["history_id"]];
        $insertHistory = $this->_db->insert("deposits_history", $historyFields);


        //logic of updating clients balance and partners commissions
        $oldDeposit = $depositData["amount"];
        $newDeposit = $amount;

        $diffToAdd = $newDeposit - $oldDeposit;

        //update balance table
        $creditAmountUpdated = $this->upsertClientBalance($depositData["client_id"], $diffToAdd, $rate);
        if ($creditAmountUpdated === false) {
            return false;
        }

        $updatedParentBalance = $this->_db->query("UPDATE partner_users SET wa_balance = wa_balance + ? WHERE id = ?", [-$diffToAdd, $partnerId]);



        // //update partners commissions --> history
        // $commissions = $this->updatePartnersCommissions($depositId, $depositData["client_id"], $creditAmountUpdated);


        // //site commissions && site balance
        // $siteCommission = $this->updateSiteCommissions($depositId, $depositData["client_id"], $creditAmountUpdated);


        //log the action
        $description = $description . ". <<--amount[" . $depositData["amount"] . "]->[" . $amount . "]>>";
        $this->log->addLog($action, $description, $depositData["client_id"]);

        return true;
    }

    /**
     * Summary of deleteDeposit
     * @param int $depositId
     * @param string $action
     * @param string $description
     * @return bool
     */
    public function deleteDeposit($depositId, $action = '', $description = '')
    {
        $partner = new user();
        $partnerId = $partner->data()["id"];

        //get deposit data
        $sql = "SELECT d.client_id, d.history_id, d.amount , dh.rate FROM {$this->tableName} d JOIN deposits_history 
                        dh ON d.history_id = dh.id WHERE d.id = ?";
        $depositData = $this->_db->query($sql, [$depositId])->first();


        //get client rate from history!
        $rate = $depositData["rate"];

        //delete record from deposit table
        $deleteDeposit = $this->_db->delete($this->tableName, [["id", "=", $depositId]]);
        if ($deleteDeposit->error()) {
            return false;
        }

        //insert new deposit history {type:delete}
        $historyFields = ["amount" => 0, "type" => 0, "rate" => $rate, "history_id" => $depositData["history_id"]];
        $insertHistory = $this->_db->insert("deposits_history", $historyFields);


        //logic of updating clients balance and partners commissions

        //delete balance table
        $creditAmountDeleted = $this->upsertClientBalance($depositData["client_id"], -$depositData["amount"], $rate);
        if ($creditAmountDeleted === false) {
            return false;
        }

        $updatedParentBalance = $this->_db->query("UPDATE partner_users SET wa_balance = wa_balance + ? WHERE id = ?", [$depositData["amount"], $partnerId]);

        // //delete partners commissions --> history
        // $commissions = $this->updatePartnersCommissions($depositId, $depositData["client_id"], $creditAmountDeleted, 0);


        // //delete site commissions --> history
        // $siteCommission = $this->updateSiteCommissions($depositId, $depositData["client_id"], $creditAmountDeleted, 0);


        //log the action
        $this->log->addLog($action, $description, $depositData["client_id"]);

        return true;
    }

    /**
     * Summary of insertDepositHistory
     * @param array $historyFields
     * @param int $depositId
     * @return bool
     */
    public function insertDepositHistory($historyFields, $depositId)
    {
        //get client rate
        //history {with rate?}
        $history = $this->_db->insert("deposits_history", $historyFields);

        $historyId = $history->lastId();

        $updated = $this->_db->update($this->tableName, [["id", "=", $depositId]], ["history_id" => $historyId]);

        return true;
    }

    /**
     * Summary of upsertClientBalance
     * @param int $clientId
     * @param mixed $amount
     * @param mixed $rate
     * @return bool|float
     */
    public function upsertClientBalance($clientId, $amount, $rate)
    {

        $dataArray = [];
        $upsertSql = "INSERT INTO clients_balance (client_id, balance, deposit) VALUES (?,?,?) ON DUPLICATE KEY UPDATE balance = balance + VALUES(balance), deposit = deposit + VALUES(deposit)";

        $parametersQuery[] = $clientId;
        // $parametersQuery[] = ($amount / $rate) * 100;
        $parametersQuery[] = $amount;
        $parametersQuery[] = $amount;


        $upsertQuery = $this->_db->query($upsertSql, $parametersQuery);
        if ($upsertQuery->error()) {
            return false;
        }

        return ($amount / $rate) * 100;

    }

    /**
     * Summary of insertPartnersCommissions
     * @param int $depositId
     * @param int $clientId
     * @param mixed $amount
     * @param int $type
     * @return bool
     */
    public function insertPartnersCommissions($depositId, $clientId, $amount, $type = 1)
    {
        //get partner rates
        $ratesBuilder = new Rates();
        $rates = $ratesBuilder->getClientsCommissionsRates($clientId);

        if ($rates === []) {
            return true;
        }


        $dataArray = [];

        //partner commissions
        $insertCommissionSql = "INSERT INTO partners_commissions (deposit_id, client_id, partner_id, amount) VALUES ";

        //commissions history
        $insertHistory = "INSERT INTO partners_commissions_history (amount, rate, type) VALUES ";

        //partners balance
        $upsertBalanceSql = "INSERT INTO partners_balance (partner_id, balance) VALUES ";

        foreach ($rates as $key => $value) {
            $rate = $value["rate"];
            $partnerId = $value["partner_id"];

            $amountToAdd = ($amount * $rate) / 100;

            //upsert partner balance
            $upsertBalanceSql .= " (?,?),";
            $parametersBalanceQuery[] = $partnerId;
            $parametersBalanceQuery[] = $amountToAdd;

            // insert partner commission
            $insertCommissionSql .= " (?,?,?,?),";
            $parametersQuery[] = $depositId;
            $parametersQuery[] = $clientId;
            $parametersQuery[] = $partnerId;
            $parametersQuery[] = $amountToAdd;

            // insert history
            $insertHistory .= " (?,?,?),";
            $parametersQueryHistory[] = $amountToAdd;
            $parametersQueryHistory[] = $rate;
            $parametersQueryHistory[] = $type;


        }

        $insertCommissionSql = rtrim($insertCommissionSql, ",");
        $insertHistory = rtrim($insertHistory, ",");

        $upsertBalanceSql = rtrim($upsertBalanceSql, ",");
        $upsertBalanceSql .= " ON DUPLICATE KEY UPDATE balance = balance + VALUES(balance)";


        //commissions
        $upsertQuery = $this->_db->query($insertCommissionSql, $parametersQuery);
        if ($upsertQuery->error()) {
            return false;
        }
        //no auto increment !!!
        $firstPartnerCommissionsId = $upsertQuery->lastId(); //the first last id from the multiple values


        //balance
        $upsertBalanceQuery = $this->_db->query($upsertBalanceSql, $parametersBalanceQuery);
        if ($upsertBalanceQuery->error()) {
            return false;
        }


        $count = count($rates);



        //insert history
        $historyQuery = $this->_db->query($insertHistory, $parametersQueryHistory);
        if ($historyQuery->error()) {
            return false;
        }
        $firstHistoryId = $historyQuery->lastId();

        for ($i = 0; $i < $count; $i++) {

            $j = $i + 2 + $i * 3; //2,6,10,14 
            $partnerId = $parametersQuery[$j];

            $historyId = $firstHistoryId + $i;

            $updated = $this->_db->update("partners_commissions", [["deposit_id", "=", $depositId], ["partner_id", "=", $partnerId]], ["history_id" => $historyId]);
        }

        return true;

    }

    /**
     * Summary of updatePartnersCommissions
     * @param int $depositId
     * @param int $clientId
     * @param mixed $amount
     * @param int $type
     * @return bool
     */
    public function updatePartnersCommissions($depositId, $clientId, $amount, $type = 2)
    {

        //get commissions data [rate, partner_id, history_id]
        $sql = "SELECT pc.partner_id, pc.history_id, pch.rate, pc.amount FROM partners_commissions pc JOIN partners_commissions_history pch ON pc.history_id = pch.id WHERE pc.deposit_id = ?";
        $commissionData = $this->_db->query($sql, [$depositId])->results();

        if ($commissionData === []) {
            return true;
        }


        $dataArray = [];

        //partner commissions
        if ($type === 2) {
            $today = new DateTime();
            $today = $today->format("Y-m-d H:i:s");

            $updateCommissionSql = "INSERT INTO partners_commissions (deposit_id, client_id, partner_id, amount, updated_at) VALUES ";
        }

        //commissions history
        $insertHistorySql = "INSERT INTO partners_commissions_history (amount, rate, type, history_id) VALUES ";

        //partners balance
        $upsertBalanceSql = "INSERT INTO partners_balance (partner_id, balance) VALUES ";

        foreach ($commissionData as $key => $value) {
            $rate = $value["rate"];
            $partnerId = $value["partner_id"];
            $historyId = $value["history_id"];

            $amountToAdd = ($amount * $rate) / 100;

            //upsert partner balance
            $upsertBalanceSql .= " (?,?),";
            $parametersBalanceQuery[] = $partnerId;
            $parametersBalanceQuery[] = $amountToAdd;

            if ($type === 2) {
                // update partner commission
                $updateCommissionSql .= " (?,?,?,?,?),";
                $parametersQuery[] = $depositId;
                $parametersQuery[] = $clientId;
                $parametersQuery[] = $partnerId;
                $parametersQuery[] = $amountToAdd;
                $parametersQuery[] = $today;

            }

            // insert history
            $historyAmount = $value["amount"] + $amountToAdd;
            if ($type === 0) {
                $historyAmount = 0.00;
            }
            $insertHistorySql .= " (?,?,?,?),";
            $parametersQueryHistory[] = $historyAmount;
            $parametersQueryHistory[] = $rate;
            $parametersQueryHistory[] = $type;
            $parametersQueryHistory[] = $historyId;



        }

        if ($type === 2) {
            $updateCommissionSql = rtrim($updateCommissionSql, ",");
            $updateCommissionSql .= " ON DUPLICATE KEY UPDATE amount = amount + VALUES(amount), updated_at = VALUES(updated_at)";
        }


        $insertHistorySql = rtrim($insertHistorySql, ",");

        $upsertBalanceSql = rtrim($upsertBalanceSql, ",");
        $upsertBalanceSql .= " ON DUPLICATE KEY UPDATE balance = balance + VALUES(balance)";


        //commissions
        if ($type === 2) {
            $updateCommissionQuery = $this->_db->query($updateCommissionSql, $parametersQuery);
            if ($updateCommissionQuery->error()) {
                return false;
            }
        }
        if ($type === 0) {
            //delete record from partners commissions table
            $deleteCommission = $this->_db->delete("partners_commissions", [["deposit_id", "=", $depositId]]);
            if ($deleteCommission->error()) {
                return false;
            }
        }

        //balance
        $updateBalanceQuery = $this->_db->query($upsertBalanceSql, $parametersBalanceQuery);
        if ($updateBalanceQuery->error()) {
            return false;
        }


        //insert history
        $historyUpdateQuery = $this->_db->query($insertHistorySql, $parametersQueryHistory);
        if ($historyUpdateQuery->error()) {
            return false;
        }

        return true;

    }

    /**
     * Summary of insertSiteCommissions
     * @param int $depositId
     * @param int $clientId
     * @param mixed $amount
     * @param int $type
     * @return bool
     */
    public function insertSiteCommissions($depositId, $clientId, $amount, $type = 1)
    {
        //get Head partner rate --> site rate
        $ratesBuilder = new Rates();
        $rate = $ratesBuilder->getClientHeadParentRate($clientId);

        if ($rate === []) {
            return true;
        }


        $dataArray = [];

        //site commissions
        $insertCommissionSql = "INSERT INTO site_commissions (deposit_id, client_id, amount) VALUES ";

        //commissions history
        $insertHistory = "INSERT INTO site_commissions_history (amount, rate, type) VALUES ";

        //site balance
        $siteId = 1;
        $upsertBalanceSql = "INSERT INTO site_balance (site_id, balance) VALUES ";

        //construct the queries parameters and values
        $rate = $rate["rate"];
        $amountToAdd = ($amount * $rate) / 100;

        //upsert site balance
        $upsertBalanceSql .= " (?,?),";
        $parametersBalanceQuery[] = $siteId;
        $parametersBalanceQuery[] = $amountToAdd;

        // insert partner commission
        $insertCommissionSql .= " (?,?,?),";
        $parametersQuery[] = $depositId;
        $parametersQuery[] = $clientId;
        $parametersQuery[] = $amountToAdd;

        // insert history
        $insertHistory .= " (?,?,?),";
        $parametersQueryHistory[] = $amountToAdd;
        $parametersQueryHistory[] = $rate;
        $parametersQueryHistory[] = $type;



        $insertCommissionSql = rtrim($insertCommissionSql, ",");
        $insertHistory = rtrim($insertHistory, ",");

        $upsertBalanceSql = rtrim($upsertBalanceSql, ",");
        $upsertBalanceSql .= " ON DUPLICATE KEY UPDATE balance = balance + VALUES(balance)";


        //commissions
        $upsertQuery = $this->_db->query($insertCommissionSql, $parametersQuery);
        if ($upsertQuery->error()) {
            return false;
        }

        //balance
        $upsertBalanceQuery = $this->_db->query($upsertBalanceSql, $parametersBalanceQuery);
        if ($upsertBalanceQuery->error()) {
            return false;
        }


        //insert history
        $historyQuery = $this->_db->query($insertHistory, $parametersQueryHistory);
        if ($historyQuery->error()) {
            return false;
        }
        $historyId = $historyQuery->lastId();

        $updated = $this->_db->update("site_commissions", [["deposit_id", "=", $depositId]], ["history_id" => $historyId]);


        return true;

    }

    /**
     * Summary of updateSiteCommissions
     * @param int $depositId
     * @param int $clientId
     * @param mixed $amount
     * @param int $type
     * @return bool
     */
    public function updateSiteCommissions($depositId, $clientId, $amount, $type = 2)
    {
        //get commissions data [rate, history_id]
        $sql = "SELECT sc.history_id, sch.rate, sc.amount FROM site_commissions sc JOIN site_commissions_history sch ON sc.history_id = sch.id WHERE sc.deposit_id = ?";
        $commissionData = $this->_db->query($sql, [$depositId])->first();

        if ($commissionData === []) {
            return true;
        }


        $dataArray = [];

        //site commissions
        if ($type === 2) {
            $today = new DateTime();
            $today = $today->format("Y-m-d H:i:s");

            $updateCommissionSql = "INSERT INTO site_commissions (deposit_id, client_id, amount, updated_at) VALUES ";
        }

        //commissions history
        $insertHistory = "INSERT INTO site_commissions_history (amount, rate, type, history_id) VALUES ";

        //site balance
        $siteId = 1;
        $upsertBalanceSql = "INSERT INTO site_balance (site_id, balance) VALUES ";

        //construct the queries parameters and values
        $rate = $commissionData["rate"];
        $historyId = $commissionData["history_id"];

        $amountToAdd = ($amount * $rate) / 100;

        //upsert site balance
        $upsertBalanceSql .= " (?,?),";
        $parametersBalanceQuery[] = $siteId;
        $parametersBalanceQuery[] = $amountToAdd;

        // update partner commission
        if ($type === 2) {
            $updateCommissionSql .= " (?,?,?,?),";
            $parametersQuery[] = $depositId;
            $parametersQuery[] = $clientId;
            $parametersQuery[] = $amountToAdd;
            $parametersQuery[] = $today;
        }

        // insert history
        $historyAmount = $commissionData["amount"] + $amountToAdd;
        if ($type === 0) {
            $historyAmount = 0.00;
        }
        $insertHistory .= " (?,?,?,?),";
        $parametersQueryHistory[] = $historyAmount;
        $parametersQueryHistory[] = $rate;
        $parametersQueryHistory[] = $type;
        $parametersQueryHistory[] = $historyId;



        if ($type === 2) {
            $updateCommissionSql = rtrim($updateCommissionSql, ",");
            $updateCommissionSql .= " ON DUPLICATE KEY UPDATE amount = amount + VALUES(amount), updated_at = VALUES(updated_at)";

        }


        $insertHistory = rtrim($insertHistory, ",");

        $upsertBalanceSql = rtrim($upsertBalanceSql, ",");
        $upsertBalanceSql .= " ON DUPLICATE KEY UPDATE balance = balance + VALUES(balance)";


        //commissions
        //update
        if ($type === 2) {
            $updateQuery = $this->_db->query($updateCommissionSql, $parametersQuery);
            if ($updateQuery->error()) {
                return false;
            }
        }
        //delete
        if ($type === 0) {
            //delete record from site commissions table
            $deleteCommission = $this->_db->delete("site_commissions", [["deposit_id", "=", $depositId]]);
            if ($deleteCommission->error()) {
                return false;
            }
        }

        //balance
        $upsertBalanceQuery = $this->_db->query($upsertBalanceSql, $parametersBalanceQuery);
        if ($upsertBalanceQuery->error()) {
            return false;
        }



        //insert history
        $historyUpdateQuery = $this->_db->query($insertHistory, $parametersQueryHistory);
        if ($historyUpdateQuery->error()) {
            return false;
        }


        return true;

    }


    /**
     * Summary of makeWaBalanceDeposit
     * @param array $fields
     * @param string $action
     * @param string $description
     * @param ?string $object
     * @return bool
     */
    public function makeWaBalanceDeposit($fields, $action = '', $description = '', $object = null, $type = 1)
    {
        $partner = new user();
        $partnerId = $partner->data()["id"];

        //cant make deposit for itself
        if ($partnerId == $fields["partner_id"]) {
            return false;
        }

        $fields["admin_id"] = "p:" . $partnerId;

        $make = $this->_db->insert("wa_balance_deposits", $fields);
        if ($make->error()) {
            return false;
        }
        $depositId = $make->lastId();

        //history 
        $historyFields = ["amount" => $fields["amount"], "type" => $type];
        $history = $this->insertWaBalanceDepositHistory($historyFields, $depositId);

        //balance of the partner
        $updatedBalance = $this->_db->query("UPDATE partner_users SET wa_balance = wa_balance + ? WHERE id = ?", [$fields["amount"], $fields["partner_id"]]);
        if ($updatedBalance->error()) {
            return false;
        }

        $updatedParentBalance = $this->_db->query("UPDATE partner_users SET wa_balance = wa_balance + ? WHERE id = ?", [-$fields["amount"], $partnerId]);

        //log the action
        $description = $description . ". <<--amount[" . $fields["amount"] . "]>>";
        $this->log->addLog($action, $description, $object);

        return true;
    }

    /**
     * Summary of insertWaBalanceDepositHistory
     * @param array $historyFields
     * @param int $depositId
     * @return bool
     */
    public function insertWaBalanceDepositHistory($historyFields, $depositId)
    {
        //history {with rate?}
        $history = $this->_db->insert("wa_balance_history", $historyFields);

        $historyId = $history->lastId();

        $updated = $this->_db->update("wa_balance_deposits", [["id", "=", $depositId]], ["history_id" => $historyId]);

        return true;
    }

    /**
     * Summary of deleteWaBalanceDeposit
     * @param int $depositId
     * @param string $action
     * @param string $description
     * @return bool
     */
    public function deleteWaBalanceDeposit($depositId, $action = '', $description = '')
    {
        $partner = new user();
        $partnerId = $partner->data()["id"];

        //get deposit data
        $sql = "SELECT d.partner_id, d.history_id, d.amount  FROM wa_balance_deposits d WHERE d.id = ?";
        $depositData = $this->_db->query($sql, [$depositId])->first();

        //delete record from deposit table
        $deleteDeposit = $this->_db->delete("wa_balance_deposits", [["id", "=", $depositId]]);
        if ($deleteDeposit->error()) {
            return false;
        }

        //insert new deposit history {type:delete}
        $historyFields = ["amount" => 0, "type" => 0, "history_id" => $depositData["history_id"]];
        $insertHistory = $this->_db->insert("wa_balance_history", $historyFields);

        //balance of the partner
        $updatedBalance = $this->_db->query("UPDATE partner_users SET wa_balance = wa_balance + ? WHERE id = ?", [-$depositData["amount"], $depositData["partner_id"]]);
        if ($updatedBalance->error()) {
            return false;
        }

        $updatedParentBalance = $this->_db->query("UPDATE partner_users SET wa_balance = wa_balance + ? WHERE id = ?", [$depositData["amount"], $partnerId]);


        //log the action
        $this->log->addLog($action, $description, $depositData["partner_id"]);

        return true;
    }

    /**
     * Summary of updateWaBalanceDeposit
     * @param int $depositId
     * @param mixed $amount
     * @param string $action
     * @param string $description
     * @return bool
     */
    public function updateWaBalanceDeposit($depositId, $amount, $action = '', $description = '')
    {
        $partner = new user();
        $partnerId = $partner->data()["id"];

        //get deposit data
        $sql = "SELECT d.partner_id, d.history_id, d.amount  FROM wa_balance_deposits d WHERE d.id = ?";
        $depositData = $this->_db->query($sql, [$depositId])->first();

        //update deposit table
        $today = new DateTime();
        $today = $today->format("Y-m-d H:i:s");

        $updateDeposit = $this->_db->update("wa_balance_deposits", [["id", "=", $depositId]], ["amount" => $amount, "updated_at" => $today]);
        if ($updateDeposit->error()) {
            return false;
        }

        //insert new deposit history {type:update}
        $historyFields = ["amount" => $amount, "type" => 2, "history_id" => $depositData["history_id"]];
        $insertHistory = $this->_db->insert("wa_balance_history", $historyFields);


        //logic of updating clients balance and partners commissions
        $oldDeposit = $depositData["amount"];
        $newDeposit = $amount;

        $diffToAdd = $newDeposit - $oldDeposit;

        //update balance table
        $updatedBalance = $this->_db->query("UPDATE partner_users SET wa_balance = wa_balance + ? WHERE id = ?", [$diffToAdd, $depositData["partner_id"]]);
        if ($updatedBalance->error()) {
            return false;
        }

        $updatedParentBalance = $this->_db->query("UPDATE partner_users SET wa_balance = wa_balance + ? WHERE id = ?", [-$diffToAdd, $partnerId]);


        //log the action
        $description = $description . ". <<--amount[" . $depositData["amount"] . "]->[" . $amount . "]>>";
        $this->log->addLog($action, $description, $depositData["partner_id"]);

        return true;
    }




}
