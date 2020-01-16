//TODO save google user and make event times available for none authorized attendees
var GoogleAuth;
// Client ID and API key from the Developer Console
var CLIENT_ID = '467734047893-55b6llqdgmu74h5v39hia5j7jtdfsn5b.apps.googleusercontent.com';
var API_KEY = 'AIzaSyC_Sj4DMosLKK8bZP5EJlzF67Hzt34_3zY';
// Authorization scopes required by the API; multiple scopes can be
// included, separated by spaces.
var SCOPES = "https://www.googleapis.com/auth/calendar https://www.googleapis.com/auth/calendar.readonly";

function handleClientLoad() {
    // Load the API's client and auth2 modules.
    // Call the initClient function after the modules load.
    gapi.load('client:auth2', initClient);
}

function initClient() {
    // Retrieve the discovery document for version 3 of Google Drive API.
    // In practice, your app can retrieve one or more discovery documents.
    var DISCOVERY_DOCS = ["https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest"];

    // Initialize the gapi.client object, which app uses to make API requests.
    // Get API key and client ID from API Console.
    // 'scope' field specifies space-delimited list of access scopes.
    gapi.client.init({
        'apiKey': API_KEY,
        'clientId': CLIENT_ID,
        'discoveryDocs': DISCOVERY_DOCS,
        'scope': SCOPES
    }).then(function () {
        GoogleAuth = gapi.auth2.getAuthInstance();

        // Listen for sign-in state changes.
        GoogleAuth.isSignedIn.listen(updateSigninStatus);

        // Handle initial sign-in state. (Determine if user is already signed in.)
        var user = GoogleAuth.currentUser.get();
        setSigninStatus();

        // Call handleAuthClick function when user clicks on
        //      "Sign In/Authorize" button.
        $('#authorize_button').click(function () {
            handleAuthClick();
        });
        $('#signout_button').click(function () {
            revokeAccess();
            updateToDatabase(user, 'delete');
        });
    });
}

function handleAuthClick() {
    if (GoogleAuth.isSignedIn.get()) {
        // User is authorized and has clicked 'Sign out' button.
        GoogleAuth.signOut();
    } else {
        // User is not signed in. Start Google auth flow.
        GoogleAuth.signIn();
    }
}

function revokeAccess() {
    GoogleAuth.disconnect();
}

function setSigninStatus(isSignedIn) {
    var user = GoogleAuth.currentUser.get();
    var isAuthorized = user.hasGrantedScopes(SCOPES);
    if (isAuthorized) {
        if (isSignedIn) {
            updateToDatabase(user, 'set');
        } else {
            updateToDatabase(user, 'update');
        }
        $('#authorize_button').css('display', 'none');
        $('#signout_button').css('display', 'inline-block');
        $('#auth-status').html(user.w3.ig + '. You are currently signed in and have granted ' +
            'access to this app.').addClass('text-success').removeClass('text-danger');
    } else {
        $('#authorize_button').css('display', 'inline-block');
        $('#signout_button').css('display', 'none');
        $('#auth-status').html('You have not authorized this app or you are ' +
            'signed out.').addClass('text-danger').removeClass('text-success');
    }
}

function updateSigninStatus(isSignedIn) {
    setSigninStatus(isSignedIn);
}

function updateToDatabase(user, request) {
    const callback = (function (response) {
        if (!isEmpty(response)) {
            if (response.executed === true) {
                if (request === 'set') {
                    $.alert({
                        title: 'logged in',
                        content: 'you are logged in to ' + user.Zi.idpId
                    })
                } else if (request === 'delete') {
                    $.alert({
                        title: 'logged out',
                        content: 'you are logged out'
                    })
                }
            } else {
                alert(response.error)
            }
        }
    });

    const userData = JSON.stringify({
        ...user.Zi
    });
    const requestData = JSON.stringify({
        type: request
    })

    $.ajax({
        type: 'POST',
        data: { user: userData, request: requestData },
        url: base_url + 'settings/json_google_token',
        dataType: 'json',
        error: function (xhr, errorType, exception) {
            if (xhr.status && xhr.status == 400) {
                alert(xhr.responseText);
            } else {
                //TODO better error handling
                alert(globalLang.something_went_wrong);
            }
        },
        success: callback
    });
}