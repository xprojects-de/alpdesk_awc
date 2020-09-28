<?php

declare(strict_types=1);

class JsonProxy {

  private $url = '';
  private $username = null;
  private $password = null;
  private $requestType = null;
  private $data = null;
  private $verifyHost = false;
  private $verifyPeer = false;
  private $followLocation = true;
  private $verbose = true;
  private $customHeaders = array();
  private $requestEncode = false;
  private $responseDecode = false;

  public function setUrl(string $url): void {
    $this->url = $url;
  }

  public function setUsername(string $username): void {
    $this->username = $username;
  }

  public function setPassword(string $password): void {
    $this->password = $password;
  }

  public function setRequestType(string $requestType): void {
    $this->requestType = $requestType;
  }

  public function setData($data): void {
    $this->data = $data;
  }

  public function setVerifyHost(bool $verifyHost): void {
    $this->verifyHost = $verifyHost;
  }

  public function setVerifyPeer(bool $verifyPeer): void {
    $this->verifyPeer = $verifyPeer;
  }

  public function setFollowLocation(bool $followLocation): void {
    $this->followLocation = $followLocation;
  }

  public function setVerbose(bool $verbose): void {
    $this->verbose = $verbose;
  }

  public function setCustomHeaders(array $customHeaders): void {
    $this->customHeaders = $customHeaders;
  }

  public function setRequestEncode(bool $requestEncode): void {
    $this->requestEncode = $requestEncode;
  }

  public function setResponseDecode(bool $responseDecode): void {
    $this->responseDecode = $responseDecode;
  }

  public function exec() {

    if ($this->requestEncode == true && $this->data != null) {
      $this->data = json_encode($this->data);
    }

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $this->url);

    if ($this->data != null) {
      if ($this->requestType != null) {
        if ($this->requestType == 'PUT') {
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
          curl_setopt($ch, CURLOPT_POSTFIELDS, $this->data);
        } else if ($this->requestType == 'DELETE') {
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        } else if ($this->requestType == 'POST') {
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $this->data);
        }
      }
    }

    if ($this->username != null && $this->password != null) {
      curl_setopt($ch, CURLOPT_USERPWD, "$this->username:$this->password");
    }

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $this->verifyHost);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verifyPeer);
    if (!function_exists('ini_get') || !ini_get('open_basedir')) {
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $this->followLocation);
    }

    curl_setopt($ch, CURLOPT_ENCODING, '');
    $headers = ['Content-Type: application/json'];
    if (count($this->customHeaders) > 0) {
      foreach ($this->customHeaders as $value) {
        array_push($headers, $value);
      }
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_VERBOSE, $this->verbose);

    $response = curl_exec($ch);

    if (!$response) {

      $http_response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      $body = curl_error($ch);
      curl_close($ch);

      throw new \Exception(sprintf('CURL Error: http response=%d, %s', $http_response, $body));
    } else {

      $http_response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);

      if ($http_response != 200 && $http_response != 201) {
        throw new \Exception('CURL HTTP Request Failed: Status Code : ' . $http_response . ', URL:' . $this->url . "\nError Message : " . $response, $http_response);
      }
    }

    if ($this->responseDecode == true) {
      $response = json_decode($response, true);
    }

    return $response;
  }

}

class AWCChannel {

  public $baseUrl = "";
  public $username = "";
  public $password = "";

  private function auth(): string {
    $jp = new JsonProxy();

    $jp->setUrl($this->baseUrl . '/auth');
    $jp->setRequestType('POST');
    $jp->setData([
        'username' => $this->username,
        'password' => $this->password
    ]);
    $jp->setRequestEncode(true);
    $jp->setResponseDecode(true);

    $response = $jp->exec();

    return $response['alpdesk_token'];
  }

  public function exec($data): string {

    $returnvalue = '<wcsresponse returncode="10" message="Error at request"><resp_registrationformprint><responsevalue name="registrationformnumber"></responsevalue></resp_registrationformprint></wcsresponse>';

    try {

      $token = $this->auth();

      $jp = new JsonProxy();

      $jp->setUrl($this->baseUrl . '/plugin');
      $jp->setRequestType('POST');
      $jp->setData([
          'plugin' => "awc",
          'data' => [
              'method' => 'legacy_add',
              'param' => $data
          ]
      ]);
      $jp->setCustomHeaders(['Authorization: Bearer ' . $token]);
      $jp->setRequestEncode(true);
      $jp->setResponseDecode(true);

      $response = $jp->exec();

      if ($response['data']['error'] == false) {
        $returnvalue = '<wcsresponse returncode="' . $response['data']['msg']['code'] . '" message="' . $response['data']['msg']['responseMessage'] . '"><resp_registrationformprint><responsevalue name="' . $response['data']['msg']['registrationformnumber'] . '"></responsevalue></resp_registrationformprint></wcsresponse>';
      }
    } catch (\Exception $ex) {
      $returnvalue = '<wcsresponse returncode="11" message="' . $ex->getMessage() . '"><resp_registrationformprint><responsevalue name="registrationformnumber"></responsevalue></resp_registrationformprint></wcsresponse>';
    }

    return $returnvalue;
  }

}

$awcChannel = new AWCChannel();
$awcChannel->baseUrl = "localhost";
$awcChannel->username = "testmandant";
$awcChannel->password = "1234567890";
$response = $awcChannel->exec("<p:wcsrequest version='2.5.0' guesthousenumber='' xmlns:p='http://wcs.wilken.de/WcsRequestResponse' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:schemaLocation='http://wcs.wilken.de/WcsRequestResponse/request_schema.xsd'><req_registrationformcreate><registrationtype>standard</registrationtype><stay><arrival>2020-09-25</arrival><departure>2020-09-26</departure></stay><travelpurpose>holiday</travelpurpose><group><mainperson><name>Hummel</name><prename>Benjmain</prename><birthdate>1985-11-21</birthdate><salutation>Herr</salutation><nationality>DE</nationality><address><streetnr>Auf der Halde 1</streetnr><country>DE</country><zip>87534</zip><city>Oberstaufen</city></address></mainperson><groupregistration/><accompanists><accompanist><name>Hummel</name><prename>Carina</prename><birthdate>1986-10-11</birthdate></accompanist></accompanists></group></req_registrationformcreate></p:wcsrequest>");
echo($response);
