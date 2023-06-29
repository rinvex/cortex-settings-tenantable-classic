<?php

declare(strict_types=1);

namespace Cortex\Settings\Models;

use Rinvex\Tenants\Traits\Tenantable;
use Cortex\Settings\Models\Setting as BaseSetting;

class SettingTenantable extends BaseSetting
{
    use Tenantable;
}
