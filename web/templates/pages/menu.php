<?php 
$locations = get_locations();
$short_name = $tokens[1];

if(isset($tokens[2])) {
    $date = $tokens[2];
} else {
    $date = date('Y-m-j', time());
}

$location_info = get_location_info($short_name);
$menu = get_menu($date, $location_info['location_id']);

$periods = $menu->menu->periods;

// 20 days before and after
$earliest_date = date('Y-m-j', time()-(60*60*24*20));
$future_date = date('Y-m-j', time()+(60*60*24*20));
$dates = create_date_range($earliest_date, $future_date);

foreach($dates as $d) {
    $dates2[] = [
        'weekday' => date('l', strtotime($d)),
        'day' => date('j', strtotime($d)),
        'month' => date('F', strtotime($d)),
        'formatted' => $d
    ];
}
$json_date = json_encode($dates2);

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

var dates = <?= $json_date ?>;

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

    $('.modal').on('hidden.bs.modal', function () {
        window.location.hash = '';
    });

    $('.modal').on('shown.bs.modal',function() {
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

    var middleElement = $('.date-wrapper.active').data('date');
    var currentPosition = dates[middleElement];

    console.log(currentPosition);

    $('#cal-right').click(function() {
    });
    $('#cal-left').click(function() {
        console.log(dates);
    });

});
</script>

<!-- Location -->
<div id="dynamicCenter">
  <div class="dropdown">
    <button class="btn btn-default dropdown-toggle" type="button" id="locationsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
      <?= $location_info['name'] ?>
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" aria-labelledby="locationsDropdown" id="locationsDropdownMenu">
        <?php foreach($locations as $location): ?>
        <li><a href="/menu/<?=$location['short_name']?>"><?=$location['name']?></a></li>
        <?php endforeach; ?>
    </ul>
  </div>
</div>

<br />

<!-- Calendar -->
<div class="calendar-wrapper">
  <div class="glyphicon glyphicon-chevron-left" id="cal-left">
  </div>

  <?php 
  $current_day = strtotime($date);
  $day_before = $current_day-(60*60*24);
  $day_after = $current_day+(60*60*24);
  $short_dates = [$day_before, $current_day, $day_after];
  $counter=0;
  foreach($short_dates as $time): 
    $counter++;
    $weekday = date('D', $time);
    $day = date('d', $time);
    $month = date('M', $time);
    $date_formatted = date('Y-m-j', $time);
  ?>
  <div class="date-wrapper <?php if($counter==2) echo 'active'; ?>" data-pos="<?=$counter?>" data-date="<?=$date_formatted?>">
    <a href="/menu/<?=$short_name?>/<?=$date_formatted?>">
    <div class="date-weekday">
      <?=$weekday?>
    </div>
    <div class="date-month">
      <?=$month?>
    </div>
    <div class="date-day">
      <?=$day?>
    </div>
    </a>
  </div>
  <?php endforeach; ?>

  <div class="glyphicon glyphicon-chevron-right" id="cal-right">
  </div>
</div>
<br />
<div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">

    <?php foreach($periods as $key => $period): ?>
        <li class="<?php if($key == 0) echo 'active'; ?>" role="presentation">
            <a href="#<?=$period->id?>" data-toggle="tab"><?=$period->name?></a>
        </li>
    <?php endforeach; ?>

  </ul>

  <!--<div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a id="clickableFoodItem">Grilled Chicken Caesar Wrap </a>
            <div id="foodDesc">Classic Caesar with a fresh squeeze of lemon adds zing to this classic wrap</div>
          </h4>

          <div id="dietWrapper">
            <img onclick="changeModalText('Food that has balanced nutrients &amp; portion size', 'Balanced')" data-toggle="modal" data-target=".bs-example-modal-sm" class="icon" src="images/img/icon_balanced_200px.png ">
            <img onclick="changeModalText('Containing a sustainable ingredient, such as local produce or seafood', 'Sustainable')" data-toggle="modal" data-target=".bs-example-modal-sm" class="icon" src="images/img/icon_sustainable_200px.png ">
            <img onclick="changeModalText('Vegan menu options are free of all animal-based ingredients and by-products', 'Vegan')" data-toggle="modal" data-target=".bs-example-modal-sm" class="icon" src="images/img/icon_vegan_200px.png ">
            <img onclick="changeModalText('Containing no solid meat but may contain eggs or dairy', 'Vegetarian')" data-toggle="modal" data-target=".bs-example-modal-sm" class="icon" src="images/img/icon_vegetarian_200px.png ">
          </div>

          <div id="extraInfo" class="rightExtraInfo">
            <h3 class="panel-title" style="width: 150px;">
                <strong style="margin-right: 30px;">1</strong>
            </h3>
            <h3 class="panel-title" style="width: 150px;">
                <strong style="margin-left: 87px;">2</strong>
            </h3>
          </div>
        </div>
      </div>-->

  <!-- Tab panes -->
  <div class="tab-content">
    <?php if(count($periods) == 0): ?>
        <br><br>
        <div class="alert alert-info">
        The student center is closed today.
        </div>
    <?php endif; ?>

    <?php foreach($periods as $key => $period): ?>
        <div role="tabpanel" class="tab-pane <?php if($key == 0) echo 'active'; ?>" id="<?=$period->id?>">

            <?php foreach($period->categories as $category): ?>

                <h3><?=$category->name?></h3>

                <table class="table table-responsive">
                <tr>
                    <th class="col-md-3"></th>
                    <th class="col-md-3"></th>
                    <th class="extraInfo">Portion</th>
                    <th class="extraInfo">Calories</th>
                </tr>
                <?php foreach($category->items as $item): ?>
                    <tr>
                        <td>
                            <div class="pull-left">
                                <b><a href="#" class="menu-item-link" data-toggle="modal" data-id="<?=$item->id?>" data-target="#modal_<?=$period->id.'_'.$item->id?>"><?=$item->name?></a></b>
                                <br />
                                <div class="foodDesc"><?=$item->desc?></div>
                            </div>
                            <div class="dietWrapper pull-left">
                                
                            </div>
                            <div class="clearfix"></div>
                        </td>
                        <td><?php echo generate_filter_icons_html($item->filters, 30); ?></td>
                        <td class="extraInfo"><?=$item->portion?></td>
                        <td class="extraInfo"><?=$item->calories?> calories</td>
                    </tr>

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
                </table>

            <?php endforeach; ?>

        </div>
    <?php endforeach; ?>
  </div>

</div>

<?php get_footer() ?>