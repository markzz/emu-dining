function loadRatings(id) {
    $('.rating-box[data-id='+id+']').html('<center>Loading ratings...</center>').load('/ratings/'+id);
}

$(document).ready(function() {
    var $star_rating = $('.your-rating .star');

    $(document).on('click', '.your-rating .star', function() {
        var id = $(this).parent().data('id');
        var rating = $(this).data('rating');
        $('form[data-id='+id+'] input[name=rating]').val($(this).data('rating'));
        $('.your-rating[data-id='+id+'] .star').removeClass('on');
        $(this).addClass('on')
               .prevAll('.your-rating[data-id='+id+'] .star')
               .addClass('on');
    });

    if(window.location.hash) {
        var hash = window.location.hash;
        var split = hash.split('_item_');
        var tab = split[0];
        tab = tab.substring(1);
        var id = split[1];
        $('.nav li a[href="#'+tab+'"]').tab('show');
        $('#modal_'+tab+'_'+id).modal('show');
    }

    $('.item-modal').on('hidden.bs.modal', function () {
        window.location.hash = '';
    });

    $('.item-modal').on('shown.bs.modal',function() {
        var id = $(this).attr('data-id');
        var tab = $(this).data('tab');
        loadRatings(id);
        window.location.hash = tab+'_item_'+id;
        document.cookie = 'return_url=' + window.location + '; expires=Thu, 2 Aug 2030 20:47:11 UTC; path=/';
    });

    $(document).on('click', '.review-btn', function() {
        $(this).next('.review-panel').show();
        $(this).hide();
    });

    $(document).on('click', '.review-panel .cancel', function() {
        $(this).closest('.review-panel').prev('.review-btn').show();
        $(this).closest('.review-panel').hide();
    });

    $(document).on('submit', 'form.review', function(e) {
        var id = $(this).data('id');
        var rating = $('input[name="rating"]', this).val();
        var text = $('textarea[name="text"]', this).val();
        if($('input[name=rating]', this).val() < 1 || $('input[name=rating]', this).val() > 5) {
            alert('Please select a rating.');
            return false;
        }
        $.ajax({
            method: 'post',
            url: '/create_rating',
            data: {
                'item_id': id,
                'rating': rating,
                'text': text
            },
            success: function(data) {
                loadRatings(id);
            }
        });
        e.preventDefault();
    });

    $(document).on('click', '.delete-review', function() {
        var id = $(this).data('id');
        if(!confirm("Delete your review?")) return false;
        $.ajax({
            method: 'post',
            url: '/delete_rating',
            data: {
                'id': id
            },
            success: function(data) {
                loadRatings(id);
            }
        })
    });

    /*
    var middleElement = $('.date-wrapper.active').data('date');
    var currentPosition = dates[middleElement];

    console.log(currentPosition);
    */
    $('#cal-left').click(function() {
        var url = $('.date-wrapper[data-pos=1] a').attr('href');
        window.location = url;
    });
    $('#cal-right').click(function() {
        var url = $('.date-wrapper[data-pos=3] a').attr('href');
        window.location = url;
    });

});

function changeLocationDropdown(locationObj){
	//Change dropbox title
	var title = document.getElementById("locationsDropdown");
	title.innerHTML = locationObj.childNodes[0].innerHTML;
	
	//Clear higlights of all other options
	var dropMenu = document.getElementById("locationsDropdownMenu");
	var c = dropMenu.childNodes.length;
	for(var i=0; i<c; i++){
		dropMenu.childNodes[i].id = "";
	}
	
	//Highlight new option
	locationObj.id = "highlighted";
}

function changeModalText(givenText, givenTitle){
	var modal = document.getElementById("dietModal");
	modal.innerHTML = givenText;
	
	var modalTitle = document.getElementById("dietModalTitle");
	modalTitle.innerHTML = givenTitle;
}
