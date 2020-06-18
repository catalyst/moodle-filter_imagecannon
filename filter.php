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
 * Image Cannon filter
 *
 * @package    filter
 * @subpackage imagecannon
 * @copyright  Brendan Heywood <brendan@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

use filter_imageopt\image;
use filter_imageopt\local;

/**
 * Image Cannon filter
 *
 * @package    filter
 * @subpackage imagecannon
 */
class filter_imagecannon extends moodle_text_filter {

    const REGEXP_IMGSRC = '/<img\s[^\>]*(src=["|\']((?:.*)(pluginfile.php(?:.*)))["|\'])(?:.*)>/isU';

    /**
     * @var stdClass - filter config
     */
    private $config;

    /**
     * Constructor
     */
    public function __construct(context $context, array $localconfig) {
        global $CFG;
        $this->config = get_config('filter_imagecannon');
        if (!isset($this->config->minduplicates)) {
            $this->config->minduplicates = 10;
        }

        parent::__construct($context, $localconfig);
    }

    /**
     * Given a plugin url find its canonical verion
     *
     * This will be cached.
     * @param string $path
     * @return false|string canonical url
     */
    private function get_canonical_url(string $path) {
        global $DB;

        $parts = explode('/', $path);
        $component = $parts[2];
        $filearea = $parts[3];

        if ($component != 'mod_label'
            && $component != '') {
            return false;
        }

        // Reach into imageopt filter to find the original stored_file.
        if (!$origfile = local::get_img_file($path)) {
            return false;
        }

        // How many copies of this file are there in the File API?
        $hash = $origfile->get_contenthash();
        $count = $DB->count_records('files', ['contenthash' => $hash]);

        // TODO turn into setting.
        if ($count < $this->config->minduplicates) {
            return false;
        }

        $path = '/' . context_system::instance()->id . '/filter_imagecannon/public/1/' . $hash;
        $canonurl = moodle_url::make_file_url('/pluginfile.php', $path);

        $fs = get_file_storage();

        if (!$canonfile = $fs->get_file_by_hash(sha1($path))) {
            $new = new stdClass;
            $new->contextid = context_system::instance()->id;
            $new->component = 'filter_imagecannon';
            $new->filearea = 'public';
            $new->filepath = '/';
            $new->filename = $hash;
            $new->itemid = 1;
            $canonfile = $fs->create_file_from_storedfile($new, $origfile);
        }
        return $canonurl;
    }

    /**
     * Process a single image tag regex match
     *
     * @param array $match
     * @return string html
     */
    private function process_img_tag(array $match) {
        global $CFG;

        $html = $match[0];
        $url = $match[2];
        $path = $match[3];

        // Don't process images that aren't in this site or don't have a relative path.
        if (stripos($url, $CFG->wwwroot) === false && substr($url, 0, 1) != '/') {
            return $html;
        }

        // TODO filearea based filter.

        $canonurl = $this->get_canonical_url($path);

        if ($canonurl === false) {
            return $html;
        }

        $html = str_replace($url, $canonurl, $html);
        return $html;
    }

    /**
     * Filter content.
     *
     * @param string $text HTML to be processed.
     * @param array $options
     * @return string String containing processed HTML.
     */
    public function filter($text, array $options = array()) {
        global $CFG, $DB;

        if (strpos($text, 'pluginfile.php') === false) {
            return $text;
        }

        $filtered = $text; // We need to return the original value if regex fails!

        $search = self::REGEXP_IMGSRC;
        $filtered = preg_replace_callback($search, 'self::process_img_tag', $filtered);

        if (empty($filtered)) {
            return $text;
        }
        return $filtered;
    }
}

