<?php
namespace Mtr\MiniCRM\Presentation\API;

use Nette\Application\UI\Presenter;
use Nette\Utils\DateTime;

/**
 * Ping minicrm
 */
final class Ping extends Presenter
{
    /**
     * @return void
     */
    public function actionPong(): void
    {
        $this->sendJson([
            'status' => 'ok',
            'time' => DateTime::from('now')->format(DateTime::ATOM),
        ]);
    }
}

