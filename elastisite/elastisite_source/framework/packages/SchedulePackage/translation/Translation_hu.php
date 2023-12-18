<?php

namespace framework\packages\SchedulePackage\translation;

class Translation_hu
{
    public function getTranslation()
    {
        return array(
            'calendar.administration' => 'Naptár adminisztrációja',
            'new.event' => 'Esemény létrehozása',
            'event.save.changes' => 'Esemény mentése',
            'datetime.required' => 'Dátum formátum szükséges (éé-hh-nn óó:pp)',
            'start.date.also.must.to.be.datetime' => 'A kezdődátumnak is dátum formátumúnak kell lennie',
            'end.date.must.be.later.than.start.date' => 'A végdátum legyen későbbi, mint a kezdődátum',
            'start.date' => 'Kezdődátum',
            'end.date' => 'Végdátum',
            'max.subscribers' => 'Max. feliratkozók'
        );
    }
}
