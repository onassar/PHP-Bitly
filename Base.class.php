<?php

    // Namespace overhead
    namespace onassar\Bitly;
    use onassar\RemoteRequests;

    /**
     * Base
     * 
     * PHP wrapper for Bitly.
     * 
     * @link    https://github.com/onassar/PHP-Bitly
     * @author  Oliver Nassar <onassar@gmail.com>
     * @extends RemoteRequests\Base
     */
    class Base extends RemoteRequests\Base
    {
        /**
         * Traits
         * 
         */
        use RemoteRequests\Traits\RateLimits;

        /**
         * _host
         * 
         * @access  protected
         * @var     string (default: 'api-ssl.bitly.com')
         */
        protected $_host = 'api-ssl.bitly.com';

        /**
         * _paths
         * 
         * @access  protected
         * @var     array
         */
        protected $_paths = array(
            'shorten' => '/v4/shorten'
        );

        /**
         * _token
         * 
         * @access  protected
         * @var     null|string (default: null)
         */
        protected $_token = null;

        /**
         * _getAuthorizationHeader
         * 
         * @access  protected
         * @return  string
         */
        protected function _getAuthorizationHeader(): string
        {
            $token = $this->_token;
            $header = 'Authorization: Bearer ' . ($token);
            return $header;
        }

        /**
         * _getContentTypeHeader
         * 
         * @access  protected
         * @return  string
         */
        protected function _getContentTypeHeader(): string
        {
            $header = 'Content-Type: application/json';
            return $header;
        }

        /**
         * _getHTTPHeaders
         * 
         * @access  protected
         * @return  array
         */
        protected function _getHTTPHeaders(): array
        {
            $headers = array();
            $header = $this->_getAuthorizationHeader();
            array_push($headers, $header);
            $header = $this->_getContentTypeHeader();
            array_push($headers, $header);
            return $headers;
        }

        /**
         * _getRequestStreamContextOptions
         * 
         * @access  protected
         * @return  array
         */
        protected function _getRequestStreamContextOptions(): array
        {
            $options = parent::_getRequestStreamContextOptions();
            $headers = $this->_getHTTPHeaders();
            $options['http']['header'] = $headers;
            return $options;
        }

        /**
         * _getShortenRequestURL
         * 
         * @access  protected
         * @return  string
         */
        protected function _getShortenRequestURL(): string
        {
            $host = $this->_host;
            $path = $this->_paths['shorten'];
            $url = 'https://' . ($host) . ($path);
            return $url;
        }

        /**
         * _setShortenRequestPOSTContent
         * 
         * @access  protected
         * @param   string $longURL
         * @return  void
         */
        protected function _setShortenRequestPOSTContent(string $longURL): void
        {
            $postContent = array();
            $postContent['long_url'] = $longURL;
            $postContent = json_encode($postContent);
            $this->_setPOSTContent($postContent);
        }

        /**
         * _setShortenRequestURL
         * 
         * @access  protected
         * @return  void
         */
        protected function _setShortenRequestURL(): void
        {
            $url = $this->_getShortenRequestURL();
            $this->setURL($url);
        }

        /**
         * getShortURL
         * 
         * @access  public
         * @param   string $longURL
         * @return  null|string
         */
        public function getShortURL(string $longURL): ?string
        {
            $this->setRequestMethod('post');
            $this->setExpectedResponseContentType('application/json');
            $this->_setShortenRequestURL();
            $this->_setShortenRequestPOSTContent($longURL);
            $response = $this->_getURLResponse() ?? false;
            $shortURL = $response['link'] ?? null;
            if ($shortURL === null) {
                return null;
            }
            $shortURL = preg_replace('/^http\:/', 'https:', $shortURL);
            return $shortURL;
        }

        /**
         * setToken
         * 
         * @access  public
         * @param   string $token
         * @return  void
         */
        public function setToken(string $token): void
        {
            $this->_token = $token;
        }
    }
