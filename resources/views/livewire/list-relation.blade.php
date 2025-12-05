<div class="accordion @if ($showrelated) active @endif">

    {{-- Header --}}
    <div class="accordion__header">
        <button
            class="button button--flexed button--fill button--primary @if ($showrelated) button--secondary active @endif"
            wire:click.prevent="@if ($showrelated === false) $set('showrelated', true) @else $set('showrelated', false) @endif">

            {{ $title }} ({{ $allResults }})

            <svg><polyline points="6 9 12 15 18 9"></polyline></svg>
        </button>
    </div>

    {{-- Body --}}
    <div class="accordion__body" style="padding-top: 5px; overflow-y: auto; max-height: 500px;">

        <table class="expandable-table">
            <thead>
                <tr>
                    @foreach ($selectedColumns as $index => $column)
                        <th @if ($index > 1) class="hidden" @endif>
                            <button class="table--btn">{{ $column }}</button>
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @if (empty($results))
                    <tr>
                        <td class="table--empty" colspan="{{ count($selectedColumns) }}">
                            No record found.
                        </td>
                    </tr>
                @else
                    @foreach ($results as $item)
                        <tr class="expandable-row">

                            @foreach ($selectedColumns as $index => $column)
                                <td @if ($index > 1) class="hidden" @endif>
                                    @if ($column === 'object')
                                        <a href="{{ route('show_' . $model, ['id' => $item['id']]) }}">
                                            {{ $item['object'] }}
                                        </a>
                                    @else
                                        {{ $item[$column] }}
                                    @endif
                                </td>
                            @endforeach

                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        @if (count($results) < $allResults)
            <button class="button button--secondary button--fill" wire:click="loadMore">
                Load more
            </button>
        @endif
    </div>
</div>
