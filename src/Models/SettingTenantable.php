<?php

declare(strict_types=1);

namespace Cortex\Settings\Models;

use Rinvex\Tenants\Traits\Tenantable;

class SettingTenantable extends Setting
{
    use Tenantable;
}
