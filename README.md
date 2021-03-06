<p align="center">
    <h1 align="center">URL Monitor</h1>
    <br>
</p>

Приложение осуществляет мониторинг, введённых пользователем ссылок. Выполнено на Yii2 Advanced, frontend модуль не используется.

Применение миграций:
```
php yii migrate
```

При миграциях создаётся пользователь "admin" c паролем "qwerty". Пароль можно сменить в админке либо в консоли по команде:
```
php yii installer/set-password admin
```

Создание ролей и разрешений (RBAC):
```
php yii installer/init-roles
```

Активация нового пользователя осуществляется пользователем с правами администратора.

При создании новой ссылки заполняются поля:
- `url` - адрес ссылки  
- `user agent` - user agent 
- `request` - тип запроса (на данный момент реализован только head) 
- `expected responses` - ожидаемые ответы через запятую (на данный момент - ожидаемые коды ответа)
- `period` - период мониторинга в минтуах
- `active` - нужно ли монторить ссылку (yes или no)

Создание нового user agent осуществляется пользователями с правами администратора.

Если полученный код ответа, не является ожидаемым, то уведомление отправляется на email и в Telegram пользователю, создавшему ссылку.
Также происходит запись в лог MonitorURL.txt (располагается в корне проекта).

Для того чтобы, происходила отправка в Telegram, нужно создать бота и указать его токен в `common/config/params`. Также пользователь должен позволить боту отправлять себе сообщения, для этого достаточно просто начать диалог с ботом.

При неудачной отправке уведомления в Telegram пользователю происходит запись в лог TelegramBotError.txt (располагается в корне проекта).