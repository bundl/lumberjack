<?php
/**
 * @author  brooke.bryan
 */

namespace Bundl\Lumberjack\Cli;

use Bundl\Lumberjack\Mappers\TransactionLog;
use Cubex\Cli\CliArgument;

class Tail extends CliBase
{
  protected $_lines;
  protected $_log;

  protected function _argumentsList()
  {
    return [
      new CliArgument(
        "lines",
        "output the last K lines, instead of the last 10", 'n',
        CliArgument::VALUE_REQUIRED, 'K'
      )
    ];
  }

  public function init()
  {
    $this->_lines = $this->argumentValue("lines", 10);
    $this->_log   = $this->positionalArgValue(0);
    if(empty($this->_log))
    {
      throw new \Exception("No log name provided");
    }
  }

  public function execute()
  {
    try
    {
      $cf      = TransactionLog::cf();
      $entries = $cf->getSlice($this->_log, '', '', true, $this->_lines);

      if($entries)
      {
        $since = last_key($entries);
        $this->outputEntriesSince($this->_log, $since);
      }
      else
      {
        echo "No data found\n";
      }
    }
    catch(\Exception $e)
    {
      if($e->getCode() == 404)
      {
        echo "No log exists";
      }
      throw $e;
    }
  }
}
