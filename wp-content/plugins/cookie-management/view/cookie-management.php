<?php
     $consent_heading   = get_option('cookie_consent_manager_heading', '');
     $show_more_detail   = get_option('cookie_show_more_detail', '');
     $consent_description  = get_option('cookie_consent_manager_description', '');
     $cookie_description   = get_option('sobeys_cookie_policy_description', '');
     $required_cookie   = get_option('sobeys_consent_required_cookie', '');
     $functional_cookie   = get_option('sobeys_consent_functional_cookie', '');
     $vimeo_description   = get_option('cookie_vimeo_description', '');
     $enabled_text   = get_option('cookie_enabled_text', '');
     $description_text   = get_option('cookie_description_text', '');
     $provider_text   = get_option('cookie_provider_text', '');
     $sap_text   = get_option('cookie_sap_text', '');
     $required_provider_description   = get_option('required_provider_description', '');
     $vimeo_text   = get_option('function_provier_vimeo', '');
     $youtube_text   = get_option('function_provier_youtube', '');
     $required_cookie_description   = get_option('required_cookies_description', '');
     $functional_cookie_description   = get_option('functional_cookies_description', '');
     $modify_btn_label     = get_option('sobeys_modify_cookie_btn', 'Modify Cookie Preferences');
     $accept_btn_label     = get_option('sobeys_accept_all_btn', 'Accept All');
     $confirm_btn_label     = get_option('sobeys_confirm_cookie_btn', 'Confirm My Choices');
     $reject_btn_label     = get_option('sobeys_reject_all_btn', 'Reject All');
     $youtube_description     = get_option('cookie_youtube_description', '');
     $cookie_policy_text     = get_option('cookie_policy_text', '');
     $privacy_policy_text     = get_option('privacy_policy_text', '');
     $term_condition_text     = get_option('term_condition_text', '');
 ?>

<div id="myModal" class="modal" style="display: none;">
    <!-- Modal content -->
    <div class="modal-content">
        <div class="modal-header" aria-live="assertive">
            <button type="button" class="close" data-dismiss="modal" aria-label="close"><span
                    aria-hidden="true">×</span></button>
            <h2 class="modal-title text-center" id="cookieManagerModalLabel">
                <?php echo isset($consent_heading) ? esc_attr($consent_heading) : ''; ?></h2>
        </div>
        <div class="main_content">
            <div class="text">
                <p><?php echo isset($consent_description) ? wp_kses_post($consent_description) : ''; ?></p>
            </div>
            <div class="content first">
                <h3><?php echo isset($required_cookie) ? esc_attr($required_cookie) : ''; ?></h3>
                <p><?php echo isset($required_cookie_description) ? wp_kses_post($required_cookie_description) : ''; ?>
                </p>
            </div>
            <div class="col-sm-2 display-table-cell">
                <div class="toggle-group cookietoggle">
                    <input type="checkbox" role="switch" class="cookiecheckbox" name="req-cookies-switch-1"
                        id="req-cookies-switch-1" checked="" onclick="return false;" aria-labelledby="reqtitle">

                    <label for="req-cookies-switch-1"></label>
                    <div class="onoffswitch" aria-hidden="true">
                        <div class="onoffswitch-label">
                            <div class="onoffswitch-inner"></div>
                            <div class="onoffswitch-switch"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <button data-toggle="collapse" data-target="#requiredcookies-config"
                    class="arrow-toggle link linkfocusborder" aria-label="Required Cookies Show More Details" value="">
                    <i class="glyphicon glyphicon-chevron-right"></i>
                    <i class="glyphicon glyphicon-chevron-down"></i>
                    <?php echo isset($show_more_detail) ? esc_attr($show_more_detail) : ''; ?>
                </button>

                <div id="requiredcookies-config" class="collapse">
                    <table class="table">

                        <caption><?php echo isset($required_cookie) ? esc_attr($required_cookie) : ''; ?> </caption>
                        <thead>
                            <tr>
                                <th scope="col" class="col-sm-2">
                                    <?php echo isset($provider_text) ? esc_attr($provider_text) : ''; ?></th>
                                <th scope="col" class="col-sm-9">
                                    <?php echo isset($description_text) ? esc_attr($description_text) : ''; ?></th>
                                <th scope="col" class="col-sm-1"><span
                                        class="pull-right"><?php echo isset($enabled_text) ? esc_attr($enabled_text) : ''; ?></span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row"><?php echo isset($sap_text) ? esc_attr($sap_text) : ''; ?></th>
                                <td id="SAPasserviceproviderreqdescription">
                                    <div role="region" aria-label="SAP as service provider-Description">
                                        <?php 
                                        echo isset($required_provider_description) && !empty($required_provider_description) 
                                            ? wp_kses_post($required_provider_description)
                                            : 'No description available.';
                                        ?>
                                        <br>
                                    </div>
                                </td>

                                <td>
                                    <div class="toggle-group cookietoggle">

                                        <input type="checkbox" role="switch" class="cookiecheckbox"
                                            data-usercountrytype="optin" id="req-cookies-switch-2" checked=""
                                            onclick="return false;" disabled="disabled"
                                            aria-label="Cookies from provider SAPasserviceprovider are required and cannot be turned off">
                                        <label for="req-cookies-switch-2"></label>
                                        <div class="onoffswitch" aria-hidden="true">
                                            <div class="onoffswitch-label">
                                                <div class="onoffswitch-inner"></div>
                                                <div class="onoffswitch-switch"></div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <hr class="splitter">

            <div class="content second">
                <h3><?php echo isset($functional_cookie) ? esc_attr($functional_cookie) : ''; ?></h3>
                <p><?php echo isset($functional_cookie_description) ? wp_kses_post($functional_cookie_description) : ''; ?>
                </p>
            </div>
            <div class="col-sm-2 display-table-cell">
                <div class="toggle-group cookietoggle">
                    <input type="checkbox" role="switch" class="cookiecheckbox" name="fun-cookies-switch-1"
                        id="fun-cookies-switch-1" onchange="changeFunSwitches()" checked="" tabindex="0"
                        aria-label="Consent to all Functional Cookies">
                    <label for="fun-cookies-switch-1"></label>
                    <div class="onoffswitch" aria-hidden="true">
                        <div class="onoffswitch-label">
                            <div class="onoffswitch-inner"></div>
                            <div class="onoffswitch-switch"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <button data-toggle="collapse" data-target="#functionalcookies-switch"
                    class="arrow-toggle link linkfocusborder" aria-label="Functional Cookies Show More Details"
                    value="">
                    <i class="glyphicon glyphicon-chevron-right"></i>
                    <i class="glyphicon glyphicon-chevron-down"></i>
                    <?php echo isset($show_more_detail) ? esc_attr($show_more_detail) : ''; ?>
                </button>

                <div id="functionalcookies-switch" class="collapse">
                    <table class="table">
                        <caption><?php echo isset($functional_cookie) ? esc_attr($functional_cookie) : ''; ?></caption>
                        <thead>
                            <tr>
                                <th scope="col" class="col-sm-2">
                                    <?php echo isset($provider_text) ? esc_attr($provider_text) : ''; ?></th>
                                <th scope="col" class="col-sm-9">
                                    <?php echo isset($description_text) ? esc_attr($description_text) : ''; ?></th>
                                <th scope="col" class="col-sm-1"><span
                                        class="pull-right"><?php echo isset($enabled_text) ? esc_attr($enabled_text) : ''; ?></span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row"><?php echo isset($vimeo_text) ? esc_attr($vimeo_text) : ''; ?></th>
                                <td id="Vimeofundescription">
                                    <div role="region" aria-label="Vimeo-Description">
                                        <p><?php echo isset($vimeo_description) ? wp_kses_post($vimeo_description) : ''; ?><br>
                                            <a href="https://www.sobeys.com/en/privacy-policy/#cookies" target="_blank"
                                                title="Sobeys Cookie Policy"><?php echo isset($cookie_policy_text) ? esc_attr($cookie_policy_text) : ''; ?></a><br>
                                            <a href="https://www.sobeys.com/en/privacy-policy/" target="_blank"
                                                title="Sobeys Privacy Policy"><?php echo isset($privacy_policy_text) ? esc_attr($privacy_policy_text) : ''; ?></a><br>
                                            <a href="https://www.sobeys.com/en/terms-and-conditions/  " target="_blank"
                                                title="Sobeys Terms and Conditions"><?php echo isset($term_condition_text) ? esc_attr($term_condition_text) : ''; ?></a>
                                        </p>

                                    </div>
                                </td>
                                <td>
                                    <div class="toggle-group cookietoggle">
                                        <input type="checkbox" role="switch" class="cookiecheckbox funcookiescheckbox"
                                            id="funcookieswitchVimeo" checked="" tabindex="0" data-provider="vimeo"
                                            aria-label="Consent to cookies from provider Vimeo">
                                        <label for="funcookieswitchVimeo"></label>
                                        <div class="onoffswitch" aria-hidden="true">
                                            <div class="onoffswitch-label">
                                                <div class="onoffswitch-inner"></div>
                                                <div class="onoffswitch-switch"></div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo isset($youtube_text) ? esc_attr($youtube_text) : ''; ?></th>
                                <td id="YouTubefundescription">
                                    <div role="region" aria-label="YouTube-Description">
                                        <p><?php echo isset($youtube_description) ? wp_kses_post($youtube_description) : ''; ?><br>
                                        <p><?php echo isset($vimeo_description) ? wp_kses_post($vimeo_description) : ''; ?><br>
                                            <a href="https://www.sobeys.com/en/privacy-policy/#cookies" target="_blank"
                                                title="Sobeys Cookie Policy"><?php echo isset($cookie_policy_text) ? esc_attr($cookie_policy_text) : ''; ?></a><br>
                                            <a href="https://www.sobeys.com/en/privacy-policy/" target="_blank"
                                                title="Sobeys Privacy Policy"><?php echo isset($privacy_policy_text) ? esc_attr($privacy_policy_text) : ''; ?></a><br>
                                            <a href="https://www.sobeys.com/en/terms-and-conditions/  " target="_blank"
                                                title="Sobeys Terms and Conditions"><?php echo isset($term_condition_text) ? esc_attr($term_condition_text) : ''; ?></a>
                                        </p>


                                    </div>
                                </td>
                                <td>
                                    <div class="toggle-group cookietoggle">
                                        <input type="checkbox" role="switch" class="cookiecheckbox funcookiescheckbox"
                                            id="funcookieswitchYouTube" checked="" tabindex="0" data-provider="youTube"
                                            aria-label="Consent to cookies from provider YouTube">
                                        <label for="funcookieswitchYouTube"></label>
                                        <div class="onoffswitch" aria-hidden="true">
                                            <div class="onoffswitch-label">
                                                <div class="onoffswitch-inner"></div>
                                                <div class="onoffswitch-switch"></div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Vimeo and YouTube Cookies -->


            <div class="modal-footer">
                <button type="button" id="cookiemanageracceptselected" class="btn-default">
                    <?php echo isset($confirm_btn_label) ? esc_attr($confirm_btn_label) : ''; ?></button>
                </button>
                <div>
                    <button type="button" id="cookiemanagerrejectall"
                        class="btn-primary"><?php echo isset($reject_btn_label) ? esc_attr($reject_btn_label) : ''; ?></button>
                    </button>
                    <button type="button" id="cookiemanageracceptall" class="btn-primary">
                        <?php echo isset($accept_btn_label) ? esc_attr($accept_btn_label) : ''; ?></button>
                </div>

            </div>
        </div>
        </section>
    </div>
</div>

<!-- Cookie Policy Banner -->

<div class="cookiePolicy cookiemanager" role="region" aria-labelledby="cookieManagerModalLabel">
    <div class="centered">
        <p><?php echo isset($cookie_description) ? wp_kses_post($cookie_description) : ''; ?></p>
        <button id="cookie-bannershow" class="cookieManagerModal" data-toggle="modal" data-target="#cookieManagerModal"
            class="link cookieSmallBannerButton secondarybutton shadowfocus" value="">
            <?php echo isset($modify_btn_label) ? esc_attr($modify_btn_label) : ''; ?></button>
        <div style="float:right">
            <button id="cookie-reject" class="cookieSmallBannerButton cookiemanagerrejectall shadowfocus" value="">
                <?php echo isset($reject_btn_label) ? esc_attr($reject_btn_label) : ''; ?></button>
            <button id="cookie-accept" class="cookieSmallBannerButton cookiemanageracceptall shadowfocus" value="">
                <?php echo isset($accept_btn_label) ? esc_attr($accept_btn_label) : ''; ?></button>
        </div>
    </div>
</div>