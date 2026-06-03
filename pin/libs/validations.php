<?php
/**
 * Lista de validaciones
 * r, requerido
 * lmin:99, largo mínimo
 * lmax:99, largo máximo
 * e, email
 * i, integer
 * f, float
 */

/**
 * Validates data based on provided rules.
 * 
 * Expected structure for $data_and_rules:
 * [
 *     'field_name' => [
 *         'value' => 'some value',
 *         'rules' => 'r|lmin:5|e'
 *     ]
 * ]
 * 
 * @param array $data_and_rules
 * @return bool
 */
function is_valid(array $data_and_rules): bool {
    foreach ($data_and_rules as $field => $config) {
        if (!isset($config['value']) || !isset($config['rules'])) {
            continue;
        }
        
        $value = $config['value'];
        $rules = explode('|', $config['rules']);
        
        foreach ($rules as $rule) {
            // Handle rules with arguments (e.g., lmin:99)
            if (strpos($rule, ':') !== false) {
                list($ruleName, $ruleParam) = explode(':', $rule);
            } else {
                $ruleName = $rule;
                $ruleParam = null;
            }

            switch ($ruleName) {
                case 'r':
                    if ($value === null || $value === '') return false;
                    break;
                case 'lmin':
                    if (strlen($value) < (int)$ruleParam) return false;
                    break;
                case 'lmax':
                    if (strlen($value) > (int)$ruleParam) return false;
                    break;
                case 'e':
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) return false;
                    break;
                case 'i':
                    if (!filter_var($value, FILTER_VALIDATE_INT) && $value !== '0' && $value !== 0) return false;
                    break;
                case 'f':
                    if (!filter_var($value, FILTER_VALIDATE_FLOAT) && $value !== '0' && $value !== 0) return false;
                    break;
            }
        }
    }
    return true;
}
