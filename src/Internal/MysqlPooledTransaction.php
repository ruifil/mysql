<?php declare(strict_types=1);

namespace Amp\Mysql\Internal;

use Amp\Mysql\MysqlResult;
use Amp\Mysql\MysqlStatement;
use Amp\Mysql\MysqlTransaction;
use Amp\Sql\Common\PooledTransaction;

/**
 * @internal
 * @extends PooledTransaction<MysqlResult, MysqlStatement, MysqlTransaction>
 */
final class MysqlPooledTransaction extends PooledTransaction implements MysqlTransaction
{
    use MysqlTransactionDelegate;

    protected function getTransaction(): MysqlTransaction
    {
        return $this->transaction;
    }
}
