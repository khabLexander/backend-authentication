<?php

namespace App\Traits;

use App\Models\Catalogue;
use App\Models\Location;
use App\Models\Phone;

trait PhoneTrait
{
    public function addPhones($phones)
    {
        if ($phones) {
            foreach ($phones as $phone) {
                $newPhone = $phone['id'] ? $this->phones()->find($phone['id']) : new Phone();
                $newPhone->phoneable()->associate($this);
                $newPhone->operator()->associate(Catalogue::find($phone['operator']['id']));
                $newPhone->location()->associate(Location::find($phone['location']['id']));
                $newPhone->number = $phone['number'];
                $newPhone->main = $phone['main'];
                $newPhone->save();
            }
        }
    }
}
