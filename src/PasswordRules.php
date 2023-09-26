<?php

namespace Apfm\PasswordRules;

use Apfm\PasswordRules\Rules\ContextSpecificWords;
use Apfm\PasswordRules\Rules\DerivativesOfContextSpecificWords;
use Apfm\PasswordRules\Rules\DictionaryWords;
use Apfm\PasswordRules\Rules\RepetitiveCharacters;
use Apfm\PasswordRules\Rules\SequentialCharacters;

abstract class PasswordRules
{
    public static function register($username, $requireConfirmation = true): array
    {
        $rules = [
            'required',
            'string',
            'min:8',
        ];

        if ($requireConfirmation) {
            $rules[] = 'confirmed';
        }

        return array_merge($rules, [
            new SequentialCharacters(),
            new RepetitiveCharacters(),
            new DictionaryWords(),
            new ContextSpecificWords($username),
            new DerivativesOfContextSpecificWords($username)
        ]);
    }

    public static function changePassword($username, $oldPassword = null): array
    {
        $rules = self::register($username);

        if ($oldPassword) {
            $rules = array_merge($rules, [
                'different:'.$oldPassword,
            ]);
        }

        return $rules;
    }

    public static function optionallyChangePassword($username, $oldPassword = null): array
    {
        $rules = self::changePassword($username, $oldPassword);

        $rules = array_merge($rules, [
            'nullable',
        ]);

        foreach ($rules as $key => $rule) {
            if (is_string($rule) && $rule === 'required') {
                unset($rules[$key]);
            }
        }

        return $rules;
    }

    public static function login(): array
    {
        return [
            'required',
            'string',
        ];
    }
}
