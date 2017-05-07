
//jQuery.noConflict(); 

/*
$(document).on('hover', '.ui-autocomplete', function(){

	console.log('hover .ui-autocomplete');
	return false;
});

$(document).on('hover', '.ui-menu', function(){

	console.log('hover .ui-menu');
	return false;
});

$(document).on('hover', '.ui-widget', function(){

	console.log('hover .ui-widget');
	return false;
});

$(document).on('hover', '.ui-widget-content', function(){

	console.log('hover .ui-widget-content');
	return false;
});
*/ 

$(document).on('click', '#show-preview', function(){

	var form 	= $(this).closest('form');  
	var action 	= clearQuery(form.attr('action'));
	form.attr('action', action + '?preview=1');
	form.attr('target', '_blank'); 
});


function clearQuery(href){ 

	var q = parseUrl(href).search; 
	if(q.length){
		href = href.replace(q, ''); 
	}  
	return href;
} 
function parseUrl( url ) {
    var a = document.createElement('a');
    a.href = url;
    return a;
}

$(document).on('click', '.save-btn', function(){

	var form = $(this).closest('form');

	var action = clearQuery(form.attr('action'));
	form.attr('action', action);
	form.attr('target', ''); 
});

 

$(document).ready(function() { 
	//------------- Colorpicker -------------//
	if($(".picker").length > 0 ){  
		
		if($('div').hasClass('picker')){
			$('.picker').farbtastic('#color');
		}  
	}
	
	if($("#loginForm").length > 0 ){  

	    $("#loginForm").validate({
	        rules: {
	            username: {
	                required: true,
	                minlength: 4
	            },
	            password: {
	                required: true,
	                minlength: 6
	            }  
	        },
	        messages: {
	            username: {
	                required: "Fill me please",
	                minlength: "My name is bigger"
	            },
	            password: {
	                required: "Please provide a password",
	                minlength: "My password is more that 6 chars"
	            }
	        }   
	    }); 
	}  

	if($('#file_upload').length == 0){

		//------------- ibutton  -------------//
		$(".ibutton").iButton({
			 labelOn: "ON",
			 labelOff: "OFF",
			 enableDrag: false
		});   
	} else {

		$(".ibutton").removeClass('nostyle');
	}



	
	//------------- To top plugin  -------------//
	$().UItoTop({ 
		//containerID: 'toTop', // fading element id
		//containerHoverID: 'toTopHover', // fading element hover id
		//scrollSpeed: 1200,
		easingType: 'easeOutQuart' 
	});


	//------------- Uniform  -------------//
	//add class .nostyle if not want uniform to style field
	$("input, textarea, select").not('.nostyle').uniform();

	//remove loadstate class from body and show the page
	setTimeout(function(){  

		$("html").removeClass("loadstate");
	},500); 
	

/*
	if($("#sortable").length > 0){

		var startPosition 	= 0; 
		
		var orderNumArray 	= []; 

		$("#sortable").sortable({
			create: function(event, ui) {
 				
 				var elements = $('#sortable tr');
				if(elements.length > 0){

					$.each(elements, function(i){
						if($(this).hasClass('ui-sortable-placeholder') == false){
							orderNumArray[i] = $(this).attr('data-order'); 
						} 
					});
				} 
			},
			start: function(event, ui) { 
				startPosition  = $('#sortable tr').index(ui.item);    
			},
			helper: function(event, ui) {
				
				ui.context.classList.add('sortable-row'); 
			 	return ui;
			},
			beforeStop: function(event, ui) { 
				ui.helper.context.classList.remove('sortable-row');
				return ui;	
			}, 
			sort: function(event, ui) {  
	 
				$('.sortable-row').css({'top': (ui.offset.top-250)});
				//console.log(sortElement,   ui.offset);
			},
			update: function(event, ui) {   
	 			
	 			var endPosition  	= $('#sortable tr').index(ui.item);
				var elements 		= $('#sortable tr');
				if(elements.length > 0){

					$.each(elements, function(i){
						if($(this).hasClass('ui-sortable-placeholder') == false){
							$(this).attr('data-order',orderNumArray[i]); 
						} 
					});
				} 

				var start = 0;
				var stop  = 0;

				if(startPosition > endPosition){
					start = endPosition;
					stop  =	startPosition;
				} else {
					start = startPosition;
					stop  =	endPosition;
				}

				//выбираем id`s с диапазона какой затронули
				var i 				 = 0;
				//массивы с данными какие были затронуты
				var chandeItemsId    = [];
				var chandeItemsOrder = [];
				for(start; start <= stop; start++){

					var thiz = $('#sortable tr').eq(start);

					chandeItemsId[i] 	= thiz.attr('data-id');
					chandeItemsOrder[i] = thiz.attr('data-order');
					i++;
				} 
				
				$.ajax({          
			          type 		: "POST",
			          url 		: "/support/ajax/chandeOrderNum",
			          dataType 	: 'json',
			          async 	: false,
			          data 		: ({itemsId     : chandeItemsId,
			          				itemsOrder  : chandeItemsOrder,
			          				model 		: model}),
			          success: function(data){  

			             
			          } 
			    }); 	
			} 
		});
	}	*/ 

});

//generate random number for charts
randNum = function(){
	return (Math.floor( Math.random()* (1+40-20) ) ) + 20;
}

function chacgeOrderNum(index){

	var element = $('#sortable tr').eq(index);
	//console.log(element.attr('data-id'),element.attr('data-order'));

	var result  = [element.attr('data-id'),element.attr('data-order')]
	return result; 
}

//window resize events
$(window).resize(function() {
	//get the window size
	var wsize =  $(window).width();
	if (wsize > 980 ) {
		$('.shortcuts.hided').removeClass('hided').attr("style","");
		$('.sidenav.hided').removeClass('hided').attr("style","");
	}

	var size ="Window size is:" + $(window).width();
	//console.log(size);

});

$(window).load(function() {
	var wheight = $(window).height();
	$('#sidebar.scrolled').css('height', wheight-63+'px');
}); 

 

//------------- Check all checkboxes  -------------//	
$(document).on('click',"#checkAll", function() {
	
	var thiz 			= $(this);
	var checkedStatus 	= thiz.hasClass('checked');  

	var checkedStatus = $(this).closest('span').hasClass('checked');
	
	$("table tr .chChildren:checkbox").each(function() {		
		this.checked = checkedStatus;
		if (checkedStatus == this.checked) { 			 
			$(this).closest('.checker > span').removeClass('checked');
		}

		if (this.checked) {			 
			$(this).closest('.checker > span').addClass('checked');
		}
	});
 
}); 
 
 


$(document).on('blur', '.nameToAlias', function(){ 
 
    var name = $(this).val();
 
  	if(name.length > 0 ){

	    $.ajax({          
	          type 		: "POST",
	          url 		: "/support/ajax/GetAlias",
	          dataType 	: 'json',
	          async 	: false,
	          data 		: ({name : name}),
	          success: function(data){  

	            var alias = $('.alias').val();
	            $('.Message').html('');

	            if(alias.length > 0 ){
	            	if(alias != data.alias)
	            		$('.Message').html('<i>Рекомендуется изменить алиас на:"'+data.alias+'"</i>');
	            }	
	            else
              		$('.alias').val(data.alias); 
	          } 
	    });
  	}  
});

// проверка алиаса

$(document).on('blur', '.alias', function(){

	var thiz        = $(this);
    var this_alias  = thiz.val();
    var model 		= thiz.attr('model');

    if(this_alias.length > 0 ){

		$.ajax({          
	          type 		: "POST",
	          url 		: "/support/ajax/checkAlias",
	          dataType 	: 'json',
	          async 	: false,
	          data 		: ({this_alias 	: this_alias,
	          				model 		: model }),
	          success: function(data){   
	             
	            $('.Message').html('');

	            if(data.susses == 1){
	            	$('.Message').html('<i>Такой алиас - "'+this_alias+'", не найден</i>');
	            }
	            else
	            	$('.Message').html('<i>Найденно совпадение алиаса - "'+this_alias+'", в записи №:'+data.id+'</i>'); 
              	
	          } 
	    });

    } 
});


 


//jQuery.noConflict();

function createArr(obj){ 
	
	var _array = new Array();

	$.each(obj, function(i) {
	 	 _array[i] = obj[i].value; 
	});
	return _array;
}

$(document).on('click','a.tab-c[tab="#tab2"]', function(){
      
        var subject     = $('input[name="subject"]').val();
       
        var description = tinyMCE.get('description').getContent();

        var news        	= createArr($('#news option:selected'));  
        var final_list		= createArr($('#final_list option'));
        var mailerEnters	= $('#mailerEnters').val();
		  

        $.ajax({
            type: "POST",
            url: "/support/ajax/PrevSendMail",
            dataType : 'json', 
            data: ({  subject       : subject,
                      description   : description,
                      news          : news,
                      final_list    : final_list,
                      mailerEnters  : mailerEnters,

                    }),

            success: function(data){
                
                var header =  $('.mail_block.header').html();
                var footer =  $('.mail_block.footer').html();

            	$('#previewBlock').html(data.html);    
 

            }
        }); // end ajax   
}); 
 

 
$(document).on('click','.add-subscribers', function(){

	var mailerUsers  = $('#mailerUsers option:selected');
	var mailerGroups = $('#mailerGroups option:selected');
	var mailerEnters = $('#mailerEnters');

	$('#final_list').html('');

	var final_list   = $('#final_list option');

	if(mailerGroups.length > 0){
 

		var mailerGroupsArray = [];
		$.each(mailerGroups, function(i){
			mailerGroupsArray[i] = mailerGroups.eq(i).val();
		});

		$.ajax({
          	type: "POST",
          	url: "/support/ajax/getMails",
          	dataType : 'json', 
          	data: ({  mailerGroups  :  mailerGroupsArray }), 
          	success: function(data){ 
               
             	if(data.success == 1){ 
                  	 
             		$.each(data.mailsArray ,function(i){

             			var thiz = data.mailsArray[i];

             			if(final_list.length > 0){

             				$.each(final_list, function(ii){

             					var item = final_list.eq(ii);

             					if(item.val() !== thiz){
             						$('#final_list').append('<option value="'+thiz+'">'+thiz+'</option>'); 
             					}

             				}); // end each final_list
 
	                  	} else {

	                  		$('#final_list').append('<option value="'+thiz+'">'+thiz+'</option>'); 
	                  	}

             		}); // end each data.mailsArray  
                  	
              	}		
         	}
  		});   // end ajax
	}
 
  
	if(mailerUsers.length > 0){

		$.each(mailerUsers, function(i){

			var thiz = mailerUsers.eq(i).val();

			 

			if($('#final_list option').length > 0){
				$.each($('#final_list option'), function(ii){

					var item = $('#final_list').eq(ii);
 

					if(item.val() !== thiz){
						$('#final_list').append('<option value="'+thiz+'">'+thiz+'</option>'); 
					}

				}); // end each final_list
			} else {

          		$('#final_list').append('<option value="'+thiz+'">'+thiz+'</option>'); 
          	}	
		}); // end each mailerUsers
	}

	return false;
});

$(document).on('click','.del-subscribers', function(){
	
	$('#final_list option:selected').remove();
	return false;
});


$(document).on('click','.del-file, .del-photo', function(){

    var thiz  	= $(this);
    var type  	= thiz.attr('type');
    var id_file = thiz.attr('id-file');
    var id_model= thiz.attr('id-model');

    $.ajax({
          type: "POST",
          url: base_path+"/support/ajax/delPhoto",
          dataType : 'json', 
          data: ({  id_photo  : id_file, 
          			id_model  : id_model, 
                    type      : type, 
                  }),

          success: function(data){ 
               
              if(data.success == 1){

                  thiz.closest('tr').remove();
              }
         }
  });   // end ajax
});
/*

$(document).on('click','#sendMailButton', function(){
 

    var subject       = $('input[name="subject"]').val(); 
    var description   = tinyMCE.get('description').getContent();

    var news        	= createArr($('#news option:selected'));  
    var final_list		= createArr($('#final_list option'));
    var mailerEnters	= $('#mailerEnters').val();

    $.ajax({
            type: "POST",
            url: "/support/ajax/sendMail",
            dataType : 'json', 
            data: ({  subject       : subject,
                      description   : description,
                      news          : news,
                      final_list    : final_list,
                      mailerEnters  : mailerEnters,
                    }),

            success: function(data){ 
                 
                  if(data.susses == 1)
                    alert('Все отправленно');
                  else
                    alert(data.errArr);
           }
    });   // end ajax

});
*/


$(document).on('click', '.change-elements', function(){

	var thiz 	= $(this);
	var action 	= thiz.attr('type');  
	var checked = $('.chChildren:checked');

	if(checked.length > 0 ){

		if(action == 'delete_choose'){
			if (!confirm("Вы уверены что хотите удалить выбранные элементы?")) {
				return false;
			}
		}

		var idsArray = [];
		$.each(checked, function(i){

			idsArray[i] = $(this).val()*1; 
		});// end each

		$.ajax({
	        type: "POST",
	        url: base_path+"/support/ajax/changeElements",
	        dataType : 'json', 
	        data: ({idsArray : idsArray, 
	        		action   : action,
	          		model  	 : model}), 
	        success: function(data){ 
	               
	            if(data.success == 1){
	                location.reload();
	            }
	        }
	  });   // end ajax  
	}// end if checked count  

	$('.box-form.right').removeClass('open');
	return false;
});

/*
//добавление тегов 
$(document).on('click', '.add-new-tag', function(){

	var thiz 	= $(this);
	var data_id = thiz.attr('data-id');

	var html = '<div class="row"><div class="block"><input class="tag-autocomplete" size="60" maxlength="255" type="text" value="" name="newTagsTransfer[1][]" class="text"></div>';
		html +='<div class="block"><input class="tag-autocomplete" size="60" maxlength="255" type="text" value="" name="newTagsTransfer[2][]" class="text"></div>';
		html +='<button type-data="del-tag" data-id="new" class="del-tag btn btn-danger btn-mini" href="#">Удалить</button></div>';

	var rows = $('#tabs-tags .row').length;	

	if(rows > 0){
		$('#tabs-tags .row:last').after(html);
	} else {
		$('#tabs-tags').append(html);
	}

	return false;
});
*/
 
//удаление тега (связи)
$(document).on('click', '.del-tag', function(){

	var thiz 		= $(this);
	var data_id 	= thiz.attr('data-id');
	var type_data  	= thiz.attr('type-data');
	//var tag_id  	= thiz.attr('tag-id');  

	if(data_id > 0){
		$.ajax({
            type: "POST",
            url: "/support/ajax/delTag",
            dataType : 'json', 
            data: ({  data_id     : data_id,
                      type_data   : type_data,
                      //tag_id      : tag_id, 
                    }), 
    	});   // end ajax
	} 

	thiz.closest('.row').remove();

	return false;
});


/*
$(function() { 
 
    $( ".tag-autocomplete" ).autocomplete({
      source: function( request, response ) {
        $.ajax({
          url: "/support/ajax/autocomplete",
          dataType: "json",
          data: {	maxRows: 12,
            		q: request.term
          		},
          success: function( data ) {
            response( $.map( data.geonames, function( item ) {
              return {
                label: item.name + (item.adminName1 ? ", " + item.adminName1 : "") + ", " + item.countryName,
                value: item.name
              }
            }));
          }
        });
      },
      minLength: 2,
      select: function( event, ui ) { 
      },
      open: function() {
        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
      },
      close: function() {
        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
      }
    });
  });
*/
 
function getRandomArbitary(min, max){
  return Math.round(Math.random() * (max - min) + min);
}
 

$(document).on('click','#add-price-proposal', function(){

	var rand = getRandomArbitary(999999,9999999);
	var html = '<div class="box hover" style="width:300px;float:left;"><div class="title"><h4><span><input placeholder="цена" style="width:100px;" name="Product[price_proposal]['+rand+'][price_proposal]" id="" size="6" type="text" value="" class="text" /></span><span><input style="width:120px;margin-left:10px;"placeholder="кол-во от (цифрами)" name="Product[price_proposal]['+rand+'][value]" id="" type="text" value="" class="text" /></span><span style="float:right;cursor:pointer;" class="icon12 icomoon-icon-cancel-3 del-price_proposal-block"></span></h4></div></div>'; 

	$('#price_proposal').find('.span12').append(html);
 
	return false;
});


 $(document).on('click','.del-specification-block', function(){

	var box 		= $(this).closest('.box');
	var box_class 	= box.attr('class').split(' ');
		box_class   = box_class[2];
		
	$('.'+box_class).remove(); 

});

$(document).on('click','.specification', function(){
 
	var thiz 			= $(this);
	var spec_id 		= thiz.attr('data-id');
	var status  		= thiz.attr('checked');
	var spec_name 		= thiz.closest('.span1').find('label').text();
	var spec_class_name = 'spec_item_'+spec_id;


	//console.log(typeof status, spec_id );

	if(typeof status == 'undefined'){  

		$('.'+spec_class_name).remove();	
		 
	} else {

		var langs = $('#myTab0 li');

		//console.log(typeof status, spec_id, langs.length);

		if(langs.length > 0){

			$.each(langs, function(i){ 
				
				var html 			= '<div class="box hover '+spec_class_name+'"><div class="title"><h4><span>'+spec_name+'</span></h4></div><div class="content"><input placeholder="'+spec_name+'" name="Product[specifications]['+spec_id+'][value]['+$(this).attr('data')+']" id="" type="text" value="" class="text"></div></div>'; 
				var elements        = $('#spec_rows .tab-content #spec_tabs-'+(i+1)+' .row-elements');

				if(elements.find('.'+spec_class_name).length == 0){

					elements.append(html);	
				}

				
			});
 		} 
	}
	 
	
     
	return true;
});



