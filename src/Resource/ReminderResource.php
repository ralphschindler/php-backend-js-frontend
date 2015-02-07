<?php


namespace ReminderApp\Resource;

use Nocarrier\Hal;
use ReminderApp\Model\Reminder;

class ReminderResource implements ResourceInterface
{
    protected $reminder = null;

    public function __construct(Reminder $reminder)
    {
        $this->reminder = $reminder;
    }

    public function getResponseCode()
    {
        return 200;
    }

    public function getHeaders()
    {
        return ['Content-Type' => 'application/hal+json'];
    }

    public function getContent()
    {
        return $this->getHalResource()->asJson(true);
    }

    public function getHalResource()
    {
        $hal = new Hal("/reminder/{$this->reminder->id}");
        $data = [
            'id' => $this->reminder->id,
            'text' => $this->reminder->text,
            'created_at' => $this->reminder->createdAt,
            'completed_at' => $this->reminder->completedAt,
        ];

        if ($this->reminder->remindAt) {
            $data['remind_at'] = $this->reminder->remindAt;
        }

        if ($this->reminder->remindNearLocation) {
            $data['remind_near_location_radius_in_feet'] = $this->reminder->remindNearLocationRadiusInFeet;
            $hal->addResource('remind_near_location', (new LocationResource($this->reminder->remindNearLocation))->getHalResource(), false);
        }

        $hal->setData($data);
        return $hal;
    }
}

