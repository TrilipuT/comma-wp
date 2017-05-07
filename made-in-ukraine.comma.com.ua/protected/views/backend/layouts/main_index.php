<div class="box"> 
 	<div class="title"> 
	    <h4>
	        <span class="icon16 icomoon-icon-equalizer-2"></span>
	        
	        <span><?=CHtml::link('Добавить', array('support/create/'.$model_name.$dop_link), array('class'=>'btn'));?></span> 
	       
	        <?php if($list): ?>
		        <form class="box-form right" action="">
		            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
		                <span class="icon16 icomoon-icon-cog-2"></span>
		                <span class="caret"></span>
		            </a>
		            <ul class="dropdown-menu">
		                <li><a class="change-elements" type="delete_choose" href="#"><span class="icon-trash"></span>Delete</a></li>
		                <li><a class="change-elements" class="" type="active_choose" href="#"><span class="icon-trash"></span>Active</a></li>
		                <li><a class="change-elements" class="" type="de-active_choose" href="#"><span class="icon-trash"></span>DeActive</a></li>
		            </ul>
		        </form> 
		    <?php endif; ?> 
	    </h4> 
	</div> 

	<?php $this->renderPartial('/'.$model_name.'/index', array( 'model_name' => $model_name, 
																'dop_link' 	 => $dop_link,
																'list' 		 => $list,

																)); ?>

</div><!-- End .box -->  
<?php   $this->widget('application.components.widgets.AdminPagination', array( 	'module_name'   => $model_name,
																				'page'			=> $page,
						 											  			'total_pages'	=> $total_pages,
						 											  			'params' 		=> $dop_link));
 