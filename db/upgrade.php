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
defined('MOODLE_INTERNAL') || die();
if ($oldversion < 2016011300) {
    // Define table block_downloadlicensepdf to be created.
    $table = new xmldb_table('block_downloadlicensepdf');
    // Adding fields to table block_downloadlicensepdf.
    $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
    $table->add_field('file_id', XMLDB_TYPE_INTEGER, '20', null, null, null, null);
    $table->add_field('course_id', XMLDB_TYPE_INTEGER, '20', null, null, null, null);
    // Adding keys to table block_downloadlicensepdf.
    $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
    // Conditionally launch create table for block_downloadlicensepdf.
    if (!$dbman->table_exists($table)) {
        $dbman->create_table($table);
    }
    // Downloadlicensepdf savepoint reached.
    upgrade_block_savepoint(true, 2016011300, 'downloadlicensepdf');
}