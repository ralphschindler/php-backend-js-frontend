<?php

namespace ReminderApp\Resource;

use Nocarrier\Hal;

class IndexResource implements ResourceInterface
{

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
        return $this->getHalResource()->asJson();
    }

    public function getHalResource()
    {
        $hal = new Hal("/");
        $hal->addLink('ra:reminders', '/reminder', ['title' => 'Reminders']);
        $hal->addLink('ra:locations', '/location', ['title' => 'Locations']);
        $hal->addCurie('ra', '/relations/{rel}.html');
        return $hal;

        // TODO: Implement getHalResource() method.
    }
}
