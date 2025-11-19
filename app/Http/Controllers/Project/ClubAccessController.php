<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Telegram\Bot\Api;

use App\Actions\Core\Bot\BotGetByID;
use App\Actions\Core\BotBranch\BotBranchRun;
use App\Actions\Core\BotBranch\BotBranchEndOnRestart;
use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\BotSupergroup\BotSupergroups;
use App\Actions\Core\BotUser\BotUserCreateFromTelegram;
use App\Actions\Core\BotUser\BotUserGetFromTelegram;
use App\Actions\Core\BotUser\BotUserSetBranch;
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
use App\Actions\Project\ClubAccess\BotMainMenuMessage;
use App\Actions\Project\ClubAccess\BotRequestAndConfirmEmail;
use App\Actions\Project\ClubAccess\BotResetUser;
use App\Actions\Project\ClubAccess\BotUserRecurrentDisable;
use App\Actions\Project\ClubAccess\BotWelcomeMessage;

class ClubAccessController extends Controller
{
    public function club_access(int $bot_id) {

        //== Инициализируем основные классы
        $botBranchRun = new BotBranchRun();
        $botBranchEndOnRestart = new BotBranchEndOnRestart();
        $botGetByID = new BotGetByID();
        $botSendMessage = new BotSendMessage();
        $botSupergroupsByBot = new BotSupergroups();
        $botUserCreateFromTelegram = new BotUserCreateFromTelegram();
        $botUserGetFromTelegram = new BotUserGetFromTelegram();
        $botUserSetBranch = new BotUserSetBranch();
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
        $botMainMenuMessage = new BotMainMenuMessage();
        $botResetUser = new BotResetUser();
        $botRequestAndConfirmEmail = new BotRequestAndConfirmEmail();
        $botUserRecurrentDisable = new BotUserRecurrentDisable();
        $botWelcomeMessage = new BotWelcomeMessage();

        $bot = $botGetByID->handle($bot_id);
        $telegram = new Api($bot->telegram_token);

        //== Заканчиваем инициацию классов


        //== Делаем первичную обработку
        //== Пишем вебхук от телеграма в БД и в переменную
        $webhook = $telegramWebhookWrite->handle(file_get_contents('php://input'), $bot_id);

        //== Получаем chat_id из вебхука, и если не найден, возвращаем ошибку
        (int) $chat_id = $telegramGetChatIDFromWebhook->handle($webhook);
        if ($chat_id == 0) return "CHAT_ID_NOT_FOUND";

        //== Получаем ID всех групп, к которым привязан бот
        $groups = $botSupergroupsByBot->handle($bot_id);

        //== И блокируем отправку в общий чат, если сообщение пришло от групп
        if (in_array($chat_id, $groups)) die();

        //== А если найден, то пишем в БД и идем дальше
        $botUserCreateFromTelegram->handle($chat_id, $bot_id, $webhook);

        //== Достаем данные юзера по chat_id после создания
        $bot_user = $botUserGetFromTelegram->handle($bot_id, $chat_id);
        //== Заканчиваем первичную обработку

        //== Переходим к работе с колбэком ТГ
        //== Если сообщение существует
        if (isset($webhook['message'])) {

            //== Проверяем, есть ли входящие параметры по ссылке на переход в ТГ к разговору с ботом
            (array) $Astart = $telegramMessageGetStartParams->handle($webhook);

            //== Если это /start, тут обрабатываем старт
            if ($Astart[0] == "/start") {

                if (count($Astart) == 2) {

                    $branch_data = base64_decode($Astart[1]);
                    $branch_data = explode("|", $branch_data);

                    if ($branch_data[0] == 1) $botUserSetBranch->handle($bot_user, 'BRANCH_MAIN');
                    if ($branch_data[0] == 2) $botBranchRun->handle($bot_user, $branch_data[1]);
                    if ($branch_data[0] == 3) $referralProgramRunForReferral->handle($bot_user, $branch_data);
                    if ($branch_data[0] == 4) {
                        return 'bbbbb';
                        $botUserSetBranch->handle($bot_user, $branch_data[1]);
                        $botSendMessage->handle($bot_user, 'SYS_WELCOME_MESSAGE');
                        $botUserSetBranch->handle($bot_user, 'BRANCH_MAIN');
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
                $bot_user = $botUserGetFromTelegram->handle($bot_id, $chat_id); //== И повторно достаем его данные после сброса
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

            //== Если это /subscription, тут обрабатываем регистрацию
            if ($Astart[0] == "/subscription") {
                $botSendMessage->handle($bot_user, 'SYS_USER_SUBSCRIPTION_DATA');
                die();
            }

            //== Если это /subscription, тут обрабатываем регистрацию
            if ($Astart[0] == "/subscribe") {
                $botSendMessage->handle($bot_user, 'SYS_PAY_IN_BOT_ALL_TARIFFS');
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
                    $botGoToClub->handle($telegram, $webhook, $bot_user);
                    die();
                }

            }


            //== Заканчиваем обрабатывать входящие параметры по ссылке на переход в ТГ к разговору с ботом

            //== Обрабатываем листенеры
            $botListenerEmail->handle($webhook, $bot_user); //== Проверяем, ожидает ли юзер ввода почты

            //== Запускаем основной скрипт клуба
            //== Проверяем, получал ли юзер приветственное сообщение

            $botWelcomeMessage->handle($bot_user); //== Обрабатываем первичное стартовое сообщение (до ввода имени)
            $botHandName->handle($bot_user, $webhook); //== Обрабатываем HandName - вручную введенное юзером имя
            $botEighteen->handle($bot_user); //== Обрабатываем подтверждение 18 лет
            $botMainMenuMessage->handle($bot_user); //== Обрабатываем сообщение с главным меню

        } else {

            if (isset($webhook['callback_query'])) {
                //== Проверяем, является ли это колбэк-запросом

                //== Инициализируем базовые данные и классы

                $callback=$webhook['callback_query']['data'];

                //== Конец базовой инициализации

                if ($callback == 'EighteenYes') $botEighteenYes->handle($bot_user, $telegram, $webhook); //== Если выбрал Да в вопросе про 18 лет
                if ($callback == 'EighteenNo') $botEighteenNo->handle($bot_id, $telegram, $webhook); //== Если выбрал Нет в вопросе про 18 лет
                if ($callback == 'GoToContacts') $botContacts->handle($bot_id, $telegram, $webhook); //== Если нажал кнопку Контакты
                if ($callback == 'GoToClub') $botGoToClub->handle($telegram, $webhook, $bot_user); //== Если нажал кнопку Стать участником
                if ($callback == 'GoToEmailVerification') {
                    return $botEmailVerification->handle($telegram, $bot_user, $webhook); //== Если нажата кнопка Подтвердить почту при условии что почта уже введена
                }
                if ($callback == 'GoToEmailChange') $botEmailChange->handle($telegram, $bot_user, $webhook); //== Если нажата кнопка Изменить почту при условии что почта уже введена
                if ($callback == 'BotUserRecurrentDisable') $botUserRecurrentDisable->handle($telegram, $bot_user, $webhook);

                if ($callback == 'GoToMainMenuMessage') {

                    if (isset($webhook['callback_query']['message']['message_id'])) {
                        $telegram->answerCallbackQuery(['callback_query_id' => $webhook['callback_query']['id']]);
                    }

                    $botMainMenuMessage->handle($bot_user);
                }

                if ($callback == 'GoToStart') {

                    if (isset($webhook['callback_query']['message']['message_id'])) {
                        $telegram->answerCallbackQuery(['callback_query_id' => $webhook['callback_query']['id']]);
                    }

                    if ($bot_user->date_end != NULL && $bot_user->date_end > date('Y-m-d', time())) {
                        $botSendMessage->handle($bot_user, 'SYS_SUCCESS_MESSAGE');
                        die();
                    } else {

                    }

                    $botResetUser->handle($bot_user->id); //== Сбрасываем юзера
                    $bot_user = $botUserGetFromTelegram->handle($bot_id, $chat_id); //== И повторно достаем его данные после сброса

                }

                if ($callback == 'GoToFullTariffs') {

                    if (isset($webhook['callback_query']['message']['message_id'])) {
                        $telegram->answerCallbackQuery(['callback_query_id' => $webhook['callback_query']['id']]);
                    }

                    $botSendMessage->handle($bot_user, 'SYS_PAY_IN_BOT_ALL_TARIFFS');
                    die();
                }

            } else {
                //== Если ни сообщение, ни колбэк, то возвращаем ошибку
                return "MESSAGE_NOT_FOUND";
            }

        }

    }
}
