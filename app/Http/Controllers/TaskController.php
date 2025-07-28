<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use App\Enums\ProjectStatus;
use Illuminate\Validation\Rules\Enum;


class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::with('project', 'user')
            ->get();

        $users = User::all()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ];
        });

        $projects = Project::all()->map(function ($project) {
            return [
                'id' => $project->id,
                'title' => $project->title,
            ];
        });

        return Inertia::render('Task/Index', [
            'tasks' => $tasks,
            'status' => ProjectStatus::toArray(),
            'users' => $users,
            'projects' => $projects,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'user' => ['required', 'array'],
            'user.id' => ['exists:users,id'],
            'project' => ['required', 'array'],
            'project.id' => ['exists:projects,id'],
            'status' => ['required', new Enum(ProjectStatus::class)],
            'endDate' => ['required', 'date'],
        ]);

        Task::create([
            'title' => $attributes['title'],
            'description' => $attributes['description'],
            'status' => $attributes['status'],
            'end_date' => $attributes['endDate'],
            'user_id' => $attributes['user']['id'],
            'project_id' => $attributes['project']['id'],
        ]);

        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
