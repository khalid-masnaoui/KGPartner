<?php
/*
PHP SESSION BASED RATE LIMITER --> CAN USE IP/USER CREDENTIALS/API KEY....
This is a really simple plug and play rate limiter class meant to be used with APIs.

Using sessions means we can throw this into any PHP API quickly.

$token can be anything to uniquely identify a user, either an IP address, an API key, a JWT token, anything that is unique to a single user.

$prefix can be whatever you want, we default it to "rate"

On init, we create a md5 hash of our $prefix and our $token, this becomes the prefix throughout the class.

We then append a timestamp to this prefix
*/
class RateExceededException extends Exception {}

class Util_RateLimiter {
	private $prefix;
	public function __construct($token, $prefix = "rate") {
		$this->prefix = md5($prefix . $token);

		if( !isset($_SESSION["cache"]) ){
			$_SESSION["cache"] = array();
		}

		if( !isset($_SESSION["expiries"]) ){
			$_SESSION["expiries"] = array();
		}else{
			$this->expireSessionKeys();
		}
	}

	public function limitRequestsInMinutes($allowedRequests, $minutes) {
		$this->expireSessionKeys();
		$requests = 0;

		foreach ($this->getKeys($minutes) as $key) {
			$requestsInCurrentMinute = $this->getSessionKey($key);
			if (false !== $requestsInCurrentMinute) $requests += $requestsInCurrentMinute;
		}

		if (false === $requestsInCurrentMinute) {
			$this->setSessionKey( $key, 1, ($minutes * 60 + 1) );
		} else {
			$this->increment($key, 1);
		}
		if ($requests > $allowedRequests) throw new RateExceededException;
	}

	private function getKeys($minutes) {
		$keys = array();
		$now = time();
		for ($time = $now - $minutes * 60; $time <= $now; $time += 60) {
			$keys[] = $this->prefix . date("dHi", $time);
		}
		return $keys;
	}

	private function increment( $key, $inc){
		$cnt = 0;
		if( isset($_SESSION['cache'][$key]) ){
			$cnt = $_SESSION['cache'][$key];
		}
		$_SESSION['cache'][$key] = $cnt + $inc;
	}

	private function setSessionKey( $key, $val, $expiry ){
		$_SESSION["expiries"][$key] = time() + $expiry;
		$_SESSION['cache'][$key] = $val;
	}
	
	private function getSessionKey( $key ){
		return isset($_SESSION['cache'][$key]) ? $_SESSION['cache'][$key] : false;
	}

	private function expireSessionKeys() {
		foreach ($_SESSION["expiries"] as $key => $value) {
			if (time() > $value) { 
				unset($_SESSION['cache'][$key]);
				unset($_SESSION["expiries"][$key]);
			}
		}
	}
}


//EXAMPLE

// session_start();
// include("ratelimiter.php");

// // in this sample, we are using the originating IP, but you can modify to use API keys, or tokens or what-have-you.
// $rateLimiter = new RateLimiter($_SERVER["REMOTE_ADDR"]);

// $limit = 100;				//	number of connections to limit user to per $minutes
// $minutes = 1;				//	number of $minutes to check for.
// $seconds = floor($minutes * 60);	//	retry after $minutes in seconds.

// try {
// 	$rateLimiter->limitRequestsInMinutes($limit, $minutes);
// } catch (RateExceededException $e) {
// 	header("HTTP/1.1 429 Too Many Requests");
// 	header(sprintf("Retry-After: %d", $seconds));
// 	$data = 'Rate Limit Exceeded ';
// 	die (json_encode($data));
// }

// // ok, they were within their limit, so let's continue with our app....
// $data = "Data Returned from API ";
// header('Content-Type: application/json');
// die(json_encode($data)); 