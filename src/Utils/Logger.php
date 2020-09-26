<?php

declare(strict_types=1);

namespace Alpdesk\AlpdeskAwcPlugin\Utils;

use Alpdesk\AlpdeskAwcPlugin\Models\AlpdeskawcLogsModel;

class Logger {

  public static function log($channel, $key, $request, $response) {
    $logModel = new AlpdeskawcLogsModel();
    $logModel->tstamp = time();
    $logModel->channel = $channel;
    $logModel->key = $key;
    $logModel->request = $request;
    $logModel->response = $response;
    $logModel->save();
  }

}
