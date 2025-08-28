<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use App\Enums\ProjectStatus;

class TaskService
{
    public function prepareDataForIndex()
    {
        $tasks = Task::with('project', 'user')
            ->paginate(10)
            ->through(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'project' => $task->project,
                    'user' => $task->user,
                    'status' => $task->status,
                    'end_date' => $task->end_date,
                ];
            });


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

        return [
            'tasks' => $tasks,
            'status' => ProjectStatus::toArray(),
            'users' => $users,
            'projects' => $projects,
        ];
    }
}
