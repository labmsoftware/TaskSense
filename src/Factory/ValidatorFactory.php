<?php

declare(strict_types = 1);

namespace App\Factory;

use Valitron\Validator;
use App\Support\Settings\Settings;

final class ValidatorFactory
{
    private string $rulesPath;

    public function __construct(Settings $settings)
    {
        $this->rulesPath = $settings->get('validator.rules_path');
    }

    public function new(string $rulesFile, array $data): ?Validator
    {
        $validator = new Validator($data);

        $validator->rules($this->getRules($rulesFile));

        return $validator;
    }

    /**
     * !! Get rules from YAML file and return as array?
     *
     * @return void
     */
    public function getRules(string $rulesFile): void
    {
        
    }
}