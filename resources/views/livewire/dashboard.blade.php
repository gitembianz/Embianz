<section class="content" style="overflow-y: auto;!important; max-height: 100%;">

    {{-- Elements count --}}
    <nav class="nav--home">
        <a href="{{ route('all_products') }}" class="button button--fill button--flexed button--secondary">
            <svg>
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M3 21l18 0" />
                <path d="M3 7v1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1h-18l2 -4h14l2 4" />
                <path d="M5 21l0 -10.15" />
                <path d="M19 21l0 -10.15" />
                <path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4" />
            </svg>
            Products ({{ $activeProductsCount }})
        </a>
        <a href="{{ route('category') }}" class="button button--fill button--flexed button--secondary">
            <svg>
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M14 4h6v6h-6z" />
                <path d="M4 14h6v6h-6z" />
                <path d="M17 17m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                <path d="M7 7m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
            </svg>
            Categories ({{ $activeCategoriesCount }})
        </a>
        <a href="{{ route('carts') }}" class="button button--fill button--flexed button--secondary">
            <svg>
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                <path d="M17 17h-11v-14h-2" />
                <path d="M6 5l14 1l-1 7h-13" />
            </svg>
            Carts ({{ $cartCount }})
        </a>
        <a href="{{ route('orders') }}" class="button button--fill button--flexed button--secondary">
            <svg>
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                <path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                <path d="M5 17h-2v-4m-1 -8h11v12m-4 0h6m4 0h2v-6h-8m0 -5h5l3 5" />
                <path d="M3 9l4 0" />
            </svg>
            Orders ({{ $orderCount }})
        </a>
    </nav>
    <script>
        let userInteracted = false;

        function setUserInteracted() {
            userInteracted = true;
        }

        document.addEventListener('click', setUserInteracted);
        document.addEventListener('keydown', setUserInteracted);
        document.addEventListener('touchstart', setUserInteracted);

        document.addEventListener('livewire:load', function() {
            userInteracted = true;
            Livewire.on('cartCountUpdated', function(newCartCount) {
                if (userInteracted) {
                    playSoundcart();
                }
            });
            Livewire.on('orderCountUpdated', function(newOrderCount) {
                if (userInteracted) {
                    playSoundorder();
                }
            });
        });

        var audio = new Audio('/sounds/cart.mp3');

        function playSoundcart() {
            audio.play().catch(function(error) {
                console.log('Error playing sound:', error);
            });
        }

        function playSoundorder() {
            let audio = new Audio('/sounds/order.mp3');
            audio.play().catch(function(error) {
                console.log('Error playing sound:', error);
            });
        }
    </script>


    {{-- pages --}}
    <nav class="nav--tabs" style="justify-content: flex-start!important">
        <button class="button button--primary button--long button--active" data-tab="main">
            Main
        </button>
        <button class="button button--primary button--long" data-tab="seo">
            SEO
        </button>
    </nav>

    <div class="tabs__content related__view active" data-tab-content="main">
        <p>main</p>
    </div>

    <div class="tabs__content related__view" data-tab-content="seo">

        {{-- Title too long >= 65 caractere  --}}
        @livewire(
            'list-seo',
            [
                'title' => 'Title too long',
                'table' => 'products,categories',
                'column' => 'name',
                'operator' => '>=',
                'caracters_count' => '65',
            ],
            key('seo-dashboard-1')
        )

        {{-- Title too short >= 50 caractere  --}}
        @livewire(
            'list-seo',
            [
                'title' => 'Title too short',
                'table' => 'products,categories',
                'column' => 'name',
                'operator' => '<=',
                'caracters_count' => '50',
            ],
            key('seo-dashboard-2')
        )
        {{-- Meta description too long >= 160 caractere  --}}
        @livewire(
            'list-seo',
            [
                'title' => 'Meta description too long',
                'table' => 'products,categories',
                'column' => 'meta_description',
                'operator' => '>=',
                'caracters_count' => '160',
            ],
            key('seo-dashboard-3')
        )

        {{-- Meta description too short <= 140 caractere  --}}
        @livewire(
            'list-seo',
            [
                'title' => 'Meta description too short',
                'table' => 'products,categories',
                'column' => 'meta_description',
                'operator' => '<=',
                'caracters_count' => '140',
            ],
            key('seo-dashboard-4')
        )

        {{-- SEO title too long >= 60 caractere  --}}
        @livewire(
            'list-seo',
            [
                'title' => 'SEO title too long',
                'table' => 'products,categories',
                'column' => 'seo_title',
                'operator' => '>=',
                'caracters_count' => '60',
            ],
            key('seo-dashboard-5')
        )

        {{-- SEO title too short <= 50 caractere  --}}
        @livewire(
            'list-seo',
            [
                'title' => 'SEO title too short',
                'table' => 'products,categories',
                'column' => 'seo_title',
                'operator' => '<=',
                'caracters_count' => '50',
            ],
            key('seo-dashboard-6')
        )

        {{-- Long description too long >= 600 caractere  --}}
        @livewire(
            'list-seo',
            [
                'title' => 'Long description too long',
                'table' => 'products,categories',
                'column' => 'long_description',
                'operator' => '>=',
                'caracters_count' => '600',
            ],
            key('seo-dashboard-7')
        )

        {{-- Long description too short <= 300 caractere  --}}
        @livewire(
            'list-seo',
            [
                'title' => 'Long description too short',
                'table' => 'products,categories',
                'column' => 'long_description',
                'operator' => '<=',
                'caracters_count' => '300',
            ],
            key('seo-dashboard-8')
        )

        {{-- Product frindly URL too long >= 60 caractere  --}}
        @livewire(
            'list-seo',
            [
                'title' => 'Product friendly URL too long',
                'table' => 'products',
                'column' => 'seo_id',
                'operator' => '>=',
                'caracters_count' => '60',
            ],
            key('seo-dashboard-9')
        )

        {{-- Product frindly URL too short <= 30 caractere  --}}
        @livewire(
            'list-seo',
            [
                'title' => 'Product friendly URL too short',
                'table' => 'products',
                'column' => 'seo_id',
                'operator' => '<=',
                'caracters_count' => '30',
            ],
            key('seo-dashboard-10')
        )

        {{-- relations --}}
        {{-- Product image few < 3 --}}
        @livewire(
            'list-relation',
            [
                'title' => 'Product image few',
                'model' => 'product',
                'relation' => 'media',
                'operator' => '<',
                'relation_count' => '3',
            ],
            key('relation-dashboard-1')
        )

        {{-- Product image many > 8 --}}
        @livewire(
            'list-relation',
            [
                'title' => 'Product image many',
                'model' => 'product',
                'relation' => 'media',
                'operator' => '>',
                'relation_count' => '8',
            ],
            key('relation-dashboard-2')
        )

        {{-- Product reviews few <3 --}}
        @livewire(
            'list-relation',
            [
                'title' => 'Product reviews few',
                'model' => 'product',
                'relation' => 'reviews',
                'operator' => '<',
                'relation_count' => '3',
            ],
            key('relation-dashboard-3')
        )

    </div>


    {{-- Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('[data-tab]');
            const contents = document.querySelectorAll('[data-tab-content]');

            buttons.forEach(button => {
                button.addEventListener('click', () => {
                    const tabName = button.dataset.tab;

                    buttons.forEach(btn => btn.classList.remove('button--active'));
                    contents.forEach(content => content.classList.remove('active'));

                    button.classList.add('button--active');
                    document.querySelector(`[data-tab-content="${tabName}"]`)?.classList.add(
                        'active');
                });
            });
        });
    </script>

</section>
