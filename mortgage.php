<?
/*
TigerTom's Loan & Mortgage Calculator (TTLoanCalc)

http://www.tigertom.com
http://www.ttfreeware.com

Copyright (c) 2005 T. O' Donnell
Developed for TigerTom by Jon K.

Released under the GNU General Public License, with the
following proviso: 

That the HTML of hyperlinks to the authors' websites
this software generates shall remain intact and unaltered, 
in any version of this software you make.
 
If this is not strictly adhered to, your licence shall be 
rendered null, void and invalid.
*/

// Remove bad input
if(isset($_REQUEST['amortization'])) $amortization = 'on';
$action = $_REQUEST['action'];
$year = $_REQUEST['year'];
$loan = $_REQUEST['loan'];
$periodicity = $_REQUEST['periodicity'];
$interest_rate = $_REQUEST['interest_rate'];
$currency = $_REQUEST['currency'];
$downpayment_percent= $_REQUEST['downpayment_percent'];

$year = ereg_replace ("<|>|\"|;|&", "", $year);
$downpayment_percent = ereg_replace ("<|>|\"|;|&", "", $downpayment_percent);
$loan = ereg_replace ("<|>|\"|;|&", "", $loan);
$interest_rate = ereg_replace ("<|>|\"|;|&", "", $interest_rate);

	$s = $currency;

	// Show form only if there is no action
	if ($action!="Calculate") {
		print "<form>\n";
		print "<table cellpadding=2 width=300 border=0 ALIGN=center>\n";
		print "<tr align=center><td colspan=2><h2>Mortgage Information</h2></td></tr>\n";

		print "<tr>\n";
		print "<td class=flabel>Sale Price of Home</td>\n";
		print "<td class=finput><input type=text name=loan size=15 class=calc_field value=".$_GET['amt']."></td>\n";
		print "</tr>\n";

		print "<tr>\n";
		print "<td class=flabel>Percentage Down, %</td>\n";
		print "<td class=finput><input type=text name=downpayment_percent size=15 class=calc_field></td>\n";
		print "</tr>\n";

		print "<tr>\n";
		print "<td class=flabel nowrap>Length of mortgage, years</td>\n";
		print "<td class=finput><input type=text name=year size=15 class=calc_field></td>\n";
		print "</tr>\n";

		print "<tr>\n";
		print "<td class=flabel>Interest rate, %</td>\n";
		print "<td class=finput><input type=text name=interest_rate size=4 class=calc_field></td>\n";
		print "</tr>\n";

		print "<tr>\n";
		print "<td class=flabel>Currency</td>\n";
		print "<td class=finput>\n";
		print "<select name=currency class=textDark>\n";
		print "<option value=$>$</option>\n";
		print "<option value=&euro;>&euro;</option>\n";
		print "<option value=&pound;>&pound;</option>\n";
		print "</td>\n";
		print "</tr>\n";

		print "<tr>\n";
		print "<td class=flabel>Show amortization</td>\n";
		print "<td class=finput><input type=checkbox name=amortization></td>\n";
		print "</tr>\n";

		print "<input type=hidden name=periodicity value=12>\n";
		print "<input type=hidden name=show value=".$_GET['show']." />";
		print "<tr><td colspan=2 align=center><input type=submit value=Calculate name=action></td></tr>\n";
		print "</table>\n";
		print "</html>\n";
		print "</form>\n";
	} else {

	// Only perform calculation and show tables if user
	// click calculate button
	include "math.php";
?>

<html>
<head>
<link href="loan.css" rel="stylesheet" type="text/css" />
</head>
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td colspan=2 class=header>Mortgage Payment Report</td>
</tr>
<tr class=top>
	<td align="right">Down Payment:</td>
	<td><b>
	<? print $s.$downpayment; ?>
	</b></td>
</tr>
<tr class=top>
	<td align="right">Amount Financed:</td>
	<td><b>
	<? print $s.$loan; ?>
	</b></td>
</tr>
<tr class=top>
	<td align="right">Length:</td>
	<td><b>
	<? print "$year years"; ?>
	</b></td>
</tr>
<tr class=top>
	<td align="right">Annual interest:</td>
	<td><b>
	<? print $interest_rate; ?> %
	</b></td>
</tr>
<tr class=toptotal>
	<td align="right">Monthly Payment:</td>
	<td><b>
	<? print $s.$periodic_payment; ?>
	</b><br>(excluding tax and insurance)</td>
</tr>
</table>

<!--
	This is the Totals table
-->

<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr class=total1>
	<td colspan="4">Totals</td>
</tr>
<tr class=total2>
	<td>&nbsp;</td>
	<td colspan="3">
		Outgoings: <B><? print $s.$total_paid; ?></B> paid out.<br>
		<? print $s.$total_interest; ?> paid in <b>interest</b>.<br>
		<? print $s.$loan; ?> paid towards <b>principal</b>.<br>
	</td>
</tr>
<? 
}
?>
<!--
	This is the Amortization table
-->

<?
	if (!$amortization) {
		
	} else {
?>

<tr class=aheader>
	<td colspan="4">Amortization</td>
</tr>
<tr class=aheader align=right>
	<td>Month</td>
	<td>Interest Paid</td>
	<td>Principal Paid</td>
	<td>Balance Outstanding</td>
</tr>

<?
	$yr = 0;
	for ($i=0; $i<$year*12; $i++) {
		print "<tr class=acontent>\n";
		print "<td>".(($i%12)+1)."</td>\n";
		print "<td>$s$periodic_interest[$i]</td>\n";
		print "<td>$s$periodic_principal[$i]</td>\n";
		print "<td>$s$balance[$i]</td>\n";
		print "</tr>\n";

		if ($i%12==11) {
			$ytotal = $yinterest[$yr] + $yprincipal[$yr];

			print "<tr class=total1>\n";
			print "<td colspan=4>Totals for year ".($yr+1)."</td>\n";
			print "</tr>\n";
			print "<tr class=total2>\n";
			print "<td>&nbsp;</td>\n";
			print "<td colspan=3>\n";
			print "Outgoings: <B>$s$ytotal</B> paid out in year ".($yr+1).".<br>\n";
			print "$s$yinterest[$yr] paid in <b>interest</b>.<br>\n";
			print "$s$yprincipal[$yr] paid towards <b>principal</b>.<br>\n";
			print "</td>\n";
			print "</tr>\n";

			if ($yr != $year-1) {
				print "<tr class=aheader align=right>\n";
				print "<td>Month</td>\n";
				print "<td>Interest Paid</td>\n";
				print "<td>Principal Paid</td>\n";
				print "<td>Balance Outstanding</td>\n";
				print "</tr>\n";
			}

			$yr++;
		}
	}
}
?>
