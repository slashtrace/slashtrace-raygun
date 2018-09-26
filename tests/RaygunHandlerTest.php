<?php

namespace SlashTrace\Raygun\Tests;

use SlashTrace\Context\User;
use SlashTrace\EventHandler\EventHandlerException;
use SlashTrace\Raygun\RaygunHandler;

use PHPUnit\Framework\TestCase;
use Raygun4php\RaygunClient;

use Exception;

class RaygunHandlerTest extends TestCase
{
    public function testExceptionIsPassedToRaygunClient()
    {
        $exception = new Exception();

        $raygun = $this->createMock(RaygunClient::class);
        $raygun->expects($this->once())
            ->method("SendException")
            ->with($exception);

        $handler = new RaygunHandler($raygun);
        $handler->handleException($exception);
    }

    public function testRaygunExceptionsAreHandled()
    {
        $originalException = new Exception();
        $raygunException = new Exception();

        $raygun = $this->createMock(RaygunClient::class);
        $raygun->expects($this->once())
            ->method("SendException")
            ->with($originalException)
            ->willThrowException($raygunException);

        $handler = new RaygunHandler($raygun);
        try {
            $handler->handleException($originalException);
            $this->fail("Expected exception: " . EventHandlerException::class);
        } catch (EventHandlerException $e) {
            $this->assertSame($raygunException, $e->getPrevious());
        }
    }

    public function testUserIsPassedToRaygunClient()
    {
        $user = new User();
        $user->setId(12345);
        $user->setEmail("pfry@planetexpress.com");
        $user->setName("Philip J. Fry");

        $raygun = $this->createMock(RaygunClient::class);
        $raygun->expects($this->once())
            ->method("SetUser")
            ->with(
                $user->getId(),
                null,
                $user->getName(),
                $user->getEmail()
            );

        $handler = new RaygunHandler($raygun);
        $handler->setUser($user);
    }

    public function testPartialUserData()
    {
        $user = new User();
        $user->setEmail("pfry@planetexpress.com");

        $raygun = $this->createMock(RaygunClient::class);
        $raygun->expects($this->once())
            ->method("SetUser")
            ->with($user->getEmail(), null, null, $user->getEmail());

        $handler = new RaygunHandler($raygun);
        $handler->setUser($user);
    }

    public function testReleaseIsPassedToSentryClient()
    {
        $release = "1.0.0";

        $raygun = $this->createMock(RaygunClient::class);
        $raygun->expects($this->once())
            ->method("SetVersion")
            ->with($release);

        $handler = new RaygunHandler($raygun);
        $handler->setRelease($release);
    }
}