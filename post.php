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
require_once('./../../config.php');
$courseid = required_param('courseid', PARAM_INT);
require_login($courseid);
$context = context_course::instance($courseid);
if (!has_capability('moodle/course:manageactivities', $context)) {
    die(get_string('notrainer', "block_downloadlicensepdf"));
}
global $DB, $CFG;
$PAGE->set_url($CFG->wwwroot . '/blocks/downloadlicensepdf/post.php');
require_once($CFG->dirroot . '/blocks/downloadlicensepdf/locallib.php');
$fileids = $_POST["file_ids"]; // Get all checked Checkboxes.
$allfileids = $_POST["all_ids"]; // Necessary to get all Checkbox elements to delete unwanted db entry's.
if (is_array($allfileids)) {
    $uncheckeditems=array();
    $checkeditems=array();
    foreach ($fileids as $fileid) {
        $checkeditems[] = intval($fileid); // Load all Post data into an int array.
    }
    foreach ($allfileids as $afi) {
    	  $afi = intval($afi);  // Convert the input to int to prevent further problems.
    	  if (!in_array($afi, $checkeditems)){
    	      $uncheckeditems[]=$afi; // All items which are not checked are unchecked.
    	  }
    }
    if (count($checkeditems)>0){
        block_downloadlicensepdf_add_records($checkeditems,$courseid);
    }
    if (count($uncheckeditems)>0){
       block_downloadlicensepdf_delete_records($uncheckeditems,$courseid);
    }
}
$uri=new moodle_url('/course/view.php', array('id' => $courseid));
redirect($uri);
// Db entry's are => id, file_id, course_id