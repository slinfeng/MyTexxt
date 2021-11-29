<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FileMimeType implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
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
        if(is_file($value)){
            $fileAbleArr = ['image/jpeg','image/png','image/gif','application/pdf','text/plain',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/msword'];
            if(in_array($value->getClientMimeType(),$fileAbleArr)){
                return true;
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
        return 'サポートされているファイルタイプは「jpeg,jpg,png,gif,pdf,txt,xls,xlsx,pdf,doc,docx」に限定されています。';
    }
}
