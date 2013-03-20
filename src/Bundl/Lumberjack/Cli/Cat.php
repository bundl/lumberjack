<?php
/**
 * @author  brooke.bryan
 */

namespace Bundl\Lumberjack\Cli;

class Cat extends CliBase
{
  protected $_log;

  public function __construct($loader, $args)
  {
    parent::__construct($loader, $args);
    $this->_log = key($args);
  }

  public function execute()
  {
    $this->outputEntriesSince($this->_log, '');
  }
}
