<?php
/**
 * @author  brooke.bryan
 */

namespace Bundl\Lumberjack\Cli;

class Cat extends CliBase
{
  protected $_log;

  public function init()
  {
    $this->_log = $this->positionalArgValue(0);
  }

  public function execute()
  {
    $this->outputEntriesSince($this->_log, '');
  }
}
