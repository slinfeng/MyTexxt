<?php

namespace App\Rules;

use App\Models\Client;
use Illuminate\Contracts\Validation\Rule;

class ClientName implements Rule
{
    protected $id;

    /**
     * Create a new rule instance.
     *
     * @param string $id
     */
    public function __construct($id='')
    {
        $this->id = $id;
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
        $client_infos = Client::whereNotIn('id',[$this->id])->where('our_role',$_POST['our_role'])->select('client_abbreviation','client_name')->get();
        $client_name = $_POST['client_name'];
        $client_abbreviation = $_POST['client_abbreviation'];
        foreach ($client_infos as $client_info){
            if($client_info->client_name == $client_name && $client_info->client_abbreviation == $client_abbreviation){
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '当該立場の取引先略称「'.$_POST['client_abbreviation'].'」と取引先名「'.$_POST['client_name'].'」は既に存在しました！';
    }
}
