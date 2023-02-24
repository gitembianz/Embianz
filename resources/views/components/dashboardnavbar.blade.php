<nav class="navbar-bg">
    <div class="logo">
        <img class="cursor-p" onclick="window.location.href='/redirect'"
            src="/images/dashboard/navbar/rectangular_light.png" id="full_logo" alt="icon large">
        <img onclick="window.location.href='/redirect'" src="/images/dashboard/navbar/logoe.png" id="min_logo"
            alt="icon ">
    </div>
    <div id="search-form" class="hidden">
        <form action="#" method="post" class="display-f" x-data>
            @csrf
            <input type="text" name="input_search" id="input_search" class="ls-2 fw-300 text-secondary p-1 br-sm"
                placeholder="Search here...">

        </form>
    </div>

    <div class="right align-center">
        <div class="controls mr-3">
            <span class="mr-1" id="search-icon">
                <svg width="64px" height="64px" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M4 11C4 7.13401 7.13401 4 11 4C14.866 4 18 7.13401 18 11C18 14.866 14.866 18 11 18C7.13401 18 4 14.866 4 11ZM11 2C6.02944 2 2 6.02944 2 11C2 15.9706 6.02944 20 11 20C13.125 20 15.078 19.2635 16.6177 18.0319L20.2929 21.7071C20.6834 22.0976 21.3166 22.0976 21.7071 21.7071C22.0976 21.3166 22.0976 20.6834 21.7071 20.2929L18.0319 16.6177C19.2635 15.078 20 13.125 20 11C20 6.02944 15.9706 2 11 2Z"
                            fill="#000000"></path>
                    </g>
                </svg>
            </span>
            <span class="mr-1">
                <svg width="64px" height="64px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                    stroke="#000000" stroke-width="0.00024000000000000003" transform="matrix(-1, 0, 0, 1, 0, 0)">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC"
                        stroke-width="0.384"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path
                            d="M20.53 16.25C20.44 16.25 18.42 15.89 18.42 10C18.42 5.84 16 3.25 12 3.25C8 3.25 5.58 5.84 5.58 10C5.58 16 3.49 16.25 3.5 16.25C3.30109 16.25 3.11032 16.329 2.96967 16.4697C2.82902 16.6103 2.75 16.8011 2.75 17C2.75 17.1989 2.82902 17.3897 2.96967 17.5303C3.11032 17.671 3.30109 17.75 3.5 17.75H8.33C8.49694 18.6007 8.95423 19.367 9.62366 19.9178C10.2931 20.4686 11.1331 20.7698 12 20.7698C12.8669 20.7698 13.7069 20.4686 14.3763 19.9178C15.0458 19.367 15.5031 18.6007 15.67 17.75H20.51C20.7089 17.75 20.8997 17.671 21.0403 17.5303C21.181 17.3897 21.26 17.1989 21.26 17C21.26 16.8011 21.181 16.6103 21.0403 16.4697C20.8997 16.329 20.7089 16.25 20.51 16.25H20.53ZM12 19.25C11.5363 19.2491 11.0843 19.1044 10.7064 18.8357C10.3284 18.567 10.0432 18.1877 9.89 17.75H14.11C13.9568 18.1877 13.6716 18.567 13.2936 18.8357C12.9157 19.1044 12.4637 19.2491 12 19.25ZM5.76 16.25C6.48 15.16 7.08 13.25 7.08 10C7.08 6.75 8.88 4.75 12 4.75C15.12 4.75 16.92 6.66 16.92 10C16.92 13.34 17.52 15.16 18.24 16.25H5.76Z"
                            fill="#000000"></path>
                    </g>
                </svg>
            </span>
            <span class="mr-1">
                <svg fill="#000000" height="64px" width="64px" version="1.1" id="Capa_1"
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    viewBox="-153.6 -153.6 819.20 819.20" xml:space="preserve"
                    transform="rotate(90)matrix(1, 0, 0, 1, 0, 0)" stroke="#000000"
                    stroke-width="0.0051200099999999995">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC"
                        stroke-width="4.096007999999999"></g>
                    <g id="SVGRepo_iconCarrier">
                        <g>
                            <g>
                                <path
                                    d="M116.364,186.182H93.091V23.273C93.091,10.42,82.671,0,69.818,0C56.965,0,46.545,10.42,46.545,23.273v162.909H23.273 C10.42,186.182,0,196.602,0,209.455v93.091c0,12.853,10.42,23.273,23.273,23.273h23.273v162.909 c0,12.853,10.42,23.273,23.273,23.273c12.853,0,23.273-10.42,23.273-23.273v-162.91h23.273c12.853,0,23.273-10.42,23.273-23.273 v-93.091C139.636,196.602,129.216,186.182,116.364,186.182z M46.545,279.273v-46.545H93.09v46.545H46.545z">
                                </path>
                            </g>
                        </g>
                        <g>
                            <g>
                                <path
                                    d="M488.727,155.152h-23.273V23.273C465.455,10.42,455.035,0,442.182,0c-12.853,0-23.273,10.42-23.273,23.273v131.879 h-23.273c-12.853,0-23.273,10.42-23.273,23.273v93.091c0,12.853,10.42,23.273,23.273,23.273h23.273v193.939 c0,12.853,10.42,23.273,23.273,23.273c12.853,0,23.273-10.42,23.273-23.273v-193.94h23.273c12.853,0,23.273-10.42,23.273-23.273 v-93.091C512,165.572,501.58,155.152,488.727,155.152z M418.91,248.242v-46.545h46.545v46.545H418.91z">
                                </path>
                            </g>
                        </g>
                        <g>
                            <g>
                                <path
                                    d="M302.545,217.212h-23.273V23.273C279.273,10.42,268.853,0,256,0c-12.853,0-23.273,10.42-23.273,23.273v193.939h-23.273 c-12.853,0-23.273,10.42-23.273,23.273v93.091c0,12.853,10.42,23.273,23.273,23.273h23.273v131.879 C232.727,501.58,243.147,512,256,512c12.853,0,23.273-10.42,23.273-23.273V356.849h23.273c12.853,0,23.273-10.42,23.273-23.273 v-93.091C325.818,227.632,315.398,217.212,302.545,217.212z M279.273,310.303h-46.545v-46.545h46.545V310.303z">
                                </path>
                            </g>
                        </g>
                    </g>
                </svg>
            </span>
        </div>
        <div class="user_container align-center">
            <div class="user_icon br-xl m-1">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    width="20" height="20" viewBox="0 0 20 20">
                    <path fill="#bafcdd"
                        d="M9.5 11c-3.033 0-5.5-2.467-5.5-5.5s2.467-5.5 5.5-5.5 5.5 2.467 5.5 5.5-2.467 5.5-5.5 5.5zM9.5 1c-2.481 0-4.5 2.019-4.5 4.5s2.019 4.5 4.5 4.5c2.481 0 4.5-2.019 4.5-4.5s-2.019-4.5-4.5-4.5z">
                    </path>
                    <path fill="#bafcdd"
                        d="M17.5 20h-16c-0.827 0-1.5-0.673-1.5-1.5 0-0.068 0.014-1.685 1.225-3.3 0.705-0.94 1.67-1.687 2.869-2.219 1.464-0.651 3.283-0.981 5.406-0.981s3.942 0.33 5.406 0.981c1.199 0.533 2.164 1.279 2.869 2.219 1.211 1.615 1.225 3.232 1.225 3.3 0 0.827-0.673 1.5-1.5 1.5zM9.5 13c-3.487 0-6.060 0.953-7.441 2.756-1.035 1.351-1.058 2.732-1.059 2.746 0 0.274 0.224 0.498 0.5 0.498h16c0.276 0 0.5-0.224 0.5-0.5-0-0.012-0.023-1.393-1.059-2.744-1.382-1.803-3.955-2.756-7.441-2.756z">
                    </path>
                </svg>
            </div>
            <div class="user_name talign-l m-1 text-white">
                <div>
                    {{ __('Iosif Relia') }}
                </div>
                <div>
                    {{ $user }}
                </div>
            </div>
            <div class="drop_icon">
                <svg version="1.1" id="drop_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20">
                    <path fill="#000000" d="M0 6c0-0.128 0.049-0.256 0.146-0.354 0.195-0.195 0.512-0.195 0.707 0l8.646 8.646 8.646-8.646c0.195-0.195 0.512-0.195 0.707 0s0.195 0.512 0 0.707l-9 9c-0.195 0.195-0.512 0.195-0.707 0l-9-9c-0.098-0.098-0.146-0.226-0.146-0.354z"></path>
                    </svg>
            </div>
        </div>
    </div>

    {{-- <div class="dropdowncontent">
        <ul class="dropdown">
            <li class="ml-1 mr-2">
                <button id="button_user" class="ls-1  font-md pb-1 pl-2 br-xs">{{ $user }}<img
                        src="/images/dashboard/navbar/chevron-down.svg" alt="icon" class="ml-1"></button>
                <div class="dropdown-content bg-secondary-light-1 font-sm o-80 p-1">
                    <a class="p-1 bg-secondary-dark-1 br-xs font-md" href="{{ route('profile.show') }}">{{ __('Profile') }}</a>
                    <a class="p-1 bg-secondary-dark-1 br-xs mt-1 font-md" href="{{ route('logout') }}">{{ __('Logout') }}</a>
                </div>

            </li>
        </ul>
    </div> --}}

    <div class="small">
        <button onclick="document.getElementById('modal-user').style.display='block'" id="button_user_small"
            class="btn-bg br-xs">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                width="20" height="20" viewBox="0 0 20 20">
                <path fill="#bafcdd"
                    d="M9.5 11c-3.033 0-5.5-2.467-5.5-5.5s2.467-5.5 5.5-5.5 5.5 2.467 5.5 5.5-2.467 5.5-5.5 5.5zM9.5 1c-2.481 0-4.5 2.019-4.5 4.5s2.019 4.5 4.5 4.5c2.481 0 4.5-2.019 4.5-4.5s-2.019-4.5-4.5-4.5z">
                </path>
                <path fill="#bafcdd"
                    d="M17.5 20h-16c-0.827 0-1.5-0.673-1.5-1.5 0-0.068 0.014-1.685 1.225-3.3 0.705-0.94 1.67-1.687 2.869-2.219 1.464-0.651 3.283-0.981 5.406-0.981s3.942 0.33 5.406 0.981c1.199 0.533 2.164 1.279 2.869 2.219 1.211 1.615 1.225 3.232 1.225 3.3 0 0.827-0.673 1.5-1.5 1.5zM9.5 13c-3.487 0-6.060 0.953-7.441 2.756-1.035 1.351-1.058 2.732-1.059 2.746 0 0.274 0.224 0.498 0.5 0.498h16c0.276 0 0.5-0.224 0.5-0.5-0-0.012-0.023-1.393-1.059-2.744-1.382-1.803-3.955-2.756-7.441-2.756z">
                </path>
            </svg>
        </button>
    </div>
</nav>
