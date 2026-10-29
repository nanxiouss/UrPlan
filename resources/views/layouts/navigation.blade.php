<nav x-data="{ open: false, scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)" :class="{ 'bg-white/70 backdrop-blur-md shadow-sm border-b border-gray-200/50': scrolled, 'bg-white border-b border-gray-100': !scrolled }" class="sticky top-0 z-50 transition-all duration-300 ease-in-out">

    <div class="h-1 w-full bg-gradient-to-r from-[#2DA9E9] via-[#1F7FD1] to-[#0B4FB3]"></div>

    <div class="max-w-full mx-auto px-2 sm:px-4 lg:px-6">
        <div class="flex justify-between h-16 items-center">

            <div class="flex items-center">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-0 group">
                        <img src="{{ asset('images/logo-1.png') }}" alt="Our Plan Logo" class="h-14 w-auto object-contain transition-all duration-300 transform group-hover:scale-105">

                        <span class="font-extrabold text-2xl tracking-tighter bg-gradient-to-r from-[#1F7FD1] to-[#0B4FB3] text-transparent bg-clip-text hidden sm:block -ml-2">
                            Our Plan
                        </span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link href="#">
                        {{ __('Features') }}
                    </x-nav-link>
                    <x-nav-link href="#">
                        {{ __('Pricing') }}
                    </x-nav-link>
                    <x-nav-link href="#">
                        {{ __('Company') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-0 py-2 border border-transparent text-sm leading-4 font-semibold rounded-md text-gray-700 bg-transparent hover:text-[#1F7FD1] focus:outline-none transition ease-in-out duration-150 group">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1 group-hover:translate-y-0.5 transition-transform">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')"> {{ __('Profile') }} </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="p-2 rounded-md text-gray-500 hover:text-[#1F7FD1] focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>
