<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages.
    |
    */
    'accepted'             => ':attributeを承認してください。',
    'active_url'           => ':attributeは、有効なサイトではありません。',
    'after'                => ':attributeには、:dateより後の日付を指定してください。',
    'after_or_equal'       => ':attributeには、:date以降の日付を指定してください。',
    'alpha'                => ':attributeには、アルファベッドのみ使用できます。',
    'alpha_dash'           => ":attributeには、英数字('A-Z','a-z','0-9')とハイフンと下線('-','_')が使用できます。",
    'alpha_num'            => ":attributeには、英数字('A-Z','a-z','0-9')が使用できます。",
    'array'                => ':attributeには、配列を指定してください。',
    'before'               => ':attributeには、:dateより前の日付を指定してください。',
    'before_or_equal'      => ':attributeには、:date以前の日付を指定してください。',
    'between'              => [
        'numeric' => ':attributeには、:minから、:maxまでの数字を指定してください。',
        'file'    => ':attributeには、:min KBから:max KBまでのサイズのファイルを指定してください。',
        'string'  => ':attributeは、:min文字から:max文字にしてください。',
        'array'   => ':attributeの項目は、:min個から:max個にしてください。',
    ],
    'boolean'              => ":attributeには、'true'か'false'を指定してください。",
    'confirmed'            => ':attributeと:attribute確認が一致しません。',
    'date'                 => ':attributeは、正しい日付ではありません。',
    'date_equals'          => ':attributeは:dateに等しい日付でなければなりません。',
    'date_format'          => ":attributeの形式は、':format'と合いません。",
    'different'            => ':attributeと:otherには、異なるものを指定してください。',
    'digits'               => ':attributeは、:digits桁にしてください。',
    'digits_between'       => ':attributeは、:min桁から:max桁にしてください。',
    'dimensions'           => ':attributeの画像サイズが無効です',
    'distinct'             => ':attributeの値が重複しています。',
    'email'                => ':attributeは、有効なメールアドレス形式で指定してください。',
    'ends_with'            => ':attributeは、次のうちのいずれかで終わらなければなりません。: :values',
    'exists'               => '選択された:attributeは、有効ではありません。',
    'file'                 => ':attributeはファイルでなければいけません。',
    'filled'               => ':attributeは必須です。',
    'gt'                   => [
        'numeric' => ':attributeは、:valueより大きくなければなりません。',
        'file'    => ':attributeは、:value KBより大きくなければなりません。',
        'string'  => ':attributeは、:value文字より大きくなければなりません。',
        'array'   => ':attributeの項目数は、:value個より大きくなければなりません。',
    ],
    'gte'                  => [
        'numeric' => ':attributeは、:value以上でなければなりません。',
        'file'    => ':attributeは、:value KB以上でなければなりません。',
        'string'  => ':attributeは、:value文字以上でなければなりません。',
        'array'   => ':attributeの項目数は、:value個以上でなければなりません。',
    ],
    'image'                => ':attributeには、画像を指定してください。',
    'in'                   => '選択された:attributeは、有効ではありません。',
    'in_array'             => ':attributeが:otherに存在しません。',
    'integer'              => ':attributeには、整数を指定してください。',
    'ip'                   => ':attributeには、有効なIPアドレスを指定してください。',
    'ipv4'                 => ':attributeはIPv4アドレスを指定してください。',
    'ipv6'                 => ':attributeはIPv6アドレスを指定してください。',
    'json'                 => ':attributeには、有効なJSON文字列を指定してください。',
    'lt'                   => [
        'numeric' => ':attributeは、:valueより小さくなければなりません。',
        'file'    => ':attributeは、:value KBより小さくなければなりません。',
        'string'  => ':attributeは、:value文字より小さくなければなりません。',
        'array'   => ':attributeの項目数は、:value個より小さくなければなりません。',
    ],
    'lte'                  => [
        'numeric' => ':attributeは、:value以下でなければなりません。',
        'file'    => ':attributeは、:value KB以下でなければなりません。',
        'string'  => ':attributeは、:value文字以下でなければなりません。',
        'array'   => ':attributeの項目数は、:value個以下でなければなりません。',
    ],
    'max'                  => [
        'numeric' => ':attributeには、:max以下の数字を指定してください。',
        'file'    => ':attributeには、:max KB以下のファイルを指定してください。',
        'string'  => ':attributeは、:max文字以下にしてください。',
        'array'   => ':attributeの項目は、:max個以下にしてください。',
    ],
    'max_mb'               => 'アップロードされたファイルには、:max_mb MB以下のファイルを指定してください。',
    'mimes'                => ':attributeには、:valuesタイプのファイルを指定してください。',
    'mimetypes'            => ':attributeには、:valuesタイプのファイルを指定してください。',
    'min'                  => [
        'numeric' => ':attributeには、:min以上の数字を指定してください。',
        'file'    => ':attributeには、:min KB以上のファイルを指定してください。',
        'string'  => ':attributeは、:min文字以上にしてください。',
        'array'   => ':attributeの項目は、:min個以上にしてください。',
    ],
    'not_in'               => '選択された:attributeは、有効ではありません。',
    'not_regex'            => ':attributeの形式が無効です。',
    'numeric'              => ':attributeには、数字を指定してください。',
    'password'             => 'パスワードが正しくありません。',
    'present'              => ':attributeが存在している必要があります。',
    'regex'                => '正しい形式の:attributeを入力してください。',
    'required'             => ':attributeは必ず指定してください。',
    'required_if'          => ':otherが:valueの場合、:attributeを指定してください。',
    'required_unless'      => ':otherが:values以外の場合、:attributeを指定してください。',
    'required_with'        => ':valuesが指定されている場合、:attributeも指定してください。',
    'required_with_all'    => ':valuesが全て指定されている場合、:attributeも指定してください。',
    'required_without'     => ':valuesが指定されていない場合、:attributeを指定してください。',
    'required_without_all' => ':valuesが全て指定されていない場合、:attributeを指定してください。',
    'same'                 => ':attributeと:otherが一致しません。',
    'size'                 => [
        'numeric' => ':attributeには、:sizeを指定してください。',
        'file'    => ':attributeには、:size KBのファイルを指定してください。',
        'string'  => ':attributeは、:size文字にしてください。',
        'array'   => ':attributeの項目は、:size個にしてください。',
    ],
    'starts_with'          => ':attributeは、次のいずれかで始まる必要があります。:values',
    'string'               => ':attributeには、文字を指定してください。',
    'timezone'             => ':attributeには、有効なタイムゾーンを指定してください。',
    'unique'               => '指定の:attributeは既に使用されています。',
    'uploaded'             => ':attributeのアップロードに失敗しました。',
    'url'                  => ':attributeは、有効なサイト形式で指定してください。',
    'uuid'                 => ':attributeは、有効なUUIDでなければなりません。',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */
    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
        'client_id' => [
            'required' => '取引先を選択してください！',
            'not_in' => '取引先を選択してください！',
        ],
        'bank_account_id' => [
            'required' => '口座情報を選択してください！',
        ],
        'name_phonetic' => [
            'regex' => '「フリガナ」を入力してください',
        ],
        'name_roman' => [
            'regex' => '「ローマ字」（半角）を入力してください',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */
    'attributes' => [
        // client
        'cooperation_start'=>'提携開始日',
        'client_name'=>'取引先名',
        'client_abbreviation'=>'略称',
        'our_role'=>'我社立場',
        'url'=>'サイト',
        'tel'=>'電話',
        'mail'=>'メール',
        'fax'=>'ファックス',
        'post_code'=>'郵便番号',
        'client_address'=>'住所',
        "document_format"=>"書類提出の仕方",

        // estimate
        'est_manage_code'=>'見積番号',
        'client_id'=>'取引先',
        'project_name_or_file_name'=>'案件名',
        'work_place'=>'作業場所',
        'acceptance_place'=>'納品場所',
        'payment_contract'=>'支払条件',
        'period'=>'作業期間',
        'employee_name'=>'作業者名',
        'employee_name.*'=>'作業者名',
        'unit_price.*'=>'単金',
        'estimate_subtotal'=>'見積額',
        'month.*'=>'人月',
        'project_name.*'=>'作業明細',
        'bank_account_id.*'=>'銀行',

        // expense
        'project_manage_code'=>'注文番号',
        'outlay'=>'経費清算',

        // invoice
        'invoice_manage_code'=>'請求番号',
        'invoice_total'=>'請求額（税込）',
        'detail_content.*'=>'内訳明細',
        'unit_price_commuting_sub.*'=>'請求金額',
        'unit_price_working_sub.*'=>'請求金額',
        'pay_deadline'=>'振込期限日/支払期限日',

        //注文請書
        'order_manage_code'=>'注文請書番号',

        //letterOfTransmittal
        'delivery_date'=>'送付日',
        'document_send'=>'送付書類',
        'content'=>'中身',
        'vertical_distance'=>'縦距離',
        'horizontal_distance'=>'横距離',


        //request_setting
        'tax_rate'=>'税率',
        'format family'=>'フォントファミリー',
        'company_info'=>'会社情報',
        'cloud_request_period'=>'クラウドで請求保存期間',

        'bank_name'=>'銀行名',
        'branch_name'=>'支店名',
        'branch_code'=>'支店番号',
        'account_type'=>'預金種類',
        'account_name'=>'口座名義',
        'account_num'=>'口座番号',

        'calendar_search_unit0'=>'カレンダー検索範囲',
        'client_sort_type'=>'取引先並び順',
        'our_position_type'=>'我社立場',
        'work_place2'=>'作業場所',
        'work_place_val'=>'作業場所',
        'acceptance_place_val'=>'納入場所',
        'acceptance_place2'=>'納入場所',
        'expense_traffic_expence_paid_by_val'=>'交通・通勤費',
        'expense_traffic_expence_paid_by'=>'交通・通勤費',


        //employee
        'name_phonetic'=>'フリガナ',
        'name_roman'=>'ローマ字',
        'birthday'=>'生年月日',
        'date_hire'=>'入社日',
        'department_type_id'=>'部門',
        'hire_type_id'=>'契約形態',
        'position_type_id'=>'役職',
        'date_retire'=>'退職日',
        'family_num'=>'扶養家族',
        'employee_code'=>'社員番号',
        'retire_type_id'=>'契約形態',
        'phone'=>'携帯電話',
        'telephone'=>'電話番号',
        'postcode'=>'郵便番号',
        'home_town_postcode'=>'本籍地郵便番号',
        'emergency_phone'=>'緊急連絡先電話',
        'residence_card_num'=>'在留カード番号',
        'residence_type'=>'在留資格種類',
        'residence_deadline'=>'在留満了年月日',
        'personal_num'=>'個人番号',
        'social_insurance'=>'社会保険（年金・健保）',
        'employment_insurance'=>'雇用保険（雇用・労災）',
        'national_health_insurance'=>'国民健康保険',
        'national_pension_insurance'=>'国民年金',
        'basic_pension_number'=>'基礎年金番号',
        'sign'=>'記号',
        'organize_number'=>'整理番号',
        'social_start_date'=>'資格取得日',
        'base_amount'=>' 基準額',
        'social_end_date'=>'資格喪失日',
        'insured_number'=>'被保険者番号',
        'employment_start_date'=>'雇用資格取得日',
        'employment_end_date'=>'雇用資格喪失日',
        'relationship_type.*'=>'扶養親族区分',
        'dname.*'=>'扶養親族の氏名',
        'dependent_birthday.*'=>'扶養親族の生年月日',
        'residence_card_front'=>'在留カードの表',
        'residence_card_back'=>'在留カードの裏',
        'icon'=>'トップ画像',

        //attendance
        'idArr.*'=>'勤務記録',
        'workingTimeArr.*'=>'勤務時間数',
        'timeArr.*'=>'勤務時間数',
        'employee_id'=>'社員',
        'year_and_month'=>'勤務日付',
        'working_time'=>'勤務時間数',
        'file'=>'勤務表ファイル',

        //company assets
        'manage_code'=>'管理番号',
        'asset_type_id'=>'資産分類',
        'loan_or_return_date'=>'貸出日/返却日',

        //receipt
        'receipt_date'=>'領収日',
        'receipt_amount'=>'領収金額',
        'document_end'=>'中身',

        //asset type
        'asset_type_name'=>'資産種類名',
        'asset_type_code'=>'資産種類コード',

        //asset setting
        'search_mode'=>'カレンダー検索範囲',
        'use_init_val'=>'初期値使用',
        'use_seal'=>'電子印鑑使用',
        'seal_file'=>'電子印鑑アプロード',

        //scheduleColor
        'order_num'=>'表示順',
        'color_id'=>'色',

        //scheduleMember
        'display_name'=>'表示名',
        'reserve_type'=>'重複予約可',
        'reserve_name_type'=>'予約者名表示',
        'constraint_type'=>'制約',


        //user
        'name'=>'名前',
        'password'=>'パスワード',
        'password_confirmation'=>'パスワード確認',

        //admin setting
        'company_name'=>'会社名',
        'phone_no'=>'電話番号',
        'address'=>'住所',
        'representative_name'=>'代表者名',
        'closing_month'=>'決算月',
        'email'=>'Eメール',
        'logo'=>'ロゴ',
        'company_short_name'=>'会社の略称',

        'mail_driver'=>'ドライバー',
        'mail_host'=>'ホスト',
        'mail_port'=>'ポート',
        'mail_username'=>'ユーザー名',
        'mail_password'=>'パスワード',
        'mail_encryption'=>'暗号化',
        'mail_from_address'=>'送信元アドレス',
        'mail_from_name'=>'表記',

        'email_id'=>'メールテンプレートID',
        'subject'=>'メールテンプレートの件名',
        'body'=>'メールテンプレート本文',

        'ipAddressName.*'=>'タイトル',
        'IPAddress00.*'=>'IPアドレス',
        'IPAddress01.*'=>'IPアドレス',
        'IPAddress02.*'=>'IPアドレス',
        'IPAddress03.*'=>'IPアドレス',
        'IPAddress13.*'=>'IPアドレス',


        'display_time_start'=>'表示時間帯',
        'display_time_end'=>'表示時間帯',




    ],
];
