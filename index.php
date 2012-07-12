<?php
require_once 'class.phpmailer.php'; $mail = new PHPMailer();
$mail->IsHTML(false); // Send as plain text
$mail->IsSMTP();
#
#	Configuration begins:
#
$mail->SMTPAuth   = true;
$mail->SMTPSecure = "ssl";
$mail->Host       = "smtp.gmail.com";
$mail->Port       = 465;

$mail->Username   = "yourcompany-legal@gmail.com";
$mail->Password   = "supersecret"
$mail->Subject    = "New NDA Signature";

$config['Company Name'] = 'Your Company';
$config['Company Logo'] = 'images/logo.gif';

$config['Email To'] = 'Your email address'; // Who should receive a notification about a new NDA?
$config['Email Name'] = 'Your name';

$config['tmpdir'] = sys_get_temp_dir(); # IF THE FUNCTION DOES NOT EXIST SET THIS VALUE TO "/tmp";

#
#	Configuration ends.
#

?><!DOCTYPE html>
<html>
<head>
<title><?php echo $config['Company Name']; ?> - Non Disclosure Agreement</title>

<link rel="stylesheet" href="css/jquery.signaturepad.css">
<style type="text/css">
	.letter { width:750px; border:1px solid black; padding:10px 50px; box-shadow:1px 1px 1px black; margin:1em auto}
	label { cursor:pointer }
	label.invalid { cursor:normal; float: right; font-size: small; color: red; }

	fieldset { border:0px solid black; border-top-width:1px; margin-top:2em }
	fieldset legend { margin-left:1em; }
</style>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<!--[if lt IE 9]><script type="text/javascript" src="js/flashcanvas/flashcanvas.js"></script><![endif]-->
<script type="text/javascript" src="js/jquery.signaturepad.js"></script>
<script type="text/javascript" src="js/json2.js"></script>
<script type="text/javascript">
$(function(){ $('form').validate({errorClass:"invalid"}); } );
var _sigobj;
$(document).ready(function(){
	_sigobj = $('form').signaturePad( {
	penColour:"black", drawOnly:true,
	penWidth:1, onFormError:function(){}
} ); } );
</script>
</head>
<body>
<div class="letter">
<h1><img src="<?php echo $config['Company Logo']; ?>" alt="" style="height:2em; vertical-align:middle" /><?php echo $config['Company Name']; ?> - Non Disclosure Agreement</h1>
<?php
if ($_POST['action'] == 'Submit Document') {
	if (count($_POST['ndaok']) != count($NDA) + 2) {
		echo 'You must agree (check) every part of the NDA. Please click back on your browser and try again';
		exit;
	}
	$text = "";
	foreach ($_POST['data'] as $k => $v) {
		$text .= "$k : $v\n";
	}
	$text .= "\n".$_SERVER['HTTP_USER_AGENT']."\n".$_SERVER['REMOTE_ADDR'];

	$mail->Body	  = $text;

	$sigb64 = array_pop(explode(',', $_POST['output']));

	$pngfile = tempnam($config['tmpdir'], 'signature');
	file_put_contents($pngfile, base64_decode($sigb64), LOCK_EX);

	$mail->AddAddress($config['Email To'], $config['Email Name']);
	$mail->AddAttachment($pngfile, "Signature.png");


	if(!$mail->Send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	}
	echo "We have received your NDA Agreement.<br /><br />Thank you for your contributions towards PhantasyRPG; past, present, and future.";
	exit;
}
?>
<p class="description">
This Non-Disclosure Agreement (hereinafter called "Agreement") is entered into to provide for the confidentiality, protection and handling of Proprietary Information related to a product of <?php echo $config['Company Name']; ?> (hereinafter called "Developer") for the purpose of examination, editing, play testing, or consideration for publication (hereinafter called "Purpose"), by the party who's legal signature appears at the end of this agreement (hereinafter called "Recipient").</p>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="$(this).find('input[name=\'output\']').val(_sigobj.getSignatureImage())">
<ol class="romannumeral">
<li><p><input type="checkbox" id="ndaok0" name="ndaok[]" /><label for="ndaok0">The term "Proprietary Information" means any and all information, in any form, whether of a technical or commercial nature, relating to the Purpose which is disclosed prior or subsequent to the date of this Agreement by the disclosing party to the recipient and identified by the disclosing party at the time of disclosure as being proprietary. Information disclosed in a form other than writing shall be confirmed in writing by the disclosing party as being proprietary within thirty (30) days of disclosure.</label></p></li>
<li><p><input type="checkbox" id="ndaok1" name="ndaok[]" /><label for="ndaok1">Proprietary Information shall not include information which, at the date of signature hereof, or thereafter becomes public domain, is known to the recipient prior to being disclosed by the disclosing party, in which case the recipient will notify to the disclosing party within 7 days that the information was already known prior to disclosure, is developed independently by the recipient, or is legally obtained by the recipient at any time from other sources who are not subject to proprietary restrictions. The recipient shall have the burden of proof in establishing any of the above mentioned exceptions.</label></p></li>
<li><p><input type="checkbox" id="ndaok2" name="ndaok[]" /><label for="ndaok2">The recipient agrees to use the Proprietary Information solely for the mutual benefit of parties in furtherance of the above stated Purpose, as specifically approved by the disclosing party, and agrees not to disclose the Proprietary Information to any third party or to any of its affiliates, employees or agents except as may be required to conduct the above mentioned Purpose. Any such disclosure to third parties shall be subject to the prior written consent of the disclosing party and shall be conditioned upon obtaining in advance a non-disclosure Agreement substantially in the form of this Agreement.</label></p></li>
<li><p><input type="checkbox" id="ndaok3" name="ndaok[]" /><label for="ndaok3">The recipient agrees to retain the Proprietary Information of the disclosing party in confidence and to exercise towards it at least the same degree of care and protection that it takes to safeguard its own Proprietary Information.</label></p></li>
<li><p><input type="checkbox" id="ndaok4" name="ndaok[]" /><label for="ndaok4">The Proprietary Information of each party, or any part thereof, whether capable of being copyrighted, patented, or otherwise registered at law, or not, is for the purposes of this Agreement acknowledged by the recipient as being the sole property of the disclosing party.</label></p></li>
<li><p><input type="checkbox" id="ndaok5" name="ndaok[]" /><label for="ndaok5">Nothing in this Agreement shall be construed as granting to the recipient any rights by license or otherwise, express or implied, to or in any of the disclosing party's patents, non-patented inventions or other intellectual property. No representation or warranty is made by the disclosing party with respect to information disclosed.</label></p></li>
<li><p><input type="checkbox" id="ndaok6" name="ndaok[]" /><label for="ndaok6">This Agreement shall remain in force and effect throughout the period in which the recipient is actively engaged in the execution of the Purpose and for a period of Ten (10) years thereafter.</label></p></li>
<li><p><input type="checkbox" id="ndaok7" name="ndaok[]" /><label for="ndaok7">Promptly upon the termination of this Agreement, unless otherwise agreed in writing by both parties, The Recipient will deliver to the Developer all such materials and copies thereof including deleting of all computer files from any storage device the Recipient has maintained them on, unless written permission is given to retain these Works, upon completion of the review. The Recipient shall not use or disclose to any person , firm, or entity any proprietary, confidential, trade secret information of the Developer without the Developer's express, prior, written permission. The Recipient may not post comments about or copies of any software being reviewed to commercial, public, private or Internet computer services or bulletin boards.</label></p></li>
<li><p><input type="checkbox" id="ndaok8" name="ndaok[]" /><label for="ndaok8">Nothing herein (including the exchange of Proprietary Information hereunder) shall be deemed as obligating the parties to enter into any business relationship with respect to the Project or otherwise.</label></p></li>
<li><p><input type="checkbox" id="ndaok9" name="ndaok[]" /><label for="ndaok9">Each Party shall fully indemnify the other against any and all actions, claims, liability, costs, damages, charges and expenses suffered or incurred in connection with or arising out of any breach by a Party of any of the provisions of this Agreement or by any unauthorized disclosure or use of Proprietary Information by a third party or by any employee of any party to whom Proprietary Information has been disclosed or who has been allowed access thereto and acknowledges and confirms that a breach of its obligations hereunder cannot be compensated adequately by an award of damages or indemnity or other pecuniary remedy but the other Party shall also be entitled in the event of any such breach to the remedies of injunction specific performance or other equitable relief in respect of any such breach. Nothing in this Clause 10 shall be construed as a waiver by either Party of any of its rights including rights to damages or indemnity or other pecuniary remedy.</label></p></li>
<li><p><input type="checkbox" id="ndaok10" name="ndaok[]" /><label for="ndaok10">This Agreement shall be governed by and construed in accordance with the laws of The United States and any dispute arising under or in connection herewith shall be presented in and determined by these courts exclusively, with the Recipient waiving any and all applicable international and national laws.</label></p></li></ol>
<hr />

<p><input type="checkbox" name="ndaok[]" /> By check marking all paragraphs above, I attest that I have read, fully understand, and accept all provisions of this agreement.</p>
<input name="data[date]" required="required" value="<?php echo date('Y/m/d'); ?>" /> Today's Date (yyyy/mm/dd)<br />
<fieldset><legend>Personal</legend>
<input name="data[birthday]" required="required" /> Birthday Date (yyyy/mm/dd)<br />
<input name="data[email]" required="required" /> Email<br />
<input name="data[firstname]" required="required" /> First name<br />
<input name="data[lastname]" required="required" /> Last name (Surname)<br />
<input name="data[username]" readonly="readonly" value="" /> <?php echo $config['Company Name']; ?> username<br />
</fieldset>
<fieldset><legend>Contact</legend>
<input name="data[address]" required="required" /> Address<br />
<input name="data[address2]" /> Address (line 2)<br />
<input name="data[city]" required="required" /> City <br />
<input name="data[state]" required="required" /> State, Province, or Prefecture <br />
<input name="data[zip]" required="required" /> Zip code (N/A if not applicable)<br />
<input name="data[country]" required="required" /> Country<br />
</fieldset>
<p><input type="checkbox" name="ndaok[]" /> I do hereby attest that all information I have provided is correct, and agree to comply fully with all provisions of this agreement.</p>


	<p class="drawItDesc">Draw your signature (required)</p>
	<div class="sig sigWrapper" style="width:300px">
		<div class="typed"></div>
		<canvas class="pad" width="300" height="55"></canvas>
		<input type="hidden" name="output" class="output">
	</div>
		<button class="clearButton">Erase Signature</button>

<input type="submit" name="action" value="Submit Document" />

</form>

</div>
</body>
</html>
