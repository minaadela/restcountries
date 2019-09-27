<?php

namespace App\Model;

/**
 * Class ResponseHandler
 *
 * @package App\Model
 */
class ResponseHandler {

  public $message;

  protected $messages = [
    'invalidParameters' => 'Invalid Parameters.' . PHP_EOL,
    'countryCodeError'  => 'Country name is invalid.' . PHP_EOL,
    'countryCode'       => 'Country language code: {code}' . PHP_EOL,
    'countryList'       => '{country} speaks same language with these countries:' . PHP_EOL . '{code}' . PHP_EOL,
    'customMessage'     => '{code}' . PHP_EOL,
  ];

  /**
   * Return invalidParameters message.
   *
   * @return string
   */
  public function invalidParameters(): string {
    $this->getMessage('invalidParameters');
    return $this->message;
  }

  /**
   * Return countryCodeError Message
   *
   * @return string
   */
  public function countryCodeError(): string {
    $this->getMessage('countryCodeError');

    return $this->message;
  }

  /**
   * Return countryCode Message
   *
   * @param $text
   *
   * @return string
   */
  public function countryCode($text): string {
    $this->getMessage('countryCode');

    $this->message = str_replace('{code}', $text, $this->message);

    return $this->message;
  }

  /**
   * return countryList Message
   *
   * @param $text
   * @param $country
   *
   * @return string
   */
  public function countryList($text, $country): string {
    $this->getMessage('countryList');

    $this->message = str_replace('{code}', $text, $this->message);
    $this->message = str_replace('{country}', $country, $this->message);

    return $this->message;
  }

  /**
   * return custom Message
   *
   * @param $text
   *
   * @return string
   */
  public function customMessage($text): string {
    $this->getMessage('customMessage');

    $this->message = str_replace('{code}', $text, $this->message);

    return $this->message;
  }

  /**
   * return Message
   *
   * @param $message
   *
   * @return $this
   */
  private function getMessage($message) {
    $this->message = $this->messages[$message];

    return $this;
  }
}