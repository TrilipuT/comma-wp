<section class="participants_box">
	<div class="wrapper">
		<h1 class="bord"><?=$this->Section->transfer->name?></h1>
		<?php if($membersItems):?>
			<div class="part_des" style="position: relative;">
				<div class="part_title" style="width: 650px;">
					<?=$this->Section->transfer->content;?>
				</div>
				<div class="main_soc_login"><?php $Users = UsersAuth::isLogin();?>
					<?php /*
					if($exit_vote_time < time() && !$Users): ?>
						<div class="login">
							<div class="text">Щоб проголосувати, залогiньтеся</div>
							<div class="btns login-btns">
								<a onclick="return check_member();" href="/login?service=vkontakte&returnUrl=http://<?=$_SERVER['HTTP_HOST'] . Yii::app()->request->url?>" class="vk vkontakte sziget-soc-login"></a>
								<a onclick="return check_member();" href="/login?service=facebook&returnUrl=http://<?=$_SERVER['HTTP_HOST'] . Yii::app()->request->url?>" class="fb facebook sziget-soc-login"></a>
							</div>
						</div>
					<?php else:?>
						<div class="unlogin-btn">Вийти з соцмережi</div>
					<?php endif;

 */?>
				</div>
			</div>
			<div class="members_list">
				<?php $this->renderPartial('_items', array('membersItems' => $membersItems)); ?>
			</div>
			<div class="more load_content">
				<a style="display:none;" href="#" page="1" class="more_items"><i></i></a>
			</div>
		<?php else:?>
			<div class="part_content">
				<div class="part_text" style="padding: 20px;">
					<div class="part_top">
						<b>Учасникам конкурсу від Sziget Festival Україна і туристичного оператора «Богемія Сервіс» та їхнім фанам!</b>
					</div>
					<div class="part_bottom">
						<p>З метою уникнути нечесного голосування ми змушені переглянути систему віддачі голосів і зробити РЕСТАРТ конкурсу в ПОНЕДІЛОК, 6 КВІТНЯ. Голосування за діючою системою буде припинено і всі зроблені до цього шери повернуті до нульової кількості. З понеділка віддати голос за улюблений гурт можна буде лише один раз із одного акаунта Facebook і/або Vkontakte.
						</p><br/>
						<p>Ми вибачаємося за зміни перед гуртами і тими, хто вже зробив свій вибір, і просимо повторити його в понеділок, або протягом всього періоду голосування. У зв’язку з цим конкурс буде продовжено включно до 18 квітня.
						</p><br/>
						<p>Сподіваємося на вашу підтримку нашого бажання зробити вибір гурту, який представить Україну на Sziget EuropeStage 11 серпня 2015 року максимально чесним і з рівними можливостями для кожного учасника. Ми стараємося саме для вас і впевнені, що вже скоро всі ми будемо пишатися нашим спільним вибором, оголошеним на фіналі конкурсу 8 травня в клубі Sentrum.
						</p>
					</div>
					<? /*<div class="part_bottom">
						Хочеш бути серед них?<br>Надсилай заявку до 28 березня.
						<div class="take"><a href="/pravila_konkursu/">взяти участь</a></div>
					</div> */ ?>
				</div>
			</div>
		<?php endif;?>
	</div>
	<div id="popup-data" style="display: none"><?=$popup;?></div>
	<div id="images-data" style="display: none"><?=$images_list;?></div>
	<script>
		$(document).ready(function(){
			vk_init();

			var html = $('#fancy_memb .des_memb').html();

			/*if(html != ''){
				$(".scroll_popup").mCustomScrollbar("destroy");

				$('#fancy_memb .des_memb').html(html);

				try{
					FB.XFBML.parse();
				}catch(ex){}

				$.fancybox({
					maxWidth: 800,
					minWidth: 500,
					href: '#fancy_memb'
				});

				vk_init();

				$(".scroll_popup").mCustomScrollbar({});
			} */


			var images_json = $('#images-data');

			if(images_json.length && images_json.text() != ''){
				var images_array = jQuery.parseJSON(images_json.text());

				if(images_array.length > 0){
					$.fn.preload = function() {
						this.each(function(){
							$('<img/>')[0].src = this;
						});
					}

 					$(images_array).preload();
				}
			}
		});
	</script>
</section>