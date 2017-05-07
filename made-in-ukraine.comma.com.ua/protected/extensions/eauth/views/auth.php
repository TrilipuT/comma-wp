<ul>
	<?php
	foreach ($services as $name => $service) {

		switch ($name) {
			case 'facebook':
				$dopClass = 'soc__fb loginform__fb';
				break;
			case 'twitter':
				$dopClass = 'soc__tw loginform__tw';
				break;
			case 'google_oauth':
				$dopClass = 'soc__gp loginform__gp';
				break;
			case 'vkontakte':
				$dopClass = 'soc__vk loginform__vk';
				break;
			case 'linkedin':
				$dopClass = 'soc__ln loginform__in';
				break;

			default:
				$dopClass = '';
				break;
		}

		echo '<li class="auth-service ' . $service->id . '">';
		//$html = '<span class="auth-icon ' . $service->id . '"><i></i></span>';
		//$html .= '<span class="auth-title">' . $service->title . '</span>';
		/*
		$html = CHtml::link($html, $action.'?service='.$name.'&returnUrl='.$returnUrl, array(
																			'class' => 'auth-link ' . $service->id,
																		)); */
		echo '<a href="' . $action . '?service=' . $name . '&returnUrl=http://' . $_SERVER['HTTP_HOST'] . $returnUrl . '" class="' . $dopClass . ' auth-link "></a>';

		echo '</li>';
	}
	?>
</ul> 