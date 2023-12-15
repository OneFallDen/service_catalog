
<h1 class="name-group"><?=$result['sectionName']?></h1>;

<div class="cards">   
    <?php while ($val = $result['sections']->GetNext()): ?>
                                <a href="">
                                    <div class="card">
                                        <div class="card__img">
                                            <img class="card__img-item" src="img/img-card.jpg" alt="..."> 
                                        </div> 
                                        <div class="card__block">
                                            <h2 class="card__name">'.<?=$val["NAME"]?>.'</h2>
                                            <img  src="img/стрелка далее.svg" alt="">
                                        </div>
                                    </div>
                                </a>
    <?php endwhile; ?>    
</div>

<div class="cards">

    <?php while ($val = $result['elements']->GetNext()): ?>
            $parentServiceId = $arElement['CODE'];
            $catalogParentServiceId = $arElement["ID"];
            $serviceName = $arElement["NAME"];

            echo "
                    <a href='parent/service?parSecId=$parentSectionId&mainSectionName=$mainSectionName&parentServiceId=$parentServiceId&catalogParentServiceId=$catalogParentServiceId&serviceName=$serviceName&parentSectionName=$parentSectionName'>
                        <div class='card'>
                            <div class='card__img'>
                                <img class='card__img-item' src='img/img-card.jpg' alt='...'> 
                            </div> 
                            <div class='card__block'>
                                <h2 class='card__name'>"<?=$val["NAME"]?>."</h2>
                                <img  src='img/стрелка далее.svg' alt=''>
                            </div>
                        </div>
                    </a>
                ";
            
                
    <?php endwhile; ?>  
</div>