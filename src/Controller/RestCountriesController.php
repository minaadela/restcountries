<?php

namespace App\Controller;

use App\Model\RestCountries;
use App\Services\MakeRequest;

class RestCountriesController extends RestCountries {

  private $output;

  public function __construct($argv) {
    parent::__construct($argv);
    $this->validate();

  }

  /**
   * Get calls results
   *
   * @return string
   */
  public function getResult(): string {

    switch ($this->getArgvCount()) {
      case 2:
        $this->getCountry();
        break;
      case 3:
        $countryName3 = [
          [$this->argv[1], $this->argv[2]],
          [$this->argv[1] . ' ' . $this->argv[2]],
        ];
        $result       = $this->compareCountries($countryName3[0][0], $countryName3[0][1], FALSE);

        if (!$result) {
          $this->getCountry($countryName3[1][0]);
        }
        break;
      default:
        break;

    }

    return $this->output;
  }

  /**
   * get Country
   *
   * @param null $countryName
   *
   * @return int
   */
  public function getCountry($countryName = NULL) {

    if (NULL === $countryName) {
      $countryName = $this->argv[1];
    }

    $url      = str_replace('{code}', $countryName, $this->getRestCountriesService('countryName'));
    $response = MakeRequest::get($url);

    $body   = $response->getBody()->getContents();
    $status = $response->getStatusCode();

    try {
      if ($status == 200) {
        $languageCode = $this->getLanguageCode($body);
        $countryList  = $this->sameLanguageCountry($languageCode, $this->getRestCountriesService('countryLang'), $countryName);

        $responseString = $this->countryList(implode(', ', $countryList), $countryName);
        $this->output   .= $responseString;

      }
      else {
        $responseString = $this->countryCodeError();
        $this->output   .= $responseString;
      }
    } catch (RequestException $exception) {
      $text           = $exception->getMessage();
      $responseString = $this->customMessage($text);
      $this->output   .= $responseString;
    }

    return $status;
  }

  /**
   * Get get Language Code
   *
   * @param $body
   *
   * @return array
   */
  private function getLanguageCode($body): array {
    $languageCodeArray = json_decode($body, TRUE)[0]['languages'];
    $languageCode      = [];
    foreach ($languageCodeArray as $lang) {
      $languageCode[] = str_replace('"', '', $lang['iso639_1']);
    }

    return $languageCode;
  }


  /**
   * Compare Countries
   *
   * @param null $firstArg
   * @param null $secondArg
   * @param bool $allMessages
   *
   * @return bool|null
   */
  private function compareCountries($firstArg = NULL, $secondArg = NULL, $allMessages = TRUE) {
    if (NULL === $firstArg or NULL === $secondArg) {
      return NULL;
    }

    $ok = TRUE;


    $languageCode = [];

    foreach ([$firstArg, $secondArg] as $arg) {

      $url = str_replace('{code}', $arg, $this->getRestCountriesService('countryName'));

      $response = MakeRequest::get($url);
      $body     = $response->getBody()->getContents();
      $status   = $response->getStatusCode();

      if ($status === 200) {
        $languageCode[] = $this->getLanguageCode($body);
      }
      else {
        $ok = FALSE;
        break;
      }
    }

    $operator = 'do not ';
    if ($ok) {
      foreach ($languageCode[0] as $lang) {
        if (in_array($lang, $languageCode[1])) {
          $operator = '';
          break;
        }
      }

      $text           = $firstArg . ' and ' . $secondArg . ' ' . $operator . 'speak the same language';
      $responseString = $this->customMessage($text);
      $this->output   .= $responseString;

    }
    else if ($allMessages) {
      $responseString = $this->countryCodeError();
      $this->output   .= $responseString;

    }

    return $ok;
  }

  /**
   * Return list of countries with the same language.
   *
   * @param $languageCode
   * @param $url
   * @param $countryName
   *
   * @return array
   */
  private function sameLanguageCountry($languageCode, $url, $countryName): array {
    $languageString = implode(', ', $languageCode);

    $languageString = str_replace('"', '', $languageString);
    $responseString = $this->countryCode($languageString);
    $this->output   .= $responseString;

    $countryList = [];
    foreach ($languageCode as $lang) {
      $currentUrl = str_replace('{code}', $lang, $url);
      $response   = MakeRequest::get($currentUrl);

      $body      = $response->getBody()->getContents();
      $countries = json_decode($body, TRUE);

      foreach ($countries as $country) {
        if (isset($country['name']) and $country['name'] != $countryName) {
          $countryList[] = $country['name'];
        }
      }
    }

    return $countryList;
  }

  /**
   * validate the number of argv
   *
   * @return string
   */
  public function validate() {
    if ($this->getArgvCount() !== 2 && $this->getArgvCount() !== 3) {
      $responseString = $this->invalidParameters();
      $this->output   .= $responseString;
    }
    return $this->output;

  }
}