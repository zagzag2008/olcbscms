<pro>
<table>
	<tr>
		<td>Логин</td><td><input type="text" placeholder="Логин" name="login" required form="from_login"></td>
	</tr>
	<tr>
		<td>Пароль&nbsp;</td><td><input type="password" placeholder="Пароль" name="psw" required form="from_login"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<input type="text" placeholder="Код с картинки" name="capcha" required form="from_login"><br>
			<img src="{srp}/capcha">
		</td>
	</tr>
	<tr>
		<td colspan="2"><button type="submit" form="from_login">Вход</button></td>
	</tr>
</table>
<form method="post" action="/{srp}/auth" id="from_login"></form>
</pro>
