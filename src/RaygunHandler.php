<?php

namespace SlashTrace\Raygun;

use SlashTrace\Context\User;
use SlashTrace\EventHandler\EventHandler;
use SlashTrace\EventHandler\EventHandlerException;

use Raygun4php\RaygunClient;
use Exception;

class RaygunHandler implements EventHandler
{
    /** @var RaygunClient */
    private $raygun;

    public function __construct($raygun)
    {
        $this->raygun = $raygun instanceof RaygunClient ? $raygun : new RaygunClient($raygun);
    }

    /**
     * @param Exception $exception
     * @return int
     * @throws EventHandlerException
     */
    public function handleException($exception)
    {
        try {
            $this->raygun->SendException($exception);
        } catch (Exception $e) {
            throw new EventHandlerException($e->getMessage(), $e->getCode(), $e);
        }
        return EventHandler::SIGNAL_CONTINUE;
    }

    /**
     * @param User $user
     * @return void
     */
    public function setUser(User $user)
    {
        $this->raygun->SetUser((string) $user->getId() ?: $user->getEmail(), null, $user->getName(), $user->getEmail());
    }

    /**
     * @param string $title
     * @param array $data
     * @return void
     */
    public function recordBreadcrumb($title, array $data = [])
    {
        // Breadcrumbs are currently not supported by the Raygun PHP SDK
    }

    /**
     * @param string $release
     * @return void
     */
    public function setRelease($release)
    {
        $this->raygun->SetVersion($release);
    }

    /**
     * @param string $path
     * @return void
     */
    public function setApplicationPath($path)
    {
        // Local application path is currently not supported by the Raygun PHP SDK
    }
}