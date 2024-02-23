<div>
	<x-alert />
	<x-loading />
	<div class="accordion">
		<div class="accordion__btn-flex">
			<button class="accordion__btn" wire:click.prevent="@if ($showrelated === false) $set('showrelated', true) @else $set('showrelated', false) @endif">
				{{ __("Orders ") }}({{ $account->orders()->count() }})
			</button>
			<button class="accordion__upload">
				<svg>
					<line x1="12" y1="5" x2="12" y2="19"></line>
					<line x1="5" y1="12" x2="19" y2="12"></line>
				</svg>
			</button>
		</div>
		@if ($showrelated)
			<div class="accordion__content">
				@if ($account->orders()->count() > 0)
					<div class="panel__header">
						<input class="panel__header--input" type="text" wire:model.debounce.300ms="search" placeholder="Search..." style="grid-column: 1/4">
						<div class="panel__header--bundle">
							<div class="dropdown">
								<button wire:click.prevent="@if ($col === false) $set('col', true) @else $set('col', false) @endif" class="dropdown-button">
									Columns
								</button>
								@if ($col)
									<div class="dropdown-list" style="display: flex;">
										@foreach ($columns as $column)
											<div class="dropdown-item">
												<input type="checkbox" wire:model="selectedColumns" value="{{ $column }}" {{ in_array($column, $selectedColumns) ? "checked" : "" }}>
												<label>{{ $column }}</label>
											</div>
										@endforeach
									</div>
								@endif
							</div>
							<div class="dropdown none" @if ($checked) style="display: unset; z-index: 5;" @endif>
								<button wire:click.prevent="@if ($all === false) $set('all', true); $set('col', false) @else $set('all', false) @endif" class="dropdown-button none" @if ($checked) style="display: flex" @endif>
									With Checked({{ count($checked) }})
								</button>
								@if ($checked && $all)
									<div class="dropdown-list" style="display: flex;">
										<button class="dropdown-item delete" type="button" wire:click="confirmItemsRemoval()">
											Delete
										</button>
									</div>
								@endif
							</div>
						</div>
						@if ($selectPage && $selectAll)
							<div class="panel__header--checked">
								<p>
									You selected <strong>{{ count($checked) }}</strong> items.
								</p>
							</div>
						@elseif($selectPage)
							<div class="panel__header--checked" wire:click="selectAll">
								<p>
									You selected {{ count($checked) }} items, select all?
								</p>
							</div>
						@endif
					</div>
					{{-- modals --}}
					{{-- delete single record --}}
					<div class="modal" id="confirmationmodal">
						<div class="modal-content">
							<h1 class="modal-content-title">
								{{ __("Are you sure to delete this record?") }}
							</h1>
							<input wire:click.prevent="deleteSingleRecord()" class="modal-content-btn submit" type="button" value="Confirm">
							<input class="modal-content-btn delete" type="button" onclick="document.getElementById('confirmationmodal').style.display='none'" value="Cancel">
							<span class="modal-content-btn delete" onclick="document.getElementById('confirmationmodal').style.display='none'">
								<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewbox="0 0 24 24" fill="none" stroke="#BBFCDE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<line x1="18" y1="6" x2="6" y2="18">
									</line>
									<line x1="6" y1="6" x2="18" y2="18">
									</line>
								</svg>
							</span>
						</div>
					</div>
					{{-- delete myltiple records --}}
					<div class="modal" id="confirmationmodalmultiple">
						<div class="modal-content">
							<h1 class="modal-content-title">
								{{ __("Are you sure to delete those records?") }}
							</h1>
							<input wire:click.prevent="deleteRecords()" class="modal-content-btn submit" type="button" value="Confirm">
							<input class="modal-content-btn delete" type="button" onclick="document.getElementById('confirmationmodalmultiple').style.display='none'" value="Cancel">
							<span class="modal-content-btn delete" onclick="document.getElementById('confirmationmodalmultiple').style.display='none'">
								<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewbox="0 0 24 24" fill="none" stroke="#BBFCDE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

									<line x1="18" y1="6" x2="6" y2="18">
									</line>
									<line x1="6" y1="6" x2="18" y2="18">
									</line>
								</svg>
							</span>
						</div>
					</div>
					{{-- end modals --}}
					<table class="table">
						<thead>
							<tr>
								<th>
									<input type="checkbox" wire:model="selectPage">
								</th>
								@if ($this->showColumn("Id"))
									<th wire:click="sortBy('id')">
										<button class="table__header--btn" @if ($orderBy === "id" && $orderAsc === "1") data-symbol="up"
                                                @else data-symbol="down" @endif>
											ID
											<svg>
												<line x1="12" y1="5" x2="12" y2="19">
												</line>
												<polyline points="19 12 12 19 5 12"></polyline>
											</svg>
										</button>
									</th>
								@endif
								@if ($this->showColumn("Name"))
									<th wire:click="sortBy('name')">
										<button class="table__header--btn" @if ($orderBy === "name" && $orderAsc === "1") data-symbol="up"
                                                @else data-symbol="down" @endif>
											Name
											<svg>
												<line x1="12" y1="5" x2="12" y2="19">
												</line>
												<polyline points="19 12 12 19 5 12"></polyline>
											</svg>
										</button>
									</th>
								@endif
								@if ($this->showColumn("Session Id"))
									<th wire:click="sortBy('session_id')">
										<button class="table__header--btn" @if ($orderBy === "session_id" && $orderAsc === "1") data-symbol="up"
                                                @else data-symbol="down" @endif>
											Session ID
											<svg>
												<line x1="12" y1="5" x2="12" y2="19">
												</line>
												<polyline points="19 12 12 19 5 12"></polyline>
											</svg>
										</button>
									</th>
								@endif
								@if ($this->showColumn("Cart"))
									<th wire:click="sortBy('cart_id')">
										<button class="table__header--btn" @if ($orderBy === "cart_id" && $orderAsc === "1") data-symbol="up"
                                                @else data-symbol="down" @endif>
											Cart
											<svg>
												<line x1="12" y1="5" x2="12" y2="19">
												</line>
												<polyline points="19 12 12 19 5 12"></polyline>
											</svg>
										</button>
									</th>
								@endif
								@if ($this->showColumn("Quantity Amount"))
									<th wire:click="sortBy('quantity_amount')">
										<button class="table__header--btn" @if ($orderBy === "quantity_amount" && $orderAsc === "1") data-symbol="up"
                                                @else data-symbol="down" @endif>
											Quantity Amount
											<svg>
												<line x1="12" y1="5" x2="12" y2="19">
												</line>
												<polyline points="19 12 12 19 5 12"></polyline>
											</svg>
										</button>
									</th>
								@endif
								@if ($this->showColumn("Sum Amount"))
									<th wire:click="sortBy('sum_amount')">
										<button class="table__header--btn" @if ($orderBy === "sum_amount" && $orderAsc === "1") data-symbol="up"
                                                @else data-symbol="down" @endif>
											Sum Amount
											<svg>
												<line x1="12" y1="5" x2="12" y2="19">
												</line>
												<polyline points="19 12 12 19 5 12"></polyline>
											</svg>
										</button>
									</th>
								@endif
								@if ($this->showColumn("Currency"))
									<th wire:click="sortBy('currency_id')">
										<button class="table__header--btn" @if ($orderBy === "currency_id" && $orderAsc === "1") data-symbol="up"
                                                @else data-symbol="down" @endif>
											Currency
											<svg>
												<line x1="12" y1="5" x2="12" y2="19">
												</line>
												<polyline points="19 12 12 19 5 12"></polyline>
											</svg>
										</button>
									</th>
								@endif
								@if ($this->showColumn("Status"))
									<th wire:click="sortBy('status')">
										<button class="table__header--btn" @if ($orderBy === "status" && $orderAsc === "1") data-symbol="up"
                                                @else data-symbol="down" @endif>
											Status
											<svg>
												<line x1="12" y1="5" x2="12" y2="19">
												</line>
												<polyline points="19 12 12 19 5 12"></polyline>
											</svg>
										</button>
									</th>
								@endif
								@if ($this->showColumn("Delivery Method"))
									<th wire:click="sortBy('delivery_method')">
										<button class="table__header--btn" @if ($orderBy === "payment_method" && $orderAsc === "1") data-symbol="up"
                                                @else data-symbol="down" @endif>
											Payment Method
											<svg>
												<line x1="12" y1="5" x2="12" y2="19">
												</line>
												<polyline points="19 12 12 19 5 12"></polyline>
											</svg>
										</button>
									</th>
								@endif
								@if ($this->showColumn("Created At"))
									<th wire:click="sortBy('created_at')">
										<button class="table__header--btn" @if ($orderBy === "created_at" && $orderAsc === "1") data-symbol="up"
                                                @else data-symbol="down" @endif>
											Created at
											<svg>
												<line x1="12" y1="5" x2="12" y2="19">
												</line>
												<polyline points="19 12 12 19 5 12"></polyline>
											</svg>
										</button>
									</th>
								@endif
								<th></th>
							</tr>
						</thead>
						<tbody>
							@if ($orders->isEmpty())
								<tr>
									<td class="table__empty" colspan="{{ count($columns) + 3 }}">No record
										found.</td>
								</tr>
							@else
								@foreach ($orders as $index => $order)
									@if ($index < $perPage)
										<tr @if ($loop->last) id="last_record" @endif class="@if ($this->isChecked($order->id)) table__row--selected @endif">
											<td data-title="Check">
												<input type="checkbox" value="{{ $order->id }}" wire:model="checked">
											</td>
											@if ($this->showColumn("Id"))
												<td data-title="ID">
													{{ $order->id }}
												</td>
											@endif
											@if ($this->showColumn("Name"))
												<td data-title="Name">
													<a href="/show_order/{{ $order->id }}">
														{{ $order->name }}
													</a>
												</td>
											@endif
											@if ($this->showColumn("Session Id"))
												<td data-title="Session Id">
													{{ $order->session_id }}
												</td>
											@endif
											@if ($this->showColumn("Cart"))
												<td data-title="Cart">
													<a href="/show_cart/{{ $order->cart_id }}">
														{{ $order->cart->name }}
													</a>
												</td>
											@endif
											@if ($this->showColumn("Quantity Amount"))
												<td data-title="Quantity Amount">
													{{ $order->quantity_amount }}
												</td>
											@endif
											@if ($this->showColumn("Sum Amount"))
												<td data-title="Sum Amount">
													{{ $order->sum_amount }}
												</td>
											@endif
											@if ($this->showColumn("Currency"))
												<td data-title="Currency">
													{{ $order->currency->first()->name }}
												</td>
											@endif
											@if ($this->showColumn("Status"))
												<td data-title="Status">
													{{ $order->status->name }}
												</td>
											@endif
											@if ($this->showColumn("Delivery Method"))
												<td data-title="Delivery Method">
													{{ $order->payment->name }}
												</td>
											@endif
											@if ($this->showColumn("Created At"))
												<td data-title="Created At">
													<div class="table__time">
														<svg>
															<circle cx="12" cy="12" r="10">
															</circle>
															<polyline points="12 6 12 12 16 14"></polyline>
														</svg>
														{{ $order->created_at }}
													</div>
												</td>
											@endif
											<td data-title="Action">
												<div class="table__buttons">
													<button class="edit" wire:click.prevent="confirmItemRemoval({{ $order->id }})">
														<svg>
															<polyline points="3 6 5 6 21 6"></polyline>
															<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
															</path>
														</svg>
													</button>
												</div>
											</td>
										</tr>
									@else
										<?php
										break;
										?>
									@endif
								@endforeach
							@endif
						</tbody>
					</table>
					@if (count($orders) >= 10)
						<div class="table__load-more" wire:click="load">
							Load more
						</div>
					@endif
				@else
					<p class="mt-2">No records related</p>
				@endif
			</div>
		@endif
	</div>
</div>
