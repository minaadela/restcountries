<?php

namespace App\Model;


class RestCountries extends ResponseHandler {

  protected $argv;

  /**
   * RestCountries constructor.
   *
   * @param $argv
   */
  public function __construct($argv) {
    $this->setArgv($argv);
  }

  /**
   * Return argv
   *
   * @return array
   */
  public function getArgv(): array {
    return $this->argv;
  }

  /**
   * set argv
   *
   * @param $argv
   */
  public function setArgv($argv) {
    $this->argv = $argv;
  }

  /**
   * Return the count of argv
   *
   * @return int
   */
  public function getArgvCount() {
    return count($this->getArgv());
  }

  /**
   * Return the restcountries.eu services
   *
   * @param $service
   *
   * @return mixed
   */
  public function getRestCountriesService($service) {
    $services = [
      'countryName' => 'https://restcountries.eu/rest/v2/name/{code}?fullText=true',
      'countryLang' => 'https://restcountries.eu/rest/v2/lang/{code}',
    ];
    return $services[$service];
  }
}