<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Tests\Integration\Taskrouter\V1\Workspace\TaskQueue;

use Twilio\Exceptions\DeserializeException;
use Twilio\Exceptions\TwilioException;
use Twilio\Http\Response;
use Twilio\Tests\HolodeckTestCase;
use Twilio\Tests\Request;

class TaskQueueCumulativeStatisticsTest extends HolodeckTestCase {
    public function testFetchRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->taskrouter->v1->workspaces("WSaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa")
                                         ->taskQueues("WQaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa")
                                         ->cumulativeStatistics()->fetch();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'get',
            'https://taskrouter.twilio.com/v1/Workspaces/WSaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/TaskQueues/WQaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/CumulativeStatistics'
        ));
    }

    public function testFetchResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "reservations_created": 100,
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "reservations_rejected": 100,
                "tasks_completed": 100,
                "end_time": "2015-07-30T20:00:00Z",
                "tasks_entered": 100,
                "tasks_canceled": 100,
                "reservations_accepted": 100,
                "task_queue_sid": "WQaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "reservations_timed_out": 100,
                "url": "https://taskrouter.twilio.com/v1/Workspaces/WSaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/TaskQueues/WQaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/CumulativeStatistics",
                "wait_duration_until_canceled": {
                    "avg": 0,
                    "min": 0,
                    "max": 0,
                    "total": 0
                },
                "wait_duration_until_accepted": {
                    "avg": 0,
                    "min": 0,
                    "max": 0,
                    "total": 0
                },
                "split_by_wait_time": {
                    "5": {
                        "above": {
                            "tasks_canceled": 0,
                            "reservations_accepted": 0
                        },
                        "below": {
                            "tasks_canceled": 0,
                            "reservations_accepted": 0
                        }
                    },
                    "10": {
                        "above": {
                            "tasks_canceled": 0,
                            "reservations_accepted": 0
                        },
                        "below": {
                            "tasks_canceled": 0,
                            "reservations_accepted": 0
                        }
                    }
                },
                "start_time": "2015-07-30T20:00:00Z",
                "tasks_moved": 100,
                "reservations_canceled": 100,
                "workspace_sid": "WSaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "tasks_deleted": 100,
                "reservations_rescinded": 100,
                "avg_task_acceptance_time": 100
            }
            '
        ));

        $actual = $this->twilio->taskrouter->v1->workspaces("WSaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa")
                                               ->taskQueues("WQaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa")
                                               ->cumulativeStatistics()->fetch();

        $this->assertNotNull($actual);
    }
}