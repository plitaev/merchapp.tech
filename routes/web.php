<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {return view('welcome');})->name('welcome');

Route::get('/phpinfo', function () {phpinfo();})->name('phpinfo');

require 'core/auto.php';
require 'core/bot.php';
require 'core/bot_message.php';
require 'core/bot_message_button.php';
require 'core/converter.php';
require 'core/devtest.php';
require 'core/devtesttelegram.php';
require 'core/google.php';
require 'core/getcourse.php';
require 'core/miniapp.php';
require 'core/pay.php';
require 'core/paycount.php';
require 'core/prodamus.php';
require 'core/robokassa.php';
require 'core/tbank.php';
require 'core/telegram.php';
require 'core/yookassa.php';

require 'project/app1.php';
require 'project/app2.php';


