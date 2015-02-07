<?php

namespace ReminderApp\Model;

class LocationRepository
{
    protected $db;
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    /**
     * @return Reminder[]
     */
    public function findAll()
    {
        return $this->find('1 = 1');
    }

    /**
     * @param $id
     * @return Reminder
     */
    public function findById($id)
    {
        $reminders = $this->find('id = :id', ['id' => $id]);
        if (!$reminders) {
            return false;
        }
        return $reminders[0];
    }

    protected function find($whereSql, $parameters = [])
    {
        $stmt = $this->db->prepare('SELECT id, address, longitude, latitude FROM location WHERE ' . $whereSql);
        $stmt->execute($parameters);
        /** @var Reminder[] $locations */
        $locations = [];
        $ids = [];
        while ($locationRow = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $ids[] = $locationRow['id'];
            $location = new Location;
            $location->id = $locationRow['id'];
            $location->address = $locationRow['address'];
            $location->longitude = $locationRow['longitude'];
            $location->latitude = $locationRow['latitude'];
            $locations[] = $location;
        }
        return $locations;
    }
}