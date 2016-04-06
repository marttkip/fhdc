<?php
if (!isset($_ROOT)) $_ROOT = "";
if (!isset($_ASSETS)) $_ASSETS = "";
if (!isset($_COMPONENTS)) $_COMPONENTS = "";

if (!DEV && $section && $section != 'index') setLEVEL(1);
if (!DEV && $sub_section && $sub_section != 'index') setLEVEL(2);

function getLEVEL()
{
	global $_LEVEL;
	
	$level = $_LEVEL == 0 ? '' : '';
	$level = $_LEVEL > 0 ? str_repeat('../', $_LEVEL) : $level;

	return $level;
}

function setLEVEL($level = false)
{
	global $_LEVEL;
	$_LEVEL = $level ? $level : LEVEL;
}

function getURL($query = false, $components = false)
{
    global $_ASSETS;
    global $_COMPONENTS;
    global $module;

    if (!$query && !$components)
        return getLEVEL() . $_ASSETS;

    if (!$query && $components)
        return getLEVEL() . $_COMPONENTS;

    if ($query === true)
        return DEV ? getLEVEL() . $module . "/" : getLEVEL();

    if (!is_array($query))
        return $query ? (DEV ? 'index.php' : getLEVEL() . 'index.html') : getLEVEL();
    else
    {
        // maybe remove the section from the link when calling getURL from another section page or sub-page
        // e.g. calling getURL(array('page' => 'google_maps', 'section' => false)) from the google_maps page,
        // fullscreen section - will create a link to the main google_maps page;
        if (isset($query['section']) && $query['section'] == false)
        {
            unset($query['section']);
            unset($_GET['section']);
        }

        $url = DEV ?
            ( !isset($query[0]) ? "?" . http_build_query($query, '', '&amp;') : 'index.php?page=' . $query[0] ) :
            getLEVEL() . implode("/", $query) . ".html";

        // switching module
        if (!DEV && isset($query['module']) && $module !== $query['module'])
            $url = "../" . $url;

        $url_params = [];
        foreach($_GET as $gqk => $gqv)
        {
            if ($gqk == 'section')
                continue;

            // if the option was specifically requested, ignore this $_GET query
            // eg. getURL(array('option_name' => 'option_value')) will ignore $_GET['option_name']
            // and build the url with the requested value
            if (isset($query[$gqk]))
                continue;

            // when page is passed as getURL(array('page_name')), strip the page from the build query
            if (isset($query[0]) && $gqk == 'page')
                continue;

            // when not in DEV mode, keep the options
            if (!DEV && in_array($gqk, ['page', 'module', 'url_rewrite']))
                continue;

            // clean urls for the build version
            if (isset($_GET['build']))
                continue;

            $url_params[] = $gqk . "=" . $gqv;
        }

        if (!empty($url_params))
        {
            if (isset($query[0])) {
                if (DEV) $url .= '&amp;';
                else $url .= '?';
            }
            else $url .= '?';
            $url .= implode("&amp;", $url_params);
        }
    }
    return $url;
}

function starts_with($haystack, $needle)
{
    return $needle === "" || strpos($haystack, $needle) === 0;
}
function ends_with($haystack, $needle)
{
    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}

function in_array_r($needle, $haystack, $strict = false)
{
    if (!is_array($haystack))
        return false;

    foreach ($haystack as $item)
    {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict)))
        {
            return true;
        }
    }
    return false;
}

function var_export_min($var, $return = false)
{
    if (is_array($var)) {
        $toImplode = array();
        foreach ($var as $key => $value) {
            $toImplode[] = var_export($key, true).'=>'.var_export_min($value, true);
        }
        $code = 'array('.implode(',', $toImplode).')';
        if ($return) return $code;
        else echo $code;
    } else {
        return var_export($var, $return);
    }
}