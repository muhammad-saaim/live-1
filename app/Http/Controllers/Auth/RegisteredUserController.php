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
use App\Models\Relation;

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
            'gender' => ['required', 'in:male,female'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
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

            $relation = Relation::find($invitation->relation_id);

            // Only proceed if relation exists
            if ($relation) {
                $this->storeInverseRelation($user->id, $invitation->invited_by, $relation->name);
                $this->linkNewRelativeWithExistingRelations($invitation->invited_by, $user->id, $relation->name, $user->gender);
            }


            // Delete the invitation after accepting it
            $invitation->delete();
        }

        event(new Registered($user));

        Auth::login($user);
        // Send welcome email
        Mail::to($user->email)->send(new WelcomeEmail($user));
        return redirect(RouteServiceProvider::HOME);
    }


    
    public function storeInverseRelation($invitedId, $inviterId, $relationName)
    {
        $inviter = User::find($inviterId);
    
        if (!$inviter || !$inviter->gender) {
            return; 
        }
    
        // Gender-sensitive inverse map
        $inverseMap = [
            'Father'    => ['male' => 'Son',     'female' => 'Daughter'],
            'Mother'    => ['male' => 'Son',     'female' => 'Daughter'],
            'Son'       => ['male' => 'Father',  'female' => 'Mother'],
            'Daughter'  => ['male' => 'Father',  'female' => 'Mother'],
            'Brother'   => ['male' => 'Brother', 'female' => 'Sister'],
            'Sister'    => ['male' => 'Brother', 'female' => 'Sister'],
            'Uncle'     => ['male' => 'Nephew',  'female' => 'Niece'],
            'Aunt'      => ['male' => 'Nephew',  'female' => 'Niece'],
            'Nephew'    => ['male' => 'Uncle',   'female' => 'Aunt'],
            'Niece'     => ['male' => 'Uncle',   'female' => 'Aunt'],
            'Grandfather' => ['male' => 'Grandson', 'female' => 'Granddaughter'],
            'Grandmother' => ['male' => 'Grandson', 'female' => 'Granddaughter'],
            'Grandson'    => ['male' => 'Grandfather', 'female' => 'Grandmother'],
            'Granddaughter' => ['male' => 'Grandfather', 'female' => 'Grandmother'],
            'Spouse'    => ['male' => 'Spouse',  'female' => 'Spouse'],
            'Cousin'    => ['male' => 'Cousin',  'female' => 'Cousin'], 
        ];
    
        // Determine inverse relation name based on inviter's gender
        $inverseName = $inverseMap[$relationName][$inviter->gender] ?? null;
    
        if (!$inverseName) {
            return; // Skip if inverse is not defined
        }
    
        $inverseRelation = Relation::where('name', $inverseName)->first();
    
        if (!$inverseRelation) {
            return; // Skip if inverse relation is not found in DB
        }
    
        // Avoid duplicate
        $exists = UserRelative::where([
            'user_id' => $invitedId,
            'relative_id' => $inviterId,
        ])->exists();
    
        if (!$exists) {
            UserRelative::create([
                'user_id' => $invitedId,
                'relative_id' => $inviterId,
                'relation_id' => $inverseRelation->id,
            ]);
        }
    }
    
    public function linkNewRelativeWithExistingRelations($inviterId, $invitedId, $relationToInviter, $invitedGender)
    {
        $inviter = User::find($inviterId);
        $invited = User::find($invitedId);

        if (!$inviter || !$invited) {
            return;
        }

        $existingRelations = UserRelative::where('user_id', $inviterId)->with('relative', 'relation')->get();

        // Define mappings: [existing_relation][new_relation] = relation_to_new_relative
        $relationMap = [
            'Father' => [
                'Brother' => 'Father',
                'Sister' => 'Father',
                'Son' => $invitedGender === 'male' ? 'Grandfather' : 'Grandfather',
                'Daughter' => $invitedGender === 'male' ? 'Grandfather' : 'Grandfather',
            ],
            'Mother' => [
                'Brother' => 'Mother',
                'Sister' => 'Mother',
                'Son' => 'Grandmother',
                'Daughter' => 'Grandmother',
            ],
            'Brother' => [
                'Brother' => 'Brother',
                'Sister' => 'Brother',
            ],
            'Sister' => [
                'Brother' => 'Sister',
                'Sister' => 'Sister',
            ],
            'Son' => [
                'Brother' => 'Son',
                'Sister' => 'Son',
            ],
            'Daughter' => [
                'Brother' => 'Daughter',
                'Sister' => 'Daughter',
            ],
            'Cousin' => [
                'Brother' => 'Cousin',
                'Sister' => 'Cousin',
            ],
            'Grandfather' => [
                'Brother' => 'Grandfather',
                'Sister' => 'Grandfather',
            ],
            'Grandmother' => [
                'Brother' => 'Grandmother',
                'Sister' => 'Grandmother',
            ],
        ];

        $inverseMap = [
            'Father' => $invitedGender === 'male' ? 'Son' : 'Daughter',
            'Mother' => $invitedGender === 'male' ? 'Son' : 'Daughter',
            'Brother' => 'Brother',
            'Sister' => 'Sister',
            'Son' => $invitedGender === 'male' ? 'Brother' : 'Sister',
            'Daughter' => $invitedGender === 'male' ? 'Brother' : 'Sister',
            'Cousin' => 'Cousin',
            'Grandfather' => $invitedGender === 'male' ? 'Grandson' : 'Granddaughter',
            'Grandmother' => $invitedGender === 'male' ? 'Grandson' : 'Granddaughter',
        ];

        foreach ($existingRelations as $relation) {
            $existingRelationName = $relation->relation->name;
            $existingRelativeId = $relation->relative_id;

            if (!isset($relationMap[$existingRelationName][$relationToInviter])) {
                continue;
            }
            // if($existingRelativeId ===  $invitedId)
            $newRelationName = $relationMap[$existingRelationName][$relationToInviter];
            $inverseRelationName = $inverseMap[$newRelationName] ?? null;

            $inverseRelationModel = Relation::where('name', $newRelationName)->first();
            $relationModel = Relation::where('name', $inverseRelationName)->first();

            // Check if the relation already exists before creating
            if ($relationModel) {
                $exists = UserRelative::where([
                    'user_id' => $existingRelativeId,
                    'relative_id' => $invitedId,
                ])->exists();

                if (!$exists) {
                    UserRelative::create([
                        'user_id' => $existingRelativeId,
                        'relative_id' => $invitedId,
                        'relation_id' => $relationModel->id,
                    ]);
                }
            }

            // Check for inverse relation
            if ($inverseRelationModel) {
                $exists = UserRelative::where([
                    'user_id' => $invitedId,
                    'relative_id' => $existingRelativeId,
                ])->exists();

                if (!$exists) {
                    UserRelative::create([
                        'user_id' => $invitedId,
                        'relative_id' => $existingRelativeId,
                        'relation_id' => $inverseRelationModel->id,
                    ]);
                }
            }

        }
    }
}
