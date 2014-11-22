<tr><td colspan="2">{tr}Please enter the following captcha to proceed for the download{/tr}:</td></tr>
<tr><td rowspan="2"><img id="captcha" src="{$captcha_img}"></td><td>
<a href="#" onclick="document.getElementById('captcha').src ='{$captcha_img}?' + Math.random(); return false">
{tr}Reload Image{/tr}</a></td></tr>
<tr><td><input type="text" size="20" name="captcha_code"></td></tr>

