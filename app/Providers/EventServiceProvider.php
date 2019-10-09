<?php

namespace App\Providers;

use App\Events\ChangeOrder;
use App\Events\CompanyRechargeEvent;
use App\Events\CouponEvent;
use App\Events\Deductibles;
use App\Events\DispatchOrder;
use App\Events\DriverOfflineEvent;
use App\Events\DriverRechargeEvent;
use App\Events\DriverStatusEvent;
use App\Events\FenceChargeEvent;
use App\Events\MqttDispatchEvent;
use App\Events\MqttPublishEvent;
use App\Events\OrderDrivingTimeEvent;
use App\Events\OrderFirstEvent;
use App\Events\OrderLogEvent;
use App\Events\OrderPay;
use App\Events\OrderRefuseLogEvent;
use App\Events\TidEvent;
use App\Events\TraceAddEvent;
use App\Events\TraceTrsearchEvent;
use App\Events\WechatNoticeEvent;
use App\Events\CompletedOrderNumEvent;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
//        Registered::class => [
//            SendEmailVerificationNotification::class,
//        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
