<?php

namespace projects\ASC\entity;

use App;
use framework\component\parent\DbEntity;
use framework\packages\EventPackage\entity\CalendarEvent;
use framework\packages\EventPackage\service\CalendarEventFactory;
use framework\packages\UserPackage\entity\UserAccount;

class AscUnit extends DbEntity
{
    /**
     * Primary key
    */
    protected $id;

    /**
     * @var AscScale
    */
    protected $ascScale;

    /**
     * @var CalendarEvent
    */
    protected $calendarEvent;

    /**
     * @var AscEntryHead
    */
    protected $ascEntryHead;

    /**
     * @var AscUnit
    */
    protected $parent;

    /**
     * @var int
    */
    protected $sequenceNumber;

    /**
     * @var string
    */
    protected $subject;

    /**
     * @var string
    */
    protected $position;

    /**
     * @var \DateTime
    */
    protected $createdAt;

    /**
     * @var UserAccount
    */
    protected $responsible;

    /**
     * @var string
    */
    protected $administrationStance;

    /**
     * @var \DateTime
    */
    protected $modifiedAt;

    /**
     * @var int
    */
    protected $status;

    const POSITION_LEFT = 'Left';
    const POSITION_RIGHT = 'Right';

    const STATUS_INACTIVE = 0;
	const STATUS_ACTIVE = 1;
    const STATUS_CLOSED_SUCCESSFUL = 10;
    const STATUS_CLOSED_FAILED = 20;

    const ADMINISTRATION_STANCE_PHONE_CALL = 'PhoneCall';
    const ADMINISTRATION_STANCE_UNSUCCESSFUL_PHONE_CALL = 'UnsuccessfulPhoneCall';
    const ADMINISTRATION_STANCE_PERSONAL = 'Personal';
    const ADMINISTRATION_STANCE_CORRESPONDENCE = 'Correspondence';
    const ADMINISTRATION_STANCE_CONCEPT_CREATION = 'ConceptCreation';

    const DUE_CALENDAR_EVENT_TYPE = 'AscDueDate';

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `asc_unit` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `asc_scale_id` int(11) DEFAULT NULL,
    `calendar_event_id` int(11) DEFAULT NULL,
    `parent_id` int(11) DEFAULT NULL,
    `sequence_number` int(11) DEFAULT NULL,
    `subject` varchar(100) DEFAULT NULL,
    `position` varchar(100) DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `responsible` int(11) DEFAULT NULL,
    `administration_stance` varchar(50) DEFAULT NULL,
    `modified_at` datetime DEFAULT NULL,
    `status` int(2) DEFAULT 1,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=215000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    const ENTITY_ATTRIBUTES = [
        'passOverMissingFields' => ['created_by_id', 'responsible_id'],
        'passOverUnnecessaryFields' => ['created_by', 'responsible'],
    ];

    private static $dueEventFactory;

    public function __construct()
    {
        $this->position = self::POSITION_LEFT;
        $this->createdAt = $this->getCurrentTimestamp();
        $this->status = self::STATUS_ACTIVE;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setAscScale(AscScale $ascScale = null)
    {
        $this->ascScale = $ascScale;
    }

    public function getAscScale()
    {
        return $this->ascScale;
    }

    public function setCalendarEvent(CalendarEvent $calendarEvent = null)
    {
        $this->calendarEvent = $calendarEvent;
    }

    public function getCalendarEvent()
    {
        return $this->calendarEvent;
    }

    public function getDueEventFactory()
    {
        App::getContainer()->wireService('EventPackage/service/CalendarEventFactory');

        if (!self::$dueEventFactory) {
            self::$dueEventFactory = new CalendarEventFactory($this->calendarEvent);
        }

        return self::$dueEventFactory;
    }

    public function setAscEntryHead(AscEntryHead $ascEntryHead = null)
    {
        $this->ascEntryHead = $ascEntryHead;
    }

    public function getAscEntryHead()
    {
        return $this->ascEntryHead;
    }

    public function setParent(AscUnit $parent = null)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setSequenceNumber(int $sequenceNumber = null)
    {
        $this->sequenceNumber = $sequenceNumber;
    }

    public function getSequenceNumber()
    {
        return $this->sequenceNumber;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function getPosition()
    {
        return $this->position;
    }

    // public function setDueType($dueType)
    // {
    //     $this->dueType = $dueType;
    // }

    // public function getDueType()
    // {
    //     return $this->dueType;
    // }

    // public function setRecurrencePattern($recurrencePattern)
    // {
    //     $this->recurrencePattern = $recurrencePattern;
    // }

    // public function getRecurrencePattern()
    // {
    //     return $this->recurrencePattern;
    // }

    // public function setDueDate($dueDate)
    // {
    //     $this->dueDate = $dueDate;
    // }

    // public function getDueDate()
    // {
    //     return $this->dueDate;
    // }

    // public function setDueTime($dueTime)
    // {
    //     $this->dueTime = $dueTime;
    // }

    // public function getDueTime()
    // {
    //     return $this->dueTime;
    // }

    // public function setCreatedBy(UserAccount $createdBy)
    // {
    //     $this->createdBy = $createdBy;
    // }

    // public function getCreatedBy()
    // {
    //     return $this->createdBy;
    // }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setResponsible(UserAccount $responsible = null)
    {
        $this->responsible = $responsible;
    }

    public function getResponsible()
    {
        return $this->responsible;
    }

    public function setAdministrationStance($administrationStance)
    {
        $this->administrationStance = $administrationStance;
    }

    public function getAdministrationStance()
    {
        return $this->administrationStance;
    }

    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;
    }

    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }
}