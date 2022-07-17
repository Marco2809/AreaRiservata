$(function(){
    // initialize custom pager script BEFORE initializing tablesorter/tablesorter pager
    // custom pager looks like this:
    // 1 | 2 … 5 | 6 | 7 … 99 | 100
    //   _       _   _        _     adjacentSpacer
    //       _           _          distanceSpacer
    // _____               ________ ends (2 default)
    //         _________            aroundCurrent (1 default)

    var $table = $('#suppliers-list'),
        $pager = $('.pager');

    $.tablesorter.customPagerControls({
            table          : $table,                   // point at correct table (string or jQuery object)
            pager          : $pager,                   // pager wrapper (string or jQuery object)
            pageSize       : '.pager-right a',                // container for page sizes
            currentPage    : '.pager-center a',               // container for page selectors
            ends           : 2,                        // number of pages to show of either end
            aroundCurrent  : 1,                        // number of pages surrounding the current page
            link           : '<a href="#">{page}</a>', // page element; use {page} to include the page number
            currentClass   : 'current',                // current page class name
            adjacentSpacer : '<span> | </span>',       // spacer for page numbers next to each other
            distanceSpacer : '<span> &#133; <span>',   // spacer for page numbers away from each other (ellipsis = &amp;#133;)
            addKeyboard    : true,                     // use left,right,up,down,pageUp,pageDown,home, or end to change current page
            pageKeyStep    : 25                        // page step to use for pageUp and pageDown
    });

    // initialize tablesorter & pager
    $table
            .tablesorter({
                textExtraction:function(s) {
                    if($(s).find('img').length == 0) return $(s).text();
                    return $(s).find('img').attr('alt');
                }
            })
            .tablesorterPager({
                    // target the pager markup - see the HTML block below
                    container: $pager,
                    size: 25,
                    output: 'showing: {startRow} to {endRow}'
            });

});