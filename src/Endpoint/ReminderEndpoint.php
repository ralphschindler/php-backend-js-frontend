<?php
    
namespace ReminderApp\Endpoint;

use ReminderApp\Model\Reminder;
use ReminderApp\Model\ReminderRepository;
use ReminderApp\Resource\CollectionResource;
use ReminderApp\Resource\ErrorResource;
use ReminderApp\Resource\ReminderResource;
use Symfony\Component\HttpFoundation\Request;

class ReminderEndpoint
{
    protected $request;
    protected $reminderRepo;

    public function __construct(Request $request, ReminderRepository $reminderRepository) {
        $this->request = $request;
        $this->reminderRepo = $reminderRepository;
    }

    public function execute($id) {
        if ($id == null) {
            return $this->collection();
        }
        return $this->entity($id);
    }

    public function collection() {
        $reminders = $this->reminderRepo->findAllCurrent();
        return new CollectionResource($reminders, '/reminder');
    }

    public function entity($id)
    {
        $reminder = $this->reminderRepo->findById($id);
        if (!$reminder) {
            return new ErrorResource(404, 'Entity by this id was not found');
        }

        $parameters = $this->request->request;

        switch ($this->request->getMethod()) {
            case 'PATCH':
                Reminder::updateFromArray($reminder, $parameters->all());
                $this->reminderRepo->store($reminder);
                break;
            case 'GET': break;
            default:
                return new ErrorResource(405, 'Only PATCH and GET are allowed on this resource');
        }
        return new ReminderResource($reminder, '/reminder');
    }
}