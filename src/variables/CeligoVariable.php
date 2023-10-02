<?php

/**
 * @copyright Copyright (c) Orbital Flight
 */

namespace orbitalflight\celigo\variables;

use orbitalflight\celigo\Celigo;

class CeligoVariable {
    
    /**
     * _call
     * Gathers the informations and send them to the service
     *
     * @param  mixed $handle
     * @param  mixed $action
     * @param  mixed $params
     * @return array
     */
    private function _call(string $handle = null, string $action, array $params = null): array {
        return Celigo::getInstance()->service->call($handle, $action, $params);
    }

    public function delete(string $handle = null, array $params = null): array {
        return $this->_call($handle, "DELETE", $params);
    }
    
    public function get(string $handle = null, array $params = null): array {
        return $this->_call($handle, "GET", $params);
    }

    public function patch(string $handle = null, array $params = null): array {
        return $this->_call($handle, "PATCH", $params);
    }

    public function post(string $handle = null, array $params = null): array {
        return $this->_call($handle, "POST", $params);
    }

    public function put(string $handle = null, array $params = null): array {
        return $this->_call($handle, "PUT", $params);
    }

    public function update(string $handle = null, array $params = null): array {
        return $this->_call($handle, "UPDATE", $params);
    }
}
