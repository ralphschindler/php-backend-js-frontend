<?php

namespace ReminderApp\Resource;

use Nocarrier\Hal;
use ReminderApp\Model;

class CollectionResource implements ResourceInterface
{
    protected $resources = [];
    protected $uri = '';

    public function __construct(array $resources, $uri)
    {
        $this->resources = $resources;
        $this->uri = $uri;
    }

    public function getResponseCode()
    {
        return 200;
    }

    public function getHeaders()
    {
        return ['Content-type' => 'application/hal+json'];
    }

    public function getHalResource()
    {
        $hal = new Hal($this->uri, ['count' => count($this->resources)]);
        foreach ($this->resources as $resource) {
            switch (get_class($resource)) {
                case Model\Reminder::class:
                    $hal->addResource('reminders', (new ReminderResource($resource, $this->uri))->getHalResource());
                    break;
                case Model\Location::class:
                    $hal->addResource('locations', (new LocationResource($resource, $this->uri))->getHalResource());
                    break;
            }
        }
        return $hal;
    }

    public function getContent()
    {
        return $this->getHalResource()->asJson(true);
    }

}
