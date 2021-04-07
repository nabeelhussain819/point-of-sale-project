<?php

namespace App\Models;

use App\Helpers\ArrayHelper;
use Spatie\Permission\Models\Permission;

/**
 * @property integer $id
 * @property string $name
 * @property string $guard_name
 * @property string $created_at
 * @property string $updated_at
 * @property UserStore[] $userStores
 */
class Role extends \Spatie\Permission\Models\Role
{
    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */

    private $permissionId;
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['name', 'guard_name', 'created_at', 'updated_at'];

    private function getPermissionIds(): array
    {
        if (empty($this->permissionId)) {
            $this->permissionId = $this->permissions->pluck('id')->toArray();
        }
        return $this->permissionId;
    }

    public function isChecked(Permission $permission): string
    {
        return ArrayHelper::inArray($permission->id, $this->getPermissionIds()) ? 'checked' : '';
    }
}
