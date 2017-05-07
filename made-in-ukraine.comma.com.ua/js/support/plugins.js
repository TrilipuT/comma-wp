
 
var base_path = '';
if (typeof window.base == "undefined") window.base = {};
  
base.tags =
{
	init: function()
	{
		$(document).ready($.proxy(this.ready, this))
		return this;
	},
	ready: function()
	{
		$('.add-new-tag').click(function() {
			$.ajax({
				url: base_path+"/support/ajax/getTagsRow",
				dataType: 'html',
				cache : false,
				success: function(response) {

					var rows = $('#tabs-tags .row').length;	 

					if(rows > 0){ 
						$('#tabs-tags .row:last').after(response);
					} else {
						$('#tabs-tags').append(response);
					}
					
					//$('#modelTags tbody').append(response);
					//$('#modelTags tbody tr:last input')

					//console.log($('#tabs-tags .row:last input').length);
					$('#tabs-tags .row:last input').ourautocomplete({
						//source: "/support/ajax/setTagsAutocomplete",
						serviceUrl : "/support/ajax/setTagsAutocomplete",
						//minLength: 1,  
						minChars : 1,   
						deferRequestBy: 0, //miliseconds
						/*
						select: function(event, ui) {
							
							var thiz = $(this);
							var parent = thiz.closest('.row');
							parent.find('input[name*="ru_name"]').val(ui.item.ru_name); 
							parent.find('input[name*="ua_name"]').val(ui.item.ua_name);

							//$('#tabs-tags .row:last input[name*="ru_name"]').val(1);
							//$('#tabs-tags .row:last input[name*="ua_name"]').val(2);
							//console.log( ui.item, thiz);

							console.log(ui.item);
							return false;
						},  
						*/
						onSelect: function(ui){ 

							console.log(ui);

							var thiz = $(this);
							var parent = thiz.closest('.row');
							parent.find('input[name*="ru_name"]').val(ui.ru_name); 
							parent.find('input[name*="ua_name"]').val(ui.ua_name);

							//$('#tabs-tags .row:last input[name*="ru_name"]').val(1);
							//$('#tabs-tags .row:last input[name*="ua_name"]').val(2);
							//console.log( ui.item, thiz); 
							//console.log(ui.item);
							return false;
						}
					});
				}
			});   
			
			return false;
		});

		/*
		$(document).on('click', '#modelTags .link-remove', function() {
			var $tr = $(this).parents('tr');
			
			if ($tr.data('id'))
			{
				$.ajax({
					url: '/admin/tagsLinks/'+$tr.data('id')+'/delete',
					success: function(response) {
						$tr.remove();
					}
				})
			}
			else
			{
				$tr.remove();
			}
			
			return false;
		});
		*/

		/*
		$(document).on('hover', '.ui-corner-all', function(){

			var thiz = $(this);
			setTimeout(function(){   
				thiz.show();
				//console.log(thiz, 'hover .ui-corner-all');
			},20);   
			
			return false;
		}); */
	}
}.init(); 
  
 
base.cropper =
{
	init: function()
	{
		$(document).ready($.proxy(this.ready, this)); 
		return this;
	},
	ready: function()
	{
		var self = this;
		
		$('.thumbnail-group .thumbnail-source img').load(function() {
			var $source = $(this);
			$source.data('selection',[0,0,this.width,this.height]);
			if ($source.data('cropper')) $source.data('cropper').destroy();
			$source.data('cropper',false);
			
			var $group = $source.parents('.thumbnail-group');
			$group.find('.thumbnail-pinchAll').show();
			
			if ($source.data('refresh')) {
				self.refresh($source);
			}
			if ($source.data('pinchAll')) {
				self.pinchAll($group);
			}
		});
		$('.thumbnail-pinchAll').click(function() {
			self.pinchAll($(this).parents('.thumbnail-group'));
			return false;
		});
		$('.thumbnail-preview img').click(function() {
			self.pinch($(this).parent());
			return false;
		});
	},
	
	setSource: function($group, imageSrc, refresh, pinchAll) {
  
		var _sourse = $group.find('.thumbnail-source img');  
		//imageSrc =  _sourse.attr('path')+"/"+imageSrc;
		console.log(imageSrc);  

		var $source = _sourse.css({'width':'','height':''}).attr('src','').attr('src',imageSrc);


		$source.data('refresh', refresh ? true : false);
		$source.data('pinchAll', pinchAll ? true : false);   
	},
	scale: function($source, $preview, selection) {
		if ($preview.data('width') && !$preview.data('height')) {
			var scale = selection[2] / $preview.data('width');
			$preview.css('height',Math.round(selection[3] / scale));
		} else if (!$preview.data('width') && $preview.data('height')) {
			var scale = selection[3] / $preview.data('width');
			$preview.css('width',Math.round(selection[2] / scale));
		} else {
			var scale = 1;
			var widthScale = selection[2] / $preview.data('width');
			var heightScale = selection[3] / $preview.data('height');
			if (widthScale < heightScale) {
				scale = heightScale;
			} else {
				scale = widthScale;
			}
		}
		var actualWidth = Math.round($source.width() / scale);
		var actualHeight = Math.round($source.height() / scale);
		var offsetX = Math.round(selection[0] / scale);
		var offsetY = Math.round(selection[1] / scale);
		if ($preview.data('width') && $preview.data('width') > 500)
		{
			var newScale = $preview.data('width') / 500;
			actualWidth = Math.round(actualWidth / newScale);
			actualHeight = Math.round(actualHeight / newScale);
			offsetX = Math.round(offsetX / newScale);
			offsetY = Math.round(offsetY / newScale);
			if (parseInt($preview.css('width')) > 500) {
				$preview.css('')
			}
		}
		$preview.find('img').css({'width':actualWidth+'px','height':actualHeight+'px','margin-left':'-'+offsetX+'px','margin-top':'-'+offsetY+'px'});
		$preview.find('input').val(selection.join(';'));
	},
	crop: function($source, $preview, selection) {
		var scale = 1;
		var widthScale = selection[2] / $preview.data('width');
		var heightScale = selection[3] / $preview.data('height');
		if (widthScale > heightScale) {
			scale = heightScale;
		} else {
			scale = widthScale;
		}
		var actualWidth = Math.round($source.width() / scale);
		var actualHeight = Math.round($source.height() / scale);
		var offsetX = Math.round(($preview.data('width') - (selection[2] / scale)) / 2);
		var offsetY = Math.round(($preview.data('height') - (selection[3] / scale)) / 2);
		offsetX -= Math.round(selection[0] / scale);
		offsetY -= Math.round(selection[1] / scale);
		if ($preview.data('width') && $preview.data('width') > 500)
		{
			var newScale = $preview.data('width') / 500;
			actualWidth = Math.round(actualWidth / newScale);
			actualHeight = Math.round(actualHeight / newScale);
			offsetX = Math.round(offsetX / newScale);
			offsetY = Math.round(offsetY / newScale);
		}
		$preview.find('img').css({'width':actualWidth+'px','height':actualHeight+'px','margin-left':offsetX+'px','margin-top':offsetY+'px'});
		$preview.find('input').val(selection.join(';'));
	},
	
	// new api
	
	// Указывает тип исходника
	// Принимает исходник и тип
	sourceType: function($source, type) {
		$source.parents('.thumbnail-group').find('input[name*="sourceType"]').val(type);
	},
	// Обновляет в группе из исходника
	// Если указана конкретная иконка - обновится только она
	// Принимает jQuery-объект исходника
	refresh: function($source, $preview) {
		if (!$source.length || !$source.parent().hasClass('thumbnail-source')) return false;
		
		var self = this;
		var selection = $source.data('selection');
		var $group = $source.parents('.thumbnail-group');
		if ($preview)
			var $_previews = $preview;
		else
			var $_previews = $group.find('.thumbnail-preview');
		$_previews.each(function() {
			var $preview = $(this).css({'width':'','height':''});
			$preview.find('img').css('max-width','');
			var scale = 1;
			if ($preview.data('width') && $preview.data('width') > 500)
				scale = $preview.data('width') / 500;
			if ($preview.data('width')) $preview.css('width',Math.round($preview.data('width') / scale)+'px');
			if ($preview.data('height')) $preview.css('height',Math.round($preview.data('height') / scale)+'px');
			$preview.find('img').attr('src',$source.attr('src'));
			// Пережимка, если можно
			if ($preview.data('method') == 'scale' && selection) self.scale($source, $preview, selection);
			else if ($preview.data('method') == 'crop' && selection) self.crop($source, $preview, selection);
		});
		
		return true;
	},
	// Создает (если не создан еще) и возвращает объект кроппера в исходнике
	// Принимает jQuery-объект исходника
	cropper: function($source) { 

		if ($source.data('cropper')) return $source.data('cropper');
		
		var self = this;
		self.refresh($source);
		$source.Jcrop({
			boxWidth: 500,
			boxHeight: 500,
			keySupport: false,
			sourceWidth: $source.width(),
			sourceHeight: $source.height(),
			$source: $source,
			$initiator: $(),
			$preview: $(),
			mode: '',
			system: self,
			onChange: function(selection) {
				selection = [selection.x,selection.y,selection.w,selection.h];
				var cropper = this;
				var cropperOptions = cropper.getOptions();
				cropperOptions.$preview.each(function() {
					var $preview = $(this);
					if ($preview.data('method') == 'scale' && selection) cropperOptions.system.scale(cropperOptions.$source, $preview, selection);
					else if ($preview.data('method') == 'crop' && selection) cropperOptions.system.crop(cropperOptions.$source, $preview, selection);
				});
			}
		}, function() {
			$source.data('cropper',this);
		});
		return $source.data('cropper');
	},
	// Запускает кроппер для конкретной иконки
	// Принимает jQuery-объект иконки
	pinch: function($preview) { 

		if (!$preview.length || !$preview.hasClass('thumbnail-preview')) return false;
		
		// Узнаем группу, в которой находится иконка
		var $group = $preview.parents('.thumbnail-group'); 

		//console.log($group);

		// Находим исходник для этой группы
		var $source = $group.find('.thumbnail-source img');
		// Выясняем, есть ли последняя область выделения для этой иконки
		var selection = $preview.find('input').val().split(';');
		if (selection.length != 4) selection = $source.data('selection');
		else 
			for (var i = 0; i < selection.length; i++) 
				selection[i] = parseFloat(selection[i]);

		// Узнаем минимальный размер выделения в кроппере
		var minWidth = $preview.data('width');
		var minHeight = $preview.data('height');
		
		if(typeof minWidth == "undefined"){
			minWidth = 100;
		}

		console.log(minWidth, minHeight);

		// Подготавливаем кроппер
		var cropper = this.cropper($source);
		if (cropper) {
			cropper.setOptions({
				$initiator: $preview,
				$preview: $preview,
				mode: 'preview',
				minSize: [minWidth || 0, minHeight || 0]
			});

			if (selection) cropper.setSelect([selection[0],selection[1],selection[0]+selection[2],selection[1]+selection[3]]);
		}
		
		return true;
	},
	// Запускает кроппер для группы иконок
	// Принимает jQuery-объект группы
	pinchAll: function($group) {
		if (!$group.length || !$group.hasClass('thumbnail-group')) return false;
		
		// Находим исходник для этой группы
		$source = $group.find('.thumbnail-source img');
		// Выясняем, есть ли последняя область выделения для группы
		// Она хранится в объекте исходника
		var selection = $source.data('selection');
		if (selection.length != 4) selection = $source.data('selection');
		else for (var i = 0; i < selection.length; i++) selection[i] = parseFloat(selection[i]);
		// Узнаем минимальный размер выделения в кроппере
		// Берутся маскимальные ширина и высота
		var minWidth = 0;
		var minHeight = 0;
		$group.find('.thumbnail-preview').each(function() {
			var $preview = $(this);
			var previewWidth = $preview.data('width') || 0;
			var previewHeight = $preview.data('height') || 0;
			if (previewWidth > minWidth) minWidth = previewWidth;
			if (previewHeight > minHeight) minHeight = previewHeight;
		});
		// Подготавливаем кроппер
		var cropper = this.cropper($source);
		if (cropper) {
			cropper.setOptions({
				$initiator: $group,
				$preview: $group.find('.thumbnail-preview'),
				mode: 'group',
				minSize: [minWidth, minHeight]
			});
			if (selection) cropper.setSelect([selection[0],selection[1],selection[0]+selection[2],selection[1]+selection[3]]);
		}
		
		return true;
	}
}.init();


base.test =
{
	init: function()
	{
		$(document).ready($.proxy(this.ready, this));
		return this;
	},
	ready: function()
	{
		var self = this;
		$('.file-uploader span').each(function() {
			var $uploader = $(this).parents('.file-uploader');
			var area = $(this)[0];
			area.onclick = function(e) {
				e.preventDefault();
				e.stopPropagation();
				$uploader.find('input').trigger('click');
				return false;
			}
			area.ondragover = function(e) {
				e.preventDefault();
				e.stopPropagation();
				e.dataTransfer.dropEffect = 'copy';
			}
			area.ondrop = function(e) {
				e.preventDefault();
				e.stopPropagation();
				var files = e.dataTransfer.files;
				self.upload($uploader, files);
			}
		});
		$(document).on('change', '.file-uploader input', function(e) {
			var $this = $(this);
			var $uploader = $this.parents('.file-uploader');
			var files = e.target.files;
			//base.uploadFiles(files);
			self.upload($uploader, files);
		});
	},
	upload: function($uploader, files)
	{
 

		var totalFiles = files.length;
		var totalSize = 0;
		for (var i = 0, file; file = files[i]; i++)
			totalSize += file.size;
		
		var name = $uploader.find('input').attr('name');
		var reciever = document.location.pathname.replace('edit','upload');


		if ($uploader.data('reciever'))
			reciever = $uploader.data('reciever');

		var defaultText = $uploader.find('span').attr('defaultText');	

		if (totalFiles == 1)
			$uploader.find('span').text('Загрузка файла');
		else
			$uploader.find('span').text('Загрузка файла 1 из '+totalFiles);
		
		function sendData(i)
		{
			var file = files[i];
			var xhr = new XMLHttpRequest;
			if (xhr)
			{
				xhr.upload.addEventListener('progress', function(e) {
					var percent = (e.loaded / e.total) * 100;
					$uploader.find('.file-progress-bar').css('width',percent+'%');
				}, false);
				xhr.upload.addEventListener('load', function(e) {
					
				}, false);
				xhr.open('POST',reciever,true);
				var xhrData = new FormData();
				xhrData.append(name,file);
				xhr.send(xhrData);
				$uploader.find('span').text('Загрузка файла '+(i+1)+' из '+totalFiles);

				xhr.onreadystatechange = function()
				{
					if (xhr.readyState === 4)
					{
						var response = $.parseJSON(xhr.responseText);
						$uploader.find('.file-progress-bar').css('width','0%');
						
						if (i < totalFiles-1)
						{
							sendData(++i);
						}
						else
						{
							$uploader.find('span').text('Файлы загружены, обрабатываю...  ');
							var $input = $uploader.find('input');
							$input.replaceWith($input.parent().html());
							$uploader.find('span').text(defaultText);
						}
						

						if (!response.error){

							var settings = $uploader.data('uploadSettings');
							if (settings && typeof settings.success == "function"){
								settings.success.call($uploader[0], response);
							
							} else {

								

								var $group = $uploader.parents('.thumbnail-group');

								var img = $group.find('.thumbnail-source img:first'); 

								if ($group.length)
									base.cropper.setSource($group, img.attr('path') + response.file + '?'+(new Date()).getTime(), true, false);
 
								
								var thumbnail_preview = $group.find('.thumbnail-preview');
								if(thumbnail_preview.length > 0){
									$.each(thumbnail_preview, function(){   

										//var img = $(this).find('img')
										img.attr('src', img.attr('path') + response.file);
									});
								}
								
							}
						}
						else
						{
							if (response.errorMsg.thumbnail)
								$uploader.find('span').text(response.errorMsg.thumbnail);
						 
						} 

						
					}
				}
			}
		}
		sendData(0);
	}
}.init();