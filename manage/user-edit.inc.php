<form action='user.php' method='post'>

	<input type='hidden' name='formsent' value='yes'>
	<input type='hidden' name='action' value='<?=$action?>'>
	<?php if ($action=='edit') { ?>
		<input type='hidden' name='id' value='<?=$id?>'><?php } ?>

	<table cellspacing='0' cellpadding='8' width='500' >

			<tr class='formalternate'>
			<td class='formcaption1'>Email</td>
			<td>
				<input size='40' name='cp_user_email' value='<?=$tablerow["cp_user_email"]?>' maxlength='100'>
			</td>
		</tr>
		<tr >
			<td class='formcaption1'>Full Name</td>
			<td>
				<input size='40' name='cp_user_name' value='<?=$tablerow["cp_user_name"]?>' maxlength='100'>
			</td>
		</tr>
		<tr class='formalternate'>
			<td class='formcaption1'>Password</td>
			<td>
				<input type='cp_password' size='20' name='cp_user_password' maxlength='20'>
			</td>
		</tr>
		<? if(checkAccess("Administrator")) { ?>
		<tr >
			<td class='formcaption1'>Access To</td>
			<td>
			<?			
				$selections = getSelections($tablerow["cp_user_groups"]);
				$rows = dbRows('cp_access','cp_access_desc');
				while ($row = dbFetchArray($rows)) {
					echo "<label><input type='checkbox' name='cp_user_groups[]' value='{$row["cp_access_id"]}'";
					if (isset($selections[$row["cp_access_id"]]))
						echo " checked";
					echo ">{$row["cp_access_desc"]}</label><br>\n";
				} ?>
			</td>
		</tr>
		<? } ?>
		
		<? if ($action == 'edit') { ?>
		<tr class='formalternate'>
			<td class=formcaption1>Date Created</td>
			<td class=normal>
				<input type="hidden" name='cp_user_created' value='<?=$tablerow["cp_user_created"]?>'>
				<?=formatDate($tablerow["cp_user_created"])?>
			</td>
		</tr>
		<tr >
			<td class=formcaption1>Last Login</td>
			<td class=normal>
				<input type=hidden name='cp_user_last_login' value='<?=$tablerow["cp_user_last_login"]?>'>
				<?=formatDate($tablerow["cp_user_last_login"])?>
			</td>
		</tr>
		<? } ?>

		<tr>
			<td colspan=2 class=formbuttons>
				<input type=submit value='Save!'>
				<input type=button value='Cancel' onclick="window.location.href='user.php'">
			</td>
		</tr>
	</table>

</form>
