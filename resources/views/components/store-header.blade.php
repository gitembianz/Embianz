<header>
    <div class="container header">
        <button class="sidebar__open" id="menuOpen">
            <svg>
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
        <a class="logo" href="{{ route('store') }}">Embianz</a>
        <div class="search-box">
            <input type="text" placeholder="Type to search..">
            <div class="search">
                <svg>
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>
        </div>
        <div class="cart">
            <button class="cart__btn" id="cartBtn">
                <svg>
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6">
                    </path>
                </svg>
            </button>
            <ul class="cart__list" id="cartContent">
                <li>
                    <a href="{{ route('cart') }}" class="cart__list--item">
                        <h4 class="cart__list--name">Your cart</h4>
                        <svg>
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                    </a>
                </li>
                <li><a class="cart__list--item" href="#">
                        <img class="cart__list--img" src="images/store/bottle1.png"
                            alt="Sustainable Sips: Reusable Bottles">
                        <span class="cart__list--text">Sustainable Sips: Reusable Bottles</span>
                        <span class="cart__list--much">x2</span>
                        <span class="cart__list--much">20$</span>
                        <button class="cart__list--delete">
                            <svg>
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </a></li>
                <li><a class="cart__list--item" href="#">
                        <img class="cart__list--img" src="images/store/bottle2.png"
                            alt="Sustainable Sips: Reusable Bottles">
                        <span class="cart__list--text">Sustainable Sips: Reusable Bottles</span>
                        <span class="cart__list--much">x5</span>
                        <span class="cart__list--much">40$</span>
                        <button class="cart__list--delete">
                            <svg>
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </a></li>
            </ul>
        </div>
        <a href="#" class="heart">
            <svg>
                <path
                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                </path>
            </svg>
        </a>
    </div>
    <nav class="menu sidebar" id="menu">
        <ul class="menu__list sidebar__content" id="menuContent">
            <button class="sidebar__close" id="menuClose">
                <svg>
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
            <li><a class="menu__item">Home</a></li>
            <li><a class="menu__item">Products</a></li>
            <li><a class="menu__item">Category</a></li>
            <li><a class="menu__item">Category</a></li>
            <li><a class="menu__item">Category</a></li>
        </ul>
    </nav>
</header>
