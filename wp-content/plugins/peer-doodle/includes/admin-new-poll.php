<div>
    <fieldset>
        <form action="" id="pd_form">
            <label for="pd_title">Title*:</label>
            <input class="required" size="40" name="pd_title" type="text" id="pd_title"/>
            <label for="pd_location">Where:</label>
            <input size="40" name="pd_location" type="text" id="pd_location"/>
            <label for="pd_description">Description:</label>
            <textarea name="pd_description" type="text" id="pd_description" cols="70"></textarea>
            <p>Select datesd:</p>
            <table style="margin: 3px 0px;">
                <tr><th></th><th>Selected dates:</th></tr>
                <tr><td style="width: 300px;"><div id="multiple-date-picker"></div></td><td><div id="selected-dates"></div></td></tr>
		  <tr>
			
		  <table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td><b>Time</td>
		<td>Day 1</td>
		<td>Day 2</td>
		<td>Day 3</td>
		<td>Day 4</td>
		<td>Day 5</td>
		<td>Day 6 </td>
		<td>Day 7</td>
		<td>Day 8</td>
	</tr>
	<tr>
		<td>hh:mm</td>
		<td><input size="5" name="pd_d1" type="time" id="pd_d1"/></td>
		<td><input size="5" name="pd_d2" type="text" id="pd_d2"/></td>
		<td><input size="5" name="pd_d3" type="text" id="pd_d3"/></td>
		<td><input size="5" name="pd_d4" type="text" id="pd_d4"/></td>
		<td><input size="5" name="pd_d5" type="text" id="pd_d5"/></td>
		<td><input size="5" name="pd_d6" type="text" id="pd_d6"/></td>
		<td><input size="5" name="pd_d7" type="text" id="pd_d7"/></td>
		<td><input size="5" name="pd_d8" type="text" id="pd_d8"/></td>
	</tr>
	<tr>
		<td>hh:mm</td>
		<td><input size="5" name="pd_d11" type="time" id="pd_d11"/></td>
		<td><input size="5" name="pd_d22" type="text" id="pd_d22"/></td>
		<td><input size="5" name="pd_d33" type="text" id="pd_d33"/></td>
		<td><input size="5" name="pd_d44" type="text" id="pd_d44"/></td>
		<td><input size="5" name="pd_d55" type="text" id="pd_d55"/></td>
		<td><input size="5" name="pd_d66" type="text" id="pd_d66"/></td>
		<td><input size="5" name="pd_d77" type="text" id="pd_d77"/></td>
		<td><input size="5" name="pd_d88" type="text" id="pd_d88"/></td>
	</tr>

</table>
 	
		  </tr>
            </table>  
	     
          

            <input type="submit" value="Submit" name="pd_form_submit" id="pd_form_submit"/>
            <input type="hidden" name="action_name" value="create_poll"/>
        </form>    
    </fieldset>

</div>
