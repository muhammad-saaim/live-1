<?php
namespace App\Http\Controllers;

use App\Services\InviteService;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Models\Invitation;
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
            'group_id' => 'required|exists:groups,id',
        ]);
    
        $group = Group::findOrFail($request->group_id);
    
        // Check if user is a member of the group
        if (!auth()->user()->groups->contains($group->id)) {
            return redirect()->back()->with('error', 'You are not authorized to invite members to this group.');
        }
    
        foreach ($request->emails as $email) {
            $this->inviteService->invite(auth()->user(), $email, $group);
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
        if ($user->groups->where('groups.id', $invitation->group_id)->exists()) {
            return redirect()->route('groups.index')->with('info', 'You are already a member of this group.');
        }
    
        // Attach user to group
        $user->groups->attach($invitation->group_id);
    
        // Mark invitation as used or delete it
        $invitation->delete();
    
        return redirect()->route('groups.show', $invitation->group_id)->with('success', 'You’ve successfully joined the group!');
    }
}
