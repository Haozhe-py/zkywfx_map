<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/check_login.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>设置</title>
        <link rel='icon' href='/icon.ico' type='image/x-icon'/>
        <link rel="stylesheet" href="/index.css">
    </head>
    <body>


        <!-- 设置页面 -->
        <div class="settings-box-s" id="settings-box">
            <div style="margin-top: 0%; height: 10%; width: 100%; margin-left: 2%; display: flex; align-items: center; gap: 10px;">
                <h2 style="width:87.5%; margin-block-start: 0%; margin-block-end: 0%; display: flex; align-items: center; line-height: 34px;">
                    <svg width="34" height="34" style="display:block; height:34px; width:34px;" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_1450_63327)"><path d="M14.086 5.51365C13.8717 5.05749 13.5879 4.58542 13.2889 4.18107C13.208 4.07171 13.1596 4.04373 13.0242 4.03053C12.4276 3.97254 11.8244 4.05526 11.2269 3.99719C10.7223 3.94815 10.3132 3.7166 10.0115 3.30918C9.6698 2.84776 9.43967 2.31343 9.09818 1.85234C9.01765 1.74364 8.96799 1.71588 8.83348 1.70281C8.29426 1.65044 7.70396 1.65061 7.1665 1.70281C7.03199 1.71588 6.98233 1.74364 6.9018 1.85234C6.56061 2.31302 6.33019 2.84774 5.98849 3.30918C5.68675 3.7166 5.27768 3.94815 4.7731 3.99719C4.17557 4.05526 3.57233 3.97254 2.97579 4.03053C2.8404 4.04373 2.79201 4.07171 2.71109 4.18107C2.41205 4.58542 2.12829 5.05749 1.91397 5.51365C1.85293 5.64358 1.8528 5.7018 1.91397 5.83189C2.14859 6.33076 2.49741 6.76892 2.73231 7.26853C2.95934 7.7515 2.96035 8.24716 2.73332 8.73043C2.49831 9.2306 2.14885 9.66837 1.91397 10.1681C1.85285 10.2982 1.85293 10.3564 1.91397 10.4863C2.1285 10.9428 2.41179 11.4142 2.71109 11.8189C2.79201 11.9283 2.8404 11.9562 2.97579 11.9694C3.57233 12.0274 4.17557 11.9447 4.7731 12.0028C5.27768 12.0518 5.68675 12.2834 5.98849 12.6908C6.33018 13.1522 6.56031 13.6865 6.9018 14.1476C6.98233 14.2563 7.03199 14.2841 7.1665 14.2972C7.70396 14.3494 8.29426 14.3495 8.83348 14.2972C8.96799 14.2841 9.01765 14.2563 9.09818 14.1476C9.43937 13.687 9.66979 13.1522 10.0115 12.6908C10.3132 12.2834 10.7223 12.0518 11.2269 12.0028C11.8243 11.9447 12.4271 12.0275 13.0242 11.9694C13.1596 11.9562 13.208 11.9283 13.2889 11.8189C13.589 11.4131 13.8719 10.942 14.086 10.4863C14.1471 10.3564 14.1471 10.2982 14.086 10.1681C13.8512 9.6686 13.5017 9.23061 13.2667 8.73043C13.0396 8.24716 13.0406 7.7515 13.2677 7.26853C13.5026 6.7689 13.8513 6.33106 14.086 5.83189C14.1472 5.7018 14.1471 5.64358 14.086 5.51365ZM15.3034 6.40372C15.0684 6.90358 14.7188 7.34118 14.4841 7.84036C14.423 7.97024 14.423 8.02855 14.4841 8.1586C14.7189 8.65833 15.0684 9.09611 15.3034 9.59626C15.5308 10.0801 15.5307 10.5743 15.3034 11.0582C15.052 11.5933 14.7224 12.1425 14.3699 12.6191C14.0684 13.0265 13.658 13.259 13.1535 13.3081C12.5565 13.366 11.9541 13.2835 11.3572 13.3414C11.2227 13.3545 11.173 13.3822 11.0925 13.4909C10.751 13.9521 10.5209 14.4864 10.1792 14.9478C9.87822 15.3542 9.46713 15.5869 8.96381 15.6358C8.34002 15.6964 7.66188 15.6966 7.03617 15.6358C6.53285 15.5869 6.12176 15.3542 5.82078 14.9478C5.47905 14.4863 5.24872 13.9517 4.90747 13.4909C4.82695 13.3822 4.77728 13.3545 4.64278 13.3414C4.04641 13.2835 3.44367 13.366 2.84647 13.3081C2.34195 13.259 1.93158 13.0265 1.63007 12.6191C1.27861 12.144 0.948392 11.5941 0.69656 11.0582C0.469254 10.5743 0.469218 10.0801 0.69656 9.59626C0.931567 9.09612 1.28124 8.65806 1.51591 8.1586C1.57702 8.02855 1.57696 7.97024 1.51591 7.84036C1.28111 7.34094 0.931574 6.90359 0.69656 6.40372C0.469152 5.91991 0.469306 5.42561 0.69656 4.94182C0.94838 4.40586 1.27862 3.85597 1.63007 3.38091C1.93158 2.97348 2.34195 2.74094 2.84647 2.69189C3.44347 2.63396 4.04593 2.71648 4.64278 2.65855C4.77728 2.64548 4.82695 2.61773 4.90747 2.50903C5.24898 2.04791 5.47907 1.51361 5.82078 1.05218C6.12176 0.645798 6.53285 0.413111 7.03617 0.36417C7.65996 0.303549 8.3381 0.303362 8.96381 0.36417C9.46713 0.413111 9.87822 0.645798 10.1792 1.05218C10.5209 1.51364 10.7513 2.04827 11.0925 2.50903C11.173 2.61773 11.2227 2.64548 11.3572 2.65855C11.9541 2.71648 12.5565 2.63396 13.1535 2.69189C13.658 2.74094 14.0684 2.97348 14.3699 3.38091C14.7214 3.85597 15.0516 4.40586 15.3034 4.94182C15.5307 5.42561 15.5308 5.91991 15.3034 6.40372Z" fill="currentColor"></path><path d="M9.13758 7.99999C9.13758 7.37149 8.62849 6.86239 7.99999 6.86239C7.37149 6.86239 6.8624 7.37149 6.8624 7.99999C6.8624 8.62849 7.37149 9.13758 7.99999 9.13758C8.62849 9.13758 9.13758 8.62849 9.13758 7.99999ZM10.4833 7.99999C10.4833 9.37126 9.37126 10.4833 7.99999 10.4833C6.62872 10.4833 5.51668 9.37126 5.51668 7.99999C5.51668 6.62872 6.62872 5.51668 7.99999 5.51668C9.37126 5.51668 10.4833 6.62872 10.4833 7.99999Z" fill="currentColor"></path></g><defs><clipPath id="clip0_1450_63327"><rect width="16" height="16" fill="currentColor"></rect></clipPath></defs></svg>
                    <span style="margin-left: 2%; line-height:34px; display:inline-block; transform:translateY(2px);">设置</span>
                </h2>
                <!-- 退出按钮 -->
                <span style="cursor: pointer; width:12; align-items: center; justify-content: center;">
                    <a href="javascript:void(0);" onclick="javascript:history.back();" style="color: gray;">
                        <svg width="20" height="20" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14.1167 13.197L13.1969 14.1168L1.88324 2.80309L2.80303 1.8833L14.1167 13.197Z" fill="currentColor"></path><path d="M13.1969 1.88331L14.1167 2.8031L2.80303 14.1168L1.88324 13.197L13.1969 1.88331Z" fill="currentColor"></path></svg>
                    </a>
                </span>
            </div>

            <div style="margin-top: 5%; height: 80%; width: 100%;">
                <div id="buttons" style="height: 100%; width: 25%; float: left; ">
                    <button class="settings-btn" id="account-btn" onclick="showAccountSettings()">
                        <svg width="20" height="20" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.0306 5.46369C11.0304 3.78995 9.67334 2.43357 7.99955 2.43357C6.32595 2.4338 4.96965 3.79009 4.96943 5.46369C4.96943 7.13748 6.32581 8.49455 7.99955 8.49477C9.67348 8.49477 11.0306 7.13762 11.0306 5.46369ZM12.3163 5.46369C12.3163 7.84777 10.3836 9.78042 7.99955 9.78042C5.61566 9.7802 3.68281 7.84763 3.68281 5.46369C3.68304 3.07994 5.61579 1.14718 7.99955 1.14696C10.3835 1.14696 12.3161 3.0798 12.3163 5.46369Z" fill="currentColor"></path>
                            <path d="M7.99996 10.3316C11.7342 10.3316 14.1863 11.8997 15.0387 14.4445L14.4292 14.6483L13.8197 14.8531C13.1954 12.9893 11.3672 11.6182 7.99996 11.6182C4.63271 11.6182 2.80449 12.9893 2.18024 14.8531L1.57074 14.6483L0.961243 14.4445C1.81362 11.8997 4.26573 10.3316 7.99996 10.3316Z" fill="currentColor"></path>
                        </svg>
                        <span>账号设置</span>
                    </button>
                </div>
                <div id="pages" style="height: 100%; width: 75%; float: right; ">

                <!-- 账号设置页面 -->
                <div id="account-page" style="height: 100%; width: 100%; display: none;">
                    <div id="account-home">
                        <a class="settings-link" href="javascript:void(0);" onclick="javascript:showUsernameSettings();"><div>
                            <span>用户名</span>
                            <span style="float: right;">
                                <span id="username-display" style="float: left; color: gray;"></span>
                                <span style="float: right; margin-left: 10px; cursor: pointer; color: gray;">
                                    <svg width="20" height="20" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.5 2.15137L5.92383 2.57617L8.65137 5.30273C8.90706 5.55843 9.13382 5.78438 9.29785 5.98828C9.46883 6.20088 9.61756 6.44405 9.66602 6.75C9.69222 6.91565 9.69222 7.08435 9.66602 7.25C9.61756 7.55595 9.46883 7.79912 9.29785 8.01172C9.13382 8.21561 8.90706 8.44157 8.65137 8.69727L5.92383 11.4238L5.5 11.8486L4.65137 11L5.07617 10.5762L7.80273 7.84863C8.07732 7.57405 8.24849 7.40124 8.3623 7.25977C8.46904 7.12709 8.47813 7.07728 8.48047 7.0625C8.48703 7.02105 8.48703 6.97895 8.48047 6.9375C8.47813 6.92272 8.46904 6.87291 8.3623 6.74023C8.24848 6.59876 8.07732 6.42595 7.80273 6.15137L5.07617 3.42383L4.65137 3L5.5 2.15137Z" fill="currentColor"></path></svg>
                                </span>
                            </span>
                        </div></a>

                        <span style="color: gray !important;"><hr style="border-color: gray;" /></span>
                        
                        <a class="settings-link" href="javascript:void(0);" onclick="javascript:showNameSettings();"><div>
                            <span>名字</span>
                            <span style="float: right;">
                                <span id="name-display" style="float: left; color: gray;"></span>
                                <span style="float: right; margin-left: 10px; cursor: pointer; color: gray;">
                                    <svg width="20" height="20" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.5 2.15137L5.92383 2.57617L8.65137 5.30273C8.90706 5.55843 9.13382 5.78438 9.29785 5.98828C9.46883 6.20088 9.61756 6.44405 9.66602 6.75C9.69222 6.91565 9.69222 7.08435 9.66602 7.25C9.61756 7.55595 9.46883 7.79912 9.29785 8.01172C9.13382 8.21561 8.90706 8.44157 8.65137 8.69727L5.92383 11.4238L5.5 11.8486L4.65137 11L5.07617 10.5762L7.80273 7.84863C8.07732 7.57405 8.24849 7.40124 8.3623 7.25977C8.46904 7.12709 8.47813 7.07728 8.48047 7.0625C8.48703 7.02105 8.48703 6.97895 8.48047 6.9375C8.47813 6.92272 8.46904 6.87291 8.3623 6.74023C8.24848 6.59876 8.07732 6.42595 7.80273 6.15137L5.07617 3.42383L4.65137 3L5.5 2.15137Z" fill="currentColor"></path></svg>
                                </span>
                            </span>
                        </div></a>
                        
                        <span style="color: gray !important;"><hr style="border-color: gray;" /></span>
                        
                        <a class="settings-link" href="javascript:void(0);" onclick="javascript:showPasswordSettings();"><div>
                            <span>密码</span>
                            <span style="float: right;">
                                <span style="float: right; margin-left: 10px; cursor: pointer; color: gray;">
                                    <svg width="20" height="20" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.5 2.15137L5.92383 2.57617L8.65137 5.30273C8.90706 5.55843 9.13382 5.78438 9.29785 5.98828C9.46883 6.20088 9.61756 6.44405 9.66602 6.75C9.69222 6.91565 9.69222 7.08435 9.66602 7.25C9.61756 7.55595 9.46883 7.79912 9.29785 8.01172C9.13382 8.21561 8.90706 8.44157 8.65137 8.69727L5.92383 11.4238L5.5 11.8486L4.65137 11L5.07617 10.5762L7.80273 7.84863C8.07732 7.57405 8.24849 7.40124 8.3623 7.25977C8.46904 7.12709 8.47813 7.07728 8.48047 7.0625C8.48703 7.02105 8.48703 6.97895 8.48047 6.9375C8.47813 6.92272 8.46904 6.87291 8.3623 6.74023C8.24848 6.59876 8.07732 6.42595 7.80273 6.15137L5.07617 3.42383L4.65137 3L5.5 2.15137Z" fill="currentColor"></path></svg>
                                </span>
                            </span>
                        </div></a>
                    </div>
                    <div id="account-usrname" style="display: none;">
                        <h2 style="margin-block-start: 0%; display: flex; align-items: center; gap: 10px; line-height:34px;">
                            <a href="javascript:void(0);" onclick="javascript:showAccountSettings();" style="color: gray; margin-right: 10px;">
                                <svg width="34" height="34" style="display:block; height:34px; width:34px;" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M 8.83704,2.15137 8.41321,2.57617 5.68567,5.30273 C 5.42998,5.55843 5.20322,5.78438 5.03919,5.98828 4.86821,6.20088 4.71948,6.44405 4.67102,6.75 c -0.0262,0.16565 -0.0262,0.33435 0,0.5 0.04846,0.30595 0.19719,0.54912 0.36817,0.76172 0.16403,0.20389 0.39079,0.42985 0.64648,0.68555 L 8.41321,11.4238 8.83704,11.8486 9.68567,11 9.26087,10.5762 6.53431,7.84863 C 6.25972,7.57405 6.08855,7.40124 5.97474,7.25977 5.868,7.12709 5.85891,7.07728 5.85657,7.0625 5.85001,7.02105 5.85001,6.97895 5.85657,6.9375 5.85891,6.92272 5.868,6.87291 5.97474,6.74023 6.08856,6.59876 6.25972,6.42595 6.53431,6.15137 L 9.26087,3.42383 9.68567,3 Z" fill="currentColor" id="path1" /></svg>
                            </a>
                            <span style="display:inline-block; line-height:34px; transform:translateY(2px); margin-bottom: 10px;">修改用户名</span>
                        </h2>
                        <form action="settings.php" method="POST" class="settings-form">
                            <input type="text" name="new_username" placeholder="新用户名" required />
                            <input type="submit" value="保存" />
                            <input type="hidden" name="action" value="change_username" />
                        </form>
                    </div>
                    <div id="account-name" style="display: none;">
                            <h2 style="margin-block-start: 0%; display: flex; align-items: center; gap: 10px; line-height:34px;">
                                <a href="javascript:void(0);" onclick="javascript:showAccountSettings();" style="color: gray; margin-right: 10px;">
                                    <svg width="34" height="34" style="display:block; height:34px; width:34px;" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M 8.83704,2.15137 8.41321,2.57617 5.68567,5.30273 C 5.42998,5.55843 5.20322,5.78438 5.03919,5.98828 4.86821,6.20088 4.71948,6.44405 4.67102,6.75 c -0.0262,0.16565 -0.0262,0.33435 0,0.5 0.04846,0.30595 0.19719,0.54912 0.36817,0.76172 0.16403,0.20389 0.39079,0.42985 0.64648,0.68555 L 8.41321,11.4238 8.83704,11.8486 9.68567,11 9.26087,10.5762 6.53431,7.84863 C 6.25972,7.57405 6.08855,7.40124 5.97474,7.25977 5.868,7.12709 5.85891,7.07728 5.85657,7.0625 5.85001,7.02105 5.85001,6.97895 5.85657,6.9375 5.85891,6.92272 5.868,6.87291 5.97474,6.74023 6.08856,6.59876 6.25972,6.42595 6.53431,6.15137 L 9.26087,3.42383 9.68567,3 Z" fill="currentColor" id="path1" /></svg>
                                </a>
                                <span style="display:inline-block; line-height:34px; transform:translateY(2px); margin-bottom: 10px;">修改名字</span>
                            </h2>
                        <form action="settings.php" method="POST" class="settings-form">
                            <input type="text" name="new_name" placeholder="新名字" required />
                            <input type="submit" value="保存" />
                            <input type="hidden" name="action" value="change_name" />
                        </form>
                    </div>
                    <div id="account-password" style="display: none;">
                            <h2 style="margin-block-start: 0%; display: flex; align-items: center; gap: 10px; line-height:34px;">
                                <a href="javascript:void(0);" onclick="javascript:showAccountSettings();" style="color: gray; margin-right: 10px;">
                                    <svg width="34" height="34" style="display:block; height:34px; width:34px;" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M 8.83704,2.15137 8.41321,2.57617 5.68567,5.30273 C 5.42998,5.55843 5.20322,5.78438 5.03919,5.98828 4.86821,6.20088 4.71948,6.44405 4.67102,6.75 c -0.0262,0.16565 -0.0262,0.33435 0,0.5 0.04846,0.30595 0.19719,0.54912 0.36817,0.76172 0.16403,0.20389 0.39079,0.42985 0.64648,0.68555 L 8.41321,11.4238 8.83704,11.8486 9.68567,11 9.26087,10.5762 6.53431,7.84863 C 6.25972,7.57405 6.08855,7.40124 5.97474,7.25977 5.868,7.12709 5.85891,7.07728 5.85657,7.0625 5.85001,7.02105 5.85001,6.97895 5.85657,6.9375 5.85891,6.92272 5.868,6.87291 5.97474,6.74023 6.08856,6.59876 6.25972,6.42595 6.53431,6.15137 L 9.26087,3.42383 9.68567,3 Z" fill="currentColor" id="path1" /></svg>
                                </a>
                                <span style="display:inline-block; line-height:34px; transform:translateY(2px); margin-bottom: 10px;">修改密码</span>
                            </h2>
                        <form action="settings.php" method="POST" class="settings-form">
                            <input type="password" name="current_password" placeholder="当前密码" required />
                            <input type="password" name="new_password" placeholder="新密码" required />
                            <input type="password" name="confirm_password" placeholder="确认新密码" required /><br />
                            <input type="submit" value="保存" />
                            <input type="hidden" name="action" value="change_password" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>

    <?php
        echo "<script>var username='".addslashes($username)."';";
        echo "var realname='".addslashes($name)."';";
        echo "var id='".$cur_id."';</script>";
    ?>
    <script src="js/settings.js"></script>
    <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action !== '') {
                $manager = new JsonFileManager($_SERVER['DOCUMENT_ROOT'] . '/data/usr.json');
                $data = $manager->read();

                if ($action === 'change_username') {
                    $new_username = $_POST['new_username'];
                    try {
                        $manager->atomicUpdate(function ($data) use (&$cur_id, &$new_username) {
                            foreach ($data['users'] as $user) {
                                if ($user['username'] === $new_username) {
                                    throw new Exception('用户名已存在');
                                } 
                            }
                            foreach ($data['users'] as &$user) {
                                if ($user['id'] === $cur_id) {
                                    $user['username'] = $new_username;
                                    $history_manager = new JsonFileManager($_SERVER['DOCUMENT_ROOT'] . '/' . 'data/' . $cur_id . '.json');
                                    $history = $history_manager->read();
                                    $new_history = [
                                        "time" => date("Y-m-d H:i:s"),
                                        "action" => "change_username",
                                        "description" => "将用户名修改为" . $new_username,
                                        // 积分不变，从最后一个记录获取。
                                        "cur_scores" => $history['history'][count($history['history']) - 1]['cur_scores']
                                    ];
                                    $history_manager->atomicUpdate(function ($history_data) use (&$new_history) {
                                        $history_data['history'][] = $new_history;
                                        return $history_data;
                                    });
                                    echo "<script>/*以GET方式刷新*/window.location.href=window.location.href;</script>";
                                    break;
                                }
                            }
                            return $data;
                        });
                    }
                    catch (Exception $e) {
                        echo "<script>window.alert('".$e->getMessage()."');</script>";
                    }
                }
                
                else if ($action === 'change_name') {}

                else if ($action === 'change_password') {}
            }
        }
    ?>
</html>