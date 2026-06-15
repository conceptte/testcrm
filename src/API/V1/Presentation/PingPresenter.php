<?php
namespace Mtr\MiniCRM\API\V1\Presentation;

use Mtr\MiniCRM\API\V1\Presentation\ApiPresenter;
use Nette\Utils\DateTime;

/**
 * Ping minicrm
 */
final class PingPresenter extends ApiPresenter
{
    /**
     * @return void
     */
    public function actionPong(): void
    {
        $this->sendJson([
            'status' => 'ok',
            'version' => $this->version,
            'time' => DateTime::from('now')->format(DateTime::ATOM),
        ]);
    }
}

