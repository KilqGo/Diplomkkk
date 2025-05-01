<?php
class validator {
    private $errors = [];
    private $db;
    private $validationRules;

    public function __construct(mysqli $db, array $validationRules) {
        $this->db = $db;
        $this->validationRules = $validationRules;
    }

    public function validate(array $data, string $table): array {
        $this->errors = [];
        
        if (!isset($this->validationRules[$table])) {
            throw new Exception("Validation rules for table '$table' not found");
        }

        $rules = $this->validationRules[$table];

        foreach ($rules as $field => $ruleConfig) {
            $value = $data[$field] ?? '';
            $value = is_string($value) ? trim($value) : $value;

            if (!empty($ruleConfig['required']) && empty($value)) {
                $this->addError(
                    $field,
                    $ruleConfig['messages']['required'] ?? "Field $field is required"
                );
                continue;
            }

            if (isset($ruleConfig['type'])) {
                $this->validateType($field, $value, $ruleConfig);
            }


            if (isset($ruleConfig['max'])) {
                $this->validateMaxLength($field, $value, $ruleConfig);
            }

            if (isset($ruleConfig['pattern'])) {
                $this->validatePattern($field, $value, $ruleConfig);
            }

            if (isset($ruleConfig['foreign'])) {
                $this->validateForeign($field, $value, $ruleConfig);
            }
        }

        return $this->errors;
    }

    private function validateType(string $field, $value, array $ruleConfig): void {
        $type = $ruleConfig['type'];
        $message = $ruleConfig['messages']['type'] ?? "Invalid format for $field";

        switch ($type) {
            case 'numeric':
                if (!is_numeric($value)) {
                    $this->addError($field, $message);
                }
                break;

            case 'date':
                if (!DateTime::createFromFormat('Y-m-d', $value)) {
                    $this->addError($field, $message);
                }
                break;
        }
    }

    private function validateMaxLength(string $field, $value, array $ruleConfig): void {
        $max = $ruleConfig['max'];
        $message = $ruleConfig['messages']['max'] ?? "Max length for $field is $max";

        if (strlen((string)$value) > $max) {
            $this->addError($field, $message);
        }
    }

    private function validatePattern(string $field, $value, array $ruleConfig): void {
        $pattern = $ruleConfig['pattern'];
        $message = $ruleConfig['messages']['pattern'] ?? "Invalid format for $field";

        if (!preg_match($pattern, $value)) {
            $this->addError($field, $message);
        }
    }

    private function validateForeign(string $field, $value, array $ruleConfig): void {
        [$foreignTable, $foreignColumn] = explode('.', $ruleConfig['foreign']);
        $message = $ruleConfig['messages']['foreign'] ?? "Invalid reference to $foreignTable";

        if (!$this->existsInTable($foreignTable, $foreignColumn, $value)) {
            $this->addError($field, $message);
        }
    }

    private function addError(string $field, string $message): void {
        $this->errors[$field] = $message;
    }

    private function existsInTable(string $table, string $column, $value): bool {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM $table WHERE $column = ?");
        $stmt->bind_param('s', $value);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0;
    }
}
?>