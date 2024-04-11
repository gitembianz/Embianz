<div>
	{{-- sesssion html --}}
	<x-alert />
	<x-loading />

	{{-- delete single record --}}
	<div class="modal" id="confirmationmodal">
		<div class="modal-content">
			<h1 class="modal-content-title">
				{{ __("Are you sure to delete this record?") }}
			</h1>
			<input wire:click.prevent="deleteSingleRecord()" class="modal-content-btn submit" type="button" value="Confirm">
			<input class="modal-content-btn delete" type="button" onclick="document.getElementById('confirmationmodal').style.display='none'" value="Cancel">

			<span class="modal-content-btn delete" onclick="document.getElementById('confirmationmodal').style.display='none'">
				<svg>
					<line x1="18" y1="6" x2="6" y2="18"></line>
					<line x1="6" y1="6" x2="18" y2="18"></line>
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

				<svg>
					<line x1="18" y1="6" x2="6" y2="18"></line>
					<line x1="6" y1="6" x2="18" y2="18"></line>
				</svg>
			</span>
		</div>
	</div>
	{{-- Header of the table --}}
	<div class="panel__header">
		<h1 class="panel__header--title">
			{{ __("Categories") }}
		</h1>
		<input class="panel__header--input" type="text" wire:model.debounce.300ms="search" placeholder="Search...">
		<div class="panel__header--bundle">
			<div class="dropdown">
				<button class="dropdown-button" wire:click.prevent="@if ($col === false) $set('col', true) @else $set('col', false) @endif">Columns
					<svg>
						<polyline points="6 9 12 15 18 9"></polyline>
					</svg>
				</button>
				@if ($col)

					<div class="dropdown-list" style="display: flex;">
						@foreach ($columns as $column)
							<div class="dropdown-item">
								<input type="checkbox" wire:ignore wire:model="selectedColumns" value="{{ $column }}" {{ in_array($column, $selectedColumns) ? "checked" : "" }}>
								<label>{{ $column }}</label>
							</div>
						@endforeach
					</div>
				@endif
			</div>
			<div class="dropdown none" @if ($checked) style="display: unset; z-index: 5;" @endif>
				<button wire:click.prevent="@if ($all === false) $set('all', true); $set('col', false) @else $set('all', false) @endif" class="dropdown-button none" @if ($checked) style="display: flex" @endif>With
					Checked({{ count($checked) }})</button>
				@if ($checked)
					@if ($all)
						<div class="dropdown-list" style="display: flex;">
							<button class="dropdown-item delete" type="button" wire:click="confirmItemsRemoval()">
								Delete
							</button>
						</div>
					@endif
				@endif
			</div>
			<a class="panel__header--button" wire:click="$refresh">
				<svg>
					<g id="SVGRepo_bgCarrier" stroke-width="0"></g>
					<g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
					<g id="SVGRepo_iconCarrier">
						<path d="M3 3V8M3 8H8M3 8L6 5.29168C7.59227 3.86656 9.69494 3 12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21C7.71683 21 4.13247 18.008 3.22302 14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
					</g>
				</svg>
			</a>
			<a class="panel__header--button" href="{{ route("newcategory") }}">
				<svg>
					<line x1="12" y1="5" x2="12" y2="19"></line>
					<line x1="5" y1="12" x2="19" y2="12"></line>
				</svg>
			</a>
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
	<div style="overflow-x: auto">
		<table class="table">
			<thead>
				<tr>
					<th><input type="checkbox" wire:model="selectPage"></th>
					@foreach ($selectedColumns as $column)
						@if ($this->showColumn($column))
							<th wire:click="sortBy('{{ $column }}')">
								<button class="table__header--btn" @if ($orderBy === $column && $orderAsc === "1") data-symbol="up"
                @else data-symbol="down" @endif>
									{{ $column }}
									<svg>
										<line x1="12" y1="5" x2="12" y2="19"></line>
										<polyline points="19 12 12 19 5 12"></polyline>
									</svg>
								</button>
							</th>
						@endif
					@endforeach
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if ($categories->isEmpty())
					<tr>
						<td class="table__empty" colspan="{{ count($selectedColumns) }}">No record found.</td>
					</tr>
				@else
					@foreach ($categories as $category)
						<tr @if ($loop->last) id="last_record" @endif class="@if ($this->isChecked($category->id)) table__row--selected @endif">
							<td data-title="Check">
								<input type="checkbox" value="{{ $category->id }}" wire:model="checked">
							</td>
							@foreach ($selectedColumns as $column)
								@if ($column === "name")
									<td data-title="Name"><a href="{{ route("show_category", ['id'=> $category->id ])}}">{{ $category->name }}</a></td>
								@elseif($column === "created_at" || $column === "updated_at")
									<td data-title="{{ $column }}">
										<div class="table__time">
											<svg>
												<circle cx="12" cy="12" r="10"></circle>
												<polyline points="12 6 12 12 16 14"></polyline>
											</svg>
											{{ $category->$column }}
										</div>
									</td>
								@elseif($column === "start_date" || $column === "end_date")
									<td data-title="{{ $column }}">
										<div class="table__time">
											<svg>
												<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
												<line x1="16" y1="2" x2="16" y2="6">
												</line>
												<line x1="8" y1="2" x2="8" y2="6">
												</line>
												<line x1="3" y1="10" x2="21" y2="10">
												</line>
											</svg>
											{{ $category->$column }}
										</div>
									</td>
								@elseif($column === "createdby" || $column === "lastmodifiedby")
									<td data-title="{{ $column }}">
										<div class="table__time">
											<svg>
												<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
												<circle cx="12" cy="7" r="4"></circle>
											</svg>
											{{ $category->$column }}
										</div>
									</td>
								@elseif ($column === "active" || $column === "store_tab" || $column === "has_parrent")
									<td data-title="{{ $column }}">
										@if ($category->$column)
											true
										@else
											false
										@endif
									</td>
								@else
									<td data-title="{{ $column }}">{{ $category->$column }}</td>
								@endif
							@endforeach
							<td data-title="Action">
								<div class="table__buttons">
									<button class="delete" wire:click.prevent="confirmItemRemoval({{ $category->id }})">
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
	</div>
	<x-lazy />
</div>
