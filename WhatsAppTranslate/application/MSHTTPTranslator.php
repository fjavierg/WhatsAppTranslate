<?php
class AccessTokenAuthentication {
    /*
     * Get the access token.
     *
     * @param string $grantType    Grant type.
     * @param string $scopeUrl     Application Scope URL.
     * @param string $clientID     Application client ID.
     * @param string $clientSecret Application client ID.
     * @param string $authUrl      Oauth Url.
     *
     * @return string.
     */
                                    function getTokens($grantType, $scopeUrl, $clientID, $clientSecret, $authUrl){
                                    try {
            //Initialize the Curl Session.
            $ch = curl_init();
            //Create the request Array.
            $paramArr = array (
                                    'grant_type'    => $grantType,
                 'scope'         => $scopeUrl,
                 'client_id'     => $clientID,
                 'client_secret' => $clientSecret
            );
            //Create an Http Query.//
            $paramArr = http_build_query($paramArr);
            //Set the Curl URL.
            curl_setopt($ch, CURLOPT_URL, $authUrl);
            //Set HTTP POST Request.
            curl_setopt($ch, CURLOPT_POST, TRUE);
            //Set data to POST in HTTP "POST" Operation.
            curl_setopt($ch, CURLOPT_POSTFIELDS, $paramArr);
            //CURLOPT_RETURNTRANSFER- TRUE to return the transfer as a string of the return value of curl_exec().
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
            //CURLOPT_SSL_VERIFYPEER- Set FALSE to stop cURL from verifying the peer's certificate.
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            //Execute the  cURL session.
            $strResponse = curl_exec($ch);
            //Get the Error Code returned by Curl.
            $curlErrno = curl_errno($ch);
            if($curlErrno){
                $curlError = curl_error($ch);
                throw new Exception($curlError);
            }
            //Close the Curl Session.
            curl_close($ch);
            //Decode the returned JSON string.
            $objResponse = json_decode($strResponse);
            if ($objResponse->error){
                throw new Exception($objResponse->error_description);
            }
            return $objResponse->access_token;
        } catch (Exception $e) {
            echo "Exception-".$e->getMessage();
        }
    }
}
/*
 * Class:HTTPTranslator
 * 
 * Processing the translator request.
 */
Class HTTPTranslator {
	
	protected $authUrl = "https://datamarket.accesscontrol.windows.net/v2/OAuth2-13/";
	protected $scopeUrl = "http://api.microsofttranslator.com";
	protected $grantType = "client_credentials";

	protected $clientID;
	protected $clientSecret;
	protected $accessToken;
	
	public function __construct($clientID,$clientSecret){
		$this->clientID = $clientID;
		$this->clientSecret = $clientSecret;
		//Create the AccessTokenAuthentication object.
		$authObj      = new AccessTokenAuthentication();
		//Get the Access token.
		$this->accessToken  = $authObj->getTokens($this->grantType, $this->scopeUrl, $this->clientID, $this->clientSecret, $this->authUrl);
	}
	
    /*
     * Create and execute the HTTP CURL request.
     *
     * @param string $url        HTTP Url.
     * @param string $authHeader Authorization Header string.
     * @param string $postData   Data to post.
     *
     * @return string.
     *
     */
    function curlRequest($url, $authHeader, $postData=''){
        //Initialize the Curl Session.
        $ch = curl_init();
        //Set the Curl url.
        curl_setopt ($ch, CURLOPT_URL, $url);
        //Set the HTTP HEADER Fields.
        curl_setopt ($ch, CURLOPT_HTTPHEADER, array($authHeader,"Content-Type: text/xml"));
        //CURLOPT_RETURNTRANSFER- TRUE to return the transfer as a string of the return value of curl_exec().
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //CURLOPT_SSL_VERIFYPEER- Set FALSE to stop cURL from verifying the peer's certificate.
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, False);
        if($postData) {
            //Set HTTP POST Request.
            curl_setopt($ch, CURLOPT_POST, TRUE);
            //Set data to POST in HTTP "POST" Operation.
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        }
        //Execute the  cURL session.
        $curlResponse = curl_exec($ch);
        //Get the Error Code returned by Curl.
        $curlErrno = curl_errno($ch);
        if ($curlErrno) {
            $curlError = curl_error($ch);
            throw new Exception($curlError);
        }
        //Close a cURL session.
        curl_close($ch);
        return $curlResponse;
    }
    /*
     * Create Request XML Format.
     *
     * @param string $languageCode  Language code
     *
     * @return string.
     */
    function createReqXML($languageCode) {
        //Create the Request XML.
        $requestXml = '<ArrayOfstring xmlns="http://schemas.microsoft.com/2003/10/Serialization/Arrays" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">';
        if($languageCode) {
            $requestXml .= "<string>$languageCode</string>";
        } else {
            throw new Exception('Language Code is empty.');
        }
        $requestXml .= '</ArrayOfstring>';
        return $requestXml;
    }
    /*
     * Gets access token
     *
     * @param string $clientID  Client id
     *               $clientSecret Client secret
     *
     * @return string.
     */
    function refreshAccessToken() {

    	//Create the AccessTokenAuthentication object.
    	$authObj      = new AccessTokenAuthentication();
    	//Get the Access token.
    	$this->$accessToken  = $authObj->getTokens($this->grantType, $this->scopeUrl, $this->clientID, $this->clientSecret, $this->authUrl);
    	//Create the authorization Header string.
    	return $accessToken;
    }
    /*
     * Trasnlate to English.
     *
     * @param string $clientID  Client id
     *               $clientSecret Client secret
     *
     * @return string.
     */
    function translate($inputStr,$toLanguage) {
    	$params = "text=".urlencode($inputStr)."&to=".$toLanguage;
    	$translateUrl = "http://api.microsofttranslator.com/v2/Http.svc/Translate?$params";
    	
    	//Get access Token
    	$authHeader = "Authorization: Bearer ". $this->accessToken;
    	
    	//Get the curlResponse.
    	$curlResponse = $this->curlRequest($translateUrl, $authHeader);
    	
    	//Interprets a string of XML into an object.
    	$xmlObj = simplexml_load_string($curlResponse);
    	foreach((array)$xmlObj[0] as $val){
    		$translatedStr = $val;
    	}
    	return $translatedStr;
    }
}

