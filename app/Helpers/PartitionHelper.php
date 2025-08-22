<?php

namespace App\Helpers;
use Illuminate\Support\Arr;

class PartitionHelper
{
    /**
     * Partitions the old data into two arrays: one for items that are in the new data,
     * and another for items that are not in the new data.
     *
     * @param  $oldData
     * @param  $newData
     * @return array
     */
    // https://laracasts.com/discuss/channels/eloquent/update-create-and-delete-hasmany-relations-in-one-go
    public static function partition($oldData, $newData): array
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

        return compact('delete', 'update', 'create');
    }
}