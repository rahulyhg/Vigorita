<?php
	/** @var \MABEL_SILITE\Code\Models\Shoppable_Image_VM $model */
?>

<div
	class="mabel-siwc-img-wrapper"
	data-text="<?php echo $model->button_text ?>"
	data-tags='<?php echo json_encode($model->tags,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT  ); ?>'
	data-icon="<?php echo $model->icon; ?>"
    data-size="<?php echo $model->size; ?>"
>
	<img src="<?php echo $model->image;?>" />
</div>