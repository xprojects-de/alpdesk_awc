<?php

declare(strict_types=1);

namespace Alpdesk\AlpdeskAwcPlugin\Elements;

use Alpdesk\AlpdeskCore\Elements\AlpdeskCoreElement;
use Alpdesk\AlpdeskCore\Library\Mandant\AlpdescCoreBaseMandantInfo;

class AlpdeskElementAwc extends AlpdeskCoreElement {

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
