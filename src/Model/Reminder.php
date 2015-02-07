<?php

namespace ReminderApp\Model;

class Reminder
{
    public $id;
    public $text;
    public $createdAt;
    public $remindAt;
    public $remindNearLocationRadiusInFeet;
    public $remindNearLocationId;
    public $remindNearLocation;
    public $completedAt;

    public static function updateFromArray(Reminder $reminder, array $data, $resetFirst = false)
    {
        if (isset($data['text'])) {
            $reminder->text = $data['text'];
        }
        // other mappings here
    }

    public function markCompleted()
    {

    }

}