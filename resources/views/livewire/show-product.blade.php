<div>
    @if (session()->has('message'))
        <div class="alert__session liveAlert" id="alertevent">
            <span class="alert__session-text">{!! session('message') !!}</span>
            <button class="alert__session-btn" type="button" data-bs-dismiss="alert" aria-hidden="true">
                <svg>
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <script>
            const alertEvent = document.getElementById("alertevent");
            header.style.marginBottom = '4rem';
            alertEvent.style.opacity = '1';

            setTimeout(function() {
                alertEvent.style.opacity = '0';
                setTimeout(function() {
                    alertEvent.remove();
                    header.style.marginBottom = '0';
                }, 500);
            }, 2000);
        </script>
    @endif
    <div class="item__header">
        <h1 class="item__header-title" id="title">Product- {{ $product->name }}</h1>
        <div class="item__header-buttons">
            <a class="item__header-btn" href="{{ route('products') }}">Back</a>
            <a class="item__header-btn" href="{{ route('add_product') }}">New</a>
            @if ($editproduct === null)
                <input class="item__header-btn" type="button" value="Edit" wire:click.prevent="editproduct()">
            @else
                <input class="item__header-btn" type="button" wire:click.prevent="saveproduct()" value="Save">
                <input class="item__header-btn" type="button" wire:click.prevent="cancelproduct()" value="Cancel">
            @endif
            <input wire:click.prevent="confirmProductRemoval({{ $product->id }})" class="item__header-btn delete"
                type="button" value="Delete" name="delete">
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
    <div class="tab">
        <div class="tabs">
            <h3 class="tabs__page active">Details</h3>
            <h3 class="tabs__page">Related</h3>
        </div>
        <div class="tab__list">
            <div class="tabs__content active" id="Details">
                <div class="item__form">
                    @if ($editproduct === null)
                        <div class="item__form-input-close">
                            <div id="category_name">{{ $product->name }}</div>
                            <span>Product Name</span>
                        </div>
                    @else
                        <div class="item__form-input">
                            <input type="text" wire:model.defer="prod.product_name" required>
                            <span> Product Name</span>
                        </div>
                    @endif
                    @if ($editproduct === null)
                        <div class="item__form-input-close">
                            <div id="category_parrent">{{ $product->product_status }}</div>
                            <span>Product Status</span>
                        </div>
                    @else
                        <div class="item__form-input">
                            <select id="select-category" wire:model.defer="prod.product_status">
                                <?php
                                $status = ['active', 'inactive', 'low stock'];
                                ?>
                                <option value="" selected="">Select a status</option>
                                @foreach ($status as $status_name)
                                    <option value="{{ $status_name }}">{{ $status_name }}</option>
                                @endforeach
                            </select>
                            <span>Product Status</span>
                        </div>
                    @endif
                    @if ($editproduct === null)
                        <div class="item__form-input-close">
                            <div id="category_start_date">{{ $product->start_date }}</div>
                            <span for="start_date">Product Start Date</span>
                        </div>
                    @else
                        <div class="item__form-input">
                            <input type="date" id="start_date" wire:model.defer="prod.start_date">
                            <span>Product Start Date</span>
                        </div>
                    @endif
                    @if ($editproduct === null)
                        <div class="item__form-input-close">
                            <div id="category_end_date">{{ $product->end_date }}</div>
                            <span>Product End Date </span>
                        </div>
                    @else
                        <div class="item__form-input">
                            <input type="date" id="end_date" wire:model.defer="prod.end_date">
                            <span>Product End Date</span>
                        </div>
                    @endif
                    @if ($editproduct === null)
                        <div class="item__form-input-close">
                            <div id="category_sequence">{{ $product->quantity }}</div>
                            <span>Product Quantity</span>
                        </div>
                    @else
                        <div class="item__form-input">
                            <input type="number" min="0" wire:model.defer="prod.quantity" required>
                            <span>Product Quantity</span>
                        </div>
                    @endif
                    @if ($editproduct === null)
                        <div class="item__form-input-close item__form-long">
                            <div id="category_short_description">{{ $product->short_description }}</div>
                            <span>Product Short Description</span>
                        </div>
                    @else
                        <div class="item__form-input item__form-long">
                            <input type="text" wire:model.defer="prod.short_description" required>
                            <span>Product Short Description</span>
                        </div>
                    @endif
                    @if ($editproduct === null)
                        <div class="item__form-input-close item__form-long">
                            <div id="category_long_description">{{ $product->long_description }}</div>
                            <span>Product Long Description</span>
                        </div>
                    @else
                        <div class="item__form-input item__form-long">
                            <textarea wire:model.defer="prod.long_description" required></textarea>
                            <span>Product Long Description</span>
                        </div>
                    @endif
                    @if ($editproduct === null)
                        <div class="item__form-input-close item__form-long">
                            <div id="seo_title">{{ $product->seo_title }}</div>
                            <span>SEO Title</span>
                        </div>
                    @else
                        <div class="item__form-input item__form-long">
                            <input type="text" wire:model.defer="prod.seo_title" required>
                            <span>SEO Title</span>
                        </div>
                    @endif
                    <div class="item__form-close">
                        <div>{{ $product->created_at }}</div>
                        <span>Create date / time</span>
                    </div>
                    <div class="item__form-close">
                        <div>{{ $product->created_by }}</div>
                        <span>Create by</span>
                    </div>
                    <div class="item__form-close">
                        <div>{{ $product->updated_at }}</div>
                        <span>Updated date / time</span>
                    </div>
                    <div class="item__form-close">
                        <div>{{ $product->last_modified_by }}</div>
                        <span>Last modified by</span>
                    </div>
                    @if ($editproduct != null)
                        <input class="item__form-btn item__form-long" wire:click.prevent="saveproduct()"
                            type="button" value="Save">
                    @endif
                </div>
            </div>
            <div class="tabs__content display-f g-1">
                <livewire:related-media-product productId="{{ $product->id }}" />
                  <livewire:related-category-product productId="{{ $product->id }}" />
            </div>
        </div>
    </div>
</div>
