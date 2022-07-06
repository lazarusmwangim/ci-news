<?php

namespace App\Libraries;

//require 'vendor\autoload.php';

use AfricasTalking\SDK\AfricasTalking;

class SMS
{
	public static function sendSMS($recipients, $message)
	{
		$username = "lazarusMwangi";
		$apiKey = "fa6bfafcb0bcad4dad5f0bd39111c3a480217b85e3b7f20c3246de284932d1cc";
		//$from = "LAZARUS";
		$gateway = new AfricasTalking($username, $apiKey);

		// Get the SMS service
		$sms        = $gateway->sms();

		// Set the numbers you want to send to in international format
		//$recipients = "+254711XXXYYY,+254733YYYZZZ";

		// Set your message
		//$message    = "I'm a lumberjack and its ok, I sleep all night and I work all day";

		// Set your shortCode or senderId
		//$from       = "myShortCode or mySenderId";

		try {
			// Thats it, hit send and we'll take care of the rest
			$result = $sms->send([
				'to'      => $recipients,
				'message' => $message
				//'from'    => $from
			]);

			//print_r($result);
		} catch (Exception $e) {
			echo "Error: " . $e->getMessage();
		}
	}
	/* public static function sendBulkSMS($recipients, $message)
	{  
		$username = "Cherehani";
		$apikey = "ed11f30e1f7a4f349639e068d801b3abdab067f7753aab0534bc5dc4d6918e65";
		$from = "Cherehani";
		$gateway = new AfricasTalkingGateway($username, $apikey);
		$status = "Success";
		try {
			$results = $gateway->sendMessage($recipients, $message, $from);
			foreach ($results as $result) {
				/*	echo " Number: " .$result->number;
			echo " Status: " .$result->status;
			echo " MessageId: " .$result->messageId;
			echo " Cost: "   .$result->cost."\n";
				$status = $result->status;
				$data = array("messageId" => $result->messageId, "phone" => substr($result->number, 1), "status" => $result->status, "cost" => substr($result->cost, 4), "message" => $message);
			}
		} catch (AfricasTalkingGatewayException $e) {
			echo "Encountered an error while sending: " . $e->getMessage();
		}

		return $status;
	} */
}
