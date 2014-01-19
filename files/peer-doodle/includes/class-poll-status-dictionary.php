<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Doodle_Poll_Status
{
    const NOTFINISHED = 0; //can't be insterted into post
    const FINISHED = 1; //can be inserted into post
    const INUSE =2; //somebody voted
    const CLOSED = 3; //poll closed
    const DELETED = 4; //poll is deleted
    // etc.
}
?>
