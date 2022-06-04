jQuery(document).ready(function(){
    var currentUserTotalReferral = 0;
    var currentListUser;
    var refCount=0;
    var arrUserIds=new Array();
    jQuery('input[name="search_by_join_sdate"]').mask('0000/00/00');
    jQuery('input[name="search_by_join_edate"]').mask('0000/00/00');
    jQuery('input[name="search_start_date"]').mask('0000/00/00');
    jQuery('input[name="search_end_date"]').mask('0000/00/00');       
    jQuery('.woocommerce_page_wc_referral .wmcReferralsCount').each(function(i){
        var element=jQuery(this);    
        var userId=element.data('id');
        var userName=element.data('name'); 
        //fnSendAjaxRequest($element,'getReferralsDetails',$userId); 
            
       jQuery.get('?getReferralsCount=' + userId, function(count){
            if(isNaN(count)){
                count='<font color="red" ><strong>?</strong></font>';
            }
            element.html(count);
            if(!isNaN(count) && count>0){
                jQuery('#user-'+userId+' .wmcView').html('<a href="#" data-name="'+userName+'" class="view_hierarchie" data-total="'+count+'" data-id="'+userId+'">View hierarchy</a>');
            }else{
                 jQuery('#user-'+userId+' .wmcView').html('');
            }
          
            
        }); 
    });

    if( jQuery('#dropdown_referral_code').length ){
        jQuery('#dropdown_referral_code').select2({
            allowClear: true
        });
    }
    
    jQuery('.wmc-email-template a.mdl-tabs__tab').click(function(e){
        e.preventDefault();
        jQuery('.mdl-tabs__panel, .mdl-tabs__tab').removeClass('is-active');
        jQuery( this ).addClass('is-active');
        jQuery( jQuery(this).attr('href') ).addClass('is-active');
    });
    jQuery('#wmc-customer-based-bonus').click(function(e){
        jQuery('.bonus_for_all').toggleClass('wmc-hide');
        jQuery('.bonus_for_customer').toggleClass('wmc-hide');
    });
    //fnMainElements();    
    function fnMainElements($index){
        $total=jQuery('.woocommerce_page_wc_referral .wmcReferralsCount').length;
        if($index=='' || typeof $index === "undefined"){ 
            $index=0;                       
        }        
        if($index>=0 && $index < $total){
            $element=jQuery('.woocommerce_page_wc_referral .wmcReferralsCount').eq($index);
            console.log('fnMainElements=>'+$element.data('id'));            
            fnNewAjaxCaller($element,$element.data('id'),++$index);
            //fnMainElements(++$index);
        }
    }   
    function fnNewAjaxCaller(element,userId,index){
        jQuery.ajax({
          url: "?t="+Math.random(),
          type: 'POST',
          data: {
            action: 'getReferralsDetails', userId: userId
          },
          success: function(response) {            
            var arrObj = JSON.parse(response);
            if(arrObj.result=='success'){
                 if(arrObj.data!=0){
                     var count=jQuery(arrObj.data).length;
                     refCount+=count;
                     if(count>0){
                         jQuery.each(arrObj.data, function(key,valueObj){
                             if(jQuery.inArray(valueObj.user_id,arrUserIds)<0){
                                arrUserIds.push(valueObj.user_id);
                             }
                         });
                     }
                 }
            }
            /*console.log("fnNewAjaxCaller="+userId);
            console.log(arrUserIds); */
            if(arrUserIds.length>0){
                 fnNewAjaxCaller(element, arrUserIds.pop(),index);
            }else{
                 element.html(refCount);
                 parentId=element.data('id');
                 if(!isNaN(refCount) && refCount>0){
                    jQuery('#user-'+parentId+' .wmcView').html('<a href="#" data-name="'+element.data('name')+'" class="view_hierarchie" data-total="'+refCount+'" data-id="'+parentId+'">View hierarchy</a>');
                 }else{                                      
                    jQuery('#user-'+parentId+' .wmcView').html('');
                 }
                 refCount=0;    
                 fnMainElements(index);
            }    
          },
          error: function(error) {
            
          },
        });
          
    } 
    jQuery('.wmcView').on('click','a.view_hierarchie', function(e){
        e.preventDefault();
        currentListUser = jQuery(this);
        jQuery.get('?load_referral_user_list=' + jQuery(this).data('id'), function(data){
            jQuery('#dialog_referral_user').html( data );
            currentUserTotalReferral = jQuery(currentListUser).data('total');
            currentUserName = jQuery(currentListUser).data('name');
            jQuery('#dialog_referral_user').dialog({
                  title: 'List of '+currentUserName+' Referrals ('+currentUserTotalReferral+')', 
                  modal: true,
                  resizable: false,
                  width: 350,
                  height: 400,
                  open: function( event, ui ) {
                        jQuery('#referral_user_form .wp-list-table').css('width','calc(100% - 350px)');
                        jQuery('body.woocommerce_page_wc_referral .ui-dialog').css('top', jQuery(currentListUser).position().top );       
                    },
                  close: function(){
                        jQuery('#referral_user_form .wp-list-table').css('width','100%');
                  }
            });     
        });
        return false;
    });
    jQuery('#dialog_referral_user').on('click', '.get_referral_user', function(e){
        e.preventDefault();
        jQuery('#dialog_referral_user').addClass('loading');
        currentListObj = jQuery(this).parent('div').parent('li');
        fetchRecords = jQuery(currentListObj).data('get');
        if (fetchRecords) {
            jQuery('#dialog_referral_user').removeClass('loading');
            jQuery(currentListObj).find('ul').first().toggle('slow');
        }else{
            jQuery.get('?load_referral_user_list=' + jQuery(currentListObj).data('id'), function(data){
                jQuery(currentListObj).append( data );
                jQuery(currentListObj).data('get', 1);
                jQuery('#dialog_referral_user').removeClass('loading');
            });   
        }
        return false;
    });
    jQuery('.active_referral_user').click(function(e){
        e.preventDefault();
        currentListObj = jQuery(this).parent('div').parent('li');
        if (confirm('Are sure want to active this user?')) {
            jQuery('.wrap').addClass('loading');
            jQuery.get('?active_referral_user=' + jQuery(this).data('id'), function(data){
                window.location.href = data;
            })
        }
    });
    jQuery('#dialog_referral_user').on('click', '.remove_referral_user', function(e){
        e.preventDefault();
        if (confirm('Are sure want to remove?')) {
        
        currentListObj = jQuery(this).parent('div').parent('li');
        
            jQuery('#dialog_referral_user').addClass('loading');
            
        jQuery.get('?remove_referral_user=' + jQuery(currentListObj).data('id'), function(data){
            currentUserName = jQuery(currentListUser).data('name');
            if (jQuery(currentListObj).parents('li').size() > 0) {
                currentListObj = jQuery(currentListObj).parents('li').first();
                jQuery(currentListObj).find('ul').remove()
                jQuery(currentListObj).append( data );
                
                count = parseInt(jQuery(currentListObj).find('.count').first().html());
                if (count > 0) {
                     jQuery(currentListObj).find('.count').first().html( count - 1 );
                }
                   
                jQuery(currentListObj).parents('li').each(function(){
                   count = parseInt(jQuery(this).find('.count').first().html());
                   if (count > 0) {
                        jQuery(this).find('.count').first().html( count - 1 );
                   }
                });
            }else{
                jQuery('#dialog_referral_user').html( data );
            }
            if( currentUserTotalReferral  > 0 ){
                currentUserTotalReferral = currentUserTotalReferral - 1
                jQuery('.ui-dialog-title').html( 'List of '+currentUserName+' Referrals ('+currentUserTotalReferral+')' );
                jQuery(currentListUser).parents('tr').find('td.no_of_followers').html( currentUserTotalReferral );
            }
            
            jQuery('#dialog_referral_user').removeClass('loading');
        });
        }
        return false;
    });
    jQuery('#referral_user_form #reset_button').click(function(){
       jQuery('#referral_user_form input[type=text]').val(''); 
       jQuery('#referral_user_form').submit(); 
    });
    jQuery('#form_widthdraw_filter #reset_button_withdraw').click(function(){
       jQuery('#form_widthdraw_filter input[type=text]').val(''); 
       jQuery('#form_widthdraw_filter').submit(); 
    });
    jQuery('input[type=radio][name=wmc-levelbase-credit]').change(function() {       
        if(this.value==1){
            jQuery('.wmc-optional').removeClass('wmc-hide');
            $totalLevels=jQuery('table.wmc-level-table .wmc-level').length;
            if(($totalLevels)>1){
                jQuery('.wmc-buttons #wmc-delete-last').show();
            }else{
                jQuery('.wmc-buttons #wmc-delete-last').hide();
            }
        }else{
            jQuery('.wmc-optional').addClass('wmc-hide');
            jQuery('.wmc-buttons #wmc-delete-last').hide();
        }
    });
    jQuery('#wmc-add-more').click(function(){
        $totalLevels=jQuery('table.wmc-level-table .wmc-level').length;        
        $row=jQuery('table.wmc-level-table .wmc-level[data-level=1]').clone();         
        $row.attr('data-level',$totalLevels+1);
        $row.find('label').attr('for','wmc-level-'+($totalLevels+1));
        $row.find('input[type=number]').attr('id','wmc-level-'+($totalLevels+1)).val(0);
        $row.find('label span').html($totalLevels+1);        
        jQuery('table.wmc-level-table > tbody').append($row);
        jQuery('#wmc-max-level').val($totalLevels+1);
        if(($totalLevels+1)>1){
            jQuery('.wmc-buttons #wmc-delete-last').show();
        }else{
            jQuery('.wmc-buttons #wmc-delete-last').hide();
        }
    });
    jQuery('.wmc-buttons #wmc-delete-last').click(function(){
        $totalLevels=jQuery('table .wmc-level').length;
        if($totalLevels > 1){
            jQuery('table.wmc-level-table tr:last').remove();
            $totalLevels--;
            jQuery('#wmc-max-level').val($totalLevels);         
        }
        if($totalLevels < 2){           
            jQuery('.wmc-buttons #wmc-delete-last').hide();
        }
    });  
    function scrollHorizontally(e) {
        e = window.event || e;
        var delta = Math.max(-1, Math.min(1, (e.wheelDelta || -e.detail)));
        document.getElementById('wmc_header_tabs').scrollLeft -= (delta*40); // Multiplied by 40
        e.preventDefault();
    }
    if( jQuery('#wmc_header_tabs').length ){
        if (document.getElementById('wmc_header_tabs').addEventListener) {
            // IE9, Chrome, Safari, Opera
            document.getElementById('wmc_header_tabs').addEventListener("mousewheel", scrollHorizontally, false);
            // Firefox
            document.getElementById('wmc_header_tabs').addEventListener("DOMMouseScroll", scrollHorizontally, false);
        } else {
            // IE 6/7/8
            document.getElementById('wmc_header_tabs').attachEvent("onmousewheel", scrollHorizontally);
        }  
    }
    jQuery('#wmc_auto_register').change(function(){
        manage_wmc_referral_code_field();
    });
    manage_wmc_referral_code_field();
});


function manage_wmc_referral_code_field(){
    if( jQuery( '#wmc_auto_register' ).val() == 'yes' ){
        jQuery( '#wmc_required_referral' ).parents('tr').show();
    }else{
        jQuery( '#wmc_required_referral' ).parents('tr').hide();
    }
}