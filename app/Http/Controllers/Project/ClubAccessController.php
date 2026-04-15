<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Telegram\Bot\Api;

use App\Actions\Core\Bot\BotGetByID;
use App\Actions\Core\BotBranch\BotBranchRun;
use App\Actions\Core\BotBranch\BotBranchEndOnRestart;
use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\BotSupergroup\BotSupergroups;
use App\Actions\Core\BotUser\BotUserCreateFromMax;
use App\Actions\Core\BotUser\BotUserCreateFromTelegram;
use App\Actions\Core\BotUser\BotUserGetFromMax;
use App\Actions\Core\BotUser\BotUserGetFromTelegram;
use App\Actions\Core\BotUser\BotUserSetBranch;

use App\Actions\Core\Max\MaxGetChatIDFromWebhook;
use App\Actions\Core\Max\MaxMessageGetStartParams;
use App\Actions\Core\Max\MaxWebhookWrite;

use App\Actions\Core\ReferralProgram\ReferralProgramRunForReferrer;
use App\Actions\Core\ReferralProgram\ReferralProgramRunForReferral;

use App\Actions\Core\Telegram\TelegramGetChatIDFromWebhook;
use App\Actions\Core\Telegram\TelegramMessageGetStartParams;
use App\Actions\Core\Telegram\TelegramWebhookWrite;

use App\Actions\Project\ClubAccess\BotCabinetRecurrentCheck;
use App\Actions\Project\ClubAccess\BotContacts;
use App\Actions\Project\ClubAccess\BotEighteen;
use App\Actions\Project\ClubAccess\BotEighteenNo;
use App\Actions\Project\ClubAccess\BotEighteenYes;
use App\Actions\Project\ClubAccess\BotEmailChange;
use App\Actions\Project\ClubAccess\BotEmailVerification;
use App\Actions\Project\ClubAccess\BotGoToClub;
use App\Actions\Project\ClubAccess\BotHandName;
use App\Actions\Project\ClubAccess\BotListenerEmail;
use App\Actions\Project\ClubAccess\BotListenerPayCount;
use App\Actions\Project\ClubAccess\BotListenerPhone;
use App\Actions\Project\ClubAccess\BotMainMenuMessage;
use App\Actions\Project\ClubAccess\BotRequestAndConfirmEmail;
use App\Actions\Project\ClubAccess\BotResetUser;
use App\Actions\Project\ClubAccess\BotUserRecurrentDisable;
use App\Actions\Project\ClubAccess\BotWelcomeMessage;

use App\Actions\Local\ClubAccessCallback;

use App\Models\Core\BotUser;

class ClubAccessController extends Controller
{
    public function club_access(string $messenger, int $bot_id) {

        //== Инициализируем основные классы
        $botBranchRun = new BotBranchRun();
        $botBranchEndOnRestart = new BotBranchEndOnRestart();
        $botGetByID = new BotGetByID();
        $botSendMessage = new BotSendMessage();
        $botSupergroupsByBot = new BotSupergroups();
        $botUserCreateFromMax = new BotUserCreateFromMax();
        $botUserCreateFromTelegram = new BotUserCreateFromTelegram();
        $botUserGetFromMax = new BotUserGetFromMax();
        $botUserGetFromTelegram = new BotUserGetFromTelegram();
        $botUserSetBranch = new BotUserSetBranch();

        $maxGetChatIDFromWebhook = new MaxGetChatIDFromWebhook();
        $maxMessageGetStartParams = new MaxMessageGetStartParams();
        $maxWebhookWrite = new MaxWebhookWrite();

        $referralProgramRunForReferrer = new ReferralProgramRunForReferrer();
        $referralProgramRunForReferral = new ReferralProgramRunForReferral();

        $telegramGetChatIDFromWebhook = new TelegramGetChatIDFromWebhook();
        $telegramMessageGetStartParams = new TelegramMessageGetStartParams();
        $telegramWebhookWrite = new TelegramWebhookWrite();

        //== Инициализируем классы проекта
        $botCabinetRecurrentCheck = new BotCabinetRecurrentCheck();
        $botContacts = new BotContacts();
        $botEighteen = new BotEighteen();
        $botEighteenYes = new BotEighteenYes();
        $botEighteenNo = new BotEighteenNo();
        $botEmailChange = new BotEmailChange();
        $botEmailVerification = new BotEmailVerification();
        $botGoToClub = new BotGoToClub();
        $botHandName = new BotHandName();
        $botListenerEmail = new BotListenerEmail();
        $botListenerPayCount = new BotListenerPayCount();
        $botListenerPhone = new BotListenerPhone();
        $botMainMenuMessage = new BotMainMenuMessage();
        $botResetUser = new BotResetUser();
        $botRequestAndConfirmEmail = new BotRequestAndConfirmEmail();
        $botUserRecurrentDisable = new BotUserRecurrentDisable();
        $botWelcomeMessage = new BotWelcomeMessage();

        $bot = $botGetByID->handle($bot_id);
        $telegram = new Api($bot->telegram_token);

        //== Заканчиваем инициацию классов


        //== Делаем первичную обработку
        //== Пишем вебхук от телеграма / макса в БД и в переменную

        if ($messenger == 'telegram') $webhook = $telegramWebhookWrite->handle(file_get_contents('php://input'), $bot_id);
        if ($messenger == 'max') $webhook = $maxWebhookWrite->handle(file_get_contents('php://input'), $bot_id);

        //== Получаем chat_id из вебхука, и если не найден, возвращаем ошибку
        if ($messenger == 'telegram') (int) $chat_id = $telegramGetChatIDFromWebhook->handle($webhook);
        if ($messenger == 'max') (int) $chat_id = $maxGetChatIDFromWebhook->handle($webhook);

        if ($chat_id == 0) return "CHAT_ID_NOT_FOUND";

        if ($messenger == 'telegram' && $chat_id < 0) die();

        //== Получаем ID всех групп, к которым привязан бот
        $groups = $botSupergroupsByBot->handle($bot_id);

        //== И блокируем отправку в общий чат, если сообщение пришло от групп
        if (in_array($chat_id, $groups)) die();

        //== А если найден, то пишем в БД и идем дальше
        if ($messenger == 'telegram') $botUserCreateFromTelegram->handle($chat_id, $bot_id, $webhook);
        if ($messenger == 'max') $botUserCreateFromMax->handle($chat_id, $bot_id, $webhook);

        //== Достаем данные юзера по chat_id после создания
        if ($messenger == 'telegram') $bot_user = $botUserGetFromTelegram->handle($bot_id, $chat_id);
        if ($messenger == 'max') $bot_user = $botUserGetFromMax->handle($bot_id, $chat_id);

        if ($bot_user->blacklist == 1) die();

        //== Заканчиваем первичную обработку

        //== Переходим к работе с колбэком ТГ
        //== Если сообщение существует
        if (($messenger == 'telegram' && isset($webhook['message'])) || ($messenger == 'max' && isset($webhook['update_type']) && $webhook['update_type'] != 'message_callback')) {

            //== Проверяем, есть ли входящие параметры по ссылке на переход в ТГ к разговору с ботом
            if ($messenger == 'telegram') (array) $Astart = $telegramMessageGetStartParams->handle($webhook);
            if ($messenger == 'max') (array) $Astart = $maxMessageGetStartParams->handle($webhook);

            //== Если это /start, тут обрабатываем старт
            if ($Astart[0] == "/start") {

                if (count($Astart) == 2) {

                    $branch_data = base64_decode($Astart[1]);
                    $branch_data = explode("|", $branch_data);

                    if ($branch_data[0] == 1) $botUserSetBranch->handle($bot_user, 'BRANCH_MAIN');
                    if ($branch_data[0] == 2) $botBranchRun->handle($bot_user, $branch_data[1]);
                    if ($branch_data[0] == 3) $referralProgramRunForReferral->handle($bot_user, $branch_data);

                    if ($branch_data[0] == 4) {
                        $botUserSetBranch->handle($bot_user, $branch_data[1]);
                        $bot_user = $botUserGetFromTelegram->handle($bot_id, $chat_id);
                        $botSendMessage->handle($bot_user, 'SYS_WELCOME_MESSAGE');
                        $botUserSetBranch->handle($bot_user, 'BRANCH_MAIN');
                        die();
                    }

                } else {
                    if (!$bot_user->bot_branch_id) $botUserSetBranch->handle($bot_user, 'BRANCH_MAIN');
                }

                if ($bot_user->date_end != NULL && $bot_user->date_end > date('Y-m-d', time())) {
                    $botSendMessage->handle($bot_user, 'SYS_SUCCESS_MESSAGE');
                    die();
                }



                $botResetUser->handle($bot_user->id); //== Сбрасываем юзера
                $botBranchEndOnRestart->handle($bot_user);

                //== И повторно достаем его данные после сброса
                if ($messenger == 'telegram') $bot_user = $botUserGetFromTelegram->handle($bot_id, $chat_id);
                if ($messenger == 'max') $bot_user = $botUserGetFromMax->handle($bot_id, $chat_id);
            }

            //== Если это /registration, тут обрабатываем регистрацию
            if ($Astart[0] == "/registration") {
                $botRequestAndConfirmEmail->handle($bot_user);
            }

            //== Если это /referral, тут обрабатываем реферальную программу
            if ($Astart[0] == "/referral") {
                $referralProgramRunForReferrer->handle($bot_user);
                die();
            }

            //== Если это /cabinet, тут обрабатываем личный кабинет
            if ($Astart[0] == "/cabinet") {
                $botCabinetRecurrentCheck->handle($bot_user);
                die();
            }

            //== Если это /subscribe, тут обрабатываем регистрацию
            if ($Astart[0] == "/subscribe") {
                $botGoToClub->handle($messenger, $telegram, $webhook, $bot_user);
                die();
            }

            //== Если это /oferta, тут обрабатываем регистрацию
            if ($Astart[0] == "/oferta") {
                $botSendMessage->handle($bot_user, 'SYS_OFERTA');
                die();
            }

            //== Тут обрабатываем разные входяшие параметры в бот после старта

            if (count($Astart)==2 && $Astart[0]=="/start" && $Astart[1]!="none") {
                $code = base64_decode($Astart[1]);

                if ($code == "GoToClub") {
                    $botGoToClub->handle($messenger, $telegram, $webhook, $bot_user);
                    die();
                }

            }


            //== Заканчиваем обрабатывать входящие параметры по ссылке на переход в ТГ к разговору с ботом

            //== Обрабатываем листенеры
            return $botListenerEmail->handle($messenger, $webhook, $bot_user); //== Проверяем, ожидает ли юзер ввода почты
            $botListenerPayCount->handle($messenger, $webhook, $bot_user);
            $botListenerPhone->handle($messenger, $webhook, $bot_user);

            //== Запускаем основной скрипт клуба
            //== Проверяем, получал ли юзер приветственное сообщение
            $botWelcomeMessage->handle($bot_user); //== Обрабатываем первичное стартовое сообщение (до ввода имени)
            $botHandName->handle($bot_user, $webhook); //== Обрабатываем HandName - вручную введенное юзером имя
            $botEighteen->handle($bot_user); //== Обрабатываем подтверждение 18 лет
            $botMainMenuMessage->handle($messenger, $telegram, $webhook, $bot_user); //== Обрабатываем сообщение с главным меню

        } else {

            if (($messenger == 'telegram' && isset($webhook['callback_query'])) || $messenger == 'max' && isset($webhook['callback']['payload'])) {
                //== Проверяем, является ли это колбэк-запросом

                //== Инициализируем базовые данные и классы

                if ($messenger == 'telegram') $callback=$webhook['callback_query']['data'];
                if ($messenger == 'max') $callback=$webhook['callback']['payload'];

                //== Конец базовой инициализации

                if ($callback == 'EighteenYes') $botEighteenYes->handle($messenger, $bot_user, $telegram, $webhook); //== Если выбрал Да в вопросе про 18 лет
                if ($callback == 'EighteenNo') $botEighteenNo->handle($messenger, $bot_id, $telegram, $webhook); //== Если выбрал Нет в вопросе про 18 лет
                if ($callback == 'GoToContacts') $botContacts->handle($bot_id, $telegram, $webhook); //== Если нажал кнопку Контакты
                if ($callback == 'GoToClub') $botGoToClub->handle($messenger, $telegram, $webhook, $bot_user); //== Если нажал кнопку Стать участником

                if ($callback == 'GoToEmailVerification') $botEmailVerification->handle($messenger, $telegram, $bot_user, $webhook); //== Если нажата кнопка Подтвердить почту при условии что почта уже введена

                if ($callback == 'GoToEmailChange') $botEmailChange->handle($messenger, $telegram, $bot_user, $webhook); //== Если нажата кнопка Изменить почту при условии что почта уже введена
                if ($callback == 'BotUserRecurrentDisable') $botUserRecurrentDisable->handle($messenger, $telegram, $bot_user, $webhook);

                if ($callback == 'GoToMainMenuMessage') $botMainMenuMessage->handle($messenger, $telegram, $webhook, $bot_user);

                if ($callback == 'GoToStart') {

                    if ($messenger == 'telegram' && isset($webhook['callback_query']['message']['message_id'])) {
                        $telegram->answerCallbackQuery(['callback_query_id' => $webhook['callback_query']['id']]);
                    }

                    if ($bot_user->date_end != NULL && $bot_user->date_end > date('Y-m-d', time())) {
                        $botSendMessage->handle($bot_user, 'SYS_SUCCESS_MESSAGE');
                        die();
                    }

                    $botResetUser->handle($bot_user->id); //== Сбрасываем юзера
                    $bot_user = $botUserGetFromTelegram->handle($bot_id, $chat_id); //== И повторно достаем его данные после сброса

                }

                if ($callback == 'GoToFullTariffs') {

                    if ($messenger == 'telegram' && isset($webhook['callback_query']['message']['message_id'])) {
                        $telegram->answerCallbackQuery(['callback_query_id' => $webhook['callback_query']['id']]);
                    }

                    $botSendMessage->handle($bot_user, 'SYS_PAY_IN_BOT_ALL_TARIFFS');
                    die();
                }

                if ($callback == 'GoToPhoneEnter') {

                    if ($messenger == 'telegram' && isset($webhook['callback_query']['message']['message_id'])) {
                        $telegram->answerCallbackQuery(['callback_query_id' => $webhook['callback_query']['id']]);
                    }

                    $botSendMessage->handle($bot_user, 'USER_PHONE_ENTER_WAITING');
                    die();
                }

                //== Генерим колбэки для присоединения Макс к ТГ

                $res = BotUser::whereNotNull('verification_from_max')->get();
                foreach ($res as $data) {

                    if ($callback == 'connect_max_to_telegram_'.$data->verification_from_max) {

                        BotUser::where('max_user_id', $data->verification_from_max)->delete();
                        BotUser::where('id', $bot_user->id)->update(['max_user_id' => $data->verification_from_max, 'verification_from_max' => NULL]);

                        $bot_user = BotUser::find($bot_user->id);

                        $botSendMessage->handle($bot_user, 'SYS_SEND_IN_MAX_AFTER_VERIFICATION_FROM_MAX', 'max');
                        $botSendMessage->handle($bot_user, 'SYS_SEND_IN_TELEGRAM_AFTER_VERIFICATION_FROM_MAX', 'telegram');

                        if ($messenger == 'telegram' && isset($webhook['callback_query']['message']['message_id'])) {
                            $telegram->answerCallbackQuery(['callback_query_id' => $webhook['callback_query']['id']]);
                        }

                    }
                }

                //== Генерим колбэки для присоединения ТГ к Макс

                $res = BotUser::whereNotNull('verification_from_telegram')->get();
                foreach ($res as $data) {

                    if ($callback == 'connect_telegram_to_max_'.$data->verification_from_telegram) {

                        BotUser::where('telegram_chat_id', $data->verification_from_telegram)->delete();
                        BotUser::where('id', $bot_user->id)->update(['telegram_chat_id' => $data->verification_from_telegram, 'verification_from_telegram' => NULL]);

                        $bot_user = BotUser::find($bot_user->id);

                        $botSendMessage->handle($bot_user, 'SYS_SEND_IN_MAX_AFTER_VERIFICATION_FROM_TELEGRAM', 'max');
                        $botSendMessage->handle($bot_user, 'SYS_SEND_IN_TELEGRAM_AFTER_VERIFICATION_FROM_TELEGRAM', 'telegram');

                        if ($messenger == 'telegram' && isset($webhook['callback_query']['message']['message_id'])) {
                            $telegram->answerCallbackQuery(['callback_query_id' => $webhook['callback_query']['id']]);
                        }

                    }
                }

                if (file_exists(base_path().'/app/Actions/Local/ClubAccessCallback.php')) {
                    $clubAccessCallback = new ClubAccessCallback();
                    return $clubAccessCallback->handle($telegram, $webhook, $callback, $bot_user);
                }

            } else {
                //== Если ни сообщение, ни колбэк, то возвращаем ошибку
                return "MESSAGE_NOT_FOUND";
            }

        }

    }
}
