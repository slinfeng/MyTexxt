<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class Period implements Rule
{
    protected $period;

    /**
     * Create a new rule instance.
     *
     * @param $period
     */
    public function __construct($period='作業期間')
    {
        $this->period=$period;
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        if(strstr($value,'～')){
            $dateArr=explode('～',$value);
            if(count($dateArr)==2){
                $date1=Carbon::parse($dateArr[0]);
                $date2=Carbon::parse($dateArr[1]);
                return $date2>$date1;
            }
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->period.'を選択してください。';
    }
}
