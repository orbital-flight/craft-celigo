<?php

/**
 * @copyright Copyright (c) Orbital Flight
 */

namespace orbitalflight\celigo\controllers;

use Craft;
use craft\web\Controller;
use craft\web\View;
use orbitalflight\celigo\Celigo;

class CallController extends Controller {
    protected array|bool|int $allowAnonymous = true;

    private function _call(string $action) {
        $this->requirePostRequest();
        if (!Celigo::getInstance()->getSettings()->allowAnonymous) {
            $this->requireLogin();
        }

        $handle = $this->request->getRequiredBodyParam('handle');
        $params = $this->request->getBodyParam("params");
        $response = Celigo::getInstance()->service->call($handle, $action, $params);


        // Let's check if we should return a plain json (AJAX) or a template with the response
        if ($this->request->isAjax) {
            $this->requireAcceptsJson();
            return $this->asJson($response);
        } else {
            $redirect = $this->request->getValidatedBodyParam('redirect') ?? $this->request->getFullPath();
            return $this->renderTemplate($redirect, ['response' => $response], View::TEMPLATE_MODE_SITE);
        }
    }

    public function actionDelete() {
        return $this->_call("DELETE");
    }
    
    public function actionGet() {
        return $this->_call("GET");
    }

    public function actionPatch() {
        return $this->_call("PATCH");
    }

    public function actionPost() {
        return $this->_call("POST");
    }

    public function actionPut() {
        return $this->_call("PUT");
    }

    public function actionUpdate() {
        return $this->_call("UPDATE");
    }
}
