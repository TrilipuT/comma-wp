<?php

class DBHtml
{
	public static function fileUploader($params=array())
	{
		if (empty($params['text']))
			$params['text'] = 'Кликните или перетащите сюда файл, чтобы загрузить картинку';
		if (empty($params['name']))
			$params['name'] = 'Filedata';

		$html = '';
		$html .= '<div class="file-uploader"';
			if (!empty($params['id']))
				$html .= ' id="'.$params['id'].'"';
			if (!empty($params['reciever']))
				$html .= ' data-reciever="'.$params['reciever'].'"';
		$html .= '>';
		$html .= '<div><input type="file" name="'.$params['name'].'" value="" accept="image/jpeg,image/png" '.($params['multiple'] ? 'multiple="multiple"' : '').' /></div>
			<div class="file-progress">
				<div class="file-progress-bar"></div>
				<span>'.$params['text'].'</span>
			</div>
		</div>';
		return $html;
	}




	public static function activeCropper(CModel $Model, $resolution, $params=array())
	{
		$namePrefix = 'Thumbnail';
		$rules = $Model->thumbnailsRules();

		$thumbnails = $rules[$resolution];
		foreach ($rules as $res=>$rule)
			if ($rule['selection'] == $resolution)
				$thumbnails[$res] = $rule;

		$html = '';
		$html .= '<div class="form-elements thumbnail-group">';

			if (!empty($params['title']))
				$html .= '<label>'.$params['title'].'</label>';


			$html .= '<div class="file-uploader" data-reciever="/support/ajax/loadPhoto?type='.(get_class($Model)).'&data_id='.$Model->id.'">
							<div style="display:none;"><input type="file" name="'.get_class($Model).'['.$resolution.']" value="" accept="image/jpeg,image/png" /></div>
							<div class="file-progress">
								<div class="file-progress-bar"></div>
								<span defaultText="Кликните или перетащите сюда файл, чтобы загрузить картинку">Кликните или перетащите сюда файл, чтобы загрузить картинку</span>
							</div>
							<div class="clear"></div>
						</div>';
			$html .= '<div class="form-element left">';

				//var_dump($thumbnails); exit;
				$key  				= 0;
				$thumbnail_source 	= array();


				foreach ($thumbnails as $res=>$rule){

					if (!$rule['canPinch'] || !$rule['path'])
						continue;

					if($key == 0){
						$thumbnail_source = $rule;
					}
					$key++;

					if($resolution == 'image'){
						$img  = $Model->image_filename;
					} else {
						$img  = $Model->icon_filename;
					}


					list($width, $height) = explode('x', $res);

					$html .= '<div class="thumbnail-preview"  '; //style="max-height: 600px;max-width: 400px;"

						if ($width > 0)
							$html .= ' data-width="'.$width.'"';

						if ($height > 0)
							$html .= ' data-height="'.$height.'"';

						$html .= ' data-method="'.$rule['method'].'">';

							$html .= '<input type="hidden" name="'.$namePrefix.'['.$resolution.']['.$res.'][selection]" value="" />';

							$thumbnail = $rule['path'].$img.'?'.time();//$Model->getThumbnailPath($res, false, true);

							//var_dump($Model->image_filename);
							//if ($Model->image_filename != null)
								$html .= '<img  path="'.$rule['path'].'" src="'.$thumbnail.'" alt="" />'; //style="max-height: 600px !important;max-width: 400px !important;"
							//else
								//$html .= '<img src="" alt="" />';


							if ($rule['required'] === false && $img !== null)
								$html .= '<label class="checkbox"><input type="checkbox" name="'.$namePrefix.'['.$res.'][delete]" value="1" /> Удалить</label>';
					$html .= '</div>';
				}

			$html .= '</div>';
			$html .= '<div class="form-element right">';
				//$html .= '<input type="file" name="'.$namePrefix.'[upload]" accept="image/jpeg,image/png" />';
				//$html .= '<input type="hidden" name="'.$namePrefix.'[source]" value="" />';
				//$html .= '<input type="hidden" name="'.$namePrefix.'[sourceType]" value="'.(isset($Model->thumbnails[$group]['src']) && file_exists($Model::SRC_PATH.$Model->thumbnails[$group]['src'])?'current':'unknown').'" />';
				$html .= '<div class="thumbnail-source">';

					//$src = $Model->getThumbnailPath($resolution, true);

					if($resolution == 'image'){
						$path = $Model::PATH_IMAGE_SRC;
						$src  = $path.$Model->image_filename.'?'.time();
					} else {
						$path 	= $Model::PATH_ICON_SRC;
						$src 	= $path.$Model->icon_filename.'?'.time();
					}


					//if ($thumbnail_source['path'] !== null)
						$html .= '<img path="'.$path.'" src="'.$src.'" alt="" style="display: none;" />';
					//else
						//$html .= '<img src="" alt="" style="display: none;" />';
				$html .= '</div>';
			$html .= '</div>';
			$html .= '<div class="clr"></div>';
		$html .= '</div>';

		return $html;
	}
}