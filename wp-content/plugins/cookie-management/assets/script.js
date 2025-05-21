jQuery(document).ready(function() {
    jQuery(".close").click(function() {
        jQuery("#myModal").hide();
    });

    jQuery(".cookieManagerModal").click(function() {        
        jQuery("#myModal").show(); 
    });

    loadCookiePreferences();

    jQuery("#funcookieswitchVimeo, #funcookieswitchYouTube").change(updateMainCheckbox);
    updateMainCheckbox();

    jQuery("#cookiemanageracceptselected").click(function() {
        saveCookiePreferences();
        jQuery(".cookiePolicy").hide();
        jQuery("#myModal").hide();
    });

    jQuery("#cookiemanageracceptall").click(function() {
        jQuery("#funcookieswitchVimeo, #funcookieswitchYouTube").prop("checked", true);
        jQuery("#myModal").hide();
        jQuery(".cookiePolicy").hide();
        updateMainCheckbox();
        saveCookiePreferences();
    });
    jQuery("#cookie-accept").click(function(){
        jQuery("#funcookieswitchVimeo, #funcookieswitchYouTube").prop("checked", true);
        jQuery(".cookiePolicy").hide();
        updateMainCheckbox();
        saveCookiePreferences();
    })

    jQuery("#cookiemanagerrejectall").click(function() {
        jQuery("#funcookieswitchVimeo, #funcookieswitchYouTube").prop("checked", false);
        jQuery("#myModal").hide();
        jQuery(".cookiePolicy").hide();
        updateMainCheckbox();
        saveCookiePreferences();
    });
    jQuery("#cookie-reject").click(function() {
        jQuery("#funcookieswitchVimeo, #funcookieswitchYouTube").prop("checked", false);
        jQuery("#myModal").hide();
        jQuery(".cookiePolicy").hide();
        updateMainCheckbox();
        saveCookiePreferences();
    });
    jQuery("#fun-cookies-switch-1").change(changeFunSwitches);
});
function changeFunSwitches(){
    if (jQuery(this).is(':checked')) {
        jQuery("#funcookieswitchVimeo, #funcookieswitchYouTube").prop("checked", true);
        console.log('checked');
    } else {
        jQuery("#funcookieswitchVimeo, #funcookieswitchYouTube").prop("checked", false);
        console.log('unchecked');
    }
}
function updateMainCheckbox() {
    let allChecked = jQuery("#funcookieswitchVimeo").is(":checked") && jQuery("#funcookieswitchYouTube").is(":checked");
    jQuery("#fun-cookies-switch-1").prop("checked", allChecked);
}

function saveCookiePreferences() {
    let vimeoChecked = jQuery("#funcookieswitchVimeo").is(":checked");
    let youtubeChecked = jQuery("#funcookieswitchYouTube").is(":checked");

    const cookieData = {
        countrytype: "optin",
        dateexpires: new Date().getTime() + 3 * 60 * 1000,
        functionalCookies: [
            { company: "Vimeo", checked: vimeoChecked ? 1 : 0 },
            { company: "YouTube", checked: youtubeChecked ? 1 : 0 }
        ],
        advertisingCookies: [],
        performanceCookies: []
    };

    const stringified = JSON.stringify(cookieData);

    localStorage.setItem("cookiePreferences", stringified);
    document.cookie = `cookiePreferences=${encodeURIComponent(stringified)}; path=/; max-age=${3 * 60}; SameSite=Lax`;
}



// Function to load saved preferences from localStorage
function loadCookiePreferences() {
    const saved = localStorage.getItem("cookiePreferences");

    if (saved) {
        try {
            const cookieData = JSON.parse(saved);
            const now = new Date().getTime();

            if (cookieData.dateexpires && now < cookieData.dateexpires) {
                // Preferences are still valid, apply them
                const vimeo = cookieData.functionalCookies?.find(c => c.company === "Vimeo");
                const youtube = cookieData.functionalCookies?.find(c => c.company === "YouTube");

                jQuery("#funcookieswitchVimeo").prop("checked", vimeo?.checked === 1);
                jQuery("#funcookieswitchYouTube").prop("checked", youtube?.checked === 1);

                jQuery(".cookiePolicy").hide();      // hide modal
                jQuery(".cookiemanager").hide();     // hide manager
                return;
            } else {
                localStorage.removeItem("cookiePreferences");
            }
        } catch (e) {
            console.error("Invalid cookiePreferences in localStorage");
        }
    }

    jQuery(".cookiePolicy").show();
    jQuery(".cookiemanager").show();
}
