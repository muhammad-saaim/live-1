<?php
namespace App\Http\Controllers;

use App\Services\InviteService;
use App\Models\User;
use App\Models\Group;
use App\Models\Relation;
use Illuminate\Http\Request;
use App\Models\Invitation;
use App\Models\UserRelative;
use Illuminate\Support\Facades\Auth;

class InviteController extends Controller
{
    protected $inviteService;

    public function __construct(InviteService $inviteService)
    {
        $this->inviteService = $inviteService;
    }

    public function sendInvite(Request $request)
    {
        $request->validate([
            'emails' => 'required|array',
            'emails.*' => 'required|email',
            'relations' => 'required|array',
            'relations.*' => 'required|exists:relations,id',
            'group_id' => 'required|exists:groups,id',
        ]);
    
        $group = Group::findOrFail($request->group_id);
    
        // Check if user is a member of the group
        if (!auth()->user()->groups->contains($group->id)) {
            return redirect()->back()->with('error', 'You are not authorized to invite members to this group.');
        }
    
        foreach ($request->emails as $index => $email) {
            $relationId = $request->relations[$index];
            $this->inviteService->invite(auth()->user(), $email, $group, $relationId);
        }
    
        return redirect()->back()->with('success', 'Invitations sent successfully.');
    }

    public function acceptInvite(Request $request)
    {
        $token = $request->query('token');
        $invitation = Invitation::where('token', $token)->first();

        if (!$invitation) {
            return redirect('/')->with('error', 'Invalid or expired invitation link.');
        }
    
        if (!Auth::check()) {
            session(['invite_token' => $token]);
            return redirect()->route('login');
        }
    
        $user = Auth::user();
    
        // Check if user already in group
        if ($user->groups()->where('groups.id', $invitation->group_id)->exists()) {
            return redirect()->route('group.index')->with('info', 'You are already a member of this group.');
        }
    
        // Attach user to group
        $user->groups()->attach($invitation->group_id, [
            'invited_by' => $invitation->invited_by,
        ]);    
        $exists = UserRelative::where([
            'user_id' => $invitation->invited_by,
            'relative_id' => $user->id,
        ])->exists();

        // Save the family relation using the model
        if (!$exists) {
            UserRelative::create([
                'user_id' => $invitation->invited_by,
                'relative_id' => $user->id,
                'relation_id' => $invitation->relation_id,
            ]);
        }

        $relation = Relation::find($invitation->relation_id);

        // Only proceed if relation exists
        if ($relation) {
            $this->storeInverseRelation(
                $user->id, // invited_id
                $invitation->invited_by, // inviter_id
                $relation->name // relation name
            );
        }

        $this->linkNewRelativeWithExistingRelations($invitation->invited_by, $user->id, $relation->name, $user->gender);

        // Mark invitation as used or delete it
        $invitation->delete();
    
        return redirect()->route('group.show', $invitation->group_id)->with('success', 'You’ve successfully joined the group!');
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
