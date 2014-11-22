<tr><td rowspan="2" valign="top">{tr}Captcha code{/tr}:<br />
<a href="#" onclick="document.getElementById('captcha').src ='{$captcha_img}?' + Math.random(); return false">
{tr}Reload Image{/tr}</a></td><td><img id="captcha" src="{$captcha_img}"></td></tr>
<tr><td><input type="text" size="20" name="captcha_code"></td></tr>