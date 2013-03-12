<?php
/**
 * @author  brooke.bryan
 */

namespace Bundl\Lumberjack\Mappers;

use Cubex\Mapper\Cassandra\CassandraMapper;

class LumberjackCassandra extends CassandraMapper
{
  protected $_cassandraConnection = 'lumberjackcass';
}
