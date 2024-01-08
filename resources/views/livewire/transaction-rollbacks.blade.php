<x-pulse::card :cols="$cols" :rows="$rows" :class="$class">
    <x-pulse::card-header name="{{ __('Transaction rollbacks') }}">
        <x-slot:icon>
            <x-dynamic-component :component="'pulse::icons.sparkles'" />
        </x-slot:icon>
    </x-pulse::card-header>

    <x-pulse::scroll :expand="$expand">
        @if ($rollbacks->isEmpty())
            <x-pulse::no-results />
        @else
            <x-pulse::table>
                <colgroup>
                    <col width="0%" />
                    <col width="0%" />
                    <col width="0%" />
                    <col width="0%" />
                </colgroup>
                <x-pulse::thead>
                    <tr>
                        <x-pulse::th>{{ __('Connection') }}</x-pulse::th>
                        <x-pulse::th>{{ __('Database') }}</x-pulse::th>
                        <x-pulse::th>{{ __('Queries') }}</x-pulse::th>
                        <x-pulse::th class="text-right">{{ __('Count') }}</x-pulse::th>
                    </tr>
                </x-pulse::thead>
                <tbody>
                    @foreach ($rollbacks as $key => $rollback)
                        <tr class="h-2 first:h-0"></tr>
                        <tr wire:key="{{ $key }}">
                            <x-pulse::td class="max-w-[1px]">
                                {{ $rollback->connection }}
                            </x-pulse::td>
                            <x-pulse::td class="text-gray-700 dark:text-gray-300 font-bold">
                                {{ $rollback->database }}
                            </x-pulse::td>
                            <x-pulse::td class="text-left text-gray-700 dark:text-gray-300 font-bold">
                                @if (!empty($rollback->queries))
                                    @foreach ($rollback->queries as $query)
                                        <code class="block text-xs text-gray-900 dark:text-gray-100">
                                            {{ $query }}
                                        </code>
                                    @endforeach
                                @else
                                    {{ __('No queries. Please use query logging.') }}
                                @endif
                            </x-pulse::td>
                            <x-pulse::td numeric class="text-gray-700 dark:text-gray-300 font-bold">
                                {{ (int) $rollback->count }}
                            </x-pulse::td>
                        </tr>
                    @endforeach
                </tbody>
            </x-pulse::table>
        @endif
    </x-pulse::scroll>
</x-pulse::card>
