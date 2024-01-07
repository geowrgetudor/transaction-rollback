<?php

namespace Geow\TransactionRollback\Recorders;

use Carbon\CarbonImmutable;
use Illuminate\Config\Repository;
use Illuminate\Database\Events\TransactionRolledBack;
use Illuminate\Support\Facades\Log;
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
        [$timestamp, $connectionName, $databaseName] = [
            CarbonImmutable::now()->getTimestamp(),
            $event->connection->getName(),
            $event->connection->getDatabaseName(),
        ];

        $this->pulse->lazy(function () use ($timestamp, $connectionName, $databaseName) {
            if ($this->shouldIgnore($connectionName) || $this->shouldIgnore($databaseName)) {
                return;
            }

            $this->pulse->record(
                type: 'rolledback_transaction',
                key: json_encode([$connectionName, $databaseName], flags: JSON_THROW_ON_ERROR),
                timestamp: $timestamp
            )->count();
        });
    }
}
