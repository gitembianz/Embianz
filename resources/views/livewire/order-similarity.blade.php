<div class="accordion @if ($showrelated) active @endif">
    <x-alert />

    {{-- Accordion Header --}}
    <div class="accordion__header">
        <button
            class="button button--flexed button--fill button--primary @if ($showrelated) button--secondary active @endif"
            wire:click.prevent="$set('showrelated', {{ $showrelated ? 'false' : 'true' }})">
            {{ __('Order similarities') }} ({{ $similarities ? $similarities->count() : 0 }})
            <svg>
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </button>
    </div>

    <div class="accordion__body">
        <nav class="nav--controls">
            <input class="input input--long" type="text" wire:model.debounce.300ms="search" placeholder="Search...">

            <div class="dropdown dropdown--right" @if (!$checked) style="display: none;" @endif>
                <button class="button button--primary button--centered button--long" tooltip="Actions with checked"
                    tooltip-top>
                    <span>With Checked ({{ count($checked) }})</span>
                </button>
                <div class="dropdown__content">
                    <button class="button button--primary button--long" wire:click="dowlandproducts">
                        Get products
                    </button>
                </div>
            </div>

            <div class="dropdown dropdown--right" wire:ignore>
                <button class="button button--primary button--centered" tooltip="Show items in table" tooltip-left>
                    <svg>
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                        <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                    </svg>
                </button>
                <div class="dropdown__content">
                    <div class="dropdown__container">
                        @foreach ($columns as $column)
                            <label class="switch switch--primary inline">
                                <input type="checkbox" wire:model="selectedColumns" value="{{ $column }}"
                                    {{ in_array($column, $selectedColumns) ? 'checked' : '' }}>
                                <span>{{ $column }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </nav>
        @if (count($checked) >= $limitselect)
            <button class="button button--fill button--secondary" style="margin-top: 10px;">You can select a maximum of {{ $this->limitselect }} orders.</button>
        @endif

        <table class="expandable-table">
            <thead>
                <tr>
                    <th style="border-right: none; border-left: none;"></th>

                    @if ($this->showColumn('Order'))
                        <th>Order</th>
                    @endif
                    @if ($this->showColumn('Products'))
                        <th>Products</th>
                    @endif
                    @if ($this->showColumn('Similarity'))
                        <th>Similarity (%)</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @if ($similarities && $similarities->isNotEmpty())
                    @foreach ($similarities as $item)
                        @php
                            $order = $item['order'];
                            $similarity = $item['similarity'];
                            $products = $item['products'];
                            $valid = $item['valid'] ?? true;
                            $idsString = $order->id;
                            $isChecked = $this->isChecked($idsString);
                            $isDisabled = !$valid || (!$isChecked && count($checked) >= $limitselect);
                        @endphp

                        <tr
                            class="expandable-row
                            @if ($isChecked) active @endif
                            @if (!$valid || (in_array($item['order']->id, $notprocesabbleorderIds))) notprocess @endif">
                            <td style="border-left: none" data-title="Check">
                                <div class="checkbox--primary">
                                    <input type="checkbox" value="{{ $idsString }}" id="checkbox-{{ $idsString }}"
                                        wire:model="checked" @if ($isDisabled || (in_array($item['order']->id, $notprocesabbleorderIds))) disabled @endif>
                                    <label for="checkbox-{{ $idsString }}"></label>
                                </div>
                            </td>

                            <td>
                                <a href="{{ route('show_order', ['id' => $order->id]) }}">
                                    {{ $order->name ?? 'Order #' . $order->id }}
                                </a>
                            </td>

                            <td>
                                <ul>
                                    @foreach ($products as $product)
                                        <li>
                                            {{ $product['name'] ?? 'Unknown' }}
                                            <small>sku({{ $product['sku'] ?? '-' }})</small> — qty
                                            x{{ $product['quantity'] }}
                                            <span
                                                style="color: {{ $product['available'] < $product['quantity'] ? 'red' : 'green' }}">
                                                ({{ $product['available'] }} available)
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>

                            <td>{{ $similarity }}%</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="table--empty" colspan="4">No record found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
