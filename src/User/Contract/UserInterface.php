<?php namespace Anomaly\UsersModule\User\Contract;

use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;
use Anomaly\Streams\Platform\Entry\EntryCollection;
use Anomaly\UsersModule\Activation\Contract\ActivationInterface;
use Anomaly\UsersModule\Suspension\Contract\SuspensionInterface;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Interface UserInterface
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\UsersModule\User\Contract
 */
interface UserInterface extends EntryInterface
{

    /**
     * Get the email.
     *
     * @return string
     */
    public function getEmail();

    /**
     * Get the username.
     *
     * @return string
     */
    public function getUsername();

    /**
     * Get the display name.
     *
     * @return string
     */
    public function getDisplayName();

    /**
     * Get the first name.
     *
     * @return string
     */
    public function getFirstName();

    /**
     * Get the last name.
     *
     * @return string
     */
    public function getLastName();

    /**
     * Set the password.
     *
     * @param $password
     * @return $this
     */
    public function setPassword($password);

    /**
     * Get related roles.
     *
     * @return EntryCollection
     */
    public function getRoles();

    /**
     * Return whether a user is in a role.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole($role);

    /**
     * Return whether a user is in
     * any of the provided roles.
     *
     * @param array $role
     * @return bool
     */
    public function hasAnyRole(array $roles);

    /**
     * Return the roles relationship.
     *
     * @return BelongsToMany
     */
    public function roles();

    /**
     * Get the permissions.
     *
     * @return array
     */
    public function getPermissions();

    /**
     * Return whether a user or it's roles has a permission.
     *
     * @param      $permission
     * @param bool $checkRoles
     * @return mixed
     */
    public function hasPermission($permission, $checkRoles = true);

    /**
     * Return whether a user has any of provided permission.
     *
     * @param $permissions
     * @return bool
     */
    public function hasAnyPermission(array $permissions);

    /**
     * Merge provided permissions onto existing ones.
     *
     * @param array $permissions
     * @return $this
     */
    public function mergePermissions(array $permissions);

    /**
     * Get the related activation.
     *
     * @return null|ActivationInterface
     */
    public function getActivation();

    /**
     * Return whether the user has a
     * completed activation or not.
     *
     * @return bool
     */
    public function isActivated();

    /**
     * Return the activation relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function activation();

    /**
     * Get the related suspension.
     *
     * @return null|SuspensionInterface
     */
    public function getSuspension();

    /**
     * Return whether the user is
     * suspended or not.
     *
     * @return bool
     */
    public function isSuspended();

    /**
     * Return the suspension relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function suspension();
}
