<?php
class HausDesign_Service_Mobypicture_Exception extends Zend_Service_Exception
{
}

class HausDesign_Service_Mobypicture extends Zend_Rest_Client
{
    const SIZE_SMALL = 'small';
    const SIZE_MEDIUM = 'medium';
    const SIZE_THUMBNAIL = 'thumbnail';

    const FORMAT_JSON = 'json';
    const FORMAT_XML = 'xml';
    const FORMAT_PLAIN = 'plain';

    const MAX_LENGTH_TITLE = 512;
    const MAX_LENGTH_DESCRIPTION = 10000;

    const MOBYPICTURE_API = 'http://api.mobypicture.com';

    /**
     * Allowed sizes
     *
     * @var array
     */
    protected $_allowedSizes = array('small', 'medium', 'thumbnail');

    /**
     * Allowed formats
     *
     * @var array
     */
    protected $_allowedFormates = array('json', 'xml', 'plain');

    /**
     * Allowed object formats
     *
     * @var array
     */
    protected $_allowedObjectFormates = array('json', 'xml');

    /**
     * The Mobypicture API key
     *
     * @var String
     */
    protected $_apiKey = null;

    /**
     * The Mobypicture username
     *
     * @var string
     */
    protected $_username = null;

    /**
     * The Mobypicture password
     *
     * @var string
     */
    protected $_password = null;

    /**
     * Local HTTP Client cloned from statically set client
     * @var Zend_Http_Client
     */
    protected $_localHttpClient = null;


    public function __construct($apiKey, $username = null, $password = null){

        $this->setLocalHttpClient(clone self::getHttpClient());
        $this->_apiKey = $apiKey;
        $this->_username = $username;
        $this->_password = $password;
    }

    /**
     * Parse the content if necessary.
     *
     * @param string $content
     * @param mixded $format
     */
    protected function _parseContent($content, $format){
        switch($format){
            case self::FORMAT_XML:
                $parsed = simplexml_load_string($content);
                break;
            default:
                $parsed = $content;
        }

        return $parsed;
    }

    /**
     * Set local HTTP client as distinct from the static HTTP client
     * as inherited from Zend_Rest_Client.
     *
     * @param Zend_Http_Client $client
     * @return self
     */
    public function setLocalHttpClient(Zend_Http_Client $client){
        $this->_localHttpClient = $client;
        return $this;
    }


    /**
     * Reveille the location of a thumbnail
     *
     * @param string $code
     * @param string $size
     * @throws Zend_Http_Client_Exception if HTTP request fails or times out
     * @throws HausDesign_Service_Mobypicture_Exception if size is incorrect
     * @return mixed
     */
    public function getThumbUrl($code, $size = self::SIZE_THUMBNAIL){
        if(!in_array($size, $this->_allowedSizes))
            throw new HausDesign_Service_Mobypicture_Exception('Unkown size specified.');

        $this->_localHttpClient->resetParameters();
        $this->_localHttpClient->setParameterGet('action', 'getThumbUrl');
        $this->_localHttpClient->setParameterGet('t', $code);
        $this->_localHttpClient->setParameterGet('s', $size);
        $this->_localHttpClient->setParameterGet('k', $this->_apiKey);
        $this->_localHttpClient->setParameterGet('format', 'plain');
        $this->_localHttpClient->setUri(self::MOBYPICTURE_API);

        return $this->_localHttpClient->request('GET')->getBody();
    }

    /**
     * Get media from mobypicture.
     *
     * @param string $tinyurl
     * @param string $size
     * @throws Zend_Http_Client_Exception if HTTP request fails or times out
     * @throws HausDesign_Service_Mobypicture_Exception if size is incorrect
     * @return mixed
     */
    public function getThumb($code, $size = self::SIZE_THUMBNAIL, $url = 'http://moby.to/'){
        if(!in_array($size, $this->_allowedSizes))
            throw new HausDesign_Service_Mobypicture_Exception('Unkown size specified.');

        $this->_localHttpClient->resetParameters();
        $this->_localHttpClient->setParameterGet('action', 'getThumb');
        $this->_localHttpClient->setParameterGet('t', $url . $code);
        $this->_localHttpClient->setParameterGet('s', $size);
        $this->_localHttpClient->setParameterGet('k', $this->_apiKey);
        $this->_localHttpClient->setParameterGet('format', 'plain');
        $this->_localHttpClient->setUri(self::MOBYPICTURE_API);

        return $this->_localHttpClient->request('GET')->getBody();
    }

    /**
     * Returns information about a posting. Fields that are not available will be returned empty.
     *
     * @param string $code
     * @param string $format
     * @throws Zend_Http_Client_Exception if HTTP request fails or times out
     * @throws HausDesign_Service_Mobypicture_Exception if format is incorrect
     * @return mixed
     */
    public function getMediaInfo($code, $format = self::FORMAT_XML){
        if(!in_array($format, $this->_allowedFormates))
            throw new HausDesign_Service_Mobypicture_Exception('Unkown format specified.');

        $this->_localHttpClient->resetParameters();
        $this->_localHttpClient->setParameterGet('action', 'getMediaInfo');
        $this->_localHttpClient->setParameterGet('t', $code);
        $this->_localHttpClient->setParameterGet('k', $this->_apiKey);
        $this->_localHttpClient->setParameterGet('format', $format);
        $this->_localHttpClient->setUri(self::MOBYPICTURE_API);

        return $this->_parseContent($this->_localHttpClient->request('GET')->getBody(), $format);
    }

    /**
     * Checks whether the user is a valid Mobypicture user. Unless specified otherwise,
     * it will -in case of a non-existing user on Mobypicture- also check whether the supplied
     * credentials are belonging to a Twitter-user. If so, it will automatically create a Mobypicture for it.
     *
     * @param string $username
     * @param string $password
     * @throws Zend_Http_Client_Exception if HTTP request fails or times out
     * @throws HausDesign_Service_Mobypicture_Exception if format is incorrect
     * @return bool
     */
    public function checkCredentials($username = null, $password = null){
        if(null == $username)
            $username = $this->_username;

        if(null == $password)
            $password = $this->_password;

        $this->_localHttpClient->resetParameters();
        $this->_localHttpClient->setParameterGet('action', 'checkCredentials');
        $this->_localHttpClient->setParameterGet('u', $username);
        $this->_localHttpClient->setParameterGet('p', $password);
        $this->_localHttpClient->setParameterGet('k', $this->_apiKey);
        $this->_localHttpClient->setParameterGet('format', 'xml');
        $this->_localHttpClient->setUri(self::MOBYPICTURE_API);

        $response = simplexml_load_string($this->_localHttpClient->request('GET')->getBody());

        if(0 == (int) $response->result['code']){
            return true;
        }else{
            throw new HausDesign_Service_Mobypicture_Exception($response->result);
        }
    }

    /**
     * Retrieves all comments for a giving posting.
     *
     * @param string $code
     * @param int $postId
     * @param string $format
     * @throws Zend_Http_Client_Exception if HTTP request fails or times out
     * @throws HausDesign_Service_Mobypicture_Exception if both $code and $postId are null
     * @throws HausDesign_Service_Mobypicture_Exception if format is incorrect
     * @return mixed
     */
    public function getComments($code = null, $postId = null, $format = self::FORMAT_XML){
        if(null == $code && null == $postId)
            throw new HausDesign_Service_Mobypicture_Exception('One of the parameters should be added.');

        if(!in_array($format, $this->_allowedObjectFormates))
            throw new HausDesign_Service_Mobypicture_Exception('Unkown format specified.');


        $this->_localHttpClient->resetParameters();
        $this->_localHttpClient->setUri(self::MOBYPICTURE_API);
        $this->_localHttpClient->setParameterGet('action', 'getComments');

        if(null != $code)
            $this->_localHttpClient->setParameterGet('tinyurl_code ', $code);

        if(null != $postId)
            $this->_localHttpClient->setParameterGet('post_id ', $postId);

        $this->_localHttpClient->setParameterGet('k', $this->_apiKey);
        $this->_localHttpClient->setParameterGet('format', 'xml');

        return $this->_parseContent($this->_localHttpClient->request('GET')->getBody(), $format);
    }

    /**
     * Get the user details of a Mobypicture user.
     *
     * @param string $username
     * @param string $format
     * @throws Zend_Http_Client_Exception if HTTP request fails or times out
     * @throws HausDesign_Service_Mobypicture_Exception if format is incorrect
     * @return mixed
     */
    public function getUser($username, $format = self::FORMAT_XML){
        if(!in_array($format, $this->_allowedObjectFormates))
            throw new HausDesign_Service_Mobypicture_Exception('Unkown format specified.');

        $this->_localHttpClient->resetParameters();
        $this->_localHttpClient->setUri(self::MOBYPICTURE_API);
        $this->_localHttpClient->setParameterGet('k', $this->_apiKey);
        $this->_localHttpClient->setParameterGet('action', 'getUser');
        $this->_localHttpClient->setParameterGet('format', $format);
        $this->_localHttpClient->setParameterGet('u', $username);

        return $this->_parseContent($this->_localHttpClient->request('GET')->getBody(), $format);
    }

    /**
     * Get the friends of a Mobypicture user.
     *
     * @param string $username
     * @param string $format
     * @throws Zend_Http_Client_Exception if HTTP request fails or times out
     * @throws HausDesign_Service_Mobypicture_Exception if format is incorrect
     * @return mixed
     */
    public function getFriends($username, $format = self::FORMAT_XML){
        if(!in_array($format, $this->_allowedObjectFormates))
            throw new HausDesign_Service_Mobypicture_Exception('Unkown format specified.');

        $this->_localHttpClient->resetParameters();
        $this->_localHttpClient->setUri(self::MOBYPICTURE_API);
        $this->_localHttpClient->setParameterGet('k', $this->_apiKey);
        $this->_localHttpClient->setParameterGet('action', 'getFriends');
        $this->_localHttpClient->setParameterGet('format', $format);
        $this->_localHttpClient->setParameterGet('u', $username);

        return $this->_parseContent($this->_localHttpClient->request('GET')->getBody(), $format);
    }

    /**
     * Search Moby picture for media.
     *
     * @param string $term
     * @param array $options
     * @param string $format
     * @throws Zend_Http_Client_Exception if HTTP request fails or times out
     * @throws HausDesign_Service_Mobypicture_Exception if format is incorrect
     * @return mixed
     */
    public function searchPosts($term, $options = array(), $format = self::FORMAT_XML){
        if(!in_array($format, $this->_allowedObjectFormates))
            throw new HausDesign_Service_Mobypicture_Exception('Unkown format specified.');

        $defaults = array(
                        'searchItemsPerPage' => 10,
                        'searchPage'         => 1,
                        'searchIn'           => 'both',
                        'searchUsername'     => '',
                        'searchMediaPhoto'   => 1,
                        'searchMediaVideo'   => 1,
                        'searchMediaAudio'   => 1,
                        'searchGeoCountry'   => '',
                        'searchGeoCity'      => '',
                        'searchTags'         => '',
                        'searchSortBy'       => 'relevant',
                        'searchOrder'        => 'asc'
                    );

        $options = array_merge($defaults, $options);
        $options['searchTerms'] = $term;

        $this->_localHttpClient->resetParameters();
        $this->_localHttpClient->setUri(self::MOBYPICTURE_API);
        $this->_localHttpClient->setParameterGet('k', $this->_apiKey);
        $this->_localHttpClient->setParameterGet('action', 'searchPosts');
        $this->_localHttpClient->setParameterGet('format', $format);
        $this->_localHttpClient->setParameterGet('searchItemsPerPage', $options['searchItemsPerPage']);
        $this->_localHttpClient->setParameterGet('searchPage', $options['searchPage']);
        $this->_localHttpClient->setParameterGet('searchTerms', $options['searchTerms']);
        $this->_localHttpClient->setParameterGet('searchIn', $options['searchIn']);
        $this->_localHttpClient->setParameterGet('searchUsername', $options['searchUsername']);
        $this->_localHttpClient->setParameterGet('searchMediaPhoto', $options['searchMediaPhoto']);
        $this->_localHttpClient->setParameterGet('searchMediaVideo', $options['searchMediaVideo']);
        $this->_localHttpClient->setParameterGet('searchMediaAudio',$options['searchMediaAudio']);
        $this->_localHttpClient->setParameterGet('searchGeoCountry', $options['searchGeoCountry']);
        $this->_localHttpClient->setParameterGet('searchGeoCity', $options['searchGeoCity']);
        $this->_localHttpClient->setParameterGet('searchTags', $options['searchTags']);
        $this->_localHttpClient->setParameterGet('searchSortBy', $options['searchSortBy']);
        $this->_localHttpClient->setParameterGet('searchOrder', $options['searchOrder']);

        return $this->_parseContent($this->_localHttpClient->request('GET')->getBody(), $format);
    }

    /**
     * Add an media file to mobypicture.
     *
     * @param string $filepath
     * @param string $title
     * @param string $description
     * @param string $format
     * @param array $options
     * @throws Zend_Http_Client_Exception if HTTP request fails or times out
     * @throws HausDesign_Service_Mobypicture_Exception If username is not set
     * @throws HausDesign_Service_Mobypicture_Exception If file can't read.
     * @throws HausDesign_Service_Mobypicture_Exception If file is larger then 16M.
     * @return mixed
     */
    public function postMedia($filepath, $title, $description = '', $format = self::FORMAT_XML, $options = array()){
        if(null == $this->_username || null == $this->_password)
            throw new HausDesign_Service_Mobypicture_Exception('Username and password must be set.');

        if(!is_readable($filepath))
            throw new HausDesign_Service_Mobypicture_Exception('File can\'t be read.');

        if(filesize($filepath) > 16777216)
            throw new HausDesign_Service_Mobypicture_Exception('File can\'t be larger then 16M.');

        if(strlen($title) > self::MAX_LENGTH_TITLE)
            $title = substr($title, 0, self::MAX_LENGTH_TITLE);

        if(strlen($description) > self::MAX_LENGTH_DESCRIPTION)
            $title = substr($title, 0, self::MAX_LENGTH_DESCRIPTION);

        $options['t'] = $title;

        if($description)
            $options['d'] = $description;

        $this->_localHttpClient->resetParameters();
        $this->_localHttpClient->setUri(self::MOBYPICTURE_API);
        $this->_localHttpClient->setParameterPost('action', 'postMediaUrl');
        $this->_localHttpClient->setFileUpload($filepath, 'i');
        $this->_localHttpClient->setParameterPost('u', $this->_username);
        $this->_localHttpClient->setParameterPost('p', $this->_password);
        $this->_localHttpClient->setParameterPost('k', $this->_apiKey);
        $this->_localHttpClient->setParameterPost('format', $format);
        $this->_localHttpClient->setConfig(array('timeout' => 30));
        foreach($options as $option => $value){
            $this->_localHttpClient->setParameterPost($option, $value);
        }

        return $this->_parseContent($this->_localHttpClient->request('GET')->getBody(), $format);
    }

    /**
     * Post a comment.
     *
     * @param string $code
     * @param string $message
     * @param array $options
     * @param string $format
     */
    public function postComment($code, $message, $options = array(), $format = self::FORMAT_XML){

        $defaults = array(
                        'name'               => '',
                        'email'              => '',
                        'tweet'              => 0
                    );

        $options = array_merge($defaults, $options);

        if(!strlen($options['name']) && !strlen($options['email']) ){
            if(null == $this->_username && null == $this->_password){
                throw new HausDesign_Service_Mobypicture_Exception('name and email or username and password should by defined');
            }
        }

        $this->_localHttpClient->resetParameters();
        $this->_localHttpClient->setUri(self::MOBYPICTURE_API);
        $this->_localHttpClient->setParameterGet('action', 'postComment');
        if(!strlen($options['name']) && !strlen($options['email'])){
            $this->_localHttpClient->setParameterGet('u', $this->_username);
            $this->_localHttpClient->setParameterGet('p', $this->_password);
        }
        $this->_localHttpClient->setParameterGet('k', $this->_apiKey);
        $this->_localHttpClient->setParameterGet('tinyurl_code ', $code);
        $this->_localHttpClient->setParameterGet('message', $code);
        $this->_localHttpClient->setParameterGet('format', $format);
        foreach($options as $option => $value){
            $this->_localHttpClient->setParameterGet($option, $value);
        }

        return $this->_parseContent($this->_localHttpClient->request('GET')->getBody(), $format);
    }
}