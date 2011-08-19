<?php

/**
 * Mastop/SystemBundle/Twitter.php
 *
 * Serviço "mastop.twitter"
 *  
 * 
 * @copyright 2011 Mastop Internet Development.
 * @link http://www.mastop.com.br
 * @author Fernando Santos <o@fernan.do>
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

namespace Mastop\SystemBundle;

use Symfony\Component\HttpFoundation\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use \TwitterOAuth;

class Twitter {

    private $twitter;
    private $callbackURL;

    public function __construct(TwitterOAuth $twitter, $callbackURL = null)
    {
        $this->twitter = $twitter;
        $this->callbackURL = $callbackURL;
    }

    public function getLoginUrl(Request $request)
    {
        $session = $request->getSession();

        /* Get temporary credentials. */
        $requestToken = $this->callbackURL ? $this->twitter->getRequestToken($this->callbackURL) : $this->twitter->getRequestToken();

        /* Save temporary credentials to session. */
        $session->set('oauth_token', $requestToken['oauth_token']);
        $session->set('oauth_token_secret', $requestToken['oauth_token_secret']);

        /* If last connection failed don't display authorization link. */
        switch ($this->twitter->http_code) {
            case 200:
                /* Build authorize URL and redirect user to Twitter. */
                $redirectURL = $this->twitter->getAuthorizeURL($requestToken);
                return $redirectURL;
                break;
            default:
                /* return null if something went wrong. */
                return null;
        }
    }

    public function getAccessToken(Request $request)
    {
        if($request->get('oauth_verifier') == null) return null;
        $session = $request->getSession();

        //set OAuth token in the API
        $this->twitter->setOAuthToken($request->get('oauth_token'), $session->get('oauth_token_secret'));

        /* Check if the oauth_token is old */
        if ($session->has('oauth_token')) {
            if ($session->get('oauth_token') && ($session->get('oauth_token') !== $request->get('oauth_token'))) {
                $session->remove('oauth_token');
                return null;
            }
        }

        /* Request access tokens from twitter */
        $accessToken = $this->twitter->getAccessToken($request->get('oauth_verifier'));

        /* Save the access tokens. Normally these would be saved in a database for future use. */
        $session->set('access_token', $accessToken['oauth_token']);
        $session->set('access_token_secret', $accessToken['oauth_token_secret']);

        /* Remove no longer needed request tokens */
        !$session->has('oauth_token') ?: $session->remove('oauth_token', null);
        !$session->has('oauth_token_secret') ?: $session->remove('oauth_token_secret', null);

        /* If HTTP response is 200 continue otherwise send to connect page to retry */
        if (200 == $this->twitter->http_code) {
            /* The user has been verified and the access tokens can be saved for future use */
            return $accessToken;
        }

        /* Return null for failure */
        return null;
    }
}