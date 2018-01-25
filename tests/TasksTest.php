<?php

namespace Tests;

use App\Task;
use \Laravel\Lumen\Testing\DatabaseMigrations;

class TasksTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_view_a_single_task_in_json_api_format()
    {
        $task = factory(Task::class)->create();

        $this->json('GET', "/api/tasks/{$task->id}")
            ->seeJsonEquals([
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
    }

    /** @test */
    public function a_user_can_view_all_tasks_in_json_api_format()
    {
        $tasks = factory(Task::class, 2)->create();

        $this->json('GET', "/api/tasks")
            ->seeJsonEquals([
                'links' => [
                    'self' => url('/api/tasks')
                ],
                'data' => [
                    [
                        'type' => 'tasks',
                        'id' => (string) $tasks[0]->id,
                        'attributes' => [
                            'title' => $tasks[0]->title,
                            'completed' => (bool) $tasks[0]->completed,
                        ],
                        'links' => [
                            'self' => url("/api/tasks/{$tasks[0]->id}")
                        ]
                    ],
                    [
                        'type' => 'tasks',
                        'id' => (string) $tasks[1]->id,
                        'attributes' => [
                            'title' => $tasks[1]->title,
                            'completed' => (bool) $tasks[1]->completed,
                        ],
                        'links' => [
                            'self' => url("/api/tasks/{$tasks[1]->id}")
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function a_user_can_add_a_new_task_in_json_api_format()
    {
        $task = factory(Task::class)->make();

        $response = $this->call('POST', "/api/tasks", [
                        'data' => [
                            'type' => 'tasks',
                            'attributes' => [
                                'title' => $task->title,
                                'completed' => (bool) $task->completed
                            ],
                        ]
                    ]);

        $this->assertEquals(201, $response->status());

        $this->seeJsonEquals([
            'data' => [
                'type' => 'tasks',
                'id' => "1",
                'attributes' => [
                    'title' => $task->title,
                    'completed' => (bool) $task->completed
                ],
                'links' => [
                    'self' => url("/api/tasks/1")
                ]
            ]
        ]);

        $this->seeInDatabase('tasks', [
            'title' => $task->title
        ]);
    }

    /** @test */
    public function when_adding_a_new_task_title_and_completed_values_are_required()
    {
        $task = factory(Task::class)->make();

        $response = $this->call('POST', "/api/tasks", [
                        'data' => [
                            'type' => 'tasks',
                            'attributes' => [
                                'title' => '',
                                'completed' => ''
                            ],
                        ]
                    ]);

        $this->assertEquals(422, $response->status());

        $this->seeJson([
            'status' => 422,
            'title' => 'The given data was invalid.'
        ]);
    }

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
