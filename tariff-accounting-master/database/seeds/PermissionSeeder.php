<?php

use Illuminate\Database\Seeder;
use \App\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('permissions')){
            /**
             * Users permissions
             */
            $parent = Permission::firstOrCreate([
                'name'  => 'Пользователь',
            ]);
            Permission::firstOrCreate([
                'name'  => 'Создать пользователя',
                'slug'  => 'users.create',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Обновить пользователя',
                'slug'  => 'users.update',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Показать пользователя',
                'slug'  => 'users.show',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Изменить пользователя',
                'slug'  => 'users.edit',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Показать всех пользователей',
                'slug'  => 'users.index',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Удалить пользователя',
                'slug'  => 'users.delete',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Скачать excel',
                'slug'  => 'users.excel',
                'parent_id' => $parent->id
            ]);

            /**
             * Permissions permissions
             */
            $parent = Permission::firstOrCreate([
                'name'  => 'Разрешения',
            ]);
            Permission::firstOrCreate([
                'name'  => 'Показать все разрешения',
                'slug'  => 'permissions.index',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Создать разрешение',
                'slug'  => 'permissions.create',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Обновить разрешение',
                'slug'  => 'permissions.update',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Изменить разрешение',
                'slug'  => 'permissions.edit',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Показать разрешение',
                'slug'  => 'permissions.show',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Удалить разрешение',
                'slug'  => 'permissions.delete',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Скачать excel',
                'slug'  => 'permissions.excel',
                'parent_id' => $parent->id
            ]);
            /**

            /**
             * Roles permissions
             */
            $parent = Permission::firstOrCreate([
                'name'  => 'Роли',
            ]);
            Permission::firstOrCreate([
                'name'  => 'Показать все роли',
                'slug'  => 'roles.index',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Создать роль',
                'slug'  => 'roles.create',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Изменить роль',
                'slug'  => 'roles.edit',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Обновить роль',
                'slug'  => 'roles.update',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Показать роль',
                'slug'  => 'roles.show',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Удалить роль',
                'slug'  => 'roles.delete',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Скачать excel',
                'slug'  => 'roles.excel',
                'parent_id' => $parent->id
            ]);

            /**
             * Client permissions
             */
            $parent = Permission::firstOrCreate([
                'name'  => 'Клиент',
            ]);
            Permission::firstOrCreate([
                'name'  => 'Показать всех клиентов',
                'slug'  => 'clients.index',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Создать клиента',
                'slug'  => 'clients.create',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Обновить клиента',
                'slug'  => 'clients.update',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Показать клиент',
                'slug'  => 'clients.show',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Изменить клиент',
                'slug'  => 'clients.edit',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Удалить клиента',
                'slug'  => 'clients.delete',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Скачать excel',
                'slug'  => 'clients.excel',
                'parent_id' => $parent->id
            ]);


            /**
             * ContractClient permissions
             */
            $parent = Permission::firstOrCreate([
                'name'  => 'Договоры(Клиента)',
            ]);
            Permission::firstOrCreate([
                'name'  => 'Показать все клиентские контракты',
                'slug'  => 'contractClients.index',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Создать клиентский договор',
                'slug'  => 'contractClients.create',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Обновить клиентский договор',
                'slug'  => 'contractClients.update',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Показать клиентский договор',
                'slug'  => 'contractClients.show',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Изменить клиентский договор',
                'slug'  => 'contractClients.edit',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Удалить клиентский договор',
                'slug'  => 'contractClients.delete',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Скачать excel',
                'slug'  => 'contractClients.excel',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Печать',
                'slug'  => 'contractClients.print',
                'parent_id' => $parent->id
            ]);

            /**
             * Reason permissions
             */
            $parent = Permission::firstOrCreate([
                'name'  => 'Причина',
            ]);
            Permission::firstOrCreate([
                'name'  => 'Показать все причины',
                'slug'  => 'reasons.index',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Создать причину',
                'slug'  => 'reasons.create',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Обновить причину',
                'slug'  => 'reasons.update',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Показать причину',
                'slug'  => 'reasons.show',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Изменить причину',
                'slug'  => 'reasons.edit',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Удалить причину',
                'slug'  => 'reasons.delete',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Скачать excel',
                'slug'  => 'reasons.excel',
                'parent_id' => $parent->id
            ]);

            /**
             * Transaction permissions
             */
            $parent = Permission::firstOrCreate([
                'name'  => 'Транзакции',
            ]);
            Permission::firstOrCreate([
                'name'  => 'Показать все транзакции ',
                'slug'  => 'transactions.index',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Создать транзакция',
                'slug'  => 'transactions.create',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Обновить транзакция',
                'slug'  => 'transactions.update',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Показать транзакция',
                'slug'  => 'transactions.show',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Изменить транзакция',
                'slug'  => 'transactions.edit',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Удалить транзакция',
                'slug'  => 'transactions.delete',
                'parent_id' => $parent->id
            ]);

            /**
             * Payment Type permissions
             */
            $parent = Permission::firstOrCreate([
                'name'  => 'Типы оплаты',
            ]);
            Permission::firstOrCreate([
                'name'  => 'Показать все виды оплаты',
                'slug'  => 'paymentTypes.index',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Создать тип оплаты',
                'slug'  => 'paymentTypes.create',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Обновить тип оплаты',
                'slug'  => 'paymentTypes.update',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Показать тип оплаты',
                'slug'  => 'paymentTypes.show',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Изменить тип оплаты',
                'slug'  => 'paymentTypes.edit',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Удалить тип оплаты',
                'slug'  => 'paymentTypes.delete',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Скачать excel',
                'slug'  => 'paymentTypes.excel',
                'parent_id' => $parent->id
            ]);

            /**
             * Measurements permissions
             */
            $parent = Permission::firstOrCreate([
                'name'  => 'Группы',
            ]);
            Permission::firstOrCreate([
                'name'  => 'Показать все измерения',
                'slug'  => 'measurements.index',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Создать измерения',
                'slug'  => 'measurements.create',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Обновить измерения',
                'slug'  => 'measurements.update',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Показать измерения',
                'slug'  => 'measurements.show',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Изменить измерения',
                'slug'  => 'measurements.edit',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Удалить измерения',
                'slug'  => 'measurements.delete',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Скачать excel',
                'slug'  => 'measurements.excel',
                'parent_id' => $parent->id
            ]);

            /**
             * States permissions
             */
            $parent = Permission::firstOrCreate([
                'name'  => 'Статусы',
            ]);
            Permission::firstOrCreate([
                'name'  => 'Показать все статусы',
                'slug'  => 'states.index',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Создать статус',
                'slug'  => 'states.create',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Обновить статус',
                'slug'  => 'states.update',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Показать статус',
                'slug'  => 'states.show',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Изменить статус',
                'slug'  => 'states.edit',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Удалить статус',
                'slug'  => 'states.delete',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Скачать excel',
                'slug'  => 'states.excel',
                'parent_id' => $parent->id
            ]);

            /**
             * Audits permissions
             */
            $parent = Permission::firstOrCreate([
                'name'  => 'Журнал ',
            ]);
            Permission::firstOrCreate([
                'name'  => 'Показать все журнал',
                'slug'  => 'audits.index',
                'parent_id' => $parent->id
            ]);
            /**
             * userAuthLogs permissions
             */
            $parent = Permission::firstOrCreate([
                'name'  => 'Действия пользователя',
            ]);
            Permission::firstOrCreate([
                'name'  => 'Показать все действия пользователей',
                'slug'  => 'userAuthLogs.index',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Скачать excel',
                'slug'  => 'userAuthLogs.excel',
                'parent_id' => $parent->id
            ]);

            /**
             * Servise permissions
             * */
            $parent = Permission::firstOrCreate([
                'name' => 'Тарифы',
            ]);
            Permission::firstOrCreate([
                'name' => 'Показать все тарифы',
                'slug' => 'services.index',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Создать тариф',
                'slug'  => 'services.create',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Обновить тариф',
                'slug'  => 'services.update',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Показать тариф',
                'slug'  => 'services.show',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Изменить тариф',
                'slug'  => 'services.edit',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Удалить тариф',
                'slug'  => 'services.delete',
                'parent_id' => $parent->id
            ]);

            /**
             * Application permissions
             */
            $parent  = Permission::firstOrCreate([
                'name'  => 'Заявки',
            ]);
            Permission::firstOrCreate([
                'name'  => 'Показать все заявки',
                'slug'  => 'applications.index',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Создать заявку',
                'slug'  => 'applications.create',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Обновить заявку',
                'slug'  => 'applications.update',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Показать заявку',
                'slug'  => 'applications.show',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Изменить заявку',
                'slug'  => 'applications.edit',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Удалить заявку',
                'slug'  => 'applications.delete',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Экспорт в Excel',
                'slug'  => 'applications.excel',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Распечатать',
                'slug'  => 'applications.print',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Показать график заявки',
                'slug'  => 'applications.chart',
                'parent_id' => $parent->id
            ]);

            /**
             * District permissions
             * */
            $parent = Permission::firstOrCreate([
                'name' => 'Районы',
            ]);
            Permission::firstOrCreate([
                'name' => 'Показать все районы',
                'slug' => 'districts.index',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Создать район',
                'slug'  => 'districts.create',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Обновить район',
                'slug'  => 'districts.update',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Показать район',
                'slug'  => 'districts.show',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Изменить район',
                'slug'  => 'districts.edit',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Удалить район',
                'slug'  => 'districts.delete',
                'parent_id' => $parent->id
            ]);

            /**
             * Payment permissions
             * */
            $parent = Permission::firstOrCreate([
                'name' => 'Платежи',
            ]);
            Permission::firstOrCreate([
                'name' => 'Показать все платежи',
                'slug' => 'payments.index',
                'parent_id' => $parent->id
            ]);


            /**
             * Summary Report permissions
             * */
            $parent = Permission::firstOrCreate([
                'name' => 'Суммарный отчет',
            ]);
            Permission::firstOrCreate([
                'name'  => 'Показать суммарный отчет',
                'slug'  => 'summary_report.index',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Скачать excel(суммарный отчет)',
                'slug'  => 'summary_report.index.excel',
                'parent_id' => $parent->id
            ]);


            /**
             * Quarter permissions
             * */
            $parent = Permission::firstOrCreate([
                'name' => 'Квартал',
            ]);
            Permission::firstOrCreate([
                'name' => 'Показать все кварталы',
                'slug' => 'quarters.index',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Создать квартал',
                'slug'  => 'quarters.create',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Обновить квартал',
                'slug'  => 'quarters.update',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Показать квартал',
                'slug'  => 'quarters.show',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Изменить квартал',
                'slug'  => 'quarters.edit',
                'parent_id' => $parent->id
            ]);
            Permission::firstOrCreate([
                'name'  => 'Удалить квартал',
                'slug'  => 'quarters.delete',
                'parent_id' => $parent->id
            ]);
        }
    }
}
