<?php

namespace Geow\TransactionRollback\Livewire;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\View;
use Laravel\Pulse\Facades\Pulse;
use Laravel\Pulse\Livewire\Card;
use Laravel\Pulse\Livewire\Concerns\RemembersQueries;
use Laravel\Pulse\Livewire\Concerns\HasPeriod;
use Livewire\Attributes\Lazy;

class TransactionRollback extends Card
{
    use HasPeriod;
    use RemembersQueries;

    #[Lazy]
    public function render(): Renderable
    {
        [$rollbacks, $time, $runAt] = $this->remember(
            fn () => Pulse::aggregate(
                'rolledback_transaction',
                'count',
                $this->periodAsInterval(),
                'count',
            )->map(function ($row) {
                [$connectionName, $databaseName, $queries] = json_decode($row->key, flags: JSON_THROW_ON_ERROR);

                return (object) [
                    'connection' => $connectionName,
                    'database' => $databaseName,
                    'queries' => $queries,
                    'count' => $row->count,
                ];
            }),
        );

        return View::make('transaction-rollbacks::livewire.transaction-rollbacks', [
            'rollbacks' => $rollbacks,
        ]);
    }
}
