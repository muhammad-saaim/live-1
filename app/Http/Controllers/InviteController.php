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
    $isFriendGroup = strtolower($request->group_name) === 'friend';

    // Validation rules
    $rules = [
        'emails' => 'required|array',
        'emails.*' => 'required|email',
        'group_id' => 'required|exists:groups,id',
    ];

    if (!$isFriendGroup) {
        $rules['relations'] = 'required|array';
        $rules['relations.*'] = 'required|exists:relations,id';
    }

    $request->validate($rules);

    $group = Group::findOrFail($request->group_id);

    // Check if user is a member of the group
    if (!auth()->user()->groups->contains($group->id)) {
        return redirect()->back()->with('error', 'You are not authorized to invite members to this group.');
    }

    // Get the 'Friend' relation ID once if needed
    $friendRelationId = $isFriendGroup
        ? Relation::whereRaw('LOWER(name) = ?', ['friend'])->value('id')
        : null;

    foreach ($request->emails as $index => $email) {
        $relationId = $isFriendGroup ? $friendRelationId : $request->relations[$index];

        // Optional safeguard
        if (!$relationId) {
            continue; // or handle as error
        }

        $this->inviteService->invite(auth()->user(), $email, $group, $relationId);
    }

    return redirect()->back()->with('success', 'Invitations sent successfully.');
}


    public function acceptInvite(Request $request)
    {
        
        $token = $request->query('token');
        $invitation = Invitation::where('token', $token)->first();
// dd($invitation);
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
            storeInverseRelation($user->id, $invitation->invited_by, $relation->name);
            linkNewRelativeWithExistingRelations($invitation->invited_by, $user->id, $relation->name, $user->gender);
        }

        // Mark invitation as used or delete it
        $invitation->delete();
    
        return redirect()->route('group.show', $invitation->group_id)->with('success', 'You’ve successfully joined the group!');
    }
}
