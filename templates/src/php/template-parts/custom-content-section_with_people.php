<?php
$title = $item['title'];
$people = $item['people'];
$i = 0;
?>

<div class="row">
    <div class="col-12">
        <?php if($title) : ?>
        <div class="title">
            <h2><?php echo $title; ?></h2>
        </div>
        <?php endif; ?>
        <div class="row">
            <?php foreach($people as $key=>$employee): ?>
            <div class="col-12 col-md-6 item">
                <div class="employee">
                    <div class="employee-image">
                        <img src="<?php echo $employee['image']; ?>" class="img-fluid" alt="<?php echo $employee['name']; ?>">
                    </div>
                    <div class="employee-meta">
                        <div class="employee-meta-name"><h3><i><?php echo $employee['name']; ?></i></h3></div>
                        <div class="employee-meta-position"><p><?php echo $employee['position']; ?></p></div>
                        <div class="employee-meta-phone">
                            <div class="employee-meta-phone-icon"></div>
                            <a href="tel:<?php echo $employee['phone']; ?>"><?php echo $employee['phone']; ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
