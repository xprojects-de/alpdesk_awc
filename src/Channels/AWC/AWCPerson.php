<?php

declare(strict_types=1);

namespace Alpdesk\AlpdeskAwcPlugin\Channels\AWC;

class AWCPerson {
 
  public $salutation;
  public $first_name;
  public $last_name;
  public $main_person = false;
  public $arrival;
  public $departure;
  public $birthdate;
  public $nationality;
  public $card_category;
  public $spa_category;
  public $street;
  public $zip;
  public $city;
  public $state;
  public $country;
  public $passport = "";
  public $email = "";
  public $advertisment = true;
}
