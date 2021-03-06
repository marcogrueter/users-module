<?php namespace Anomaly\UsersModule\User\Table\Action;

use Anomaly\Streams\Platform\Ui\Table\Component\Action\ActionHandler;
use Anomaly\UsersModule\Suspension\SuspensionManager;
use Anomaly\UsersModule\User\Contract\UserRepositoryInterface;

/**
 * Class SuspendHandler
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\UsersModule\User\Table\Action
 */
class SuspendHandler extends ActionHandler
{

    /**
     * Handle the action.
     *
     * @param UserRepositoryInterface $users
     * @param SuspensionManager       $suspender
     * @param                         $selected
     */
    public function handle(UserRepositoryInterface $users, SuspensionManager $suspender, $selected)
    {
        $success = 0;
        $error   = 0;

        foreach ($selected as $id) {

            $user = $users->find($id);

            if ($user && !$user->hasRole('admin')) {

                $suspender->suspend($user);

                $success += 1;
            } else {
                $error += 1;
            }
        }

        if ($success) {
            $this->messages->success(
                trans('anomaly.module.users::success.suspend_users', ['count' => $success])
            );
        }

        if ($error) {
            $this->messages->error(
                trans('anomaly.module.users::error.suspend_users', ['count' => $error])
            );
        }
    }
}
