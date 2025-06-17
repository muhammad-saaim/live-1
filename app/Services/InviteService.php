<?php

namespace App\Services;

use App\Models\User;
use App\Models\Group;
use App\Models\Invitation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class InviteService
{
    public function invite(User $inviter, string $email, Group $group, ?int $relationId = null): Invitation
    {
        // Generate an invitation token
        $token = Str::random(32);
        $user = User::where('email', $email)->first();

        // Create the invitation record in the database
        $invitation = Invitation::create([
            'email' => $email,
            'group_id' => $group->id,
            'invited_by' => $inviter->id,
            'token' => $token,
            'relation_id' => $relationId,
        ]);

        if ($user) {
            $link = url("/groups/accept-invite?token={$token}");
            Mail::raw("$inviter->name invited you to Join  $group->name: $link", function ($message) use ($email) {
                $message->to($email)
                    ->subject("Group Invitation");
            });
        } else {
            $link = url("/register?token={$token}");
            Mail::raw("$inviter->name invited you to register: $link", function ($message) use ($email) {
                $message->to($email)
                    ->subject("Group Invitation");
            });
        }

        return $invitation;
    }
}
