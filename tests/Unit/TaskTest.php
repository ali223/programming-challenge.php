<?php

namespace Tests\Unit;

use App\Task;
use Tests\TestCase;

class TaskTest extends TestCase
{
    /** @test */
    public function a_task_has_title_and_completed_fields()
    {
        $task = factory(Task::class)->make([
                    'title' => 'Test task',
                    'completed' => true
                ]);

        $this->assertEquals('Test task', $task->title);
        $this->assertEquals(true, $task->completed);
    }
}
