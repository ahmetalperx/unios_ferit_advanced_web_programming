<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller {

    // ---------------------------------------------------------------------------------------------- //

    public function index() {

        $user = Auth::user();

        $managed_projects = $user -> managedProjects() -> with('members') -> get();

        $member_projects = $user -> memberProjects() -> with('manager') -> get();

        return view('projects.index', compact('managed_projects', 'member_projects'));

    }

    // ---------------------------------------------------------------------------------------------- //

    public function create() {

        $available_users = User::where('id', '!=', Auth::id()) -> get();

        return view('projects.create', compact('available_users'));

    }

    // ---------------------------------------------------------------------------------------------- //

    public function store(Request $request) {

        $validated = $request -> validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'done_jobs' => 'nullable|string',
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id'
        ]);

        $project = Project::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'done_jobs' => $validated['done_jobs'] ?? '',
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'manager_id' => Auth::id()
        ]);

        if (isset($validated['members']) === true) {

            $project -> members() -> sync($validated['members']);

        }

        return redirect() -> route('projects.index') -> with('success', __('Project created successfully!'));

    }

    // ---------------------------------------------------------------------------------------------- //

    public function edit(Project $project) {

        $user_id = Auth::id();

        $is_manager = ($user_id === $project -> manager_id);

        $is_member = $project -> members -> contains($user_id);

        if ($is_manager === false && $is_member === false) {

            abort(403, __('Unauthorized action.'));

        }

        $available_users = User::where('id', '!=', $project -> manager_id) -> get();

        return view('projects.edit', compact('project', 'is_manager', 'is_member', 'available_users'));

    }

    // ---------------------------------------------------------------------------------------------- //

    public function update(Request $request, Project $project) {

        $user_id = Auth::id();

        $is_manager = ($user_id === $project -> manager_id);

        $is_member = $project -> members -> contains($user_id);

        if ($is_manager === true) {

            $validated = $request -> validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'done_jobs' => 'nullable|string',
                'members' => 'nullable|array',
                'members.*' => 'exists:users,id'
            ]);

            $project -> update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'done_jobs' => $validated['done_jobs'] ?? '',
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date']
            ]);

            $project -> members() -> sync($request -> members ?? []);

            return redirect() -> route('projects.index') -> with('success', __('Project updated successfully by Manager!'));

        } elseif ($is_member === true) {

            $validated = $request -> validate([
                'done_jobs' => 'nullable|string'
            ]);

            $project -> update([
                'done_jobs' => $validated['done_jobs'] ?? ''
            ]);

            return redirect() -> route('projects.index') -> with('success', __('Completed jobs updated successfully by Team Member!'));

        }

        abort(403, __('Unauthorized action.'));

    }

    // ---------------------------------------------------------------------------------------------- //

}
