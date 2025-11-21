<div class="accordion @if ($showrelated) active @endif">
    {{-- Accordion Header --}}
    <div class="accordion__header">
        <button
            class="button button--flexed button--fill button--primary @if ($showrelated) button--secondary active @endif"
            wire:click.prevent="@if ($showrelated === false) $set('showrelated', true) @else $set('showrelated', false) @endif">
            {{ $title }}
            ({{ $allResults }})
            <svg>
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </button>
    </div>

    {{-- Accordion Body --}}
    <div class="accordion__body" style="padding-top: 5px; overflow-y: auto;!important; max-height: 500px;">
        {{-- Table --}}
        <table class="expandable-table">
            <thead>
                <tr>
                    @foreach ($selectedColumns as $index => $column)
                        <th @if ($index > 1) class="hidden" @endif>
                            <button class="table--btn">
                                {{ $column }}
                            </button>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 0;
                @endphp
                @if (empty($results))
                    <tr>
                        <td class="table--empty" colspan="{{ count($selectedColumns) + 2 }}">No record found.</td>
                    </tr>
                @else
                    @foreach ($results as $item)
                        <tr @if ($loop->last) id="last_record" @endif class="expandable-row">
                            @foreach ($selectedColumns as $index => $column)
                                <td @if ($index > 1) class="hidden" @endif
                                    data-title="{{ $column }}">
                                    @if ($column === 'object')
                                        @if ($item['type'] === 'product')
                                            <a
                                                href="{{ route('show_product', ['id' => $item['id']]) }}">{{ $item['object'] }}</a>
                                        @else
                                            <a
                                                href="{{ route('show_category', ['id' => $item['id']]) }}">{{ $item['object'] }}</a>
                                        @endif
                                    @else
                                        {{ $item[$column] }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        @php
                            $i++;
                        @endphp
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
