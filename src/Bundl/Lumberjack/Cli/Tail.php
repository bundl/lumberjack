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
        "output the last K lines, instead of the last 10",
        CliArgument::VALUE_OPTIONAL, 'n'
      )
    ];
  }

  public function init()
  {
    $this->_lines = $this->argumentValue("lines", 10);
    $this->_log   = $this->positionalArgValue(0);
  }

  public function execute()
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
}
