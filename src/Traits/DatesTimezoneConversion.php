<?php

namespace DivineOmega\DatesTimezoneConversion\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait DatesTimezoneConversion
{

    /**
     * Overrides getAttributeValue, and convert any dates
     * to the user's timezone.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttributeValue($key)
    {
        /** @var Carbon $value */
        $value = parent::getAttributeValue($key);

        if ($this->isDateObject($key, $value)) {

            /** @var Model $user */
            $user = Auth::user();

            if ($user) {
                $value->setTimezone($user->getAttributeValue('timezone'));
            }

        }

        return $value;
    }

    /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    public function setAttribute($key, $value)
    {
        if ($this->isDateObject($key, $value)) {
            $value->setTimezone(config('app.timezone'));
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Checks if a date is part of the model's dates array,
     * is an object, and is a Carbon instance.
     *
     * @param $key
     * @param $value
     * @return bool
     */
    private function isDateObject($key, $value)
    {
        return in_array($key, $this->getDates()) &&
            is_object($value) &&
            get_class($value) === Carbon::class;
    }

}