<?php namespace Anomaly\UsersModule\Authenticator;

use Anomaly\Streams\Platform\Addon\Extension\ExtensionCollection;
use Anomaly\UsersModule\Authenticator\Event\UserWasKickedOut;
use Anomaly\UsersModule\Authenticator\Event\UserWasLoggedIn;
use Anomaly\UsersModule\Authenticator\Event\UserWasLoggedOut;
use Anomaly\UsersModule\User\Contract\UserInterface;
use Illuminate\Auth\Guard;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;

/**
 * Class Authenticator
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\UsersModule\Authenticator
 */
class Authenticator
{

    /**
     * Laravel's authentication.
     *
     * @var Guard
     */
    protected $guard;

    /**
     * The event dispatcher.
     *
     * @var Dispatcher
     */
    protected $events;

    /**
     * The service container.
     *
     * @var Container
     */
    protected $container;

    /**
     * The extension collection.
     *
     * @var ExtensionCollection
     */
    protected $extensions;

    /**
     * Create a new Authenticator instance.
     *
     * @param Guard               $guard
     * @param Dispatcher          $events
     * @param Container           $container
     * @param ExtensionCollection $extensions
     */
    public function __construct(Guard $guard, Dispatcher $events, Container $container, ExtensionCollection $extensions)
    {
        $this->guard      = $guard;
        $this->events     = $events;
        $this->container  = $container;
        $this->extensions = $extensions;
    }

    /**
     * Attempt to login a user.
     *
     * @param array $credentials
     * @param bool  $remember
     * @return bool|UserInterface
     */
    public function attempt(array $credentials, $remember = false)
    {
        if ($user = $this->check($credentials)) {

            $this->login($user, $remember);

            return $user;
        }

        return false;
    }

    /**
     * Check authentication only.
     *
     * @param array $credentials
     * @return bool|UserInterface
     */
    public function check(array $credentials)
    {
        $authenticators = $this->extensions->search('anomaly.module.users::authenticator.*');

        foreach ($authenticators as $authenticator) {

            $user = $this->container->call(
                substr(get_class($authenticator), 0, -9) . 'Handler@handle',
                compact('credentials')
            );

            if ($user instanceof UserInterface) {
                return $user;
            }
        }

        return false;
    }

    /**
     * Force login a user.
     *
     * @param UserInterface $user
     * @param bool          $remember
     */
    public function login(UserInterface $user, $remember = false)
    {
        $this->guard->login($user, $remember);

        $this->events->fire(new UserWasLoggedIn($user));
    }

    /**
     * Logout a user.
     *
     * @param UserInterface $user
     */
    public function logout(UserInterface $user = null)
    {
        if (!$user) {
            $user = $this->guard->user();
        }

        $this->guard->logout($user);

        $this->events->fire(new UserWasLoggedOut($user));
    }

    /**
     * Kick out a user.
     *
     * @param UserInterface $user
     */
    public function kickOut(UserInterface $user, $reason)
    {
        $this->guard->logout($user);

        $this->events->fire(new UserWasKickedOut($user, $reason));
    }
}
