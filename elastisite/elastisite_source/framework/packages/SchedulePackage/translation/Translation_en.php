<?php

namespace framework\packages\SchedulePackage\translation;

class Translation_en
{
    public function getTranslation()
    {
        return array(
            'calendar.administration' => 'Calendar\'s administration',
            'new.event' => 'Create event',
            'event.save.changes' => 'Save event',
            'datetime.required' => 'Date format required (yy-mm-dd hh:mi)',
            'start.date.also.must.to.be.datetime' => 'Date format required for start date also',
            'end.date.must.be.later.than.start.date' => 'End date must be later than start date',
            'start.date' => 'Start date',
            'end.date' => 'End date',
            'max.subscribers' => 'Max. subscribers',
            'already.have.an.appointment' => 'Do you have an appointment already?'
        );
    }
}
