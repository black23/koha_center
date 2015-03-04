<?

/*
 *   (c) 2009-2013 Neogenia s.r.o.
 *   PHP trida, ktera odesila placene sms pres portal www.smsbrana.cz
 *   PHP 5 >= 5.3.0
 */

require_once(__DIR__ . "/http.php");

class CSMSConnect
{
	const AUTH_PLAIN = 1; //typ autentizace 1 - plain heslo (nedoporuceno)
	const AUTH_HASH = 2; //typ autentizace 2 - prenasen pouze kontrolni hash (doporuceno)
	private $apiScript = "http://api.smsbrana.cz/smsconnect/http.php"; //link na rozhrani API
	private $authType = 2;
	private $login = null; //uzivatelske jmeno SMSconnectu
	private $password = null; //heslo SMSconnectu
	private $queue = null; //sms ktere jsou k odeslani

	/*
	 * Generate salt for access
	 *
	 * @param Int $delka the length of salt to be returned
	 * @return String
	 */
	private function salt($length)
	{
		$result = "";
		$source = array_merge(range("a", "z"), range("A", "Z"), range(0, 9), array(":"));

		for ($counter = 0; $counter < $length; $counter++)
		{
			$result .= $source[rand(0, count($source)-1)];
		}
		return $result;
	}


	/*
	 * Create URL attributes
	 *
	 * @return Array of login attributes | null if no login attributes are set
	 */
	private function getAuthData()
	{
		if (empty($this->login) || empty($this->password))
			return null;
		else
		{
			$resultArray = array();
			if ($this->authType == self::AUTH_PLAIN)
			{
				$resultArray["login"] = $this->login;
				$resultArray["password"] = $this->password;
			}
			else
			{
				$salt = $this->salt(10);
				$time = date("Ymd")."T".date("His");

				$resultArray["login"] = $this->login;
				$resultArray["sul"] = $salt;
				$resultArray["time"] = $time;
				$resultArray["hash"] = md5($this->password.$time.$salt);
			}
			return $resultArray;
		}
	}

	/*
	 * Create new queue
	 *
	 * @param String $login
	 * @param String $password
	 * @param Int $auth_type
	 *
	 * @return true | false on empty login data
	 */
	public function create($login, $password, $authType)
	{
		if (empty($login) || (empty($password)) || !in_array($authType, array(self::AUTH_HASH, self::AUTH_PLAIN)))
			return false;
		else
		{
			$this->queue = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><queue></queue>');
			$this->login = $login;
			$this->password = $password;
			$this->authType = $authType;
			return true;
		}
	}

	/*
	 * Erase login attributes out of class variables
	 *
	 * @return true
	 */
	public function logout()
	{
		$this->login = null;
		$this->password = null;
		$this->queue = null;
		return true;
	}

	/*
	 * Try to output xml if $data in xml format, or else output raw $data
	 *
	 * @param String $data content of some URL
	 *
	 * @return String in xml format | String content of some URL
	 */
	private function getAnswer($data)
	{
		$xmlSolid = simplexml_load_string($data);//Pokusí se vytvořit platný XML objekt se správnou strukturou

		if($xmlSolid === false)//Nepodařilo se vytvořit objekt
			return $data;
		else
			return $xmlSolid->asXML();
	}

	/*
	 * Get the same error as the server's to invalid login or password
	 *
	 * @return String in xml format
	 *
	 */
	private function getLoginError()
	{
		return $this->getAnswer("<?xml version=\"1.0\" encoding=\"utf-8\"?><result><err>2</err></result>");
	}

	/*
	 * Get inbox SMS
	 *
	 * @return String response body of the target page
	 */
	public function inbox()
	{
		$dataArray = $this->getAuthData();
		if (!empty($dataArray))
		{
			$dataArray["action"] = "inbox";

			$httpConfig = array();
			$httpConfig["method"] = "GET";
			$httpConfig["target"] = $this->apiScript;
			$httpConfig["params"] = $dataArray;

			$http = new Http();
			$http->initialize($httpConfig);

			return $this->getAnswer($http->execute());
		}
		else
			return $this->getLoginError();
   	}

	/*
	 * Send 1 SMS
	 *
	 * @param String $number phone number of receiver
	 * @param String $message message for receiver
	 * @param String $time sending time
	 * @param String $sender phone number of sender
	 * @param String $delivery delivery report?
	 *
	 * @return String response body of the target page
	 */
	public function send_SMS($number, $message, $time="", $sender="", $delivery="")
	{
		$dataArray = $this->getAuthData();
		if (!empty($dataArray))
		{
			$dataArray["action"] = "send_sms";
			$dataArray["number"] = $number;
			$dataArray["message"] = $message;
			$dataArray["when"] = $time;
			$dataArray["sender_id"] = $sender;
			$dataArray["delivery_report"] = $delivery;

			$httpConfig = array();
			$httpConfig["method"] = "GET";
			$httpConfig["target"] = $this->apiScript;
			$httpConfig["params"] = $dataArray;

			$http = new Http();
			$http->initialize($httpConfig);

			return $this->getAnswer($http->execute());
		}
		else
			return $this->getLoginError();
	}

	/*
	 * Insert sms to queue (supposed xml object)
	 *
	 * @param String $number phone number of receiver
	 * @param String $message message for receiver
	 * @param String $time sending time
	 * @param String $sender phone number of sender
	 * @param String $delivery delivery report?
	 *
	 * @return true on success | false on fail
	 */
	public function add_SMS($number, $message, $time="", $sender="", $delivery="")
	{
		if (!is_null($this->queue))
		{
			$sms = $this->queue->addChild("sms");
			$sms->addChild("number", $this->xmlEncode($number));
			$sms->addChild("message", $this->xmlEncode($message));
			$sms->addChild("when", $this->xmlEncode($time));
			$sms->addChild("sender_id", $this->xmlEncode($sender));
			$sms->addChild("delivery_report", $this->xmlEncode($delivery));
			return true;
		}
		else
			return false;
	}

	/*
	 * Give queue to system
	 *
	 * @return String response body of the target page | false on no queue
	 */
	public function sendAllSMS()
	{
		$dataArray = $this->getAuthData();
		if (!empty($dataArray))
		{
			if(!$this->queue->count())
				return false;

			$dataArray["action"] = "xml_queue";

			$httpConfig = array();
			$httpConfig["method"] = "POST";
			$http = new Http();
			$http->initialize($httpConfig);
			$http->setTarget($this->apiScript."?".$http->_makeQueryString($dataArray));

			return $this->getAnswer($http->execute('', '', '', $this->queue->asXML()));//Tady natvrdo POSTuju string, ne pole
		}
		else
			return $this->getLoginError();
	}

	protected function xmlEncode( $string )
	{
		return htmlspecialchars(preg_replace('#[\x00-\x08\x0B\x0C\x0E-\x1F]+#', '', $string), ENT_QUOTES);
	}
        
        /*
	 * Get credit
	 *
	 * @return String response body of the target page
	 */
	public function credit()
	{
		$dataArray = $this->getAuthData();
		if (!empty($dataArray))
		{
			$dataArray["action"] = "credit_info";

			$httpConfig = array();
			$httpConfig["method"] = "GET";
			$httpConfig["target"] = $this->apiScript;
			$httpConfig["params"] = $dataArray;

			$http = new Http();
			$http->initialize($httpConfig);

			return $this->getAnswer($http->execute());
		}
		else
			return $this->getLoginError();
   	}
        
        public function deleteInbox()
	{
		$dataArray = $this->getAuthData();
		if (!empty($dataArray))
		{
			$dataArray["action"] = "inbox";
                        $dataArray["delete"] = 1;
			$httpConfig = array();
			$httpConfig["method"] = "GET";
			$httpConfig["target"] = $this->apiScript;
			$httpConfig["params"] = $dataArray;

			$http = new Http();
			$http->initialize($httpConfig);

			return $this->getAnswer($http->execute());
		}
		else
			return $this->getLoginError();
   	}
        
}
