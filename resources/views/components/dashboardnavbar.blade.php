<header class="header">
    <!-- Sidebar Button for mobile -->
    <button class="sidebar__btn">
        <img src="/images/dashboard/navbar/mini-logo.png" alt="logo">
    </button>

    <!-- Logo -->
    <a href="{{ route('redirect') }}" class="header__logo">
        <img src="/images/dashboard/navbar/logo.png" alt="logo">
    </a>

    <!-- Header Buttons on mobile -->
    <div class="header__buttons">
        <!-- Search button on mobile -->
        <button class="header__search-btn" id="openSearch">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewbox="0 0 24 24" fill="none"
                stroke="#BBFCDE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </button>
        <!-- Profile button on mobile -->
        <button class="header__profile-btn" id="openProfile">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewbox="0 0 24 24" fill="none"
                stroke="#BBFCDE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
        </button>

        <!-- Header Search  -->
        <div class="header__search">
            <!-- Search Close Button -->
            <button class="header__search-btn" id="closeSearch" type="reset">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewbox="0 0 24 24" fill="none"
                    stroke="#BBFCDE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
            <!-- Searchbar input -->
            <input type="text" class="header__search-input">
            <!-- Search submit button -->
            <button class="header__search-btn" type="submit" id="toggleSearch">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewbox="0 0 24 24" fill="none"
                    stroke="#BBFCDE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </button>
        </div>
        <!-- Header Notification  -->
        <div class="header__notify">
            <div class="header__notify-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewox="0 0 24 24" fill="none"
                    stroke="#BBFCDE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                <span class="alert-count">3</span>
            </div>
            <ul class="header__notify-list">
                <h3 class="header__notify-title">Notification</h3>
                <li>
                    <div class="header__notify-item alert-circle">
                        <span class="header__notify-message">Lorem ipsum dolor sit amet.</span>
                        <time class="header__notify-time">22.05.23<br>23:00</time>
                    </div>
                </li>
                <li>
                    <div class="header__notify-item alert-circle">
                        <span class="header__notify-message">Lorem ipsum dolor sit amet.</span>
                        <time class="header__notify-time">22.05.23<br>23:00</time>
                    </div>
                </li>
                <li>
                    <div class="header__notify-item alert-circle">
                        <span class="header__notify-message">Lorem ipsum dolor sit amet.</span>
                        <time class="header__notify-time">22.05.23<br>23:00</time>
                    </div>
                </li>
                <li>
                    <div class="header__notify-item">
                        <span class="header__notify-message">Lorem ipsum dolor sit amet.</span>
                        <time class="header__notify-time">22.05.23<br>23:00</time>
                    </div>
                </li>
                <li>
                    <div class="header__notify-item">
                        <span class="header__notify-message">Lorem ipsum dolor sit amet.</span>
                        <time class="header__notify-time">22.05.23<br>23:00</time>
                    </div>
                </li>
            </ul>
        </div>
        <!-- Header Profile -->
        <div class="header__profile">
            <!-- Profile Top part -->
            <div class="header__profile-top">
                <button class="header__profile-btn" id="closeProfile">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewbox="0 0 24 24"
                        fill="none" stroke="#BBFCDE" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </button>
                <div class="header__profile-name">
                    {{ $user }}
                </div>
            </div>
            <!-- Profile Bottom part -->
            <div class="header__profile-bottom">
                <button class="header__profile-btn" onclick="window.location.href='{{ route('profile.show') }}'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewbox="0 0 24 24"
                        fill="none" stroke="#BBFCDE" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path
                            d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                        </path>
                    </svg>
                </button>
                <button class="header__profile-btn" onclick="window.location.href='{{ route('logout') }}'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewbox="0 0 24 24"
                        fill="none" stroke="#BBFCDE" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</header>
