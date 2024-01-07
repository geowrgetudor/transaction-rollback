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
        [$executedCommands, $time, $runAt] = $this->remember(
            fn () => Pulse::aggregate(
                'executed_command',
                'count',
                $this->periodAsInterval(),
                'count',
            )->map(function ($row) {
                [$command, $arguments, $options, $statusCode] = json_decode($row->key, flags: JSON_THROW_ON_ERROR);

                return (object) [
                    'name' => $command,
                    'arguments' => json_encode((array) $arguments),
                    'options' => json_encode((array) $options),
                    'statusCode' => $statusCode,
                    'count' => $row->count,
                ];
            }),
        );

        return View::make('transation-rollbacks::livewire.transation-rollbacks', [
            'executedCommands' => $executedCommands,
        ]);
    }
}
