<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

/**
 * Class GuzzleHttp
 *
 * @package App\Services
 */
class GuzzleHttp {

  public static function guzzleGetRequest($url) {
    $client = new Client();
    try {
      $response = $client->request('GET', $url);

    } catch (ClientException $exception) {
      $response = $exception->getResponse();

    } catch (RequestException $exception) {
      $response = $exception->getResponse();

    }

    return $response;
  }
}