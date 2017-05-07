<?php
//$page = $this->Getcontroller();

if($this->url == NULL){

	$url = Yii::app()->request->requestUri;

	$_url = explode('?',$url);
	if(count($_url) > 1){
		$url = $_url[0];
	} 
}
else{

	$url = $this->url;
} 

if($this->page <= 0)
	$this->page = 1;

if($this->page > $this->total_pages)
	$this->page = $this->total_pages;


if ($this->total_pages > 1): ?> 

		<?	$plinks = array();
			//if(1 < $this->page)
				//$plinks[] = '<li class="paginator-i paginator-i_prev"><a href="'.$url.'?page='.($this->page-1).$params.'" class="paginator-l">←</a></li>'; 
			if($this->page > 1)
				$plinks[] = '<a href="'.$url.'?page='.($this->page-1).$params.'" class="prev"></a>'; 
			
			if($this->page < $this->total_pages)
				$plinks[] = '<a href="'.$url.'?page='.($this->page+1).$params.'" class="next"></a>'; 


			for ($i = 1; $i <= $this->total_pages; $i++) :

					if ($i == 1 || $i == $this->total_pages || abs($i - $this->page) <= 2) {
						
						if ($i == $this->page) {
							$plinks[] = '<a href="'.$url.'?page='.$i.$params.'" class="selected">'.$i.'</a>';
						} else {
							$plinks[] = '<a href="'.$url.'?page='.$i.$params.'">'.$i.'</a>';
						}
					}
					else {
						if ($plinks[count($plinks) - 1] != '<span>&hellip;</span>') {
							$plinks[] = '<span>&hellip;</span>';
						} else {
							continue;
						}
					}
			endfor;

			//if($this->total_pages > $this->page)
				//$plinks[] = '<li class="paginator-i paginator-i_next"><a href="'.$url.'?page='.($this->page+1).$params.'" class="paginator-l">→</a></li>';
//, 'remains_class' => 'js-main-remains'
		 ?>
	 
	<div class="pagination">
		<?php if($this->remains > 0):?>
			<div page="<?=$this->page?>" class="pagination-more <?=$this->remains_class;?>">
                <a href="#"><b></b>Больше материалов</a>
            </div>
        <?php endif;?>
    	<div class="pagination-pages">
	      <?=implode('', $plinks)?> 
		</div>
	</div>
	 
<?php endif; ?>  