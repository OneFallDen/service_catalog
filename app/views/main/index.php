<div class="hello">
<p class="hello__text">Добро пожаловать!</p>
<p>С чем связана ваша проблема?</p>
</div>

<ul class="block__list">
    <?php while ($val = $result->GetNext()): ?>
        <li class="block__item">
            <?php $href="parent/select?mainSectionName=".$val["NAME"]."&parentSectionId=".$val["ID"]?>
            <a class="chapter" href="<?=$href?>">
                <p><?=$val["NAME"]?></p>
            </a>
        </li>
    <?php endwhile; ?>
</ul>