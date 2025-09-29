<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard.index') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"/>
                    </a>
                </div>

                <!-- Navigation Links -->
<!-- Navigation Links -->
<div class="hidden md:flex md:space-x-2 lg:space-x-8 sm:-my-px sm:ms-2 lg:ms-10 text-xs md:text-sm lg:text-base">
                    <x-nav-link :href="route('dashboard.index')" :active="request()->routeIs('dashboard.index')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('testing.index')" :active="request()->routeIs('testing.index')">
                        {{ __('Testing') }}
                    </x-nav-link>
                    <x-nav-link :href="route('analysis.index')" :active="request()->routeIs('analysis.index')">
                        {{ __('Analysis') }}
                    </x-nav-link>
                    <x-nav-link :href="route('training.index')" :active="request()->routeIs('training.index')">
                        {{ __('Training') }}
                    </x-nav-link>
                    <x-nav-link :href="route('matching.index')" :active="request()->routeIs('matching.index')">
                        {{ __('Matching') }}
                    </x-nav-link>
                    <x-nav-link :href="route('solutions.index')" :active="request()->routeIs('solutions.index')">
                        {{ __('Solutions') }}
                    </x-nav-link>
                    <div x-data="{ billingOpen: false }"
                         class="relative flex items-center"
                         @mouseenter="if (window.matchMedia('(min-width: 1024px)').matches) billingOpen = true"
                         @mouseleave="if (window.matchMedia('(min-width: 1024px)').matches) billingOpen = false"
                         @click.outside="billingOpen = false">
                        <button type="button"
                                @click.prevent="if (!window.matchMedia('(min-width: 1024px)').matches) billingOpen = !billingOpen"
                                @keydown.enter.prevent="if (!window.matchMedia('(min-width: 1024px)').matches) billingOpen = !billingOpen"
                                @keydown.space.prevent="if (!window.matchMedia('(min-width: 1024px)').matches) billingOpen = !billingOpen"
                                class="inline-flex items-center gap-1 px-1 pt-1 border-b-2 leading-5 font-medium focus:outline-none transition duration-150 ease-in-out
                                       text-xs md:text-sm lg:text-base
                                       {{ request()->routeIs('billing.*')
                                            ? 'border-indigo-400 text-gray-900 dark:text-gray-100'
                                            : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300' }}"
                                aria-haspopup="true" :aria-expanded="billingOpen.toString()">
                            <span>{{ __('Billing') }}</span>
                            <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': billingOpen }" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                        <div x-cloak x-show="billingOpen" x-transition.origin.top.left class="absolute left-0 top-full mt-1 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-50">
                            <div class="py-1">
                                <a href="{{ route('billing.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">{{ __('Billing') }}</a>
                                <a href="{{ route('billing.history') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">{{ __('Invoice History') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
 <!-- User Image -->
    @if(Auth::user()->image)
<img src="{{ asset('storage/' . Auth::user()->image) }}" 
             alt="Profile" 
             class="w-8 h-8 rounded-full object-cover mr-2">
    @else
<img src="{{ asset('assets/image/default.jpeg') }}" alt="Default Image"
             class="w-8 h-8 rounded-full object-cover mr-2">
    @endif                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @hasanyrole('admin|editor')
                        <x-dropdown-link :href="route('admin.index')">
                            {{ __('Admin console') }}
                        </x-dropdown-link>
                        @endhasanyrole
                        @role('mentor')
                        <x-dropdown-link :href="route('mentor.index')">
                            {{ __('Mentor') }}
                        </x-dropdown-link>
                        @endrole
                        <x-dropdown-link :href="route('account.edit')">
                            {{ __('Account') }}
                        </x-dropdown-link>

                        @hasanyrole('user') <!-- Only Users can add their informations detailed -->
                        <x-dropdown-link :href="route('profile.information')">
                            {{ __('Profile Informations') }}
                        </x-dropdown-link>
                        @endhasanyrole

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                             onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                              stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard.index')" :active="request()->routeIs('dashboard.index')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('testing.index')" :active="request()->routeIs('testing.index')">
                {{ __('Testing') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('analysis.index')" :active="request()->routeIs('analysis.index')">
                {{ __('Analysis') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('training.index')" :active="request()->routeIs('training.index')">
                {{ __('Training') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('matching.index')" :active="request()->routeIs('matching.index')">
                {{ __('Matching') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('solutions.index')" :active="request()->routeIs('solutions.index')">
                {{ __('Solutions') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('billing.index')" :active="request()->routeIs('billing.index')">
                {{ __('Billing') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('billing.history')" :active="request()->routeIs('billing.history')">
                {{ __('Invoice History') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('account.edit')">
                    {{ __('Account') }}
                </x-responsive-nav-link>
                @hasanyrole('admin|editor')
                <x-responsive-nav-link :href="route('admin.index')">
                    {{ __('Admin console') }}
                </x-responsive-nav-link>
                @endhasanyrole
                @role('mentor')
                <x-responsive-nav-link :href="route('mentor.index')">
                    {{ __('Mentor') }}
                </x-responsive-nav-link>
                @endrole
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                                           onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>