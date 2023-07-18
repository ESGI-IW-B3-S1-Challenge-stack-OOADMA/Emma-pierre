<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Session\SessionManager;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

abstract class AbstractController
{
    public function __construct(
        private Environment    $twig,
        private SessionManager $sessionManager,
        private UserRepository $userRepository,
    )
    {
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function render(string $template, array $context = []): string
    {
        return $this->twig->render($template, $context);
    }

    public function getSession()
    {
        return $this->sessionManager;
    }

    public function redirectToRoute(string $url)
    {
        header("Location: " . $url);
    }

    public function getUser(): ?User
    {
        if ($this->getSession()->has('user_id')) {
            return $this->userRepository->find($this->getSession()->get('user_id'));
        }
        return null;
    }
}
