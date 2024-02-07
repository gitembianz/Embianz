{{-- <footer> --}}
<livewire:store-footer />
{{-- </footer> --}}
<script src="/script/store/general.js" async defer></script>
@livewireScripts
{{-- -------------------------- Special Script -------------------------- --}}
<style type='text/css'>
    .embeddedServiceHelpButton .helpButton .uiButton {
        background-color: #005290;
        font-family: "Arial", sans-serif;
    }

    .embeddedServiceHelpButton .helpButton .uiButton:focus {
        outline: 1px solid #005290;
    }
</style>

<script type='text/javascript' src='https://service.force.com/embeddedservice/5.0/esw.min.js'></script>
<script type='text/javascript'>
    var initESW = function(gslbBaseURL) {
        embedded_svc.settings.displayHelpButton = true; //Or false
        embedded_svc.settings.language = ''; //For example, enter 'en' or 'en-US'

        embedded_svc.settings.defaultMinimizedText = 'Support'; //(Defaults to Chat with an Expert)
        //embedded_svc.settings.disabledMinimizedText = 'Agent Offline'; //(Defaults to Agent Offline)

        embedded_svc.settings.loadingText = 'Se Incarca';
        //embedded_svc.settings.storageDomain = 'yourdomain.com'; //(Sets the domain for your deployment so that visitors can navigate subdomains during a chat session)

        // Settings for Chat
        //embedded_svc.settings.directToButtonRouting = function(prechatFormData) {
        // Dynamically changes the button ID based on what the visitor enters in the pre-chat form.
        // Returns a valid button ID.
        //};
        //embedded_svc.settings.prepopulatedPrechatFields = {}; //Sets the auto-population of pre-chat form fields
        //embedded_svc.settings.fallbackRouting = []; //An array of button IDs, user IDs, or userId_buttonId
        //embedded_svc.settings.offlineSupportMinimizedText = '...'; //(Defaults to Contact Us)

        embedded_svc.settings.enabledFeatures = ['LiveAgent'];
        embedded_svc.settings.entryFeature = 'LiveAgent';

        embedded_svc.init(
            'https://eztem.my.salesforce.com',
            'https://eztem.my.site.com/liveAgentSetupFlow',
            gslbBaseURL,
            '00D09000008XPQu',
            'Team_1', {
                baseLiveAgentContentURL: 'https://c.la1-core1.sfdc-yzvdd4.salesforceliveagent.com/content',
                deploymentId: '5729N0000000077',
                buttonId: '5739N000000008U',
                baseLiveAgentURL: 'https://d.la1-core1.sfdc-yzvdd4.salesforceliveagent.com/chat',
                eswLiveAgentDevName: 'Team_1',
                isOfflineSupportEnabled: true
            }
        );
    };

    if (!window.embedded_svc) {
        var s = document.createElement('script');
        s.setAttribute('src', 'https://eztem.my.salesforce.com/embeddedservice/5.0/esw.min.js');
        s.onload = function() {
            initESW(null);
        };
        document.body.appendChild(s);
    } else {
        initESW('https://service.force.com');
    }
</script>
{{-- ------------------------ End Special Script ------------------------ --}}
</body>

</html>
