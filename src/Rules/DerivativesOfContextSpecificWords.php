<?php

namespace Apfm\PasswordRules\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Class DerivativesOfContextSpecificWords.
 *
 * Implements the 'Context-specific words' recommendation with 'derivatives thereof'.
 * from NIST SP 800-63B section 5.1.1.2.
 */
class DerivativesOfContextSpecificWords extends ContextSpecificWords implements Rule
{
    private ?string $detectedWord = null;

    /**
     * DerivativesOfContextSpecificWords constructor.
     *
     * @param $username
     */
    public function __construct($username)
    {
        parent::__construct($username);
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
        $value = strtolower($value);

        foreach ($this->words as $word) {
            similar_text($value, $word, $percentage);

            if ($percentage >= 75) {
                $this->detectedWord = $word;

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
    public function message(): string
    {
        return __('apfm-password-rules::validation.can-not-be-similar-to-word', ['word' => $this->detectedWord]);
    }
}
