<?php

namespace Botble\SslCommerz\Providers;

use Botble\Hotel\Repositories\Interfaces\BookingAddressInterface;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\SslCommerz\Library\SslCommerz\SslCommerzNotification;
use Html;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Throwable;

class HookServiceProvider extends ServiceProvider
{
    public function boot()
    {
        add_filter(PAYMENT_FILTER_ADDITIONAL_PAYMENT_METHODS, [$this, 'registerSslCommerzMethod'], 18, 2);
        $this->app->booted(function () {
            add_filter(PAYMENT_FILTER_AFTER_POST_CHECKOUT, [$this, 'checkoutWithSslCommerz'], 18, 2);
        });

        add_filter(PAYMENT_METHODS_SETTINGS_PAGE, [$this, 'addPaymentSettings'], 199);

        add_filter(BASE_FILTER_ENUM_ARRAY, function ($values, $class) {
            if ($class == PaymentMethodEnum::class) {
                $values['SSLCOMMERZ'] = SSLCOMMERZ_PAYMENT_METHOD_NAME;
            }

            return $values;
        }, 24, 2);

        add_filter(BASE_FILTER_ENUM_LABEL, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == SSLCOMMERZ_PAYMENT_METHOD_NAME) {
                $value = 'SslCommerz';
            }

            return $value;
        }, 24, 2);

        add_filter(BASE_FILTER_ENUM_HTML, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == SSLCOMMERZ_PAYMENT_METHOD_NAME) {
                $value = Html::tag('span', PaymentMethodEnum::getLabel($value),
                    ['class' => 'label-success status-label'])
                    ->toHtml();
            }

            return $value;
        }, 24, 2);
    }

    /**
     * @param string $settings
     * @return string
     * @throws Throwable
     */
    public function addPaymentSettings($settings)
    {
        return $settings . view('plugins/sslcommerz::settings')->render();
    }

    /**
     * @param string $html
     * @param array $data
     * @return string
     */
    public function registerSslCommerzMethod($html, array $data)
    {
        return $html . view('plugins/sslcommerz::methods', $data)->render();
    }

    /**
     * @param Request $request
     * @param array $data
     */
    public function checkoutWithSslCommerz(array $data, Request $request)
    {
        if ($request->input('payment_method') == SSLCOMMERZ_PAYMENT_METHOD_NAME) {
            $body = [];
            $body['total_amount'] = $request->input('amount'); // You cant not pay less than 10
            $body['currency'] = $request->input('currency');
            $body['tran_id'] = uniqid(); // tran_id must be unique

            $bookingAddress = $this->app->make(BookingAddressInterface::class)
                ->getFirstBy(['booking_id' => $request->input('order_id')]);

            // CUSTOMER INFORMATION
            $body['cus_name'] = $bookingAddress->first_name . ' ' . $bookingAddress->last_name;
            $body['cus_email'] = $bookingAddress->email;
            $body['cus_add1'] = $bookingAddress->address;
            $body['cus_add2'] = '';
            $body['cus_city'] = '';
            $body['cus_state'] = '';
            $body['cus_postcode'] = '';
            $body['cus_country'] = $bookingAddress->country;
            $body['cus_phone'] = $bookingAddress->phone;
            $body['cus_fax'] = '';

            $body['ship_name'] = 'Not set';
            $body['ship_add1'] = 'Not set';
            $body['ship_add2'] = 'Not set';
            $body['ship_city'] = 'Not set';
            $body['ship_state'] = 'Not set';
            $body['ship_postcode'] = 'Not set';
            $body['ship_phone'] = 'Not set';
            $body['ship_country'] = 'Not set';

            $body['shipping_method'] = 'NO';

            $body['product_category'] = 'Goods';
            $body['product_name'] = 'Booking #' . $request->input('order_id');
            $body['product_profile'] = 'non-physical-goods';

            $body['value_a'] = $request->input('order_id');

            $sslc = new SslCommerzNotification;

            // initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payment gateway here )
            $sslc->makePayment($body, 'hosted');
        }

        return $data;
    }
}
