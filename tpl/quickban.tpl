<!-- BEGIN: MAIN -->

		<!-- BEGIN: COMPLETE -->
		<div class="block">
			<div class="done">{PHP.L.quickban_complete}</div>
			<table class="cells">
				<tr>
					<td>{PHP.L.Users}</td>
					<td>{QUICKBAN_COUNT_USERS}</td>
				</tr>
				<!-- IF {QUICKBAN_COUNT_FILES} -->
				<tr>
					<td>{PHP.L.Files}</td>
					<td>{QUICKBAN_COUNT_FILES}</td>
				</tr>
				<!-- ENDIF -->
				<!-- IF {PHP.active.forums} -->
				<tr>
					<td>{PHP.L.forums_topics}</td>
					<td>{QUICKBAN_COUNT_TOPICS}</td>
				</tr>
				<tr>
					<td>{PHP.L.forums_posts}</td>
					<td>{QUICKBAN_COUNT_POSTS}</td>
				</tr>
				<!-- ENDIF -->
				<!-- IF {PHP.active.page} -->
				<tr>
					<td>{PHP.L.Pages}</td>
					<td>{QUICKBAN_COUNT_PAGES}</td>
				</tr>
				<!-- ENDIF -->
				<!-- IF {PHP.active.comments} -->
				<tr>
					<td>{PHP.L.comments_comments}</td>
					<td>{QUICKBAN_COUNT_COMMENTS}</td>
				</tr>
				<!-- ENDIF -->
				<!-- IF {PHP.active.pm} -->
				<tr>
					<td>{PHP.L.Private_Messages}</td>
					<td>{QUICKBAN_COUNT_PM}</td>
				</tr>
				<!-- ENDIF -->
			</table>
		</div>
		<!-- END: COMPLETE -->
		
		<!-- BEGIN: CONFIRM -->
		<div class="block">
			<h2>{PHP.L.quickban_user_match}</h2>
			<table class="cells">
				<thead>
					<tr>
						<th>{PHP.L.User}</th>
						<th>{PHP.L.Group}</th>
						<th>{PHP.L.Email}</th>
						<th>{PHP.L.Lastlogged}</th>
						<th>{PHP.L.Ip}</th>
					</tr>
					</tr>
				</thead>
				<tbody>
					<!-- BEGIN: USERS -->
					<tr>
						<td>{USER_NAME}</td>
						<td>{USER_MAINGRP}</td>
						<td>{USER_EMAIL}</td>
						<td>{USER_LASTLOG}</td>
						<td>
						<!-- IF {PHP.active.ipsearch} -->
						{USER_IPSEARCH}
						<!-- ELSE -->
						{USER_LASTIP}
						<!-- ENDIF -->
						</td>
					</tr>
					<!-- END: USERS -->
				</tbody>
			</table>
		</div>
		<div class="block">
			<h2>{PHP.L.quickban_confirm_options}</h2>
			<form action="{QUICKBAN_URL}" method="post">
				<table>
					<!-- IF {PHP.active.banlist} -->
					<tr>
						<td>{PHP.L.quickban_banlist}?</td>
						<td>{QUICKBAN_BANLIST}</td>
					</tr>
					<!-- ENDIF -->
					<tr>
						<td>{PHP.L.Delete} {PHP.L.quickban_userdata}?</td>
						<td>{QUICKBAN_USERDATA}</td>
					</tr>
					<!-- IF {PHP.active.pfs} -->
					<tr>
						<td>{PHP.L.Delete} {PHP.L.PFS} {PHP.L.Files}?</td>
						<td>{QUICKBAN_PFS}</td>
					</tr>
					<!-- ENDIF -->
					<!-- IF {PHP.active.userimage} -->
					<tr>
						<td>{PHP.L.Delete} {PHP.L.User} {PHP.L.Image}?</td>
						<td>{QUICKBAN_USERIMAGE}</td>
					</tr>
					<!-- ENDIF -->
					<!-- IF {PHP.active.forums} -->
					<tr>
						<td>{PHP.L.Delete} {PHP.L.forums_topics} & {PHP.L.forums_posts}?</td>
						<td>{QUICKBAN_FORUMS}</td>
					</tr>
					<!-- ENDIF -->
					<!-- IF {PHP.active.page} -->
					<tr>
						<td>{PHP.L.Delete} {PHP.L.Pages}?</td>
						<td>{QUICKBAN_PAGE}</td>
					</tr>
					<!-- ENDIF -->
					<!-- IF {PHP.active.comments} -->
					<tr>
						<td>{PHP.L.Delete} {PHP.L.comments_comments}?</td>
						<td>{QUICKBAN_COMMENTS}</td>
					</tr>
					<!-- ENDIF -->
					<!-- IF {PHP.active.pm} -->
					<tr>
						<td>{PHP.L.Delete} {PHP.L.Private_Messages}?</td>
						<td>{QUICKBAN_PM}</td>
					</tr>
					<!-- ENDIF -->
					<tr>
						<td colspan="2"><div class="warning">{PHP.L.quickban_warning_message}</div></td>
					</tr>
					<tr>
						<td colspan="2"><input type="submit" class="submit"  name="submit" value="{PHP.L.Submit}" /></td>
					</tr>
				</table>
			</form>
		</div>
		<!-- END: CONFIRM -->
<!-- END: MAIN -->