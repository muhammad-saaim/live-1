<?php
namespace App\Http\Controllers;

use App\Services\InviteService;
use App\Models\Group;
use Illuminate\Http\Request;

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
            'email' => 'required|email',
            'group_id' => 'required|exists:groups,id',
        ]);

        $group = Group::findOrFail($request->group_id);
        // Kullanıcının grupta üye olup olmadığını kontrol et
        if (!auth()->user()->groups->contains($group->id)) {
            return redirect()->back()->with('error', 'You are not authorized to invite members to this group.');
        }

        $invitee = $this->inviteService->invite(auth()->user(), $request->email, $group);

        return redirect()->back()->with('success', 'Invitation sent successfully.');
    }
}
