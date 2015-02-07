<?php

namespace ReminderApp\Resource;

use Nocarrier\Hal;
use ReminderApp\Model\Location;

class LocationResource implements ResourceInterface
{
    protected $location = null;

    public function __construct(Location $location)
    {
        $this->location = $location;
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

    /**
     * @return Hal
     */
    public function getHalResource()
    {
        $hal = new Hal("/location/{$this->location->id}");
        $hal->setData([
            'address' => $this->location->address,
            'longitude' => $this->location->longitude,
            'latitude' => $this->location->latitude
        ]);
        return $hal;
    }
}

