<?php

declare(strict_types=1);

namespace Alpdesk\AlpdeskAwcPlugin\Elements;

use Alpdesk\AlpdeskCore\Elements\AlpdeskCoreElement;
use Alpdesk\AlpdeskCore\Library\Mandant\AlpdescCoreBaseMandantInfo;
use Alpdesk\AlpdeskAwcPlugin\Channels\AWC\AWCChannel;
use Alpdesk\AlpdeskAwcPlugin\Channels\AWC\AWCPerson;
use Alpdesk\AlpdeskAwcPlugin\Utils\Logger;

class AlpdeskElementAwc extends AlpdeskCoreElement {

  private function testRequest(array $response, array $mandantInfoData): array {

    $awcChannel = new AWCChannel();

    $awcChannel->url = "https://oats-test-wcs.wilken.de/pms/v1/regform/create";
    $awcChannel->username = $mandantInfoData['awcusername'];
    $awcChannel->password = $mandantInfoData['awcpassword'];

    $awcChannel->swvendor = "alpdesk";
    $awcChannel->swname = "alpdesk";
    $awcChannel->awcapikey = $mandantInfoData['awcapikey'];
    $awcChannel->awckey = $mandantInfoData['awckey'];
    $awcChannel->journey_reason = "holiday";
    $awcChannel->modification_note = "test";

    $p1 = new AWCPerson();
    $p1->salutation = "MR";
    $p1->first_name = "Benjmain";
    $p1->last_name = "Hummel";
    $p1->main_person = true;
    $p1->arrival = date('Y-m-d');
    $p1->departure = date('Y-m-d');
    $p1->birthdate = "1985-11-21";
    $p1->nationality = "DE";
    $p1->card_category = "adult";
    $p1->spa_category = "adult";
    $p1->street = "Debian 1";
    $p1->zip = "80000";
    $p1->city = "Linux";
    $p1->state = "Bayern";
    $p1->country = "DE";
    $p1->passport = "";
    $p1->email = "infos@x-projects.de";
    $p1->advertisment = true;
    $awcChannel->addPerson($p1);

    $p2 = new AWCPerson();
    $p2->salutation = "MRS";
    $p2->first_name = "Carina";
    $p2->last_name = "Hummel";
    $p2->main_person = false;
    $p2->arrival = date('Y-m-d');
    $p2->departure = date('Y-m-d');
    $p2->birthdate = "1986-11-10";
    $p2->nationality = "DE";
    $p2->card_category = "adult";
    $p2->spa_category = "adult";
    $p2->street = "Debian 1";
    $p2->zip = "80000";
    $p2->city = "Linux";
    $p2->state = "Bayern";
    $p2->country = "DE";
    $p2->passport = "";
    $p2->email = "carina.hummel@x-projects.de";
    $p2->advertisment = true;
    $awcChannel->addPerson($p2);

    $awcChannel->exec();
    $response['error'] = ( $awcChannel->errorCode == 0 ? false : true);
    $response['msg'] = array(
        'code' => $awcChannel->errorCode,
        'responseMessage' => $awcChannel->responseMessage
    );

    Logger::log('AWC', $mandantInfoData['awckey'], $awcChannel->requestString, "Code: ".$awcChannel->errorCode . " => Message: " . $awcChannel->responseMessage);

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
