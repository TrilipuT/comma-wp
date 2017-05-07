<section class="juri_block">
	<div class="wrapper">
		<?php if($juryItems):?>
			<h1><?=$this->Section->transfer->name;?></h1>

			<div class="juri_content">
				<?php
				$juryList = array();

				$left = 1; // снаяала слева
				$row_end = false;
				$row_start = true;

				$in_row = 2;
				$row = 1;
				$key = 0;

				$count_items = count($juryItems);
				foreach($juryItems as $real_key => $Jury):
					$img_path = Jury::IMAGE_326x326;

					if(empty($Jury->image_filename)
						&& !file_exists($_SERVER['DOCUMENT_ROOT'] . Jury::IMAGE_326x326 . $Jury->image_filename)
						&& !file_exists($_SERVER['DOCUMENT_ROOT'] . Jury::IMAGE_652x326 . $Jury->image_filename)
						&& !file_exists($_SERVER['DOCUMENT_ROOT'] . Jury::IMAGE_490x650 . $Jury->icon_filename)){
						continue;
					}

					$juryList[$real_key] = array(
						'image' => Jury::IMAGE_490x650 . $Jury->icon_filename,
						'name' => addslashes($Jury->transfer->name),
						'text' => ($Jury->transfer->text),
					);

					$key++;

					//последний
					if(($real_key+1 == $count_items)){ //$key%3 == 0 ||
						$row_end = true;
					}

					$width_class = 'w326';

					// первая строка 2 елемента
					if($row == 1){
						if($key == $left){
							$width_class = 'w652';
							$img_path = Jury::IMAGE_652x326;
						}

						if($key == 2){
							$row = 2;
							$in_row = 3;
							$row_end = true;
						}
					} else { // вторая строка 3 елемента
						if($key == 3){
							$in_row = 2;
							$row = 1;
							$row_end = true;

							if($left == 1){
								$left = 2;
							} else {
								$left = 1;
							}
						}
					}

					if($row_start === true):
						$row_start = false;?>
						<div class="juri_row clearfix">
					<?php endif;?>

						<div class="col_juri <?=$width_class?>">
							<a data-id="<?=$real_key?>" href="javascript:void(0)">
								<span class="img_juri">
									<img src="<?=$img_path.$Jury->image_filename?>" alt="<?=$Jury->transfer->name?>" />
								</span>
								<span class="hide_juri">
									<span class="text_juri_hide">
										<span><?=$Jury->transfer->name?></span>
										&mdash;<br>
										<?=$Jury->transfer->description?>
									</span>
								</span>
							</a>
						</div>

					<?php if($row_end === true):
							$row_end = false;
							$row_start = true;
							$key = 0;
							?>
						</div>
					<?php endif;
				endforeach;?>
			</div>

			<div id="jury_json_list" style="display: none"><?=json_encode($juryList, JSON_HEX_QUOT | JSON_HEX_TAG);?></div>
			<script>
				$(document).ready(function(){
					var jury_list = $('#jury_json_list').text();
					var jury_array = jQuery.parseJSON(jury_list);

					if(jury_array.length > 0){
						$.fn.preload = function() {
							this.each(function(){
								$('<img/>')[0].src = this;
							});
						}
						var imgs = [];
						$.each(jury_array, function(i){
							imgs[i] = $(this)[0].image;
						});
						$(imgs).preload();

						function set_juri_info(index){
							$('.juri_description').attr('data-id', index);

							$('.juri_description .img_popup img').attr('src', jury_array[index].image);
							$('.juri_description .name_juri').text(jury_array[index].name);
							$('.juri_description .popup_des').html(jury_array[index].text);
						}

						$(document).on('click', '.col_juri a', function(){
							var index = $(this).attr('data-id');

							set_juri_info(index);

							$('.juri_content').hide();
							$('.juri_description').fadeIn(400).attr('data-id', index);

							$('html, body').animate({ scrollTop: 200 }, 400);
						});

						$(document).on('click', '.juri_description .control_slide a', function (){

							var current_index = $('.juri_description').attr('data-id')*1;
							var new_index = 0;

							if($(this).parent().hasClass('leftslide')){
								 if(current_index == 0){
									 new_index = jury_array.length-1;
								 } else {
									 new_index = current_index-1;
								 }
							} else{
								if(current_index+1 == jury_array.length){
									new_index = 0;
								} else {
									new_index = current_index+1;
								}
							}

							set_juri_info(new_index);

							$('.juri_description').attr('data-id', new_index);
						});
					}
				});
			</script>
			<div class="juri_description" style="display: none">
				<div class="popup_title clearfix">
					<div class="controls_content clearfix">
						<div class="control_slide leftslide"><a href="javascript:void(0)"><span></span></a></div>
						<div class="control_slide rightslide"><a href="javascript:void(0)"><span></span></a></div>
					</div>
					<div class="close_popup"><a href="javascript:void(0)"><span></span></a></div>
				</div>
				<div class="popup_content clearfix">
					<div class="img_popup">
						<img src="/images/blank.gif" alt="" />
					</div>
					<div class="popup_text">
						<div class="text_content">
							<div class="name_juri"></div>
							<div class="popup_des"></div>
						</div>
					</div>
				</div>
			</div>
		<?php else: ?>
			<h1 class="bord"><?=$this->Section->transfer->name;?></h1>
			<div class="clear_juri">
				<div class="clear_text">
					<?=$this->Section->transfer->description;?>
				</div>
			</div>
		<?php endif;?>
	</div>
</section>