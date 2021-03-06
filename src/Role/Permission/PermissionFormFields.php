<?php namespace Anomaly\UsersModule\Role\Permission;

use Anomaly\Streams\Platform\Addon\Addon;
use Anomaly\Streams\Platform\Addon\AddonCollection;
use Anomaly\UsersModule\Role\Contract\RoleInterface;
use Illuminate\Config\Repository;
use Illuminate\Translation\Translator;

/**
 * Class PermissionFormFields
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\UsersModule\Role\Permission
 */
class PermissionFormFields
{

    /**
     * Handle the fields.
     *
     * @param PermissionFormBuilder $builder
     * @param AddonCollection       $addons
     * @param Translator            $translator
     * @param Repository            $config
     */
    public function handle(
        PermissionFormBuilder $builder,
        AddonCollection $addons,
        Translator $translator,
        Repository $config
    ) {
        /* @var RoleInterface $role */
        $role = $builder->getEntry();

        $fields = [];

        /* @var Addon $addon */
        foreach ($addons->withConfig('permissions') as $addon) {
            foreach ($config->get($addon->getNamespace('permissions'), []) as $group => $permissions) {
                foreach ($permissions as $permission) {

                    if ($translator->has('anomaly.module.users::permission.default.' . $permission)) {
                        $label = 'anomaly.module.users::permission.default.' . $permission;
                    } else {
                        $label = $addon->getNamespace('permission.' . $group . '.' . $permission);
                    }

                    $instructions = null;

                    if ($translator->has($addon->getNamespace('permission.default.' . $permission . '_instructions'))) {
                        $instructions = $addon->getNamespace('permission.default.' . $permission . '_instructions');
                    } elseif ($translator->has(
                        $addon->getNamespace('permission.' . $group . '.' . $permission . '_instructions')
                    )
                    ) {
                        $instructions = $addon->getNamespace(
                            'permission.' . $group . '.' . $permission . '_instructions'
                        );
                    }

                    $fields[$addon->getNamespace($group . '.' . $permission)] = [
                        'label'        => $label,
                        'instructions' => $instructions,
                        'type'         => 'anomaly.field_type.boolean',
                        'value'        => $role->hasPermission($addon->getNamespace($group . '.' . $permission)),
                        'config'       => [
                            'off_color' => 'danger'
                        ]
                    ];
                }
            }
        }

        $builder->setFields($fields);
    }
}
