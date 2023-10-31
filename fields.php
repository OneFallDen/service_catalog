<?php

    // Идентификатор смарт-процесса
    $entityTypeId = 179;
    $smartTypeId = 4;

    // Переменная для статусов смарт-процесса
    $smartStatus = 'DT'.$entityTypeId.'_'.$smartTypeId.':';

    // Поля канбан-доски смарт-процесса
    $last_name_field  = "UF_CRM_2_1697998153517";
    $name_field  = "UF_CRM_2_1697998170061";
    $email_field = "UF_CRM_2_1697998263678";
    $comment_field = "UF_CRM_2_1697998389474";
    $mainSectionName_field = "UF_CRM_2_1698163732";
    $review_field = "UF_CRM_2_1698316543";

    // Символьный код инфоблока
    $iblockCode = "catalog_uslug";

    // Символьный код группы техпода
    $groupName = "Support_catalog";

    // Получение индентификатора блока
    $iblockId = CIBlock::GetList([], ["CODE" => $iblockCode])->Fetch()["ID"];

    // Перевод статусов
    $statuses = array(
        "NEW" => "Не обработан",
        "PREPARATION" => 'В работе',
        "CLIENT" => "Обработан",
        "FAIL" => "Отменён",
        "SUCCESS" => "Получен",
    );

    // Переменная с данными пользователя
    global $USER;

    // Создание фабрики для работы со смарт-процессом
    use Bitrix\Crm\Service\Container;
    $factory = Container::getInstance()->getFactory($entityTypeId);

?>
