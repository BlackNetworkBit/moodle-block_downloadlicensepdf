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
 * API functions implementation for block_downloadlicensepdf.
 *
 * @package   block_downloadlicensepdf
 * @copyright 2016 Vincent Schneider
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
 //id (AI)(int 20l), file_id (int 20l), course_id (int 20l))
function block_downloadlicensepdf_get_records($courseid) {
    global $DB; 
    return $DB->get_records('mdl_block_downloadlicensepdf', array('course_id' => $courseid));
}
function block_downloadlicensepdf_add_records($dataobjects) {
    global $DB;
    $insertdata=array();
    foreach ($dataobjects as $data){
        $newd = new stdClass();
        $newd->file_id = $data['file_id'];
        $newd->course_id = $data['course_id'];
        $insertdata[]=$newd;
    }
    return $DB->insert_records('mdl_block_downloadlicensepdf', $insertdata);
}