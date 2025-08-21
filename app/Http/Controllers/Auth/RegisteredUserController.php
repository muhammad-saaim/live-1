<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Group;
use App\Models\Relation;
use Illuminate\View\View;
use App\Mail\WelcomeEmail;
use App\Models\Invitation;
use Illuminate\Support\Str;
use App\Models\UserRelative;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

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
    // Validate request
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'gender' => ['required', 'in:male,female'],
    ]);

    // Default image path from public folder
    $defaultImagePath = public_path('assets/image/default.jpeg');
    $newImageName = 'profile_images/' . Str::uuid() . '.jpeg';

    // Check if file exists before copying
    if (file_exists($defaultImagePath)) {
        Storage::disk('public')->put($newImageName, File::get($defaultImagePath));
    } else {
        // Optional: handle missing default image
        $newImageName = null;
    }

    // Create user with default image path if available
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'gender' => $request->gender,
        'password' => Hash::make($request->password),
        'image' => $newImageName ? $newImageName : null,
     ]);

    $user->assignRole('user');

    // Check if there is a pending invitation for this email
    $invitations = Invitation::where('email', $request->email)->get();
    foreach ($invitations as $invitation) {
        $group = Group::with('groupTypes')->find($invitation->group_id);
        if ($group) {
            $user->groups()->attach($group->id, [
                'invited_by' => $invitation->invited_by,
            ]);

            $isFamilyGroup = $group->groupTypes->contains(function ($type) {
                return strtolower($type->name) === 'family';
            });

            if ($isFamilyGroup) {
                UserRelative::create([
                    'user_id' => $invitation->invited_by,
                    'relative_id' => $user->id,
                    'relation_id' => $invitation->relation_id,
                ]);

                $relation = Relation::find($invitation->relation_id);

                if ($relation) {
                    storeInverseRelation($user->id, $invitation->invited_by, $relation->name);
                    linkNewRelativeWithExistingRelations($invitation->invited_by, $user->id, $relation->name, $user->gender);
                }
            }
        }
        $invitation->delete();
    }

    event(new Registered($user));

    Auth::login($user);

    // Send welcome email
    Mail::to($user->email)->send(new WelcomeEmail($user));

    return redirect(RouteServiceProvider::HOME);
}
}
