<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-2 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Enter your email or phone number"/>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-6">
            <div class="flex items-start">
                <div class="flex items-center h-5 pr-4">
                    <input id="remember" name="remember" aria-describedby="remember" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800">
                </div>
                <div class="text-sm">
                    <label for="remember" class="text-gray-500 dark:text-gray-300">{{ __('Remember me') }}</label>
                </div>
            </div>
            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-primary-600 hover:underline dark:text-primary-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <button type="submit" class="mt-4 mb-4 w-full text-white bg-ml-color-green hover:bg-ml-color-green hover:shadow-md focus:ring-4 focus:outline-none focus:ring-ml-color-green font-medium rounded-full text-sm px-3 py-2 text-center">
            {{ __('Log in') }}
        </button>

        <div class="w-full text-ml-color-green hover:text-white border border-ml-color-green hover:border-ml-color-orange hover:bg-ml-color-orange focus:ring-4 focus:outline-none focus:ring-ml-color-orange font-medium rounded-full text-sm px-3 py-2 text-center me-2 mb-2">
            <a href="{{ route('register') }}" class=" font-medium text-primary-600 hover:underline dark:text-primary-500">{{ __('Don’t have an account yet?').' '.  __('Sign up') }}</a>
        </div>

        {{--<p class="text-sm font-light text-gray-500 dark:text-gray-400">
            Don’t have an account yet? <a href="{{ route('register') }}" class="font-medium text-primary-600 hover:underline dark:text-primary-500">{{ __('Sign up') }}</a>
        </p>--}}
    </form>

    <hr>
    @include('components.button-google')

</x-guest-layout>
