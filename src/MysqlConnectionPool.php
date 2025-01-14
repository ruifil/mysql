<?php declare(strict_types=1);

namespace Amp\Mysql;

use Amp\Sql\Common\ConnectionPool;
use Amp\Sql\Result;
use Amp\Sql\SqlConnector;
use Amp\Sql\Statement;
use Amp\Sql\Transaction;

/**
 * @extends ConnectionPool<MysqlConfig, MysqlResult, MysqlStatement, MysqlTransaction, MysqlConnection>
 */
final class MysqlConnectionPool extends ConnectionPool implements MysqlConnection
{
    /**
     * @param positive-int $maxConnections
     * @param positive-int $idleTimeout
     * @param SqlConnector<MysqlConfig, MysqlConnection>|null $connector
     */
    public function __construct(
        MysqlConfig $config,
        int $maxConnections = self::DEFAULT_MAX_CONNECTIONS,
        int $idleTimeout = self::DEFAULT_IDLE_TIMEOUT,
        ?SqlConnector $connector = null,
    ) {
        parent::__construct($config, $connector ?? mysqlConnector(), $maxConnections, $idleTimeout);
    }

    protected function createResult(Result $result, \Closure $release): MysqlResult
    {
        \assert($result instanceof MysqlResult);
        return new Internal\MysqlPooledResult($result, $release);
    }

    protected function createStatement(Statement $statement, \Closure $release): MysqlStatement
    {
        \assert($statement instanceof MysqlStatement);
        return new Internal\MysqlPooledStatement($statement, $release);
    }

    protected function createStatementPool(string $sql, \Closure $prepare): MysqlStatement
    {
        return new Internal\MysqlStatementPool($this, $sql, $prepare);
    }

    protected function createTransaction(Transaction $transaction, \Closure $release): MysqlTransaction
    {
        return new Internal\MysqlPooledTransaction($transaction, $release);
    }

    /**
     * Changes return type to this library's configuration type.
     */
    public function getConfig(): MysqlConfig
    {
        return parent::getConfig();
    }

    /**
     * Changes return type to this library's Result type.
     */
    public function query(string $sql): MysqlResult
    {
        return parent::query($sql);
    }

    /**
     * Changes return type to this library's Statement type.
     */
    public function prepare(string $sql): MysqlStatement
    {
        return parent::prepare($sql);
    }

    /**
     * Changes return type to this library's Result type.
     */
    public function execute(string $sql, array $params = []): MysqlResult
    {
        return parent::execute($sql, $params);
    }

    /**
     * Changes return type to this library's Transaction type.
     */
    public function beginTransaction(): MysqlTransaction
    {
        return parent::beginTransaction();
    }
}
