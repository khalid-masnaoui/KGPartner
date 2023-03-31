<?php
require_once __DIR__ . "/../functions/sanitize.php";

/**
 * User
 * 
 * log ,create,update,find and logout user---> logging using session/cookies
 * 
 * @author khalid
 */
class user
{
    private ?DB $_db;
    private ?ActivityLogger $log;
    private array $_data = [];
    private string $_sessionName;
    private string $_cookieName;
    private bool $_loggedIn;
    private string $_roleName;

    /**
     * __construct
     *
     * @param  mixed $user can be numeric or string [id , username or email]
     * @return void
     */
    public function __construct($user = null)
    {
        $this->_db = DB::getInstance();
        $this->log = new ActivityLogger();
        $this->_sessionName = config::get("session/login_name");
        $this->_cookieName = config::get("remember/cookie_name");
        $this->_roleName = config::get("session/role_name");

        if (!$user) {
            if (session::exists($this->_sessionName)) {
                $user = session::get($this->_sessionName);
                if ($user) {
                    $this->find($user);
                    $this->_loggedIn = true;
                } else {
                    //logOut!
                }
            }
        } else {
            $this->find($user);
        }
    }

    /**
     * create
     *
     * @param  array $fields
     * @return void
     */
    public function create(array $fields = array())
    {
        $created = $this->_db->insert("partner_users", $fields);
        if (!$created->error()) {
            throw new Exception("sorry ,a problem acquired while we were registering you");
        }
    }

    /**
     * find
     *
     * @param  mixed $user
     * @return void
     */
    public function find($user = null): bool
    {
        if ($user) {
            $field = (is_numeric($user) ? "id" : "username");
            $data = $this->_db->get("*", "partner_users", array([$field, "=", $user]));
            if ($data->count()) {

                $unescapeData = $data->first();
                $escapedData = [];
                foreach ($unescapeData as $key => $value) {
                    $escapedData[$key] = escape($value);
                }
                $this->_data = $escapedData;
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * log : LogIn
     *
     * @param  ?string $username
     * @param  ?string $password
     * @param  bool $remember
     * @return bool
     */
    public function log(?string $username = null, ?string $password = null, bool $remember = false): bool
    {
        if (!$username && !$password && $this->exists()) {
            //log user in 
            session_regenerate_id();
            $_SESSION = [];
            session::put($this->_sessionName, $this->data()["id"]);

            //log the action
            $action = "Login";
            $description = "The Partner '[" . $this->data()["username"] . "]' has been logged in automatically via remember me feature";
            $logged = $this->log->addLog($action, $description);

            // session::put($this->_roleName,$this->data()["role_id"]);  //equivalent to loggedin=true (using $user= new user())
            return true;
        } else {
            $user = $this->find($username);
            if ($user) {
                $pass = $password;
                if (password_verify($pass, $this->_data["password"])) {

                    session_regenerate_id();
                    $_SESSION = [];
                    session::put($this->_sessionName, $this->data()["id"]);

                    //log the action
                    $action = "Login";
                    $description = "The Partner '[" . $this->data()["username"] . "]' has been logged in manually via login page";
                    $logged = $this->log->addLog($action, $description);

                    // session::put($this->_roleName,$this->data()["role_id"]);
                    if ($remember) {
                        $hash = "";
                        $hashCheck = $this->_db->get("hash", "partner_users_session", [["partner_users_id", "=", $this->_data["id"]]]);
                        if (!$hashCheck->error()) {
                            if (!$hashCheck->count()) {
                                $hash = hash::unique();
                                $this->_db->insert("partner_users_session", array(
                                    "partner_users_id" => $this->_data["id"],
                                    "hash" => $hash
                                )
                                );
                            } else {
                                $hash = $hashCheck->first()["hash"];
                            }
                            cookie::put($this->_cookieName, $hash, config::get("remember/cookie_exp"));
                        }
                        //user will be logged in without remmeber me {even if checked --> in case of a database error}

                    }
                    $updatedLogStatus = $this->_db->update("partner_users", [["id", "=", $this->_data["id"]]], ["log" => 1]);
                    return true;
                }
                return false;
            }
            return false;
        }
    }

    /**
     * logOut
     *
     * @return void
     */
    public function logOut(): void
    {
        $deleted = $this->_db->delete("partner_users_session", [["partner_users_id", "=", $this->data()["id"]]]);
        if (!$deleted->error()) {

            //log the action before deleting sessions
            $action = "Logout";
            $description = "The Partner '[" . $this->data()["username"] . "]' has been logged out";
            $logged = $this->log->addLog($action, $description);

            session::delete($this->_sessionName);
            session_regenerate_id();
            // session::delete($this->_roleName);
            cookie::delete($this->_cookieName);


            $updatedLogStatus = $this->_db->update("partner_users", [["id", "=", $this->_data["id"]]], ["log" => 0]);
        }
        //else we show a system error

    }

    /**
     * exists
     *
     * @return bool
     */
    public function exists(): bool
    {
        return (!empty($this->_data) ? true : false);
    }

    /**
     * data
     *
     * @return void
     */
    public function data(): array
    {
        return $this->_data;
    }

    /**
     * isLoggedIn
     *
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return $this->_loggedIn;
    }
}
