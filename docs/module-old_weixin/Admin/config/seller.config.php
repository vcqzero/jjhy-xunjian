<?php
return [
    'pay'=>[
        'url'           =>'https://trade.koudaitong.com/wxpay/confirmQr?qr_id=5992173&kdt_id=18370016',
        'payment'       =>500,
        'free_of_charge'=>[
            'is_free'   =>true,
            'end_time'  =>strtotime('2018-12-31 23:59:59'),//免费成为销售员到？？？
        ],
    ],
    'point'=>[
        'nums'  =>500,
        'reason'=>'分销员奖励积分',
    ],
    'commission'=>[
        'date'=>[
            'start_day' =>strtotime('2017-11-01'),//佣金开始计算时间
            'end_day'   =>strtotime('2018-12-12'),//计划结束时间
        ],
        'percent'   =>[
            '0'     =>['start'=>0, 'end'=>1 * 10000],
            '0.05'  =>['start'=>1 * 10000, 'end'=>50 * 10000],
            '0.08'  =>['start'=>50 * 10000, 'end'=>100 * 10000],
            '0.10'  =>['start'=>100 * 10000, 'end'=>10000 * 10000],
        ],
    ],
    'url'=>[
        'youzan_seller_center'=>'https://h5.youzan.com/v2/trade/directseller/center?kdt_id=18370016',
    ],
];
