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
 * downloadlicensepdf block caps.
 *
 * @package    block_downloadlicensepdf
 * @copyright  Daniel Neis <danielneis@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class block_downloadlicensepdf extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_downloadlicensepdf');
    }
    public function get_content() {
        global $CFG, $OUTPUT, $COURSE;
        if ($this->content !== null) {
            return $this->content;
        }
        $context = context_course::instance($COURSE->id);
        if (!has_capability('moodle/course:manageactivities', $context)) {
            return;
        }
        require_once($CFG->dirroot . '/blocks/downloadlicensepdf/locallib.php');
        $checkedfiles = block_downloadlicensepdf_get_records($COURSE->id);
        $this->content = new stdClass;
        $fs = get_file_storage();
        $mod = get_fast_modinfo($COURSE);
        $sections = $mod->get_sections();
        $this->content->text = html_writer::start_tag('form', array(
                               'action' => '/blocks/downloadlicensepdf/post.php',
                               'method' => 'post'));
        $this->content->text .= html_writer::start_tag('strong');
        $this->content->text .= get_string('form_title', 'block_downloadlicensepdf');
        $this->content->text .= html_writer::end_tag('strong');
        foreach ($sections as $sectionn => $cmids) {
            foreach ($cmids as $cmid) {
                $cminfo = $mod->get_cm($cmid);
                $modn = $cminfo->modname;
                $section = $mod->get_section_info($sectionn);
                $secdir = sprintf("%02f", $sectionn) . ". " . clean_filename($section->name);
                if ($cminfo->uservisible) {
                    $cm = $cminfo->get_course_module_record(true);
                    $files = $fs->get_area_files($cminfo->context->id,
                                        'mod_'. $modn,
                                        'content',
                                        false,
                                        'itemid, filepath, filename',
                                        false);
                    $dir = $secdir;
                    if ($modn != 'resource') {
                        $dir .= '/' . clean_filename($cminfo->get_formatted_name());
                    }
                    foreach ($files as $pathha => $file) {
                        $rawfilename = $file->get_filename();
                        if ($file->get_mimetype() == 'application/pdf' || substr(strrchr($rawfilename, '.'), 1) == 'pdf') {
                            $filename = substr($rawfilename, 0, strrpos($rawfilename, '.'));
                            $this->content->text .= html_writer::start_tag('div');
                            $this->content->text .= html_writer::start_tag('input', array(
                                                    'type' => 'hidden',
                                                    'name' => 'all_ids[]',
                                                    'value' => $file->get_id()));
                            $isselected = false;
                            foreach ($checkedfiles as $chk) {
                                if ($file->get_id() == $chk->file_id) {
                                    $isselected = true;
                                }
                            }
                            $checkbox = '<input type="checkbox" name="file_ids[]" value="' . $file->get_id() . '" ';
                            $checkbox .= ($isselected ? 'checked' : '') . '>'; // Not working with html_writer.
                            $this->content->text .= $checkbox;
                            $this->content->text .= $filename . ' (PDF)' .  html_writer::end_tag('div');
                        }
                    }
                }
            }
        }
        $this->content->text .= html_writer::empty_tag('input', array(
                                'type' => 'hidden',
                                'name' => 'courseid',
                                'value' => $COURSE->id));
        $this->content->text .= html_writer::empty_tag('input', array(
                                'type' => 'submit',
                                'name' => 'send',
                                'value' => get_string('save', 'block_downloadlicensepdf')));
        $this->content->text .= html_writer::end_tag('form');
        return $this->content;
    }
    public function applicable_formats() {
        return array('course-view' => true);
    }
    public function has_config() {
        return true;
    }
    public function cron() {
        mtrace( "Hey, my cron script is running" );
        return true;
    }
}