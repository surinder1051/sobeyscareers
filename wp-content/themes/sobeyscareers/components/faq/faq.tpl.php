<div <?php $this->component_class() ?> data-js-faq>
	<div class="container">
            <?php
                $current = 0;
                $post_current = 0;
            ?>
            <?php foreach($faqs as $faq => $posts) : ?>
            <?php $active_class = $current === 0 ? ' -active' : ''; ?>
            <?php $title = sanitize_title($faq); ?>
                <div class="category-list">
                    <?php $active_class = $current === 0 ? ' -active' : ''; ?>
                    <?php $title = sanitize_title($faq); ?>
                    <span class="category-item<?php echo $active_class; ?>" data-target="section-<?php echo $node_id; ?>-<?php echo $current; ?>" role="button" tabindex="0" id="item-<?php echo $node_id;?>-<?php echo $current; ?>"><?php echo esc_attr($faq) ?><span class="active-icon button-icon-right fas-icon <?php echo $active_icon ?>"></span></span>
                </div>
                <div id="section-<?php echo $node_id; ?>-<?php echo $current; ?>" class="question-section <?php echo $active_class; ?>">
                    <div class="accordion" id="accordion-<?php echo $node_id; ?>-<?php echo $current; ?>">
                    <?php foreach($posts as $index => $post) : ?>
                        <div class="card">
                            <div class="card-header" id="card-<?php echo $node_id; ?>-<?php echo $post_current; ?>">
                            <h3 class="mb-0">
                                <?php $collapse_class = ($post_current === 0) ? '' : ' collapsed'; ?>
                                <?php $expanded = ($post_current === 0) ? 'true' : 'false'; ?>
                                <button class="<?php echo $collapse_class ?>" data-toggle="collapse" data-target="#collapse-<?php echo $node_id; ?>-<?php echo $post_current; ?>" aria-expanded="<?php echo $expanded;?>" aria-controls="collapse-<?php echo $node_id; ?>-<?php echo $post_current; ?>">
                                <?php echo $post->post_title; ?>
                                <span class="-opened button-icon-right <?php echo $open_icon ?>" role="decoration"></span>
                                <span class="-closed button-icon-right <?php echo $closed_icon ?>" role="decoration"></span>
                                </button>
                            </h3>
                            </div>
                            <?php $show_class = ($post_current) === 0 ? ' show' : ''; ?>
                            <div id="collapse-<?php echo $node_id; ?>-<?php echo $post_current; ?>" class="collapse<?php echo $show_class; ?>" aria-labelledby="card-<?php echo $node_id; ?>-<?php echo $post_current; ?>" data-parent="#accordion-<?php echo $node_id; ?>-<?php echo $current; ?>">
                                <div class="card-body">
                                    <?php echo $post->post_content; ?>
                                </div>
                            </div>
                        </div>
                    <?php $post_current++; ?>
                    <?php endforeach; ?>
                    </div>
                </div>
            <?php $current++; ?>
            <?php endforeach; ?>
	</div>
</div>