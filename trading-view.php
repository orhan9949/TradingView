<?php

/**
 * Template Name: TradingView
 */

header('Content-Type: application/xml');

$args = [
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page'=> 20,
    'order' => 'DESC',
    'orderby' => 'date',
    'meta_query' => [
        [
            'key' => 'cn_post_cryptocurrency',
            'value' => '',
            'compare' => '!='
        ]
    ]
];

$the_query = new WP_Query($args);

if ($the_query->have_posts()) {

   echo '<?xml version="1.0" encoding="UTF-8"?>';

    ?>

    <rss version="2.0">

        <channel>

            <title><?php echo get_bloginfo('name'); ?></title>

            <link><?php echo get_permalink( get_the_ID() ); ?></link>

            <description><?php echo get_bloginfo('description'); ?></description>

            <language><?php echo get_bloginfo('language'); ?></language>

            <lastBuildDate><?php echo date('r') ?></lastBuildDate>

            <?php

            while ($the_query->have_posts()) {

               $the_query->the_post();

               $description = preg_replace(['/<(div).*?>(.*?)<\/\1>/ism','/<!--(.*?)-->/'], '', get_the_content());

               $description = str_replace('&nbsp;', '', $description);

                ?>

                <item>

                    <title><?php echo esc_html(get_the_title()); ?></title>

                    <link><?php echo get_the_permalink(); ?></link>

                    <description><![CDATA[<?php echo $description; ?>]]></description>

                    <pubDate><?php echo date('r', strtotime(get_the_date())) ?></pubDate>

                    <guid><?php echo get_the_permalink(); ?></guid>

                    <?php
                    $cryptocurrency_ids_array = get_field('cn_post_cryptocurrency');

                    foreach ($cryptocurrency_ids_array as $cryptocurrency_id){

                        echo ' <category domain="tradingview:symbol">'.get_field('cn_cryptocurrency_tradingview_symbol', $cryptocurrency_id).'</category>';

                    }

                    ?>

                    <category domain="tradingview:market">crypto</category>

                    <category domain="tradingview:language">en</category>

                </item>

                <?php
            }

            ?>

        </channel>

    </rss>

    <?php
}

wp_reset_postdata();

?>