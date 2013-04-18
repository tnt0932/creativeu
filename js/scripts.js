/* scripts.js */

//hide snippet view from home

$(function() {
	$('#snippet_view').hide();
	//alert('hello');
});

//This function will run automatically after the page is loaded
$(document).ready(function() {
    masonry_init();
    filter_tab_switch();
    filter_container_slide_inout();
    nav_dropdown();
    alert_animation();
});



// This functions controls the switching between tabs in the filter dropdown
function filter_tab_switch() {
    $('#filter_container div.filter_content').hide();//tabsContent class is used to hide all the tabs content in the start
    $('div.filter1').show(); // It will show the first tab content when page load, you can set any tab content you want - just put the tab content class e.g. tab4
    $('#filter_container #filter_tabs li.1 a').addClass('tab-current');// We will add the class to the current open tab to style the active state
    //It will add the click event on all the anchor tag under the htmltabs class to show the tab content when clicking to the tab
    $('#filter_container ul li a').click(function() {
        var thisClass = this.className.slice(0,7);//"this" is the current anchor where user click and it will get the className from the current anchor and slice the first part as we have two class on the anchor
        $('#filter_container div.filter_content').hide();// It will hide all the tab content
        $('div.' + thisClass).show(); // It will show the current content of the user selected tab
        $('#filter_container #filter_tabs li a').removeClass('tab-current');// It will remove the tab-current class from the previous tab to remove the active style
        $(this).addClass('tab-current'); //It will add the tab-current class to the user selected tab
    });

}

function filter_container_slide_inout() {
    $('#filter_container').hide();
    $('#filter_reveal').click(function() {
        $('#filter_container').slideToggle(100);
    });
}

//masonry init
function masonry_init() {
    $('#snippet_stream_container').masonry({
        itemSelector: '.snippet_item',
        isFitWidth: true
    });
}

function trigger_modals(trigger, modal) {
    $(trigger).click(function(e) {
        $(modal).lightbox_me({
            centered: true, 
            closeClick: true,
            overlayCSS: {background: 'black', 
							opacity: .85}
        });
        e.preventDefault();
    });
}

function nav_dropdown(trigger, content) {
    $(trigger).click(function(e) {
        e.preventDefault();
        $(content).toggle();
        $(trigger).toggleClass("menu-open");
    });
    
    $(content).mouseup(function() {
        return false
    });
    $(document).mouseup(function(e) {
        if($(e.target).parent(trigger).length==0) {
            $(trigger).removeClass("menu-open");
            $(content).hide();
        }
    });
}

function password_strength(elem) {
    $(elem).complexify({minimumChars:6, strengthScaleFactor:0.4}, function(valid, complexity) {
        if (complexity > 0 && complexity < 30) {
//            $('.strength').css('background-color', 'red').html( 'BAD | complexity: ' + Math.round(complexity) + '%');
            $('.strength').css('background-color', 'red');
        }
        if (complexity >= 30 && complexity < 60 ) {
//            $('.strength').css('background-color', 'yellow').html( 'WEAK | complexity: ' + Math.round(complexity) + '%');
            $('.strength').css('background-color', 'yellow');
        }
        if (complexity >= 60 && complexity <= 100 ) {
//            $('.strength').css('background-color', 'green').html( 'GOOD | complexity: ' + Math.round(complexity) + '%');
            $('.strength').css('background-color', 'green');
        }
    });
}

function alert_animation() {
    $('.alert').hide().fadeIn(1000).delay(3000).fadeOut(3000);
}






