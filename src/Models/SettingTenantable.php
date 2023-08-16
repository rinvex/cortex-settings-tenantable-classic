<?php

declare(strict_types=1);

namespace Cortex\Settings\Models;

use Rinvex\Tenants\Traits\Tenantable;

class SettingTenantable extends Setting
{
    use Tenantable;
    // @TODO: We need to override validation rules to change the unqiue key to allow unique per tenant instead of per database!
    // 'key' => 'required|max:150|unique:'.config('rinvex.settings.models.setting').',key',
}
