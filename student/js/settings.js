/* PHP 填写
var username = ...;
var realname = ...;
var id = ...;
*/

function showAccountSettings() {
    document.getElementById('account-usrname').style.display = 'none';
    document.getElementById('account-name').style.display = 'none';
    document.getElementById('account-password').style.display = 'none';

    document.getElementById('account-page').style.display = 'block';
    document.getElementById('account-home').style.display = 'block';


    document.getElementById('account-btn').classList.add('settings-btn-clicked');
    document.getElementById('account-btn').classList.remove('settings-btn');
    document.getElementById('account-btn').disabled = true;


    document.getElementById('username-display').innerText = username;
    document.getElementById('name-display').innerText = realname;
}

function showUsernameSettings() {
    document.getElementById('account-page').style.display = 'block';
    document.getElementById('account-usrname').style.display = 'block';

    document.getElementById('account-name').style.display = 'none';
    document.getElementById('account-password').style.display = 'none';
    document.getElementById('account-home').style.display = 'none';
}

function showNameSettings() {
    document.getElementById('account-page').style.display = 'block';
    document.getElementById('account-name').style.display = 'block';

    document.getElementById('account-usrname').style.display = 'none';
    document.getElementById('account-password').style.display = 'none';
    document.getElementById('account-home').style.display = 'none';
}

function showPasswordSettings() {
    document.getElementById('account-page').style.display = 'block';
    document.getElementById('account-password').style.display = 'block';

    document.getElementById('account-usrname').style.display = 'none';
    document.getElementById('account-name').style.display = 'none';
    document.getElementById('account-home').style.display = 'none';
}


showAccountSettings();