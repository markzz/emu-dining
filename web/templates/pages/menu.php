<?php 
$short_name = $tokens[1];
$location_info = get_location_info($short_name);
$date = "2016-12-5"; //date('Y-m-j', time());
$menu = get_menu($date, $location_info['location_id']);

$periods = $menu->menu->periods;

// Header
get_header("Menu");
?>

<style>
.your-rating {
    cursor: pointer;
}
.your-rating span.glyphicon-star {
    color: #ddd;
}
span.glyphicon-star.on {
    color: #f4c542;
}
.review-panel {
    display: none;
}
</style>

<script>
function loadRatings(id) {
    $('.rating-box[data-id='+id+']').load('/ratings/'+id);
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

    $('.modal').on('hidden.bs.modal', function () {
        window.location.hash = '';
    });

    $('.modal').on('shown.bs.modal',function() {
        var id = $(this).attr('data-id');
        var tab = $(this).data('tab');
        loadRatings(id);
        window.location.hash = tab+'_item_'+id;
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
        if($('input[name=rating]', this).val() < 1 || $('input[name=rating]', this).val() > 5) {
            alert('Please select a rating.');
            return false;
        }
        e.preventDefault();
    });

});
</script>

<h1><?=$location_info['name']?></h1>

<div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">

    <?php foreach($periods as $key => $period): ?>
        <li class="<?php if($key == 0) echo 'active'; ?>" role="presentation">
            <a href="#<?=$period->id?>" data-toggle="tab"><?=$period->name?></a>
        </li>
    <?php endforeach; ?>

  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <?php foreach($periods as $key => $period): ?>
        <div role="tabpanel" class="tab-pane <?php if($key == 0) echo 'active'; ?>" id="<?=$period->id?>">

            <?php foreach($period->categories as $category): ?>

                <h3><?=$category->name?></h3>

                <ul>
                <?php foreach($category->items as $item): ?>

                    <li>
                        <b><a href="#" class="menu-item-link" data-toggle="modal" data-id="<?=$item->id?>" data-target="#modal_<?=$period->id.'_'.$item->id?>"><?=$item->name?></a></b>, 
                        <?=$item->portion?>, <?=$item->calories?> calories
                        <p><?=$item->desc?></p>
                        <p>
                            <?php echo generate_filter_icons_html($item->filters); ?>
                        </p>
                    </li>

                    <div class="modal fade" tabindex="-1" role="dialog" id="modal_<?=$period->id.'_'.$item->id?>" data-id="<?=$item->id?>" data-tab="<?=$period->id?>">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">
                                <?=$item->name?>
                                <br>
                                <em style="font-size:13px"><?= $item->desc ? $item->desc : 'No description available.' ?></em>
                            </h4>
                          </div>
                          <div class="modal-body">
                                
                                <div class="rating-box" data-id="<?=$item->id?>">
                                    <center>Loading ratings...</center>
                                </div>

                                <div class="clearfix"></div>

                                <hr />

                                <h5>Nutrients</h5>
                                <div class="row" style="font-size:12px;">
                                    <div class="col-xs-4">
                                    <?php for($i = 0; $i < count($item->nutrients); $i++): ?>
                                            <b><?=$item->nutrients[$i]->name?>:</b>
                                            <?=$item->nutrients[$i]->value?>
                                            <br>
                                            <?php if($i != 0 && $i % 4 == 0): ?>
                                            </div>
                                            <div class="col-xs-4">
                                            <?php endif; ?>
                                    <?php endfor; ?>
                                    </div>
                                </div>
                                <br />
                                <p style="font-size:12px;"><b>Ingredients:</b> <?=$item->ingredients?></p>

                                <?php echo generate_filter_icons_html($item->filters, 45); ?>

                          </div>
                        </div><!-- /.modal-content -->
                      </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->

                <?php endforeach; ?>
                </ul>

            <?php endforeach; ?>

        </div>
    <?php endforeach; ?>
  </div>

</div>

<?php get_footer() ?>