<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cookie Consent Manager</title>
</head>

<body>

    <!-- The Modal for Cookie Preferences -->
    <div id="myModal" class="modal" style="display:none;">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <section class="main">
                <div class="content">
                    <h2>Cookie Consent Manager</h2>
                </div>
                <div class="main_content">
                    <div class="text">
                        <p>When you visit any website, it may store or retrieve information on your browser,
                            particularly in the form of cookies. Because we respect your right to privacy, you can
                            choose not to allow some types of cookies. However, blocking some types of cookies may
                            impact your experience of the site and the services we are able to offer.</p>
                    </div>
                    <div class="content first">
                        <h3>Required Cookies</h3>
                        <p>These cookies are required to use this website and can't be turned off.</p>
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

                    <hr class="splitter">

                    <div class="content second">
                        <h3>Functional Cookies</h3>
                        <p>These cookies are required to use this website and can't be turned off.</p>
                    </div>
                    <div class="col-sm-2 display-table-cell">
                        <div class="toggle-group cookietoggle">
                            <input type="checkbox" role="switch" class="cookiecheckbox" name="fun-cookies-switch-1"
                                id="fun-cookies-switch-1" onchange="updateMainCheckbox()" checked="" tabindex="0"
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

                    <!-- Vimeo and YouTube Cookies -->
                    <div id="functionalcookies-switch">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Provider</th>
                                    <th scope="col">Description</th>
                                    <th scope="col"><span class="pull-right">Enabled</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">Vimeo</th>
                                    <td>
                                        <p>Vimeo is a video hosting platform. Opting out of Vimeo cookies will disable your ability to watch or interact with Vimeo videos.</p>
                                    </td>
                                    <td>
                                        <div class="toggle-group cookietoggle">
                                            <input type="checkbox" role="switch" class="cookiecheckbox funcookiescheckbox" id="funcookieswitchVimeo" checked="" data-provider="vimeo">
                                            <label for="funcookieswitchVimeo"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">YouTube</th>
                                    <td>
                                        <p>YouTube is a video-sharing service. Opting out of YouTube cookies will disable your ability to watch or interact with YouTube videos.</p>
                                    </td>
                                    <td>
                                        <div class="toggle-group cookietoggle">
                                            <input type="checkbox" role="switch" class="cookiecheckbox funcookiescheckbox" id="funcookieswitchYouTube" checked="" data-provider="youtube">
                                            <label for="funcookieswitchYouTube"></label>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer">
                        <button type="button" id="cookiemanageracceptselected" class="btn-default">Confirm My Choices</button>
                        <button type="button" id="cookiemanagerrejectall" class="btn-primary">Reject All Cookies</button>
                        <button type="button" id="cookiemanageracceptall" class="btn-primary">Accept All Cookies</button>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Cookie Policy Banner -->
    <div class="cookiePolicy cookiemanager" role="region" aria-labelledby="cookieManagerModalLabel">
        <div class="centered">
            <p>We use cookies to offer you the best possible website experience. Your cookie preferences will be stored
                in your browser’s local storage.</p>
            <button id="cookie-bannershow" class="cookieManagerModal">Modify Cookie Preferences</button>
            <div style="float:right">
                <button id="cookie-reject" class="cookieSmallBannerButton cookiemanagerrejectall">Reject All Cookies</button>
                <button id="cookie-accept" class="cookieSmallBannerButton cookiemanageracceptall">Accept All Cookies</button>
            </div>
        </div>
    </div>
    </body>

</html>