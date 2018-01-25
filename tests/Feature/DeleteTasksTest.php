<?php

namespace Tests\Feature;

use App\Task;
use Tests\TestCase;
use \Laravel\Lumen\Testing\DatabaseMigrations;

class DeleteTasksTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_delete_a_task()
    {
        $task = factory(Task::class)->create();

        $response = $this->call('DELETE', "/api/tasks/{$task->id}");

        $this->assertEquals(200, $response->status());

        $this->notSeeInDatabase('tasks', [
            'id' => $task->id
        ]);
    }

    /** @test */
    public function when_trying_to_delete_a_non_existing_task_a_404_response_is_returned()
    {
        $response = $this->call('DELETE', '/api/tasks/12345');

        $this->assertEquals(404, $response->status());

        $this->seeJsonEquals([
            'errors' => [
                'status' => 404,
                'title' => 'Resource Not Found',
                'details' => 'Cannot find Resource with the id 12345'
            ]
        ]);
    }
}
