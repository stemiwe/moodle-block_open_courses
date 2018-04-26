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
 * Course list block.
 *
 * @package    block_open_courses
 * @copyright  Stefan Weber, FH Technikum Wien (webers@technikum-wien.at)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

include_once($CFG->dirroot . '/course/lib.php');
include_once($CFG->libdir . '/coursecatlib.php');

class block_open_courses extends block_list {
    function init() {
        $this->title = get_string('pluginname', 'block_open_courses');
    }

    function get_content() {
        global $CFG, $USER, $DB, $OUTPUT;

        if($this->content !== NULL) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';

        $icon = $OUTPUT->pix_icon('i/course', get_string('course'));
       
		$sortorder = 'visible DESC,sortorder ASC';
		$accesscourses = enrol_get_my_courses(NULL, $sortorder, 0, [], true); //get all courses the user can view
		$mycourses = enrol_get_my_courses(NULL, $sortorder, 0, [], false); //get all courses the user is enrolled in
		
		//collect courses the user can view but is not enrolled in
		foreach ($accesscourses as $course) {
			if(!in_array ($course, $mycourses)) {
				$opencourses[] = $course;
			}
		}		
		
		//display courses
		foreach ($opencourses as $course) {
			
				$coursecontext = context_course::instance($course->id);
				$linkcss = $course->visible ? "" : " class=\"dimmed\" ";
				$this->content->items[]="<a $linkcss title=\"" . format_string($course->shortname, true, array('context' => $coursecontext)) . "\" ".
						   "href=\"$CFG->wwwroot/course/view.php?id=$course->id\">".$icon.format_string(get_course_display_name_for_list($course)). "</a>";
			
		}
		$this->title = get_string('pluginname', 'block_open_courses');
		   
    }

}


