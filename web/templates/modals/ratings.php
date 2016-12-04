<?php
$rating = round(get_avg_rating($item_id));
?>

<div class="row">
    <div class="col-md-8">

        <?php if(is_logged_in()): ?>
        <button class="btn btn-primary btn-sm review-btn" style="margin-top:10px;">Review This Item</button>
        
        <div class="review-panel">
            <h5>Review This Item</h5>

            <form method="post" class="review" data-id="<?=$item_id?>">
                <input type="hidden" name="id" value="<?=$item_id?>" />
                <input type="hidden" name="rating" value="" />

                
                <span class="your-rating" style="font-size:22px;" data-id="<?=$item_id?>">
                    <span class="glyphicon glyphicon-star star star-off" data-rating="1"></span>
                    <span class="glyphicon glyphicon-star star star-off" data-rating="2"></span>
                    <span class="glyphicon glyphicon-star star star-off" data-rating="3"></span>
                    <span class="glyphicon glyphicon-star star star-off" data-rating="4"></span>
                    <span class="glyphicon glyphicon-star star star-off" data-rating="5"></span>
                </span>

                <textarea class="form-control" placeholder="Write a review... (optional)" name="text"></textarea>
                <br>
                <button class="btn btn-primary btn-sm">Submit</button>
                <button class="btn btn-default btn-sm cancel" type="button">Cancel</button>
            </form>
        </div>
        
        <?php else: ?>

        <a href="/login">Login</a> to leave a review.

        <?php endif; ?>

    </div>
    <div class="col-md-4">

        <h5>Average Rating</h5>

        <?php if($rating > 1): ?>
        <div class="rating-box" data-id="<?=$item->id?>">
            <div class="avg-rating" style="font-size:22px;">
                <span class="glyphicon glyphicon-star <?php if($rating > 0) echo 'on'; ?>"></span>
                <span class="glyphicon glyphicon-star <?php if($rating >= 2) echo 'on'; ?>"></span>
                <span class="glyphicon glyphicon-star <?php if($rating >= 3) echo 'on'; ?>"></span>
                <span class="glyphicon glyphicon-star <?php if($rating >= 4) echo 'on'; ?>"></span>
                <span class="glyphicon glyphicon-star <?php if($rating >= 5) echo 'on'; ?>"></span>
            </div>
        </div>
        <?php else: ?>
        <em>There aren't enough ratings yet.</em>
        <?php endif; ?>

    </div>
</div>