<!--Begin CTCT Sign-Up Form-->
<!-- EFD 1.0.0 [Thu Nov 17 15:49:44 EST 2016] -->
<!-- <link rel='stylesheet' type='text/css' href='https://static.ctctcdn.com/h/contacts-embedded-signup-assets/1.0.2/css/signup-form.css'> -->
<?php
/**
 * Include Sign-up form
 * show Constant Contact sign-up form
 * @var [boolean]
 */
$includeSignup = get_post_meta( get_the_ID(), 'kits_signup', true );
$signupForm = "";

if ( $includeSignup ) {
	$signupForm = "<!-- Success message: DO NOT DELETE -->";
	$signupForm .= "<span id='success_message' style='display:none;'>";
    $signupForm .= "<div style='text-align:center;'>Thanks for signing up!</div>";
	$signupForm .= "</span>";

	$signupForm .= "<form style='display:none;' data-id='embedded_signup:form' class='bbg__form__sign-up' name='embedded_signup' method='POST' action='https://visitor2.constantcontact.com/api/signup'>";
	$signupForm .= '<hr style="margin-top:2rem;">';
	$signupForm .= '<h3>Want to stay informed? <span id="btnClose" class="bbg__button--close"></span></h3>';
	$signupForm .= '<p>Sign up to receive our press releases, newsletters, and event notices.</p>';

		$signupForm .= "<!-- The following code must be included to ensure your sign-up form works properly. -->";

		$signupForm .= "<input data-id='ca:input' type='hidden' name='ca' value='98c4cff0-5ff9-41f4-b686-77c1a956c79a'>";
		$signupForm .= "<input data-id='source:input' type='hidden' name='source' value='EFD'>";
		$signupForm .= "<input data-id='url:input' type='hidden' name='url' value=''>";

		$signupForm .= "<p data-id='Email Address:p'>";
			$signupForm .= "<label for='input-type-text' data-id='Email Address:label' data-name='email' class='bbg__form--required'>Email Address <span class='usa-additional_text usa-sr-only'>Required</span></label>";
			$signupForm .= "<input data-id='Email Address:input' type='text' name='email' maxlength='80'>";
		$signupForm .= "</p>";

		$signupForm .= "<p><span data-id='First Name:p'>";
			$signupForm .= "<label for='input-type-text' data-id='First Name:label' data-name='first_name' class='bbg__form--required'>First Name <span class='usa-additional_text usa-sr-only'>Required</span></label>";
			$signupForm .= "<input data-id='First Name:input' type='text' name='first_name' value='' maxlength='50'>";
		$signupForm .= "</span>";
		$signupForm .= "<span data-id='Last Name:p'>";
			$signupForm .= "<label for='input-type-text' data-id='Last Name:label' data-name='last_name' class='bbg__form--required'>Last Name <span class='usa-additional_text usa-sr-only'>Required</span></label>";
			$signupForm .= "<input data-id='Last Name:input' type='text' name='last_name' value='' maxlength='50'>";
		$signupForm .= "</span>";
		$signupForm .= "</p>";

		$signupForm .= "<p data-id='Company:p'>";
			$signupForm .= "<label for='input-type-text' data-id='Company:label' data-name='company'>Company</label>";
			$signupForm .= "<input data-id='Company:input' type='text' name='company' value='' maxlength='50'>";
		$signupForm .= "</p>";

		$signupForm .= "<p data-id='Phone Number:p'>";
			$signupForm .= "<label for='input-type-text' data-id='Phone Number:label' data-name='phone'>Phone Number</label>";
			$signupForm .= "<input data-id='Phone Number:input' type='text' name='phone' value='' maxlength='50'>";
		$signupForm .= "</p>";

		$signupForm .= "<p data-id='Job Title:p'>";
			$signupForm .= "<label for='input-type-text' data-id='Job Title:label' data-name='job_title'>Job Title</label>";
			$signupForm .= "<input data-id='Job Title:input' type='text' name='job_title' value='' maxlength='50'>";
		$signupForm .= "</p>";

		$signupForm .= "<p data-id='Twitter:p'>";
			$signupForm .= "<label for='input-type-text' data-id='Twitter:label' data-name='cf_text_value--0' class=''>Twitter</label>";
			$signupForm .= "<input data-id='Twitter_value:input' type='text' name='cf_text_value--0' value=''>";
		$signupForm .= "</p>";

		$signupForm .= "<input data-id='Twitter_name:input' type='hidden' name='cf_text_name--0' value='custom_field_1'>";
		$signupForm .= "<input data-id='Twitter_label:input' type='hidden' name='cf_text_label--0' value='Twitter'>";

		$signupForm .= "<fieldset class='usa-fieldset-inputs bbg_hidden-fields'>";
			$signupForm .= "<legend data-id='Lists:label' data-name='list' class='ctct-form-required'>Email Lists</legend>";

			$signupForm .= "<ul class='usa-checklist' data-id='Lists:p'>";
				$signupForm .= "<li>";
					$signupForm .= "<input data-id='Lists:input' type='checkbox' name='list_0' value='6' checked>";
					$signupForm .= "<span data-id='Lists:span'>BBG Events</span>";
				$signupForm .= "</li>";
				/*$signupForm .= "<li>";
					$signupForm .= "<input data-id='Lists:input' type='checkbox' name='list_1' value='5' checked>";
					$signupForm .= "<span data-id='Lists:span'>BBG Media Highlights</span>";
				$signupForm .= "</li>";*/
				$signupForm .= "<li>";
					$signupForm .= "<input data-id='Lists:input' type='checkbox' name='list_2' value='7' checked>";
					$signupForm .= "<span data-id='Lists:span'>BBG Newsletter</span>";
				$signupForm .= "</li>";
				$signupForm .= "<li>";
					$signupForm .= "<input data-id='Lists:input' type='checkbox' name='list_3' value='8' checked>";
					$signupForm .= "<span data-id='Lists:span'>BBG Press Releases</span>";
				$signupForm .= "</li>";
				$signupForm .= "<li>";
					$signupForm .= "<input data-id='Lists:input' type='checkbox' name='list_4' value='1456319600' checked>";
					$signupForm .= "<span data-id='Lists:span'>CEO Blog</span>";
				$signupForm .= "</li>";
			$signupForm .= "</ul>";
		$signupForm .= "</fieldset>";

		$signupForm .= "<button type='submit' class='usa-button bbg__kits__inquiries__button--half' data-enabled='enabled'>Sign Up</button>";

		$signupForm .= "<div>";
		$signupForm .= "<p class='ctct-form-footer'>By submitting this form, you are granting the Broadcasting Board of Governors permission to email you. You may unsubscribe via the link found at the bottom of every email. (See our <a href='http://www.constantcontact.com/legal/privacy-statement' target='_blank'>Email Privacy Policy</a> for details.) Emails are serviced by Constant Contact.</p>";
		$signupForm .= "</div>";
		$signupForm .= "</form>";

		$signupForm .= "<!-- Error messages: DO NOT DELETE -->";
		$signupForm .= "<script type='text/javascript'>";
		$signupForm .= "var localizedErrMap = {};";
		$signupForm .= "localizedErrMap['required'] = 		'This field is required.';";
		$signupForm .= "localizedErrMap['ca'] = 			'An unexpected error occurred while attempting to send email.';";
		$signupForm .= "localizedErrMap['email'] = 			'Please enter your email address in name@email.com format.';";
		$signupForm .= "localizedErrMap['birthday'] = 		'Please enter birthday in MM/DD format.';";
		$signupForm .= "localizedErrMap['anniversary'] = 	'Please enter anniversary in MM/DD/YYYY format.';";
		$signupForm .= "localizedErrMap['custom_date'] = 	'Please enter this date in MM/DD/YYYY format.';";
		$signupForm .= "localizedErrMap['list'] = 			'Please select at least one email list.';";
		$signupForm .= "localizedErrMap['generic'] = 		'This field is invalid.';";
		$signupForm .= "localizedErrMap['shared'] = 		'Sorry, we could not complete your sign-up. Please contact us to resolve this.';";
		$signupForm .= "localizedErrMap['state_mismatch'] = 'Mismatched State/Province and Country.';";
		$signupForm .= "localizedErrMap['state_province'] = 'Select a state/province';";
		$signupForm .= "localizedErrMap['selectcountry'] = 	'Select a country';";
		$signupForm .= "var postURL = 'https://visitor2.constantcontact.com/api/signup';";
		$signupForm .= "</script>";
		$signupForm .= "<script type='text/javascript' src='https://static.ctctcdn.com/h/contacts-embedded-signup-assets/1.0.2/js/signup-form.js'>";
		$signupForm .= "</script>";
		$signupForm .= "<!--End CTCT Sign-Up Form-->";

} ?>