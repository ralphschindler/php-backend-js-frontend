<?php

namespace ReminderApp\Model;

class ReminderRepository
{
    protected $db;
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    /**
     * @return Reminder[]
     */
    public function findAllCurrent()
    {
        return $this->find('completed_at IS NULL ORDER BY remind_at');
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
        $stmt = $this->db->prepare('SELECT * FROM reminder WHERE ' . $whereSql);
        $stmt->execute($parameters);
        /** @var Reminder[] $reminders */
        $reminders = [];
        $ids = [];
        while ($reminderRow = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $ids[] = $reminderRow['id'];
            $reminder = new Reminder;
            $reminder->id = $reminderRow['id'];
            $reminder->text = $reminderRow['text'];
            $reminder->createdAt = $reminderRow['created_at'];
            $reminder->remindAt = $reminderRow['remind_at'];
            $reminders[$reminderRow['id']] = $reminder;
        }
        if ($ids) {
            $idSql = '(' . implode(', ', $ids) . ')';
            $stmt = $this->db->prepare("
              SELECT reminder_id, radius_in_feet, id, address, longitude, latitude
              FROM reminder_location
              LEFT JOIN location ON reminder_location.location_id = location.id
              WHERE reminder_id IN $idSql
            ");
            $stmt->execute();
            while ($locationRow = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $reminder = $reminders[$locationRow['reminder_id']];
                $reminder->remindNearLocationRadiusInFeet = $locationRow['radius_in_feet'];
                $location = new Location;
                $location->id = $locationRow['id'];
                $location->address = $locationRow['address'];
                $location->longitude = $locationRow['longitude'];
                $location->latitude = $locationRow['latitude'];
                $reminder->remindNearLocation = $location;
                $reminder->remindNearLocationId = $location->id;
            }
        }
        return array_values($reminders);
    }

    public function store(Reminder $reminder)
    {
        if (!$reminder->id) {
            throw new \InvalidArgumentException('Missing an id for reminder');
        }
        $sql = 'UPDATE reminder SET text = :text WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $reminder->id, 'text' => $reminder->text]);
    }

    public function remove(Reminder $reminder)
    {

    }

}