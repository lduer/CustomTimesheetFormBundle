
/**
 * function detects the media query ranges from bootstrap and checks if the "alias" is visible
 *
 * input can be a string or an array
 * 'xs' or ['xs', 'xm']
 *
 * requires the following code right before the "</body>" tag:
 * <code>
 <div class="device-xs visible-xs-block"></div>
 <div class="device-sm visible-sm-block"></div>
 <div class="device-md visible-md-block"></div>
 <div class="device-lg visible-lg-block"></div>
 </code>
 *
 * CAUTION: This code works with Bootstrap v3
 *
 * @param {type} alias of bootstraps size-keys (xs,sm,md,lg)
 * @returns {Boolean}
 */
function mediaQueryVisible(alias) {
    if (!(alias.constructor === Array)) {
        alias = new Array(alias);
    }

    var isVisible = false;
    try {
        for (var i = 0; i < alias.length; i++) {
            var bootstrapAlias = alias[i];

            isVisible = $('.device-' + bootstrapAlias).is(':visible');
            if (isVisible === true) {
                break;
            }
        }
    } catch (e) {
        isVisible = false;
    }


    return isVisible;
}