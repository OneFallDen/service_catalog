<?

    if(isset($_GET['confirm'])){
        $confirm = addslashes(strip_tags(trim($_GET['confirm'])));

        if (CModule::IncludeModule("crm")) {
            global $USER;
            
            $parentServiceId = addslashes(strip_tags(trim($_GET['parentServiceId'])));
            $catalogParentServiceId = addslashes(strip_tags(trim($_GET['catalogParentServiceId'])));
            $serviceName = addslashes(strip_tags(trim($_GET['serviceName'])));
            $parentSectionName = addslashes(strip_tags(trim($_GET['parentSectionName'])));
            $mainSectionName = addslashes(strip_tags(trim($_GET['mainSectionName'])));
            $comment = addslashes(strip_tags(trim($_GET['comment'])));

            // Проверяем, авторизован ли пользователь в Битрикс24
            if ($USER->IsAuthorized()) {
                // Получаем данные пользователя
                $currentUserId = $USER->GetID();
                $arUser = CUser::GetByID($currentUserId)->Fetch();

                // Выбор случайного пользователя саппорта
                if (CModule::IncludeModule("main")) {
                
                    $groupFilter = array("STRING_ID" => $groupName);
                    $groupDb = CGroup::GetList($by = "c_sort", $order = "asc", $groupFilter);
                    if ($group = $groupDb->Fetch()) {
                        $groupId = $group["ID"];
                
                        $userList = array();
                        $userFilter = array("GROUPS_ID" => array($groupId));
                        $userDb = CUser::GetList(($by = "name"), ($order = "asc"), $userFilter);
                        while ($user = $userDb->GetNext()) {
                            $userList[] = $user;
                        }
                
                        if (!empty($userList)) {
                
                            $randomUser = $userList[array_rand($userList)];
                
                            $rndUser = $randomUser["ID"];
                        }
                    }
                }

                $data = [

                    'TITLE' => $parentSectionName . ": " . $serviceName,
                    'ASSIGNED_BY_ID'=>$rndUser,
                    $name_field => $arUser["NAME"],
                    $last_name_field => $arUser["LAST_NAME"],
                    $email_field => $arUser["EMAIL"],
                    $comment_field => $comment,
                    $mainSectionName_field => $mainSectionName,
                    "STAGE_ID"  =>  $smartStatus."NEW",
    
                ];
                
                $item = $factory ->createItem($data); 
                
                $item->save();

                $leadId =  $item['ID'];
        
                if ($leadId) {

                    $to = $arUser["EMAIL"];
                    $subject = "Создание новой заявки";
                    $message = "Успешно создана заявка на услугу $parentSectionName: $serviceName с №$leadId.\nПри изменении статуса заявки Вам придёт письмо.\nТакже отслеживать статус заявки можете в Личном кабинете.";

                    mail($to, $subject, $message);

                    if (CModule::IncludeModule("main")) {
                        $userData = CUser::GetByID($rndUser)->Fetch();
                        if ($userData) {
                            $userEmail = $userData["EMAIL"];
                        }
                    }

                    $to = $userEmail;
                    $subject = "Создание новой заявки";
                    $message = "Создана заявка на услугу $parentSectionName: $serviceName с №$leadId.";

                    mail($to, $subject, $message);

                    echo '
                        <div class="message">
                            <p class="message__text1">Мы получили ваш заказ</p>
                            <p class="message__text2">Номер заказа <span class="nomber-id">'.$leadId.'</span> будет отображаться в <a class="lich-kab" href="?personalAccount=1">личном кабинете</a></p>
                        </div>
                    ';
                    
                }
            } else {
                echo "Пользователь не авторизован в Битрикс24.";
            }
        } else {
            echo "Модуль 'crm' не подключен.";
        }

    } else {

        $parentSectionId = addslashes(strip_tags(trim($_GET['parSecId'])));
        $parentServiceId = addslashes(strip_tags(trim($_GET['parentServiceId'])));
        $catalogParentServiceId = addslashes(strip_tags(trim($_GET['catalogParentServiceId'])));
        $serviceName = addslashes(strip_tags(trim($_GET['serviceName'])));
        $parentSectionName = addslashes(strip_tags(trim($_GET['parentSectionName'])));
        $mainSectionName = addslashes(strip_tags(trim($_GET['mainSectionName'])));

        $arFilter = array(
            "IBLOCK_ID" => $iblockId, // ID вашего инфоблока
            "SECTION_ID" => $parentSectionId,
            "ACTIVE" => "Y", // Можно добавить другие фильтры
        );

        $arSelect = array("ID", "NAME", "PREVIEW_TEXT", "CODE"); // Выбираем необходимые свойства услуги

        $rsElements = CIBlockElement::GetList(array("SORT" => "ASC"), $arFilter, false, false, $arSelect);

        while ($arElement = $rsElements->GetNext()) {
            if ($arElement['NAME'] == $serviceName) {
                $serviceDescription = $arElement['PREVIEW_TEXT'];
            }
        }

        echo '<div class="card-block">';

        echo '
            <div class="card-block__img">
                <img src="img/img-card.jpg" alt="картинка">
            </div>
        ';

        echo "
            <div class='card-block__info'>
                <h2>$parentSectionName</h2>
                <h3>$serviceName</h3>
                <p>Описание: $serviceDescription</p>
            </div>
        ";

        echo '</div>';

        echo "
            <div class='order-block'>
                <p class='order-block__name'>Заказ услуги</p>
                <form class='order-block__inputs' action='?parentServiceId=parentServiceId&catalogParentServiceId=catalogParentServiceId&serviceName=serviceName&parentSectionName=parentSectionName&confirm=yes&comment=comment'>
                    <input name='comment' type='text' placeholder='Комментарий'>
                    <input id='noDisp' name='parentServiceId' type='text' value='$parentServiceId'>
                    <input id='noDisp' name='catalogParentServiceId' type='text' value='$catalogParentServiceId'>
                    <input id='noDisp' name='serviceName' type='text' value='$serviceName'>
                    <input id='noDisp' name='parentSectionName' type='text' value='$parentSectionName'>
                    <input id='noDisp' name='mainSectionName' type='text' value='$mainSectionName'>
                    <input id='noDisp' name='confirm' type='text' value='yes'>
                    <input id='button' type='submit' value='Отправить'>
                </form>
            </div>
        ";
    }

?>