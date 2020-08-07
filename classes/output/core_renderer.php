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

namespace theme_stiftungschweiz\output;

defined('MOODLE_INTERNAL') || die;

/**
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * @package    theme_stiftungschweiz
 * @copyright  2020 Guy thomas
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class core_renderer extends \core_renderer {

    /**
     * We hard code this for the Stiftungschweiz theme.
     */
    public function get_compact_logo_url($maxwidth = 100, $maxheight = 100) {
        return $this->image_url('logo', 'theme');
    }

    /**
     * Returns the CSS classes to apply to the body tag.
     *
     * @since Moodle 2.5.1 2.6
     * @param array $additionalclasses Any additional classes to apply.
     * @return string
     */
    public function body_css_classes(array $additionalclasses = array()) {
        global $PAGE;
        if ($PAGE->context->contextlevel === CONTEXT_MODULE) {
            $additionalclasses[] = 'context-level-module'; // This is so we can use the course header for modules.
        }
        $additionalclasses[] = 'theme_stiftungschweiz';
        return parent::body_css_classes($additionalclasses);
    }

    /**
     * Return the 'back' link that normally appears in the footer.
     *
     * @return string HTML fragment.
     */
    public function home_link() {
        global $CFG, $SITE;

        if ($this->page->pagetype == 'site-index') {
            // Special case for site home page - please do not remove
            return '<div class="sitelink">' .
                '<a title="Moodle" href="http://moodle.org/">' .
                '<img src="' . $this->image_url('moodle-logo', 'theme_stiftungschweiz') . '" alt="'.get_string('moodlelogo').'" /></a></div>';

        } else if (!empty($CFG->target_release) && $CFG->target_release != $CFG->release) {
            // Special case for during install/upgrade.
            return '<div class="sitelink">'.
                '<a title="Moodle" href="http://docs.moodle.org/en/Administrator_documentation" onclick="this.target=\'_blank\'">' .
                '<img src="' . $this->image_url('moodle-logo', 'theme_stiftungschweiz') . '" alt="'.get_string('moodlelogo').'" /></a></div>';

        } else if ($this->page->course->id == $SITE->id || strpos($this->page->pagetype, 'course-view') === 0) {
            return '<div class="homelink"><a href="' . $CFG->wwwroot . '/">' .
                get_string('home') . '</a></div>';

        } else {
            return '<div class="homelink"><a href="' . $CFG->wwwroot . '/course/view.php?id=' . $this->page->course->id . '">' .
                format_string($this->page->course->shortname, true, array('context' => $this->page->context)) . '</a></div>';
        }
    }
}