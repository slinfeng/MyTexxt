<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

class ActivityLogJa implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        switch ($value){
            case "App\\Models\\Client": return "取引先管理";
            case "App\\Models\\AccountsEstimate":return "見積書管理";
            case "App\\Models\\AccountsOrder": return "注文書管理";
            case "App\\Models\\AccountsInvoice":return "請求書管理";
            case "App\\Models\\AccountsOrderConfirmation":return "注文請書管理";
            case "App\\Models\\LetterOfTransmittal": return "送付状管理";
            case "App\\Models\\RequestSettingExtra":
            case "App\\Models\\RequestSettingGlobal": return "請求管理初期設定";
            case "App\\Models\\BankAccount": return "請求管理初期設定の口座情報";
            case "App\\Models\\EmployeeBank":
            case "App\\Models\\EmployeeBase":
            case "App\\Models\\EmployeeContact":
            case "App\\Models\\EmployeeDependentRelation":
            case "App\\Models\\EmployeeInsurance":
            case "App\\Models\\EmployeeStay": return "社員情報管理";
            case "App\\Models\\Attendance":return "勤務情報管理";
            case "App\\Models\\Leave": return "休暇情報管理";
            case "App\\Models\\HrSetting":return "人事管理初期設定";
            case "App\\Models\\Department": return "人事管理初期設定の部門";
            case "App\\Models\\HireType":return "人事管理初期設定の契約形態";
            case "App\\Models\\PositionType": return "人事管理初期設定の役職";
            case "App\\Models\\RetireType": return "人事管理初期設定の在職区分";
            case "App\\Models\\ResidenceType": return "人事管理初期設定の在留資格種類";
            case "App\\Models\\AssetInfo":return  "設備管理";
            case "App\\Models\\AssetRentalLog":return  "設備貸出記録";
            case "App\\Models\\Receipt":return "領収書管理";
            case "App\\Models\\AssetType": return "資産管理初期設定";
            case "App\\Models\\User": return "ユーザー管理";
            case "App\\Models\\AdminSetting":return "設定";
            case "App\\Models\\UserIpAddress":return "ipアドレス設定";
            case "App\\Models\\Role":
            case "App\\Models\\PermissionRole": return "役割設定";
            case "App\\Models\\EmailTemplate": return "メールテンプレート設定";
            case "created": return "新規";
            case "deleted": return "削除";
            case "updated": return "編集";
        }
        return $value;

    }

    public function set($model, $key, $value, $attributes)
    {
        return $value;
    }
}
