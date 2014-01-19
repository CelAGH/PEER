<div>
    <fieldset>
        <form action="" id="pd_form">
            <label for="pd_title">Title*:</label>
            <input class="required" size="40" name="pd_title" type="text" id="pd_title"/>
            <label for="pd_location">Where:</label>
            <input size="40" name="pd_location" type="text" id="pd_location"/>
            <label for="pd_description">Description:</label>
            <textarea name="pd_description" type="text" id="pd_description" cols="70"></textarea>
            <p>Select dates:</p>
            <table style="margin: 3px 0px;">
                <tr><th></th><th>Selected dates:</th></tr>
                <tr><td style="width: 300px;"><div id="multiple-date-picker"></div></td><td><div id="selected-dates"></div></td></tr>
            </table>            
            <input type="submit" value="Submit" name="pd_form_submit" id="pd_form_submit"/>
            <input type="hidden" name="action_name" value="create_poll"/>
        </form>    
    </fieldset>

</div>
