// load more courses button click event
jQuery(function($) {
    $('#load-more').on('click', function(){
        // our click button
        let $btn = $(this);

        // disable button (you can add spinner to button here too)
        $btn.prop('disabled', true);

        // set current courses empty array
        let currentCourses = [];

        // loop thought through all courses
        $('[data-course]', '#course-list').each( function(key, elem) {

            // current course push elements to current courses array
            currentCourses.push( $(elem).data('course') );

        });

        // re post current page running the query again
        $.post('', {

            // pass this data to our php var $excludeCourses to modify the page WP_Query
            excludeCourses: currentCourses

            // html param passed data is full html of our reloaded page
        }, function(html) {

            // find all the course div elements via data-course attribute in html
            let $newCourses = $('#course-list [data-course]', html);

            // if new courses length is less than our window.postsPerPage variable value
            if( $newCourses.length < postsPerPage ) {

                // then hide the load more button
                $btn.hide();

            }

            // insert new courses after last current data-course div
            $newCourses.insertAfter( $('[data-course]', '#course-list').last() );

        })

            // once our new courses have been inserted
            .done(function() {

                // do stuff here to lazy load images etc...

                // re-enable button (and stop button spinner if you have one)
                $btn.prop('disabled', false);

            });

    });
})
