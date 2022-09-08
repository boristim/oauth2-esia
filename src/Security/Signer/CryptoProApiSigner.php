<?php

namespace Ekapusta\OAuth2Esia\Security\Signer;

use Ekapusta\OAuth2Esia\Security\Signer;

class CryptoProApiSigner extends Signer
{
  public function __construct($params)
  {
    $this->keyPin = $params['keyPin'] ?? null;
    $this->findType = $params['findType'] ?? 'sha1';
    $this->certQuery = $params['certQuery'] ?? null;
    $this->apiUrl = $params['apiUrl'] ?? null;

  }

  public function sign($message)
  {
    $response = $this->request($message);
    $response = json_decode($response, true);
    $sig = $response['signedContent'] ?? null;
//    file_put_contents(__DIR__ . '/message', $message);
//    file_put_contents(__DIR__ . '/sig.json', print_r($response, true));
//    file_put_contents(__DIR__ . '/sig', $sig);
    return $sig;
  }

  public function request($message)
  {

    $certQuery = 'find_type=' . $this->findType . '&query=' . $this->certQuery . '&pin=' . $this->keyPin;
    $cmd = 'echo "' . $message . '" | curl -sS -X POST --data-binary @- "' . $this->apiUrl . '/sign?' . $certQuery . '"';
//    file_put_contents(__DIR__ . '/cmd', $cmd);
    return shell_exec($cmd);
  }
}