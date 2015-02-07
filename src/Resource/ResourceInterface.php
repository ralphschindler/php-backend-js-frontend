<?php

namespace ReminderApp\Resource;

interface ResourceInterface
{
    public function getResponseCode();
    public function getHeaders();
    public function getContent();
    public function getHalResource();
}