<nav class="navbar-bg">
    <div class="logo">
        <img class="cursor-p" onclick="window.location.href='/redirect'" src="/images/dashboard/navbar/rectangular_light.png" id="full_logo" alt="icon large">
        <img onclick="window.location.href='/redirect'" src="/images/dashboard/navbar/logoe.png" id="min_logo" alt="icon ">
    </div>
    <div>
        <form action="#" method="post" class="display-f" x-data>
            @csrf
            <input type="text" name="input_search" id="input_search"
                class="ls-2 fw-300 text-secondary p-1 pl-3 br-sm" placeholder="Search here...">

        </form>
    </div>
    <div class="dropdowncontent">
        <ul class="dropdown">
            <li class="ml-1 mr-2">
                <button id="button_user" class="btn-secondary ls-1  font-md pb-1 pl-2 br-xs">{{ $user }}<img
                        src="/images/dashboard/navbar/chevron-down.svg" alt="icon" class="ml-1"></button>
                {{-- dropdown element // show profile and logout --}}
                <div class="dropdown-content bg-secondary-light-1 font-sm o-80 p-1">
                    <a class="p-1 bg-secondary-dark-1 br-xs font-md" href="{{ route('profile.show') }}">{{ __('Profile') }}</a>
                    <a class="p-1 bg-secondary-dark-1 br-xs mt-1 font-md" href="{{ route('logout') }}">{{ __('Logout') }}</a>
                </div>
                {{-- end dropdown --}}
            </li>
        </ul>
    </div>
    <div class="small">
        <button onclick="document.getElementById('modal-user').style.display='block'" id="button_user_small" class="btn-bg br-xs"><svg version="1.1"
            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20"
            height="20" viewBox="0 0 20 20">
            <path fill="#bafcdd"
                d="M9.5 11c-3.033 0-5.5-2.467-5.5-5.5s2.467-5.5 5.5-5.5 5.5 2.467 5.5 5.5-2.467 5.5-5.5 5.5zM9.5 1c-2.481 0-4.5 2.019-4.5 4.5s2.019 4.5 4.5 4.5c2.481 0 4.5-2.019 4.5-4.5s-2.019-4.5-4.5-4.5z">
            </path>
            <path fill="#bafcdd"
                d="M17.5 20h-16c-0.827 0-1.5-0.673-1.5-1.5 0-0.068 0.014-1.685 1.225-3.3 0.705-0.94 1.67-1.687 2.869-2.219 1.464-0.651 3.283-0.981 5.406-0.981s3.942 0.33 5.406 0.981c1.199 0.533 2.164 1.279 2.869 2.219 1.211 1.615 1.225 3.232 1.225 3.3 0 0.827-0.673 1.5-1.5 1.5zM9.5 13c-3.487 0-6.060 0.953-7.441 2.756-1.035 1.351-1.058 2.732-1.059 2.746 0 0.274 0.224 0.498 0.5 0.498h16c0.276 0 0.5-0.224 0.5-0.5-0-0.012-0.023-1.393-1.059-2.744-1.382-1.803-3.955-2.756-7.441-2.756z">
            </path>
        </svg></button>
    </div>
</nav>
