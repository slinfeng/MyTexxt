<?php
namespace App\Constants;

use App\Models\AdminSetting;

class RedisKey{
    const ADMIN = 'アドミン';
    const STATISTICAL_EMPTY = 0;
    const STATISTICAL_START = 100;
    const YEAR_START = 1;
    const DAYS_OF_FISCAL_YEAR_END = 31;
    const ATTENDANCE_WAIT = 0;
    const ATTENDANCE_CONFIRMED = 1;
    const ATTENDANCE_REJECTED = 2;
    const NOTIFY_DELETED = 0;
    const NOTIFY_CONFIRMED = 1;
    const MSG_NOTIFY = [':attributeは拒否されました。',':attributeは承認されました。'];
}
