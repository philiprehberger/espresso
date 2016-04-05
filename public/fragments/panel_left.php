<!-- panel left --> 
<div data-role="panel" id="leftpanel" data-display="overlay" data-position-fixed="true" >
    <div class='nd2-sidepanel-profile wow fadeInDown'>
        <img class='profile-background' src="//lorempixel.com/400/200/abstract/3/" />
        <div class="row">
            <div class='col-xs-4 center-xs'>
                <div class='box'>
                    <?php
                    if (isset($_SESSION['image_id']) === TRUE) {
                        echo '<img data-reset_item="src" data-reset_value="" class="reset profile-thumbnail" src="/assets/uploads/files/' . $_SESSION['image_id'] . '" />';
                    }
                    ?>

                </div>
            </div>
            <div class='col-xs-8'>
                <div class='box profile-text'>                                
                    <strong data-reset_item="html" data-reset_value="Guest" class="reset"><?php
                        if (isset($_SESSION['first_name']) === TRUE) {
                            echo $_SESSION['first_name'];
                            if (isset($_SESSION['last_name']) === TRUE) {
                                echo ' ' . $_SESSION['last_name'];
                            }
                        } else {
                            echo 'Guest';
                        }
                        ?> </strong>
                    <span data-reset_item="html" data-reset_value="" class='subline reset'><?php
                        if (isset($_SESSION['username']) === TRUE) {
                            echo $_SESSION['username'];
                        }
                        ?></span>
                </div>
            </div>
        </div>
    </div>
    <?php if (isset($_SESSION['user_type_id']) === TRUE && $_SESSION['user_type_id'] === 1) { ?>
        <ul class="remove " data-role="listview" data-inset="false">
            <li data-role="list-divider">Manage</li>
            <li ><a href="/manage" class="waves-effect waves-button waves-effect waves-button">All</a></li>
            <li ><a href="/manage/products" class="waves-effect waves-button waves-effect waves-button">Products</a></li>
        </ul>
        <div class="remove" data-role="collapsible" data-inset="false"  data-collapsed-icon="carat-d" data-expanded-icon="carat-d" data-iconpos="right">
            <h3>Reports</h3>
            <ul data-role="listview" data-inset="false" data-icon="false">
                <li><a href="#" data-ajax='false' data-icon="false">Report 1</a></li>
                <li><a href="#" data-ajax='false' data-icon="false">Report 2</a></li>
            </ul>
        </div>
        <hr class="inset remove ">
        <ul data-role="listview" data-inset="false">
            <li ><a href="#" style="display:none;" class="btn_login waves-effect waves-button waves-effect waves-button">Login</a></li>
            <li ><a href="#" style="display:none;" class="btn_signup waves-effect waves-button waves-effect waves-button">New User</a></li>
            <li ><a href="#" class="remove waves-effect waves-button waves-effect waves-button">My Profile</a></li>
            <li ><a href="#" class="remove btn_logout waves-effect waves-button waves-effect waves-button">Log Out</a></li>
        </ul>

    <?php } else { ?>
        <ul data-role="listview" data-inset="false">
            <li ><a href="#" class="btn_login waves-effect waves-button waves-effect waves-button">Login</a></li>
            <li ><a href="#" class="btn_signup waves-effect waves-button waves-effect waves-button">New User</a></li>
        </ul>
    <?php } ?>
</div>
<!-- /panel left -->