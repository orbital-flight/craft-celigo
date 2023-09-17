<?php

/**
 * @copyright Copyright (c) Orbital Flight
 */

namespace orbitalflight\celigo;

use Craft;
use craft\base\Model;
use craft\base\Plugin;
use craft\web\twig\variables\CraftVariable;
use orbitalflight\celigo\models\Settings;
use orbitalflight\celigo\services\CeligoService;
use orbitalflight\celigo\variables\CeligoVariable;
use yii\base\Event;

/**
 * Celigo plugin
 *
 * @method static Celigo getInstance()
 * @method Settings getSettings()
 * @author Orbital Flight <flightorbital@gmail.com>
 * @copyright Orbital Flight
 * @license https://craftcms.github.io/license/ Craft License
 */
class Celigo extends Plugin {
    public string $schemaVersion = '1.0.0';
    public bool $hasCpSettings = true;

    public static function config(): array {
        return [
            'components' => [
                // Define component configs here...
            ],
        ];
    }

    public function init(): void {
        parent::init();

        // Defer most setup tasks until Craft is fully initialized
        Craft::$app->onInit(function () {
            $this->attachEventHandlers();
            // ...
        });

        // Register services
        $this->setComponents([
            'service' => CeligoService::class,
        ]);

        // Register variable 
        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                $variable = $event->sender;
                $variable->set('celigo', CeligoVariable::class);
            }
        );
    }

    protected function createSettingsModel(): ?Model {
        return Craft::createObject(Settings::class);
    }

    protected function settingsHtml(): ?string {
        return Craft::$app->view->renderTemplate('celigo/_settings.twig', [
            'plugin' => $this,
            'settings' => $this->getSettings(),
        ]);
    }

    private function attachEventHandlers(): void {
        // Register event handlers here ...
        // (see https://craftcms.com/docs/4.x/extend/events.html to get started)
    }
}
