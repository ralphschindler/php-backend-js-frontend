<?php
    
namespace ReminderApp\Endpoint;

use ReminderApp\Model\LocationRepository;
use ReminderApp\Resource\CollectionResource;
use ReminderApp\Resource\LocationResource;

class LocationEndpoint
{
    protected $locationRepo;

    public function __construct(LocationRepository $locationRepository) {
        $this->locationRepo = $locationRepository;
    }

    public function execute($id) {
        if ($id == null) {
            return $this->collection();
        }
        return $this->entity($id);
    }

    public function collection() {
        $locations = $this->locationRepo->findAll();
        return new CollectionResource($locations, '/location');
    }

    public function entity($id)
    {
        $location = $this->locationRepo->findById($id);
        if (!$location) {
            return new ErrorResource();
        }
        return new LocationResource($location);
    }
}