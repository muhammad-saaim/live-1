<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class ProviderController extends Controller
{
    public function redirect($provider)
    {
        try {
            if (!$provider) {
                throw new Exception("Provider is not set.");
            }

            return Socialite::driver($provider)->redirect();
        } catch (Exception $e) {
            // Log the error
            Log::error($e->getMessage());

            // Redirect to an appropriate error page or handle the exception
            return redirect()->route('login')->withErrors(['message' => 'An error occurred during the login process.']);
        }
    }

    public function callback($provider): RedirectResponse
    {
        try {
            $socialUser = Socialite::driver($provider)->user();

            $user = $this->findOrCreateUser($provider, $socialUser);

            Auth::login($user);

            return redirect()->route('dashboard');
        } catch (Exception $e) {
            return redirect('login')->withErrors(['message' => $e->getMessage()]);
        }
    }
    private function findOrCreateUser(string $provider, $socialUser): User
    {
        $user = User::where([
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
        ])->first();

        if (!$user) {
            $password = $this->generateSecurePassword();

            if (User::where('email', $socialUser->getEmail())->exists()) {
                throw new Exception('This email is already registered using a different login method.');
            }

            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'username' => User::generateUniqueUsername($socialUser->getNickname()),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'provider_token' => $socialUser->token,
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ]);

            $user->markEmailAsVerified();
            $user->assignRole('User');
        }

        return $user;
    }

    static function generateSecurePassword(int $lower = 3, int $upper = 3, int $digits = 2, int $special = 2, int $length = 10)
    {
        $lowercaseChars = 'abcdefghijklmnopqrstuvwxyz';
        $uppercaseChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $digitChars = '0123456789';
        $specialChars = '!@#$%^&*()_+~`|}{[]\:;?><,./-=';

        $password = '';

        // Add the required number of lowercase, uppercase, digits, and special characters
        $password .= substr(str_shuffle($lowercaseChars), 0, $lower);
        $password .= substr(str_shuffle($uppercaseChars), 0, $upper);
        $password .= substr(str_shuffle($digitChars), 0, $digits);
        $password .= substr(str_shuffle($specialChars), 0, $special);

        // Add additional random characters to reach the desired length
        $remainingLength = $length - strlen($password);
        $allChars = $lowercaseChars . $uppercaseChars . $digitChars . $specialChars;
        $password .= substr(str_shuffle($allChars), 0, $remainingLength);

        // Shuffle the password to ensure the position of the different character types is random
        return str_shuffle($password);
    }
}
