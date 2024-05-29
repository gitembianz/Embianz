<div>
	<x-alert />
	<x-loading />
	<div class="accordion">
		<div class="accordion__btn-flex">
			<button class="accordion__btn" wire:click.prevent="@if ($showrelatedprod === false) $set('showrelatedprod', true) @else $set('showrelatedprod', false) @endif">
				{{ __("Cart Items ") }}({{ $cart->carts->count() }})
			</button>
			<button class="accordion__upload">
				<svg>
					<line x1="12" y1="5" x2="12" y2="19"></line>
					<line x1="5" y1="12" x2="19" y2="12"></line>
				</svg>
			</button>
		</div>
		@if ($showrelatedprod)
			<div class="accordion__content">
				@if ($cart->carts()->count() > 0)
					{{-- Header of the table --}}
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
					<div class="modal" id="confirmationmodalcart">
						<div class="modal-content">
							<h1 class="modal-content-title">
								{{ __("Are you sure to delete this record?") }}
							</h1>
							<input wire:click.prevent="deleteSingleRecord()" class="modal-content-btn submit" type="button" value="Confirm">
							<input class="modal-content-btn delete" type="button" onclick="document.getElementById('confirmationmodalcart').style.display='none'" value="Cancel">
							<span class="modal-content-btn delete" onclick="document.getElementById('confirmationmodalcart').style.display='none'">
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
					{{-- Table --}}
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
								@if ($this->showColumn("Product"))
									<th>
										<span class="table__header--btn">Product Name</span>
									</th>
								@endif
								@if ($this->showColumn("Price"))
									<th wire:click="sortBy('price')">
										<button class="table__header--btn" @if ($orderBy === "price" && $orderAsc === "1") data-symbol="up"
                                                @else data-symbol="down" @endif>
											Price
											<svg>
												<line x1="12" y1="5" x2="12" y2="19">
												</line>
												<polyline points="19 12 12 19 5 12"></polyline>
											</svg>
										</button>
									</th>
								@endif
								@if ($this->showColumn("Quantity"))
									<th wire:click="sortBy('quantity')">
										<button class="table__header--btn" @if ($orderBy === "quantity" && $orderAsc === "1") data-symbol="up"
                                                @else data-symbol="down" @endif>
											Quantity
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
							@if ($cartproducts->isEmpty())
								<tr>
									<td class="table__empty" colspan="{{ count($columns) + 3 }}">
										No record found.
									</td>
								</tr>
							@else
								@foreach ($cartproducts as $index => $product)
									@if ($index < $perPage)
										<tr class="@if ($this->isChecked($product->id)) table__row--selected @endif">
											<td data-title="Check">
												<input type="checkbox" value="{{ $product->id }}" wire:model="checked">
											</td>
											@if ($this->showColumn("Id"))
												<td data-title="ID">{{ $product->id }}</td>
											@endif
											@if ($this->showColumn("Product"))
												<td data-title="Product">
													<a href="{{ route("show_product", ['id'=> $product->product->id ])}}">{{ $product->product->name }}</a>
												</td>
											@endif
											@if ($this->showColumn("Price"))
												<td class="table__description" data-title="Price">
													{{ $product->price }}
												</td>
											@endif
											@if ($this->showColumn("Quantity"))
												<td class="table__description" data-title="Quantity">
													{{ $product->quantity }}
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
														{{ $product->created_at }}
													</div>
												</td>
											@endif
											<td data-title="Action">
												<div class="table__buttons">
													<button class="delete" wire:click.prevent="confirmItemRemoval({{ $product->id }})">
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
					@if (count($cartproducts) >= 10)
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
