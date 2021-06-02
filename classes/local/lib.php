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
 * Local lib
 *
 * @package    theme_stiftungschweiz
 * @copyright  Copyright (c) 2021 Citricity Ltd, Copyright (c) 2015 Blackboard Inc. (http://www.blackboard.com)
 * @author     Guy Thomas
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace theme_stiftungschweiz\local;

defined('MOODLE_INTERNAL') || die();

use stored_file;
use moodle_url;

/**
 * Local lib
 *
 * @package    theme_stiftungschweiz
 * @copyright  2021 Citricity Ltd
 * @author     Guy Thomas
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class lib {

    /**
     * Get the first image in the course file area.
     *
     * @param int $courseid
     * @return stored_file|null
     * @throws coding_exception
     */
    public static function get_course_firstimage(int $courseid): ?stored_file {
        $fs      = get_file_storage();
        $context = \context_course::instance($courseid);

        // Try format header image first.
        $files = $fs->get_area_files($context->id, 'format_visualsections', 'headerimage', false, 'filename', false);
        if (count($files) > 0) {
            foreach ($files as $file) {
                if ($file->is_valid_image()) {
                    return $file;
                }
            }
        }

        // Try course image as a fallback.
        $files   = $fs->get_area_files($context->id, 'course', 'overviewfiles', false, 'filename', false);
        if (count($files) > 0) {
            foreach ($files as $file) {
                if ($file->is_valid_image()) {
                    return $file;
                }
            }
        }

        return null;
    }

    /**
     * Get cover image url for course.
     *
     * @param int $courseid
     * @return null|moodle_url
     */
    public static function course_coverimage_url(int $courseid): ?moodle_url {
        $file = self::get_course_firstimage($courseid);
        if (!$file) {
            return null;
        }
        return moodle_url::make_pluginfile_url(
            $file->get_contextid(),
            $file->get_component(),
            $file->get_filearea(),
            $file->get_itemid(),
            $file->get_filepath(),
            $file->get_filename()
        );
    }

    /**
     * Adds the course cover image styling.
     *
     * @param int $courseid
     * @return string The html style tag and css.
     */
    public static function course_coverimage_style(int $courseid): string {
        $coverurl = self::course_coverimage_url($courseid);
        $style = '';
        if ($coverurl) {
            $style = "<style>
                body.pagelayout-course.course-$courseid #page-header .card-body {
                    background-image: url($coverurl);
                }
                </style>";
        }
        return $style;
    }
}
