<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Amount implements Rule
{
    protected $amountName;
    protected $zeroAllow;

    /**
     * Create a new rule instance.
     *
     * @param string $amountName
     * @param bool $zeroAllow
     */
    public function __construct($amountName,$zeroAllow=false)
    {
        //
        $this->amountName=isset($amountName)?$amountName:':attribute';
        $this->zeroAllow=$zeroAllow;
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
        $amount=mb_ereg_replace('[^0-9]','',$value);
        if (is_numeric($amount)) {
            if($this->zeroAllow){
                return true;
            }
            if($amount==0){
                return false;
            }
            return true;
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
        return $this->amountName.'は必ず指定してください。';
    }
}
