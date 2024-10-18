<div class="modal" id="updateProfile">
    <div class="modal_content">
        <div class="cont">
            <div style="margin-top:5px;display:flex;justify-content:space-evenly;align-items:center;">
                <a class="Cancel" style="opacity:0">X</a>
                <h2 style="color:aliceblue;">Product Adding Form</h2>
                <a onclick="removeModal('profile')" class="close">X</a>
            </div>
            <div class="form-content">
                <form action="" method="post">
                    <div class="top_ui" style="display:flex;gap:10px;flex-wrap:wrap">
                        <input type="text" name="user_id" id="" value="<?= $_SESSION['user_id'] ?>" hidden>
                        <label>
                            <span>Username</span>
                            <input type="text" value="<?php echo $_SESSION['username'] ?>" disabled/>
                        </label>
                        <label>
                            <span>Profession:</span>
                            <input type="text" name="profession" placeholder="<?php
                                                                                switch ($userDetails['profession']) {
                                                                                    case '':
                                                                                        echo 'Not Set';
                                                                                        break;
                                                                                    default:
                                                                                        echo $userDetails['profession'];
                                                                                }
                                                                                ?>" value="<?php if ($userDetails['profession'] != '') echo $userDetails['profession'] ?>" />
                        </label>
                        <label>
                            <span>About me</span>
                            <textarea name="description" rows="2" placeholder="<?php
                                                                                switch ($userDetails['description']) {
                                                                                    case '':
                                                                                        echo 'I love Thick Girls';
                                                                                        break;
                                                                                    default:
                                                                                        echo $userDetails['description'];
                                                                                }
                                                                                ?>" value="<?php if ($userDetails['description'] != '') echo $userDetails['description'] ?>"></textarea>
                        </label>
                        <label>
                            <span>Address</span>
                            <textarea name="address" rows="2" placeholder="<?php
                                                                                switch ($userDetails['address']) {
                                                                                    case '':
                                                                                        echo 'Not Set';
                                                                                        break;
                                                                                    default:
                                                                                        echo $userDetails['address'];
                                                                                }
                                                                                ?>" value="<?php if ($userDetails['address'] != '') echo $userDetails['address'] ?>"></textarea>
                        </label>
                        <label>
                            <span>Phone no.</span>
                            <input type="num" name="phone" placeholder="<?php
                                                                        switch ($userDetails['phone']) {
                                                                            case '':
                                                                                echo 'Not Set';
                                                                                break;
                                                                            default:
                                                                                echo $userDetails['phone'];
                                                                        }
                                                                        ?>" value="<?php if ($userDetails['phone'] != '') echo $userDetails['phone'] ?>" />
                        </label>
                        <label>
                            <span>Email</span>
                            <input type="email" value="<?php if ($userDetails['email'] != '') echo $userDetails['email'] ?>" disabled />
                        </label>
                        <label>
                            <span>User Type</span>
                            <input type="text" name="user_type" value="<?php if ($userDetails['user_type'] != '') echo ucfirst($userDetails['user_type']) ?>" disabled />
                        </label>
                        
                    </div>

                    <button class="btn" name="updateProfile" style="left:500px;">
                        <span class="button_top">
                            Update Profile
                        </span>
                    </button>

                </form>
            </div>
        </div>
    </div>
</div>