<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;


class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::with('client')->get();

        return Inertia::render('Project/Index', [
            'projects' => $projects,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::doesntHave('project')->get();
        $users = User::all();

        return Inertia::render('Project/Create', [
            'clients' => $clients,
            'users' => $users,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $attributes = $request->validate([
            'title' => ['required', 'string', 'max:128'],
            'description' => ['required', 'string'],
            'status' => ['required', 'in:pending,in_progress,completed'],
            'endDate' => ['required', 'date'],
            'client' => ['required', 'exists:clients,id'],
            'assignedUser' => ['required', 'exists:users,id'],
            'tasks' => ['array'],
            'tasks.*.title' => ['required', 'string', 'max:128'],
            'tasks.*.description' => ['required', 'string', 'max:255'],
            'tasks.*.status' => ['required', 'in:pending,in_progress,completed'],
            'tasks.*.endDate' => ['required', 'date'],
            'tasks.*.assignedUser' => ['required', 'exists:users,id'],
        ]);

        $project = Project::create([
            'title' => $attributes['title'],
            'description' => $attributes['description'],
            'status' => $attributes['status'],
            'end_date' => $attributes['endDate'],
            'client_id' => $attributes['client'],
            'user_id' => $attributes['assignedUser'],
        ]);

        if (!empty($attributes['tasks'])) {
            foreach ($attributes['tasks'] as $task) {
                $project->tasks()->create([
                    'title' => $task['title'],
                    'description' => $task['description'],
                    'status' => $task['status'],
                    'end_date' => $task['endDate'],
                    'user_id' => $task['assignedUser'],
                ]);
            }
        }

        return redirect()->route('projects.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Project::with(['client', 'user', 'tasks', 'tasks.user'])->findOrFail($id);
        return Inertia::render('Project/Show', [
            'project' => $project,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
