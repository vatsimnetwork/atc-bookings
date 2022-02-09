<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CallsignSearchFilter implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return preg_match("/[^0-9a-zA-Z-,_]/", $value) == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The :attribute query must only alphanumerical characters, underscores, dashes and commas';
    }
}
