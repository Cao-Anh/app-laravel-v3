<?php

namespace App\Http\Controllers;

use App\Models\UserActivity;
use Illuminate\Http\Request;

class UserActivityController extends Controller
{
    public function index()
    {
        $logs = UserActivity::with('user')->latest()->paginate(20);
        return view('logs.index', compact('logs'));
    }

    public function show($userId)
    {
        $logs = UserActivity::with('user')
            ->where('user_id', $userId)
            ->latest()
            ->paginate(20);

        return view('logs.show', compact('logs'));
    }
}
