<div>
	<x-alert />
	<x-loading />
	<div class="accordion">
		<div class="accordion__btn-flex">
			<button class="accordion__btn" wire:click.prevent="@if ($showrelatedcat === false) $set('showrelatedcat', true) @else $set('showrelatedcat', false) @endif">
				{{ __("Categories ") }}({{ $product->product_categories->count() }})
			</button>
			<button class="accordion__upload" wire:click="toggleTable">
				<svg>
					<line x1="12" y1="5" x2="12" y2="19"></line>
					<line x1="5" y1="12" x2="19" y2="12"></line>
				</svg>
			</button>
		</div>

		@if ($showrelatedcat)
			<div class="accordion__content">
				{{-- html for adding products --}}
				@if ($showTable != false)
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
								<h1 class="panel__header--title">
									{{ __("Add related category") }}
								</h1>
								<input class="panel__header--input" type="text" wire:model="searchadd" placeholder="Search...">
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
														<label>
															{{ $column }}
														</label>
													</div>
												@endforeach
											</div>
										@endif
									</div>
									<button @if ($checkedadd) style="display: unset; z-index: 5;" @else style="display: none;" @endif class="panel__header--button panel__header--checked" wire:click.prevent="confirmItemsLinkmultiple()" @if ($checkedadd) style="display: flex" @endif> Add
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
									<div class="panel__header--checked" wire:click="selectAlladd">
										<p>
											You selected {{ count($checkedadd) }} items, select all?
										</p>
									</div>
								@endif
							</div>
							{{-- Table --}}
							<div style="overflow-y: auto; position: relative; background: white;">
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
											<th>
											</th>
										</tr>
									</thead>
								</table>
								<table class="table" style="margin-top: 2rem">
									<tbody>
										@if ($cats->count() > 0)
											@foreach ($cats as $cat)
												<tr @if ($loop->last) id="last_record" @endif class="@if ($this->isCheckedadd($cat->id)) table__row--selected @endif">
													<td data-title="Check">
														<input type="checkbox" value="{{ $cat->id }}" wire:model="checkedadd">
													</td>
													@if ($this->showColumnadd("Id"))
														<td data-title="ID">
															{{ $cat->id }}
														</td>
													@endif
													@if ($this->showColumnadd("Name"))
														<td data-title="Name">
															<a href="{{ route("show_category", ['id'=> $cat->id ])}}">
																{{ $cat->name }}
															</a>
														</td>
													@endif
													@if ($this->showColumnadd("Short Description"))
														<td class="table__description" data-title="Description">
															{{ $cat->short_description }}
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
																{{ $cat->created_at }}
															</div>
														</td>
													@endif
													<td data-title="Action">
														<div class="table__buttons">
															<button class="edit" wire:click.prevent="confirmItemlink({{ $cat->id }})">
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
												<td class="table__empty" colspan="{{ count($columns) + 3 }}">No
													record
													found.
												</td>
											</tr>
										@endif
									</tbody>
								</table>
							</div>
							<span class="top-up-modal delete" wire:click="cancel">
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
						<x-lazy />
					</div>
				@endif
				{{-- end html for adding products --}}
				@if ($product->product_categories()->count() > 0)
					{{-- delete single record --}}
					<div class="modal" id="confirmationmodalsingle">
						<div class="modal-content">
							<h1 class="modal-content-title">
								{{ __("Are you sure to delete this record?") }}
							</h1>
							<input wire:click.prevent="deleteSingleRecord()" class="modal-content-btn submit" type="button" value="Confirm" id="confirmLoad">
							<input class="modal-content-btn delete" type="button" onclick="document.getElementById('confirmationmodalsingle').style.display='none'" value="Cancel">

							<span class="modal-content-btn delete" onclick="document.getElementById('confirmationmodalsingle').style.display='none'">
								<svg>
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
							<input wire:click.prevent="deleteRecords()" class="modal-content-btn submit" type="button" value="Confirm" id="confirmLoad">
							<input class="modal-content-btn delete" type="button" onclick="document.getElementById('confirmationmodalmultiple').style.display='none'" value="Cancel">

							<span class="modal-content-btn delete" onclick="document.getElementById('confirmationmodalmultiple').style.display='none'">

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
								<button wire:click.prevent="@if ($all === false) $set('all', true); $set('col', false) @else $set('all', false) @endif" class="dropdown-button none" @if ($checked) style="display: flex" @endif>With
									Checked({{ count($checked) }})
								</button>
								@if ($checked && $all)
									<div class="dropdown-list" style="display: flex;">
										<button class="dropdown-item delete" type="button" wire:click="confirmItemsRemovalmultiple()">
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
								<th>
								</th>
							</tr>
						</thead>

						<tbody>
							@if ($relatedcats->isEmpty())
								<tr>
									<td class="table__empty" colspan="{{ count($columns) + 3 }}">No record
										found.
									</td>
								</tr>
							@else
								@foreach ($relatedcats as $index => $categori)
									@if ($index >= $perPage)
										<?php
										break;
										?>
									@endif
									<tr @if ($loop->last) id="last_record" @endif class="@if ($this->isChecked($categori->id)) table__row--selected @endif">
										<td data-title="Check">
											<input type="checkbox" value="{{ $categori->id }}" wire:model="checked">
										</td>

										@if ($this->showColumn("Id"))
											<td data-title="ID">
												{{ $categori->category->id }}
											</td>
										@endif

										@if ($this->showColumn("Name"))
											<td data-title="Name">
												<a href="{{ route("show_category", ['id'=> $categori->category->id ])}}">{{ $categori->category->name }}</a>
											</td>
										@endif

										@if ($this->showColumn("Short Description"))
											<td class="table__description" data-title="Description">
												{{ $categori->category->short_description }}
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
													{{ $categori->category->created_at }}
												</div>
											</td>
										@endif

										<td data-title="Action">
											<div class="table__buttons">
												<button class="delete" wire:click.prevent="ItemRemoval({{ $categori->id }})">
													<svg>
														<polyline points="3 6 5 6 21 6"></polyline>
														<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
														</path>
													</svg>
												</button>
											</div>
										</td>
									</tr>
								@endforeach
							@endif
						</tbody>
					</table>
					@if ($perPage <= count($relatedcats))
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
