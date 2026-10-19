<?php B_PROLOG_INCLUDED === true || die();

use Bitrix\Main\Localization\Loc;

\Bitrix\Main\UI\Extension::load('ui.sidepanel-content');

/**
 * @var array $arParams
 * @var array $arResult
 */
?>

<div class="ui-slider-section">
    <p class="ui-slider-paragraph">Дополнительная информация</p>
	<?php if (is_array($arResult['ELEMENTS']) && count($arResult['ELEMENTS']) > 0): ?>
		<?php foreach ($arResult['ELEMENTS'] as $value): ?>
			<div class="ui-slider-content-box">
				<p class="ui-slider-paragraph"><?= $value['ID'] ?></p>
				<p class="ui-slider-paragraph"><?= $value['TITLE'] ?></p>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
	<div class="ui-slider-content-box">
		<p class="ui-slider-paragraph"><a href="<?= $arResult['LIST_PAGE_URL'] ?>"><?= Loc::getMessage('EXAM31_ELEMENT_DETAIL_BACK_TO_LIST') ?></a></p>
	</div>
</div>
