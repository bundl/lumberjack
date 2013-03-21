<?php
/**
 * @author  brooke.bryan
 */

namespace Bundl\Lumberjack\Cli;

class Cat extends CliBase
{
  public function init()
  {
    $this->requireLog();
  }

  public function execute()
  {
    $this->outputEntriesSince($this->_log, '');
  }
}
