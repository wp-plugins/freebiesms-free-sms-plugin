<?php
/*
Plugin Name: FreebieSMS
Plugin URI: http://freebiesms.co.uk/
Description: Allow your visitors to send free SMS to any mobile number.
*/

/*  Copyright 2010  MARTIN FITZPATRICK  (email : martin.fitzpatrick@mutube.com) */

function freebiesms(){
	global $freebiesms;

	$freebiesms->display();
	
	
}

class freebiesms {

	function display(){
		
		// Each widget can store its own options. We keep strings here.
		$options = get_option('widget_freebiesms');
?>

<style> .SubTableHeader { background-color: #ffcc33; font-family:Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 15; }
    .TableContents { background-color: #F2F2F2; height: 24px; font-family:Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 15; padding-left: 5px; }
    .FormElement { border: silver 1px solid; background-color: #F1F7F7; font-family:Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 15; }
    </style>

<form action=http://www.freebiesms.co.uk/SMSForm.aspx method=post name=SMSForm onsubmit='return validate()' >
<input type="hidden" name="AffiliateID" value="<?php echo $options['affiliateid'] ?>"> <input type="hidden" name="ReturnURL" value="http://<?php echo $_SERVER['SERVER_NAME'] ?><?php echo $_SERVER['REQUEST_URI'] ?>">
<table width="300" style="BORDER-RIGHT:black 1px solid; BORDER-TOP:black 1px solid; BORDER-LEFT:black 1px solid; BORDER-BOTTOM:black 1px solid">
    <tr>
        <td class="SubTableHeader" colspan="2">
            <a href="http://www.freebiesms.co.uk">
                Send an SMS Text Message
            </a>
        </td>
    </tr>
    <tr>
        <td class="TableContents" valign="top" style="WIDTH: 52px">
            To Mobile
            :
        </td>
        <td>
            <input class="FormElement" name="ToMobile" value="0044">
        </td>
    </tr>
    <tr>
        <td class="TableContents" valign="top" style="WIDTH: 52px">
            To Name
            :
        </td>
        <td>
            <input class="FormElement" name="ToName" maxlength="10">
        </td>
    </tr>
    <tr>
        <td class="TableContents" valign="top" style="WIDTH: 52px">
            From Mobile
            :
        </td>
        <td>
            <input class="FormElement" name="FromMobile" value="0044">
        </td>
    </tr>
    <tr>
        <td class="TableContents" valign="top" style="WIDTH: 52px">
            From Name
            :
        </td>
        <td>
            <input class="FormElement" name="FromName" maxlength="10">
        </td>
    </tr>
    <tr>
        <td class="TableContents" valign="top" colspan="2">
            Message
            :
            <br>
            <textarea class="FormElement" onkeydown="limitLength(this,140)" name="Message" rows="8" cols="27" name="Message"></textarea>
        </td>
    </tr>
    <tr>
        <td style="WIDTH: 52px">
            <input name="Migrated_AffiliateForm:Submit1" type="submit" id="Migrated_AffiliateForm_Submit1" value="Send" onclick="validate" />
        </td>
        <td align="right">
            <a ONCLICK="alert('Please see www.freebiesms.co.uk for terms and conditions')">T&amp;C</a>
        </td>
    </tr>
</table>
</form>
<script language="javascript">
    
    function limitLength(textarea,maxlimit)
    {
        if (textarea.value.length > maxlimit) 
        textarea.value = textarea.value.substring(0, maxlimit);
    }
    
    function validate()
    {
        var Prefix = "0044"
        
        var ToMobile = window.document.SMSForm.ToMobile.value;
        var FromMobile = window.document.SMSForm.FromMobile.value;
        var ToName = window.document.SMSForm.ToName.value;
        var FromName = window.document.SMSForm.FromName.value;
        var Message = window.document.SMSForm.Message.value;
        
        // Ensure To-Mobile number is international format & from correct country
        if (ToMobile.indexOf(Prefix)!=0)
        {
            alert(ToMobile + " is not a valid Phone number.");
            return false;
        }
        // Ensure From-Mobile number is international format
        if (FromMobile.indexOf("00")!=0)
        {
            alert(FromMobile + " is not a valid Phone number.");
            return false;
        }       
        
        // Ensure names are filled in
        if (ToName.length<3 || FromName.length<3)
        {
            alert("Your name must be between 3 and 11 letters long.");          
            return false;
        }       
        
        // To and from cannot be the same.
        if (FromMobile == ToMobile)
        {
            alert("You cannot send a message to and from the same person");
            return false;
        }
        
        if (Message.length==0)
        {
            alert("Your message is blank. Please type a message in the box provided");
            return false;
        }
        
        return true;
    }
    
</script>


<?php


	}

		// Admin panel configuration
		function settings_control() {

			// Get our options and see if we're handling a form submission.
			$options = get_option('widget_freebiesms');

			if ( $_POST['freebiesms-submit']=='options' )
			{

				// Remember to sanitize and format use input appropriately.
				$options['affiliateid'] = strip_tags(stripslashes($_POST['freebiesms-affiliateid']));

				update_option('widget_freebiesms', $options);
			}

			// Be sure you format your options to be valid HTML attributes.
			$affiliateid = htmlspecialchars($options['affiliateid'], ENT_QUOTES);

			// Here is our little form segment. Notice that we don't need a
			// complete form. This will be embedded into the existing form.

		?>
		<div class="wrap">
		<h2>FreebieSMS Options</h2>
		<div style="width:550px;margin-top:20px;">
		<form action="" method="post">
		<table>
		<tr><td><label for="freebiesms-affiliateid">AffiliateID:</label></td><td><input style="width: 200px;" id="freebiesms-affiliateid" name="freebiesms-affiliateid" type="text" value="<?php echo $affiliateid;?>" title="Enter your FreebieSMS affiliate ID."/></td></tr>
        </table>
		<input type="hidden" id="freebiesms-submit" name="freebiesms-submit" value="options" />
		
         <p class="submit"><input type="submit" value="Save changes &raquo;"></p>
         </form></div>
        </div>				
                <?php
		}

		// This is the function that outputs freebiesms writing-box
		function widget($args) {

			// $args is an array of strings that help widgets to conform to	
			// the active theme: before_widget, before_title, after_widget,
			// and after_title are the array keys. Default tags: li and h2.
			extract($args);
			$options = get_option('widget_freebiesms');
            echo $before_widget . $before_title . $options['title'] . $after_title;
            $this->display();
            echo $after_widget;
		}


	    function widget_control() {
			
		// Get our options and see if we're handling a form submission.
			$options = get_option('widget_freebiesms');
			
			if ( $_POST['freebiesms-submit']=='options' )
			{

				// Remember to sanitize and format use input appropriately.
				$options['title'] = strip_tags(stripslashes($_POST['freebiesms-title']));
				update_option('widget_freebiesms', $options);

			}
				
			?>
		<table>
		<tr><td><label for="freebiesms-title">Title:</label></td><td><input style="width: 200px;" id="freebiesms-title" name="freebiesms-title" type="text" value="<?php echo $options['title'];?>" /></td></tr>
		</table>
		<input type="hidden" id="freebiesms-submit" name="freebiesms-submit" value="options" />
		<?php
		}

	function add_pages(){
         add_options_page("FreebieSMS Options", "FreebieSMS", 10, "freebie-sms", array(&$this,'settings_control'));
	}
		

	// Put functions into one big function we'll call at the plugins_loaded
	// action. This ensures that all required plugin functions are defined.
	function init() {

			$options = get_option('widget_freebiesms');
			if ( !is_array($options) )
			{
				
				$options = array(
					'affiliateid'=>'8015',
				);
				
				update_option('widget_freebiesms', $options);

			}

		
			if (function_exists('wp_register_sidebar_widget') )
			{   //Do Widget-specific code
				wp_register_sidebar_widget('freebiesms', 'FreebieSMS', 'freebiesms_widget');
				wp_register_widget_control('freebiesms', 'FreebieSMS', 'freebiesms_widget_control', 300, 100);
			}
			
			add_action('admin_menu', array(&$this,'add_pages'));
	
	}

}



$freebiesms = new freebiesms();


/*	
	SIDEBAR MODULES COMPATIBILITY KLUDGE 
	These functions are external to the class above to allow compatibility with SBM
	which does not allow calls to be passed to a class member.
	These functions are dummy passthru's for the real functions above
*/

	function freebiesms_widget($args){
		global $freebiesms;
		$freebiesms->widget($args);
	}

	function freebiesms_widget_control(){
		global $freebiesms;
		$freebiesms->widget_control();
	}

/*
	END DUMMY KLUDGE
*/


// Run our code later in case this loads prior to any required plugins.
add_action('plugins_loaded', array(&$freebiesms,'init'));

?>
