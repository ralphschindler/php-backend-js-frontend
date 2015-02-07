<?php

namespace ReminderApp\Endpoint;

use ReminderApp\Resource\IndexResource;

class IndexEndpoint
{
    public function execute()
    {
        return new IndexResource();
    }
}
