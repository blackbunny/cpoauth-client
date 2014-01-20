<?php

namespace CpOauthClient;

Class Client{
    
    
    const TEXT_CLIENT_ID = "client_id";
    const TEXT_CLIENT_SECRET = "client_secret";
    const TEXT_REDIRECT_URI = "redirect_uri";
    const TEXT_RESPONSE_TYPE = "response_type";
    const TEXT_GRANT_TYPE = "grant_type";
    const TEXT_CODE  = "code";

    protected $client_secret;
    protected $client_id;
    protected $authorize_url = "http://oauth.crowdpanthers.com/oauth2/authorize";
    protected $access_token_url = "http://oauth.crowdpanthers.com/oauth2/token";
    protected $api_url = "http://api.crowdpanthers.com/api/v1";    
    protected $redirect_uri;
    protected $authorization_code;
    protected $access_token;
    protected $grant_type = "authorization_code";
    private   $curl;
    
    public function __construct($params=array()) {
        
        foreach($params as $key => $val){
            $this->{$key} = $val;
        }
        
        $this->curl = curl_init();
    }
    
    
    public function __destruct() {
        curl_close($this->curl);
    }
    
    
    public function generateCpLoginUrl(){
        $data = array(
            self::TEXT_CLIENT_ID => $this->client_id,
            self::TEXT_REDIRECT_URI => $this->redirect_uri,
            self::TEXT_RESPONSE_TYPE => self::TEXT_CODE
        );
        
        $link = $this->authorize_url."?".http_build_query($data);
        return $link;
    }

    
    public function changeCodeWithToken(){
        
        curl_setopt($this->curl, CURLOPT_URL, $this->access_token_url);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, array(
            "redirect_uri" => $this->redirect_uri,
            "grant_type" => "authorization_code",
            "code" => $this->authorization_code,
            "client_id" => $this->client_id,
            "client_secret" => $this->client_secret

        ));
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
             "Accept: application/json",
        )); 
        
        $result = curl_exec($this->curl);
        
        return $result;
    }
    
    public function requestResource($resource){
        
        $link = $this->api_url.$resource;
        
        curl_setopt($this->curl, CURLOPT_URL, $link);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_POST, false);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
             "Accept: application/json",
             "Authorization: Bearer ".$this->access_token
        ));        
        
        $result = curl_exec($this->curl);
        
        return $result;
    }


    public function getClient_secret() {
        return $this->client_secret;
    }

    public function setClient_secret($client_secret) {
        $this->client_secret = $client_secret;
        return $this;
    }

    public function getClient_id() {
        return $this->client_id;
    }

    public function setClient_id($client_id) {
        $this->client_id = $client_id;
        return $this;
    }

    public function getAuthorize_url() {
        return $this->authorize_url;
    }

    public function setAuthorize_url($authorize_url) {
        $this->authorize_url = $authorize_url;
        return $this;
    }

    public function getAccess_token_url() {
        return $this->access_token_url;
    }

    public function setAccess_token_url($access_token_url) {
        $this->access_token_url = $access_token_url;
        return $this;
    }

    public function getRedirect_uri() {
        return $this->redirect_uri;
    }

    public function setRedirect_uri($redirect_uri) {
        $this->redirect_uri = $redirect_uri;
        return $this;
    }

    public function getApi_url() {
        return $this->api_url;
    }

    public function setApi_url($api_url) {
        $this->api_url = $api_url;
        return $this;
    }

    public function getAuthorization_code() {
        return $this->authorization_code;
    }

    public function setAuthorization_code($authorization_code) {
        $this->authorization_code = $authorization_code;
        return $this;
    }

    public function getAccess_token() {
        return $this->access_token;
    }

    public function setAccess_token($access_token) {
        $this->access_token = $access_token;
        return $this;
    }

    public function getGrant_type() {
        return $this->grant_type;
    }

    public function setGrant_type($grant_type) {
        $this->grant_type = $grant_type;
        return $this;
    }


}