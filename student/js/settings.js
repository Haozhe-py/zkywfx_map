/* PHP 填写
var username = ...;
var realname = ...;
var id = ...;
*/

function showAccountSettings() {
    document.getElementById('account-page').style.display = 'block';


    document.getElementById('account-btn').classList.add('settings-btn-clicked');
    document.getElementById('account-btn').classList.remove('settings-btn');
    document.getElementById('account-btn').disabled = true;


    document.getElementById('username-display').innerText = username;
    document.getElementById('name-display').innerText = realname;
}




showAccountSettings();