<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RuleStringMax implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    private $max = null;

    public function __construct($max)
    {
        $this->max = $max;
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
        if (strlen($value) > $this->max){
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
        return 'O campo :attribute não possui o limite permitido de caracteres!';
    }
}
