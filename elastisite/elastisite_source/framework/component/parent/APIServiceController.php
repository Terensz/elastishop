<?php
namespace framework\component\parent;

// use framework\component\parent\RouteRendering;
use framework\kernel\utility\BasicUtils;
use framework\kernel\utility\FileHandler;
use framework\component\parent\JsonResponse;
use framework\component\exception\ElastiException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key as JWTKey;
use framework\packages\UserPackage\entity\User;
use framework\packages\UserPackage\repository\UserRepository;
use framework\packages\UserPackage\service\Permission;

class APIServiceController extends RouteRendering
{
    public $user;

    public $identityRepository;

    protected $controllerType = 'APIService';

    public $userVersion;

    public $authToken;

    const ERRORS = [
        'ERROR_AUTHENTICATION_REQUIRED' => [
            'code' => 10403,
            'translationReference' => 'error.authentication.required'
        ],
        'ERROR_AUTH_TOKEN_EXPIRED' => [
            'code' => 11061,
            'translationReference' => 'error.auth.token.expired'
        ],
        'ERROR_INVALID_AUTH_HEADER' => [
            'code' => 11062,
            'translationReference' => 'error.invalid.auth.header'
        ]
    ];

    public function __construct()
    {
        FileHandler::includeFileOnce('thirdparty/firebase/php-jwt/src/BeforeValidException.php', 'source');
        FileHandler::includeFileOnce('thirdparty/firebase/php-jwt/src/ExpiredException.php', 'source');
        FileHandler::includeFileOnce('thirdparty/firebase/php-jwt/src/SignatureInvalidException.php', 'source');
        FileHandler::includeFileOnce('thirdparty/firebase/php-jwt/src/Key.php', 'source');
        FileHandler::includeFileOnce('thirdparty/firebase/php-jwt/src/JWK.php', 'source');
        FileHandler::includeFileOnce('thirdparty/firebase/php-jwt/src/JWT.php', 'source');
        FileHandler::includeFileOnce('thirdparty/firebase/php-jwt/src/CachedKeySet.php', 'source');
    }

    public function processAuthHeader($authHeader)
    {
        $authHeaderParts = explode(' ', $authHeader);
        $this->authToken = isset($authHeaderParts[1]) ? $authHeaderParts[1] : null;
        $this->user = $this->identityRepository->authenticateByToken($this, $this->authToken, $this->getBrowserFingerprint());
    }

    public function checkPermission($requiredPermission)
    {
        $authHeader = $this->getContainer()->getRequest()->getHeader('Authentication');
        $this->processAuthHeader($authHeader);
        $this->getContainer()->wireService('UserPackage/service/Permission');
        // $permission = $this->getContainer()->getService('Permission');

        return Permission::check($requiredPermission, $this->user);
    }

    public function getAuthTokenData($authToken)
    {
        $data = JWT::decode($authToken, new JWTKey($this->getContainer()->getConfig()->getGlobal('jwt.secretKey'), $this->getContainer()->getConfig()->getGlobal('jwt.algorithm')));

        return $data;
    }

    public function createAuthToken($id, $loginData, $browserFingerprint)
    {
        // $jwt = new \Firebase\JWT\JWT();
        $secretKey = $this->getContainer()->getConfig()->getGlobal('jwt.secretKey');
        $date = (new \DateTimeImmutable());
        $data = array(
            'iat' => $date->getTimestamp(),
            'iss' => $this->getContainer()->getUrl()->getHttpDomain(),
            'exp' => $date->modify('+' . $this->getContainer()->getConfig()->getGlobal('jwt.validityMinutes') . ' minutes')->getTimestamp(),
            'id' => $id,
            'loginData' => $loginData,
            'browserFingerprint' => $browserFingerprint
        );

        return JWT::encode($data, $secretKey, $this->getContainer()->getConfig()->getGlobal('jwt.algorithm')); 
    }

    public static function getErrorCode($errorKey)
    {
        return isset(self::ERRORS[$errorKey]) ? self::ERRORS[$errorKey]['code'] : null;
    }

    // public function beforeAction($actionName)
    // {
    //     return true;
    // }

    public function successResult($data) {
        $response = [
            'result' => 'success',
            'data' => $data
        ];
        return new JsonResponse($response);
    }

    public function errorResult($errorTag) {
        // dump($errorTag);exit;
        if (isset(self::ERRORS[$errorTag])) {
            $errorDetails = [
                'code' => self::ERRORS[$errorTag]['code'],
                'tag' => $errorTag,
                'message' => trans(self::ERRORS[$errorTag]['translationReference'])
            ];
        } else {
            $errorDetails = [
                'code' => 30001,
                'tag' => 'MISSING_ERROR_CODE',
                'message' => 'missing.error.code'
            ];
        }

        $response = [
            'result' => 'failure',
            'errorDetails' => $errorDetails
        ];
        return new JsonResponse($response);
    }

    public function getBrowserFingerprint()
    {
        $request = $this->getContainer()->getRequest();
        $fingerprintBaseString = $this->getContainer()->getUrl()->getHttpDomain();

        if ($request->getHeader('User-Agent')) {
            $fingerprintBaseString .= hash('md4', $request->getHeader('User-Agent'));
        }

        $fingerprintBaseString .= $request->getHeader('Connection');
        $fingerprintBaseString .= $request->getHeader('Accept-Encoding');
        $fingerprintBaseString .= $request->getHeader('Accept-Language');

        return hash_hmac('sha224', $fingerprintBaseString, sha1($this->getContainer()->getConfig()->getGlobal('server.salt')));
    }
}
