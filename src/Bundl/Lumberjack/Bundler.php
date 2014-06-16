<?php
/**
 * @author  brooke.bryan
 */

namespace Bundl\Lumberjack;

use Bundl\Lumberjack\Mappers\LogEntry;
use Bundl\Lumberjack\Mappers\TransactionLog;
use Cubex\Bundle\Bundle;
use Cubex\Events\EventManager;
use Cubex\Events\StdEvent;
use Psr\Log\LogLevel;

class Bundler extends Bundle
{
  public function init($initialiser = null)
  {
    EventManager::listen(EventManager::CUBEX_LOG, [$this, 'log']);
  }

  public function log(StdEvent $event)
  {
    $level         = $event->getStr('level', LogLevel::INFO);
    $message       = $event->getStr('message', '');
    $context       = $event->getRaw('context', null);
    $file          = $event->getStr('file', 'unknown');
    $line          = $event->getInt('line', 0);
    $transactionId = $event->getStr('transaction_id', null);
    $logName       = $event->getStr('log_name', $transactionId);

    if($transactionId === null)
    {
      return null;
    }

    $logTime = microtime(true);

    try
    {
      $transLog = new TransactionLog();
      $transLog->setId($logName);
      $transLog->setData("$logTime", $level);
      $transLog->saveChanges();

      $logEntry = new LogEntry();
      $logEntry->setId(LogEntry::makeId($logName, $logTime));
      $logEntry->level            = $level;
      $logEntry->message          = $message;
      $logEntry->context          = $context;
      $logEntry->file             = $file;
      $logEntry->line             = $line;
      $logEntry->cubexEnvironment = defined(
        "CUBEX_ENV"
      ) ? CUBEX_ENV : 'not set';
      $logEntry->serverIp         = idx($_SERVER, 'SERVER_ADDR', '127.0.0.1');
      $logEntry->serverName       = idx($_SERVER, 'SERVER_NAME', 'localhost');
      $logEntry->saveChanges();
    }
    catch(\Exception $e)
    {
      return null;
    }
  }
}
