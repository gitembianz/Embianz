<div>
	<x-alert />
	<x-loading />
	<div class="accordion">
		<div class="accordion__btn-flex">
			<button class="accordion__btn" wire:click.prevent="@if ($showrelatedsub === false) $set('showrelatedsub', true) @else $set('showrelatedsub', false) @endif">
				{{ __("Subcategories ") }}({{ $category->subcategory()->count() }})
			</button>
			<button class="accordion__upload" wire:click="toggleTable">
				<svg>
					<line x1="12" y1="5" x2="12" y2="19"></line>
					<line x1="5" y1="12" x2="19" y2="12"></line>
				</svg>
			</button>
		</div>
		@if ($showrelatedsub)
			<div class="accordion__content">
				@if ($showTable === false)
				@else
					<div class="modal" id="modalelements" style="display: block">
						<div class="modal-content modal--tabel">
							<div class="modal" id="confirmationmodallink">
								<div class="modal-content">
									<h1 class="modal-content-title">
										{{ __("Are you sure to relate this record?") }}
									</h1>
									<input wire:click.prevent="linkSingleRecord()" class="modal-content-btn submit" type="button" value="Confirm" id="confirmLoad">
									<input class="modal-content-btn delete" type="button" onclick="document.getElementById('confirmationmodallink').style.display='none'" value="Cancel">

									<span class="modal-content-btn delete" onclick="document.getElementById('confirmationmodallink').style.display='none'">
										<svg>
											<line x1="18" y1="6" x2="6" y2="18">
											</line>
											<line x1="6" y1="6" x2="18" y2="18">
											</line>
										</svg>
									</span>
								</div>
							</div>
							<div class="modal" id="confirmationmodallinkmultiple">
								<div class="modal-content">
									<h1 class="modal-content-title">
										{{ __("Are you sure to link those records?") }}
									</h1>
									<input wire:click.prevent="linkRecords()" class="modal-content-btn submit" type="button" value="Confirm" id="confirmLoad">
									<input class="modal-content-btn delete" type="button" onclick="document.getElementById('confirmationmodallinkmultiple').style.display='none'" value="Cancel">

									<span class="modal-content-btn delete" onclick="document.getElementById('confirmationmodallinkmultiple').style.display='none'">

										<svg>
											<line x1="18" y1="6" x2="6" y2="18">
											</line>
											<line x1="6" y1="6" x2="18" y2="18">
											</line>
										</svg>
									</span>
								</div>
							</div>
							{{-- Header of the table --}}
							<div class="panel__header">
								<h1 class="panel__header--title" id="top">
									{{ __("Add related Subcategories") }}
								</h1>
								<input class="panel__header--input" type="text" wire:model.debounce.300ms="searchadd" placeholder="Searchs...">
								<div class="panel__header--bundle">
									<div class="dropdown">
										<button wire:click.prevent="@if ($coladd === false) $set('coladd', true) @else $set('coladd', false) @endif" class="dropdown-button">
											Columns
										</button>
										@if ($coladd)
											<div class="dropdown-list" style="display: flex;">
												@foreach ($columnsadd as $column)
													<div class="dropdown-item">
														<input type="checkbox" wire:model="selectedColumnsadd" value="{{ $column }}" {{ in_array($column, $selectedColumnsadd) ? "checked" : "" }}>
														<label>{{ $column }}</label>
													</div>
												@endforeach
											</div>
										@endif
									</div>
									<button @if ($checkedadd) style="display: unset; z-index: 5;" @else style="display: none;" @endif class="panel__header--button panel__header--checked" wire:click.prevent="confirmLinkmultiple()" @if ($checkedadd) style="display: flex" @endif> Add
										{{ count($checkedadd) }} records
									</button>
								</div>
								@if ($selectPageadd && $selectAlladd)
									<div class="panel__header--checked">
										<p>
											You selected <strong>{{ count($checkedadd) }}</strong> items.
										</p>
									</div>
								@elseif($selectPageadd)
									<div class="panel__header--checked">
										<a href="#" wire:click="selectAlladd">
											<p>
												You selected <strong>{{ count($checkedadd) }}</strong> items,
												Do
												you
												want
												to Select All?
											</p>
										</a>
									</div>
								@endif
							</div>
							{{-- Table --}}
							<div style="overflow-y: auto;position: relative;background: white;">
								<table class="table table-top">
									<thead>
										<tr>
											<th>
												<input type="checkbox" wire:model="selectPageadd">
											</th>
											@if ($this->showColumnadd("Id"))
												<th wire:click="sortByadd('id')">
													<button class="table__header--btn" @if ($orderByadd === "id" && $orderAscadd === "1") data-symbol="up"
                                                        @else data-symbol="down" @endif>
														ID
														<svg>
															<line x1="12" y1="5" x2="12" y2="19"></line>
															<polyline points="19 12 12 19 5 12"></polyline>
														</svg>
													</button>
												</th>
											@endif
											@if ($this->showColumnadd("Name"))
												<th wire:click="sortByadd('name')">
													<button class="table__header--btn" @if ($orderByadd === "name" && $orderAscadd === "1") data-symbol="up"
                                                        @else data-symbol="down" @endif>
														Name
														<svg>
															<line x1="12" y1="5" x2="12" y2="19"></line>
															<polyline points="19 12 12 19 5 12"></polyline>
														</svg>
													</button>
												</th>
											@endif
											@if ($this->showColumnadd("Short Description"))
												<th wire:click="sortByadd('short_description')">
													<button class="table__header--btn" @if ($orderByadd === "short_description" && $orderAscadd === "1") data-symbol="up"
                                                        @else data-symbol="down" @endif>
														Description
														<svg>
															<line x1="12" y1="5" x2="12" y2="19"></line>
															<polyline points="19 12 12 19 5 12"></polyline>
														</svg>
													</button>
												</th>
											@endif
											@if ($this->showColumnadd("Created At"))
												<th wire:click="sortByadd('created_at')">
													<button class="table__header--btn" @if ($orderByadd === "created_at" && $orderAscadd === "1") data-symbol="up"
                                                        @else data-symbol="down" @endif>
														Created at
														<svg>
															<line x1="12" y1="5" x2="12" y2="19"></line>
															<polyline points="19 12 12 19 5 12"></polyline>
														</svg>
													</button>
												</th>
											@endif
											<th></th>
										</tr>
									</thead>
								</table>
								<table class="table" style="margin-top: 2rem">
									<tbody>
										@if ($categories->count() > 0)
											@foreach ($categories as $category)
												<tr @if ($loop->last) id="last_record" @endif class="@if ($this->isCheckedadd($category->id)) table__row--selected @endif">
													<td data-title="Check">
														<input type="checkbox" value="{{ $category->id }}" wire:model="checkedadd">
													</td>
													@if ($this->showColumnadd("Id"))
														<td data-title="ID">
															{{ $category->id }}
														</td>
													@endif
													@if ($this->showColumnadd("Name"))
														<td data-title="Name">
															<a href="{{ route("show_category", ['id'=> $category->id ])}}">
																{{ $category->name }}
															</a>
														</td>
													@endif
													@if ($this->showColumnadd("Short Description"))
														<td class="table__description" data-title="Description">
															{{ $category->short_description }}
														</td>
													@endif
													@if ($this->showColumnadd("Created At"))
														<td data-title="Created At">
															<div class="table__time">
																<svg>
																	<circle cx="12" cy="12" r="10">
																	</circle>
																	<polyline points="12 6 12 12 16 14"></polyline>
																</svg>
																{{ $category->created_at }}
															</div>
														</td>
													@endif
													<td data-title="Action">
														<div class="table__buttons">
															<button class="edit" wire:click.prevent="confirmitemlink({{ $category->id }})">
																<svg>
																	<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71">
																	</path>
																	<path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71">
																	</path>
																</svg>
															</button>
														</div>
													</td>
												</tr>
											@endforeach
										@else
											<tr>
												<td class="table__empty" colspan="{{ count($columns) + 3 }}">
													No record found.
												</td>
											</tr>
										@endif
									</tbody>
								</table>
							</div>
							<span wire:click="cancel" class="top-up-modal delete">
								<svg>
									<line x1="18" y1="6" x2="6" y2="18">
									</line>
									<line x1="6" y1="6" x2="18" y2="18">
									</line>
								</svg>
							</span>
							<a href="#top" class="top-up-modal" id="topUp">
								<svg>
									<polyline points="18 15 12 9 6 15"></polyline>
								</svg>
							</a>
						</div>
					</div>
					<x-lazy />
				@endif
				@if ($category->subcategory()->count() > 0)
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
								@if ($this->showColumn("Short Description"))
									<th wire:click="sortBy('short_description')">
										<button class="table__header--btn" @if ($orderBy === "short_description" && $orderAsc === "1") data-symbol="up"
                                                @else data-symbol="down" @endif>
											Description
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
							@if ($relatedsubcats->isEmpty())
								<tr>
									<td class="table__empty" colspan="{{ count($columns) + 3 }}">No record
										found.</td>
								</tr>
							@else
								@foreach ($relatedsubcats as $index => $subcat)
									@if ($index < $perPage)
										<tr @if ($loop->last) id="last_record" @endif class="@if ($this->isChecked($subcat->id)) table__row--selected @endif">
											<td data-title="Check">
												<input type="checkbox" value="{{ $subcat->id }}" wire:model="checked">
											</td>
											@if ($this->showColumn("Id"))
												<td data-title="ID">
													{{ $subcat->id }}
												</td>
											@endif
											@if ($this->showColumn("Name"))
												<td data-title="Name">
													<a href="{{ route("show_category", ['id'=> $subcat->category_id ])}}">
														{{ $subcat->category->name }}
													</a>
												</td>
											@endif
											@if ($this->showColumn("Short Description"))
												<td class="table__description" data-title="Description">
													{{ $subcat->category->short_description }}
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
														{{ $subcat->created_at }}
													</div>
												</td>
											@endif
											<td data-title="Action">
												<div class="table__buttons">
													<button class="edit" wire:click.prevent="confirmItemRemoval({{ $subcat->id }})">
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
					@if (count($relatedsubcats) >= 10)
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
