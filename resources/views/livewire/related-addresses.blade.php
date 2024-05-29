<div>
	<x-alert />
	<x-loading />
	<div class="accordion">
		<div class="accordion__btn-flex">
			<button class="accordion__btn" wire:click.prevent="@if ($showrelatedadd === false) $set('showrelatedadd', true) @else $set('showrelatedadd', false) @endif">
				{{ __("Addresses ") }}({{ $account->addresses()->count() }})
			</button>
			<button class="accordion__upload">
				<svg>
					<line x1="12" y1="5" x2="12" y2="19"></line>
					<line x1="5" y1="12" x2="19" y2="12"></line>
				</svg>
			</button>
		</div>
		@if ($showrelatedadd)
			<div class="accordion__content">
				@if ($account->addresses()->count() > 0)
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
								@if ($this->showColumn("Type"))
									<th wire:click="sortBy('type')">
										<button class="table__header--btn" @if ($orderBy === "type" && $orderAsc === "1") data-symbol="up"
                                                @else data-symbol="down" @endif>
											Type
											<svg>
												<line x1="12" y1="5" x2="12" y2="19">
												</line>
												<polyline points="19 12 12 19 5 12"></polyline>
											</svg>
										</button>
									</th>
								@endif
								@if ($this->showColumn("First Name"))
									<th wire:click="sortBy('first_name')">
										<button class="table__header--btn" @if ($orderBy === "first_name" && $orderAsc === "1") data-symbol="up"
                                                @else data-symbol="down" @endif>
											First Name
											<svg>
												<line x1="12" y1="5" x2="12" y2="19">
												</line>
												<polyline points="19 12 12 19 5 12"></polyline>
											</svg>
										</button>
									</th>
								@endif
								@if ($this->showColumn("Last Name"))
									<th wire:click="sortBy('last_name')">
										<button class="table__header--btn" @if ($orderBy === "last_name" && $orderAsc === "1") data-symbol="up"
                                                @else data-symbol="down" @endif>
											Last Name
											<svg>
												<line x1="12" y1="5" x2="12" y2="19">
												</line>
												<polyline points="19 12 12 19 5 12"></polyline>
											</svg>
										</button>
									</th>
								@endif
								@if ($this->showColumn("Phone"))
									<th wire:click="sortBy('phone')">
										<button class="table__header--btn" @if ($orderBy === "phone" && $orderAsc === "1") data-symbol="up"
                                                @else data-symbol="down" @endif>
											Phone
											<svg>
												<line x1="12" y1="5" x2="12" y2="19">
												</line>
												<polyline points="19 12 12 19 5 12"></polyline>
											</svg>
										</button>
									</th>
								@endif
								@if ($this->showColumn("Email"))
									<th wire:click="sortBy('email')">
										<button class="table__header--btn" @if ($orderBy === "email" && $orderAsc === "1") data-symbol="up"
                                                @else data-symbol="down" @endif>
											Email
											<svg>
												<line x1="12" y1="5" x2="12" y2="19">
												</line>
												<polyline points="19 12 12 19 5 12"></polyline>
											</svg>
										</button>
									</th>
								@endif
								@if ($this->showColumn("Address"))
									<th wire:click="sortBy('address1')">
										<button class="table__header--btn" @if ($orderBy === "address1" && $orderAsc === "1") data-symbol="up"
                                                @else data-symbol="down" @endif>
											Address
											<svg>
												<line x1="12" y1="5" x2="12" y2="19">
												</line>
												<polyline points="19 12 12 19 5 12"></polyline>
											</svg>
										</button>
									</th>
								@endif
								@if ($this->showColumn("Optional Address"))
									<th wire:click="sortBy('address2')">
										<button class="table__header--btn" @if ($orderBy === "address2" && $orderAsc === "1") data-symbol="up"
                                                @else data-symbol="down" @endif>
											Optional Address
											<svg>
												<line x1="12" y1="5" x2="12" y2="19">
												</line>
												<polyline points="19 12 12 19 5 12"></polyline>
											</svg>
										</button>
									</th>
								@endif
								@if ($this->showColumn("Country"))
									<th wire:click="sortBy('country')">
										<button class="table__header--btn" @if ($orderBy === "country" && $orderAsc === "1") data-symbol="up"
                                                @else data-symbol="down" @endif>
											Country
											<svg>
												<line x1="12" y1="5" x2="12" y2="19">
												</line>
												<polyline points="19 12 12 19 5 12"></polyline>
											</svg>
										</button>
									</th>
								@endif
								@if ($this->showColumn("County"))
									<th wire:click="sortBy('county')">
										<button class="table__header--btn" @if ($orderBy === "county" && $orderAsc === "1") data-symbol="up"
                                                @else data-symbol="down" @endif>
											County
											<svg>
												<line x1="12" y1="5" x2="12" y2="19">
												</line>
												<polyline points="19 12 12 19 5 12"></polyline>
											</svg>
										</button>
									</th>
								@endif
								@if ($this->showColumn("City"))
									<th wire:click="sortBy('city')">
										<button class="table__header--btn" @if ($orderBy === "city" && $orderAsc === "1") data-symbol="up"
                                                @else data-symbol="down" @endif>
											City
											<svg>
												<line x1="12" y1="5" x2="12" y2="19">
												</line>
												<polyline points="19 12 12 19 5 12"></polyline>
											</svg>
										</button>
									</th>
								@endif
								@if ($this->showColumn("Post Code"))
									<th wire:click="sortBy('zipcode')">
										<button class="table__header--btn" @if ($orderBy === "zipcode" && $orderAsc === "1") data-symbol="up"
                                                @else data-symbol="down" @endif>
											Post Code
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
							@if ($addresses->isEmpty())
								<tr>
									<td class="table__empty" colspan="{{ count($columns) + 3 }}">No record
										found.</td>
								</tr>
							@else
								@foreach ($addresses as $index => $address)
									@if ($index < $perPage)
										<tr @if ($loop->last) id="last_record" @endif class="@if ($this->isChecked($address->id)) table__row--selected @endif">
											<td data-title="Check">
												<input type="checkbox" value="{{ $address->id }}" wire:model="checked">
											</td>
											@if ($this->showColumn("Id"))
												<td data-title="ID">
													{{ $address->id }}
												</td>
											@endif
											@if ($this->showColumn("Type"))
												<td data-title="Type">
													{{ $address->type }}
												</td>
											@endif
											@if ($this->showColumn("First Name"))
												<td data-title="First Name">
													@if ($editindex !== $index)
														{{ $address->first_name }}
													@else
														<input type="text" required class="table__edit" wire:model.defer="adress.{{ $index }}.first_name">
													@endif
												</td>
											@endif
											@if ($this->showColumn("Last Name"))
												<td data-title="Last Name">
													@if ($editindex !== $index)
														{{ $address->last_name }}
													@else
														<input type="text" required class="table__edit" wire:model.defer="adress.{{ $index }}.last_name">
													@endif
												</td>
											@endif
											@if ($this->showColumn("Phone"))
												<td data-title="Phone">
													@if ($editindex !== $index)
														{{ $address->phone }}
													@else
														<input type="phone" required class="table__edit" wire:model.defer="adress.{{ $index }}.phone">
													@endif
												</td>
											@endif
											@if ($this->showColumn("Email"))
												<td data-title="Email">
													@if ($editindex !== $index)
														{{ $address->email }}
													@else
														<input type="email" required class="table__edit" wire:model.defer="adress.{{ $index }}.email">
													@endif
												</td>
											@endif
											@if ($this->showColumn("Address"))
												<td data-title="Address">
													@if ($editindex !== $index)
														{{ $address->address1 }}
													@else
														<input type="text" required class="table__edit" wire:model.defer="adress.{{ $index }}.address1">
													@endif

												</td>
											@endif
											@if ($this->showColumn("Optional Address"))
												<td data-title="Optional Address">
													@if ($editindex !== $index)
														{{ $address->address2 }}
													@else
														<input type="text" required class="table__edit" wire:model.defer="adress.{{ $index }}.address2">
													@endif
												</td>
											@endif
											@if ($this->showColumn("Country"))
												<td data-title="Country">
													@if ($editindex !== $index)
														{{ $address->country }}
													@else
														<input type="text" required class="table__edit" wire:model.defer="adress.{{ $index }}.country">
													@endif
												</td>
											@endif
											@if ($this->showColumn("County"))
												<td data-title="County">
													@if ($editindex !== $index)
														{{ $address->county }}
													@else
														<input type="text" required class="table__edit" wire:model.defer="adress.{{ $index }}.county">
													@endif
												</td>
											@endif
											@if ($this->showColumn("City"))
												<td data-title="City">
													@if ($editindex !== $index)
														{{ $address->city }}
													@else
														<input type="text" required class="table__edit" wire:model.defer="adress.{{ $index }}.city">
													@endif
												</td>
											@endif
											@if ($this->showColumn("Post Code"))
												<td data-title="Post Code">
													@if ($editindex !== $index)
														{{ $address->zipcode }}
													@else
														<input type="text" required class="table__edit" wire:model.defer="adress.{{ $index }}.zipcode">
													@endif
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
														{{ $address->created_at }}
													</div>
												</td>
											@endif
											<td data-title="Action">
												<div class="table__buttons">
													@if ($editindex !== $index)
														<button class="edit" wire:click.prevent="edititem({{ $index }}, {{ $address->id }})">
															<svg>
																<path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
																</path>
															</svg>
														</button>
														<button class="delete" wire:click.prevent="confirmItemRemoval({{ $address->id }})">
															<svg>
																<polyline points="3 6 5 6 21 6"></polyline>
																<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
																</path>
															</svg>
														</button>
													@else
														<button class="edit" wire:click.prevent="saveitem({{ $index }} , {{ $address->id }})">
															<svg>
																<polyline points="20 6 9 17 4 12"></polyline>
															</svg>
														</button>
														<button class="save" wire:click.prevent="canceledit()">
															<svg>
																<line x1="18" y1="6" x2="6" y2="18">
																</line>
																<line x1="6" y1="6" x2="18" y2="18">
																</line>
															</svg>
														</button>
													@endif
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
					@if (count($addresses) >= 10)
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
