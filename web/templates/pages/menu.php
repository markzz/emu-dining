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

/*foreach($dates as $d) {
>>>>>>> b8c28fe906c387fc58732d95ffc6649e75eb236f
    $dates2[] = [
        'weekday' => date('l', strtotime($d)),
        'day' => date('j', strtotime($d)),
        'month' => date('F', strtotime($d)),
        'formatted' => $d
    ];
}
$json_date = json_encode($dates2);*/

// Header
get_header($location_info['name'] . " - EMU Dining");
?>

<script>
$(document).ready(function() {
    $('#date-picker').submit(function(e) {
        var date = $('input', this).val();
        window.location = '/menu/' + '<?=addslashes($location_info['short_name'])?>/' + date;
        e.preventDefault();
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
        <li><a href="/menu/<?=$location['short_name']?>/<?=$date?>"><?=$location['name']?></a></li>
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

<div class="dateSearchWrapper">
    <form method="post" id="date-picker">
      <div class="input-group input-datepicker show-input">
            <input type="date" class="form-control" data-format="YYYY-MM-DD" placeholder="YYYY-MM-DD" value="<?=$date?>">
            <span class="input-group-btn">
                <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-circle-arrow-right"></span></button>
            </span>
      </div>
    </form>
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
        <?=$location_info['name']?> is closed today.
        </div>
    <?php endif; ?>

    <?php foreach($periods as $key => $period): ?>
        <div role="tabpanel" class="tab-pane <?php if($key == 0) echo 'active'; ?>" id="<?=$period->id?>">

            <?php foreach($period->categories as $category): ?>

                <h3><?=$category->name?></h3>

                <table class="table table-responsive">
                <tr>
                    <th></th>
                    <th></th>
                    <th class="extraInfo">Portion</th>
                    <th class="extraInfo">Calories</th>
                </tr>
                <?php foreach($category->items as $item): ?>
                    <tr>
                        <td>
                                <b><a href="#" class="menu-item-link" data-toggle="modal" data-id="<?=$item->id?>" data-target="#modal_<?=$period->id.'_'.$item->id?>"><?=$item->name?></a></b>
                                <br />
                                <div class="foodDesc"><?=$item->desc?></div>
                            <div class="clearfix"></div>
                        </td>
                        <td><div class="dietWrapper"><?php echo generate_filter_icons_html($item->filters, 30); ?></div></td>
                        <td class="extraInfo"><?=$item->portion?></td>
                        <td class="extraInfo"><?=$item->calories?></td>
                    </tr>

                    <div class="modal item-modal fade" tabindex="-1" role="dialog" id="modal_<?=$period->id.'_'.$item->id?>" data-id="<?=$item->id?>" data-tab="<?=$period->id?>">
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

<!-- Dietary Key modals -->
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="dietModalTitle">Default title</h4> </div>
      <div class="modal-body">
        <p id="dietModal"></p>
        <p>
        </p>
      </div>
    </div>
  </div>
</div>

<?php get_footer() ?>
