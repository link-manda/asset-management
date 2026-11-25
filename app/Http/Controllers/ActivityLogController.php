<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activities.
     */
    public function index(Request $request)
    {
        $query = Activity::with('causer')->latest();

        // Filter by Causer (User)
        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id)
                  ->where('causer_type', User::class);
        }

        // Filter by Event
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        // Filter by Subject Type
        if ($request->filled('subject_type')) {
            $query->where('subject_type', 'like', '%' . $request->subject_type . '%');
        }

        $activities = $query->paginate(20)->withQueryString();
        $users = User::orderBy('name')->get();

        return view('reports.activity-log', compact('activities', 'users'));
    }

    /**
     * Display the specified activity details.
     */
    public function show(Activity $activity)
    {
        return view('reports.activity-log-show', compact('activity'));
    }
}
