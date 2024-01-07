<?php

namespace Geow\TransactionRollback\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Geow\TransactionRollback\TransactionRollback
 */
class TransactionRollback extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Geow\TransactionRollback\TransactionRollback::class;
    }
}
