<?php
/**
 * @author  brooke.bryan
 */

namespace Bundl\Lumberjack\Mappers;

class LogEntry extends LumberjackCassandra
{
  public $written;
  public $level;
  public $message;
  public $context;
  public $file;
  public $line;

  public static function makeId($transactionId, $logTime)
  {
    return $transactionId . '-' . $logTime;
  }
}
