<?php


/**
 * Validate
 * 
 * set of rules to validate users inputs
 * 
 * @author khalid
 */
class validate
{

    private bool $_passed = false;
    private array $_errors = [];
    private ?DB $_db = null;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->_db = DB::getInstance();
    }

    /**
     * check
     *
     * @param  array $source array of values 
     * @param  array $items array of rules
     * @return validate
     */
    public function check(array $source, array $items = []): validate
    {
        foreach ($items as $item => $rules) {
            $value = $source[$item];
            foreach ($rules as $rule => $rule_value) {
                if ($rule === "required" && empty(trim($value))) {
                    $this->addError("{$item} is required", $item);
                } else {
                    $y = 0;
                    switch ($rule) {
                        case "notEmpty":
                            if ($value == '' || $value == null || strlen($value) < 0) {
                                $this->addError("{$item} is required and need to be selected", $item);
                            }
                            break;
                        case "min":
                            if (strlen($value) < $rule_value && strlen($value) > 0) {
                                $this->addError("{$item} is too short : {$item} must be a minimum of {$rule_value} characters", $item);
                            }
                            break;

                        case "max":
                            if (strlen($value) > $rule_value) {
                                $this->addError("{$item} is too long : {$item} must be a minimum of {$rule_value} characters", $item);
                            }
                            break;

                        case "biggerThan":
                            if ($value <= $rule_value) {
                                $this->addError("{$item} must be bigger than {$rule_value} value", $item);
                            }
                            break;

                        case "lessThan":
                            if ($value >= $rule_value) {
                                $this->addError("{$item} must be less than {$rule_value} value", $item);
                            }
                            break;

                        case "biggerThanOrEqual":
                            if ($value < $rule_value) {
                                $this->addError("{$item} must be bigger than or equal {$rule_value} value", $item);
                            }
                            break;

                        case "lessThanOrEqual":
                            if ($value > $rule_value) {
                                $this->addError("{$item} must be less than or equal {$rule_value} value", $item);
                            }
                            break;

                        case "exclude":
                            if (in_array($value, $rule_value)) {
                                $this->addError("Not a valid option. Please select a valid option", $item);
                            }
                            break;

                        case "exclusion":

                            $allowedValueList = $rule_value["list"];

                            if (!in_array($value, $allowedValueList)) {
                                $this->addError($rule_value["msg"], $item);
                            }
                            break;

                        case "include":
                            if (!in_array($value, $rule_value)) {
                                $this->addError("Not a valid option. Please select a valid option", $item);
                            }
                            break;

                        case "inclusion":

                            $allowedValueList = $rule_value["list"];

                            if (!in_array($value, $allowedValueList)) {
                                $this->addError($rule_value["msg"], $item);
                            }
                            break;

                        case "pattern":
                            if (!preg_match($rule_value["rule"], $value)) {
                                $this->addError($rule_value["msg"], $item);
                            }
                            break;
                        case "matches":
                            if ($value != $source[$rule_value]) {
                                $this->addError("비밀번호가 일치하지 않습니다", $item);
                            }

                            break;

                        case "unique":
                            $column = $item;
                            if ($item == "playerID" || $item == "playerSelect") {
                                $column = "user_id";
                            }
                            if ($item == "templateName") {
                                $column = "template_name";
                            }

                            if (is_array($rule_value)) {
                                $user = $this->_db->get("*", $rule_value["table"], [[$column, "=", $value], ["client_id", "=", $rule_value["clientID"]]]);
                                if ($user->count()) {
                                    $name = $item;
                                    if ($item == "username") {
                                        $name = "아이디";
                                    }
                                    if ($item == "prefix") {
                                        $name = "프리픽스";
                                    }
                                    $this->addError("이미 사용중인 $name 입니다.", $item);
                                    // $this->addError("$item field already exist", $item);

                                }
                            } else {
                                $user = $this->_db->get("*", $rule_value, [[$column, "=", $value]]);
                                if ($user->count()) {
                                    $name = $item;
                                    if ($item == "username") {
                                        $name = "아이디";
                                    }
                                    if ($item == "prefix") {
                                        $name = "프리픽스";
                                    }
                                    $this->addError("이미 사용중인 $name 입니다.", $item);
                                    // $this->addError("$item field already exist", $item);
                                }
                            }
                            break;

                        case "uniqueForEdit":
                            if ($value != $rule_value["original"]) {

                                $user = $this->_db->get("*", $rule_value["table"], [[$item, "=", $value]]);
                                if ($user->count()) {
                                    $this->addError("$item field already exist", $item);
                                }
                            }
                            break;

                        case "exist":
                            $user = $this->_db->get("username", "partner_users", [[$item, "=", $value]])->count();
                            if ($user == 0) {
                                $y = 1;
                                $this->addError("this username does not exists!", $item);
                            }
                            break;

                        case "pass_matches":
                            $user_count = $this->_db->get("password", "partner_users", [["username", "=", $source["username"]]])->count();
                            if ($user_count == 1) {
                                $user_pass = $this->_db->get("password", "partner_users", [["username", "=", $source["username"]]])->first();
                                $password_inp = $source[$item];
                                if (!password_verify($password_inp, $user_pass["password"])) {
                                    $this->addError("the password is incorrect", $item);
                                }
                            }
                            break;

                        case "RateBiggerOfPartner":
                            $partnerRate = $value;
                            $parentRate = $rule_value["parentRate"];

                            if ($partnerRate < $parentRate) {
                                $this->addError("본인의 요율보다 같거나 높게 입력해 주세요.", $item);
                            }

                            break;

                        case "RateLesserOfChild":
                            $partnerRate = $value;
                            $childPattern = $rule_value["child"] . "/";


                            $childRates = $this->_db->query("SELECT rate from partner_users where pt_id like ?", ["$childPattern%"]);

                            if (!$childRates->error()) {
                                if ($childRates->count()) {
                                    $childRates = $childRates->results();
                                } else {
                                    //no child
                                    break;
                                }
                            } else {
                                $this->addError("We could not Fetch Child Data, please try again or contact us!", $item);
                                break;
                            }

                            $rates = array();
                            foreach ($childRates as $rate) {
                                $rates[] = $rate['rate'];
                            }

                            if (min($rates) <= $partnerRate) {
                                $this->addError("The Partner Rate must be less than that of his children", $item);
                            }

                            break;


                        //we can add many cases --> pattern (regex) , uri ,url , ip....          
                    }
                }
            }
        }
        if (empty($this->_errors)) {
            $this->_passed = true;
        }
        return $this;
    }

    /**
     * addError
     *
     * @param  string $error 
     * @param  string $item itemName
     * @return void
     */
    private function addError(string $error, string $item): void
    {
        $this->_errors[$item] = $error;
    }

    /**
     * errors
     *
     * @return array
     */
    public function errors(): array
    {
        return $this->_errors;
    }

    /**
     * passed
     *
     * @return bool
     */
    public function passed(): bool
    {
        return $this->_passed;
    }
}

?>
