<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeEmail;
use App\Models\Group;
use App\Models\Invitation;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\UserRelative;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole('user');

        // Check if there is a pending invitation for this email
        $invitations = Invitation::where('email', $request->email)->get();
        foreach ($invitations as $invitation) {
            // Automatically add the user to the invited group(s)
            $group = Group::find($invitation->group_id);
            if ($group) {
                $user->groups()->attach($group->id, [
                    'invited_by' => $invitation->invited_by,
                ]);
            }

            UserRelative::create([
                'user_id' => $invitation->invited_by,
                'relative_id' => $user->id,
                'relation_id' => $invitation->relation_id,
            ]);

            // Delete the invitation after accepting it
            $invitation->delete();
        }

        event(new Registered($user));

        Auth::login($user);
        // Send welcome email
        Mail::to($user->email)->send(new WelcomeEmail($user));
        return redirect(RouteServiceProvider::HOME);
    }
}
