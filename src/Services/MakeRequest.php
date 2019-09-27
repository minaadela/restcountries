<?php

namespace App\Services;

class MakeRequest extends GuzzleHttp {

  /**
   * Make a get Request
   *
   * @param $url
   *
   * @return mixed|null|\Psr\Http\Message\ResponseInterface
   */
  public static function get($url) {
    return parent::guzzleGetRequest($url);

  }
}