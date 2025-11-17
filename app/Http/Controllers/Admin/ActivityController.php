<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::with(['user', 'project', 'task'])
            ->orderBy('created_at', 'desc')
            ->paginate(Auth::user()->getSettings()->items_per_page ?? 25);

        return view('admin.activities.index', compact('activities'));
    }

    public function forProject($projectId)
    {
        $activities = Activity::with(['user', 'task'])
            ->forProject($projectId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.activities.index', compact('activities'));
    }
}
