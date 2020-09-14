<?php

declare(strict_types=1);

namespace Alpdesk\AlpdeskAwcPlugin\Elements;

use Alpdesk\AlpdeskCore\Elements\AlpdeskCoreElement;
use Alpdesk\AlpdeskCore\Library\Mandant\AlpdescCoreBaseMandantInfo;
use Alpdesk\AlpdeskCore\Utils\JsonProxy;

class AlpdeskElementAwc extends AlpdeskCoreElement {

  private function testRequest(array $response, array $mandantInfoData): array {

    $awcData = array(
        "operator_info" => null,
        "software_vendor" => "alpdesk",
        "software_name" => "alpdesk",
        "software_version" => 1,
        "api_key" => $mandantInfoData['awcapikey'],
        "guesthouse" => $mandantInfoData['awckey'],
        "guesthouse_override" => null,
        "regform_id" => null,
        "state" => null,
        "online_checkin_link" => null,
        "online_checkin_lock" => false,
        "online_checking_mailing_state" => null,
        "travel_guide_link" => null,
        "travel_guide_mailing_state" => null,
        "mail_state" => null,
        "journey_reason" => "holiday",
        "modification_note" => "I'm sorry about the mistake and fixed it.",
        "persons" => [
            [
                "person_id" => null,
                "visible_card_nr" => null,
                "mifare_id" => null,
                "salutation" => "MR",
                "first_name" => "Benjmain",
                "last_name" => "Hummel",
                "main_person" => true,
                "arrival" => date('Y-m-d'),
                "departure" => date('Y-m-d'),
                "birthdate" => "1985-11-21",
                "nationality" => "DE",
                "card_category" => "adult",
                "spa_category" => "adult",
                "address" => [
                    "street" => "Debian 1",
                    "zip" => "80000",
                    "city" => "Linux",
                    "state" => "Bayern",
                    "country" => "DE"
                ],
                "passport" => "PA 123456",
                "email" => "infos@x-projects.de",
                "advertisment" => true
            ], [
                "person_id" => null,
                "visible_card_nr" => null,
                "mifare_id" => null,
                "salutation" => "MRS",
                "first_name" => "Carina",
                "last_name" => "Hummel",
                "main_person" => false,
                "arrival" => "1986-09-01",
                "departure" => date('Y-m-d'),
                "birthdate" => date('Y-m-d'),
                "nationality" => "DE",
                "card_category" => "adult",
                "spa_category" => "adult",
                "address" => [
                    "street" => "Debian 1",
                    "zip" => "80000",
                    "city" => "Linux",
                    "state" => "Byyern",
                    "country" => "DE"
                ],
                "passport" => "PA 9844447",
                "email" => "carina.hummel@x-projects.de",
                "advertisment" => true
            ]
        ]
    );

    //dump($awcData);
    //dump(json_encode($awcData));
    //die;

    $response['error'] = false;
    $jp = new JsonProxy();
    $jp->setUrl("https://oats-test-wcs.wilken.de/pms/v1/regform/create");
    $jp->setRequestType('POST');
    $jp->setData($awcData);
    $jp->setRequestEncode(true);
    $jp->setResponseDecode(true);
    $response['msg'] = $jp->exec();
    return $response;
  }

  public function execute(AlpdescCoreBaseMandantInfo $mandantInfo, array $data): array {
    $response = array(
        'error' => true,
        'msg' => ''
    );
    if (\is_array($data) && \array_key_exists('method', $data) && \array_key_exists('param', $data)) {
      try {
        switch ($data['method']) {
          case 'ping':
            $response['error'] = false;
            $response['msg'] = 'pong';
            break;
          case 'test':
            $mandantInfoData = $mandantInfo->getAdditionalDatabaseInformation();
            $response = $this->testRequest($response, $mandantInfoData);
            break;
          default:
            break;
        }
      } catch (\Exception $ex) {
        $response['error'] = true;
        $response['msg'] = $ex->getMessage();
      }
    }
    return $response;
  }

}
