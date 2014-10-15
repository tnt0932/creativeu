/* scripts.js */

//hide snippet view from home

$(function() {
	$('#snippet_view').hide();
	//alert('hello');
});

//This function will run automatically after the page is loaded
$(document).ready(function() {
    filter_container_slide_inout();
    nav_dropdown();
    alert_animation();
});


function filter_container_slide_inout() {
    $('#filter_container').hide();
    $('#filter_reveal').click(function() {
        $('#filter_container').slideToggle(100);
    });
}

function trigger_modals(trigger, modal) {
    $(trigger).click(function(e) {
        $(modal).lightbox_me({
            centered: true,
            closeClick: true,
            overlayCSS: {background: 'black',
							opacity: 0.85}
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
        return false;
    });
    $(document).mouseup(function(e) {
        if($(e.target).parent(trigger).length===0) {
            $(trigger).removeClass("menu-open");
            $(content).hide();
        }
    });
}

function password_strength(elem) {
    $(elem).complexify({minimumChars:6, strengthScaleFactor:0.4}, function(valid, complexity) {
        if (complexity > 0 && complexity < 30) {
            $('.strength').css('background-color', 'red');
        }
        if (complexity >= 30 && complexity < 60 ) {
            $('.strength').css('background-color', 'yellow');
        }
        if (complexity >= 60 && complexity <= 100 ) {
            $('.strength').css('background-color', 'green');
        }
    });
}

function alert_animation() {
    $('.alert').hide().fadeIn(1000).delay(3000).fadeOut(3000);
}






