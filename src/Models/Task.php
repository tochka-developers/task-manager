<?php

namespace Tochka\TaskManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

/**
 * @property int            $id
 * @property string         $name
 * @property int            $last
 * @property array          $args
 * @property boolean        $is_active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Task extends Model
{
    protected $casts = [
        'args'      => 'array',
        'is_active' => 'bool',
    ];

    public function getConnectionName()
    {
        $connection = Config::get('task-manager.tasks_table.connection');

        return $connection ?? DB::getDefaultConnection();
    }

    public function getTable()
    {
        return Config::get('task-manager.tasks_table.table', 'tasks');
    }
}
