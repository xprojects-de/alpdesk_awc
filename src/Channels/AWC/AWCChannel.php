<?php

declare(strict_types=1);

namespace Alpdesk\AlpdeskAwcPlugin\Channels\AWC;

use Alpdesk\AlpdeskAwcPlugin\Channels\AWC\AWCPerson;
use Alpdesk\AlpdeskCore\Utils\JsonProxy;

class AWCChannel {

  public $url = "https://oats-test-wcs.wilken.de/pms/v1/regform/create";
  public $username = "";
  public $password = "";
  public $awcapikey;
  public $awckey;
  public $swvendor = "alpdesk";
  public $swname = "alpdesk";
  public $journey_reason = "";
  public $modification_note = "";
  private $persons = array();
  public $errorCode = -1;
  public $responseMessage = "invalid response";

  public function addPerson(AWCPerson $person) {
    array_push($this->persons, array(
        "person_id" => null,
        "visible_card_nr" => null,
        "mifare_id" => null,
        "salutation" => $person->salutation,
        "first_name" => $person->first_name,
        "last_name" => $person->last_name,
        "main_person" => $person->main_person,
        "arrival" => $person->arrival,
        "departure" => $person->departure,
        "birthdate" => $person->birthdate,
        "nationality" => $person->nationality,
        "card_category" => $person->card_category,
        "spa_category" => $person->spa_category,
        "address" => [
            "street" => $person->street,
            "zip" => $person->zip,
            "city" => $person->city,
            "state" => $person->state,
            "country" => $person->country
        ],
        "passport" => $person->passport,
        "email" => $person->email,
        "advertisment" => $person->advertisment
    ));
  }

  private function getData(): array {
    return array(
        "operator_info" => null,
        "software_vendor" => $this->swvendor,
        "software_name" => $this->swname,
        "software_version" => 1,
        "api_key" => $this->awcapikey,
        "guesthouse" => $this->awckey,
        "guesthouse_override" => null,
        "regform_id" => null,
        "state" => null,
        "online_checkin_link" => null,
        "online_checkin_lock" => false,
        "online_checking_mailing_state" => null,
        "travel_guide_link" => null,
        "travel_guide_mailing_state" => null,
        "mail_state" => null,
        "journey_reason" => $this->journey_reason,
        "modification_note" => $this->modification_note,
        "persons" => $this->persons
    );
  }

  public function exec(): array {
    $jp = new JsonProxy();

    $jp->setUrl($this->url);
    $jp->setUsername($this->username);
    $jp->setPassword($this->password);
    $jp->setRequestType('POST');
    $jp->setData($this->getData());
    $jp->setRequestEncode(true);
    $jp->setResponseDecode(true);

    $response = $jp->exec();    
    if (array_key_exists('status_code', $response)) {
      if (is_array($response['status_code'])) {
        if (array_key_exists('return_code', $response['status_code'][0]) && array_key_exists('text_de', $response['status_code'][0])) {
          $this->errorCode = $response['status_code'][0]['return_code'];
          $this->responseMessage = $response['status_code'][0]['text_de'];
        }
      }
    }
    return $jp->exec();
  }

}
