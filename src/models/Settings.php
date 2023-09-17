<?php

/**
 * @copyright Copyright (c) Orbital Flight
 */

namespace orbitalflight\celigo\models;

use craft\base\Model;
use craft\helpers\App;

/**
 * Celigo settings
 */
class Settings extends Model {
    // === Properties ===
    public array $apis = [];
    public int $timeout = 30;

    // === Methods ===
    public function getMaxExecutionTime(): int {
        return ini_get('max_execution_time');
    }

    /**
     * validateApis
     * This function is used to validate the user api credentials
     *
     * @param  mixed $attribute
     * @return void
     */
    public function validateApis(mixed $attribute): void {

        if (empty($this->apis)) {
            $this->addError($attribute, "API infos cannot be empty.");
            return;
        }

        foreach ($this->apis as $api) {
            foreach ($api as $currentData) {
                if ($currentData === '') {
                    $this->addError($attribute, "All fields must be filled.");
                    return;
                }
            }

            // Credential content verification
            $hexadecimalRegex = '/^[0-9a-fA-F]+$/';
            $alphaDashRegex = '/^[a-zA-Z\-]+$/';

            if (!preg_match($alphaDashRegex, $api[0])) {
                $this->addError($attribute, "The handle must consist of only letters (a-z, A-Z) and dashes (-).");
                return;
            }

            if (strlen($api[0]) > 32) {
                $this->addError($attribute, "The handle cannot exceed a maximum of 32 characters.");
                return;
            }


            if (!preg_match($hexadecimalRegex, App::parseEnv($api[1])) || !preg_match($hexadecimalRegex, App::parseEnv($api[2]))) {
                $this->addError($attribute, "API ID and token must be hexadecimal.");
                return;
            }
        }
    }

    // === Rules ===
    protected function defineRules(): array {
        $maxTime = $this->getMaxExecutionTime();
        return [
            [['timeout', 'apis'], 'required'],
            [['timeout'], 'integer'],
            [['timeout'], 'in', 'range' => range(5, $maxTime), 'message' => "Timeout must be in the 5 - " . $maxTime . " seconds range."],
            [['apis'], 'validateApis'],
        ];
    }
}