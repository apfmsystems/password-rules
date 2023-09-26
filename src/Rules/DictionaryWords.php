<?php

namespace Apfm\PasswordRules\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Class DictionaryWords.
 *
 * Implements the 'Dictionary words' recommendation
 * from NIST SP 800-63B section 5.1.1.2.
 */
class DictionaryWords implements Rule
{
    const DICTIONARY_FILE = __DIR__.'/../../resources/words.txt';

    private array $words = [];

    /**
     * DictionaryWords constructor.
     */
    public function __construct()
    {
        $this->words = explode("\n", file_get_contents(self::DICTIONARY_FILE));
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return !in_array(strtolower(trim($value)), $this->words);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return __('apfm-password-rules::validation.can-not-be-dictionary-word');
    }
}
