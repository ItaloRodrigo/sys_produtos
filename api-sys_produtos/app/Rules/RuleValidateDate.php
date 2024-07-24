<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RuleValidateDate implements Rule
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
        $date_value = \Carbon\Carbon::parse($value)->format('dd/mm/Y');
        $date_new   = \Carbon\Carbon::parse(now())->format('dd/mm/Y');
        //---
        if($date_value < $date_new){
            return false;
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
        return 'O campo :attribute não pode ser menor que a data atual!';
    }
}
