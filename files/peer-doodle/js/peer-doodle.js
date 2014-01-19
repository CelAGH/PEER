/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(document).ready(function(f){        
    
    var dates = f('#multiple-date-picker').multiDatesPicker({
        maxPicks: 8,
        dateFormat: "DD, d MM, yy",
        onSelect: function ()
        {
            f("#selected-dates-errs").html("");
            var dates = f('#multiple-date-picker').multiDatesPicker('getDates');
                
            var html = '';
            f.each(dates, function (i, val)
            {
                html += '<input readonly size="36" type="text" name="dates[]" value="' + val + '"/>';
                var ts = Date.parse(val);
                html += '<input size="36" type="hidden" name="dates_timestamp[]" value="' + ts + '"/>';
                html += "<br>";
            });
            html += ''
            f("#selected-dates").html(html);
        }
    });
   
    f('#pd_form').validate({
        rules: {
            pd_title: {
                required: true,
                minlength: 2
            }
        },
        messages: {
            pd_title: {
                required: "Title field is required",
                minlength: "title must have at least 3 characters"
            }
        }
    });
   
    f('#pd_form_submit').click(function() {            
        
    });
    
    
    f(document).on("click",".dpc_submit_participant",function(o){
        //o.preventDefault();        
        //var id = f(this).closest("article").attr("id");
        //alert(id);
        //var j=f(this).closest("#email-details").find(".clonedOption").length; 
    });
    
});
