<?php
$var=translate("sl","en","Trenutno pojem.");
echo $var;

/*from to synonims
 *source: http://msdn.microsoft.com/en-us/library/hh456380.aspx
 *
 * ar	Arabic	العربية
cs	Czech	česky, čeština
da	Danish	dansk
de	German	Deutsch
en	English	English
et	Estonian	eesti, eesti keel
fi	Finnish	suomi, suomen kieli
fr	French	français
nl	Dutch	Nederlands, Vlaams
el	Greek	Ελληνικά
he	Hebrew	עברית
ht	Haitian Creole	Kreyòl ayisyen
hu	Hungarian	Magyar
id	Indonesian	Bahasa Indonesia
it	Italian	Italiano
ja	Japanese	日本語
ko	Korean	한국어
lt	Lithuanian	lietuvių kalba
lv	Latvian	latviešu valoda
no	Norwegian	Norsk
pl	Polish	polski
pt	Portuguese	Português
ro	Romanian	română
es	Spanish	español
ru	Russian	русский язык
sk	Slovak	slovenčina
sl	Slovene	slovenščina
sv	Swedish	svenska
th	Thai	ไทย
tr	Turkish	Türkçe
uk	Ukrainian	українська
vi	Vietnamese	Tiếng Việt
zh-CHS	Simplified Chinese	中文
zh-CHT	Traditional Chinese
 */
function translate($from, $to, $text)
{
    include 'HttpTranslator.php';
    include 'AccessTokenAuthentication.php';

    try {
	//TODO: check your CLIENTID AND CLIENTSECRET KEY!!!
        //Client ID of the application.
        $clientID = "xxxxxxxxxxxxxxxxxxxx";
        //Client Secret key of the application.
        $clientSecret = "yyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy";
        //OAuth Url.
        $authUrl = "https://datamarket.accesscontrol.windows.net/v2/OAuth2-13/";
        //Application Scope Url
        $scopeUrl = "http://api.microsofttranslator.com";
        //Application grant type
        $grantType = "client_credentials";

        //Create the AccessTokenAuthentication object.
        $authObj = new AccessTokenAuthentication();
        //Get the Access token.
        $accessToken = $authObj->getTokens($grantType, $scopeUrl, $clientID, $clientSecret, $authUrl);
        //Create the authorization Header string.
        $authHeader = "Authorization: Bearer " . $accessToken;

        //Set the params.//
        $fromLanguage = $from;
        $toLanguage = $to;
//        $inputStr     = $_POST["txtToTranslate"];
        $inputStr = $text;
        $contentType = 'text/plain';
        $category = 'general';

        $params = "text=" . urlencode($inputStr) . "&to=" . $toLanguage . "&from=" . $fromLanguage;
        $translateUrl = "http://api.microsofttranslator.com/v2/Http.svc/Translate?$params";

        //Create the Translator Object.
        $translatorObj = new HTTPTranslator();

        //Get the curlResponse.
        $curlResponse = $translatorObj->curlRequest($translateUrl, $authHeader);

        //Interprets a string of XML into an object.
        $xmlObj = simplexml_load_string($curlResponse);

        foreach ((array)$xmlObj[0] as $val) {
            $translatedStr = $val;
        }
       return $translatedStr;

    } catch (Exception $e) {
        echo "Exception: " . $e->getMessage() . PHP_EOL;
    }
}

