<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\BotTemplateMessage;
use App\Models\Core\BotTemplateMessageButton;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $bot_template_message_id = BotTemplateMessage::create(['name' => 'Приветственное сообщение',
            'bot_template_id' => '1',
            'bot_template_branch_id' => '1',
            'bot_message_appointment_id' => '1',
            'bot_message_type_id' => '1',
            'text' => 'Добро пожаловать в <b>“Точку И”</b> 💜%0A%0AВы здесь — и это уже многое значит. Значит, вы выбрали заботу. О себе. О теле. О своём «внутри».%0A%0AЭтот клуб — пространство, которого мне самой когда-то не хватало.%0AГде можно говорить про все, что внутри и не чувствовать себя странной или «не такой».%0A%0AЯ здесь не только как врач. Я здесь как женщина. Мама. Человек.%0A%0AЧто вас ждёт:%0A%0A📌 Методички — понятные и практичные%0A📆 Челленджи — без перегруза, в ритме жизни%0A🎥 Эфиры — живые и настоящие%0A🗂 Навигация по рубрикам: уход, питание, эмоции, отношения%0A💌 Анонимные вопросы — можно задать всё, что болит%0A👩‍⚕️ Открытые консультации%0A🫶 Поддержка. Простота. Настоящесть.',
            'delete_through' => '0',
            'delete_keyboard_through' => '0',
            'pause_after_message' => '0']);

        BotTemplateMessageButton::create(['name' => 'СТАТЬ УЧАСТНИКОМ',
            'bot_template_message_id' => $bot_template_message_id->id,
            'bot_message_button_type_id' => '2',
            'bot_message_callback_id' => '8',
            'tracking' => '0',
            'pos'=> '1']);

        BotTemplateMessageButton::create(['name' => 'Публичная оферта',
            'bot_template_message_id' => $bot_template_message_id->id,
            'bot_message_button_type_id' => '1',
            'url' => 'https://гомеопат.online/DOCS',
            'tracking' => '0',
            'pos'=> '2']);

        BotTemplateMessageButton::create(['name' => 'Политика конфиденциальности',
            'bot_template_message_id' => $bot_template_message_id->id,
            'bot_message_button_type_id' => '1',
            'url' => 'https://гомеопат.online/DOCS',
            'tracking' => '0',
            'pos'=> '3']);

        BotTemplateMessageButton::create(['name' => 'Написать в поддержку',
            'bot_template_message_id' => $bot_template_message_id->id,
            'bot_message_button_type_id' => '1',
            'url' => 'https://wa.me/+79114668105',
            'tracking' => '0',
            'pos'=> '4']);

        $bot_template_message_id = BotTemplateMessage::create(['name' => 'Запрос Email у пользователя',
            'bot_template_id' => '1',
            'bot_template_branch_id' => '1',
            'bot_message_appointment_id' => '3',
            'bot_message_type_id' => '1',
            'text' => 'Чтобы предоставить вам доступ, пожалуйста, отправьте мне сообщением <b>вашу электронную почту.</b>%0A%0AНе переживайте, беспокоить спамом не будем.',
            'delete_through' => '0',
            'delete_keyboard_through' => '0',
            'pause_after_message' => '0']);

        $bot_template_message_id = BotTemplateMessage::create(['name' => 'Пользователь ввел неверный email',
            'bot_template_id' => '1',
            'bot_message_appointment_id' => '5',
            'bot_message_type_id' => '1',
            'text' => 'Возможно, вы допустили ошибку при вводе E-mail. Отправьте ваш E-mail еще раз.',
            'delete_through' => '0',
            'delete_keyboard_through' => '0',
            'pause_after_message' => '0']);

        $bot_template_message_id = BotTemplateMessage::create(['name' => 'Подтверждение Email пользователя',
            'bot_template_id' => '1',
            'bot_message_appointment_id' => '4',
            'bot_message_type_id' => '1',
            'text' => 'Это ваша электронная почта: VAR_USER_EMAIL?%0A%0AДавайте проверим, если всё верно, перейдем к следующему шагу! Спасибо!',
            'delete_through' => '0',
            'delete_keyboard_through' => '0',
            'pause_after_message' => '0']);

        BotTemplateMessageButton::create(['name' => '✔️ Подтвердить',
            'bot_template_message_id' => $bot_template_message_id->id,
            'bot_message_button_type_id' => '2',
            'bot_message_callback_id' => '3',
            'tracking' => '0',
            'pos'=> '1']);


        BotTemplateMessageButton::create(['name' => '✏️ Изменить',
            'bot_template_message_id' => $bot_template_message_id->id,
            'bot_message_button_type_id' => '2',
            'bot_message_callback_id' => '4',
            'tracking' => '0',
            'pos'=> '2']);


        $bot_template_message_id = BotTemplateMessage::create(['name' => 'Оплата внутри бота',
            'bot_template_id' => '1',
            'bot_template_branch_id' => '1',
            'bot_message_appointment_id' => '13',
            'bot_message_type_id' => '1',
            'text' => 'После оплаты бот предоставит вам доступ в закрытый телеграм-канал клуба%0A"Точка И" Ирины Шевченко.%0A%0AВ клубе предусмотрено автоматическое продление. Отказаться от автоматического продления вы можете в любое время после оплаты, нажав на кнопку "Меню" - "Личный кабинет" в этом боте%0A%0AЧтобы оплатить, нажмите кнопку ниже 👇',
            'delete_through' => '0',
            'delete_keyboard_through' => '0',
            'pause_after_message' => '0']);

        BotTemplateMessageButton::create(['name' => 'ОПЛАТИТЬ',
            'bot_template_message_id' => $bot_template_message_id->id,
            'bot_message_button_type_id' => '4',
            'pay_system_id' => '1',
            'product_id' => '7',
            'tracking' => '0',
            'pos'=> '1']);

        $bot_template_message_id = BotTemplateMessage::create(['name' => 'Кабинет - рекуррент включен',
            'bot_template_id' => '1',
            'bot_message_appointment_id' => '20',
            'bot_message_type_id' => '1',
            'text' => 'У вас оплачена подписка до VAR_USER_DATE_END.%0AДля вашего аккаунта включено автопродление. Списание произойдет VAR_USER_DATE_END%0AВы можете отказаться от подписки, нажав на кнопку 👇',
            'delete_through' => '0',
            'delete_keyboard_through' => '0',
            'pause_after_message' => '0']);

        BotTemplateMessageButton::create(['name' => 'Вернуться назад',
            'bot_template_message_id' => $bot_template_message_id->id,
            'bot_message_button_type_id' => '2',
            'bot_message_callback_id' => '2',
            'tracking' => '0',
            'pos'=> '1']);

        BotTemplateMessageButton::create(['name' => 'Отказаться от подписки',
            'bot_template_message_id' => $bot_template_message_id->id,
            'bot_message_button_type_id' => '2',
            'bot_message_callback_id' => '10',
            'tracking' => '0',
            'pos'=> '2']);

        $bot_template_message_id = BotTemplateMessage::create(['name' => 'Кабинет - рекуррент выключен',
            'bot_template_id' => '1',
            'bot_message_appointment_id' => '21',
            'bot_message_type_id' => '1',
            'text' => 'Для вашего аккаунта отключено автопродление подписки на клуб.%0A<b>У вас оплачен доступ до VAR_USER_DATE_END.</b>%0AЧтобы возобновить подписку, проведите оплату в ручном режиме. Чтобы восстановить автопродление, нажмите кнопку "К оплате", и проведите оплату в ручном режиме. Оплаченный период будет добавлен к текущему сроку доступа%0A%0AНе пропадет ни один день.',
            'delete_through' => '0',
            'delete_keyboard_through' => '0',
            'pause_after_message' => '0']);

        BotTemplateMessageButton::create(['name' => 'Вернуться назад',
            'bot_template_message_id' => $bot_template_message_id->id,
            'bot_message_button_type_id' => '2',
            
           
           
            'bot_message_callback_id' => '2',
            
            'tracking' => '0',
            'pos'=> '2']);

        BotTemplateMessageButton::create(['name' => 'К оплате',
            'bot_template_message_id' => $bot_template_message_id->id,
            'bot_message_button_type_id' => '2',
            
           
           
            'bot_message_callback_id' => '8',
            
            'tracking' => '0',
            'pos'=> '1']);

        $bot_template_message_id = BotTemplateMessage::create(['name' => 'Кабинет - сообщение после отключения рекуррента',
            'bot_template_id' => '1',
            'bot_message_appointment_id' => '2',
            'bot_message_type_id' => '1',
            'text' => 'Для вашего аккаунта отключено автопродление подписки на клуб.%0A<b>У вас оплачен доступ до VAR_USER_DATE_END.</b>%0AЧтобы возобновить подписку, проведите оплату в ручном режиме. Чтобы восстановить автопродление, нажмите кнопку "К оплате", и проведите оплату в ручном режиме. Оплаченный период будет добавлен к текущему сроку доступа%0A%0AНе пропадет ни один день.',
            'delete_through' => '0',
            'delete_keyboard_through' => '0',
            'pause_after_message' => '0']);

        BotTemplateMessageButton::create(['name' => 'К оплате',
            'bot_template_message_id' => $bot_template_message_id->id,
            'bot_message_button_type_id' => '2',
            
           
           
            'bot_message_callback_id' => '8',
            
            'tracking' => '0',
            'pos'=> '1']);

        BotTemplateMessageButton::create(['name' => 'Вернуться назад',
            'bot_template_message_id' => $bot_template_message_id->id,
            'bot_message_button_type_id' => '2',
            
           
           
            'bot_message_callback_id' => '2',
            
            'tracking' => '0',
            'pos'=> '1']);;

        $bot_template_message_id = BotTemplateMessage::create(['name' => 'Заявка на вступление в чат отклоненае',
            'bot_template_id' => '1',
            'bot_message_appointment_id' => '23',
            'bot_message_type_id' => '1',
            'text' => 'Ваша заявка отклонена.%0AНе смог найти вашу оплату.',
            'delete_through' => '0',
            'delete_keyboard_through' => '0',
            'pause_after_message' => '0']);

        BotTemplateMessageButton::create(['name' => 'ОПЛАТИТЬ КЛУБ',
            'bot_template_message_id' => $bot_template_message_id->id,
            'bot_message_button_type_id' => '2',
            
           
           
            'bot_message_callback_id' => '8',
            
            'tracking' => '0',
            'pos'=> '1']);

        $bot_template_message_id = BotTemplateMessage::create(['name' => 'Заявка на вступление в чат одобрена',
            'bot_template_id' => '1',
            'bot_message_appointment_id' => '24',
            'bot_message_type_id' => '1',
            'text' => 'Ваша заявка одобрена.%0A%0AВы стали участником клуба.',
            'delete_through' => '0',
            'delete_keyboard_through' => '0',
            'pause_after_message' => '0']);

        BotTemplateMessageButton::create(['name' => 'ВОЙТИ В КАНАЛ',
            'bot_template_message_id' => $bot_template_message_id->id,
            'bot_message_button_type_id' => '1',
            
           
            'url' => 'https://t.me/+vxaOHk4HH3BlNzAy',
           
            
            'tracking' => '0',
            'pos'=> '1']);

        $bot_template_message_id = BotTemplateMessage::create(['name' => 'Выдача доступа в чат',
            'bot_template_id' => '1',
            'bot_template_branch_id' => '1',
            'bot_message_appointment_id' => '2',
            'bot_message_type_id' => '1',
            'text' => 'У вас оформлена подписка на <b>клуб "Точка И" Ирины Шевченко до VAR_USER_DATE_END!</b>%0A%0AПодпишитесь на канал и подключайтесь к ближайшему эфиру.%0A%0AС заботой,%0A<b>Команда Точка И</b>',
            'delete_through' => '0',
            'delete_keyboard_through' => '0',
            'pause_after_message' => '0']);

        BotTemplateMessageButton::create(['name' => 'ПРИСОЕДИНИТЬСЯ',
            'bot_template_message_id' => $bot_template_message_id->id,
            'bot_message_button_type_id' => '1',
            
           
            'url' => 'https://t.me/+vxaOHk4HH3BlNzAy',
           
            
            'tracking' => '0',
            'pos'=> '1']);

        BotTemplateMessageButton::create(['name' => 'Написать в поддержку',
            'bot_template_message_id' => $bot_template_message_id->id,
            'bot_message_button_type_id' => '1',
            
           
            'url' => 'https://wa.me/+79114668105',
           
            
            'tracking' => '0',
            'pos'=> '2']);

        $bot_template_message_id = BotTemplateMessage::create(['name' => 'Рассылка 1',
            'bot_template_id' => '1',
            'bot_message_appointment_id' => '25',
            'bot_message_type_id' => '1',
            'text' => 'Синдром хорошей девочки ворует радость 💔%0AМы привыкли любить других «просто так», но к себе беспощадны: нельзя устать, нельзя отдыхать, нужно быть только на отлично. В итоге — чувство вины даже за минуту покоя. Пора это менять.%0A%0A✨ Сегодня — последний день, чтобы войти в клуб ТОЧКА И.%0AИиии мы отправляемся в интенсив по фейсфитнесу))))%0A%0AМы уже начали:%0A— уход за кожей после 25 (базовые знания для каждой девочки),%0A— интенсив по омоложению лица,%0A— «синдром хорошей девочки» — честный разговор о том, как перестать гнобить себя.%0A%0A🎁 В подарок методичка «Что делать при ОРВИ».%0A%0AСтоимость входа сегодня — 1900 ₽. Завтра цена будет выше.%0A%0A',
            'delete_through' => '0',
            'delete_keyboard_through' => '0',
            'pause_after_message' => '0']);

        BotTemplateMessageButton::create(['name' => '👉 Вступить в клуб',
            'bot_template_message_id' => $bot_template_message_id->id,
            'bot_message_button_type_id' => '2',
            
           
           
            'bot_message_callback_id' => 8,
            
            'tracking' => '0',
            'pos'=> '1']);

        $bot_template_message_id = BotTemplateMessage::create(['name' => 'Есть другой пользователь с этим Email',
            'bot_template_id' => '1',
            'bot_message_appointment_id' => '17',
            'bot_message_type_id' => '1',
            'text' => 'К сожалению, по почте, которую вы прислали уже получил доступ другой человек.%0A%0AНапишите в службу заботы, они разберутся со сложившейся ситуацией ❤️%0A%0AИли отправьте мне другую почту.',
            'delete_through' => '0',
            'delete_keyboard_through' => '0',
            'pause_after_message' => '0']);



        $bot_template_message_id = BotTemplateMessage::create(['name' => 'Уведомление за 3 дня до списания рекуррента',
            'bot_template_id' => '1',
            'bot_template_branch_id' => '1',
            'bot_message_appointment_id' => '32',
            'bot_message_type_id' => '1',
            'text' => 'Напоминаем, что <b>уже через 3 дня ваша подписка на клуб Ирины Шевченко "Точка И" будет автоматически продлеваться</b>, и средства спишутся с вашей карты.

А впереди — эфир гинеколога, методичка «Что делать при болезненных месячных», питание при миомах и упражнения для женского здоровья.

Если вы с нами — просто наслаждайтесь процессом, никаких действий не требуется, всё продлится автоматически.

Если вдруг решите, что не готовы продлить участие — напишите нам заранее, чтобы мы успели отключить автопродление.',
            'funnel_id' => '1',
            'funnel_condition_id' => '17',
            'funnel_condition_trigger_id' => '2',
            'funnel_days' => '3',
            'funnel_hours' => '0',
            'funnel_minutes' => '0',
            'delete_through' => '0',
            'delete_keyboard_through' => '0',
            'pause_after_message' => '0']);

        BotTemplateMessageButton::create(['name' => 'Написать в поддержку',
            'bot_template_message_id' => $bot_template_message_id->id,
            'bot_message_button_type_id' => '1',
            
           
            'url' => 'https://wa.me/+79114668105',
           
            
            'tracking' => '0',
            'pos'=> '1']);

        $bot_template_message_id = BotTemplateMessage::create(['name' => 'Уведомление за 1 день до списания рекуррента',
            'bot_template_id' => '1',
            'bot_template_branch_id' => '1',
            'bot_message_appointment_id' => '33',
            'bot_message_type_id' => '1',
            'text' => 'Уже завтра ваша подписка на клуб Ирины Шевченко "Точка И" будет автоматически продлена, и средства спишутся с вашей карты. 💜

Не теряй место рядом — оставайся с нами, чтобы не пропустить новые материалы и эфиры месяца.

Если вы с нами — просто наслаждайтесь процессом, никаких действий не требуется, всё продлится автоматически.

Если вдруг решите, что не готовы продлить участие — напишите нам заранее, чтобы мы успели отключить автопродление.',
            'funnel_id' => '1',
            'funnel_condition_id' => '17',
            'funnel_condition_trigger_id' => '2',
            'funnel_days' => '1',
            'funnel_hours' => '0',
            'funnel_minutes' => '0',
            'delete_through' => '0',
            'delete_keyboard_through' => '0',
            'pause_after_message' => '0']);

        BotTemplateMessageButton::create(['name' => 'Написать в поддержку',
            'bot_template_message_id' => $bot_template_message_id->id,
            'bot_message_button_type_id' => '1',
            
           
            'url' => 'https://wa.me/+79114668105',
           
            
            'tracking' => '0',
            'pos'=> '1']);

        $bot_template_message_id = BotTemplateMessage::create(['name' => 'Уведомление за 3 дня без рекуррента',
            'bot_template_id' => '1',
            'bot_template_branch_id' => '1',
            'bot_message_appointment_id' => '34',
            'bot_message_type_id' => '1',
            'text' => '⚡️ «Три дня пролетают незаметно».
Через 3 дня твой доступ к клубу «ТОЧКА И» закончится.

А впереди — эфир гинеколога, методичка «Что делать при болезненных месячных», питание при миомах и упражнения для женского здоровья.
Продли участие заранее, чтобы остаться в клубе.',
            'funnel_id' => '1',
            'funnel_condition_id' => '16',
            'funnel_condition_trigger_id' => '2',
            'funnel_days' => '3',
            'funnel_hours' => '0',
            'funnel_minutes' => '0',
            'delete_through' => '0',
            'delete_keyboard_through' => '0',
            'pause_after_message' => '0']);

        BotTemplateMessageButton::create(['name' => 'Продлить доступ',
            'bot_template_message_id' => $bot_template_message_id->id,
            'bot_message_button_type_id' => '2',
            
           
           
            'bot_message_callback_id' => '8',
            
            'tracking' => '0',
            'pos'=> '1']);

        $bot_template_message_id = BotTemplateMessage::create(['name' => 'Уведомление за 1 день без рекуррента',
            'bot_template_id' => '1',
            'bot_template_branch_id' => '1',
            'bot_message_appointment_id' => '35',
            'bot_message_type_id' => '1',
            'text' => '⏳ «Завтра тебя может выбросить из клуба».
Доступ заканчивается уже завтра.

Не теряй место рядом — оставайся с нами, чтобы не пропустить новые материалы и эфиры месяца.',
            'funnel_id' => '1',
            'funnel_condition_id' => '16',
            'funnel_condition_trigger_id' => '2',
            'funnel_days' => '1',
            'funnel_hours' => '0',
            'funnel_minutes' => '0',
            'delete_through' => '0',
            'delete_keyboard_through' => '0',
            'pause_after_message' => '0']);

        BotTemplateMessageButton::create(['name' => 'Остаться в клубе',
            'bot_template_message_id' => $bot_template_message_id->id,
            'bot_message_button_type_id' => '2',
            
           
           
            'bot_message_callback_id' => '8',
            
            'tracking' => '0',
            'pos'=> '1']);


        $bot_template_message_id = BotTemplateMessage::create(['name' => 'Рекуррент в боте провален',
            'bot_template_id' => '1',

            'bot_template_branch_id' => '1',
            'bot_message_appointment_id' => '100',
            'bot_message_type_id' => '1',
            'text' => 'Ой-ой 🙄 Оплата не прошла.

Я не смог списать автоматическое продление клуба Ирины Шевченко "Точка И".

В 23:30 по МСК я удалю Вас из клуба. После этого Вы не сможете оплатить по ранее зафиксированной индивидуальной стоимости, но вы можете сделать это сейчас.

Для этого нажмите на кнопку ниже 🔽',
            'delete_through' => 0,
            'delete_keyboard_through' => 0,
            'pause_after_message' => 0]);

        BotTemplateMessageButton::create(['name' => 'Перейти к оплате',
            'bot_template_message_id' => $bot_template_message_id->id,
            'bot_message_button_type_id' => '2',
            
           
           
            'bot_message_callback_id' => '8',
            
            'tracking' => '0',
            'pos'=> '1']);



        $bot_template_message_id = BotTemplateMessage::create(['name' => 'Сразу после бана',
            'bot_template_id' => '1',

            'bot_template_branch_id' => '1',
            'bot_message_appointment_id' => '36',
            'bot_message_type_id' => '1',
            'text' => '💔 «Нам грустно, что ты больше не с нами».
Твой доступ к клубу закончился.

А у нас стартует новый месяц про женское здоровье: эфир гинеколога, методичка «Что делать при болезненных месячных», упражнения и питание для женского здоровья.

Ты можешь вернуться в любой момент.',
            'funnel_id' => '2',
            'funnel_condition_id' => '8',
            'funnel_condition_trigger_id' => '1',
            'delete_through' => '0',
            'delete_keyboard_through' => '0',
            'pause_after_message' => '0']);

        BotTemplateMessageButton::create(['name' => 'Вернуться в клуб',
            'bot_template_message_id' => $bot_template_message_id->id,
            'bot_message_button_type_id' => '2',
            
           
           
            'bot_message_callback_id' => '8',
            
            'tracking' => '0',
            'pos'=> '1']);



        $bot_template_message_id = BotTemplateMessage::create(['name' => 'Через 3 дня после бана',
            'bot_template_id' => '1',

            'bot_template_branch_id' => '1',
            'bot_message_appointment_id' => '94',
            'bot_message_type_id' => '1',
            'text' => '🥺 «Три дня тебя нет в клубе — и мы уже скучаем».
Внутри уже идут новые эфиры и обсуждения, а твое место пустует.

Хочешь снова быть в женском круге? Возвращайся 👇',
            'funnel_id' => '2',
            'funnel_condition_id' => '8',
            'funnel_condition_trigger_id' => '3',
            'funnel_days' => '3',
            'funnel_hours' => '0',
            'funnel_minutes' => '0',
            'delete_through' => '0',
            'delete_keyboard_through' => '0',
            'pause_after_message' => '0']);

        BotTemplateMessageButton::create(['name' => 'Вернуться',
            'bot_template_message_id' => $bot_template_message_id->id,
            'bot_message_button_type_id' => '2',
            
           
           
            'bot_message_callback_id' => '8',
            
            'tracking' => '0',
            'pos'=> '1']);



        $bot_template_message_id = BotTemplateMessage::create(['name' => 'Через 7 дней после бана',
            'bot_template_id' => '1',

            'bot_template_branch_id' => '1',
            'bot_message_appointment_id' => '95',
            'bot_message_type_id' => '1',
            'text' => '⌛️ «Неделя без клуба — как будто тебя не хватает».
Мы продолжаем делиться результатами и поддержкой, и нам важно, чтобы ты тоже была рядом.

Будем рады видеть тебя снова внутри клуба.',
            'funnel_id' => '2',
            'funnel_condition_id' => '8',
            'funnel_condition_trigger_id' => '3',
            'funnel_days' => '7',
            'funnel_hours' => '10',
            'funnel_minutes' => '0',
            'delete_through' => '0',
            'delete_keyboard_through' => '0',
            'pause_after_message' => '0']);

        BotTemplateMessageButton::create(['name' => 'Вернуться в клуб',
            'bot_template_message_id' => $bot_template_message_id->id,
            'bot_message_button_type_id' => '2',
            
           
           
            'bot_message_callback_id' => '8',
            
            'tracking' => '0',
            'pos'=> '1']);


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
