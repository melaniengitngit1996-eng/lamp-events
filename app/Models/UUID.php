<?php

namespace App\Models;

class UUID
{
    public static function issue(Event $event)
    {
        $set_id = $event->available_id_set;

        if (!$event->available_id_set) {
            $set_id = 1;
        }

        $range = AvailableUuid::available()->where('id', $set_id)->first();

        return $range->next();
    }
}
