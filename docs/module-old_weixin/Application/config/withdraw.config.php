<?php
return array(
    //about fee
    'service_charge'=>[
        'rate' => 0.03, // 提现手续费
        'the_highest_fee' => 1000, // 最高手续费
    ],
    //about date
    'date'=>[
        'start_day' => 1,
        'end_day' => 24,
        'remit_day_start' => 25,//财务打款开始时间
        'the_maximum_times'=>1,//每月最多可提现次数
    ],
    //about cash 
    'cash_withdraw'=>[
        'the_lowest_cash' => 100.00,//单次最低提现金额
        'the_highest_cash' => 50000,//单次最高提现金额
    ],
    
    'bank'=>[
      'the_highest_cash_need_bank_branch'=>20000,//不需要提供银行开户行的最低提现额  
    ],
);
