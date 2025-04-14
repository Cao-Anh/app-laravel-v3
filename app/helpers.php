<?php
use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;

function logActivity(string $action, ?string $description = null)
{
    $user = Auth::user();

    UserActivity::create([
        'user_id'     => $user?->id,
        'action'      => $action,
        'description' => $description,
        'url'         => request()->fullUrl(),
        'ip_address'  => request()->ip(),
        'user_agent'  => request()->userAgent(),
    ]);
}
