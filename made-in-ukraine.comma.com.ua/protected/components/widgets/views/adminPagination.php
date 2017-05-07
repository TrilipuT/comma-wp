<?php
 
$url =  '/support/'.$this->module_name;

//var_dump($url); 

//var_dump($this->page);

if ($this->total_pages > 1): ?> 

		<?	$plinks = array();
			if(1 < $page)
				$plinks[] = '<li><a href="'.$url.'?page='.($this->page-1).$this->params.'" class="previous"><span class="icon12 minia-icon-arrow-left-3"></span></a></li>'; 


			for ($i = 1; $i <= $this->total_pages; $i++) :

					if ($i == 1 || $i == $this->total_pages || abs($i - $this->page) <= 2) {
						
						if ($i == $this->page) {
							$plinks[] = '<li class="active" ><a href="">'.$i.'</a></li>';
						} else {
							$plinks[] = '<li><a href="'.$url.'?page='.$i.$this->params.'" >'.$i.'</a></li>';
						}
					}
					else {
						if ($plinks[count($plinks) - 1] != '<li><span>&hellip;</span></li>') {
							$plinks[] = '<li><span>&hellip;</span></li>';
						} else {
							continue;
						}
					}
			endfor;

			if($this->total_pages > $this->page)
				$plinks[] = '<li class="paginator-i paginator-i_next"><a href="'.$url.'?page='.($this->page+1).$this->params.'" class="next"><span class="icon12 minia-icon-arrow-right-3"></span></a></li>';

		 ?>
	<!-- .paginator begin -->
	<div class="pagination">
		<ul>
			<p class="paginator-t"><?=Yii::t('app','pagination_pages')?>:</p>
		      <?=implode('', $plinks)?> 
		</ul>
	</div>
	<!-- .paginator end--> 
<?php endif; ?> 
	 