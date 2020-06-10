<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Classic theme callbacks.
 *
 * @package    theme_stiftungschweiz
 * @copyright  2020 Guy Thomas Citricity Ltd
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

/**
 * Returns the main SCSS content.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_stiftungschweiz_get_main_scss_content($theme) {
    global $CFG;

    $scss = '';

    $scss .= file_get_contents($CFG->dirroot . '/theme/stiftungschweiz/scss/stiftungschweiz/pre.scss');

    $scss .= file_get_contents($CFG->dirroot . '/theme/stiftungschweiz/scss/preset/stiftungschweiz.scss');

    $scss .= file_get_contents($CFG->dirroot . '/theme/stiftungschweiz/scss/stiftungschweiz/post.scss');

    return $scss;
}

/**
 * Get SCSS to prepend.
 *
 * @param theme_config $theme The theme config object.
 * @return array
 */
function theme_stiftungschweiz_get_pre_scss($theme) {
    $scss = '';
    $configurable = [
        // Config key => [variableName, ...].
        'brandcolor' => ['primary'],
    ];

    // Prepend variables first.
    foreach ($configurable as $configkey => $targets) {
        $value = isset($theme->settings->{$configkey}) ? $theme->settings->{$configkey} : null;
        if (empty($value)) {
            continue;
        }
        array_map(function($target) use (&$scss, $value) {
            $scss .= '$' . $target . ': ' . $value . ";\n";
        }, (array) $targets);
    }

    // Prepend pre-scss.
    if (!empty($theme->settings->scsspre)) {
        $scss .= $theme->settings->scsspre;
    }

    return $scss;
}

/**
 * Inject additional SCSS.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_stiftungschweiz_get_extra_scss($theme) {
    global $CFG;
    $content = '';

    // Set the page background image.
    $imageurl = $theme->setting_file_url('backgroundimage', 'backgroundimage');
    if (!empty($imageurl)) {
        $content .= '$imageurl: "' . $imageurl . '";';
        $content .= file_get_contents($CFG->dirroot .
            '/theme/stiftungschweiz/scss/stiftungschweiz/body-background.scss');
    }

    if (!empty($theme->settings->navbardark)) {
        $content .= file_get_contents($CFG->dirroot .
            '/theme/stiftungschweiz/scss/stiftungschweiz/navbar-dark.scss');
    } else {
        $content .= file_get_contents($CFG->dirroot .
            '/theme/stiftungschweiz/scss/stiftungschweiz/navbar-light.scss');
    }
    if (!empty($theme->settings->scss)) {
        $content .= $theme->settings->scss;
    }
    return $content;
}

/**
 * Get compiled css.
 *
 * @return string compiled css
 */
function theme_stiftungschweiz_get_precompiled_css() {
    global $CFG;
    return file_get_contents($CFG->dirroot . '/theme/stiftungschweiz/style/moodle.css');
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_stiftungschweiz_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    if ($context->contextlevel == CONTEXT_SYSTEM && ($filearea === 'backgroundimage')) {
        $theme = theme_config::load('stiftungschweiz');
        // By default, theme files must be cache-able by both browsers and proxies.
        if (!array_key_exists('cacheability', $options)) {
            $options['cacheability'] = 'public';
        }
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    } else {
        send_file_not_found();
    }
}
