<?php
require_once __DIR__ . "/../core/ini.php";

/**
 * Database Class
 * 
 * Complete Database class using PDO with error logging and singleton_pattern
 * 
 * @author khalid
 */
class DB
{
    private static $_instance = null;
    private PDO $_pdo;
    private $_query;
    private bool $_error = false;
    private array $_results;
    private int $_count = 0;
    private FileLogger $_fileLogger;

    /**
     * __construct
     *
     * @return void
     */
    private function __construct()
    {
        $this->_fileLogger = new FileLogger(__DIR__ . '/../logs/database.log');
        try {
            $this->_pdo = new PDO("mysql:host=" . config::get("mysql/host") . ";dbname=" . config::get("mysql/db") . ";charset=" . config::get("mysql/charset"), config::get("mysql/username"), config::get("mysql/password"));
            $this->_pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $this->_fileLogger->log("STACK-TRACE: " . $e->getTraceAsString() . "--- MESSAGE: " . $e->getMessage(), FileLogger::FATAL);
            die();
        }
    }

    /**
     * getInstance : Singleton Pattern for private and also to check if already connected (same page---(not refreshing the page !));
     *
     * @return DB
     */
    public static function getInstance(): DB
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new DB();
        }
        return self::$_instance;
    }

    /**
     * query : make a pdo query
     *
     * @param  string $sql
     * @param  array $params
     * @return DB
     */
    public function query(string $sql, array $params = []): DB
    {
        $this->_error = false;
        $this->_count = 0;
        try {
            $this->_query = $this->_pdo->prepare($sql);

            if ($this->_query) {
                $x = 1;
                if (count($params) > 0) {
                    foreach ($params as $param) {
                        $this->_query->bindValue($x++, $param);
                    }
                }

                if ($this->_query->execute()) {
                    $this->_results = $this->_query->fetchALL(PDO::FETCH_ASSOC);
                    $this->_count = $this->_query->rowCount();

                } else {
                    $this->_error = true;
                    $this->_fileLogger->log($this->_query->errorInfo()[2] . " ||| QUERY: '" . $sql . "'", FileLogger::ERROR);
                }
            } else {
                $this->_error = true;
                $error = $this->_pdo->errorInfo();
                $this->_fileLogger->log("Error occurred while preparing the query " . json_encode($error) . " ||| QUERY: '" . $sql . "'", FileLogger::ERROR);

            }
        } catch (PDOException $e) {
            $this->_error = true;
            $this->_fileLogger->log("STACK-TRACE: " . $e->getTraceAsString() . "--- MESSAGE: " . $e->getMessage(), FileLogger::FATAL);
        }
        return $this; //chaining
    }

    /**
     * action : make a query with explicit condition (on their own array)--> only AND : used for CRUD functions
     *
     * @param  string $action
     * @param  string $table
     * @param  array $where
     * @param  string $orderby
     * @return DB
     */
    private function action(string $action, string $table, array $where = [], string $orderby = ''): DB
    {
        if (count($where) >= 1) {
            $operators = array("=", "+", "<", ">", "=>", "=<", "<>");
            $txt_where = '';
            $array_values = [];
            $i = 0;
            foreach ($where as $key => $element) {
                # code...
                $field = $element[0];
                $operator = $element[1];
                if (in_array($operator, $operators)) {
                    $array_values[] = $element[2];
                    ($i == 0) ? ($txt_where = $txt_where . "{$field} {$operator} ?") : ($txt_where = $txt_where . " and {$field} {$operator} ?");
                    $i = 1;
                } elseif ($operator == 'IN') {
                    $id_list = "(";
                    $length = count($element[2]);
                    for ($j = 0; $j < $length; $j++) {
                        $array_values[] = $element[2][$j];
                        if ($j == 0) {
                            $id_list = $id_list . "?";
                        } else {
                            $id_list = $id_list . "," . "?";
                        }
                    }
                    $id_list = $id_list . ")";

                    ($i == 0) ? ($txt_where = $txt_where . "{$field} {$operator} {$id_list}") : ($txt_where = $txt_where . " and {$field} {$operator} {$id_list}");
                    $i = 1;
                }
            }
            $sql = "{$action} FROM {$table} WHERE $txt_where $orderby";
            $db = $this->query($sql, $array_values);
        } elseif ($where == []) {
            $sql = "{$action} FROM {$table} $orderby";
            $db = $this->query($sql);
        }
        return $this;

    }

    /**
     * get : get data from a select query
     *
     * @param  string $fields
     * @param  string $table
     * @param  array $where
     * @param  string $orderby
     * @return DB
     */
    public function get(string $fields, string $table, array $where = [], string $orderby = ''): DB
    {
        return $this->action("SELECT $fields", $table, $where, $orderby);
    }

    /**
     * delete
     *
     * @param  string $table
     * @param  array $where
     * @return DB
     */
    public function delete(string $table, array $where): DB
    {
        return $this->action("DELETE ", $table, $where);
    }

    /**
     * insert
     *
     * @param  string $table
     * @param  array $fields
     * @return DB
     */
    public function insert(string $table, array $fields = []): DB
    {
        if (count($fields)) {
            $keys = array_keys($fields);
            $values = "";
            $x = 0;
            foreach ($fields as $field => $value) {
                ++$x;
                if ($x == count($fields)) {
                    $values .= "?";
                } else {
                    $values .= "? ";
                }
            }
            $sql = "INSERT INTO {$table} (" . implode(',', $keys) . ") values (" . implode(', ', explode(' ', $values)) . ")";

            $inserted = $this->query($sql, array_values($fields));
            return $this;
        }
        $this->_error = true;
        $this->_count = 0;
        return $this;
    }

    /**
     * update
     *
     * @param  string $table
     * @param  array $where
     * @param  array $fields
     * @return DB
     */
    public function update(string $table, array $where = [], array $fields = []): DB
    {
        $set = "";
        $x = 0;
        $array_values = [];

        foreach ($fields as $field => $value) {
            ++$x;
            if ($x == count($fields)) {
                $set .= $field . "=?";
            } else {
                $set .= $field . "=?,";
            }
        }
        $txt_where = '';

        if (count($where) >= 1) {
            $operators = array("=", "+", "<", ">", "=>", "=<", "<>");
            $txt_where = 'WHERE ';
            $i = 0;
            foreach ($where as $key => $element) {
                # code...
                $field = $element[0];
                $operator = $element[1];
                if (in_array($operator, $operators)) {
                    $array_values[] = $element[2];
                    ($i == 0) ? ($txt_where = $txt_where . "{$field} {$operator} ?") : ($txt_where = $txt_where . " and {$field} {$operator} ?");
                    $i = 1;
                } elseif ($operator == 'IN') {
                    $id_list = "(";
                    $length = count($element[2]);
                    for ($j = 0; $j < $length; $j++) {
                        $array_values[] = $element[2][$j];

                        if ($j == 0) {
                            $id_list = $id_list . "?";
                        } else {
                            $id_list = $id_list . "," . "?";
                        }
                    }
                    $id_list = $id_list . ")";

                    ($i == 0) ? ($txt_where = $txt_where . "{$field} {$operator} {$id_list}") : ($txt_where = $txt_where . " and {$field} {$operator} {$id_list}");
                    $i = 1;
                }
            }

        }
        $sql = "UPDATE {$table} SET " . $set . " $txt_where";
        $values = array_merge(array_values($fields), $array_values);

        $updated = $this->query($sql, $values);
        return $this;
    }


    /**
     * results : get the query results : [0=>["name"=>"khalid,...],1=>....]
     *
     * @return array
     */
    public function results(): array
    {
        return $this->_results;
    }

    /**
     * first
     *
     * @return array
     */
    public function first(): array
    {
        return $this->results()[0];
    }

    /**
     * error
     *
     * @return bool
     */
    public function error(): bool
    {
        return $this->_error;
    }

    /**
     * count
     *
     * @return int
     */
    public function count(): int
    {
        return $this->_count;
    }

    /**
     * lastId : get the last inserted/updated record Id
     *
     * @return mixed
     */
    public function lastId()
    {
        return $this->_pdo->lastInsertId();
    }

}
