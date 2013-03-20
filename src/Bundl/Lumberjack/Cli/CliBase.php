<?php
/**
 * @author  brooke.bryan
 */

namespace Bundl\Lumberjack\Cli;

use Bundl\Lumberjack\Mappers\LogEntry;
use Bundl\Lumberjack\Mappers\TransactionLog;
use Cubex\Cli\CliCommand;
use Cubex\Foundation\Config\ConfigTrait;

abstract class CliBase extends CliCommand
{
  use ConfigTrait;

  public function outputLine($time, $line)
  {
    echo strtoupper($line['level']) . ' ';
    echo '[';
    echo $this->logOutputFilename($line['file']) . ':' . $line['line'];
    echo '] ';
    echo date("Y-m-d H:i:s", (int)$time) . ' ';
    echo $line['message'];
    echo "\n";
  }

  public function logOutputFilename($filename)
  {
    $parts = explode('\\', $filename);
    return implode('\\', array_slice($parts, -4));
  }

  public function outputEntriesSince($log, $start)
  {
    $cf = TransactionLog::cf();
    do
    {
      $keys  = [];
      $lines = $cf->getSlice($log, $start);
      if($lines)
      {
        foreach($lines as $line => $type)
        {
          $keys[] = LogEntry::makeId($log, $line);
          $start  = $line;
        }
        $entries = LogEntry::cf()->multiGetSlice($keys);
        if($entries)
        {
          foreach($entries as $transtime => $entry)
          {
            list(, $time) = explode('-', $transtime);
            $this->outputLine($time, $entry);
          }
        }
      }
    }
    while(count($lines) == 100);
  }
}
