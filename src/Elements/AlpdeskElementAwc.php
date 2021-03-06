<?php

declare(strict_types=1);

namespace Alpdesk\AlpdeskAwcPlugin\Elements;

use Alpdesk\AlpdeskCore\Elements\AlpdeskCoreElement;
use Alpdesk\AlpdeskCore\Library\Mandant\AlpdescCoreBaseMandantInfo;
use Alpdesk\AlpdeskAwcPlugin\Channels\AWC\AWCChannel;
use Alpdesk\AlpdeskAwcPlugin\Channels\AWC\AWCPerson;
use Alpdesk\AlpdeskAwcPlugin\Utils\Logger;

class AlpdeskElementAwc extends AlpdeskCoreElement
{
    private function testRequest(array $response, array $mandantInfoData): array
    {

        $awcChannel = new AWCChannel();

        $awcChannel->url = ($GLOBALS['TL_CONFIG']['awc_baseurl'] != '' ? $GLOBALS['TL_CONFIG']['awc_baseurl'] . '/pms/v1/regform/create' : 'https://oats-test-wcs.wilken.de/pms/v1/regform/create');
        $awcChannel->username = $mandantInfoData['awcusername'];
        $awcChannel->password = $mandantInfoData['awcpassword'];

        $awcChannel->swvendor = "alpdesk";
        $awcChannel->swname = "alpdesk";
        $awcChannel->awcapikey = $mandantInfoData['awcapikey'];
        if ($mandantInfoData['awckey_overrite'] != "") {
            $awcChannel->awckey_overrite = $mandantInfoData['awckey_overrite'];
        }
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
        $response['error'] = ($awcChannel->errorCode == 0 ? false : true);
        $response['msg'] = array(
            'code' => $awcChannel->errorCode,
            'responseMessage' => $awcChannel->responseMessage
        );

        $logKey = ($mandantInfoData['awckey_overrite'] != "" ? $mandantInfoData['awckey'] . '/' . $mandantInfoData['awckey_overrite'] : $mandantInfoData['awckey']);
        Logger::log('AWC', $logKey, $awcChannel->requestString, "Code: " . $awcChannel->errorCode . " => Message: " . $awcChannel->responseMessage);

        return $response;
    }

    private function isMultidimensionalArray(array $array): bool
    {
        return \count($array) !== \count($array, COUNT_RECURSIVE);
    }

    private function sendRequestFromArray(array $response, array $mandantInfoData, array $data)
    {
        try {

            $awcChannel = new AWCChannel();

            $awcChannel->url = ($GLOBALS['TL_CONFIG']['awc_baseurl'] != '' ? $GLOBALS['TL_CONFIG']['awc_baseurl'] . '/pms/v1/regform/create' : 'https://oats-test-wcs.wilken.de/pms/v1/regform/create');
            $awcChannel->username = $mandantInfoData['awcusername'];
            $awcChannel->password = $mandantInfoData['awcpassword'];

            $awcChannel->swvendor = "alpdesk";
            $awcChannel->swname = "alpdesk";
            $awcChannel->awcapikey = $mandantInfoData['awcapikey'];
            $awcChannel->awckey = $mandantInfoData['awckey'];
            if ($mandantInfoData['awckey_overrite'] != "") {
                $awcChannel->awckey_overrite = $mandantInfoData['awckey_overrite'];
            }
            $awcChannel->journey_reason = $data['req_registrationformcreate']['travelpurpose'];
            $awcChannel->modification_note = $data['req_registrationformcreate']['registrationtype'];

            $p1 = new AWCPerson();
            $p1->salutation = $data['req_registrationformcreate']['group']['mainperson']['salutation'];
            $p1->first_name = $data['req_registrationformcreate']['group']['mainperson']['prename'];
            $p1->last_name = $data['req_registrationformcreate']['group']['mainperson']['name'];
            $p1->main_person = true;
            $p1->arrival = $data['req_registrationformcreate']['stay']['arrival'];
            $p1->departure = $data['req_registrationformcreate']['stay']['departure'];
            $p1->birthdate = $data['req_registrationformcreate']['group']['mainperson']['birthdate'];
            $p1->nationality = $data['req_registrationformcreate']['group']['mainperson']['nationality'];
            $p1->card_category = "adult";
            $p1->spa_category = "adult";
            $p1->street = $data['req_registrationformcreate']['group']['mainperson']['address']['streetnr'];
            $p1->zip = $data['req_registrationformcreate']['group']['mainperson']['address']['zip'];
            $p1->city = $data['req_registrationformcreate']['group']['mainperson']['address']['city'];
            $p1->state = "";
            $p1->country = $data['req_registrationformcreate']['group']['mainperson']['address']['country'];
            $p1->passport = "";
            $p1->email = "";
            $p1->advertisment = true;
            $awcChannel->addPerson($p1);

            foreach ($data['req_registrationformcreate']['group']['accompanists'] as $accompanist) {

                if ($this->isMultidimensionalArray($accompanist)) {

                    foreach ($accompanist as $accompanistsub) {

                        $pA = new AWCPerson();
                        $pA->salutation = '';
                        $pA->first_name = $accompanistsub['prename'];
                        $pA->last_name = $accompanistsub['name'];
                        $pA->main_person = false;
                        $pA->arrival = $data['req_registrationformcreate']['stay']['arrival'];
                        $pA->departure = $data['req_registrationformcreate']['stay']['departure'];
                        $pA->birthdate = $accompanistsub['birthdate'];
                        $pA->nationality = $data['req_registrationformcreate']['group']['mainperson']['nationality'];
                        $pA->card_category = "adult";
                        $pA->spa_category = "adult";
                        $pA->street = '';
                        $pA->zip = '';
                        $pA->city = '';
                        $pA->state = "";
                        $pA->country = $data['req_registrationformcreate']['group']['mainperson']['address']['country'];
                        $pA->passport = "";
                        $pA->email = "";
                        $pA->advertisment = true;
                        $awcChannel->addPerson($pA);

                    }

                } else {

                    $pA = new AWCPerson();
                    $pA->salutation = '';
                    $pA->first_name = $accompanist['prename'];
                    $pA->last_name = $accompanist['name'];
                    $pA->main_person = false;
                    $pA->arrival = $data['req_registrationformcreate']['stay']['arrival'];
                    $pA->departure = $data['req_registrationformcreate']['stay']['departure'];
                    $pA->birthdate = $accompanist['birthdate'];
                    $pA->nationality = $data['req_registrationformcreate']['group']['mainperson']['nationality'];
                    $pA->card_category = "adult";
                    $pA->spa_category = "adult";
                    $pA->street = '';
                    $pA->zip = '';
                    $pA->city = '';
                    $pA->state = "";
                    $pA->country = $data['req_registrationformcreate']['group']['mainperson']['address']['country'];
                    $pA->passport = "";
                    $pA->email = "";
                    $pA->advertisment = true;
                    $awcChannel->addPerson($pA);
                }

            }

            $execResponse = $awcChannel->exec();
            $registrationformnumber = $execResponse['regform_id'];
            $response['error'] = ($awcChannel->errorCode == 0 ? false : true);
            $response['msg'] = array(
                'code' => $awcChannel->errorCode,
                'registrationformnumber' => $registrationformnumber,
                'responseMessage' => $awcChannel->responseMessage
            );

            $logKey = ($mandantInfoData['awckey_overrite'] != "" ? $mandantInfoData['awckey'] . '/' . $mandantInfoData['awckey_overrite'] : $mandantInfoData['awckey']);
            Logger::log('AWC', $logKey, $awcChannel->requestString, "Code: " . $awcChannel->errorCode . " => Message: " . $awcChannel->responseMessage);

        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }

        return $response;
    }

    private function legacyAddRequest(array $response, array $mandantInfoData, string $xmlData): array
    {
        if ($xmlData != '') {
            try {
                $xmlString = simplexml_load_string($xmlData);
                $xmlJson = json_encode($xmlString);
                $xmlArray = json_decode($xmlJson, true);
                $response = $this->sendRequestFromArray($response, $mandantInfoData, $xmlArray);
            } catch (\Exception $ex) {
                $response['msg'] = array(
                    'code' => 10,
                    'registrationformnumber' => 0,
                    'responseMessage' => $ex->getMessage()
                );
            }
        }

        return $response;
    }

    public function execute(AlpdescCoreBaseMandantInfo $mandantInfo, array $data): array
    {
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
                    case 'legacy_add':
                        $mandantInfoData = $mandantInfo->getAdditionalDatabaseInformation();
                        $response = $this->legacyAddRequest($response, $mandantInfoData, $data['param']);
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
