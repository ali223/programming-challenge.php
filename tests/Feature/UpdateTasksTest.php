<?php

namespace Tests\Feature;

use App\Task;
use Tests\TestCase;
use \Laravel\Lumen\Testing\DatabaseMigrations;

class UpdateTasksTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_update_a_task_title_and_completed_column_values()
    {
        $task = factory(Task::class)->create();

        $task->title = 'Updated title';
        $task->completed = true;

        $response = $this->call('PATCH', "/api/tasks/{$task->id}", [
                        'data' => [
                            'type' => 'tasks',
                            'id' => $task->id,
                            'attributes' => [
                                'title' => $task->title,
                                'completed' => $task->completed
                            ],
                        ]
                    ]);

        $this->assertEquals(200, $response->status());

        $this->seeJsonEquals([
            'data' => [
                'type' => 'tasks',
                'id' => (string) $task->id,
                'attributes' => [
                    'title' => $task->title,
                    'completed' => $task->completed
                ],
                'links' => [
                    'self' => url("/api/tasks/{$task->id}")
                ]
            ]
        ]);

        $this->seeInDatabase('tasks', [
            'title' => $task->title,
            'completed' => $task->completed
        ]);
    }

    /** @test */
    public function a_user_can_partially_update_data_in_tasks_table()
    {
        $task = factory(Task::class)->create();

        $task->title = 'Updated title';

        $response = $this->call('PATCH', "/api/tasks/{$task->id}", [
                        'data' => [
                            'type' => 'tasks',
                            'id' => $task->id,
                            'attributes' => [
                                'title' => $task->title
                            ],
                        ]
                    ]);

        $this->assertEquals(200, $response->status());

        $this->seeJsonEquals([
            'data' => [
                'type' => 'tasks',
                'id' => (string) $task->id,
                'attributes' => [
                    'title' => $task->title,
                    'completed' => (bool) $task->completed
                ],
                'links' => [
                    'self' => url("/api/tasks/{$task->id}")
                ]
            ]
        ]);
        
        $this->seeInDatabase('tasks', [
            'title' => $task->title
        ]);
    }
}
