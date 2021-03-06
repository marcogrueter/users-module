<?php namespace Anomaly\UsersModule\Role\Contract;

use Anomaly\Streams\Platform\Entry\Contract\EntryRepositoryInterface;
use Anomaly\Streams\Platform\Entry\EntryCollection;

/**
 * Interface RoleRepositoryInterface
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\UsersModule\RoleInterface\Contract
 */
interface RoleRepositoryInterface extends EntryRepositoryInterface
{

    /**
     * Find a role by it's slug.
     *
     * @param $slug
     * @return null|RoleInterface
     */
    public function findBySlug($slug);

    /**
     * Find a role by a permission key.
     *
     * @param $permission
     * @return null|EntryCollection
     */
    public function findByPermission($permission);

    /**
     * Update permissions for a role.
     *
     * @param RoleInterface $role
     * @param array         $permissions
     * @return RoleInterface
     */
    public function updatePermissions(RoleInterface $role, array $permissions);
}
