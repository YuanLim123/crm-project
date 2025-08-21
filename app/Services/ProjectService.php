<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Arr;

class ProjectService
{
    public function store(array $attributes)
    {
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
                    'user_id' => $task['user'],
                ]);
            }
        }

        if (!empty($attributes['attachments'])) {
            foreach ($attributes['attachments'] as $attachment) {
                $project->addMedia($attachment)->toMediaCollection('attachments');
            }
        }
    }

    public function update(array $attributes, Project $project): Project
    {
        $project->update([
            'title' => $attributes['title'],
            'description' => $attributes['description'],
            'status' => $attributes['status'],
            'end_date' => $attributes['endDate'],
            'client_id' => $attributes['client'],
            'user_id' => $attributes['assignedUser'],
        ]);
        
        // initial approach, Delete all old tasks then Create new tasks
        // $project->tasks()->delete();
        // foreach ($attributes['tasks'] as $task) {
        //     $project->tasks()->create([
        //         'title' => $task['title'],
        //         'description' => $task['description'],
        //         'status' => $task['status'],
        //         'end_date' => $task['endDate'],
        //         'user_id' => $task['user'],
        //     ]);
        // }

        // approach from laracast
        // Sync tasks
        $oldData = $project->tasks;
        $newData = $attributes['tasks'] ?? [];

        $partition = $this->updateRelations($oldData, $newData);
        // Delete removed tasks
        foreach ($partition['delete'] as $task) {
            $project->tasks()->where('id', $task['id'])->delete();
        }

        // Update existing tasks
        foreach ($partition['update'] as $task) {
            $project->tasks()->where('id', $task['id'])->update([
                'title' => $task['title'],
                'description' => $task['description'],
                'status' => $task['status'],
                'end_date' => $task['endDate'],
                'user_id' => $task['user'],
            ]);
        }
        // Create new tasks
        foreach ($partition['create'] as $task) {
            $project->tasks()->create([
                'title' => $task['title'],
                'description' => $task['description'],
                'status' => $task['status'],
                'end_date' => $task['endDate'],
                'user_id' => $task['user'],
            ]);
        }

        // Handle current file deletions
        if (!empty($attributes['fileToDelete'])) {
            foreach ($attributes['fileToDelete'] as $fileId) {
                $media = $project->getMedia('attachments')->where('id', $fileId)->first();
                if ($media) {
                    $media->delete();
                }
            }
        }

        // Handle new file uploads
        if (!empty($attributes['attachments'])) {
            foreach ($attributes['attachments'] as $attachment) {
                $project->addMedia($attachment)->toMediaCollection('attachments');
            }
        }

        return $project->fresh();
    }

    // https://laracasts.com/discuss/channels/eloquent/update-create-and-delete-hasmany-relations-in-one-go
    private function updateRelations($oldData, $newData)
    {
        $oldIds = Arr::pluck($oldData, 'id');
        $newIds = Arr::pluck($newData, 'id');

        // groups
        $delete = collect($oldData)
            ->filter(function ($model) use ($newIds) {
                return !in_array($model["id"], $newIds);
            });

        $update = collect($newData)
            ->filter(function ($model) use ($oldIds) {
                return isset($model["id"]) && in_array($model["id"], $oldIds);
            });

        $create = collect($newData)
            ->filter(function ($model) {
                return !isset($model["id"]);
            });
        // return
        return compact('delete', 'update', 'create');
    }
}
