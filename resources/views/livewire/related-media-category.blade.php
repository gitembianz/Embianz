<div>
	<x-alert />
	<x-loading />

	<div class="accordion">
		<div class="accordion__btn-flex">
			<button class="accordion__btn" wire:click.prevent="@if ($showmedia === false) $set('showmedia', true) @else $set('showmedia', false) @endif">
				{{ __("Media ") }}({{ $category->media()->count() }})
			</button>
			<button class="accordion__upload" wire:click="uploadmedia">
				<div class="item__upload-btn">
					<svg>
						<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
						<polyline points="17 8 12 3 7 8"></polyline>
						<line x1="12" y1="3" x2="12" y2="15"></line>
					</svg>
				</div>
			</button>
		</div>
		<div class="modal" id="uploadmedia">
			<div class="modal-content">
				<h1 class="modal-content-title">
					{{ __("How you will upload?") }}
				</h1>
				<input id="imgUpload" accept="image/*,video/*" type="file" multiple wire:model="medias" style="display: none">
				<label class="modal-content-btn edit" style="color: black" for="imgUpload">
					Local
				</label>
				<input class="modal-content-btn" wire:click="external" type="button" value="External">

				<span class="modal-content-btn delete" onclick="document.getElementById('uploadmedia').style.display='none'">
					<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewbox="0 0 24 24" fill="none" stroke="#BBFCDE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<line x1="18" y1="6" x2="6" y2="18">
						</line>
						<line x1="6" y1="6" x2="18" y2="18">
						</line>
					</svg>
				</span>
			</div>
		</div>
		@if ($showmedia)
			<div class="accordion__content" id="contentDiv">
				@if ($medias)
					<form wire:submit.prevent="save">
						<div class="modal" id="modalelements" style="display: block">
							<div class="modal-content modal--tabel">
								<div class="panel__header">
									<h1 class="panel__header--title">
										{{ __("Add Local Media") }}
									</h1>
									<input type="submit" class="panel__header--input panel__header--checked" value="Save">
								</div>
								<div style="overflow-y: auto; position: relative; background: white">
									<table class="table table-top">
										<thead>
											<tr>
												<th class="wid-3">
													<div class="table__header--btn">Media</div>
												</th>
												<th class="wid-2">
													<div class="table__header--btn">Name</div>
												</th>
												<th class="wid-1">
													<div class="table__header--btn">Size</div>
												</th>
												<th class="wid-1">
													<div class="table__header--btn">Type</div>
												</th>
												<th class="wid-1">
													<div class="table__header--btn">Sequence</div>
												</th>
												<th class="wid-1">
													<div class="table__header--btn float-r">Action</div>
												</th>

											</tr>
										</thead>
									</table>
									<table class="table" style="margin-top: 1.5rem">
										<tbody>
											@foreach ($medias as $index => $media)
												<tr>
													<td class="wid-3">
														@if (str_starts_with($media->getMimeType(), "image"))
															<img loading="lazy" src="data:{{ $media->getMimeType() }};base64,{{ base64_encode($media->get()) }}" width="50px">
														@elseif (str_starts_with($media->getMimeType(), "video"))
															<video width="100px" controls>
																<source src="data:{{ $media->getMimeType() }};base64,{{ base64_encode($media->get()) }}" type="{{ $media->getMimeType() }}">
																<span>{{ __("Your browser does not support the video tag") }}</span>
															</video>
														@endif
													</td>
													<td class="wid-2">{{ $media->getClientOriginalName() }}</td>
													<td class="wid-1">{{ $media->getSize() }} KB</td>
													<td class="wid-1">{{ $media->getClientOriginalExtension() }}</td>
													<td class="wid-1"><input type="number" class="table__edit" placeholder="Media sequence" min="0" required wire:model="file_sequences.{{ $index }}"></td>
													<td class="wid-1">
														<div class="table__buttons">
															<button class="edit" wire:click.prevent="removemedia({{ $index }})">
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
										</tbody>
									</table>
								</div>
								<span class="top-up-modal delete" id="cancelbutton" wire:click="cancel">
									<svg>
										<line x1="18" y1="6" x2="6" y2="18">
										</line>
										<line x1="6" y1="6" x2="18" y2="18">
										</line>
									</svg>
								</span>
								<a href="#cancelbutton" class="top-up-modal" id="topUp">
									<svg>
										<polyline points="18 15 12 9 6 15"></polyline>
									</svg>
								</a>
							</div>
						</div>
					</form>
				@endif
				@if ($externalmedia)
					<form wire:submit.prevent="saveexternal">
						<div class="modal" style="display: block">
							<div class="modal-content modal--tabel">
								<div class="panel__header">
									<h1 class="panel__header--title" id="top1">
										{{ __("Add external media") }}
									</h1>
									<input type="submit" class="panel__header--input panel__header--checked" value="Save">
								</div>
								<div style="overflow-y: auto; position: relative; background: white">
									<table class="table table-top">
										<thead>
											<tr>
												<th class="wid-3">
													<div class="table__header--btn">Name</div>
												</th>

												<th class="wid-1">
													<div class="table__header--btn">Sequence</div>
												</th>
												<th class="wid-4">
													<div class="table__header--btn">Link</div>
												</th>
												<th class="wid-1"></th>
											</tr>
										</thead>
									</table>
									<table class="table" style="margin-top: 1.5rem">
										<tbody>
											@for ($i = 0; $i <= $row; $i++)
												<tr>
													<td class="wid-3">
														<input required type="text" placeholder="Media name" class="table__edit" wire:model="file_name.{{ $i }}">
													</td>
													<td class="wid-1">
														<input required placeholder="Ex: 1,2,3.." required type="number" min="0" class="table__edit" wire:model="file_sequences.{{ $i }}">
													</td>
													<td class="wid-4">
														<input required placeholder="Media external link" type="url" class="table__edit" wire:model="file_link.{{ $i }}">
													</td>
													<td class="wid-1">
														<div class="table__buttons">
															@if ($i == $row)
																<button type="button" class="edit" wire:click="plus">
																	<svg>
																		<line x1="12" y1="5" x2="12" y2="19">
																		</line>
																		<line x1="5" y1="12" x2="19" y2="12">
																		</line>
																	</svg>
																</button>
																<button type="button" class="save" wire:click="clear({{ $i }})">
																	<svg>
																		<line x1="18" y1="6" x2="6" y2="18">
																		</line>
																		<line x1="6" y1="6" x2="18" y2="18">
																		</line>
																	</svg>
																</button>
															@endif
															@if ($i != $row)
																<button type="button" class="save" wire:click="clear({{ $i }})">
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
											@endfor
										</tbody>
									</table>
								</div>
								<span class="top-up-modal delete" wire:click="clearall">
									<svg>
										<line x1="18" y1="6" x2="6" y2="18">
										</line>
										<line x1="6" y1="6" x2="18" y2="18">
										</line>
									</svg>
								</span>
								<a href="#top1" class="top-up-modal" id="topUp">
									<svg>
										<polyline points="18 15 12 9 6 15"></polyline>
									</svg>
								</a>
							</div>
						</div>
					</form>
				@endif
				@if ($category->media->count() > 0)
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
							<a class="panel__header--button" wire:click="$refresh">
								<svg>
									<g id="SVGRepo_bgCarrier" stroke-width="0"></g>
									<g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
									</g>
									<g id="SVGRepo_iconCarrier">
										<path d="M3 3V8M3 8H8M3 8L6 5.29168C7.59227 3.86656 9.69494 3 12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21C7.71683 21 4.13247 18.008 3.22302 14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										</path>
									</g>
								</svg>
							</a>
						</div>
						@if ($selectPage && $selectAll)
							<div class="panel__header--checked">
								<p>
									You selected <strong>{{ count($checked) }}</strong> items.
								</p>
							</div>
						@elseif ($selectPage)
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
								<th><input type="checkbox" wire:model="selectPage"></th>
								@if ($this->showColumn("Id"))
									<th>
										<div class="table__header--btn">ID</div>
									</th>
								@endif
								@if ($this->showColumn("Media"))
									<th>
										<div class="table__header--btn">Media</div>
									</th>
								@endif
								@if ($this->showColumn("Name"))
									<th>
										<div class="table__header--btn">Name</div>
									</th>
								@endif
								@if ($this->showColumn("Sequence"))
									<th>
										<div class="table__header--btn">Sequence</div>
									</th>
								@endif
								@if ($this->showColumn("Created At"))
									<th>
										<div class="table__header--btn">
											Created at
										</div>
									</th>
								@endif
								<th></th>
							</tr>
						</thead>
						<tbody>
							@if ($filteredMedia->isEmpty())
								<tr>
									<td class="table__empty" colspan="{{ count($columns) + 2 }}">
										No record found.
									</td>
								</tr>
							@else
								@foreach ($filteredMedia as $index => $file)
									<tr class="@if ($this->isChecked($file->id)) th_checked @endif">
										<td data-title="Check"><input type="checkbox" value="{{ $file->id }}" wire:model="checked">
										</td>
										@if ($this->showColumn("Id"))
											<td data-title="ID">{{ $file->id }}</td>
										@endif
										@if ($this->showColumn("Media"))
											<td data-title="Media">
												@if (in_array($file->extension, ["jpg", "jpeg", "png", "gif", "svg", "jfif", "webp"]))
													<img loading="lazy" src="/{{ $file->path . $file->name }}" alt="{{ $file->name }}" width="50">
												@else
													A problem with media
												@endif
											</td>
										@endif
										@if ($this->showColumn("Name"))
											<td data-title="Name">
												@if ($editedMediaIndex !== $index)
													<div>{{ $file->name }}</div>
												@else
													<input type="text" class="table__edit" wire:model.defer="filess.{{ $index }}.name" value="{{ $file->name }}">
												@endif
											</td>
										@endif
										@if ($this->showColumn("Sequence"))
											<td data-title="Sequence">
												@if ($editedMediaIndex !== $index)
													<div>{{ $file->sequence }}</div>
												@else
													<input type="number" min="0" required class="table__edit" wire:model.defer="filess.{{ $index }}.sequence">
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
													{{ $file->created_at }}
											</td>
										@endif
										<td data-title="Action">
											<div class="table__buttons">
												@if ($editedMediaIndex !== $index)
													<button class="edit" wire:click.prevent="editMedia({{ $index }}, {{ $file->id }})">
														<svg>
															<path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
															</path>
														</svg>
													</button>
													<button class="delete" wire:click.prevent="confirmItemRemoval({{ $file->id }})">
														<svg>
															<polyline points="3 6 5 6 21 6"></polyline>
															<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
															</path>
														</svg>
													</button>
												@else
													<button class="edit" wire:click.prevent="saveMedia({{ $index }} , {{ $file->id }})">
														<svg>
															<polyline points="20 6 9 17 4 12"></polyline>
														</svg>
													</button>
													<button class="save" wire:click.prevent="cancelMedia()">
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
								@endforeach
							@endif
						</tbody>
					</table>
				@else
					<p class="mt-2">No records related</p>
				@endif
			</div>
		@endif
	</div>
</div>
