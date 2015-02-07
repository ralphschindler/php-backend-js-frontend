<?php

namespace ReminderApp\Resource;

use Nocarrier\Hal;

class ErrorResource implements ResourceInterface
{
    protected $statusCode;
    protected $message;

    public function __construct($statusCode, $message)
    {
        $this->statusCode = $statusCode;
        $this->message = $message;
    }

    public function getResponseCode()
    {
        return $this->statusCode;
    }

    public function getHeaders()
    {
        return ['Content-Type' => 'application/vnd.error+json'];
    }

    public function getContent()
    {
        return $this->getHalResource()->asJson();
    }

    public function getHalResource()
    {
        $hal = new Hal();
        $hal->setData([
            'message' => $this->message,
        ]);
        return $hal;
    }
}
