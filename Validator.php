<?php

namespace App;

use App\Exceptions\ValidatorException;
use Exception;

class Validator
{
    private string $current_field;

    public function __construct(
        private readonly array $rules,
        private array          $form_values,
    )
    {
    }

    /**
     * Validate the current request.
     * @throws ValidatorException
     */
    public function validate(): bool
    {
        foreach ($this->rules as $field => $requested_rules) {
            $this->current_field = $field;

            foreach ($requested_rules as $rule) {
                $rule_name = explode(":", $rule)[0];

                $function_name = "is_$rule_name";

                if (method_exists($this::class, $function_name)) {
                    $rule_arguments = explode(":", $rule);
                    array_shift($rule_arguments);

                    try {
                        if (empty($rule_arguments)) !$this->$function_name($this->form_values[$field]);
                        else !$this->$function_name($this->form_values[$field], $rule_arguments[0]);
                    } catch (Exception $e) {
                        throw new ValidatorException(message: $e->getMessage(), field: $field);
                    }
                }
            }
        }

        return true;
    }

    /**
     * Check whether the value is unique or not.
     * @throws ValidatorException
     */
    private function is_unique(string $value): bool
    {
        $users = json_decode(
            file_get_contents(DATA_LOCATION)
        );

        if (in_array($value, array_column($users, $this->current_field))) {
            throw new ValidatorException("Já existe um \"$this->current_field\" cadastrado com este valor: \"$value\"!");
        }

        return true;
    }

    /**
     * Check the value minimum length.
     * @throws ValidatorException
     */
    private function is_min(string $value, int $min): bool
    {
        if (strlen($value) < $min) {
            throw new ValidatorException("Campo \"$this->current_field\" não pôde ser validado com tamanho minimo de $min caracteres!");
        }

        return true;
    }

    /**
     * Check the value equals a form value.
     * @throws ValidatorException
     */
    private function is_equals(string $value, string $field_name): bool
    {
        if ($value !== $this->form_values[$field_name]) {
            throw new ValidatorException("\"$value\" não corresponde com campo: \"$field_name\"!");
        }

        return true;
    }
}

