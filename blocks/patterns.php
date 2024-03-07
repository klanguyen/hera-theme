<?php
// register a new block pattern category
add_action( 'init', function() {
    register_block_pattern_category(
        'hera', // slug used in pattern files
        ['label' => 'HERA'] // what the user sees
    );
});

// register block patterns found in this
// plugin's /patterns/ folder
add_action( 'init', function(){
    $default_headers = array(
        'title'         => 'Title',
        'slug'          => 'Slug',
        'description'   => 'Description',
        'viewportWidth' => 'Viewport Width',
        'inserter'      => 'Inserter',
        'categories'    => 'Categories',
        'keywords'      => 'Keywords',
        'blockTypes'    => 'Block Types',
        'postTypes'     => 'Post Types',
        'templateTypes' => 'Template Types',
    );

    $files = glob( __DIR__ . '/patterns/*.php' );
    foreach ( $files as $file ) {
        // get fields from header comment
        $pattern = get_file_data($file, $default_headers);

        // get pattern content
        ob_start();
        include $file;
        $pattern['content'] = ob_get_clean();

        // clean up
        $pattern['source'] = 'plugin';
        $pattern['categories'] = wp_parse_list($pattern['categories']);
        $pattern['keywords'] = wp_parse_list($pattern['keywords']);
        $pattern['blockTypes'] = wp_parse_list($pattern['blockTypes']);
        $pattern['postTypes'] = wp_parse_list($pattern['postTypes']);
        $pattern['templateTypes'] = wp_parse_list($pattern['templateTypes']);

        // if valid, register block pattern
        if ( $pattern['title'] && $pattern['slug'] && $pattern['content']  ) {
            register_block_pattern( $pattern['slug'], array_filter($pattern) );
        }
    }
});
