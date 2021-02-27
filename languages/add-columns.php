<?php

function chi_add_post_inzerce_columns( $columns )
{

    $columns["ID"] = __("ID", "chi-advertising");
    $columns["shortcode"] = __("Shortcode", "chi-advertising");

    return $columns;

}

function chi_add_post_inzerce_columns_data( $column, $post_id )
{

    if ($column == "ID")
    {
        ?>
        <a class="row-title" href="<?php echo get_edit_post_link($post_id); ?>">
            <span class="questionId"><?php echo $post_id; ?></span>
        </a>
        <?php
    }

    if ($column == "shortcode")
    {
        echo '[advertising id="'.$post_id. '"]';
    }

}
