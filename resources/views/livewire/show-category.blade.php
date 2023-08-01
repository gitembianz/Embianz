<div>
    <x-alert />
    <div wire:loading.delay>
        <div class="modal" style="display:flex;">
            <div class="loader"></div>
        </div>
    </div>
    <div class="item__header">
        <h1 class="item__header-title" id="title">Category - {{ $category->name }}</h1>
        <div class="item__header-buttons">
            <a class="item__header-btn" href="{{ route('category') }}">All Categories</a>
            <a class="item__header-btn" href="{{ route('newcategory') }}">New</a>
            @if ($editcategory === null)
                <input class="item__header-btn" type="button" value="Edit" wire:click.prevent="editcategory()">
            @else
                <input class="item__header-btn" type="button" wire:click.prevent="savecategory()" value="Save">
                <input class="item__header-btn" type="button" wire:click.prevent="cancelcategory()" value="Cancel">
            @endif
            <input class="item__header-btn delete" type="button" value="Delete" name="delete"
                wire:click.prevent="confirmItemRemoval({{ $category->id }})">
        </div>
    </div>

    <div class="tab">
        <div class="tabs">
            <h3 class="tabs__page active">Details</h3>
            <h3 class="tabs__page">Related</h3>
        </div>
        <div class="tab__list">
            <div class="tabs__content active" id="Details">
                <div class="tab__list--form">
                    @if ($editcategory === null)
                        <div class="item__form-input-close">
                            <div>{{ $category->name }}</div>
                            <span>Category Name</span>
                        </div>
                    @else
                        <div class="item__form-input">
                            <input type="text" wire:model.defer="cat.name">
                            <span> Name</span>
                        </div>
                    @endif
                    <div class="tabs__form-input__bundle">
                        @if ($editcategory === null)
                            <div class="item__form-input-close">
                                <div>
                                    @if ($category->active)
                                        {{ _('Active') }}
                                    @else
                                        {{ _('Inactive') }}
                                    @endif
                                </div>
                                <span>Is Active</span>
                            </div>
                            <div class="item__form-input-close">
                                <div>
                                    @if ($category->store_tab)
                                        {{ _('Visible') }}
                                    @else
                                        {{ _('None') }}
                                    @endif
                                </div>
                                <span>Visible on Store Tab</span>
                            </div>
                            <div class="item__form-input-close">
                                <div>{{ $category->sequence }}</div>
                                <span>Category Sequence</span>
                            </div>
                        @else
                            <div class=" display-f align-center jus-s">
                                <input type="checkbox" wire:model.defer="cat.active">
                                <span class="ml-1"> is active</span>
                            </div>
                            <div class="display-f align-center jus-s">
                                <input type="checkbox" wire:model.defer="cat.visible">
                                <span class="ml-1">Displayed on Store Tab?</span>
                            </div>
                            <div class="item__form-input">
                                <input type="number" min="0" wire:model.defer="cat.sequence">
                                <span>Sequence</span>
                            </div>
                        @endif
                    </div>
                    @if ($editcategory === null)
                        <div class="item__form-input-close">
                            <div>{{ $category->start_date }}</div>
                            <span for="start_date">Category Start Date</span>
                        </div>
                    @else
                        <div class="item__form-input">
                            <input type="date" wire:model.defer="cat.start_date">
                            <span>Start Date</span>
                        </div>
                    @endif
                    @if ($editcategory === null)
                        <div class="item__form-input-close">
                            <div>{{ $category->end_date }}</div>
                            <span>Category End Date </span>
                        </div>
                    @else
                        <div class="item__form-input">
                            <input type="date" wire:model.defer="cat.end_date">
                            <span>End Date</span>
                        </div>
                    @endif

                    @if ($editcategory === null)
                        <div class="item__form-input-close item__form-long">
                            <div>{{ $category->short_description }}</div>
                            <span>Category Short Description</span>
                        </div>
                    @else
                        <div class="item__form-input item__form-long">
                            <input type="text" wire:model.defer="cat.short_description">
                            <span>Short Description</span>
                        </div>
                    @endif
                    @if ($editcategory === null)
                        <div class="item__form-input-close item__form-long">
                            <div>{{ $category->long_description }}</div>
                            <span>Category Long Description</span>
                        </div>
                    @else
                        <div class="item__form-input item__form-long">
                            <textarea wire:model.defer="cat.long_description"></textarea>
                            <span>Long Description</span>
                        </div>
                    @endif
                    @if ($editcategory === null)
                        <div class="item__form-input-close item__form-long">
                            <div>{{ $category->seo_title }}</div>
                            <span>SEO Title</span>
                        </div>
                    @else
                        <div class="item__form-input item__form-long">
                            <input type="text" wire:model.defer="cat.seo_title">
                            <span>SEO Title</span>
                        </div>
                    @endif

                    <div class="item__form-close">
                        <div>{{ $category->created_at }}</div>
                        <span>Create date / time</span>
                    </div>
                    <div class="item__form-close">
                        <div>{{ $category->createdby }}</div>
                        <span>Create by</span>
                    </div>
                    <div class="item__form-close">
                        <div>{{ $category->updated_at }}</div>
                        <span>Updated date / time</span>
                    </div>
                    <div class="item__form-close">
                        <div>{{ $category->lastmodifiedby }}</div>
                        <span>Last modified by</span>
                    </div>
                    @if ($editcategory != null)
                        <input class="item__form-btn item__form-long" wire:click.prevent="savecategory()" type="button"
                            value="Save">
                    @endif
                </div>
            </div>
            <div class="tabs__content display-f g-1">
                <livewire:related-media-category categoryId="{{ $category->id }}" />

                <livewire:related-product-category categoryId="{{ $category->id }}" />

                <livewire:related-subcategory categoryId="{{ $category->id }}" />
            </div>

        </div>
        <div class="modal" id="confirmationmodalitem">
            <div class="modal-content">
                <h1 class="modal-content-title">
                    {{ __('Are you sure to delete this record?') }}
                </h1>
                <input wire:click.prevent="deleteSingleRecord()" class="modal-content-btn submit" type="button"
                    value="Confirm">
                <input class="modal-content-btn delete" type="button"
                    onclick="document.getElementById('confirmationmodalitem').style.display='none'" value="Cancel">

                <span class="modal-content-btn delete"
                    onclick="document.getElementById('confirmationmodalitem').style.display='none'">
                    <svg>
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </span>
            </div>
        </div>
    </div>
</div>
