<?php

namespace Geow\TransactionRollback\Recorders;

use Carbon\CarbonImmutable;
use Illuminate\Config\Repository;
use Illuminate\Database\Events\TransactionRolledBack;
use Laravel\Pulse\Pulse;
use Laravel\Pulse\Recorders\Concerns;

class TransactionRollbackRecorder
{
    use Concerns\Ignores;

    public array $listen = [
        TransactionRolledBack::class,
    ];

    public function __construct(
        protected Pulse $pulse,
        protected Repository $config
    ) {
    }

    public function record(TransactionRolledBack $event): void
    {
        [$timestamp, $connectionName, $databaseName, $queryLog] = [
            CarbonImmutable::now()->getTimestamp(),
            $event->connection->getName(),
            $event->connection->getDatabaseName(),
            collect($event->connection->getQueryLog())->map(fn($i) => $i['query'])->all(),
        ];

        $this->pulse->lazy(function () use ($timestamp, $connectionName, $databaseName, $queryLog) {
            if ($this->shouldIgnore($connectionName) || $this->shouldIgnore($databaseName)) {
                return;
            }

            $this->pulse->record(
                type: 'rolledback_transaction',
                key: json_encode([$connectionName, $databaseName, $queryLog], flags: JSON_THROW_ON_ERROR),
                timestamp: $timestamp
            )->count();
        });
    }
}
