<?php
namespace App\Http\Controllers\Project;

use Telegram\Bot\Api;

use App\Actions\Core\Bot\BotGetByID;
use App\Actions\Core\BotSupergroup\BotSupergroups;
use App\Actions\Core\BotUser\BotUserCreateFromTelegram;
use App\Actions\Core\BotUser\BotUserGetFromTelegram;
use App\Actions\Core\Telegram\TelegramGetChatIDFromWebhook;
use App\Actions\Core\Telegram\TelegramMessageGetStartParams;
use App\Actions\Core\Telegram\TelegramWebhookWrite;
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
use App\Actions\Project\ClubAccess\BotWelcomeMessage;
use App\Http\Controllers\Controller;

class ClubAccessController extends Controller
{
    public function club_access(int $bot_id) {

        //== Инициализируем основные классы
        $botGetByID = new BotGetByID();
        $botUserCreateFromTelegram = new BotUserCreateFromTelegram();
        $telegramGetChatIDFromWebhook = new TelegramGetChatIDFromWebhook();
        $botUserGetFromTelegram = new BotUserGetFromTelegram();
        $telegramMessageGetStartParams = new TelegramMessageGetStartParams();
        $botSupergroupsByBot = new BotSupergroups();
        $telegramWebhookWrite = new TelegramWebhookWrite();

        //== Инициализируем классы проекта
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
        $botWelcomeMessage = new BotWelcomeMessage();
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
                $botResetUser->handle($bot_user->id); //== Сбрасываем юзера
                $bot_user = $botUserGetFromTelegram->handle($bot_id, $chat_id); //== И повторно достаем его данные после сброса
            }

            //== Если это /registration, тут обрабатываем регистрацию
            if ($Astart[0] == "/registration") {
                $botRequestAndConfirmEmail->handle($bot_user);
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

                $bot = $botGetByID->handle($bot_id);
                $telegram = new Api($bot->telegram_token);
                $callback=$webhook['callback_query']['data'];

                //== Конец базовой инициализации

                if ($callback == 'EighteenYes') $botEighteenYes->handle($bot_user, $telegram, $webhook); //== Если выбрал Да в вопросе про 18 лет
                if ($callback == 'EighteenNo') $botEighteenNo->handle($bot_id, $telegram, $webhook); //== Если выбрал Нет в вопросе про 18 лет
                if ($callback == 'GoToContacts') $botContacts->handle($bot_id, $telegram, $webhook); //== Если нажал кнопку Контакты
                if ($callback == 'GoToClub') $botGoToClub->handle($telegram, $webhook, $bot_user); //== Если нажал кнопку Стать участником
                if ($callback == 'GoToEmailVerification') $botEmailVerification->handle($telegram, $bot_user, $webhook); //== Если нажата кнопка Подтвердить почту при условии что почта уже введена
                if ($callback == 'GoToEmailChange') $botEmailChange->handle($telegram, $bot_user, $webhook); //== Если нажата кнопка Изменить почту при условии что почта уже введена

            } else {
                //== Если ни сообщение, ни колбэк, то возвращаем ошибку
                return "MESSAGE_NOT_FOUND";
            }

        }

    }
}
